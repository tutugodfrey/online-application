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
		$http->configAuth('Basic', Configure::read('AxiaDbAPI.access_token'), Configure::read('AxiaDbAPI.password'));
		return $http;
	}
}
