<?php
App::uses('EmailTimeline', 'Model');
class AddApiConfigurationsTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_api_configurations_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'onlineapp_api_configurations' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary'
					),
					'configuration_name' => array(
						'type' => 'string',
						'length' => '150',
						'null' => false,
					),
					'auth_type' => array(
						'type' => 'string',
						'length' => '150',
					),
					'instance_url' => array(
						'type' => 'string',
						'length' => '250',
					),
					'authorization_url' => array(
						'type' => 'string',
						'length' => '250',
					),
					'access_token_url' => array(
						'type' => 'string',
						'length' => '250',
					),
					'redirect_url' => array(
						'type' => 'string',
						'length' => '250',
					),
					'client_id' => array(
						'type' => 'string',
						'length' => '250',
					),
					'client_secret' => array(
						'type' => 'string',
						'length' => '250',
					),
					'access_token' => array(
						'type' => 'string',
						'length' => '250',
					),
					'access_token_lifetime_seconds' => array(
						'type' => 'integer',
					),
					'refresh_token' => array(
						'type' => 'string',
						'length' => '250',
					),
					'issued_at' => array(
						'type' => 'string',
						'length' => '100',
					),
					'indexes' => array(
						'PRIMARY' => array(
							'column' => 'id',
							'unique' => 1
						),
						'ONLINEAPP_API_CONFIG_NAME' => array(
							'column' => 'configuration_name',
							'unique' => 1
						),
					)
				)
			)
		),
		'down' => array(
			'drop_table' => array(
				'onlineapp_api_configurations'
			)
		),
	);


/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		if ($direction === 'up') {
			$EmailTimelineSubject = $this->generateModel('EmailTimelineSubject');
			ClassRegistry::init('EmailTimeline');
			if (!$EmailTimelineSubject->save(array('id' => EmailTimeline::APP_SENT_TO_UW, 'subject' => 'Application Sent to Underwriting'))) {
				echo '*** ERROR: Failed to save new EmailTimelineSubject ***';
				return false;
			}
		}
		return true;
	}
}