<?php
class AddColumnsToApplicationGroups extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_columns_to_application_groups';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
            'create_field' => array(
                'onlineapp_application_groups'=> array(
                    'client_access_token' => array('type' => 'string', 'length' => 40),
                    'client_password' => array('type' => 'string', 'length' => 200),
                    'client_pw_expiration' => array('type' => 'date'),
                    'client_fail_login_count' => array('type' => 'integer', 'default' => 0),
                    'client_account_locked' => array('type' => 'boolean', 'default' => false)
                )
            )
		),
		'down' => array(
            'drop_field' => array(
                'onlineapp_application_groups'=> array(
                    'client_access_token',
                    'client_password',
                    'client_pw_expiration',
                    'client_fail_login_count',
                    'client_account_locked',
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
        if ($direction === 'up') {
            $CobrandedApplication = ClassRegistry::init('CobrandedApplication');
            $ApplicationGroup = ClassRegistry::init('ApplicationGroup');
            $User = ClassRegistry::init('User');
            
            //get all applications that are not grouped and create a group for all of them
            $ungrouped = $CobrandedApplication->find('all',array(
                'recursive' => -1,
                'fields' => array('id'),
                'conditions' => array(
                    "application_group_id IS NULL",
                    'OR' => array(
                        "created >= '2021-01-01'",
                        "modified >= '2021-01-01'",
                    )
                )
            ));
            foreach ($ungrouped as $app) {
                $CobrandedApplication->addAppToGroup($app['CobrandedApplication']['id']);
            }

            //update existing groups do not update created/modified date
            $existingGroups = $ApplicationGroup->find('all',array(
                'recursive' => -1,
                'conditions' => array(
                    'client_access_token IS NULL',
                )
            ));
            foreach($existingGroups as $appGroup) {
                //generate client credentials
                $appGroup['ApplicationGroup']['client_access_token'] = $ApplicationGroup->genRandomSecureToken(20);
                $appGroup['ApplicationGroup']['client_password'] =  $User->encrypt($User->generateRandPw(), Configure::read('Security.OpenSSL.key'));
                $appGroup['ApplicationGroup']['client_pw_expiration'] = date_format(date_modify(new DateTime(date("Y-m-d")), '+2 month'), 'Y-m-d');
                $ApplicationGroup->clear();
                $ApplicationGroup->save($appGroup['ApplicationGroup']);
            }
            $ApplicationGroup->query('ALTER TABLE onlineapp_application_groups alter COLUMN client_access_token SET NOT NULL');
            $ApplicationGroup->query('ALTER TABLE onlineapp_application_groups alter COLUMN client_password SET NOT NULL');
            $ApplicationGroup->query('ALTER TABLE onlineapp_application_groups alter COLUMN client_pw_expiration SET NOT NULL');
        }
		return true;
	}
}
