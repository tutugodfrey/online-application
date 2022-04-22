<?php
App::uses('AppModel', 'Model');
App::uses('Okta', 'Model');
App::uses('AuthComponent', 'Controller/Component');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class User extends AppModel {

	public $actsAs = array('Search.Searchable', 'Containable');

	//Group Constants
	const ADMIN_GROUP_ID = 1;
	const REPRESENTATIVE_GROUP_ID = 2;
	const API_GROUP_ID = 3;
	const MANAGER_GROUP_ID = 4;

	const ADMIN = 'admin';
	const REP = 'rep';
	const API = 'api';
	const MANAGER = 'manager';

/**
 * User maximum number of failed log in attempts
 *
 * @var string
 */
	const MAX_LOG_IN_ATTEMPTS = 6;

	public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
			),
			'unique_email' => array(
				'rule' => array('isUnique'),
				'message' => 'Emails are used as usernames to log in and must be unique. This one is already in use.'
			),
		),
		'pwd' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'password_confirm' => array(
			'required' => 'notBlank',
			'match' => array(
				'rule' => 'validatePasswdConfirm',
				'message' => 'Passwords do not match'
			)
		),
		'template_id' => array(
			'rule' => 'hasDefaultTemplate',
			'message' => 'Please specify a default Template.',
		),
		'Template' => array(
			'withCobrandsNotEmpty' => array(
				'rule' => 'withCobrandsNotEmpty',
				'message' => 'Cobrands were selected, please also select a Template.',
			),
			'templatesMatchCobrand' => array(
				'rule' => 'templatesMatchCobrand',
				'message' => 'One or more of the selected templates do not belong to the selected cobrands.',
			),
		),
		'group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'firstname' => array(
			'input_has_only_valid_chars' => array(
	            'rule' => array('inputHasOnlyValidChars'),
	            'message' => 'Special characters (i.e "<>`()[]"... etc) are not permitted!',
	            'required' => false,
	            'allowEmpty' => true,
	        ),
		),
		'lastname' => array(
			'input_has_only_valid_chars' => array(
	            'rule' => array('inputHasOnlyValidChars'),
	            'message' => 'Special characters (i.e "<>`()[]"... etc) are not permitted!',
	            'required' => false,
	            'allowEmpty' => true,
	        ),
		),
	);

	public $virtualFields = array(
		'fullname' => 'firstname || \' \' || lastname'
	);

	public $displayField = 'fullname';

	// The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	public $hasMany = array(
		'Apip' => array(
			'className' => 'Apip',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'CobrandedApplication' => array(
			'className' => 'CobrandedApplication',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ApiLog' => array(
			'className' => 'ApiLog',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

	public $hasAndBelongsToMany = array(
		'Manager' => array(
				'with' => 'UsersManager',
				'className' => 'User',
				'joinTable' => 'onlineapp_users_managers',
				'foreignKey' => 'user_id',
				'associationForeignKey' => 'manager_id',
		),
		'AssignedRepresentative' => array(
				'with' => 'UsersManager',
				'className' => 'User',
				'joinTable' => 'onlineapp_users_managers',
				'foreignKey' => 'manager_id',
				'associationForeignKey' => 'user_id',
		),
		'Cobrand' => array(
				'with' => 'UsersCobrand',
				'className' => 'Cobrand',
				'joinTable' => 'onlineapp_users_onlineapp_cobrands',
				'foreignKey' => 'user_id',
				'associationForeignKey' => 'cobrand_id',
		),
		'Template' => array(
				'with' => 'UsersTemplate',
				'className' => 'Template',
				'joinTable' => 'onlineapp_users_onlineapp_templates',
				'foreignKey' => 'user_id',
				'associationForeignKey' => 'template_id',
		),
	);

/**
 * Array of Arguments to be used by the search plugin
 */
	public $filterArgs = array(
		'search' => array('type' => 'query', 'method' => 'orConditions'),
	);

/**
 * Return conditions to be used when searching for users
 *
 * @param array $data containing conditions
 * @return array
 */
	public function orConditions($data = array()) {
		$criteria = $data['search'];
		$criteria = '%' . $criteria . '%';
		$conditions = array(
			'OR' => array(
				'User.firstname ILIKE' => $criteria,
				'User.lastname ILIKE' => $criteria,
				'User.fullname ILIKE' => $criteria,
				'User.email ILIKE' => $criteria,
				'CAST(User.extension AS TEXT) ILIKE' => $criteria,
				'CAST(User.id AS TEXT) ILIKE' => $criteria,
			)
		);
		return $conditions;
	}

/**
 * bindNode
 *
 * @param array $user containing a user data
 * @return array
 */
	public function bindNode($user) {
		return array('model' => 'Group', 'foreign_key' => Hash::get($user, 'User.group_id'));
	}

/**
 * validatePasswdConfirm
 * Custom Validation rule to check submitted passwords match
 *
 * @param array $data submitted from client-side form
 * @return boolean
 */
	public function validatePasswdConfirm($data) {
		if (Security::hash($this->data[$this->alias]['pwd'], null, true) !== Security::hash($data['password_confirm'], null, true)) {
			return false;
		}
		return true;
	}

/**
 * hasDefaultTemplate
 * Custom validation rule
 *
 * @param array $data template_id data
 * @return boolean
 */
	public function hasDefaultTemplate($data) {
		if (!empty($this->data['User']['Template'])) {
			return (!empty($data['template_id']));
		}
		return true;
	}

/**
 * withCobrandsNotEmpty
 * Custom validation rule
 *
 * @param array $data template_id data
 * @return boolean
 */
	public function withCobrandsNotEmpty($data) {
		if (!empty($this->data['User']['Cobrand'])) {
			return (!empty($this->data['User']['Template']));
		}
		return true;
	}

/**
 * generateRandPw
 * Generates a 32bit Cryptographically secure pseudo-random password string
 * and utilizes unreserved characters (section 2.3 of RFC3986 spec).
 * 
 * @return mixed boolean|array
 */
	public function generateRandPw() {
		$factory = new RandomLib\Factory;
		$generator = $factory->getMediumStrengthGenerator();
		$intList = $generator->generateInt(1000, 99999);
		$alphaList = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$unreservedSymbolList = '._+-~';
		$chars = $alphaList . $intList . $unreservedSymbolList;
		
		return $generator->generateString(32, $chars);
	}

/**
 * getEmailArgs
 * Returns an array of email arguments to use for sending a CakeEmail
 *
 * @param string $userId the id of the user that the email wool be sent to
 * @param array $args optinal email metadata arguments to use instead of defaults
 * @return mixed boolean|array
 */
	public function getEmailArgs($userId, $args = []) {
		$email = $this->field('email', ['id' => $userId]);
		$userName = $this->field('firstname', ['id' => $userId]);

		if (empty($email)) {
			return false;
		}
		$msgBody = "Hello $userName\n";
		$msgBody .= "This is an automated request to reset your password please do not reply.\n";
		$msgBody .= "If you are not aware of a password reset request for your account, you may disregard this.\n";
		$msgBody .= "Otherwise, please follow the URL below to set a new password:\n";
		$msgBody .= Router::url(['controller' => 'Users', 'action' => 'change_pw', $userId], true);
		$defaults = [
			'from' => ['webmaster@axiamed.com' => 'Axia Online Applications'],
			'to' => $email,
			'subject' => 'Axia Online App Account Password Reset',
			'format' => 'html',
			'template' => 'default',
			'viewVars' => ['content' => $msgBody],
		];
		$args = array_merge($defaults, $args);
		return $args;

	}
/**
 * newPwExpiration
 * Generates a date to be used as new user password expiration based on App's Configured pw validity age in bootstrap.
 * 
 * @return string representation of new password expiration date.
 */
	public function newPwExpiration() {
		$date = new DateTime(date('Y-m-d'));
		$interval = "P" . Configure::read('App.pw_validity_age') . "D";
		$date->add(new DateInterval($interval));
		return $date->format('Y-m-d');
	}

/**
 * return the number of days until a user password expires
 *
 * @param string $id User id
 * @return bool
 */
	public function getDaysTillPwExpires($id) {
		$pwExpDate = $this->field('pw_expiry_date', ['id' => $id]);
		$now = date_create(date('Y-m-d'));
		$pwExpDate = date_create($pwExpDate);
		$diff = date_diff($now, $pwExpDate);
		return (int)$diff->format("%R%a");
	}

/**
 * pwIsValid
 * Checks whether the password submitted is different from users current password
 *
 * @param string $id User id
 * @param string $pw a password to compare against the users current password
 * @return bool
 */
	public function pwIsValid($id, $pw) {
		$pw = Security::hash($pw, null, true);
		return $this->hasAny(['id' => $id, 'password' => $pw]);
	}

/**
 * setPwFieldsValidation
 * Sets validation rules that apply to the password reset/renewal UI fields
 *
 * @param boolean $validateCurPass whether to add validation for current password
 * @return void
 */
	public function setPwFieldsValidation($validateCurPass = false) {
		//remove all rules since they are not relevant for password resetting
		foreach ($this->validate as $field => $rules) {
			$this->validator()->remove($field);
		}
		$this->validator()->add('pwd', [
				'required' => [
					'rule' => ['notBlank'],
					'message' => 'Password is required!',
					'required' => true,
					'allowEmpty' => false,
				],
				'matches' => [
					'rule' => ['validateFieldsEqual', 'pwd', 'repeat_password'],
					'message' => 'Passwords do not match!',
					'allowEmpty' => true,
				]
		])->add('repeat_password', [
				'required' => [
					'rule' => ['notBlank'],
					'message' => 'Repeat Password is required!',
					'required' => true,
					'last' => false,
					'allowEmpty' => false,
				],
				'matches' => [
					'rule' => ['validateFieldsEqual', 'pwd', 'repeat_password'],
					'message' => 'Passwords do not match!',
					'last' => false,
					'allowEmpty' => true,
				],
				'minLength' => [
					'rule' => ['minLength', 8],
					'message' => 'Passwords should have 8 characters or more',
					'last' => false,
				],
			]
		);
		if ( $validateCurPass) {
			$this->validator()->add('cur_password', [
					'required' => [
						'rule' => ['notBlank'],
						'message' => 'Password is required!',
						'required' => true,
						'allowEmpty' => false,
					]
			]);
		}
	}

/**
 * templatesMatchCobrand
 * Custom validation rule checks for any Templates for which no corresponding cobrands were selected
 *
 * @param array $data submitted data
 * @return boolean
 */
	public function templatesMatchCobrand($data) {
		if (!empty($this->data[$this->alias]['Template']) && empty($this->data[$this->alias]['Cobrand'])) {
			return false;
		}
		if (!empty($this->data[$this->alias]['Template'])) {
			$selectedTemplateIds = $this->data[$this->alias]['Template'];
			$actualTmpltAndCob = $this->Template->find('all', array(
					'fields' => array('Template.id', 'Cobrand.id'),
					'conditions' => array('Template.id' => $selectedTemplateIds),
					'contain' => array('Cobrand'),
				));
			$actualCbrandIds = Hash::extract($actualTmpltAndCob, '{n}.Cobrand.id');
			$misMatchingCobrands = array_diff($actualCbrandIds, $this->data[$this->alias]['Cobrand']);
			return empty($misMatchingCobrands);

		}
		return true;
	}

/**
 *
 * @param type $token
 * @return boolean
 * @throws Exception
 */
	public function getActiveUserList() {
		return $this->find('list', array(
				'fields' => array('User.id', 'User.fullname'),
				'order' => array('User.fullname ASC'),
				'conditions' => array('User.active' => 't')
			)
		);
	}

/**
 * assignableUsers
 * Return the list of users that can be assigned by the userId.
 *
 * @param type $userId a user id
 * @param string $userGroupId the group of the user
 * @return array
 */
	public function assignableUsers($userId, $userGroupId) {
		if ($userGroupId == self::ADMIN_GROUP_ID) {
			// return all users
			$users = $this->find('list', array('fields' => array('id', 'fullname'), 'order' => array('fullname')));
		} elseif ($userGroupId == self::MANAGER_GROUP_ID) {
			// return all users related to the manager
			$users = $this->find('list', array(
					'conditions' => array('id' => $this->getAssignedUserIds($userId)),
					'fields' => array('id', 'fullname'),
					'order' => array('fullname')
					)
				);
		} else {
			return array();
		}
		return $users;
	}

/**
 * getAllManagers
 * Get a List of Managers
 *
 * @param integer $managerId the id of manger user group
 * @return array
 */
	public function getAllManagers($managerId) {
		$managers = $this->find('list', array(
			'conditions' => array('group_id' => $managerId),
			'fields' => array('id', 'fullname'),
			'order' => array('fullname')));
		return $managers;
	}

/**
 * getAssignedManagerIds
 * Get an array of all the assigned user ids to a specific user
 *
 * @param integer $userId a user id
 * @return array
 */
	public function getAssignedManagerIds($userId) {
		$assignedManagers = $this->UsersManager->find(
			'all',
			array(
				'conditions' => array('user_id' => $userId),
				'fields' => array('manager_id')
			)
		);
		$assignedManagerIds = Hash::extract($assignedManagers, '{n}.UsersManager.manager_id');

		return $assignedManagerIds;
	}

	public function getAssignedManagersList($userId){
		$assignedManagers =  $this->find(
			'list',
			array(
				'conditions' => array('id' => $this->getAssignedManagerIds($userId)),
				'fields' => array('id', 'fullname'),
				'order' => array('fullname')
			)
		);
		return $assignedManagers;
	}


/**
 * getAssignedUserIds
 * Get an array of all the assigned user ids to a specific user
 *
 * @param integer $userId a user id
 * @return array
 */
	public function getAssignedUserIds($userId) {
		$assignedUsers = $this->UsersManager->find('all', array(
								'conditions' => array('manager_id' => $userId),
								'fields' => array('user_id')
						));
		$assignedUserIds = Hash::extract($assignedUsers, '{n}.UsersManager.user_id');
		$assignedUserIds[] = $userId;
		return $assignedUserIds;
	}

/**
 * useToken
 *
 * @param string $token a string token max length 40 chars
 * @return boolean
 * @throws Exception
 */
	public function useToken($token) {
		$user = $this->find('first', array(
			'conditions' => array($this->alias . '.token' => $token),
			'recursive' => -1
				));

		if (empty($user)) {
			throw new Exception('Token is not valid');
		}

		$apiSettings = Configure::read('API');
		$tokenUsed = !empty($user[$this->alias]['token_used']) ?
				$user[$this->alias]['token_used'] : null;
		$tokenUses = $user[$this->alias]['token_uses'];

		if (!empty($tokenUsed)) {
			$tokenTimeThreshold = strtotime('+' .
					$apiSettings['time'], strtotime($tokenUsed));
		}
		$now = time();

		if (!empty($tokenUsed) && $now <= $tokenTimeThreshold &&
				$tokenUses >= $apiSettings['maximum']) {
			return false;
		}
		$id = $user[$this->alias][$this->primaryKey];
		if (!empty($tokenUsed) && $now <= $tokenTimeThreshold) {
			$this->id = $id;
			$this->saveField('token_uses', $tokenUses + 1);
		} else {
			$this->id = $id;
			$this->save(
					array('token_used' => date('Y-m-d H:i:s'), 'token_uses' => 1), false, array('token_used', 'token_uses')
					);
		}
		return $id;
	}



	public function beforeValidate($options = array()) {
		//Modify any HABTM data structures so that the data can be validated
		//This is the required data structure for HABTM validation
		foreach (array_keys($this->hasAndBelongsToMany) as $model){
			if(isset($this->data[$model][$model])){
				$this->data[$this->name][$model] = $this->data[$model][$model];
				unset($this->data[$model]);
			}
		}
		return true;
	}

/**
 * beforeSave
 * Callback triggered before save
 * @param  array  $options options
 * @return boolean
 */
	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		if (!empty($this->data[$this->alias]['pwd'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['pwd']);
			//update expiration date whenever new password is saved
			if (!isset($this->data[$this->alias]['pw_expiry_date'])) {
				$this->data[$this->alias]['pw_expiry_date'] = $this->newPwExpiration();
			}
			//In production sync user password with okta account whenever new password is saved
			if (Configure::read('debug') == 0) {
				try {
					if (!empty($this->data[$this->alias]['id'])) {
						if (empty($uEmail = Hash::get($this->data, "{$this->alias}.email"))) {
							$uEmail = $this->field('email', ['id' => $this->data[$this->alias]['id']]);
						}
						$Okta = new Okta();
						$Okta->chngPwd($uEmail, $this->data[$this->alias]['password']);
					}
				} catch (Exception $e) {/*Is possible the user has yet to be created in okta so ignore exception.*/}
			}
		}

		//Modify any HABTM data structures so that the data can be saved
		//This is the required data structure for saving HABTM data
		foreach (array_keys($this->hasAndBelongsToMany) as $model){
			if(isset($this->data[$this->name][$model])){
				$this->data[$model][$model] = $this->data[$this->name][$model];
				unset($this->data[$this->name][$model]);
			}
		}
		return true;
	}

/**
 * afterFind callback
 *
 * @param array $results Array of results
 * @param boolean $primary a boolean primary param requied for callback
 * @return boolean
 */
	public function afterFind($results, $primary = false) {
		parent::afterFind($results, $primary);

		if (!empty($results) && is_array($results)) {
			foreach ($results as $key => $val) {
				if (isset($val['User']['template_id'])) {
					$template = $this->Template->find(
						'first',
						array(
							'conditions' => array(
								'Template.id' => $val['User']['template_id']
							),
							'fields' => array('Template.name')
						)
					);

					$results[$key]['Template']['name'] = $template['Template']['name'];
				}
			}
		}

		return $results;
	}

/**
 * oktaUserLogin
 * Makes API call to authenticate Okta user
 * Returns API response data when user is found, is authenticated and is already enrolled in multifactor authentication.
 * Otherwise returnse false.
 * 
 * @param string $userId this is NOT an okta user id but rather this system's integer user id
 * @return mixed array|boolean
 */
	public function authenticateOktaUser($userId) {
		$userCred = $this->find('first', [
			'recursive' => -1,
			'fields' => ['User.email', 'User.password'],
			'conditions' => ['id' => $userId]
		]);
		if (!empty($userCred)) {
			$Okta = new Okta();
			try {
				return $Okta->primaryAuth($userCred['User']['email'], $userCred['User']['password']);
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		} else {
			return false;
		}
	}

/**
 * getCobrandIds
 * Get all UsersCobrands that belong to a user
 *
 * @param integer $userId a user id
 * @return array
 */
	public function getCobrandIds($userId) {
		$cobrandIds = $this->UsersCobrand->find(
			'all',
			array(
				'conditions' => array('user_id' => $userId),
				'fields' => array('cobrand_id')
			)
		);

		$ids = Hash::extract($cobrandIds, '{n}.UsersCobrand.cobrand_id');
		return $ids;
	}

/**
 * getTemplates
 * Get all UsersTemplate that belong to a user
 *
 * @param integer $userId a user id
 * @return array
 */
	public function getTemplates($userId) {
		$templateIds = $this->UsersTemplate->find(
			'list',
			array(
				'conditions' => array(
					'user_id' => $userId
				),
				'fields' => array(
					'template_id'
				),
			)
		);

		$ids = array();
		foreach ($templateIds as $key => $val) {
			$ids[] = $val;
		}

		$templates = $this->Template->find('all',
			array(
				'contain' => array('Cobrand.partner_name'),
				'fields' => array('Template.id', 'Template.name'),
				'order' => array('Cobrand.partner_name' => 'ASC'),
				'conditions' => array('Template.id' => $ids),
			)
		);

		$templates = Hash::combine($templates, '{n}.Template.id', array('%2$s - %1$s', '{n}.Template.name', '{n}.Cobrand.partner_name'));
		return $templates;
	}

/**
 * allTemplates
 * Get all UsersTemplate that belong to a user with detailed Template data.
 *
 * @param integer $userId a user id
 * @return array indexed array containing detail Template data arrays 
 */
	public function allTemplates($userId) {
		$templates = $this->UsersTemplate->find('all', array(
				'conditions' => array('UsersTemplate.user_id' => $userId),
				'fields' => array(
					'Template.id',
					'Template.cobrand_id',
					'(("Cobrand"."partner_name")) as "Template__partner_name"',
					'Template.name',
					'Template.description',
					'Template.requires_coversheet',
					'Template.created',
					'(CASE WHEN "User"."template_id" = "Template"."id" THEN \'YES\' ELSE \'NO\' END) AS "Template__is_default_user_template"',
				),
				'joins' => array(
					array(
						'table' => 'onlineapp_templates',
						'alias' => 'Template',
						'type' => 'INNER',
						'conditions' => array(
							'UsersTemplate.template_id = Template.id'
						)
					),
					array(
						'table' => 'onlineapp_users',
						'alias' => 'User',
						'type' => 'LEFT',
						'conditions' => array(
							'UsersTemplate.user_id = User.id'
						)
					),
					array(
						'table' => 'onlineapp_cobrands',
						'alias' => 'Cobrand',
						'type' => 'LEFT',
						'conditions' => array(
							'Template.cobrand_id = Cobrand.id'
						)
					)
				)
			)
		);
		
		return Hash::extract($templates, '{n}.Template');
	}

	/**
	 * getEditViewData
	 *
	 * @param integer $id a user id
	 * @return array
	 */
	 public function getEditViewData($id) {
		$data = $this->find('first', array(
				'conditions' => array('User.id' => $id),
				'contain' => array(
					'Manager' => array('fields' => array('id', 'fullname')),
					'AssignedRepresentative' => array('fields' => array('id', 'fullname')),
					'Cobrand' => array('fields' => array('id', 'partner_name')),
					'Template' => array('fields' => array('id', 'name')),
				),
			));
		return $data;
	 }

/**
 * getCombinedCobrandTemplateList
 *
 * @param mixed $templateIds string|array of template ids
 * @return array of ids as keys and [Cobrand-Template] names as values
 */
	public function getCombinedCobrandTemplateList($templateIds) {
		$conditions['conditions'] = array('Template.id' => $templateIds);
		$tmplts = $this->Template->getTemplatesAndCobrands($conditions);
		return $this->Template->setCobrandsTemplatesList($tmplts);
	}

/**
 * getJsonCobrandsTemplates
 *
 * @return string JSON encoded array with Cobrand ids keys and [Template id-Template name] as values
 */
	public function getJsonCobrandsTemplates() {
		$data = $this->Template->getTemplatesAndCobrands(array('recursive' => -1));

		foreach (Hash::extract($data, '{n}.Cobrand.id') as $cobId) {
			foreach ($data as $tAndC) {
				if ($tAndC['Cobrand']['id'] === $cobId) {
					$cobAndTmpl[$cobId][$tAndC['Template']['id']] = $tAndC['Cobrand']['partner_name'] . ' - ' . $tAndC['Template']['name'];
				}
			}
		}
		return json_encode($cobAndTmpl);
	}

/**
 * trackIncorrectLogIn
 * tracks the current user inccorrect log in attemts
 *
 * @param string $eamil User.email
 * @return integer the current count of failed attempts if an active user exists or 0 if user not found
 */
	public function trackIncorrectLogIn($email, $reset = false) {
		$userData = $this->find('first', array(
			'fields' => array('id', 'wrong_log_in_count', 'is_blocked', 'active', 'email'),
			'conditions' => array(
				'email' => $email,
				'active' => true
			)
		));
		if(!empty($userData) && $reset) {
			//reset
			$userData['User']['wrong_log_in_count'] = 0;
			$userData['User']['is_blocked'] = false;
			$this->clear();
			$this->save($userData, array('validate' => false));
		} elseif (!empty($userData) && $userData['User']['is_blocked'] == false) {
			if ($userData['User']['wrong_log_in_count'] < self::MAX_LOG_IN_ATTEMPTS) {
				$userData['User']['wrong_log_in_count'] += 1;
				$this->save($userData, array('validate' => false));
		 	} 
		 	if ($userData['User']['wrong_log_in_count'] >= self::MAX_LOG_IN_ATTEMPTS) {
				//block user and notify send email
				$userData['User']['pw_reset_hash'] = $this->getRandHash();
				$userData['User']['pwd'] = $this->generateRandPw();
				$this->clear();
				$this->save($userData, array('validate' => false));
				$this->notifyUserBlockedFailedLogInAttempts($userData);
		 		$this->toggleBlockUser($userData['User']['id'], true);
			}
		}
		
		return Hash::get($userData, 'User.wrong_log_in_count', 0);
	}

/**
 * notifyUserBlockedFailedLogInAttempts
 * Send email to user when account is blocked due to too many failed log in attempts
 *
 * @param array $user data about the user; must contain a new pw_reset_hash, new temporary password and the user_email
 * @return void
 */
	public function notifyUserBlockedFailedLogInAttempts($user) {
		if (empty($user['User']['id'])) {
			return null;
		}
		
		$msg = "Your Online Applicaion account has been locked due to excessive incorrect log in attempts.\n";
		$msg .= "The following temporary password has been set as your current password on your account:\n {$user['User']['pwd']}\n";
		$msg .= "To unlock your account you must change the temporary password with a new password using this url:\n";
		$msg .= Router::url(['controller' => 'Users', 'action' => 'change_pw', true, $user['User']['pw_reset_hash']], true) . "\n";
		$args['viewVars'] = ['content' => $msg];
		$args = $this->getEmailArgs($user['User']['id'], $args);
		$this->sendEmail($args);
	}

/**
 * toggleBlockUser
 * Block or unblock users
 *
 * @param string $id User.id
 * @param boolean $isBlocked true|false to block|unblock
 * @return void
 */
	public function toggleBlockUser($id, $isBlocked) {
		$this->save(['id' => $id, 'is_blocked' => $isBlocked], array('validate' => false));
	}
}
