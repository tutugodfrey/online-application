<?php
App::uses('AppController', 'Controller');
/**
 * Cobrands Controller
 *
 */
class CobrandsController extends AppController {

	public $scaffold = 'admin';

	protected $_listUrl = '/admin/cobrands';

	public function admin_add() {
		$this->set('title_for_layout', 'Add Cobrand');
		$this->set('responseUrlTypes', $this->Cobrand->responseUrlTypes);
		$this->set('existingLogos', $this->Cobrand->getExistingLogos());
		$this->_setViewNavData();
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$data = $this->Cobrand->setLogoUrl($data);
			$this->Cobrand->create();
			if ($this->Cobrand->save($data)) {
				$this->_success("Cobrand Saved!");
				$this->redirect($this->_listUrl);
			}
			$this->_failure(__('Unable to add your cobrand'));
		}
	}

	public function admin_edit($idToEdit) {
		$this->Cobrand->id = $idToEdit;
		$this->set('responseUrlTypes', $this->Cobrand->responseUrlTypes);
		$this->set('existingLogos', $this->Cobrand->getExistingLogos());

		$data = $this->Cobrand->getById($this->Cobrand->id);
		$this->set('cobrand', $data);
		
		if (empty($this->request->data)) {
			$this->request->data = $data;
		} else {
			if ($this->request->data['Cobrand']['delete_cobrand_logo'] == '1') {
				unlink(WWW_ROOT . substr($data['Cobrand']['cobrand_logo_url'],1));
				$this->request->data['Cobrand']['cobrand_logo_url'] = '';
			}

			if ($this->request->data['Cobrand']['delete_brand_logo'] == '1') {
				unlink(WWW_ROOT . substr($data['Cobrand']['brand_logo_url'],1));
				$this->request->data['Cobrand']['brand_logo_url'] = '';
			}

			$this->request->data = $this->Cobrand->setLogoUrl($this->request->data);

			if ($this->Cobrand->saveAll($this->request->data)) {
				$this->_success("Cobrand Saved!");
				return $this->redirect($this->_listUrl);
			}

			$this->_failure(__('Unable to update your cobrand'));
		}
	}

	public function admin_index() {
		$this->set('responseUrlTypes', $this->Cobrand->responseUrlTypes);
		
		$this->paginate = array(
			'limit' => 25,
			'order' => array('Cobrand.partner_name' => 'asc'),
		);

		$data = $this->paginate('Cobrand');
		$this->set('cobrands', $data);
		$this->_setViewNavData();
		$this->set('scaffoldFields', array_keys($this->Cobrand->schema()));
	}

	public function admin_delete($idToDelete) {
		$this->Cobrand->delete($idToDelete);
		$this->_success("Cobrand Deleted!");
		$this->redirect($this->_listUrl);
	}

	public function get_template_ids($cobrandId) {
		$this->autoRender = false;
		$templateIds = $this->Cobrand->getTemplateIds($cobrandId);

		$response = array();

		if (!empty($templateIds) && is_array($templateIds)) {
			foreach ($templateIds as $key => $val) {
				$response[$key] = $val;
   			}
		}
		
		echo json_encode($response);
	}

/**
 * _setViewNavContent
 * Utility method sets an array of urls to use as left navigation items on views
 *
 * @param string $showActive string representation of boolean value
 * @return array
 */
	protected function _setViewNavData() {
		$elVars = array(
			'navLinks' => array(
				'Cobrands Index' => Router::url(array('action' => 'index', 'admin' => true)),
				'Add Cobrand' => Router::url(array('action' => 'add', 'admin' => true)),
			)
		);

		$this->set(compact('elVars'));
	}
}