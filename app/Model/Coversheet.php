<?php

App::uses('User', 'Model');
App::uses('EmailTimeline', 'Model');
App::uses('CobrandedApplication', 'Model');

class Coversheet extends AppModel {
	/**
	* MIN_LEV
	* Minimum levenshtein distance allowed to consider two worrds similar enough to safely assume they are the same word
	*
	* @var
	*/
	const MIN_LEV = 2;

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
		),
		'org_name' => array(
			'checkOrgRegionSubRegion' => array(
				'rule' => array('checkOrgRegionSubRegion'),
				'required' => false
			),
		),
		'region_name' => array(
			'checkOrgRegionSubRegion' => array(
				'rule' => array('checkOrgRegionSubRegion'),
				'required' => false
			),
		),
		'subregion_name' => array(
			'checkOrgRegionSubRegion' => array(
				'rule' => array('checkOrgRegionSubRegion'),
				'required' => false
			),
		),
		'expected_install_date' => array(
			'dateIsNotInThePast' => array(
				'rule' => array('dateIsNotInThePast'),
				'required' => false,
				'allowEmpty' => true
			),
		),
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

/**
 * dateIsNotInThePast
 * Custom validation rule checks whether a date is in the past
 * 
 * @param array $check an associated model's Region id
 * @return array list of subregions that belong to the passed region
 */
	public function dateIsNotInThePast($check) {
		$field = key($check);
		$fieldName = Inflector::humanize($field);
		$dateVal = Hash::get($check, $field);
		if (empty($dateVal)) {
			return true;
		}
		$checkTimeStamp = strtotime($dateVal);
		$curTimeStamp = strtotime(date('Y-m-d'));

		if ($checkTimeStamp < $curTimeStamp) {
			return "$fieldName cannot be in the past!";
		}
		return true;
	}

/**
 * checkOrgRegionSubRegion
 * Custom validation rule checks whether Organization was in inputted when a Region and/or a subregion was submitted and vicecersa
 * If no Org entered then cannot enter a region and subregion
 * If Org entered but not a Region then cannot enter a subregion
 * if no Org and Region then cannot enter subregion
 * 
 * @param array $check an associated model's Region id
 * @return array list of subregions that belong to the passed region
 */
	public function checkOrgRegionSubRegion($check) {
		$field = key($check);
		$fieldName = Inflector::humanize($field);
		$org = Hash::get($this->data, 'Coversheet.org_name');
		$region = Hash::get($this->data, 'Coversheet.region_name');
		$subregion = Hash::get($this->data, 'Coversheet.subregion_name');

		if ($field === 'org_name' && empty($org) && (!empty($region) || !empty($subregion))) {
			return __("A parent Organization is required if Region or Subregion are entered");
		} elseif ($field === 'region_name' && empty($region) && !empty($subregion)) {
			return __("Region is required if adding a Subregion");
		}
		return true;
	}

/**
 * afterSave callback
 *
 * @param $created boolean
 * @param $options array
 * @return void
 */
	public function afterSave($created, $options = array()) {
		if (!empty($this->data['Coversheet']['org_name']) || !empty($this->data['Coversheet']['region_name']) || !empty($this->data['Coversheet']['subregion_name'])) {
			$this->distinctSaveOrgData($this->data);
		}
	}

