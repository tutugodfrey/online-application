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
		'app.onlineappApiLog',
		'app.onlineapp_users_manager',
		'app.onlineappUsersTemplate',
		'app.onlineappUsersCobrand',
		'app.onlineappUsersManager',
		'app.merchant',
		'app.onlineappGroup',
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappCobrandedApplication',
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
		$this->UsersTemplate = ClassRegistry::init('UsersTemplate');
		$this->UsersCobrand = ClassRegistry::init('UsersCobrand');

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
		$this->loadFixtures('OnlineappApiLog');
		$this->loadFixtures('OnlineappCobrandedApplication');
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
		unset($this->UsersTemplate);
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
 * @covers User::bindNode()
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
 * @covers User::getAllManagers
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
 * @covers User::getAssignedManagerIds
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
 * @covers User::getAssignedUserIds
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
 * @covers User::beforeSave()
 * @return void
 */
	public function testBeforeSave() {
		$data = array(
			'User' => array(
				'pwd' => 'password123',
				'api_password' => 'apiPassword123'
			)
		);
		$passwordHasher = new BlowfishPasswordHasher();
		$encPwd = AuthComponent::password('password123');
		$encApiPwd = $passwordHasher->hash('apiPassword123');
		$this->User->set($data);
		$this->User->beforeSave();
		$expected = $data;
		$expected['User']['password'] = $encPwd;		
		$this->assertSame($expected['User']['password'], $this->User->data['User']['password']);
		$this->assertTrue( $passwordHasher->check('apiPassword123', $this->User->data['User']['api_password']));
	}

/**
 * testAfterFind
 *
 * @covers User::__construct
 * @covers User::filterArgs property
 * @covers User::virtualFields property
 * @return void
 */
	public function testAfterFind() {
		$users = $this->User->find('all', array(
			'recursive' => -1,
			'fields' => array('template_id')
		));

		//all users shold have their templates inserted on afterFind
		//despite recursive = -1
		foreach ($users as $idx => $data) {
			$this->assertArrayHasKey('Template', $data);
		}
	}

/**
 * testGetCobrandIds
 *
 * @covers User::getCobrandIds
 * @return void
 */
	public function testGetCobrandIds() {
		//Set HABTM data structure
		$user = array(
			'User' => array(
				'id' => 1,
			),
			'Cobrand' => array(
				'Cobrand' => array(1, 2)
			),
			'Template' => array(
				'Template' => array(
					1,
					2,
					3
				)
			),
		);
		//save new Cobrands/Templates for user
		$this->User->saveAll($user);
		$expected = array(1, 2);
		$actual = $this->User->getCobrandIds(1);
		$this->assertSame($expected, $actual);
	}

/**
 * testGetTemplates
 *
 * @covers User::getTemplates
 * @return void
 */
	public function testGetTemplates() {
		//Set HABTM data structure
		$user = array(
			'User' => array(
				'id' => 1,
			),
			'Cobrand' => array(
				'Cobrand' => array(1, 2)
			),
			'Template' => array(
				'Template' => array(
					1,
					2,
					3
				)
			),
		);
		//save new Cobrands/Templates for user
		$this->User->saveAll($user);

		$expected = array(
			1 => 'Partner Name 1 - Template 1 for PN1',
			2 => 'Partner Name 1 - Template 2 for PN1',
			3 => 'Partner Name 2 - Template 1 for PN2'
		);

		$actual = $this->User->getTemplates($user['User']['id']);
		ksort($actual);//sort for consistency
		$this->assertSame($expected, $actual);
	}

/**
 * testGetDaysTillPwExpires
 *
 * @covers User::getDaysTillPwExpires
 * @return void
 */
	public function testGetDaysTillPwExpires() {
		$pwExpDate = $this->User->field('pw_expiry_date', ['id' => 1]);
		$now = date_create(date('Y-m-d'));
		$pwExpDate = date_create($pwExpDate);
		$diff = date_diff($now, $pwExpDate);
		$expected = $diff->format("%R%a");

		$actual = $this->User->getDaysTillPwExpires(1);
		$this->assertSame((int)$expected, $actual);
	}

