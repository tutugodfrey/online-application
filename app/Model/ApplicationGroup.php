<?php
App::uses('AppModel', 'Model');
App::uses('User', 'Model');
/**
 * ApplicationGroup Model
 *
 */
class ApplicationGroup extends AppModel {
/**
 * Number of days that will be added to a renewed access token
 * @var EXP_DAYS_ADD
 */
	public const EXP_DAYS_ADD = 2;
/**
 * Number of months that will be added to a client_pw_expiration
 * @var EXP_DAYS_ADD
 */
	public const EXP_MONTHS_ADD = 2;

/**
 * Byte length for access token generation
 * @var TOKEN_BYTE_LENGTH
 */
	public const TOKEN_BYTE_LENGTH = 32;

/**
 * createNewGroup
 * Creates and returns a new application group
 * 
 * @return array the new app group data
 */
	public function createNewGroup() {
		return $this->renewAccessToken();
	}

/**
 * renewAccessToken
 * 
 * @param  string  $id                Optional ApplicationGroup.id to renew existing token or null to create a new group
 * @param  boolean $resetRenewalCount if true, the renewal count will be reset
 * @return array                     the ApplicationGroup record will be returned.
 */
	public function renewAccessToken($id = null) {
		$data = [
			'access_token' => $this->genRandomSecureToken(self::TOKEN_BYTE_LENGTH),
			'token_expiration' => $this->getRenewedExpirationDate()
		];
		if (!empty($id)) {
			$data['id'] = $id;
		} else {
			$User = ClassRegistry::init('User');
			$data['client_access_token'] = $this->genRandomSecureToken(20);
			$data['client_password'] = $User->encrypt($User->generateRandPw(), Configure::read('Security.OpenSSL.key'));
			$data['client_pw_expiration'] = date_format(date_modify(new DateTime(date("Y-m-d H:i:s")), '+'.self::EXP_MONTHS_ADD. ' month'), 'Y-m-d');

			$this->create();
		}
		return $this->save($data);
	}

/**
 * getRenewedExpirationDate
 * returns a renewed expiration date set to self::EXP_DAYS_ADD days from now
 * @return string representation of a date in Y-m-d H:i:s format
 */
	public function getRenewedExpirationDate() {
		//add two days worth of seconds to current day 
		//(seconds * minutes * hours) = number of seconds in a day
		$secsToAdd = ((60*60*24)* self::EXP_DAYS_ADD);
		return date('Y-m-d H:i:s', time() + $secsToAdd);
	}

/**
 * findByAccessToken
 * Checks if provided access token is expired and if not, the data about
 * the ApplicationGroup containing provided valid access toke will be returned.
 * Otherwise false will be returned.
 * If the checkExpiration parameter is set to false the access token expiraion
 * will not be checked and data will always be returned if found.
 * 
 * @param  string $token access token
 * @param  boolean checkExpiration 
 * @return mixed array|boolean true if expired or does not exist otherwise false
 */
	public function findByAccessToken($token, $checkExpiration = true) {

		if ($checkExpiration == false || !$this->isTokenExpired($token)) {
			return $this->find('first',[
				'conditions' => [
					'access_token' => $token,
				]
			]);
		}
		return false;
	}

/**
 * findByClientAccessToken
 * The data about the ApplicationGroup containing provided valid client access toke will be returned.
 * 
 * @param  string $token access token
 * @return array
 */
	public function findByClientAccessToken($clientAccessToken) {
		return $this->find('first',[
			'conditions' => [
				'client_access_token' => $clientAccessToken,
			]
		]);
	}

/**
 * isTokenExpired
 * Checks is provided access token is expired.
 * 
 * @param  string $token access token
 * @return boolean true if expired or does not exist otherwise false
 */
	public function isTokenExpired($token) {
		$expirationDate = $this->find('first',[
			'fields'=> ['token_expiration'],
			'conditions' => [
				'access_token' => $token,
			]
		]);
		return (empty($expirationDate)? true : (time() > strtotime($expirationDate['ApplicationGroup']['token_expiration'])));
	}
/**
 * isClientPwExpired
 * Checks if client password has expired.
 * 
 * @param  string $id record id
 * @return boolean true if expired or does not exist otherwise false
 */
	public function isClientPwExpired($id) {
		$expirationDate = $this->find('first',[
			'fields'=> ['client_pw_expiration'],
			'conditions' => [
				'id' => $id,
			]
		]);
		return (empty($expirationDate)? true : (time() > strtotime($expirationDate['ApplicationGroup']['client_pw_expiration'])));
	}


/**
 * validateClientCredentials
 * Checks is provided client access token and password match existing and if so returns corresponding record
 * 
 * @param  string $clientAccessToken client access token
 * @param  string $clientPassword client password
 * @return array
 */
	public function validateClientCredentials($clientAccessToken, $clientPassword) {
		if (!$this->isEncrypted($clientPassword)) {
			$clientPassword = $this->encrypt($clientPassword, Configure::read('Security.OpenSSL.key'));
		}
		$clientUser = $this->find('first', array(
			'conditions' => array(
				'client_access_token' => $clientAccessToken,
				'client_password' => $clientPassword,
				'client_fail_login_count < 6',
				'client_account_locked' => false
			)
		));
		if (!empty($clientUser) && $this->isClientPwExpired($clientUser['ApplicationGroup']['id'])) {
			return ['password_expired' => true];
		}
		return $clientUser;
	}

/**
 * resetPartialClientCredentials
 * This method should be used to unlock client accounts. A new password will be generated  however password expiration 
 * will not be renewed, since it is desired to limit the ammount of time the account is accessible.
 *
 * @param string $id $this->id
 * @return array the updated record
 */
	public function resetPartialClientCredentials($id) {
		if (!empty($id)) {
			$User = ClassRegistry::init('User');
			$data = [
				'id' => $id,
				'client_password' => $this->encrypt($User->generateRandPw(), Configure::read('Security.OpenSSL.key')),
				'client_fail_login_count' => 0,
				'client_account_locked' => false
			];
			return $this->save($data);
		}
	}
/**
 * resetFullClientCredentials
 * Renews client credentials which will allow customer access to their CobrandedApplications
 *
 * @param string $id $this->id
 * @return array the updated record
 */
	public function resetFullClientCredentials($id) {
		if (!empty($id)) {
			$User = ClassRegistry::init('User');
			$data = [
				'id' => $id,
				'client_password' => $this->encrypt($User->generateRandPw(), Configure::read('Security.OpenSSL.key')),
				'client_pw_expiration' => date_format(date_modify(new DateTime(date("Y-m-d H:i:s")), '+'.self::EXP_MONTHS_ADD. ' month'), 'Y-m-d'),
				'client_fail_login_count' => 0,
				'client_account_locked' => false
			];
			return $this->save($data);
		}
	}

/**
 * trackIncorrectLogIn
 * tracks the current user inccorrect log in attemts
 *
 * @param string $eamil User.email
 * @return integer the current count of failed attempts if an active user exists or 0 if user not found
 */
	public function trackIncorrectLogIn($clientAccessToken, $reset = false) {
		$data = $this->find('first', array(
			'fields' => array('id', 'client_password', 'client_pw_expiration', 'client_fail_login_count', 'client_account_locked'),
			'conditions' => array(
				'client_access_token' => $clientAccessToken,
			)
		));
		$User = ClassRegistry::init('User');
		if (!empty($data) && $reset) {
			
			//reset
			$data['ApplicationGroup']['client_fail_login_count'] = 0;
			$this->clear();
			$this->save($data, array('validate' => false));
		} elseif (!empty($data) && $data['ApplicationGroup']['client_account_locked'] == false) {
			if ($data['ApplicationGroup']['client_fail_login_count'] < User::MAX_LOG_IN_ATTEMPTS) {
				$data['ApplicationGroup']['client_fail_login_count'] += 1;
				$this->save($data, array('validate' => false));
		 	} 
		 	if ($data['ApplicationGroup']['client_fail_login_count'] >= User::MAX_LOG_IN_ATTEMPTS) {
				//change password and lock account
				$data['ApplicationGroup']['client_password'] = $this->encrypt($User->generateRandPw(), Configure::read('Security.OpenSSL.key'));
				$data['ApplicationGroup']['client_account_locked'] = true;
				$this->clear();
				$this->save($data, array('validate' => false));
			}
		}
		
		return Hash::get($data, 'ApplicationGroup.client_fail_login_count', 0);
	}

}
