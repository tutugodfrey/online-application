<?php
App::uses('ApplicationGroup', 'Model');

/**
 * ApplicationGroup Test Case
 *
 */
class ApplicationGroupTest extends CakeTestCase {

	public $autoFixtures = false;

	/**
 	* Fixtures
 	*
 	* @var array
 	*/
	public $fixtures = array(
		'app.onlineappApplicationGroup',
	);

	/**
	 * setUp method
	 *
	 * @return void
 	*/
	public function setUp() {
		parent::setUp();
		$this->ApplicationGroup = ClassRegistry::init('ApplicationGroup');
		// load data
		$this->loadFixtures('OnlineappApplicationGroup');
	}

	/**
 	* tearDown method
 	*
 	* @return void
 	*/
	public function tearDown() {
		$this->ApplicationGroup->deleteAll(true, false);
		unset($this->ApplicationGroup);
		parent::tearDown();
	}

	/**
 	* testCreateNewGroup method
 	*
 	* @return void
 	*/
	public function testCreateNewGroup() {
		$result = $this->ApplicationGroup->createNewGroup();
		$this->assertNotEmpty($result['ApplicationGroup']['id']);
		$this->assertSame(64, strlen($result['ApplicationGroup']['access_token']));
	}

	/**
 	* testFindByAccessToken method
 	*
 	* @return void
 	*/
	public function testFindByAccessToken() {
		$expected = $this->ApplicationGroup->createNewGroup();
		$actual = $this->ApplicationGroup->findByAccessToken($expected['ApplicationGroup']['access_token'], true);

		$this->assertEqual($expected['ApplicationGroup']['id'], $actual['ApplicationGroup']['id']);

		//set token as expired then check again
		$actual['ApplicationGroup']['token_expiration'] = '2000-01-06 21:53:52';
		$this->ApplicationGroup->save($actual);

		//Must return false since the token is expired
		$actual = $this->ApplicationGroup->findByAccessToken($expected['ApplicationGroup']['access_token'], true);
		$this->assertFalse($actual);
	}

	/**
 	* testFindByClientAccessToken method
 	*
 	* @return void
 	*/
	public function testFindByClientAccessToken() {
		$expected = $this->ApplicationGroup->createNewGroup();
		$actual = $this->ApplicationGroup->findByClientAccessToken($expected['ApplicationGroup']['client_access_token']);

		$this->assertEqual($expected['ApplicationGroup']['id'], $actual['ApplicationGroup']['id']);
	}

	/**
 	* testIsClientPwExpired method
 	*
 	* @return void
 	*/
	public function testIsClientPwExpired() {
		$AppGroup = $this->ApplicationGroup->createNewGroup();
		$this->assertFalse($this->ApplicationGroup->isClientPwExpired($AppGroup['ApplicationGroup']['id']));

		//set token as expired then check again
		$AppGroup['ApplicationGroup']['client_pw_expiration'] = '2000-01-06';
		$this->ApplicationGroup->save($AppGroup);

		$this->assertTrue($this->ApplicationGroup->isTokenExpired($AppGroup['ApplicationGroup']['id']));
	}

	/**
 	* testValidateClientCredentials method
 	*
 	* @return void
 	*/
	public function testValidateClientCredentials() {
		$AppGroup = $this->ApplicationGroup->createNewGroup();
		$actual = $this->ApplicationGroup->validateClientCredentials($AppGroup['ApplicationGroup']['client_access_token'], $AppGroup['ApplicationGroup']['client_password']);
		$this->assertFalse(array_key_exists('password_expired', $actual));

		//set as expired then check again
		$actual['ApplicationGroup']['client_pw_expiration'] = '2000-01-06';
		$this->ApplicationGroup->clear();
		$actual = $this->ApplicationGroup->save($actual);
		$actual = $this->ApplicationGroup->validateClientCredentials($AppGroup['ApplicationGroup']['client_access_token'], $AppGroup['ApplicationGroup']['client_password']);
		$this->assertTrue($actual['password_expired']);

		//**************restore test data
		$this->ApplicationGroup->clear();
		$actual = $this->ApplicationGroup->save($AppGroup);

		//set as client account locked
		$actual['ApplicationGroup']['client_account_locked'] = true;
		$this->ApplicationGroup->clear();
		$actual = $this->ApplicationGroup->save($actual);
		$actual = $this->ApplicationGroup->validateClientCredentials($AppGroup['ApplicationGroup']['client_access_token'], $AppGroup['ApplicationGroup']['client_password']);
		$this->assertEmpty($actual);

	}