/**
 * testPwIsValid
 *
 * @covers User::pwIsValid
 * @return void
 */
	public function testPwIsValid(){
		for($x = 0; $x <= 100; $x++) {
			$pwd = str_shuffle('123456789abcdefg');
			$this->User->save(['id' => 1, 'pwd' => $pwd]);
			$this->assertTrue($this->User->pwIsValid(1, $pwd));
			$this->assertFalse($this->User->pwIsValid(1, $pwd . $pwd));
		}
	}

/**
 * testNewPwExpiration
 *
 * @covers User::newPwExpiration
 * @return void
 */
	public function testNewPwExpiration() {
		$date = date('Y-m-d');
		//Using different method for creating expectation of new date as control for test
		$expected = date('Y-m-d', strtotime($date. ' + ' . Configure::read('App.pw_validity_age') . ' days'));
		$actual = $this->User->newPwExpiration();
		$this->assertSame($expected, $actual);
	}

/**
 * testSetPwFieldsValidation
 *
 * @covers User::setPwFieldsValidation
 * @return void
 */
	public function testSetPwFieldsValidation() {
		$tstData = [
			'id' => 999,
			'pwd' => 12345678,
		];

		$actual = $this->User->setPwFieldsValidation();

		$this->User->create();
		$this->assertFalse($this->User->save($tstData));
		
		//Test missing repeat_password validation error
		$actual = $this->User->validationErrors;
		$this->assertContains('Passwords do not match!', $actual['pwd']);
		$this->assertContains('Repeat Password is required!', $actual['repeat_password']);
		
		//Test repeat_password should have 8 characters or more
		$tstData['repeat_password'] = '2345678';
		$this->User->create();
		$this->assertFalse($this->User->save($tstData));
		$actual = $this->User->validationErrors;
		$this->assertContains('Passwords should have 8 characters or more', $actual['repeat_password']);

		//Test repeat_password does not match
		$tstData['repeat_password'] = '23456789';
		$this->User->create();
		$this->assertFalse($this->User->save($tstData));
		$actual = $this->User->validationErrors;
		$this->assertContains('Passwords do not match!', $actual['pwd']);
		$this->assertContains('Passwords do not match!', $actual['repeat_password']);
	}

/**
 * testGetEmailArgs
 *
 * @covers User::getEmailArgs
 * @return void
 */
	public function testGetEmailArgs() {
		$expected = array(
			'from' => array(
					'webmaster@axiamed.com' => 'Axia Online Applications'
			),
			'to' => 'testing11@axiapayments.com',
			'subject' => 'Axia Online App Account Password Reset',
			'format' => 'html',
			'template' => 'default',
			'viewVars' => array(
					'content' => "Hello John\n" .
					"This is an automated request to reset your password please do not reply.\n" .
					"If you are not aware of a password reset request for your account, you may disregard this.\n" .
					"Otherwise, please follow the URL below to set a new password:\n" .
					"http://localhost/Users/change_pw/11"
				)
			);

		$this->assertSame($expected, $this->User->getEmailArgs(11));
		$this->assertFalse($this->User->getEmailArgs(9999));
	}

/**
 * hasDefatestHltTemplate
 *
 * @covers User::hasDefaultTemplate
 * @return void
 */
	public function testHasDefaultTemplate() {
		$tstData = ['User' => ['Template' => ['template_id' => 123]]];
		$this->User->set($tstData);

		$actual = $this->User->hasDefaultTemplate($tstData['User']['Template']);
		$this->assertTrue($actual);

		$tstData = ['User' => ['Template' => ['template_id' => null]]];
		$this->User->clear();
		$this->User->set($tstData);
		$actual = $this->User->hasDefaultTemplate($tstData['User']['Template']);
		$this->assertFalse($actual);
	}

/**
 * testGenerateRandPw
 *
 * @covers User::generateRandPw
 * @return void
 */
	public function testGenerateRandPw() {
		$actual = $this->User->generateRandPw();

		$this->assertNotEmpty($actual);
		$this->assertEquals(32, strlen($actual));
	}

