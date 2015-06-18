<?php
class OnlineappUpdateNameFromEmvToEmv2 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'onlineapp_update_name_from_emv_to_emv2';

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
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		if ($direction == 'up') {
                        $this->db->execute(
				"UPDATE onlineapp_cobranded_application_values
   				    SET name = 'EMV2-Yes'
 				  WHERE template_field_id in (
     					SELECT cav.template_field_id
       					  FROM onlineapp_cobranded_application_values cav
       					  JOIN onlineapp_template_fields otf ON cav.template_field_id = otf.id
       					  JOIN onlineapp_template_sections ots on otf.section_id = ots.id
      					 WHERE cav.name = 'EMV-Yes'
        				   AND ots.name ilike 'Terminal/Software Type(2)'
				  )
   				  AND name = 'EMV-Yes';"
			);
                        $this->db->execute(
				"UPDATE onlineapp_cobranded_application_values
   				    SET name = 'EMV2-No'
 				  WHERE template_field_id in (
     					SELECT cav.template_field_id
       					  FROM onlineapp_cobranded_application_values cav
       					  JOIN onlineapp_template_fields otf ON cav.template_field_id = otf.id
       					  JOIN onlineapp_template_sections ots on otf.section_id = ots.id
      					 WHERE cav.name = 'EMV-No'
        				   AND ots.name ilike 'Terminal/Software Type(2)'
				  )
   				  AND name = 'EMV-No';"
			);
                }

		if ($direction == 'down') {
                        $this->db->execute(
				"UPDATE onlineapp_cobranded_application_values
   				    SET name = 'EMV-Yes'
 				  WHERE template_field_id in (
     					SELECT cav.template_field_id
       					  FROM onlineapp_cobranded_application_values cav
       					  JOIN onlineapp_template_fields otf ON cav.template_field_id = otf.id
       					  JOIN onlineapp_template_sections ots on otf.section_id = ots.id
      					 WHERE cav.name = 'EMV2-Yes'
        				   AND ots.name ilike 'Terminal/Software Type(2)'
				  )
   				  AND name = 'EMV2-Yes';"
			);
                        $this->db->execute(
				"UPDATE onlineapp_cobranded_application_values
   				    SET name = 'EMV-No'
 				  WHERE template_field_id in (
     					SELECT cav.template_field_id
       					  FROM onlineapp_cobranded_application_values cav
       					  JOIN onlineapp_template_fields otf ON cav.template_field_id = otf.id
       					  JOIN onlineapp_template_sections ots on otf.section_id = ots.id
      					 WHERE cav.name = 'EMV2-No'
        				   AND ots.name ilike 'Terminal/Software Type(2)'
				  )
   				  AND name = 'EMV2-No';"
			);
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
		return true;
	}
}
