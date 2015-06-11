<?php
class OnlineappAddEmvToCobrandedApplicationValues extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'onlineapp_add_emv_to_cobranded_application_values';

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
		$this->CobrandedApplicationValue = $this->generateModel('OnlineappCobrandedApplicationValue');

		$this->Cobrand->recursive = -1;
		$this->Template->recursive = -1;
		$this->TemplatePage->recursive = -1;
		$this->TemplateSection->recursive = -1;
		$this->TemplateField->recursive = -1;
		$this->CobrandedApplication->recursive = -1;
		$this->CobrandedApplicationValue->recursive = -1;

		$optBluTemplateIds = array();

		$cobrands = $this->Cobrand->find('all',
			array(
				'fields' => array('id')
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
				$optBlueTemplate = false;

				$emvYesId = null;
				$emvNoId = null;
				$emv2YesId = null;
				$emv2NoId = null;

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
							if (preg_match('/^Schedule of Fees Part I$/', $templateSection['OnlineappTemplateSection']['name'])) {
								if (preg_match('/^Amex Discount Rate$/', $templateField['OnlineappTemplateField']['merge_field_name'])) {
									$optBlueTemplate = true;
								}
							}

							if (preg_match('/^EMV-Yes$/', $templateField['OnlineappTemplateField']['merge_field_name'])) {
								$emvYesId = $templateField['OnlineappTemplateField']['id'];
							}

							if (preg_match('/^EMV-No$/', $templateField['OnlineappTemplateField']['merge_field_name'])) {
								$emvNoId = $templateField['OnlineappTemplateField']['id'];
							}

							if (preg_match('/^EMV2-Yes$/', $templateField['OnlineappTemplateField']['merge_field_name'])) {
								$emv2YesId = $templateField['OnlineappTemplateField']['id'];
							}

							if (preg_match('/^EMV2-No$/', $templateField['OnlineappTemplateField']['merge_field_name'])) {
								$emv2NoId = $templateField['OnlineappTemplateField']['id'];
							}
						}
					}
				}

				if ($optBlueTemplate) {
					// get apps for this template_id
					$applications = $this->CobrandedApplication->find('all',
						array(
							'fields' => array('id'),
							'conditions' => array(
								'template_id' => $templateId,
							),
						)
					);

					foreach ($applications as $application) {
						$cav = array();
						$cav['CobrandedApplicationValue']['cobranded_application_id'] = $application['OnlineappCobrandedApplication']['id'];

						$cav['CobrandedApplicationValue']['template_field_id'] = $emvYesId;
						$cav['CobrandedApplicationValue']['name'] = 'EMV-Yes';
						$cav['CobrandedApplicationValue']['value'] = '';
echo print_r($cav);
						$this->CobrandedApplicationValue->save($cav);

						$cav['CobrandedApplicationValue']['template_field_id'] = $emvNoId;
						$cav['CobrandedApplicationValue']['name'] = 'EMV-No';
						$cav['CobrandedApplicationValue']['value'] = 'true';
						$this->CobrandedApplicationValue->save($cav);

						$cav['CobrandedApplicationValue']['template_field_id'] = $emv2YesId;
						$cav['CobrandedApplicationValue']['name'] = 'EMV2-Yes';
						$cav['CobrandedApplicationValue']['value'] = '';
						$this->CobrandedApplicationValue->save($cav);

						$cav['CobrandedApplicationValue']['template_field_id'] = $emv2NoId;
						$cav['CobrandedApplicationValue']['name'] = 'EMV2-No';
						$cav['CobrandedApplicationValue']['value'] = 'true';
						$this->CobrandedApplicationValue->save($cav);

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
