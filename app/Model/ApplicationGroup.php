<?php
App::uses('AppModel', 'Model');
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
	public function renewAccessToken($id = null, $resetRenewalCount = false) {
		$data = [
			'access_token' => $this->genRandomSecureToken(self::TOKEN_BYTE_LENGTH),
			'token_expiration' => $this->getRenewedExpirationDate()
		];
		if (!empty($id)) {
			$oldData = $this->find('first',[
				'conditions' => [
					'id' => $id,
				]
			]);
			$data['token_renew_count'] = ($resetRenewalCount)? 0 : $oldData['ApplicationGroup']['token_renew_count'] + 1;
			unset($oldData['ApplicationGroup']['modified']);
			$data = array_merge($oldData['ApplicationGroup'], $data);
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
}
