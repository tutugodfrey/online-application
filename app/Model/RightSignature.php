<?php
App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');
App::uses('ApiConfiguration', 'Model');

class RightSignature extends AppModel {

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
 * This property will be set from data stroed in ApiConfiguration's table
 *
 * @property host
 */
	public $base_url = 'https://api.rightsignature.com';

/**
 * Path to the rightsignature API endpoints
 *
 * @constant API_PATH
 */
	const API_V1_PATH = '/public/v1';

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
			$this->renewAccessTokenIfExpired($configData);
			$this->api = new HttpSocket();
			$this->api->config['request']['header']['Authorization'] = "Bearer " . $configData['ApiConfiguration']['access_token'];
			$this->api->config['request']['header']['Content-Type'] = "application/json";

		} else {
			$this->api = false;
		}
	}

/**
 * This is a stand alone model class requires no table
 *
 * constants
 */
	const RS_CONFIG_NAME = 'rightsignature';
	const RS_CONFIG_NAME_DEV = 'rightsignature test';

/**
 * getApiConfig
 * Returns API connection configuration metadata.
 * Production config will be returned in production otherwise test configuration will be returned
 *
 * @return array containing ApiConfiguration model metadata about this external API
 */
	public function getApiConfig() {
		$conditions['configuration_name'] = self::RS_CONFIG_NAME;
		if (Configure::read('debug') > 0) {
			$conditions['configuration_name'] = self::RS_CONFIG_NAME_DEV;
		}
		$ApiConfiguration = ClassRegistry::init('ApiConfiguration');
		return $ApiConfiguration->find('first', ['conditions' => $conditions]);
	}

/**
 * renewAccessTokenIfExpired
 * Checks if current access token is expired and renews it if necessary
 * The referenced configuration array param will be updated with the latest access token information and saved at the same time. 
 * Rightsignature refresh_token is one-time-use therefore it must also be updated with the new one that is returned with every refesh request.
 *
 * @param array &$configData reference to rightsignature connection configuration stored in the ApiConfiguration model table.
 * @return boolean false if failed to renew token
 */
	public function renewAccessTokenIfExpired(&$configData) {
		if (empty($configData)) {
			return false;
		}
		//rightsignature access token issued time is the number of miliseconds since a unix epoch
		$issued = $configData['ApiConfiguration']['issued_at'];
		$expired = $this->isExpiredToken($configData['ApiConfiguration']['issued_at'], $configData['ApiConfiguration']['access_token_lifetime_seconds']);
		$accessTokenUrl = $configData['ApiConfiguration']['access_token_url'];
		if ($expired) {
			$paramsUri = $this->_getRefreshTokenParamsStr($configData);
			$httpSocket = new HttpSocket();
			$response = $httpSocket->post($accessTokenUrl.'?'.$paramsUri, null, null);
			$responseBody = json_decode($response->body, true);

			if ($response->isOk()) {
				//rightsignature returns both new access and refresh tokens
				$configData['ApiConfiguration']['access_token'] = $responseBody['access_token'];
				$configData['ApiConfiguration']['refresh_token'] = $responseBody['refresh_token'];
				$configData['ApiConfiguration']['issued_at'] = $responseBody['created_at'];
				//expiration returned is in seconds
				$configData['ApiConfiguration']['access_token_lifetime_seconds'] = $responseBody['expires_in'];

				ClassRegistry::init('ApiConfiguration')->save($configData['ApiConfiguration']);
			} else {
				return false;
			}
		}
		return true;
	}

/**
 * _getRefreshTokenParamsStr
 * Buids a URI string containing required parameters for a refresh token request as specified in rightsignature documentation:
 * https://api.rightsignature.com/documentation/resources/v1/oauth_tokens/create.en.html
 *
 * @param int $configData ApiConfiguration data array containing API authentication specific to RightSignature.
 * @return string
 */
	protected function _getRefreshTokenParamsStr(&$configData) {
		$paramsStr = 'grant_type=refresh_token&';
		$paramsStr .= 'client_id=' . $configData['ApiConfiguration']['client_id'] . '&';
		$paramsStr .= 'client_secret=' . $configData['ApiConfiguration']['client_secret'] . '&';
		$paramsStr .= 'refresh_token=' . $configData['ApiConfiguration']['refresh_token'];
		return $paramsStr;
	}

/**
 * isExpiredToken
 * Checks if current access token is expired based on configuration parameters which originated from rightsignature
 * The original access token created_at timestamp is included in the access token request response from rightsignature as a unix epoch in seconds
 *
 * @param int $issuedTimestamp original Unix epoc timestamp in seconds when the access token was issued.
 * @param int $secondsValid the number of seconds after which the token expires, if ommitted or empty then it is assument the access token never expires.
 * @return boolean false if failed to renew token
 */
	public function isExpiredToken($issuedTimestamp, $secondsValid = null) {
		if (empty($issuedTimestamp)) {
			return true;
		} elseif (empty($secondsValid)) {
			return false;
		}

		//Subtracting 5 seconds from secondsValid to account for a bit of processing time
		return ((time() - $issuedTimestamp) >= ($secondsValid - 5));
	}

/** 
* Returns json response from RightSignature's Document Details call
* 
* @param string $id - RightSignature Document UUID
* @return JSON string 
*/ 
	public function getDocumentDetails($id) {
		return $this->api->get($this->base_url.self::API_V1_PATH."/documents/$id");
	}
}
