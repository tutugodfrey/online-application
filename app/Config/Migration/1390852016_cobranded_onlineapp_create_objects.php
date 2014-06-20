<?php
class CobrandedOnlineappCreateObjects extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'onlineapp_cobrands' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary'
					),
					'partner_name' => array(
						'type' => 'string',
						'null' => false,
					),
					'partner_name_short' => array(
						'type' => 'string',
						'null' => false,
					),
					'logo_url' => array(
						'type' => 'string',
						'null' => true,
					),
					'description' => array(
						'type' => 'text',
						'null' => true,
					),
					'created' => array(
						'type' => 'datetime',
						'null' => false,
					),
					'modified' => array(
						'type' => 'datetime',
						'null' => false,
					),
					'indexes' => array(
						'PRIMARY' => array(
						'column' => 'id',
						'unique' => 1,
						)
					)
				),
				'onlineapp_templates' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary',
					),
					'name' => array(
						'type' => 'string',
						'null' => false,
					),
					'logo_position' => array(
						'type' => 'integer',
						'null' => false,
						'default' => 3
					),
					'include_axia_logo' => array(
						'type' => 'boolean',
						'null' => false,
						'default' => true,
					),
					'description' => array(
						'type' => 'text',
						'null' => true,
					),
					'cobrand_id' => array(
						'type' => 'integer',
						'null' => true,
					),
					'created' => array(
						'type' => 'datetime',
						'null' => false,
					),
					'modified' => array(
						'type' => 'datetime',
						'null' => false,
					),
					'indexes' => array(
						'PRIMARY' => array(
						'column' => 'id',
						'unique' => 1,
						),
					),
				),
				'onlineapp_template_pages' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary'
					),
					'name' => array(
						'type' => 'string',
						'null' => false
					),
					'description' => array(
						'type' => 'text',
						'null' => true
					),
					'rep_only' => array(
						'type' => 'boolean',
						'null' => false,
						'default' => false,
					),
					'template_id' => array(
						'type' => 'integer',
						'null' => false
					),
					'order' => array(
						'type' => 'integer',
						'null' => false
					),
					'created' => array(
						'type' => 'datetime',
						'null' => false
					),
					'modified' => array(
						'type' => 'datetime',
						'null' => false
					),
					'indexes' => array(
						'PRIMARY' => array(
						'column' => 'id',
						'unique' => 1
						),
					),
				),
				'onlineapp_template_sections' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary'
					),
					'name' => array(
						'type' => 'string',
						'null' => false
					),
					'description' => array(
						'type' => 'text',
						'null' => true
					),
					'rep_only' => array(
						'type' => 'boolean',
						'null' => false,
						'default' => false,
					),
					'width' => array(
						'type' => 'integer',
						'null' => false,
						'default' => 12 /* 1 - 12 columns, using bootstrap layout */
					),
					'page_id' => array(
						'type' => 'integer',
						'null' => false
					),
					'order' => array(
						'type' => 'integer',
						'null' => false
					),
					'created' => array(
						'type' => 'datetime',
						'null' => false
					),
					'modified' => array(
						'type' => 'datetime',
						'null' => false
					),
					'indexes' => array(
						'PRIMARY' => array(
						'column' => 'id',
						'unique' => 1
						),
					),
				),
				'onlineapp_template_fields' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary'
					),
					'name' => array(
						'type' => 'string',
						'null' => false
					),
					'description' => array(
						'type' => 'text',
						'null' => true
					),
					'rep_only' => array(
						'type' => 'boolean',
						'null' => false,
						'default' => false,
					),
					'width' => array(
						'type' => 'integer',
						'null' => false,
						'default' => 12 /* 1 - 12 columns, using bootstrap layout */
					),
					'type' => array(
						'type' => 'integer',
						'null' => false
					),
					'required' => array(
						'type' => 'boolean',
						'null' => false,
						'default' => false
					),
					'source' => array(
						'type' => 'integer',
						'null' => false
					),
					'default_value' => array(
						'type' => 'text',
						'null' => true
					),
					'merge_field_name' => array(
						'type' => 'string',
						'null' => true
					),
					'order' => array(
						'type' => 'integer',
						'null' => false
					),
					'section_id' => array(
						'type' => 'integer',
						'null' => false
					),
					'created' => array(
						'type' => 'datetime',
						'null' => false
					),
					'modified' => array(
						'type' => 'datetime',
						'null' => false
					),
					'indexes' => array(
						'PRIMARY' => array(
							'column' => 'id',
							'unique' => 1
						),
					),
				),
				'onlineapp_cobranded_applications' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary',
					),
					'user_id' => array(
						'type' => 'integer',
						'unll' => false,
					),
					'template_id' => array(
						'type' => 'integer',
						'unll' => false,
					),
					'uuid' => array(
						'type' => 'string',
						'null' => false,
						'length' => 36,
					),
					'created' => array(
						'type' => 'datetime',
						'null' => false,
					),
					'modified' => array(
						'type' => 'datetime',
						'null' => false,
					),
					'indexes' => array(
						'PRIMARY' => array(
							'column' => 'id',
							'unique' => 1,
						),
						'UNIQUE_UUID' => array(
							'column' => 'uuid',
							'unique' => 1,
						),
					),
				),
				'onlineapp_cobranded_application_values' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary',
					),
					'cobranded_application_id' => array(
						'type' => 'integer',
						'null' => false,
					),
					'template_field_id' => array(
						'type' => 'integer',
						'null' => false,
					),
					'name' => array(
						'type' => 'string',
						'null' => false,
					),
					'value' => array(
						'type' => 'string',
					),
					'created' => array(
						'type' => 'datetime',
						'null' => false,
					),
					'modified' => array(
						'type' => 'datetime',
						'null' => false,
					),
					'indexes' => array(
						'PRIMARY' => array(
							'column' => 'id',
							'unique' => 1,
						),
					),
				),
			),
			'create_field' => array(
				'onlineapp_users' => array(
					'cobrand_id' => array(
						'type' => 'integer',
						'null' => true,
					),
					'template_id' => array(
						'type' => 'integer',
						'null' => true,
					),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_users' => array(
					'cobrand_id',
					'template_id'
				),
			),
			'drop_table' => array(
				'onlineapp_cobranded_application_values',
				'onlineapp_cobranded_applications',
				'onlineapp_template_fields',
				'onlineapp_template_sections',
				'onlineapp_template_pages',
				'onlineapp_templates',
				'onlineapp_cobrands',
			),
		)
	);


