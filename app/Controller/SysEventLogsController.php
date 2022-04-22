<?php
class SysEventLogsController extends AppController {

    function beforeFilter() {
        parent::beforeFilter();
    }

    function admin_index() {
        $this->paginate = array(
            'order' => array('created' => 'desc')
        );
        $sysEventLogs = $this->paginate('SysEventLog');
        $this->set(compact('sysEventLogs'));
    }

}
?>
