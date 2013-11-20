<?php
class TimelineEntriesController extends AppController {
    
    //public $components = array ('RequestHandler');
       public $components = array('Email');
    public $scaffold = 'admin';
    
        function beforeFilter() {
        parent::beforeFilter();
        
    }
    
}
?>
