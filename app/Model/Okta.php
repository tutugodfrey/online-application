<?php
App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');
App::uses('ApiConfiguration', 'Model');

class Okta extends AppModel {

/**
 * This is a stand alone model class requires no table
 *
 * @property useTable
 */
	public $useTable = false;

/**
 * This property will be set to become HttpSocket object
 * to be used like this $this->api->get/post/[...](...)
 *
 * @property request
 */
	public $api = null;

/**
 * This property will be set from data stored in ApiConfiguration's table
 *
 * @property base_url
 */
	public $base_url = null;
/**
 * This property will be set from data stored in ApiConfiguration's table
 *
 * @property base_url
 */
	public $apiUrl = null;

/**
 * This property will be set from data stored in ApiConfiguration's table
 *
 * @property host
 */
	public $apiKey = null;

/**
 * Path to the okta API endpoints
 *
 * @constant API_PATH
 */
	const API_PATH = '/api/v1';

/**
 * Name of okta configuration name.
 * Must match config name stored in database since this string will be used to retrieve
 * Authentication configuration.
 * constants
 */
	const OKTA_CONFIG_NAME = 'Okta';
	const OKTA_CONFIG_NAME_DEV = 'Okta'; //same - no sandbox available

/**
 *  Okta constants
 */
	const USER_INACTIVE = 'DEPROVISIONED';
	const USER_ACTIVE = 'ACTIVE';
	const MFA_ENROLL = 'MFA_ENROLL';
	const MFA_REQ = 'MFA_REQUIRED';

/**
 * Constructor
 *
 * @param mixed $id Model ID
 * @param string $table Table name
 * @param string $ds Datasource
 * @access public
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$configData = $this->getApiConfig();
		if (!empty($configData)) {
			$this->base_url = $configData['ApiConfiguration']['instance_url'];
			$this->apiUrl = $configData['ApiConfiguration']['instance_url'] . self::API_PATH;
			$this->apiKey = $configData['ApiConfiguration']['access_token'];
			$this->api = new HttpSocket();
			$this->api->config['request']['header']['Accept'] = "application/json";
			$this->api->config['request']['header']['Content-Type'] = "application/json";
		} else {
			$this->api = false;
		}
	}

/**
 * __setAuthHeader
 * Sets SSWS Authorization header, expects $this->apiKey to be set and $this->api to be instantiated
 * at the constructor.
 * 
 * @return void
 */
	private function __setAuthHeader() {
		$this->api->config['request']['header']['Authorization'] = "SSWS " . $this->apiKey;
	}

/**
 * getApiConfig
 * Returns API connection configuration metadata.
 * Production config will be returned in production otherwise test configuration will be returned
 *
 * @return array containing ApiConfiguration model metadata about this external API
 */
	public function getApiConfig() {
		$conditions['configuration_name'] = self::OKTA_CONFIG_NAME;
		if (Configure::read('debug') > 0) {
			$conditions['configuration_name'] = self::OKTA_CONFIG_NAME_DEV;
		}
		$ApiConfiguration = ClassRegistry::init('ApiConfiguration');
		return $ApiConfiguration->find('first', ['conditions' => $conditions]);
	}

/**
 * createUser
 * Creates a new user account in okta with the same password.
 * The password must remain encrypted and must be exactly the same as how it is stored in this database.
 *
 * @param array $user must contain all of the following: firstname, lastname, email, password
 * @throws Exception
 * @return array containing okta user account data or empty array when nothing is found
 */
	public function createUser($user) {
		$this->__setAuthHeader();
		$newUser = [
			"profile" => [
				"firstName" => $user['User']['firstname'],
				"lastName" => $user['User']['lastname'],
				"email" => $user['User']['email'],
				"login" => $user['User']['email'],
			],
			"credentials" => [
				"password" => $user['User']['password']
			]
		];
		$response = $this->api->post($this->apiUrl . "/users?activate=true", json_encode($newUser));
		$user = [];
		if ($response->isOk()) {
			$user = json_decode($response->body, true);
		} else {
			CakeLog::write('debug', print_r(json_decode($response->body, true), true));
			throw new Exception('Unexpected API error ocurred, try again later.');
		}
		return $user;
	}

/**
 * updateLoginEmail
 * Updates the email used as login in the users okta account for okta primary authentication.
 * The user email must always be the same in both systems and therefore must be synced when changed
 *
 * @param string $oldLoginEmail the original email,
 * @param string $newLoginEmail the new email to use for loggin in/primary authentication,
 * @throws Exception 
 * @return boolean true on success will throw exception on falure 
 */
	public function updateLoginEmail($oldLoginEmail, $newLoginEmail) {
		try {
			$user = $this->findUser($oldLoginEmail);
			//No user found then nothing to update
			if (!empty($user) && hash::get($user, 'status') === self::USER_ACTIVE) {
				$update = [
					"profile" => [
						"email" => $newLoginEmail,
	    				"login" => $newLoginEmail,
				]];
				$response = $this->api->post($this->apiUrl . "/users/{$user['id']}", json_encode($update));

				$response = json_decode($response->body, true);
				if (!empty($response['errorCode'])) {
					CakeLog::write('debug', print_r($response, true));
					throw new Exception($response['errorSummary']);
				}
				return true;
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
/**
 * deactivateUser
 * Deactivates a user with an email string parameter
 *
 * @param string $userEmail the email used when user was created/activated,
 * @throws Exception 
 * @return boolean true on success will throw exception on falure 
 */
	public function deactivateUser($userEmail) {
		try {
			$user = $this->findUser($userEmail);
			if (!empty($user)) {
				//Is user still active?
				if (hash::get($user, 'status') === self::USER_ACTIVE) {
					$deactivateUserUrl = Hash::get($user, '_links.deactivate.href');
					$response = $this->api->post($deactivateUserUrl);

					$resData = json_decode($response->body, true);
					if (!empty($resData['errorCode'])) {
						CakeLog::write('debug', print_r($resData, true));
						throw new Exception($resData['errorSummary']);
					}
				}
			}
			return true;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

/**
 * findUser
 * Searches a user with a search string parameter
 * Acceptable search string should the user's email since okta requires users emails to 
 * be unique for each user.
 *
 * @param string $srchStr acceptable search strings are firstName, lastName or email
 * @throws Exception
 * @return array containing user or empty array when nothing is found
 */
	public function findUser($srchStr) {
		$this->__setAuthHeader();
		$response = $this->api->get($this->apiUrl . "/users?q=$srchStr&limit=1");
		$user = [];
		if ($response->isOk()) {
			$data = json_decode($response->body, true);
			if (!empty($data)) {
				$getUserRequestUrl = Hash::get($data, '0._links.self.href');
				$response = $this->api->get($getUserRequestUrl);
				$user = json_decode($response->body, true);
			}
		} else {
			CakeLog::write('debug', print_r(json_decode($response->body, true), true));
			throw new Exception('Unexpected API error ocurred, try again later.');
		}
		return $user;
	}

/**
 * chngPwd
 * Updates user password to the suplied new password parameter.
 * Password must always be the same in both systems.
 * Passwords must be sent exactly as they are stored in the database.
 *
 * @param string $userEmail the email used when user was created/activated,
 * @param string $newPwd the password with which to update the old one
 * @throws Exception
 * @return boolean true on success will throw exception on falure 
 */
	public function chngPwd($userEmail, $newPwd) {
		try {
			$user = $this->findUser($userEmail);
			if (!empty($user)) {
				$updates = [
					'credentials' => ['password' => ['value' => $newPwd]],
				];
				$chngPwUrl = $this->apiUrl.'/users/'.Hash::get($user, 'id');
				$response = $this->api->post($chngPwUrl, json_encode($updates));
				$response = json_decode($response->body, true);
				if (!empty($response['errorCode'])) {
					CakeLog::write('debug', print_r($response, true));
					throw new Exception(Hash::get($response, 'errorSummary').' '. Hash::get($response, 'errorCauses.0.errorSummary'));
				}
				return true;
			} else {
				throw new Exception("Okta user with email $userEmail not found.");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

/**
 * resetFactors
 * Resets all factors enrolled for authentication.
 * This will cause the user to be asked to re-enroll in MFA
 *
 * @param string $userEmail the email used when user was created/activated,
 * @throws Exception
 * @return boolean true on success will throw exception on falure 
 */
	public function resetFactors($userEmail) {
		try {
			$user = $this->findUser($userEmail);
			if (!empty($user)) {
				$resetFactorsUrl = Hash::get($user, '_links.resetFactors.href');
				$response = $this->api->post($resetFactorsUrl);
				$response = json_decode($response->body, true);
				if (!empty($response['errorCode'])) {
					CakeLog::write('debug', print_r($response, true));
					throw new Exception(Hash::get($response, 'errorSummary').' '. Hash::get($response, 'errorCauses.0.errorSummary'));
				}
				return true;
			} else {
				throw new Exception("Okta user with email $userEmail not found.");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

/**
 * primaryAuth
 * Okta Primary Authentication
 * Since authentication is done programatically a 401 Unauthorized response will be interpreted as user account not created in Okta,
 * and this function will return false. The same will ocurr if authentication succeeds but user is not yet enrolled in MFA.
 *
 * @param string $userEmail the user email also used as login,
 * @param string $password the password with which to login (must match the password the user has in the online application system)
 * @param boolean $returnResponseData defaults to false but if true, the API response data will be returned
 * @return mixed array|boolean false on success will throw exception on falure
 * @throws Exception
 */
	public function primaryAuth ($userEmail, $password, $returnResponseData = false) {
		$data = [
			"username" => $userEmail,
			"password" => $password,
			"options" => [
				"multiOptionalFactorEnroll" => false,
				"warnBeforePasswordExpired" => false,
			]
		];
		$response = $this->api->post($this->apiUrl . "/authn", json_encode($data));
		$respData = json_decode($response->body, true);

		if ($returnResponseData && $response->isOk()) {
			return $respData;
		}

		//Return false when user authentication fails or user is not MFA enrolled
		if ($response->code == '401' || Hash::get($respData, 'status') === self::MFA_ENROLL) {
			return false;
		} elseif ($response->isOk()) {
			return $respData;
		} else {
			CakeLog::write('debug', print_r(json_decode($response->body, true), true));
			throw new Exception('Unexpected Okta API error ocurred, try again later.');
		}
	}

/**
 * verifyOktaMfa
 * This method can be used to verify both a push factor and submitted TOTP factor depending on the $factorId parameter
 * and the data.
 * For TOTP verification the fatctorId param must correspond to the TOTP factor and the data param must contain the "passCode" key and its value.
 * Omit the "passCode" for okta push factor verification calls but include the push factor factorId.
 * 
 * @param string $stateToken the state token returned after primary authentication
 * @param string  $factorId the factor id corresponding the okta push factor
 * @return array
 * @throws Exception
 */
	public function verifyOktaMfa ($data, $factorId) {
		$response = $this->api->post($this->apiUrl . "/authn/factors/$factorId/verify", json_encode($data));

		if ($response->isOk() || $response->code == 403) {
			return json_decode($response->body, true);
		} else {
			CakeLog::write('debug', print_r(json_decode($response->body, true), true));
			throw new Exception('Unexpected Okta API error ocurred, try again later.');
		}
	}

/**
 * enrollPushFactor
 * Enroll a user to Okta Verify push factor endpoint using the okta user id
 *
 * @param string $oktaUserId the okta user id
 * @return array the response data which inclides the QR code
 * @throws Exception
 */
	public function enrollPushFactor($oktaUserId) {
		$this->__setAuthHeader();
		$data = [
			"factorType" => 'push',
			"provider" => 'OKTA',
		];
		$response = $this->api->post($this->apiUrl . "/users/$oktaUserId/factors", json_encode($data));
		$respData = json_decode($response->body, true);

		if ($response->isOk()) {
			return $respData;
		} else {
			CakeLog::write('debug', print_r(json_decode($response->body, true), true));
			throw new Exception('Unexpected Okta API error ocurred, try again later.');
		}
	}

/**
 * pollPushFactorActivation
 * This function can be used to do polling request to check when a user has finished enrolling in push factor while a user
 * is in the process of performing said action. 
 * It requires the polling URL returned by $this->enrollPushFactor(..) method since it is specific to that user's
 * enrollment API call
 *
 * @param string $pollingURL the polling URL returned after calling $this->enrollPushFactor(..) method
 * @return array the response data which inclides the QR code
 * @throws Exception
 */
	public function pollPushFactorActivation($pollingURL) {
		$this->__setAuthHeader();
		$response = $this->api->post($pollingURL);
		$respData = json_decode($response->body, true);

		if ($response->isOk()) {
			return $respData;
		} else {
			CakeLog::write('debug', print_r(json_decode($response->body, true), true));
			throw new Exception('Unexpected Okta API error ocurred, try again later.');
		}
	}
}