	/**
 	* testResetPartialClientCredentials method
 	*
 	* @return void
 	*/
	public function testResetPartialClientCredentials() {
		$AppGroup = $this->ApplicationGroup->createNewGroup();
		$expectedUnchangedDate = $AppGroup['ApplicationGroup']['client_pw_expiration'] ;
		$AppGroup['ApplicationGroup']['client_password'] = 'old pasword';
		$AppGroup['ApplicationGroup']['client_fail_login_count'] = 99;
		$AppGroup['ApplicationGroup']['client_account_locked'] = true;
		$this->ApplicationGroup->save($AppGroup);
		$actual = $this->ApplicationGroup->resetPartialClientCredentials($AppGroup['ApplicationGroup']['id']);

		$this->assertNotEqual($actual['ApplicationGroup']['client_password'],$AppGroup['ApplicationGroup']['client_password']);
		$this->assertNotEqual($actual['ApplicationGroup']['client_fail_login_count'],$AppGroup['ApplicationGroup']['client_fail_login_count']);
		$this->assertNotEqual($actual['ApplicationGroup']['client_account_locked'],$AppGroup['ApplicationGroup']['client_account_locked']);
		$this->assertFalse($actual['ApplicationGroup']['client_account_locked']);
		$this->assertEqual(0, $actual['ApplicationGroup']['client_fail_login_count']);

		$fullRecord = $this->ApplicationGroup->findByClientAccessToken($AppGroup['ApplicationGroup']['client_access_token']);
		$this->assertEqual($expectedUnchangedDate, $fullRecord['ApplicationGroup']['client_pw_expiration']);
	}

	/**
 	* testResetFullClientCredentials method
 	*
 	* @return void
 	*/
	public function testResetFullClientCredentials() {
		$AppGroup = $this->ApplicationGroup->createNewGroup();
		$AppGroup['ApplicationGroup']['client_password'] = 'old pasword';
		$AppGroup['ApplicationGroup']['client_pw_expiration'] = '2000-01-01';
		$AppGroup['ApplicationGroup']['client_fail_login_count'] = 99;
		$AppGroup['ApplicationGroup']['client_account_locked'] = true;
		$this->ApplicationGroup->save($AppGroup);
		$actual = $this->ApplicationGroup->resetFullClientCredentials($AppGroup['ApplicationGroup']['id']);

		$this->assertNotEqual($actual['ApplicationGroup']['client_password'],$AppGroup['ApplicationGroup']['client_password']);
		$this->assertNotEqual($actual['ApplicationGroup']['client_fail_login_count'],$AppGroup['ApplicationGroup']['client_fail_login_count']);
		$this->assertNotEqual($actual['ApplicationGroup']['client_account_locked'],$AppGroup['ApplicationGroup']['client_account_locked']);
		$this->assertNotEqual($actual['ApplicationGroup']['client_pw_expiration'],$AppGroup['ApplicationGroup']['client_pw_expiration']);
		$this->assertFalse($actual['ApplicationGroup']['client_account_locked']);
		$this->assertEqual(0, $actual['ApplicationGroup']['client_fail_login_count']);
	}

	/**
 	* testIsTokenExpired method
 	*
 	* @return void
 	*/
	public function testIsTokenExpired() {
		$AppGroup = $this->ApplicationGroup->createNewGroup();
		$this->assertFalse($this->ApplicationGroup->isTokenExpired($AppGroup['ApplicationGroup']['access_token']));

		//set token as expired then check again
		$AppGroup['ApplicationGroup']['token_expiration'] = '2000-01-06 21:53:52';
		$this->ApplicationGroup->save($AppGroup);

		$this->assertTrue($this->ApplicationGroup->isTokenExpired($AppGroup['ApplicationGroup']['access_token']));
	}

	/**
 	* testRenewAccessToken method
 	*
 	* @return void
 	*/
	public function testRenewAccessToken() {
		$result = [];
		for ($x = 0 ; $x <=5; $x++) {
			$originalToken = Hash::get($result, 'ApplicationGroup.access_token');
			$tokenRenewCount = Hash::get($result, 'ApplicationGroup.token_renew_count', 0);

			// update/create
			$result = $this->ApplicationGroup->renewAccessToken(Hash::get($result, 'ApplicationGroup.id'));

			$this->assertNotEqual($originalToken, Hash::get($result, 'ApplicationGroup.access_token'));
			$this->assertSame(64, strlen($result['ApplicationGroup']['access_token']));
		}
	}


}














