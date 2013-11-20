<?php

App::uses('CakeEmail', 'Network/Email');
App::uses('EmailTimeline', 'Model');
App::uses('User', 'Model');
class Multipass extends AppModel {

    const MULTIPASS_LOW_THRESHOLD = 10;
    const MULTIPASS_EMPTY = 0;

    public $actsAs = array('Containable', 'Search.Searchable');
    public $useTable = 'onlineapp_multipasses';
    public $belongsTo = 'Application';
    public $validate = array(
        'merchant_id' => array(
            'rule' => 'notEmpty'
        ),
        'device_number' => array(
            'rule' => 'notEmpty'
        ),
        'username' => array(
            'rule' => 'notEmpty'
        ),
        'pass' => array(
            'rule' => 'notEmpty'
        )
    );
    /**
     * Callback has failed
     * 
     */
    public function callbackFailure() {
        $Email = new CakeEmail();
        $Email->from(array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications'));
        $Email->to(EmailTimeline::DATA_ENTRY_EMAIL);
        $Email->subject('Multipass Minimum Threshold Reached');
        $Email->send('There are only ' . $count . ' available multipass records left, please generate and upload more now');
    }
    
    /**
     * Grabs an unused multipass record for use by 3rd party
     * @param integer $id
     * @return array
     */
    public function initializeMultipass($application) {
        $data = $this->find('first', array(
            'conditions' => array('in_use' => false, 'application_id' => NULL),
            'recursive' => -1
        ));
        $id = $application['Application']['id'];
        $count = ($this->find('count', array('conditions' => array('in_use' => FALSE))) -1);
        if ($count <= self::MULTIPASS_LOW_THRESHOLD && $count >= self::MULTIPASS_EMPTY) {
            $this->emailLowMultipass($count);
        }
        if ($data['Multipass']['id']) {
            $this->id = $data['Multipass']['id'];
            if ($this->updateALL(array('Multipass.application_id' => $id, 'Multipass.in_use' => TRUE), array('id' => $data['Multipass']['id']))) {
                return $data;
            } else {
                //$this->multipassFailure($application);
                $this->emailSaveFailure($application, $id);
                
            }
        } else {
            //$this->callbackFailure($application);
            $this->emailNoneAvailable($application, $id);
            
        }
    }

    /**
     * Saves an Edited Mutipass Record
     * @param array $data
     * @return boolean
     */
    public function editMultipass($data) {
        if ($this->save($data)) {
            return true;
        }
        return false;
    }

    /**
     * in the event that we are running low on available multipass records
     * send an email to the appropriate party telling them to create more
     * @param integer $count
     */
    public function emailLowMultipass($count) {
        $Email = new CakeEmail();
        $Email->from(array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications'));
        $Email->to(EmailTimeline::DATA_ENTRY_EMAIL);
        $Email->subject('Multipass Minimum Threshold Reached');
        $Email->send('There are only ' . $count . ' available multipass records left, please generate and upload more now');
    }

    /**
     * Let the appropriate party know that the multipass record failes to save properly
     * @param array $data
     * @param integer $id
     * @todo make sure that this works, create appropriate EmailTimeline
     */
    public function emailSaveFailure($data, $id) {
        $Email = new CakeEmail();
        $Email->viewVars(
                array(
                    'id' => $data['Application']['id'], 
                    'dba' => $data['Application']['dba_business_name'], 
                    'guid' => $data['Application']['guid'],
                    'rsGuid' => $data['Application']['rs_document_guid'],
                    'rep' => $data['User']['fullname']
                    )
                );
        $Email->from(array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications'));
        $Email->to(EmailTimeline::DATA_ENTRY_EMAIL);
        $Email->subject('Multipass Board Failure');
        $Email->emailFormat('html');
        $Email->template('multipass_failure');
        $Email->send('Save Failed');
//                $this->Application->EmailTimeline->create();
//                $this->Application->EmailTimeline->save(array('app_id' => $data['Application']['id'], 'date' => DboSource::expression('NOW()'),'subject_id' => EmailTimeline::COVERSHEET_TO_UW, 'recipient' => EmailTimeline::UNDERWRITING_EMAIL));
    }
    
        public function emailNoneAvailable($data, $id) {
        $Email = new CakeEmail();
                $Email->viewVars(
                array(
                    'id' => $data['Application']['id'], 
                    'dba' => $data['Application']['dba_business_name'], 
                    'guid' => $data['Application']['guid'],
                    'rsGuid' => $data['Application']['rs_document_guid'],
                    'rep' => $data['User']['fullname']
                    )
                );
        $Email->from(array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications'));
        $Email->to(EmailTimeline::DATA_ENTRY_EMAIL);
        $Email->subject('Multipass Records Exhausted');
        $Email->emailFormat('html');
        $Email->template('multipass_failure');
        $Email->send('Save Failed');
//                $this->Application->EmailTimeline->create();
//                $this->Application->EmailTimeline->save(array('app_id' => $data['Application']['id'], 'date' => DboSource::expression('NOW()'),'subject_id' => EmailTimeline::COVERSHEET_TO_UW, 'recipient' => EmailTimeline::UNDERWRITING_EMAIL));
//                $Email->from(array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications'));
//        $Email->to(EmailTimeline::DATA_ENTRY_EMAIL);
//        $Email->subject('Multipass Records Exhausted');
//        $Email->send('Multipass Records Exhausted');
        
        }

    /**
     * Grab the specified Multipass record for viewing or editing
     * @param varchar $id
     * @return array
     */
    public function getMultipass($id) {


        $conditions = array(
            'Multipass.id' => $id
        );
        return $this->find('first', array('conditions' => $conditions, 'recursive' => -1));
    }

    /**
     * Query the database for a list of multipass records that are in use
     * @return array $inUseMultipasses
     * @todo implement this feature
     */
    public function inUseMultipass() {
        $inUseMultipasses = $this->find('all');
        return $inUseMultipasses;
    }

    /**
     * Query the database for a list of Multipass records that are not being used
     * @return array $availableMultipasses
     * @todo implement this feature
     */
    public function availableMultipass() {
        $availableMultipasses = $this->find('all');
        return $availableMultipasses;
    }

    /**
     * Massage data prior to validation
     * @param array $options
     * @return boolean
     */
    public function beforeValidate($options = array()) {
        parent::beforeValidate($options);
        if ($this->data[$this->alias]['merchant_id'] == '' && $this->data[$this->alias]['pass'] == '') {
            unset($this->data[$this->alias]);
        } return true;
    }

    /**
     * Massage data prior to saving
     * @param array $options
     * @return boolean
     */
    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        if ($this->data[$this->alias]['merchant_id'] == '' && $this->data[$this->alias]['pass'] == '') {
            unset($this->data[$this->alias]);
        } return true;
    }
    /**
     * update the modified date stamp any time data changes
     * @param type $data
     * @param type $validate
     * @param type $fieldList
     * @return type
     */
    function save($data = null, $validate = true, $fieldList = array()) {
        //clear modified field value before each save
        if (isset($this->data) && isset($this->data[$this->name]))
            unset($this->data[$this->name]['modified']);
        if (isset($data) && isset($data[$this->name]))
            unset($data[$this->name]['modified']);
        return parent::save($data, $validate, $fieldList);
    }
}

?>
