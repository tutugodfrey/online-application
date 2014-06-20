<?php
class OnlineappEmailTimelineModifications extends CakeMigration {

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
                        'rename_field' => array(
				'onlineapp_email_timelines' => array(
					'subject_id' => 'email_timeline_subject_id',
				),
                        ),
			'create_field' => array(
				'onlineapp_email_timelines' => array(
					'cobranded_application_id' => array('type' => 'integer', 'null' => true),
				),
			),
                ),
                'down' => array(
                        'rename_field' => array(
				'onlineapp_email_timelines' => array(
					'email_timeline_subject_id' => 'subject_id',
				),
                        ),
			'drop_field' => array(
				'onlineapp_email_timelines' => array('cobranded_application_id'),
			),
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
                        $this->db->execute(
                                "ALTER TABLE onlineapp_email_timelines
                                DROP CONSTRAINT onlineapp_email_timelines_cobranded_application_id_fkey;"
			);
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
                        $this->db->execute(
                                "ALTER TABLE onlineapp_email_timelines
				ADD CONSTRAINT onlineapp_email_timelines_cobranded_application_id_fkey FOREIGN KEY (cobranded_application_id) REFERENCES onlineapp_cobranded_applications (id);

                                ALTER TABLE onlineapp_email_timelines
				DROP CONSTRAINT onlineapp_email_timelines_subject_id_fkey;

                                ALTER TABLE onlineapp_email_timelines
				ADD CONSTRAINT onlineapp_email_timelines_email_timeline_subject_id_fkey FOREIGN KEY (email_timeline_subject_id) REFERENCES onlineapp_email_timeline_subjects (id);

				INSERT INTO onlineapp_email_timeline_subjects (id, subject) VALUES (10, 'New API Application');"
                        );
                }

                if ($direction == 'down') {
                        $this->db->execute(
                                "ALTER TABLE onlineapp_email_timelines
				DROP CONSTRAINT onlineapp_email_timelines_email_timeline_subject_id_fkey;

                                ALTER TABLE onlineapp_email_timelines
				ADD CONSTRAINT onlineapp_email_timelines_subject_id_fkey FOREIGN KEY (subject_id) REFERENCES onlineapp_email_timeline_subjects (id);

				DELETE FROM onlineapp_email_timeline_subjects WHERE id = 10;"
                        );
                }

                return true;
        }
}
