<?php
App::uses('AppController', 'Controller');

/**
 * TemplateBuilder Controller
 *
 */
class TemplateBuilderController extends AppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');

		if ($this->request->is('post')) {
			$templateId = $this->request->data['TemplateBuilder']['base_template'];
			$this->Template->id = $templateId;
			$template = $this->Template->find(
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

			$this->request->data = $this->Session->read('requestData');
			$this->set('template', $template);

			$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
			
			$client = $this->CobrandedApplication->createRightSignatureClient();
			$results = $this->CobrandedApplication->getRightSignatureTemplates($client);

			$templates = array();
			$installTemplates = array();

			foreach ($results as $guid => $filename) {
				if (preg_match('/install/i', $filename)) {
					$installTemplates[$guid] = $filename;
				} else {
					$templates[$guid] = $filename;
				}
			}

			$this->set('templateList', $templates);
			$this->set('installTemplateList', $installTemplates);
		}

		$this->Session->delete('requestData');
		$this->set('logoPositionTypes', $this->Template->logoPositionTypes);
		$this->set('cobrands', $this->Cobrand->getList());
		$this->set('templates', $this->Template->getList());
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		$response = array(
			'success' => true,
			'msg' => '',
		);

		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');

		$this->Session->write('requestData', $this->request->data);

		$requestData = $this->request->data['TemplateBuilder'];

		foreach ($requestData as $key => $val) {
			if ($val) {
				// make sure all selected sections have a selected parent page
				if (preg_match('/^template_page_id_(\d+)_section_id_(\d+)$/', $key, $matches)) {
					$parentPageId = 'template_page_id_'.$matches[1];

					if (!$requestData[$parentPageId]) {
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

						$response['success'] = false;
						$response['msg'] .= 'section: '.$section['TemplateSection']['name'].' was selected, but not it\'s parent page: '.$parentPage['TemplatePage']['name'].'<br>';
					}
				}

				// make sure all selected fields have a selected section
				if (preg_match('/^template_page_id_(\d+)_section_id_(\d+)_field_id_(\d+)$/', $key, $matches)) {
					$parentSectionId = 'template_page_id_'.$matches[1].'_section_id_'.$matches[2];

					if (!$requestData[$parentSectionId]) {
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

						$response['success'] = false;
						$response['msg'] .= 'field: '.$field['TemplateField']['name'].' was selected, but not it\'s parent section: '.$parentSection['TemplateSection']['name'].'<br>';
					}
				}
			}
		}

		if ($response['success'] == true) {
			$response['msg'] = 'successfully created a new template';
			
    		$newTemplate['Template'] = array(
    			'cobrand_id' => $requestData['new_template_cobrand_id'],
    			'name' => $requestData['name'],
    			'logo_position' => $requestData['logo_position'],
    			'include_brand_logo' => $requestData['include_brand_logo'],
    			'description' => $requestData['description'],
    			'rightsignature_template_guid' => $requestData['rightsignature_template_guid'],
    			'rightsignature_install_template_guid' => $requestData['rightsignature_install_template_guid'],
    			'owner_equity_threshold' => $requestData['owner_equity_threshold']
    		);

    		$this->Template->create();
    		$newTemplateData = $this->Template->save($newTemplate);

    		if (!is_array($newTemplateData)) {
    			$this->Session->setFlash(__('The template could not be saved. Please, try again.'));
    		} else {
				$pageIdMap = array();
				$sectionIdMap = array();

				foreach ($requestData as $key => $val) {
					if ($val) {
						if (preg_match('/^template_page_id_(\d+)$/', $key, $matches)) {
							$templatePage = $this->TemplatePage->find(
								'first',
								array(
									'conditions' => array('TemplatePage.id' => $matches[1]),
									'recursive' => -1
								)
							);

							$repOnly = $requestData['rep_only_template_page_id_'.$matches[1]];

							$newTemplatePage['TemplatePage'] = array(
								'name' =>  $templatePage['TemplatePage']['name'],
								'description' =>  $templatePage['TemplatePage']['description'],
								'rep_only' =>  $repOnly,
								'template_id' =>  $newTemplateData['Template']['id'],
								'order' =>  $templatePage['TemplatePage']['order']
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

							$repOnly = $requestData['rep_only_template_page_id_'.$matches[1].'_section_id_'.$matches[2]];

							$newTemplateSection['TemplateSection'] = array(
								'name' =>  $templateSection['TemplateSection']['name'],
								'description' =>  $templateSection['TemplateSection']['description'],
								'rep_only' =>  $repOnly,
								'width' =>  $templateSection['TemplateSection']['width'],
								'page_id' =>  $pageIdMap[$matches[1]],
								'order' =>  $templateSection['TemplateSection']['order']
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

							$repOnly = $requestData['rep_only_template_page_id_'.$matches[1].'_section_id_'.$matches[2].'_field_id_'.$matches[3]];
							$required = $requestData['required_template_page_id_'.$matches[1].'_section_id_'.$matches[2].'_field_id_'.$matches[3]];
							$defaultValue = $requestData['default_template_page_id_'.$matches[1].'_section_id_'.$matches[2].'_field_id_'.$matches[3]];

							$newTemplateField['TemplateField'] = array(
								'name' =>  $templateField['TemplateField']['name'],
								'description' =>  $templateField['TemplateField']['description'],
								'rep_only' =>  $repOnly,
								'width' =>  $templateField['TemplateField']['width'],
								'type' =>  $templateField['TemplateField']['type'],
								'required' =>  $required,
								'source' =>  $templateField['TemplateField']['source'],
								'default_value' =>  $defaultValue,
								'merge_field_name' =>  $templateField['TemplateField']['merge_field_name'],
								'order' =>  $templateField['TemplateField']['order'],
								'section_id' =>  $sectionIdMap[$matches[2]],
								'encrypt' =>  $templateField['TemplateField']['encrypt']
							);

							$this->TemplateField->create();
							$this->TemplateField->save($newTemplateField);
						}
					}
				}
			}
		} else {
			$this->Session->setFlash(__($response['msg']));
		}

		if ($response['success'] == true) {
			$this->set('response', $response['msg']);
		}
	}
}