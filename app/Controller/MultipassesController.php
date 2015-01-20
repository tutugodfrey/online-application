<?php
App::uses('User','Model');
class MultipassesController extends AppController {
    public $components = array('Search.Prg');
    function admin_add($id = null) {        
        if ($this->request->is('post')) {
            $this->Multipass->create();
            if ($this->Multipass->save($this->request->data)) {
                $this->Session->setFlash(__('A Multipass record has been created'));
                $this->redirect(array('action'=> 'index', 'admin' => true));
            } else {
                $this->Session->setFlash(__('The Multipass record could not be saved'));
            }
        }
        $this->set(compact('users'));        
    }
    
    function index() {
        $this->redirect(array('action'=> 'index', 'admin' => true));
    }
    /**
     * Display a paginated list of all multipass records
     */
    function admin_index() {
//        $inUseMultipasses = $this->Multipass->inUseMultipass();
//        $availableMultipasses = $this->Multipass->availableMultipass();
//        $this->set(compact('availableMultipasses', 'inUseMultipasses'));
        $this->paginate = array(
            'limit' => 50,
            'fields' => array('*', 'Application.dba_business_name','Application.id','Application.hash'),
            'order' => array('Multipass.modified' => 'desc')
        );
        $multipasses = $this->paginate('Multipass');
                if ($this->Auth->user('group_id') != User::ADMIN_GROUP_ID) {
            $conditions =  array(
                    'Application.user_id' => $this->Multipass->Application->User->getAssignedUserIds($this->Auth->user('id'))
            );
        }
        $this->set(compact('multipasses'));
        $this->set('users', $this->Multipass->Application->User->assignableUsers($this->Auth->user('id'), $this->Auth->user('group_id')));
    }

    /**
     * Edit a single multipass record
     * @param type $id
     * @return type
     * @throws NotFoundException
     */
    function admin_import() {
        $this->redirect(array('action' => 'import', 'admin' => false));
    }
    function admin_edit($id = null) {
        $this->Multipass->id = $id;
        if (is_null($id) || !$this->Multipass->exists()) {
            throw new NotFoundException(__('Invalid Multipass'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Multipass->editMultipass($this->request->data)) {
                $this->Session->setFlash(__('The multipass has been saved'));
                $this->redirect(array('action' => 'index'));

                return;
            }

            $this->Session->setFlash(__('The multipass could not be saved. Please, try again.'));
        }
        $multipass = $this->Multipass->getMultipass($id);
        if ($this->request->is('get')) {
            $this->request->data = $multipass;
        }

        $this->set('multipass', $multipass);
    }
function admin_search() {
        $this->Prg->commonProcess();
        $this->set('users', $this->Multipass->Application->User->assignableUsers($this->Auth->user('id'), $this->Auth->user('group_id')));
        $criteria = trim($this->params['data']['Multipass']['search']);
        $criteria = trim($this->passedArgs['search']);
        $criteria = '%' . $criteria . '%';
        $conditions = array('OR' => array(
                'Multipass.merchant_id ILIKE' => $criteria,
                'Multipass.device_number ILIKE' => $criteria,
                'Multipass.username ILIKE' => $criteria,
                'Application.dba_business_name ILIKE' => $criteria,
                'Application.hash ILIKE' => $criteria,
                'Application.rs_document_guid' => $criteria,
                'CAST(Application.id AS TEXT) ILIKE' => $criteria,
            ),
        );

        if ($this->passedArgs['Select User'] != '') {
            $conditions[] = array('Application.user_id' => $this->passedArgs['Select User']);
        } else if ($this->Auth->user('group_id') != User::ADMIN_GROUP_ID) {
            $conditions[] = array('Application.user_id' => $this->Multipass->Application->User->getAssignedUserIds($this->Auth->user('id')));
        }
        if ($this->passedArgs['Status'] != '') {
            $conditions[] = array('Application.status' => $this->passedArgs['Status']);
        }
                        if ($this->passedArgs['Multipass In Use'] != '') {
                        $conditions[] = array('Multipass.in_use' => $this->passedArgs['Multipass In Use']);
                }
        $this->paginate = array(
            'limit' => 50,
            'fields' => array('*', 'Application.dba_business_name','Application.id','Application.hash'),
            'order' => array('Multipass.modified' => 'desc')
        );
        $multipasses = $this->paginate('Multipass', $conditions);
        $this->set(compact('multipasses'));
        $this->set('criteria', $this->passedArgs['search']); //I do it this way because I dont want to include the % chars
        $this->render('admin_index');
    }    

}

?>
