<?php
App::uses('AppController', 'Controller');

/**
 * TemplateBuilder Controller
 *
 */
class TemplateBuilderController extends AppController {

/**
 * Load helpers
 */
	public $helpers = array('Js');

/**
 * admin_build
 *
 * @return void
 */
	public function admin_build() {
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->set('cobrands', $this->Cobrand->getList());
		$this->set('templates', $this->Template->getList());
	}

/**
 * admin_ajax_add_new
 *
 * @param integer $baseTemplate a Template.id optional since it can be passed through request->data
 * @return void
 */
	public function add_template($baseTemplate = null) {
		if ($this->request->is('ajax')) {
			$response = array();
			if (!empty($baseTemplate)) {
				$this->request->data['TemplateBuilder']['selected_template_id'] = $baseTemplate;
			}
			if (Hash::get($this->request->data, 'TemplateBuilder.mainBuilderForm')) {
				$baseTemplate = $this->request->data['TemplateBuilder']['selected_template_id'];

				$response = $this->TemplateBuilder->saveNewTemplate($this->request->data['TemplateBuilder']);
				if (empty($response['errors'])) {
					$redirectUrl = Router::url(array('controller' => 'Admin', 'action' => 'index'));
					$this->set('redirectUrl', $redirectUrl);
					$this->Session->setFlash('Successfully created a new template!', 'default', array('class' => 'alert alert-success'));
					$this->render('/Elements/Ajax/nonAjaxRedirect', 'ajax');
					return;
				}
			}
			$this->set('response', $response);
			$this->set($this->TemplateBuilder->setBuilderViewData($baseTemplate));
			$this->render('add_template', 'ajax');
		}
	}
}