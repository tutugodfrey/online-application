<?php
App::uses('RightSignature', 'Model');
App::uses('CobrandedApplication', 'Model');
App::uses('Template', 'Model');
class UpdateRSTemplateGUIDsWithNewUUIDs extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'update_RS_template_GUIDs_with_new_UUIDs';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_templates' => array(
					'old_template_guid' => array(
						'type' => 'string',
						'length' => 50
					),
					'secondary_rightsignature_template_id' => array(
						'type' => 'string',
						'length' => 50
					),

				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_templates' => array(
					'old_template_guid',
					'secondary_rightsignature_template_id'
				)
			)
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function before($direction) {
		if ($direction === 'down') {
			$Template = ClassRegistry::init('Template');
			$Template->query('UPDATE onlineapp_templates set rightsignature_template_guid = old_template_guid');
		}
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		if ($direction == 'up') {
			$Template = ClassRegistry::init('Template');
			$CobrandedApplication = ClassRegistry::init('CobrandedApplication');
			//Back up old template guids
			$Template->query('UPDATE onlineapp_templates set old_template_guid = rightsignature_template_guid');
			$client = $CobrandedApplication->createRightSignatureClient();
			$results = $CobrandedApplication->getRightSignatureTemplates($client);
			if (!empty(Hash::get($results, 'error'))) {
				throw new Exception('API Error: failed to get template list ' . Hash::get($results, 'error'));
			}

			$installTemplateUuid = null;

			foreach ($results as $rsUuid => $filename) {
				$templateMeta = [
					'old_rs_guid' => null,
					'new_rs_uuui' => $rsUuid,
					'set_as_secondary_template' => null,
				];

				if (empty($installTemplateUuid) && preg_match('/install/i', $filename)) {
						$installTemplateUuid = $rsUuid;
				} else {
					//Get All Template Details
					$templateData = $CobrandedApplication->getRightSignatureTemplate($client, $rsUuid);
					$templateData = json_decode($templateData, true);
					//if error try one more time, access token could expire during run time
					if (!empty(Hash::get($templateData, 'error'))) {
						echo "Error getting template data ". Hash::get($templateData, 'error').", trying again...\n";
						$client = $CobrandedApplication->createRightSignatureClient();
						$templateData = $CobrandedApplication->getRightSignatureTemplate($client, $rsUuid);
						$templateData = json_decode($templateData, true);
					}
					if (empty(Hash::get($templateData, 'error'))) {
						foreach ($templateData['reusable_template']['merge_field_components'] as $mergeField) {
							//old_guid is a mergefield manually added to all templates for migration purposes and it contains the old GUID reference
							if ($mergeField['name'] == 'old_guid') {
								$templateMeta['old_rs_guid'] = Hash::get($mergeField, 'metadata.value');
								break;
							}
						}
						//Check the number of roles there is never just 3 roles
						$roleCount = count($templateData['reusable_template']['roles']);
						//if roleCount > 2 then set_as_secondary_template = true always.
						if ($roleCount > 2) {
							$templateMeta['set_as_secondary_template'] = true;
						} elseif ($roleCount == 2) {
							//if roleCount = 2 roles then then set_as_secondary_template = true IFF any of the roles names contains the number 2 as in Owner/Officer 2 
							$templateMeta['set_as_secondary_template'] = false;
							$aggRoleName = $templateData['reusable_template']['roles'][0]['name'] . ' ' .$templateData['reusable_template']['roles'][1]['name'];

							if (strpos($aggRoleName, '2') !== false) {
								$templateMeta['set_as_secondary_template'] = true;
							}
						} elseif ($roleCount == 1) {
							//if roleCount = 1 role then set_as_secondary_template = false
							$templateMeta['set_as_secondary_template'] = false;
						}

					} else {
						throw new Exception('API Error: failed to get template details ' . Hash::get($templateData, 'error'));
					}

				}

				if (!empty($templateMeta['old_rs_guid'])) {
					if ($templateMeta['set_as_secondary_template']) {
					 	$fieldName = 'secondary_rightsignature_template_id';
					} else  {
						$fieldName = 'rightsignature_template_guid';
					}
					$Template->updateAll(
						[$fieldName => "'". $templateMeta['new_rs_uuui'] ."'"],
						['rightsignature_template_guid' => $templateMeta['old_rs_guid']]
					);
					//Use the fieldName in the Update statement.
				} else {
					echo "RS Template with uuid= $rsUuid does not have 'old_guid' merge_field, could not update associated onlineapp_templates record.\n\n";
				}
			}
			
			// assign the new UUID to all templates install template guids where not null
			if (!empty($installTemplateUuid)) {
				$Template->updateAll(
					['rightsignature_template_guid' => "'". $installTemplateUuid ."'"],
					['rightsignature_template_guid' => $templateMeta['old_rs_guid']]
				);
			}

		}
		return true;
	}
}
