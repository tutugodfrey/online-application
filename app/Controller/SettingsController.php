<?php
class SettingsController extends AppController {

       public $scaffold = 'admin';

    public $permissions = array();

    function beforeFilter() {
        parent::beforeFilter();
        
        $this->loadModel('User');
        if (!$this->Auth->user('group_id') || $this->User->Group->field('name', array('id' => $this->Auth->user('group_id'))) != 'admin') {
            header("HTTP/1.0 403 Forbidden");
            exit;
        }
    }

    function admin_delete() {
        echo 'Admin delete is disabled!';
        exit;
    }

    function admin_add() {
        if ($this->Common->isPosted()) {
            $this->Setting->create();
            if ($this->Setting->save($this->request->data)) {
                $this->Session->setFlash(__('The Setting has been created'));
                $this->redirect(array('action'=> 'index', 'admin' => true));
            } else {
                $this->Session->setFlash(__('The Setting Could not Be saved'));
            }
        }
    }

}
?>
