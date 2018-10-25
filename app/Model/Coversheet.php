<?php

App::uses('User', 'Model');
App::uses('EmailTimeline', 'Model');
App::uses('CobrandedApplication', 'Model');

class Coversheet extends AppModel {

	public $displayField = 'cobranded_application_id';

	public $actsAs = array('Containable', 'Search.Searchable');

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $validate = array(
		'setup_banking' => array(
			'rule' => array('comparison', '==', '1'),
			'message' => 'Required'
		),
		'setup_drivers_license' => array(
			'rule' => array('comparison', '==', '1'),
			'message' => 'Required'
		),
		'setup_tier_select' => array(
			'rule' => 'notBlank',
			'message' => 'Please select a Tier'
		),
		'setup_equipment_terminal' => array(
			'rule' => array('equipment'),
			'message' => 'Equipment Type: Select One'
		),
		'setup_equipment_gateway' => array(
			'rule' => array('equipment'),
			'message' => 'Equipment Type: Select One'
		),
		'setup_install' => array(
			'rule' => 'notBlank',
			'message' => 'Someone has got to perform the install'
		 ),
		'setup_tier3' => array(
			'rule' => array('tier3'),
			'message' => 'Required'
		),
		'setup_tier4' => array(
			'rule' => array('tier4'),
			'message' => 'Required'
		),
		'setup_tier5_financials' => array(
			'rule' => array('setup_tier5_financials'),
			'message' => 'Required'
		),
		'setup_tier5_processing_statements' => array(
			'rule' => array('setup_tier5_processing_statements'),
			'message' => 'Required'
		),
		'setup_tier5_bank_statements' => array(
			'rule' => array('setup_tier5_bank_statements'),
			'message' => 'Required'
		),
		'setup_starterkit' => array(
			'rule' => array('setup_starterkit'),
			'message' => 'It will not send itself'
		),
		'setup_equipment_payment' => array(
			'rule' => array('equipment_payment'),
			'message' => 'Please Enter Lease Terms'
		),
		'cp_encrypted_sn' => array(
			'rule' => array('debit'),
			'message' => 'Please select Encryption Method'
		),
		'cp_pinpad_ra_attached' => array(
			'rule' => array('debit'),
			'message' => 'Please select Encryption Method'
		),
		'cp_check_guarantee_info' => array(
			'rule' => array('check_guarantee'),
			'message' => 'Please fill in the Info'
		),
		'cp_pos_contact' => array(
			'rule' => array('pos'),
			'message' => 'Please fill in Contact Information'
		),
		'micros' => array(
			'rule' => array('micros'),
			'message' => 'How will the additional per item fee be handled?'
		),
		'gateway_package' => array(
			'rule' => array('gateway_package'),
			'message' => 'What Package?'
		),
		'gateway_gold_subpackage' => array(
			'rule' => array('gateway_gold_subpackage'),
			'message' => 'What Gold Package?'
		),
		'gateway_epay' => array(
			'rule' => array('gateway_epay'),
			'message' => 'ePay Charge Software'
		),
		'gateway_billing' => array(
			'rule' => array('gateway_billing'),
			'message' => 'How will the billing of gateway fees be handled?'
		),
		'moto_online_chd' => array(
			'rule' => array('moto'),
			'message' => 'Internet Merchants: Does the merchant store credit card numbers online?'
		)
	);

	public $findMethods = array('index' => true);

