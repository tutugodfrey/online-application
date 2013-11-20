<?php
class GroupsController extends AppController {

       public $scaffold = 'admin';

    function beforeFilter() {
        parent::beforeFilter();
#        $this->Auth->allow();
    }

}
?>
