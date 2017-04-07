<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
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

	//User Constants
	const HOOZA = 59;
	const FIRE_SPRING = 102;
	const INSPIRE_PAY = 69;

	public $useTable = 'onlineapp_users';
	public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
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
		'Epayment' => array(
			'className' => 'Epayment',
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
				'with' => 'UserCobrand',
				'className' => 'Cobrand',
				'joinTable' => 'onlineapp_users_onlineapp_cobrands',
				'foreignKey' => 'user_id',
				'associationForeignKey' => 'cobrand_id',
		),
		'Template' => array(
				'with' => 'UserTemplate',
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


	function bindNode($user) {
		return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
	}

	function validatePasswdConfirm($data) {
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
 * templatesMatchCobrand
 * Custom validation rule checks for any Templates for which no corresponding cobrands were selected
 *
 * @param array $data submitted data
 * @return boolean
 */
	public function templatesMatchCobrand($data) {
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
		//$users = array();
		return $this->find('list', array('fields' => array('User.id','User.fullname'),'order' => array('User.fullname ASC'),'conditions' => array('User.active' => 't')));
	}
	/**
 * Return the list of users that can be assigned by the userId.
 * @param type $userId
 * @param string $userGroupId the group of the user
 */
		public function assignableUsers($userId, $userGroupId) {
				$users = array();
				if ($userGroupId == self::ADMIN_GROUP_ID) {
						// return all users
						return $this->find('list', array('fields' => array('id', 'fullname'), 'order' => array('fullname')));
				} else if ($userGroupId == self::MANAGER_GROUP_ID) {
						// return all users related to the manager
						return $this->find('list', array(
								'conditions' => array('id' => $this->getAssignedUserIds($userId)),
								'fields' => array('id', 'fullname'),
								'order' => array('fullname')));
				}
				return $users;
		}

		/*
		 * Get a List of Managers
		 */
		public function getAllManagers($managerId) {
				$managers = $this->find('list', array(
					'conditions' => array('group_id' => $managerId),
					'fields' => array('id', 'fullname'),
					'order' => array('fullname')));
				return $managers;
		}

	public function getAssignedManagerIds($userId){
		$assignedManagers =  $this->UsersManager->find(
			'all',
			array(
				'conditions' => array('user_id' => $userId),
				'fields' => array('manager_id')
			)
		);
		$assignedManagerIds = Set::classicExtract($assignedManagers, '{n}.UsersManager.manager_id');

//        return $this->find('list', array(
//                                'conditions' => array('id' => $assignedManagerIds),
//                                'fields' => array('id', 'fullname'),
//                                'order' => array('fullname')));

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
 * Get an array of all the assigned user ids to a specific user
 */
	public function getAssignedUserIds($userId){
		$assignedUsers =  $this->UsersManager->find('all', array(
								'conditions' => array('manager_id' => $userId),
								'fields' => array('user_id')
						));
		$assignedUserIds = Set::classicExtract($assignedUsers, '{n}.UsersManager.user_id');
		$assignedUserIds[] = $userId;
		return $assignedUserIds;
}

/**
 * determine whether a users should be able to assign flat rate pricing
 * @param type integer
 * @return boolean
 */
	public function flatRateUsers($userId) {
		$flatRateManagers = Configure::read('Axia.flatRateManagers');
		if (count($flatRateManagers) > count(array_diff($flatRateManagers,$this->getAssignedManagerIds($userId))) || in_array($userId, $flatRateManagers)){
			return true;
		} else{
			return false;
		}
	}

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


	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		if (!empty($this->data[$this->alias]['pwd'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['pwd']);
		}
		if (!empty($this->data[$this->alias]['api_password'])) {
			$this->data[$this->alias]['api_password'] = AuthComponent::password($this->data[$this->alias]['api_password']);
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

	public function afterSave($created, $options = array()) {
		// make sure all templates selected have associated
		// cobrands selected, otherwise delete those user template records

		if (!empty($this->data['Template']['Template'])) {
			$this->Template = ClassRegistry::init('Template');
		
			$templates = $this->Template->find(
				'all',
				array(
					'fields' => array(
						'id',
						'cobrand_id'
					)
				)
			);

			$templateToCobrandMap = array();

			foreach ($templates as $template) {
				$templateToCobrandMap[$template['Template']['id']] = $template['Template']['cobrand_id'];
			}

			$usersCobrands = $this->data['Cobrand']['Cobrand'];
			$usersTemplates = $this->data['Template']['Template'];

			foreach ($usersTemplates as $userTemplateId) {
				$assocCobrandId = $templateToCobrandMap[$userTemplateId];
				$flag = false;
				if (!empty($usersCobrands)) {
					foreach ($usersCobrands as $userCobrandId) {
						if ($userCobrandId == $assocCobrandId) {
							$flag = true;
						}
					}
				}
				if ($flag == false) {
					// get rid of the onlineapp_users_onlineapp_templates record
					// we don't have an associated cobrand selected by the user
					$this->UserTemplate->deleteAll(array(
						'UserTemplate.user_id' => $this->data['User']['id'],
						'UserTemplate.template_id' => $userTemplateId
					), false);
				}
			}
		}
	}

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


	public function arrayDiff($change) {
		$original = 
			$this->find('all', 
				array(
					'contain' => array(
						'Template' => array(
							'fields' => array('Template.id')
						),
						'Cobrand' => array(
							'fields' => array('Cobrand.id')
						)
					),
					'fields' => array(
						'User.id',
						'User.firstname',
						'User.lastname',
						'User.email',
						'User.group_id',
						'User.template_id',
						'User.active'
					),
					'recursive' => -1,
					'order' => array('User.firstname' => 'ASC'),
					'limit' => 150
				)
		);
		$user = Hash::remove($original, '{n}.Cobrand.{n}.UserCobrand');
		$user = Hash::remove($user, '{n}.Template.{n}.UserTemplate');
		$user = Hash::remove($user, '{n}.Template.name');
		$user = Hash::flatten($user);
		foreach ($user as $key => $value) {
			if(preg_match("/^(.*)[\.](Template|Cobrand)[\.](.*)[\.].*$/", $key)) {
			$newKey = preg_replace("/^(.*)[\.](Template|Cobrand)[\.](.*)[\.].*$/", "$1.$2.$2.$3", $key);
			unset($user[$key]);
			$user[$newKey] = $value;
		}
		}
		$user = Hash::expand($user);	
		$changedUsers = Hash::diff($change, $user);
		
		return $changedUsers;
	}

	public function getCobrandIds($userId){
		$cobrandIds = $this->UserCobrand->find(
			'all',
			array(
				'conditions' => array('user_id' => $userId),
				'fields' => array('cobrand_id')
			)
		);

		$ids = Set::classicExtract($cobrandIds, '{n}.UserCobrand.cobrand_id');
		return $ids;
	}

	public function getTemplates($userId){
		$templateIds = $this->UserTemplate->find(
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

}
?>
