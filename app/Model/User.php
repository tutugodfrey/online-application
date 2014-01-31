<?php
class User extends AppModel {
    public $actsAs = array('Search.Searchable', 'Containable');
    
    //Group Constants
    const ADMIN_GROUP_ID = 1;
    const REPRESENTATIVE_GROUP_ID = 2;
    const API_GROUP_ID = 3;    
    const MANAGER_GROUP_ID = 4;
    
    //User Constants
    const HOOZA = 59;
    const FIRE_SPRING = 102;
    const INSPIRE_PAY = 69;
    
    public $useTable = 'onlineapp_users';
    public $validate = array(
        'email' => array(
            'email' => array(
                'rule' => array('email'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'pwd' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'password_confirm' => array(
            'required'=>'notEmpty',
            'match'=>array(
            'rule' => 'validatePasswdConfirm',
            'message' => 'Passwords do not match'
           )
        ),
        'group_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    public $virtualFields = array(
    'fullname' => 'firstname || \' \' || lastname'
    );
    public $displayField = 'fullname';

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Cobrand' => array(
            'className' => 'Cobrand',
            'foreignKey' => 'cobrand_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Template' => array(
            'className' => 'Template',
            'foreignKey' => 'template_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
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
        'Application' => array(
            'className' => 'Application',
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
        )
    );
        
    function bindNode($user) {
        return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
    }
    
    function validatePasswdConfirm($data)
  {
    if (Security::hash($this->data[$this->alias]['pwd'], null, true) !== Security::hash($data['password_confirm'], null, true))
    {
      return false;
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
        $assignedManagers =  $this->UsersManager->find('all', array(
                                'conditions' => array('user_id' => $userId),
                                'fields' => array('manager_id')
                        ));
        $assignedManagerIds = Set::classicExtract($assignedManagers, '{n}.UsersManager.manager_id');
        
//        return $this->find('list', array(
//                                'conditions' => array('id' => $assignedManagerIds),
//                                'fields' => array('id', 'fullname'),
//                                'order' => array('fullname')));

        return $assignedManagerIds;
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

public function beforeSave($options = array()) {
    parent::beforeSave($options);
    if (!empty($this->data[$this->alias]['pwd'])) {
        $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['pwd']);
    }
        if (!empty($this->data[$this->alias]['api_password'])) {
        $this->data[$this->alias]['api_password'] = AuthComponent::password($this->data[$this->alias]['api_password']);
    }
    return true;
}

public function arrayDiff($change) {
    $new = Set::sort($change['User'], '{n}.id', 'asc');
    $original = Set::sort(Set::combine($this->find('all', array('fields' => array('id','firstname','lastname','email','group_id','active'),'order' => array('firstname' => 'ASC'),'recursive' => -1)),'{n}.User.id','{n}.User'), '{n}.id', 'asc');
    $delta = set::diff($new,$original);
    return $delta;
}
//public function beforeValidate($options = array()) {
//    parent::beforeValidate($options);
//    if(empty($this->data[$this->alias['pwd']]) && empty($this->data[$this->alias]['password_confirm'])) {
//        unset($this->data[$this->alias]['password_confirm']);
//    }
//}
}
?>