	public $belongsTo = array(
		'CobrandedApplication' => array(
			'className' => 'CobrandedApplication',
			'foreignKey' => 'cobranded_application_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	function equipment() {
		if ($this->data['Coversheet']['setup_equipment_terminal'] != '1' && $this->data['Coversheet']['setup_equipment_gateway'] != '1') {
			return false;
		}
		return true;
	}

	function tier3() {
		if (($this->data['Coversheet']['setup_tier_select'] == '3')) {
			if ($this->data['Coversheet']['setup_tier3'] != '1') {
				return false;
			}
			return true;
		}
		return true;
	}

	function tier4() {
		if (($this->data['Coversheet']['setup_tier_select'] == '4')) {
			if ($this->data['Coversheet']['setup_tier4'] != '1') {
				return false;
			}
			return true;
		}
		return true;
	}

	function setup_tier5_financials() {
		if (($this->data['Coversheet']['setup_tier_select'] == '5')) {
			if ($this->data['Coversheet']['setup_tier5_financials'] != '1') {
				return false;
			}
			return true;
		}
		return true;
	}

	function setup_tier5_processing_statements() {
		if (($this->data['Coversheet']['setup_tier_select'] == '5')) {
			if ($this->data['Coversheet']['setup_tier5_processing_statements'] != '1') {
				return false;
			}
			return true;
		}
		return true;
	}

	function setup_tier5_bank_statements() {
		if (($this->data['Coversheet']['setup_tier_select'] == '5')) {
			if ($this->data['Coversheet']['setup_tier5_bank_statements'] != '1') {
				return false;
			}
			return true;
		}
		return true;
	}

	function setup_starterkit() {
		if ($this->data['Coversheet']['setup_equipment_terminal'] == '1') {
			if ($this->data['Coversheet']['setup_starterkit'] == '') {
				return false;
			}
			return true;
		}
		return true;
	}

	function equipment_payment() {
		if ($this->data['Coversheet']['setup_equipment_payment'] == 'lease') {
			if ($this->data['Coversheet']['setup_lease_price'] == '' || $this->data['Coversheet']['setup_lease_months'] == '') {
				return false;
			}
			return true;
		}
		return true;
	}

	function referrer() {
		if ($this->data['Coversheet']['setup_referrer'] != '') {
			if ($this->data['Coversheet']['setup_referrer_type'] == '' || $this->data['Coversheet']['setup_referrer_pct'] == '') {
				return false;
			}
			return true;
		}
		return true;
	}

	function reseller() {
		if ($this->data['Coversheet']['setup_reseller'] != '') {
			if ($this->data['Coversheet']['setup_reseller_type'] == '' || $this->data['Coversheet']['setup_reseller_pct'] == '') {
				return false;
			}
			return true;
		}
		return true;
	}

	function debit() {
		if ($this->data['Coversheet']['debit'] == 'yes') {
			if ($this->data['Coversheet']['cp_encrypted_sn'] == '' && $this->data['Coversheet']['cp_pinpad_ra_attached'] == '0') {
				return false;
			}
			return true;
		}
		return true;
	}

	function check_guarantee() {
		if ($this->data['Coversheet']['cp_check_guarantee'] == 'yes') {
			if ($this->data['Coversheet']['cp_check_guarantee_info'] == '') {
				return false;
			}
			return true;
		}
		return true;
	}

	function pos() {
		if ($this->data['Coversheet']['cp_pos'] == 'yes') {
			if ($this->data['Coversheet']['cp_pos_contact'] == '') {
				return false;
			}
			return true;
		}
		return true;
	}

	function micros() {
		if ($this->data['Coversheet']['micros'] != '') {
			if ($this->data['Coversheet']['micros_billing'] == '') {
				return false;
			}
			return true;
		}
		return true;
	}

	function moto() {
		if ($this->data['Coversheet']['moto'] == 'internet') {
			if ($this->data['Coversheet']['moto_online_chd'] == '') {
				return false;
			}
			return true;
		}
		return true;
	}

	function gateway_package() {
		$gwOptn = Hash::get($this->data, 'Coversheet.gateway_option');
		$gwPkg = Hash::get($this->data, 'Coversheet.gateway_package');
		if (!empty($gwOptn)) {
			if (empty($gwPkg)) {
				return false;
			}
			return true;
		}
		return true;
	}

	function gateway_gold_subpackage() {
		if ($this->data['Coversheet']['gateway_package'] == 'gold') {
			if ($this->data['Coversheet']['gateway_gold_subpackage'] == '') {
				return false;
			}
			return true;
		}
		return true;
	}

	function gateway_epay() {
		$gwOptn = Hash::get($this->data, 'Coversheet.gateway_option');
		$gwEpay = Hash::get($this->data, 'Coversheet.gateway_epay');
		if (!empty($gwOptn)) {
			if (empty($gwEpay)) {
				return false;
			}
			return true;
		}
		return true;
	}

	function gateway_billing() {
		$gwOptn = Hash::get($this->data, 'Coversheet.gateway_option');
		$gwBil = Hash::get($this->data, 'Coversheet.gateway_billing');
		if (!empty($gwOptn)) {
			if (empty($gwBil)) {
				return false;
			}
			return true;
		}
		return true;
	}

	public function pdfGen($id = null, $data = null) {
		if ($id && $data) {
			$path = WWW_ROOT . 'files' . DS;
			$fp = @fopen($path . 'axia_coversheet.xfdf', 'w');
			if ($fp === false) {
				throw new Exception("Internal Error: Unable to generate coversheet PDF --cannot open file axia_coversheet.xfdf");
			}
			fwrite($fp, $data);
			fclose($fp);
			exec('pdftk ' . $path . 'axia_coversheet.pdf fill_form ' . $path . 'axia_coversheet.xfdf output ' . $path . 'axia_' . $id . '_coversheet.pdf flatten');
			$result = unlink($path . 'axia_coversheet.xfdf');
			return $result;
		} else {
			return false;
		}
	}

	public function sendCoversheet($id = null, $args = array()) {
		if ($id) {
			$this->id = $id;
			$data = $this->findById($id);

			$conditions = array(
				'conditions' => array(
					'cobranded_application_id' => $data['CobrandedApplication']['id'],
				),
				'recursive' => 1
			);

			$CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');

			$appValues = $CobrandedApplicationValue->find(
				'all',
				$conditions
			);

			$appValueArray = array();
			foreach ($appValues as $arr) {
				$appValueArray[] = $arr['CobrandedApplicationValue'];
			}

			$dbaBusinessName = '';
			$corpName = '';
			$userEmail = $this->CobrandedApplication->User->field('email', ['id' => $data['CobrandedApplication']['user_id']]);
			$valuesMap = $this->CobrandedApplication->buildCobrandedApplicationValuesMap($appValueArray);

			if (!empty($valuesMap['DBA'])) {
				$dbaBusinessName = $valuesMap['DBA'];
			}

			if (!empty($valuesMap['CorpName'])) {
				$corpName = $valuesMap['CorpName'];
			}

			$from = array(EmailTimeline::NEWAPPS_EMAIL => 'Axia Online Applications');
			if (stripos($userEmail, EmailTimeline::ENTITY1_EMAIL_DOMAIN) !== false) {
				$to = array(EmailTimeline::I3_UNDERWRITING_EMAIL, EmailTimeline::DATA_ENTRY_EMAIL);
			} else {
				$to = EmailTimeline::ENTITY2_APPS_EMAIL;
			}

			if (!empty($args['to'])) {
				$to = $args['to'];
			}

			$subject = $dbaBusinessName.' - Coversheet';
			$format = 'html';
			$template = 'email_coversheet';
			$viewVars = array();
			$viewVars['business_name'] = $corpName;
			$viewVars['dba'] = $dbaBusinessName;
			$attachments = array($dbaBusinessName . ' coversheet.pdf' => WWW_ROOT . 'files' . DS . 'axia_' . $id . '_coversheet.pdf');

			$args = array(
				'from' => $from,
				'to' => $to,
				'subject' => $subject,
				'format' => $format,
				'template' => $template,
				'viewVars' => $viewVars,
				'attachments' => $attachments
			);

			$response = $this->CobrandedApplication->sendEmail($args);

			unset($args);

			if ($response['success'] == true) {
				$args['cobranded_application_id'] = $data['CobrandedApplication']['id'];
				$args['email_timeline_subject_id'] = EmailTimeline::COVERSHEET_TO_UW;
				$args['recipient'] = $to;
				$response = $this->CobrandedApplication->createEmailTimelineEntry($args);

				if ($response['success'] == true) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function unlinkCoversheet($id = null) {
		if ($id) {
			$result = unlink(WWW_ROOT . DS . 'files' . DS . 'axia_' . $id . '_coversheet.pdf');
			return $result;
		} else {
			return false;
		}
	}

	protected function _findIndex($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['fields'] = array(
				'Coversheet.id',
				'Dba.value',
				'User.id',
				'User.firstname',
				'User.lastname',
				'CobrandedApplication.id',
				'CobrandedApplication.uuid',
				'CobrandedApplication.status',
				'Coversheet.status'
			);
			$query['recursive'] = -1;
			$query['joins'] = array(
				array(
					'table' => 'onlineapp_cobranded_applications',
					'alias' => 'CobrandedApplication',
					'type' => 'LEFT',
					'conditions' => array(
						'Coversheet.cobranded_application_id = CobrandedApplication.id'
					)
				),
				array(
					'table' => 'onlineapp_cobranded_application_values',
					'alias' => 'Dba',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Dba.cobranded_application_id and Dba.name =' . "'DBA'",
					)
				),
				array(
					'table' => 'onlineapp_cobranded_application_values',
					'alias' => 'CorpName',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = CorpName.cobranded_application_id and CorpName.name =' . "'CorpName'",
					)
				),
				array(
					'table' => 'onlineapp_cobranded_application_values',
					'alias' => 'CorpCity',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = CorpCity.cobranded_application_id and CorpCity.name =' . "'CorpCity'",
					)
				),
				array(
					'table' => 'onlineapp_cobranded_application_values',
					'alias' => 'CorpContact',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = CorpContact.cobranded_application_id and CorpContact.name =' . "'CorpContact'",
					)
				),
				array(
					'table' => 'onlineapp_cobranded_application_values',
					'alias' => 'Owner1Name',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Owner1Name.cobranded_application_id and Owner1Name.name =' . "'Owner1Name'",
					)
				),
				array(
					'table' => 'onlineapp_cobranded_application_values',
					'alias' => 'Owner2Name',
					'type' => 'LEFT',
					'conditions' => array(
						'CobrandedApplication.id = Owner2Name.cobranded_application_id and Owner2Name.name =' . "'Owner2Name'",
					)
				),
				array(
					'table' => 'onlineapp_users',
					'alias' => 'User',
					'type' => 'LEFT',
					'conditions' => array(
						'Coversheet.user_id = User.id'
					)
				)
			);
			return $query;
		}
		return $results;
	}

	public $filterArgs = array(
		'search' => array('type' => 'query', 'method' => 'orConditions'),
		'user_id' => array('type' => 'value'),
		'app_status' => array('type' => 'value', 'field' => 'CobrandedApplication.status'),
		'coversheet_status' => array('type' => 'value', 'field' => 'Coversheet.status')
	);

	public function orConditions ($data = array()) {
		$filter = $data['search'];
			$conditions = array(
				'OR' => array(
					'Dba.value ILIKE' => '%' . $filter . '%',
					'CorpName.value ILIKE' => '%' . $filter . '%',
					'CorpCity.value ILIKE' => '%' . $filter . '%',
					'CorpContact.value ILIKE' => '%' . $filter . '%',
					'Owner1Name.value ILIKE' => '%' . $filter . '%',
					'Owner2Name.value ILIKE' => '%' . $filter . '%',
					'User.email ILIKE' => '%' . $filter . '%',
				),
			);
		return $conditions;
	}

}
?>
