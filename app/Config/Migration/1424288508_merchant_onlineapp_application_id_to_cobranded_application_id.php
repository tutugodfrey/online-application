<?php
class MerchantOnlineappApplicationIdToCobrandedApplicationId extends CakeMigration {

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
		if ($direction == 'down') {
			$this->db->execute('BEGIN;');
			$this->db->execute('UPDATE merchant set onlineapp_application_id = cobranded_application_id where onlineapp_application_id is not null;');
			$this->db->execute('UPDATE merchant set cobranded_application_id = null;');
			$this->db->execute('END;');
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
		if ($direction == 'up') {
			$this->db->execute('BEGIN;');
			$this->db->execute('UPDATE merchant set cobranded_application_id = onlineapp_application_id where onlineapp_application_id is not null;');
			$this->db->execute('UPDATE merchant set onlineapp_application_id = null;');
			$this->db->execute('END;');
		}
		return true;
	}
}
