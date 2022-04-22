<?php
App::uses('BaseAuthenticate', 'Controller/Component/Auth');
 
/**
* Uses the Facebook Connect API to log in a user through the Auth Component.
*
*/
class ApiAuthenticate extends BaseAuthenticate {
/**
* Settings for this object.
*
* - `fields` The fields to use to identify a user by.
* - `userModel` The model name of the User, defaults to User.
* - `scope` Additional conditions to use when looking up and authenticating users,
* i.e. `array('User.is_active' => 1).`
*
* @var array
* @todo Implement
*/

	public $settings = array(
		'fields' => array(
			'username' => 'token',
			'password' => 'password'
		),
		'userModel' => 'User',
		'scope' => array(),
		'recursive' => 0,
		'contain' => null,
                    'realm' => 'api'
	);
//    public $components = array('RequestHandler');
//    public function test($param) {
//        
//    }
public function authenticate(CakeRequest $request, CakeResponse $response) {
// 
////return $this->Api->getUser(); 
////debug($this->getUser($request));
//        $conditions = array('Apip.ip_address >>=' => $this->RequestHandler->getClientIP(), 'Apip.user_id' => $this->Auth->user('id'));
//        if (!$this->Application->User->Apip->find('first', array('conditions' => $conditions))) {
//            $this->Security->blackHole($this);
//        }
}
}
?>