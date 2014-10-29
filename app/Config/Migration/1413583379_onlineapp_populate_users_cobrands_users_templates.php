<?php
class OnlineappPopulateUsersCobrandsUsersTemplates extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function before($direction) {
		if ($direction == 'up') {
			$User = ClassRegistry::init('User');
			$Template = ClassRegistry::init('Template');
			$UserCobrand = ClassRegistry::init('UserCobrand');
			$UserTemplate = ClassRegistry::init('UserTemplate');

			$users = $User->find(
				'list',
				array(
					'fields' => array(
						'id',
						'template_id'
					)
				)
			);

			foreach ($users as $userId => $templateId) {
				$template = $Template->find(
					'list',
					array(
						'fields' => array(
							'id',
							'cobrand_id'
						),
						'conditions' => array(
							'Template.id' => $templateId
						)
					)
				);

				$key = key($template);
				$cobrandId = $template[$key];

				$exists = $UserCobrand->find(
					'first',
					array (
						'conditions' => array(
							'user_id' => $userId,
							'cobrand_id' => $cobrandId,
						)
					)
				);

				if (!$exists) {
					$UserCobrand->create(
						array(
							'user_id' => $userId,
							'cobrand_id' => $cobrandId,
						)
					);

					$UserCobrand->save();
				}

				$exists = $UserTemplate->find(
					'first',
					array (
						'conditions' => array(
							'user_id' => $userId,
							'template_id' => $templateId,
						)
					)
				);

				if (!$exists) {
					$UserTemplate->create(
						array(
							'user_id' => $userId,
							'template_id' => $templateId,
						)
					);

					$UserTemplate->save();
				}
			}
		}

		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function after($direction) {
		return true;
	}
}