/**
 * distinctSaveOrgData
 * Function validates that organization region and sub-region values do not already exist in the database
 * and saves them IFF they are new.
 * Data may not to save associated child region and subregion data if the provided names for existing parent organization or parent region are not found.
 *
 * @param array $data Coversheet data containing org_name, region_name and/or subregion_name fields
 * @return boolean true on success | false on falure
 */
	public function distinctSaveOrgData($data) {
		if (!empty(Hash::get($data, 'Coversheet'))) {
			$data = Hash::get($data, 'Coversheet');
		}
		//Sanitize strings
		$data['org_name'] = $this->trimExtra(Hash::get($data, 'org_name'));
		$data['region_name'] = $this->trimExtra(Hash::get($data, 'region_name'));
		$data['subregion_name'] = $this->trimExtra(Hash::get($data, 'subregion_name'));
		$orgId = null;
		$regionId = null;
		$isNewOrg = false;
		$isNewRegion = false;
		if (!empty($data['org_name'])) {
			//Check if org exists
			if ($this->isUniqueOrgName($data)) {
				$orgId = CakeText::uuid();
				$isNewOrg = true;
			} else {
				//get organization.id
				$org = $this->getSimilarOrgs($data['org_name']);
				if (!empty($org)) {
					foreach ($org as $organization) {
						if ($this->wordsAreWithinMinLevenshtein($data['org_name'], [$organization['Organization']['name']], self::MIN_LEV) !== false) {
							$orgId = $organization['Organization']['id'];
							break;
						}
					}
				}
			}
		}
		//cannot save associated data if existing parent Organization is not found using provided org_name.
		if ($isNewOrg === false && empty($orgId) && (!empty($data['region_name']) || !empty($data['subregion_name']))) {
			return false;
		} elseif ($isNewOrg) {
			//Save Org IFF isNewOrg
			try {
				$OrgModel = new Model(array('table' => 'organizations', 'ds' => $this->connection));
				if (!$OrgModel->save(['id' => $orgId, 'name' => $data['org_name']])) {
					return false;
				}
			} catch (Exception $e) {
				return false;
			}
		}

		$parentOrgId = ($isNewOrg)? null : $orgId;
		if (!empty($data['region_name'])) {
			//Check if region exists
			if ($this->isUniqueRegionName($data, $parentOrgId)) {
				$regionId = CakeText::uuid();
				$isNewRegion = true;
			} else {
				//get region.id
				$regions = $this->getSimilarRegions($data['region_name'], $parentOrgId);
				if (!empty($regions)) {
					foreach ($regions as $region) {
						if ($this->wordsAreWithinMinLevenshtein($data['region_name'], [$region['Region']['name']], self::MIN_LEV) !== false) {
							$regionId = $region['Region']['id'];
							break;
						}
					}
				}
			}
		}

		//cannot save associated data if existing parent region is not found using provided region_name.
		if ($isNewRegion === false && empty($regionId) && !empty($data['subregion_name'])) {
			return false;
		} elseif ($isNewRegion) {
			//Save Region IFF is new
			try {
				$RegionModel = new Model(array('table' => 'regions', 'ds' => $this->connection));
				if (!$RegionModel->save(['id' => $regionId, 'organization_id' =>$orgId, 'name' => $data['region_name']])) {
					return false;
				}
			} catch (Exception $e) {
				return false;
			}
		}

		$parentRegionId = ($isNewRegion)? null : $regionId;
		if (!empty($data['subregion_name'])) {
			//Check if subregion exists
			if ($this->isUniqueSubRegionName($data, $parentOrgId, $parentRegionId)) {
				try {
					$SubRegionModel = new Model(array('table' => 'subregions', 'ds' => $this->connection));
					if (!$SubRegionModel->save(['region_id' => $regionId, 'organization_id' => $orgId, 'name' => $data['subregion_name']])) {
						return false;
					}
				} catch (Exception $e) {
					return false;
				}
			}
		}
		return true;
	}

/**
 * isUniqueOrgData
 * Check uniqueness of Organization names
 * 
 * @param array $data sungle dimentional array containing org_name field
 * @return mixed boolean false when value is empty or a matching record is found otherwise true
 */
	public function isUniqueOrgName($data) {
		$orgName = Hash::get($data, 'org_name');
		if (empty($orgName)) {
			return false;
		}
		$result = $this->getSimilarOrgs($orgName);
		if (empty($result) ) {
			return true;
		} elseif ($this->wordsAreWithinMinLevenshtein($orgName, Hash::extract($result, '{n}.Organization.name'), self::MIN_LEV) === false) {
			return true;
		}
		return false;
	}

