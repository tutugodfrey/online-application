<?php
class MerchantsController extends AppController {

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
        echo 'Admin add is disabled!';
        exit;
    }
    
    function admin_import($id) {
        $this->Merchant->import($id,$mid);
    }

}
?>
