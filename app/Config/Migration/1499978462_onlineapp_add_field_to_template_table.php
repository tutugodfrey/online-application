<?php
class OnlineappAddFieldToTemplateTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_field_to_template_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_templates' => array(
					'email_app_pdf' => array('type' => 'boolean', 'default' => false)),
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_templates' => array(
					'email_app_pdf'
				),
			),
		)
	);

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 * @throws Exception
 */
	public function after($direction) {
		$Template = $this->generateModel('OnlineappTemplate');
		if ($direction === 'up') {
			$payFusTemplateId = $Template->field('id', array('name' => 'Payment Fusion Sales Agreement'));
			$isSaved = $Template->save(
				array(
					'id' => $payFusTemplateId,
					'email_app_pdf' => true,
				)
			);
			if (!$isSaved) {
				throw new Exeption('Error: Could Not Save!');
			}
		}
		return true;
	}
}