/**
 * isUniqueRegionName
 * Check uniqueness of Region names
 * This function will check whether the Conversheet.org_name exists and has an associated Conversheet.region_name
 * Function will return false if Conversheet.org_name and orgId parameter are both empty or when Conversheet.org_name field is empty
 * 
 * @param array $data sungle dimentional array containing org_name and region_name fields
 * @param string $orgId optional if known, the organization.id that is or should be associated with the region name
 * @return mixed boolean false when:
 *   Conversheet.org_name and orgId parameter are both empty or when Conversheet.org_name field is empty
 *	 Value is empty or a matching record is found otherwise
 *	 boolean true when no existing record exist
 */
	public function isUniqueRegionName($data, $orgId = null) {
		$orgName = Hash::get($data, 'org_name');
		$regionName = Hash::get($data, 'region_name');
		if ((empty($regionName) && empty($orgName)) || (empty($orgName) && empty($orgId))) {
			return false;
		}

		if (empty($orgId)) {
			$orgData = $this->getSimilarOrgs($orgName);
			if (count($orgData) > 1) {
				foreach ($orgData as $organization) {
					if ($this->wordsAreWithinMinLevenshtein($orgName, [$organization['Organization']['name']], self::MIN_LEV) !== false) {
						$orgId = $organization['Organization']['id'];
						break;
					}
				}
			} else {
				$orgId = Hash::get($orgData, '0.Organization.id');
			}
		}

		$result = $this->getSimilarRegions($regionName, $orgId);
		if (empty($result) ) {
			return true;
		} elseif ($this->wordsAreWithinMinLevenshtein($regionName, Hash::extract($result, '{n}.Region.name'), self::MIN_LEV) === false) {
			return true;
		}
		return false;
	}

/**
 * isUniqueSubRegionName
 * Check uniqueness of SubRegion names
 * This function will check whether the Conversheet.org_name exists and has an associated Conversheet.region_name
 * Function will return false if Conversheet.org_name and orgId parameter are both empty or when Conversheet.org_name field is empty
 * 
 * @param array $data sungle dimentional array containing all org_name, region_name and subregion_name fields
 * @param string $orgId optional if known, the organization.id that is or should be associated with the child region and subregion
 * @param string $regionId optional if known, the region.id that is or should be associated with the subregion name
 * @return mixed boolean false when:
 *   Conversheet.org_name and orgId parameter are both empty or when Conversheet.org_name and $regionId are both empty
 *	 Value is empty or a matching record is found otherwise
 *	 boolean true when no existing record exist
 */
	public function isUniqueSubRegionName($data, $orgId = null, $regionId = null) {
		$orgName = Hash::get($data, 'org_name');
		$regionName = Hash::get($data, 'region_name');
		$subRegionName = Hash::get($data, 'subregion_name');
		if (empty($subRegionName) || (empty($regionName) && empty($regionId)) || (empty($orgName) && empty($orgId))) {
			return false;
		}

		if (empty($orgId)) {
			$orgData = $this->getSimilarOrgs($orgName);
			if (count($orgData) > 1) {
				foreach ($orgData as $organization) {
					if ($this->wordsAreWithinMinLevenshtein($orgName, [$organization['Organization']['name']], self::MIN_LEV) !== false) {
						$orgId = $organization['Organization']['id'];
						break;
					}
				}
			} else {
				$orgId = Hash::get($orgData, '0.Organization.id');
			}
		}

		if (empty($regionId)) {
			$regionData = $this->getSimilarRegions($regionName);
			if (count($regionData) > 1) {
				foreach ($regionData as $region) {
					if ($this->wordsAreWithinMinLevenshtein($regionName, [$region['Region']['name']], self::MIN_LEV) !== false) {
						$regionId = $region['Region']['id'];
						break;
					}
				}
			} else {
				$regionId = Hash::get($regionData, '0.Region.id');
			}
		}

		$result = $this->getSimilarSubregions($subRegionName, $regionId, $orgId);
		if (empty($result) ) {
			return true;
		} elseif ($this->wordsAreWithinMinLevenshtein($subRegionName, Hash::extract($result, '{n}.SubRegion.name'), self::MIN_LEV) === false) {
			return true;
		}
		return false;
	}

	/**
	 * getSimilarOrgs
	 * Finds data of organizations that are textually similar to the provided org name
	 *
	 * @param string $orgName the name of an organization
	 * @return array
	 */
	public function getSimilarOrgs($orgName) {
		$Organization = new Model(array('name' => 'Organization', 'table' => 'organizations', 'ds' => $this->connection));
		return $Organization->find('all', array('conditions' => array(
			'OR' => array(
				array('lower(name)' => strtolower($orgName)),
				//Must check LIKENESS in both directions because
				//("Word" ILIKE "Words") === false but ("Words ILIKE Word") === true
				//but they are all the same word obviously
				array("name ILIKE '%$orgName%'"),
				array("'$orgName' ILIKE '%'|| name ||'%'")
			)
		)));
	}

