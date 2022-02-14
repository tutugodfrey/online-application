<?php
class AddFieldsRetoUserTableRequiredForLockOut extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_fields_reto_user_table_required_for_lock_out';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'onlineapp_users' => array(
                    'wrong_log_in_count' => array('type' => 'integer', 'default' => 0),
                    'is_blocked' => array('type' => 'boolean', 'default' => false)
                )
            )
        ),
        'down' => array(
            'drop_field' => array(
                'onlineapp_users' => array(
                    'wrong_log_in_count',
                    'is_blocked',
                )
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
        $User = $this->generateModel('User');
        if ($direction == 'up') {
            $User->updateAll(
                array('is_blocked' => true),
                array('active' => false)
            );
        }
        return true;
    }

}
