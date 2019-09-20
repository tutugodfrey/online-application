<?php
App::uses('EventType', 'Model');
class CreateEventLogsTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'create_event_logs_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'onlineapp_sys_event_logs' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary'
					),
					'user_id' => array(
						'type' => 'integer',
						'null' => false,
					),
					'event_type_id' => array(
						'type' => 'integer',
						'null' => false,
					),
					'client_ip' => array(
						'type' => 'string',
						'length' => '100',
					),
					'created' => array(
						'type' => 'datetime',
						'default' => null,
					),
					'indexes' => array(
						'PRIMARY' => array(
							'column' => 'id',
							'unique' => 1,
						),
						'ONLINEAPP_SYS_EVENT_LOGS_USER_ID' => array(
							'column' => 'user_id',
						),
					)
				),
				'onlineapp_event_types' => array(
					'id' => array(
						'type' => 'integer',
						'null' => false,
						'key' => 'primary'
					),
					'description' => array(
						'type' => 'string',
						'null' => false,
						'length' => '100'
					),
					'indexes' => array(
						'PRIMARY' => array(
							'column' => 'id',
							'unique' => 1,
						)
					)
				)
			)
		),
		'down' => array(
			'drop_table' => array(
				'onlineapp_sys_event_logs',
				'onlineapp_event_types'
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
			$EventType = $this->generateModel('EventType');
			if (!$EventType->save(array('id' => EventType::USER_LOG_IN_ID, 'description' => 'user logged in'))) {
				echo '*** ERROR: Failed to save event type ***';
				return false;
			}
		}
		return true;
	}
}
