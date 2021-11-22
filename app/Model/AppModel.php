<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	const API_SUCCESS = 'success';
	const API_FAILS = 'failed';

	public $actsAs = array(
		'Containable',
		'Utils.CsvImport' => array(
			'delimiter' => ',',
			'hasHeader' => true,
		)
	);

/**
 * getById
 * Returns a single record by its id. By Default no associated Model data is contained unless specified otherwise in settings param.
 *
 * @param int $id a User.id
 * @param array $settings to use for search
 * @return array
 */
	public function getById($id, $settings = array()) {
		$default = array(
			'contain' => false
		);
		$settings = array_merge($default, $settings);
		$settings['conditions']["{$this->alias}.id"] = $id;

		return $this->find('first', $settings);
	}

	/**
	 * Model Constructor
	 *
	 * @param mixed $id id
	 * @param mixed $table table name
	 * @param mixed $ds directory separator
	 */
		public function __construct($id = false, $table = null, $ds = null) {
			$this->_setPrefix();
			parent::__construct($id, $table, $ds);
		}

	/**
	 * Some of the tables require another prefix. Using two datasources for this
	 * was no good solution because cross datasource joins won't work.
	 *
	 * Add models that require another prefix than the default one to the
	 * $legacyModels array of that method
	 *
	 * @return string
	 */
		protected function _setPrefix() {
			$class = get_class($this);
			$legacyModels = array('Merchant', 'TimelineEntry', 'TimelineItem', 'EquipmentProgramming');
			if (in_array($class, $legacyModels)) {
				$this->tablePrefix = '';
			} else {
				$this->tablePrefix = 'onlineapp_';
			}
		}

/**
 * Custom validation rule, check if field value is equal (===) to another field
 *
 * @param string $check array values
 * @param string $fieldName1 first fieldName
 * @param string $fieldName2 second fieldName
 * @return bool
 */
	public function validateFieldsEqual($check, $fieldName1, $fieldName2) {
		if (!isset($this->data[$this->alias][$fieldName1]) || !isset($this->data[$this->alias][$fieldName2])) {
			return false;
		}
		return $this->data[$this->alias][$fieldName1] === $this->data[$this->alias][$fieldName2];
	}

/**
 * getRandHash method
 * Generates a pseudorandom md5 hash string which can be used as a unique idenfier.
 * Should not to be used as a password!
 * 
 * @return string md5 hash
 */
	public function getRandHash() {
		$alphaNum = implode('', array_merge(range('a', 'z'), range(0, 9)));
		return md5(str_shuffle($alphaNum));
	}

/**
 * sendEmail
 *
 * @param array $args arguments to control how the email should be composed
 *
 * @return $response array
 */
	public function sendEmail($args) {
		$response = array(
			'success' => false,
			'msg' => 'Failed to send email.',
		);

		if (!$this->CakeEmail) {
			$this->CakeEmail = new CakeEmail('default');
		}

		if (key_exists('from', $args)) {
			$this->CakeEmail->from($args['from']);

		} else {
			$response['msg'] = 'from argument is missing.';
			return $response;
		}

		if (key_exists('to', $args)) {
			$validEmail = true;
			if (is_array($args['to'])) {
				foreach($args['to'] as $emailStr) {
					if (!Validation::email($emailStr)) {
						$validEmail = false;
						break;
					}
				}
			} else {
				$validEmail = Validation::email($args['to']);
			}
			if ($validEmail) {
				$this->CakeEmail->to($args['to']);
			} else {
				$response['msg'] = 'invalid email address submitted.';
				return $response;
			}
		} else {
			$response['msg'] = 'to argument is missing.';
			return $response;
		}

		if (key_exists('cc', $args)) {
			if (Validation::email($args['cc'])) {
				$this->CakeEmail->cc($args['cc']);
			} else {
				$response['msg'] = 'invalid CC email address submitted.';
				return $response;
			}
		}

		$subject = 'No subject';
		if (key_exists('subject', $args)) {
			$subject = $args['subject'];
		}

		$this->CakeEmail->subject($subject);

		if (key_exists('format', $args)) {
			$this->CakeEmail->emailFormat($args['format']);
		}

		if (key_exists('template', $args)) {
			$this->CakeEmail->template($args['template']);
		}

		if (key_exists('viewVars', $args)) {
			$this->CakeEmail->viewVars($args['viewVars']);
		}

		if (key_exists('attachments', $args) && !empty($args['attachments'])) {
			$this->CakeEmail->attachments($args['attachments']);
		}

		if ($this->CakeEmail->send()) {
			$response['success'] = true;
			$response['msg'] = '';
		}

		return $response;
	}

/**
 * trimExtra
 * Removes not only trailing spaces but also any extra spaces in between words in a string.
 * Turns multiple spaces between words into sigle space. 
 * Example the string: " Monday  -  Tuesday " becomes "Monday - Tuesday"
 * 
 * @param string $str the string to sanitize
 * @return string the sanitized string
 */
	public function trimExtra($str) {
		$pattern = '/(\s+)/i';
		$replacement = " ";
		return preg_replace($pattern, $replacement, trim($str));
	}

/**
 * wordsAreWithinMinLevenshtein
 * Will return false if the input string is not within the provided minimum distance threshold when compared to all of the strings in an array.
 * Otherwise will retirn the first most similar string that is within the minDistance threshold.
 * 
 * @param string $input to find closest Levenshtein distace agains an array of strings
 * @param array $strings one dimention array of strings to compare agains input
 * @param integer $minDistance a number representing the tolerace threshold at or below which the $input string will be considered to be very highly similar to one the $strings
 * @return mixed boolean | string.
 */
	public function wordsAreWithinMinLevenshtein($input, $strings, $minDistance) {
		foreach ($strings as $str) {
			$lev = levenshtein($input, $str);
			if ($lev <= $minDistance) {
				return $str;
			}
		}
		return false;
	}

