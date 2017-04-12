<?php
App::uses('User', 'Model');

/**
 * User Test Test Case
 *
 */
class UserTest extends CakeTestCase {

	public $autoFixtures = false;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappUser',
		'app.onlineappApip',
		'app.onlineapp_users_manager',
		'app.onlineappUsersTemplate',
		'app.onlineappUsersCobrand',
		'app.onlineappUsersManager',
		'app.merchant',
		'app.onlineappGroup',
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->UsersManager = ClassRegistry::init('UsersManager');
		$this->Group = ClassRegistry::init('Group');
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');

		// load data
		$this->loadFixtures('OnlineappUser');
		$this->loadFixtures('OnlineappUsersTemplate');
		$this->loadFixtures('OnlineappUsersCobrand');
		$this->loadFixtures('OnlineappUsersManager');
		$this->loadFixtures('Merchant');
		$this->loadFixtures('OnlineappGroup');
		$this->loadFixtures('OnlineappCobrand');
		$this->loadFixtures('OnlineappTemplate');
		$this->loadFixtures('OnlineappTemplatePage');
		$this->loadFixtures('OnlineappApip');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);
		unset($this->UsersManager);
		unset($this->Group);
		unset($this->Cobrand);
		unset($this->Template);
		parent::tearDown();
	}

/** 
 * testOrConditions
 *
 * @covers User::orConditions()
 * @return void
 */
	public function testOrConditions() {
		$expected = array(
			'OR' => array(
				'User.firstname ILIKE' => '%Some Condition%',
				'User.lastname ILIKE' => '%Some Condition%',
				'User.fullname ILIKE' => '%Some Condition%',
				'User.email ILIKE' => '%Some Condition%',
				'CAST(User.extension AS TEXT) ILIKE' => '%Some Condition%',
				'CAST(User.id AS TEXT) ILIKE' => '%Some Condition%',
			)
		);
		$actual = $this->User->orConditions(array('search' => 'Some Condition'));
		$this->assertSame($expected, $actual);
	}

/**
 * testBindNode
 *
 * @covers User::testBindNode()
 * @return void
 */
	public function testBindNode() {
		$user = array();//pass empty data
		$result = $this->User->bindNode($user);
		$this->assertEmpty($result['foreign_key']);
		$user = array(
				'User' => array(
					'group_id' => 123
				)
			);
		$result = $this->User->bindNode($user);
		$this->assertNotEmpty($result['foreign_key']);
		$this->assertEqual($user['User']['group_id'], $result['foreign_key']);
	}

/**
 * testValidatePasswdConfirm
 *
 * @covers User::validatePasswdConfirm()
 * @return void
 */
	public function testValidatePasswdConfirm() {
		$requestData['User'] = array('pwd' => 'password123');
		$this->User->set($requestData);
		//Test no match
		$data['password_confirm'] = 'open sesame';
		$result = $this->User->validatePasswdConfirm($data);
		$this->assertFalse($result);

		//Test match
		$data['password_confirm'] = 'password123';
		$result = $this->User->validatePasswdConfirm($data);
		$this->assertTrue($result);
	}

/**
 * testGetActiveUserList
 *
 * @covers User::getActiveUserList
 * @return void
 */
	public function testGetActiveUserList() {
		$expected = $this->User->find('list', array(
			'fields' => array('User.id', 'User.fullname'),
			'order' => array('User.fullname ASC'),
			'conditions' => array('User.active' => true)));
		$actual = $this->User->getActiveUserList();
		$this->assertEqual(count($expected), count($actual));
		$this->assertSame($expected, $actual);
	}

/**
 * testAssignableUsers
 *
 * @covers User::assignableUsers
 * @return void
 */
	public function testAssignableUsers() {
		$expected = array(
			12 => 'Chew Bacca',
		);

		$actual = $this->User->assignableUsers(2, User::MANAGER_GROUP_ID);
		$this->assertSame($expected, $actual);

		$expected = array(
			11 => 'John Doe',
			1 => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet'
		);

		$actual = $this->User->assignableUsers(1, User::MANAGER_GROUP_ID);
		$this->assertSame($expected, $actual);

		$expected = array(
			12 => 'Chew Bacca',
			11 => 'John Doe',
			1 => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet',
		);

		$actual = $this->User->assignableUsers(1, User::ADMIN_GROUP_ID);
		$this->assertSame($expected, $actual);

		$actual = $this->User->assignableUsers(1, 'invalid_group_id');
		$this->assertSame(array(), $actual);
	}

