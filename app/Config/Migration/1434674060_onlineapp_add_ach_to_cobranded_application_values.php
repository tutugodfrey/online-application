<?php
class OnlineappAddAchToCobrandedApplicationValues extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'onlineapp_add_ach_to_cobranded_application_values';

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
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		$this->Cobrand = $this->generateModel('OnlineappCobrand');
		$this->Template = $this->generateModel('OnlineappTemplate');
		$this->TemplatePage = $this->generateModel('OnlineappTemplatePage');
		$this->TemplateSection = $this->generateModel('OnlineappTemplateSection');
		$this->TemplateField = $this->generateModel('OnlineappTemplateField');
		$this->CobrandedApplication = $this->generateModel('OnlineappCobrandedApplication');

		$this->Cobrand->recursive = -1;
		$this->Template->recursive = -1;
		$this->TemplatePage->recursive = -1;
		$this->TemplateSection->recursive = -1;
		$this->TemplateField->recursive = -1;
		$this->CobrandedApplication->recursive = -1;

		$cobrands = $this->Cobrand->find('all',
			array(
				'fields' => array('id'),
				'conditions' => array(
					'partner_name' => 'Corral'
				)
			)
		);

		foreach ($cobrands as $cobrand) {
			$cobrandId = $cobrand['OnlineappCobrand']['id'];

			$templates = $this->Template->find('all',
				array(
					'fields' => array('id', 'name'),
					'conditions' => array(
						'cobrand_id' => $cobrandId,
					),
				)
			);

			foreach ($templates as $template) {
				$achYesId = null;
				$achNoId = null;

				$templateId = $template['OnlineappTemplate']['id'];

				$templatePages = $this->TemplatePage->find('all',
					array(
						'fields' => array('id'),
						'conditions' => array(
							'template_id' => $templateId,
						),
					)
				);

				foreach ($templatePages as $templatePage) {
					$templatePageId = $templatePage['OnlineappTemplatePage']['id'];

					$templateSections = $this->TemplateSection->find('all',
						array(
							'fields' => array('id', 'name'),
							'conditions' => array(
								'page_id' => $templatePageId,
							),
						)
					);

					foreach ($templateSections as $templateSection) {
						$templateSectionId = $templateSection['OnlineappTemplateSection']['id'];

						$templateFields = $this->TemplateField->find('all',
							array(
								'fields' => array('id', 'merge_field_name'),
								'conditions' => array(
									'section_id' => $templateSectionId,
								),
							)
						);

						foreach ($templateFields as $templateField) {
							if (preg_match('/^ACH-$/', $templateField['OnlineappTemplateField']['merge_field_name'])) {
								$achYesId = $templateField['OnlineappTemplateField']['id'];
								$achNoId = $templateField['OnlineappTemplateField']['id'];
							}
						}
					}
				}

				// get apps that are not completed or signed, for this template_id
				$applications = $this->CobrandedApplication->find('all',
					array(
						'fields' => array('id'),
						'conditions' => array(
							'template_id' => $templateId,
							'NOT' => array(
								'status' => array('completed', 'signed')
							)
						),
					)
				);

				foreach ($applications as $application) {
					if ($direction == 'up') {
						if (!empty($achYesId) && !empty($achNoId)) {
							$cav = array();
							$cav['OnlineappCobrandedApplicationValue']['cobranded_application_id'] = $application['OnlineappCobrandedApplication']['id'];
							$cav['OnlineappCobrandedApplicationValue']['template_field_id'] = $achYesId;
							$cav['OnlineappCobrandedApplicationValue']['name'] = 'ACH-Yes';
							$cav['OnlineappCobrandedApplicationValue']['value'] = '';
							$this->CobrandedApplicationValue = $this->generateModel('OnlineappCobrandedApplicationValue');
							$this->CobrandedApplicationValue->save($cav);

							$cav = array();
							$cav['OnlineappCobrandedApplicationValue']['cobranded_application_id'] = $application['OnlineappCobrandedApplication']['id'];
							$cav['OnlineappCobrandedApplicationValue']['template_field_id'] = $achNoId;
							$cav['OnlineappCobrandedApplicationValue']['name'] = 'ACH-No';
							$cav['OnlineappCobrandedApplicationValue']['value'] = 'true';
							$this->CobrandedApplicationValue = $this->generateModel('OnlineappCobrandedApplicationValue');
							$this->CobrandedApplicationValue->save($cav);
						}
					}
					if ($direction == 'down') {
						$this->CobrandedApplicationValue = $this->generateModel('OnlineappCobrandedApplicationValue');
						$achYes = $this->CobrandedApplicationValue->find('first',
							array(
								'fields' => array('id'),
								'conditions' => array(
									'cobranded_application_id' => $application['OnlineappCobrandedApplication']['id'],
									'name' => 'ACH-Yes',
								),
							)
						);

						if (!empty($achYes)) {
							$this->CobrandedApplicationValue = $this->generateModel('OnlineappCobrandedApplicationValue');
							$this->CobrandedApplicationValue->id = $achYes['OnlineappCobrandedApplicationValue']['id'];
							$this->CobrandedApplicationValue->delete();
						}

						$achNo = $this->CobrandedApplicationValue->find('first',
							array(
								'fields' => array('id'),
								'conditions' => array(
									'cobranded_application_id' => $application['OnlineappCobrandedApplication']['id'],
									'name' => 'ACH-No',
								),
							)
						);

						if (!empty($achNo)) {
							$this->CobrandedApplicationValue = $this->generateModel('OnlineappCobrandedApplicationValue');
							$this->CobrandedApplicationValue->id = $achNo['OnlineappCobrandedApplicationValue']['id'];
							$this->CobrandedApplicationValue->delete();
						}
					}
				}
			}
		}

		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