/**
 * testWithCobrandsNotEmpty
 *
 * @covers User::withCobrandsNotEmpty
 * @return void
 */
	public function testWithCobrandsNotEmpty() {
		$tstData = ['User' => [
				'Template' => ['template_id' => 123],
				'Cobrand' => ['id' => 321]
			],
		];
		$this->User->set($tstData);

		$actual = $this->User->withCobrandsNotEmpty([]);
		$this->assertTrue($actual);

		$tstData = ['User' => [
				'Template' => [],
				'Cobrand' => ['id' => null]
			],
		];

		$this->User->clear();
		$this->User->set($tstData);
		$actual = $this->User->withCobrandsNotEmpty([]);
		$this->assertFalse($actual);

		$this->User->clear();
		$actual = $this->User->withCobrandsNotEmpty([]);
		$this->assertTrue($actual);
	}

/**
 * testGetEditViewData
 *
 * @covers User::getEditViewData
 * @return void
 */
	public function testGetEditViewData() {
		$actual = $this->User->getEditViewData(1);
		$userExpected = [
			'password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
			'id' => 1,
			'email' => 'testing@axiapayments.com',
			'group_id' => 1,
			'created' => '2014-01-24 11:02:22',
			'modified' => '2014-01-24 11:02:22',
			'token' => 'Lorem ipsum dolor sit amet',
			'token_used' => '2014-01-24 11:02:22',
			'token_uses' => 1,
			'firstname' => 'Lorem ipsum dolor sit amet',
			'lastname' => 'Lorem ipsum dolor sit amet',
			'extension' => 1,
			'active' => true,
			'api_password' => 'Lorem ipsum dolor sit amet',
			'api_enabled' => true,
			'template_id' => 1,
			'pw_expiry_date' => '2019-10-01 00:00:00',
			'pw_reset_hash' => null,
			'fullname' => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet',
			'wrong_log_in_count' => 0,
			'is_blocked' => false,

		];
		$managerExpected = [
			[
                'id' => 1,
                'fullname' => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet',
                'UsersManager' => [
                    'id' => '52c32849-9ebc-414c-95e6-7eb634627ad4',
                    'user_id' => 1,
                    'manager_id' => 1
                ]
           ]
		];
		$expectedTemplate = ['name' => 'Template 1 for PN1'];
		$this->assertEquals($userExpected, $actual['User']);
		$this->assertEquals($managerExpected, $actual['Manager']);
		$this->assertEquals($expectedTemplate, $actual['Template']);
	}

