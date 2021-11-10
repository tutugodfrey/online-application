<?php
class GroupApplicationsRetroactively extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'group_applications_retroactively';

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
        $CobrandedApplication = ClassRegistry::init('CobrandedApplication');
        if ($direction == 'up') {
            $ungrouped = $CobrandedApplication->find('all', array(
                'recursive' => -1,
                'fields' => array('id'),
                'conditions' => array(
                    'OR' => array(
                        "created >= '2021-01-01'",
                        "modified >= '2021-01-01'",
                    )
            )));

            foreach ($ungrouped as $app) {
                $CobrandedApplication->addAppToGroup($app['CobrandedApplication']['id']);
            }

        } else {
            $CobrandedApplication->query('UPDATE onlineapp_cobranded_applications SET application_group_id = null');
            $CobrandedApplication->query('TRUNCATE TABLE onlineapp_application_groups');
        }
		return true;
	}
}
