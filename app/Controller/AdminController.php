<?php
class AdminController extends AppController {
    //public $components = array('RequestHandler');
       public $uses = array();
    public $permissions = array(
        'index' => array('admin', 'rep','manager'),
    );

    function beforeFilter() {
        parent::beforeFilter();
    }

    function index() {
        // ...
    }

}
?>
