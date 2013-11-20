<?php
class ApiLogsController extends AppController {

    function beforeFilter() {
        parent::beforeFilter();
    }

    function admin_index() {
        $this->paginate = array(
            'order' => array('created' => 'desc')
        );
        $apiLogs = $this->paginate('ApiLog');
        $this->set(compact('apiLogs'));
    }

}
?>