/**
 * createAxiaDbApiAuthClient
 * Creates an HttpSocket with the authentication configuration required to connect to the external Axia Database API
 * 
 * @return HttpSocket object
 */
	public function createAxiaDbApiAuthClient() {
		$http = new HttpSocket();
		$axMedApi = 'AxiaDbAPI';
		if (Configure::read('debug') > 0) {
			$axMedApi = 'AxiaDevDbAPI';
		}
		$http->configAuth('Basic', Configure::read("$axMedApi.access_token"), Configure::read("$axMedApi.password"));
		return $http;
	}

/**
 * isEncrypred method
 * Attempts to decrypt on success true will be returned
 * If decryption attempt fails false will be returned
 *
 * @param string $val value suspected to be encrypted
 * @return bool On success true will be returned false descryption fails.
 */
	public function isEncrypted($val) {
		//must use === in case non boolean zero is returned
		if (@$this->decrypt($val, Configure::read('Security.OpenSSL.key')) === false) {
			return false;
		}
		return true;
	}

/**
 * encrypt
 * OpenSSL Encryption method used for highly sensitive customer data
 * 
 * @param string $data data that will be encrypt
 * @param string of bytes $key  key
 * @return string $edata
 */
	public function encrypt($data, $key) {
		$key = base64_decode($key);
		$plaintext = $data;
		$cipher = Configure::read('Security.OpenSSL.cipher');
		$iv = base64_decode(Configure::read('Security.OpenSSL.iv'));
		$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
		$hmac = hash_hmac('sha256', $ciphertext_raw, $key, true);
		$ciphertext = base64_encode($iv.$hmac.$ciphertext_raw);

		return $ciphertext;
	}

/**
 * decrypt
 * Method to decrypt data encrypted with OpenSSL encryption method self->encrypt(..)
 *
 * @param string $ecryptedData data that will be decrypted
 * @param string of bytes $key  key
 * @return mixed decrypted data | boolean false when decryption fails
 */
	public function decrypt($ecryptedData, $key) {
		$key = base64_decode($key);
		$c = base64_decode($ecryptedData);
		$cipher = Configure::read('Security.OpenSSL.cipher');
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = substr($c, 0, $ivlen);
		$hmac = substr($c, $ivlen, $sha2len=32);
		$ciphertext_raw = substr($c, $ivlen+$sha2len);
		$decryptedData = openssl_decrypt($ciphertext_raw, $cipher, $key, OPENSSL_RAW_DATA, $iv);

		if ($decryptedData === false) {
			return false;
		}
		$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
		//PHP 5.6+ timing attack safe comparison
		if (hash_equals($hmac, $calcmac)) {
		    return $decryptedData;
		}
		return false;
	}

/**
 * isValidUUID method
 *
 * check is the given string parameter is a valid UUID
 * Implementations should not implement validation, since UUIDs should be in a consistent format across all implementations.
 *
 * @param string $uuid The UUID.
 * @return bool True if valid, false otherwise.
 */
	public static function isValidUUID($uuid) {
		return (bool)preg_match("/^[0-9a-f]{8}-([0-9a-f]{4}-){3}[0-9a-f]{12}$/", $uuid);
	}

/**
 * genRandomSecureToken
 * Generates a string of pseudo-random bytes hexadecimally encoded making sure a cryptographically strong algorithm is used to produce the pseudo-random bytes.
 * The length parameter is intended for the length in bytes and since the resulting pseudo-random bytes will be returned hexadecimally encoded
 * the resulting token will be twice the specified lenfth
 * 
 * @param int $length the length in bytes defaults to 32 which will result in a 64 character hex string.
 * @return the hexadecimally encoded pseudo-random bytes which will be double the specified length.
 */
	public function genRandomSecureToken(int $length = null) {
		if (!$length) {
			$length = 32;
		}
		$cstrong = null;
		$token = bin2hex(openssl_random_pseudo_bytes($length,$cstrong ));
		if ($cstrong === false) {
			$token = bin2hex(random_bytes($length));
		}
		return $token;
	}

/**
 * maskUsernamePartOfEmail
 * Expects an email string, truncates and masks the username part of the email with asterisks, for example:
 * username@email.com becomes u******e@email.com
 * mo@email.com becomes *o@email.com
 * s@email.com becomes *@email.com
 * 
 * @param string $email an email address
 * @return string the masked email addres
 */
	public function maskUsernamePartOfEmail(string $email) {
		if (!empty($email)) {
			$emailUserName = preg_replace('/^([\w\-\.]+)(@[\w\-]+\.)+([\w\-]{2,4})$/', '\1', $email);
			$sLength = strlen($emailUserName);
			if ($sLength == 2) {
				$emailUserName = '*' . $emailUserName[1];
			} elseif($sLength == 1) {
				$emailUserName = '*';
			} else {
				$emailUserName =  $emailUserName[0] . str_repeat('*', $sLength -2) . $emailUserName[$sLength -1];
			}
			
			$emailRemainder = preg_replace('/^([\w\-\.]+)(@[\w\-]+\.)+([\w\-]{2,4})$/', '\2\3', $email);
			return $emailUserName . $emailRemainder;
		}
	}

}
