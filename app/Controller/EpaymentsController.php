<?php
class EpaymentsController extends AppController {
    
    //public $components = array ('RequestHandler');
       public $components = array('RequestHandler','Security');
    public $scaffold = 'admin';
    
        
    function beforeFilter() {
        parent::beforeFilter();
        if ($this->_isJSON() && $this->request->is('get')) {
            $this->Security->unlockedActions= array('api_sourcekey');
        }
    }
//        if ($this->_isJSON() && 0 < count($this->Epayment->User->Apip->find('list', array('fields' => array('Apip.ip_address'), 'conditions' => array('Apip.ip_address >>=' => $this->RequestHandler->getClientIP()))))) {
//            $this->Auth->allow(array('api_sourcekey'));
//            $this->Security->loginOptions = array(
//                'type' => 'basic',
//                'realm' => 'My REST services,services',
//                'login' => '_restLogin'
//            );
//
//            $this->Security->requireLogin(array('api_sourcekey'));
//            $this->Security->validatePost = false;
//            if (
//                    $this->_isJSON() &&
//                    !$this->RequestHandler->isGet()
//            ) {
//                ///end New code added 02/23/2012
//                if (empty($this->request->data) && !empty($_POST)) {
//                    $this->request->data[$this->modelClass] = $_POST;
//                }
//            }
//        }
//    }

    //New code added omm
    public function _restLogin($credentials) {

        $model = $this->Auth->getModel();
        try {
            $id = $model->useToken($credentials['username']);
            if (empty($id)) {
                $this->redirect(null, 503);
            }
        } catch (Exception $e) {
            $id = null;
        }
        if (empty($id) || !$this->Auth->login(strval($id))) {
            $this->Security->blackhole($this, 'login');
        }
    }//--end New code added 02/23/12

    public function beforeRender() {
        parent::beforeRender();
        if ($this->_isJSON()) {
            Configure::write('debug', 0);
            $this->disableCache();
        }
    }
            protected function _isJSON() {
            return ($this->params['ext'] == 'json' || $this->request->accepts('application/json'));
        }
// ******  ADD, EDIT, INDEX and DELETE for FUTURE USE ******  //
        /*
    public function api_add(){
        $this->setAction('edit');
    }

    public function api_edit($id=null) {
        if ($this->_isJSON() && !$this->RequestHandler->isPost()) {
            $this->redirect(null, 400);
        }
        if ($this->Common->isPosted()) {
            if (!empty($id)) {
                $this->Epayment->id = $id;
            } else {
                $this->Epayment->create();
            }
            if ($this->Epayment->save($this->request->data)) {
                $this->Session->setFlash('Epayment Item Added');
                if ($this->_isJSON()) {
                    $this->redirect(null, 200);
                } else {
                $this->redirect(array('action' => 'index'));
                }
            } else {
                if ($this->_isJSON()) {
                    $this->redirect(null,403);
                } else {
                    $this->Session->setFlash('Please Fix It');
                }
                
            }
        } elseif(!empty($id)) {
            $this->request->data = $this->Epayment->find('first', array(
                'conditions' => array('Epayment.id' => $id)
            ));
            if (empty($this->request->data)) {
                throw new NotFoundException();
            }
        }
        $this->set(compact('id'));
    }
    
    public function api_delete($id) {
        if ($this->_isJSON() && !$this->RequestHandler->isDelete()) {
            $this->redirect(null, 400);
        }
        $epayment = $this->Epayment->find('first', array(
            'conditions' => array('Epayment.id' => $id)
        ));
        if (empty($this->request->data)) {
            if ($this->_isJSON()) {
                $this->redirect(null, 404);
            }
            throw new NotFoundException();
        }
        if (!empty($this->request->data) || $this->RequestHandler->isDelete()) {
            if ($this->Epayment->delete($id)) {
                $this->Session->SetFlash ('Epayment deleted');
                $this->redirect(array('action' => 'index'));
                if ($this->_isJSON()) {
                    $this->redirect(null, 200);
                } else {
                    $this->redirect(array('action'=>'index'));
                }
            } else {
                if ($this->_isJSON()) {
                    $this->redirect(null, 403);
                } else {
                    $this->Session->SetFlash('Could not delete Epayment');
                }
            }
        }
        $this->set(compact('epayment'));
            
    }

    public function api_index($id) {
        
        if ($this->_isJSON() && !$this->RequestHandler->isGet()) {
            $this->redirect(null, 400);
        }
        
        $epayment = $this->Epayment->find('all', array('fields' => array('Epayment.merchant_id','Epayment.pin')));
        
        $key = array(array('key' => 'key'));
        $epayments = array_merge($epayment,$key);
        //$this->set('var', 'vars');
        $this->set(compact('epayments'));
    }
     * 
     */

    public function api_sourcekey() {
        if ($this->_isJSON() && !$this->request->is('get')) {
            $this->redirect(null, 400);
        }
        $epayments = $this->Epayment->find('all', array('conditions' => array('Epayment.merchant_id' => $this->request->query['merchant_id']), 'fields' => array('Epayment.pin', 'Epayment.application_id')));
        $epayments[0]['Epayment']['sourcekey'] = trim($this->getSourceKey($epayments[0]['Epayment']['application_id']));
        unset ($epayments[0]['Epayment']['application_id']);
        if($epayments[0]['Epayment']['sourcekey'] != '') {
	$data = $this->Epayment->findByMerchantId($this->request->query['merchant_id']);
        $this->Epayment->id = $data['Epayment']['id'];
        $this->Epayment->saveField('date_retrieved',DboSource::expression('NOW()'));
            
        }
        $this->set(compact('epayments'));
    }
    
    protected function getSourceKey($appId) {
        $apikey = '72063deb919178dd02646d543bb58da3';

// API Pin assigned by USAePay
        $pin = 'f4c31e4ed207c0ce';

// Generate a random salt
        $salt = mt_rand();

// Hash the salt using the pin
        $hash = hash_hmac('sha256', $salt, $pin, true);

// Base64 Encode the Hash
        $hash = base64_encode($hash);

// Raw URL Encode the Hash
        $hash = rawurlencode($hash);

// Concatinate the salt and hash
        $hash = '$s256$' . $salt . '$' . $hash;

// Append the apikey and hash values to the request url
        $url = 'https://secure.axiaepay.com/interface/vendorapi/sourcekeys?output=xml&apikey=' . $apikey . '&hash=' . $hash . '&startdate=2012-05-07';
        //echo $url;
// Send Request
        $xml = new SimpleXMLElement(file_get_contents($url));
        //print_r($xml); exit;
        foreach ($xml->SourceKeys->SourceKey as $s) {
        if ($s->ApplicationID == $appId) {
            return $s->SourceKey; 
        }
            
        } return false;
    }
    
}
?>
