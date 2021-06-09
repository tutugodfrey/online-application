<?php
class CreateChargebackNotificationTemplateFields extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'create_chargeback_notification_template_fields';

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
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		$TemplateField = ClassRegistry::init('TemplateField');

		if ($direction === 'up') {
			//the general comment fields are a reference point within the section where we want
			//the new fields to be placed
			$generalCommentFields = $TemplateField->find('all',[
				'recursive'=> -1,
				'conditions' => ["name LIKE '%General Comments%'"],
			]);
			foreach($generalCommentFields as &$fieldMeta) {
				$newFields['TemplateField'] = [					
					'name' => 'Chargeback notification settings',
					'rep_only' => false,
					'width' => 6,
					'type' => 4,
					'required' => false,
					'source' => 2,
					'default_value' => 'Mail/Fax::MailFax,Email::Email,Mail/Fax and Email::MailFaxEmail',
					'merge_field_name' => 'ChargebkNotifyBy-',
					'order' => $fieldMeta['TemplateField']['order'],
					'section_id' => $fieldMeta['TemplateField']['section_id'],
					'encrypt' => false,
				];
				$TemplateField->clear();
				$TemplateField->save($newFields,['callbacks' => true]);
				$newFields['TemplateField'] = [
					'name' => 'Chargeback email',
					'rep_only' => false,
					'width' => 6,
					'type' => 14,
					'required' => false,
					'source' => 2,
					'default_value' => null,
					'merge_field_name' => 'ChargebkEmail',
					'order' => $fieldMeta['TemplateField']['order'] + 1,
					'section_id' => $fieldMeta['TemplateField']['section_id'],
					'encrypt' => false,
				];
				$TemplateField->clear();
				$TemplateField->save($newFields,['callbacks' => true]);
				//shift UI display order by two since we want the two new fields to be before this old one
				$fieldMeta['TemplateField']['order'] = $fieldMeta['TemplateField']['order'] + 2;
				$TemplateField->clear();
				$TemplateField->save($fieldMeta,['callbacks' => true]);
			}
		} else {
			//Remove unsynchronized data
			$TemplateField->query("UPDATE onlineapp_cobranded_applications set data_to_sync = null where data_to_sync like '%Chargebk%'");
			//Remove any synchronized data
			$TemplateField->query("DELETE FROM onlineapp_cobranded_application_values where name like '%Chargebk%';");
			//Restore the position of the relative field
			$TemplateField->query("UPDATE onlineapp_template_fields  set \"order\" = \"order\" - 2 WHERE name LIKE '%General Comments%' and section_id in (SELECT distinct section_id from onlineapp_template_fields WHERE merge_field_name LIKE '%Chargebk%')");
			//remove chargeback notification fields created in direction == up
			$TemplateField->query("DELETE FROM onlineapp_template_fields WHERE merge_field_name LIKE '%Chargebk%'");
		}
		return true;
	}
}