/**
 * testGetAllManagers
 *
 * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testGetAllManagers() {
		$expected = array(12 => 'Chew Bacca');
		$actual = $this->User->getAllManagers(User::MANAGER_GROUP_ID);
		$this->assertSame($expected, $actual);
	}

/**
 * testGetAssignedManagerIds
 *
 * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testGetAssignedManagerIds() {
		$userId = 1;
		$expected = array(1);
		$actual = $this->User->getAssignedManagerIds($userId);
		$this->assertSame($expected, $actual);

		$userId = 2;
		$expected = array(2);
		$actual = $this->User->getAssignedManagerIds($userId);
		$this->assertSame($expected, $actual);

		$userId = 12;
		$expected = array(2);
		$actual = $this->User->getAssignedManagerIds($userId);
		$this->assertSame($expected, $actual);
	}

/**
 * testGetAssignedUserIds
 *
 * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testGetAssignedUserIds() {
		$managerId = 1;
		$expected = array(1, 11, $managerId); //managerId is pushed at the end
		$actual = $this->User->getAssignedUserIds($managerId);
		$this->assertSame($expected, $actual);

		$managerId = 2;
		$expected = array(2, 12, $managerId); //managerId is pushed at the end
		$actual = $this->User->getAssignedUserIds($managerId);
		$this->assertSame($expected, $actual);

		$managerId = 10;
		$expected = array(10, $managerId); //managerId is pushed at the end
		$actual = $this->User->getAssignedUserIds($managerId);
		$this->assertSame($expected, $actual);
	}

/**
 * testFlatRateUsers
 *
 * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testFlatRateUsers() {
		$userId = 99999; //fake user id
		$result = $this->User->flatRateUsers($userId);
		$this->assertFalse($result);

		foreach (Configure::read('Axia.flatRateManagers') as $userId) {
			$result = $this->User->flatRateUsers($userId);
			$this->assertTrue($result);
		}

		$usersMgrsData = array(
					'user_id' => 99,
					'manager_id' => 69
				);
		$this->UsersManager->create();
		$this->UsersManager->save($usersMgrsData);
		$result = $this->User->flatRateUsers($usersMgrsData['user_id']);
		$this->assertTrue($result);

		$usersMgrsData = array(
				array(
					'user_id' => 99,
					'manager_id' => 69
				),
				array(
					'user_id' => 99,
					'manager_id' => 68
				),
				array(
					'user_id' => 99,
					'manager_id' => 1234 //fake user not in app config
				),
			);
		$this->UsersManager->saveMany($usersMgrsData);
		$result = $this->User->flatRateUsers($usersMgrsData[0]['user_id']);
		$this->assertTrue($result);
	}

/**
 * testUseTokenExceptionThrown
 *
 * @expectedException Exception
 * @expectedExceptionMessage Token is not valid
 * @return void
 */
	public function testUseTokenExceptionThrown() {
		$this->User->useToken('invalid token');
	}

/**
 * testUseToken
 *
 * @covers User::useToken()
 * @return void
 */
	public function testUseToken() {
		$user = array(
			'id' => 11,
			'token_used' => '3000-01-24 11:02:22', //date far in the future
			'token_uses' => 99,
		);
		$this->User->create();
		$this->User->save($user);
		//test time threshold and max uses exceeded will return false
		$result = $this->User->useToken('tokenForUserId11');
		$this->assertFalse($result);

		$user = array(
			'id' => 12,
			'token_used' => '3000-01-24 11:02:22', //date far in the future
			'token_uses' => 1,
		);
		$this->User->create();
		$this->User->save($user);
		//test return id
		$expectedId = $user['id'];
		$actualId = $this->User->useToken('tokenForUserId12');
		$this->assertSame($expectedId, $actualId);

		//check token usage got incremented by one
		$expected = $user['token_uses'] + 1;
		$actual = $this->User->field('token_uses', array('token' => 'tokenForUserId12'));
		$this->assertSame($expected, $actual);
	}

/**
 * testBeforeSave
 *
 * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testBeforeSave() {
		$data = array(
			'User' => array(
				'pwd' => 'password123',
				'api_password' => 'apiPassword123'
			)
		);
		$encPwd = AuthComponent::password('password123');
		$encApiPwd = AuthComponent::password('apiPassword123');
		$this->User->set($data);
		$this->User->beforeSave();
		$expected = $data;
		$expected['User']['password'] = $encPwd;
		$expected['User']['api_password'] = $encApiPwd;
		$this->assertSame($expected['User']['password'], $this->User->data['User']['password']);
		$this->assertSame($expected['User']['api_password'], $this->User->data['User']['api_password']);
	}

/**
 * testAfterSave
 *
 * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testAfterSave() {}

/**
 * testAfterFind
 *
 * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testAfterFind() {}

/**
 * testArrayDiff
 *
 * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testArrayDiff() {}

/**
 * testGetCobrandIds
 * * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testGetCobrandIds() {}

/**
 * testGetTemplates
 *
 * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testGetTemplates() {}


}
