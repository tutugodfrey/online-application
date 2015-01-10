<?php
App::uses('AppController', 'Controller');

/**
 * TemplateBuilder Controller
 *
 */
class TemplateBuilderController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Security');

	//public $permissions = array(
	//	'index' => array(User::ADMIN, User::REP, User::MANAGER)
	//);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;

/*			// are we authenticated?
			if (is_null($this->Auth->user('id'))) {
				// not authenticated
				// next -> were we passed a valid uuid?
				$uuid = (isset($this->params['pass'][0]) ? $this->params['pass'][0] : '');
				if (Validation::uuid($uuid)) {
					// yup, allow edit action
					$this->Auth->allow('edit');
					// could look it up even
				} else {
					// invalid uuid - allow retrievel of their application via their email
					$this->Auth->allow('retrieve', 'retrieve_thankyou');
				}
			}
			*/
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
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
			$this->set('template', $template);
		}

		$this->set('logoPositionTypes', $this->Template->logoPositionTypes);
		$this->set('cobrands', $this->Cobrand->getList());
		$this->set('templates', $this->Template->getList());
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');

		$requestData = $this->request->data['TemplateBuilder'];

    	$newTemplate['Template'] = array(
    		'cobrand_id' => $requestData['new_template_cobrand_id'],
    		'name' => $requestData['name'],
    		'logo_position' => $requestData['logo_position'],
    		'include_axia_logo' => $requestData['include_axia_logo'],
    		'description' => $requestData['description'],
    		'rightsignature_template_guid' => $requestData['rightsignature_template_guid'],
    		'rightsignature_install_template_guid' => $requestData['rightsignature_install_template_guid'],
    		'owner_equity_threshold' => $requestData['owner_equity_threshold']
    	);

    	$this->Template->create();
    	$newTemplateData = $this->Template->save($newTemplate);

$this->log($requestData, 'debug');

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

					$newTemplatePage['TemplatePage'] = array(
						'name' =>  $templatePage['TemplatePage']['name'],
						'description' =>  $templatePage['TemplatePage']['description'],
						'rep_only' =>  $templatePage['TemplatePage']['rep_only'],
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

					$newTemplateSection['TemplateSection'] = array(
						'name' =>  $templateSection['TemplateSection']['name'],
						'description' =>  $templateSection['TemplateSection']['description'],
						'rep_only' =>  $templateSection['TemplateSection']['rep_only'],
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

					$newTemplateField['TemplateField'] = array(
						'name' =>  $templateField['TemplateField']['name'],
						'description' =>  $templateField['TemplateField']['description'],
						'rep_only' =>  $templateField['TemplateField']['rep_only'],
						'width' =>  $templateField['TemplateField']['width'],
						'type' =>  $templateField['TemplateField']['type'],
						'required' =>  $templateField['TemplateField']['required'],
						'source' =>  $templateField['TemplateField']['source'],
						'default_value' =>  $templateField['TemplateField']['default_value'],
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
}

















