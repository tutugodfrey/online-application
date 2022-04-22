<?php
class BuildIndexes extends CakeMigration {

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
			$this->db->execute(file_get_contents(APP . 'Config' . DS . 'SQL' . DS . 'drop_index.sql'));
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
			$this->db->execute(file_get_contents(APP . 'Config' . DS . 'SQL' . DS . 'create_index.sql'));
			$this->db->execute('END;');
		}
		return true;
	}
}
