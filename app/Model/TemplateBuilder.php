<?php
App::uses('AppModel', 'Model');

/**
 * TemplateBuilder Model
 *
 */
class TemplateBuilder extends AppModel {

	public $useTable = false;

/**
 * setBuilderViewData
 * 
 * @param int $templateId a Template.id
 * @return array of variables for the TemplateBuilder form
 * @throws Exception on API error
 */
	public function setBuilderViewData($templateId) {
		$TemplateModel = ClassRegistry::init('Template');
		$CobrandedApplication = ClassRegistry::init('CobrandedApplication');

		$template = $TemplateModel->find(
				'first', array(
					'contain' => array(
						'Cobrand',
						'TemplatePages' => array(
							'TemplateSections' => array(
								'TemplateFields'
							)
						)
					),
					'conditions' => array('Template.id' => $templateId)
				)
			);

		$client = $CobrandedApplication->createRightSignatureClient();
		$results = $CobrandedApplication->getRightSignatureTemplates($client);
		$orderedTemplates = [];
		//Throw exception only on production
		if (Configure::read('debug') == 0 && !empty(Hash::get($results, 'error'))) {
			throw new Exception('API ERROR: ' . Hash::get($results, 'error'));
		}

		$orderedTemplates = $CobrandedApplication->arrayDiffTemplateTypes($results);
		$templateList = Hash::get($orderedTemplates, 'templates');
		$installTemplateList = Hash::get($orderedTemplates, 'install_templates');

		$logoPositionTypes = $TemplateModel->logoPositionTypes;
		$cobrands = ClassRegistry::init('Cobrand')->getList();
		return compact('cobrands', 'logoPositionTypes', 'template', 'templateList', 'installTemplateList');
	}

/**
 * saveNewTemplate
 *
 * @param array $templateRequestData TemplateBuilder form data
 * @return array of errors or null of everything saves correctly
 */
	public function saveNewTemplate($templateRequestData) {
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');
		$response = array();
		foreach ($templateRequestData as $key => $val) {
			if ($val) {
				// make sure all selected sections have a selected parent page
				if (preg_match('/^template_page_id_(\d+)_section_id_(\d+)$/', $key, $matches)) {
					$parentPageId = 'template_page_id_' . $matches[1];

					if (!$templateRequestData[$parentPageId]) {
						$parentPage = $this->TemplatePage->find(
							'first',
							array(
								'conditions' => array('TemplatePage.id' => $matches[1]),
								'recursive' => -1
							)
						);

						$section = $this->TemplateSection->find(
							'first',
							array(
								'conditions' => array('TemplateSection.id' => $matches[2]),
								'recursive' => -1
							)
						);

						$response['errors'][] = '-- ' . 'section: ' . $section['TemplateSection']['name'] . ' was selected, but not it\'s parent page: ' . $parentPage['TemplatePage']['name'];
					}
				}

				// make sure all selected fields have a selected section
				if (preg_match('/^template_page_id_(\d+)_section_id_(\d+)_field_id_(\d+)$/', $key, $matches)) {
					$parentSectionId = 'template_page_id_' . $matches[1] . '_section_id_' . $matches[2];

					if (!$templateRequestData[$parentSectionId]) {
						$parentSection = $this->TemplateSection->find(
							'first',
							array(
								'conditions' => array('TemplateSection.id' => $matches[2]),
								'recursive' => -1
							)
						);

						$field = $this->TemplateField->find(
							'first',
							array(
								'conditions' => array('TemplateField.id' => $matches[3]),
								'recursive' => -1
							)
						);

						$response['errors'][] = '-- ' . 'field: ' . $field['TemplateField']['name'] . ' was selected, but not it\'s parent section: ' . $parentSection['TemplateSection']['name'];
					}
				}
			}
		}

		if (!array_key_exists('errors', $response)) {
			$newTemplate['Template'] = array(
				'cobrand_id' => $templateRequestData['new_template_cobrand_id'],
				'name' => $templateRequestData['name'],
				'logo_position' => $templateRequestData['logo_position'],
				'include_brand_logo' => $templateRequestData['include_brand_logo'],
				'description' => $templateRequestData['description'],
				'rightsignature_template_guid' => $templateRequestData['rightsignature_template_guid'],
				'rightsignature_install_template_guid' => $templateRequestData['rightsignature_install_template_guid'],
				'owner_equity_threshold' => $templateRequestData['owner_equity_threshold']
			);

			$this->Template->create();
			$newTemplateData = $this->Template->save($newTemplate);

			if (!empty($this->Template->validationErrors)) {
				foreach ($this->Template->validationErrors as $key => $value) {
					$response['errors'][] = '-- ' . Inflector::humanize($key) . ': ' . $value[0];
				}
				array_unshift($response['errors'], __('Template could not be saved, the following errors occurred:'));

			} else {
				$pageIdMap = array();
				$sectionIdMap = array();

				foreach ($templateRequestData as $key => $val) {
					if ($val) {
						if (preg_match('/^template_page_id_(\d+)$/', $key, $matches)) {
							$templatePage = $this->TemplatePage->find(
								'first',
								array(
									'conditions' => array('TemplatePage.id' => $matches[1]),
									'recursive' => -1
								)
							);

							$repOnly = $templateRequestData['rep_only_template_page_id_' . $matches[1]];

							$newTemplatePage['TemplatePage'] = array(
								'name' => $templatePage['TemplatePage']['name'],
								'description' => $templatePage['TemplatePage']['description'],
								'rep_only' => $repOnly,
								'template_id' => $newTemplateData['Template']['id'],
								'order' => $templatePage['TemplatePage']['order']
							);

							$this->TemplatePage->create();
							$this->TemplatePage->save($newTemplatePage);

							$pageIdMap[$matches[1]] = $this->TemplatePage->getLastInsertID();
						}

						if (preg_match('/^template_page_id_(\d+)_section_id_(\d+)$/', $key, $matches)) {
							$templateSection = $this->TemplateSection->find(
								'first',
								array(
									'conditions' => array('TemplateSection.id' => $matches[2]),
									'recursive' => -1
								)
							);

							$repOnly = $templateRequestData['rep_only_template_page_id_' . $matches[1] . '_section_id_' . $matches[2]];

							$newTemplateSection['TemplateSection'] = array(
								'name' => $templateSection['TemplateSection']['name'],
								'description' => $templateSection['TemplateSection']['description'],
								'rep_only' => $repOnly,
								'width' => $templateSection['TemplateSection']['width'],
								'page_id' => $pageIdMap[$matches[1]],
								'order' => $templateSection['TemplateSection']['order']
							);

							$this->TemplateSection->create();
							$this->TemplateSection->save($newTemplateSection);
							$sectionIdMap[$matches[2]] = $this->TemplateSection->getLastInsertID();
						}

						if (preg_match('/^template_page_id_(\d+)_section_id_(\d+)_field_id_(\d+)$/', $key, $matches)) {
							$templateField = $this->TemplateField->find(
								'first',
								array(
									'conditions' => array('TemplateField.id' => $matches[3]),
									'recursive' => -1
								)
							);

							$repOnly = $templateRequestData['rep_only_template_page_id_' . $matches[1] . '_section_id_' . $matches[2] . '_field_id_' . $matches[3]];
							$required = $templateRequestData['required_template_page_id_' . $matches[1] . '_section_id_' . $matches[2] . '_field_id_' . $matches[3]];
							$defaultValue = $templateRequestData['default_template_page_id_' . $matches[1] . '_section_id_' . $matches[2] . '_field_id_' . $matches[3]];

							$newTemplateField['TemplateField'] = array(
								'name' => $templateField['TemplateField']['name'],
								'description' => $templateField['TemplateField']['description'],
								'rep_only' => $repOnly,
								'width' => $templateField['TemplateField']['width'],
								'type' => $templateField['TemplateField']['type'],
								'required' => $required,
								'source' => $templateField['TemplateField']['source'],
								'default_value' => $defaultValue,
								'merge_field_name' => $templateField['TemplateField']['merge_field_name'],
								'order' => $templateField['TemplateField']['order'],
								'section_id' => $sectionIdMap[$matches[2]],
								'encrypt' => $templateField['TemplateField']['encrypt']
							);

							$this->TemplateField->create();
							$this->TemplateField->save($newTemplateField);
						}
					}
				}
			}
		}
		return $response;
	}
}