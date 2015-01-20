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

		if ($this->request->is('post')) {
			$data = $this->request->data;
			$data = $this->Cobrand->setLogoUrl($data);
			$this->Cobrand->create();
			if ($this->Cobrand->save($data)) {
				$this->Session->setFlash("Cobrand Saved!");
				$this->redirect($this->_listUrl);
			}
			$this->Session->setFlash(__('Unable to add your cobrand'));
		}
	}

	public function admin_edit($idToEdit) {
		$this->Cobrand->id = $idToEdit;
		$this->set('responseUrlTypes', $this->Cobrand->responseUrlTypes);
		$data = $this->Cobrand->find('first', array('conditions' => array('id' => $this->Cobrand->id), 'recursive' => -1));
		if (empty($this->request->data)) {
			$this->request->data = $this->Cobrand->read();
		} else {
			// try to update the cobrand
			if ($this->request->data['Cobrand']['logo']['error'] ==  0 && 
				is_file(WWW_ROOT . substr($data['Cobrand']['logo_url'], 1)))
				 {
					unlink(WWW_ROOT . substr($data['Cobrand']['logo_url'],1));
				} else if ($this->request->data['Cobrand']['delete_logo'] == '1') {
					unlink(WWW_ROOT . substr($data['Cobrand']['logo_url'],1));
					$this->request->data['Cobrand']['logo_url'] = '';
				}
			$this->request->data = $this->Cobrand->setLogoUrl($this->request->data);
			if ($this->Cobrand->saveAll($this->request->data)) {
				$this->Session->setFlash("Cobrand Saved!");
				return $this->redirect($this->_listUrl);
			}
			$this->Session->setFlash(__('Unable to update your cobrand'));
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
		$this->set('scaffoldFields', array_keys($this->Cobrand->schema()));
	}

	public function admin_delete($idToDelete) {
		$this->Cobrand->delete($idToDelete);
		$this->Session->setFlash("Cobrand Deleted!");
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
}