/**
 * testGetAssignedManagersList
 *
 * @covers User::getAssignedManagersList
 * @return void
 */
	public function testGetAssignedManagersList() {
		$userIds = $this->User->find('list', ['fields'=> ['id', 'id']]);
		$expected = [1 => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet'];

		$actual = $this->User->getAssignedManagersList(1);
		$this->assertSame($expected, $actual);

		$actual = $this->User->getAssignedManagersList(11);
		$this->assertSame($expected, $actual);

		$actual = $this->User->getAssignedManagersList(12);
		$this->assertEmpty($actual);
		
	}

/**
 * testGetJsonCobrandsTemplates
 *
 * @covers User::getJsonCobrandsTemplates
 * @return void
 */
	public function testGetJsonCobrandsTemplates() {
		$actual = $this->User->getJsonCobrandsTemplates();
		$expected = [
			4 => [
				 6 => 'Corral - Template 1 for Corral'
			],
			1 => [
				1 => 'Partner Name 1 - Template 1 for PN1',
				2 => 'Partner Name 1 - Template 2 for PN1'
			],
			2 => [
				3 => 'Partner Name 2 - Template 1 for PN2',
				4 => 'Partner Name 2 - Template used to test afterSave of app values',
				5 => 'Partner Name 2 - Template used to test getFields'
			],
		];
		$actual = json_decode($actual, true);
		$this->assertSame($expected[4][6], $actual[4][6]);
		$this->assertSame($expected[1][1], $actual[1][1]);
		$this->assertSame($expected[1][2], $actual[1][2]);
		$this->assertEquals($expected[2], $actual[2]);
	}

/**
 * testAllTemplates
 *
 * @covers User::allTemplates
 * @return void
 */
	public function testAllTemplates() {
		//Set HABTM data structure
		$user = array(
			'User' => array(
				'id' => 1,
			),
			'Cobrand' => array(
				'Cobrand' => array(1, 2)
			),
			'Template' => array(
				'Template' => array(
					1,
					2,
					3
				)
			),
		);
		//save new Cobrands/Templates for user
		$this->User->saveAll($user);
		$expected = array(1, 2);
		$actual = $this->User->allTemplates(1);

		$this->assertCount(3, $actual);
		$tstActualVals = Hash::extract($actual, '{n}.name');
		$this->assertContains('Template 1 for PN1', $tstActualVals);
		$this->assertContains('Template 2 for PN1', $tstActualVals);
		$this->assertContains('Template 1 for PN2', $tstActualVals);
		$tstActualVals = Hash::extract($actual, '{n}.cobrand_id');
		$this->assertContains(1, $tstActualVals);
		$this->assertContains(2, $tstActualVals);
		$tstActualVals = Hash::extract($actual, '{n}.id');
		$this->assertContains(1, $tstActualVals);
		$this->assertContains(2, $tstActualVals);
		$this->assertContains(3, $tstActualVals);
	}

/**
 * testTemplatesMatchCobrand()
 *
 * @covers User::templatesMatchCobrand()
 * @return void
 */
	public function testTemplatesMatchCobrand() {
		//Test invalid parameters return true
		$this->assertTrue($this->User->templatesMatchCobrand([]));

		//Test no cobrand to compare to returns false
		$data['User'] = [
			'Template' => [99,125,66],
			'Cobrand' => []
		]; //bogus template ids
		$this->User->set($data);
		$this->assertFalse($this->User->templatesMatchCobrand($data));

		//Test fake cobrands to compare to returns true
		$data['User'] = [
			'Template' => [99,125,66],
			'Cobrand' => [18,19]
		]; //bogus template ids
		$this->User->set($data);
		$this->assertTrue($this->User->templatesMatchCobrand($data));
	}

/**
 * testGetCombinedCobrandTemplateList()
 *
 * @covers User::getCombinedCobrandTemplateList()
 * @return void
 */
	public function testGetCombinedCobrandTemplateList() {
		$expected = array(6 => 'Corral - Template 1 for Corral');
		$actual = $this->User->getCombinedCobrandTemplateList([6]);
		$this->assertSame($expected, $actual);
	}

/**
 * testToggleBlockUser
 *
 * @covers User::toggleBlockUser()
 * @return void
 */
	public function testToggleBlockUser() {
		$notBLockedUser = $this->User->find('first', array('conditions' => array('is_blocked' => false))); //retrieve any unblocked user
		$this->User->toggleBlockUser($notBLockedUser['User']['id'], true);
		$this->assertTrue($this->User->hasAny(array('is_blocked' => true, 'id' => $notBLockedUser['User']['id'])));

		$this->User->toggleBlockUser($notBLockedUser['User']['id'], false);
		$this->assertFalse($this->User->hasAny(array('is_blocked' => true, 'id' => $notBLockedUser['User']['id'])));
		
	}

/**
 * testTrackIncorrectLogIn
 *
 * @covers User::trackIncorrectLogIn()
 * @return void
 */
	public function testTrackIncorrectLogIn() {

		for ($x = 1; $x < 7; $x ++) {
			$curentCount = $this->User->trackIncorrectLogIn('testing@axiapayments.com', false);
			$this->assertEquals($curentCount, $x);
		}		
		$this->assertTrue($this->User->hasAny(array('is_blocked' => true, 'email' => 'testing@axiapayments.com', 'pw_reset_hash IS NOT NULL')));
		
		$this->User->trackIncorrectLogIn('testing@axiapayments.com', true);
		$this->assertTrue($this->User->hasAny(array('is_blocked' => false, 'email' => 'testing@axiapayments.com', 'wrong_log_in_count' => 0)));
	}
}
