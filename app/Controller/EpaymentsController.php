<?php
App::uses('AppController', 'Controller');
class EpaymentsController extends AppController {

	//public $components = array ('RequestHandler');
	public $components = array('RequestHandler','Security');
	public $scaffold = 'admin';

	public function beforeFilter() {
		parent::beforeFilter();
		if ($this->_isJSON() && $this->request->is('get')) {
			$this->Security->unlockedActions= array('api_sourcekey');
		}
	}

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

	public function api_sourcekey() {
		if ($this->_isJSON() && !$this->request->is('get')) {
			$this->redirect(null, 400);
		}
		$epayments = $this->Epayment->find('all', array('conditions' => array('Epayment.merchant_id' => $this->request->query['merchant_id']), 'fields' => array('Epayment.pin', 'Epayment.application_id')));
		$epayments[0]['Epayment']['sourcekey'] = trim($this->getSourceKey($epayments[0]['Epayment']['application_id']));
		unset ($epayments[0]['Epayment']['application_id']);
		if ($epayments[0]['Epayment']['sourcekey'] != '') {
		$data = $this->Epayment->findByMerchantId($this->request->query['merchant_id']);
		$this->Epayment->id = $data['Epayment']['id'];
		$this->Epayment->saveField('date_retrieved', DboSource::expression('NOW()'));

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

/**
 * Create admin prefix Index method
 *
 * @return null
 */
	public function admin_index() {
		$this->paginate = array(
			'contain' => array('User' => array('fields' => array('id', 'fullname')))
		);
		$this->set('epayments', $this->paginate());
	}
}
?>