/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		if ($direction == 'down') {
			$this->db->execute(
				"ALTER TABLE onlineapp_users
				DROP CONSTRAINT IF EXISTS onlineapp_users_cobrand_fk CASCADE;
				ALTER TABLE onlineapp_users
				DROP CONSTRAINT IF EXISTS onlineapp_users_template_fk CASCADE;
				ALTER TABLE onlineapp_templates
				DROP CONSTRAINT IF EXISTS onlineapp_template_cobrand_fk CASCADE;
				ALTER TABLE onlineapp_template_pages
				DROP CONSTRAINT IF EXISTS onlineapp_template_page_template_fk CASCADE;
				ALTER TABLE onlineapp_template_sections
				DROP CONSTRAINT IF EXISTS onlineapp_template_section_page_fk CASCADE;
				ALTER TABLE onlineapp_template_fields
				DROP CONSTRAINT IF EXISTS onlineapp_template_field_section_fk CASCADE;
				ALTER TABLE onlineapp_cobranded_application_values
				DROP CONSTRAINT IF EXISTS onlineapp_cobranded_applications_applications_values_fk CASCADE;
				ALTER TABLE onlineapp_cobranded_application_values
				DROP CONSTRAINT IF EXISTS onlineapp_template_fields_applications_values_fk CASCADE;
				ALTER TABLE onlineapp_cobranded_applications
				DROP CONSTRAINT onlineapp_users_cobranded_applications_fk;
				ALTER TABLE onlineapp_cobranded_applications
				DROP CONSTRAINT onlineapp_templates_cobranded_applications_fk;"
			);
		}
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		if ($direction == 'up') {
			// add the constraints and seed some data
			$this->db->execute(
				"ALTER TABLE onlineapp_users
				ADD CONSTRAINT onlineapp_users_cobrand_fk FOREIGN KEY (cobrand_id) REFERENCES onlineapp_cobrands (id) ;
				ALTER TABLE onlineapp_users
				ADD CONSTRAINT onlineapp_users_template_fk FOREIGN KEY (template_id) REFERENCES onlineapp_templates (id) ON DELETE SET NULL;
				ALTER TABLE onlineapp_templates
				ADD CONSTRAINT onlineapp_template_cobrand_fk FOREIGN KEY (cobrand_id) REFERENCES onlineapp_cobrands (id);
				ALTER TABLE onlineapp_template_pages
				ADD CONSTRAINT onlineapp_template_page_template_fk FOREIGN KEY (template_id) REFERENCES onlineapp_templates (id);
				ALTER TABLE onlineapp_template_sections
				ADD CONSTRAINT onlineapp_template_section_page_fk FOREIGN KEY (page_id) REFERENCES onlineapp_template_pages (id);
				ALTER TABLE onlineapp_template_fields
				ADD CONSTRAINT onlineapp_template_field_section_fk FOREIGN KEY (section_id) REFERENCES onlineapp_template_sections (id);
				ALTER TABLE onlineapp_cobranded_application_values
				ADD CONSTRAINT onlineapp_cobranded_applications_applications_values_fk FOREIGN KEY (cobranded_application_id) REFERENCES onlineapp_cobranded_applications (id);
				ALTER TABLE onlineapp_cobranded_application_values
				ADD CONSTRAINT onlineapp_template_fields_applications_values_fk FOREIGN KEY (template_field_id) REFERENCES onlineapp_template_fields (id);
				ALTER TABLE onlineapp_cobranded_applications
				ADD CONSTRAINT onlineapp_users_cobranded_applications_fk FOREIGN KEY (user_id) REFERENCES onlineapp_users (id);
				ALTER TABLE onlineapp_cobranded_applications
				ADD CONSTRAINT onlineapp_templates_cobranded_applications_fk FOREIGN KEY (template_id) REFERENCES onlineapp_templates (id);

				INSERT INTO onlineapp_cobrands
				(partner_name, partner_name_short, logo_url, created, modified) VALUES ('A Charity for Charities', 'ACFC', 'TODO: add ACFC logo', current_timestamp, current_timestamp);
				INSERT INTO onlineapp_cobrands
				(partner_name, partner_name_short, logo_url, created, modified) VALUES ('Axia', 'AX', '/img/axia_logo.png', current_timestamp, current_timestamp);
				INSERT INTO onlineapp_cobrands
				(partner_name, partner_name_short, logo_url, created, modified) VALUES ('Inspire', 'IN', 'TODO: add IN logo', current_timestamp, current_timestamp);
				INSERT INTO onlineapp_cobrands
				(partner_name, partner_name_short, logo_url, created, modified) VALUES ('Passport', 'PP', 'TODO: add PP logo', current_timestamp, current_timestamp);
				INSERT INTO onlineapp_cobrands
				(partner_name, partner_name_short, logo_url, created, modified) VALUES ('PaymentSpring', 'PS', 'TODO: add PS logo', current_timestamp, current_timestamp);
				INSERT INTO onlineapp_cobrands
				(partner_name, partner_name_short, logo_url, created, modified) VALUES ('Shortcut', 'SC', 'TODO: add SC logo', current_timestamp, current_timestamp);
				INSERT INTO onlineapp_cobrands
				(partner_name, partner_name_short, logo_url, created, modified) VALUES ('Appfolio', 'AF', 'TODO: add AF logo', current_timestamp, current_timestamp);
				ALTER SEQUENCE onlineapp_cobranded_applications_id_seq RESTART WITH 5001;"
			);
		}
		return true;
	}
}