/**
 * getSimilarRegions
 * Finds a list of regions that are textually similar to the provided name
 *
 * @param string $name the name of an organization
 * @param string $parentOrgId optional organization.id associated with or is the parent of the region
 * @return array
	 */
	public function getSimilarRegions($regionName, $parentOrgId = null) {
		$conditions = array();
		if (!empty($parentOrgId)) {
			$conditions['organization_id'] = $parentOrgId;
		}
		$conditions['OR'] = array(
			array('lower(name)' => strtolower($regionName)),
			//Must check LIKENESS in both directions because
			//("Word" ILIKE "Words") === false but ("Words ILIKE Word") === true
			//but they are all the same word obviously
			array("name ILIKE '%$regionName%'"),
			array("'$regionName' ILIKE '%'|| name ||'%'")
		);
		$Region = new Model(array('name' => 'Region', 'table' => 'regions', 'ds' => $this->connection));
		return $Region->find('all', array('conditions' => $conditions));
	}

/**
 * getSimilarSubregions
 * Finds a list of subregions that are textually similar to the provided name
 *
 * @param string $subRegionName the name of an subregion
 * @param string $parentRegionId optional region.id associated with or is the parent of the subregion
 * @param string $parentOrgId optional organization.id associated with or is the parent of the subregion
 * @return array
 */
	public function getSimilarSubregions($subRegionName, $parentRegionId = null, $parentOrgId = null) {
		$conditions = array();
		if (!empty($parentRegionId)) {
			$conditions['region_id'] = $parentRegionId;
		}
		if (!empty($parentOrgId)) {
			$conditions['organization_id'] = $parentOrgId;
		}
		$conditions['OR'] = array(
			array('lower(name)' => strtolower($subRegionName)),
			//Must check LIKENESS in both directions because
			//("Word" ILIKE "Words") === false but ("Words ILIKE Word") === true
			//but they are all the same word obviously
			array("name ILIKE '%$subRegionName%'"),
			array("'$subRegionName' ILIKE '%'|| name ||'%'")
		);
		$SubRegion = new Model(array('name' => 'SubRegion', 'table' => 'subregions', 'ds' => $this->connection));
		return $SubRegion->find('all', array('conditions' => $conditions));
	}
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
				$to = array(EmailTimeline::I3_UNDERWRITING_EMAIL);
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
			$attachments = array(preg_replace("/\"|'/", "", $dbaBusinessName) . ' coversheet.pdf' => WWW_ROOT . 'files' . DS . 'axia_' . $id . '_coversheet.pdf');

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
				$args['recipient'] = is_array($to)? implode(';', $to) : $to;
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

/**
 * createNew
 * Creates new coversheet for the given user and application id
 *
 * @param integer $appId a CobrandedApplication.id
 * @param integer $uid a User.id
 * @param array $data optional single dimention array of coversheet data
 * @throws Exception when required parameters are missing
 */
	public function createNew($appId, $uid, $data = array()) {
		if (empty($appId) || empty($uid)) {
			throw new Exception('Cannot create new coversheet without required parameters');
		}
		$data['cobranded_application_id'] = $appId;
		$data['user_id'] = $uid;
		$data['status'] = 'saved';
		$this->create();
		return $this->save($data);
	}

}
?>
