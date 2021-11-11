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
		for($x = 0 ; $x <=5; $x++) {
			$originalToken = Hash::get($result, 'ApplicationGroup.access_token');
			$tokenRenewCount = Hash::get($result, 'ApplicationGroup.token_renew_count', 0);

			// update/create
			$result = $this->ApplicationGroup->renewAccessToken(Hash::get($result, 'ApplicationGroup.id'), false);

			$this->assertEqual(Hash::get($result, 'ApplicationGroup.token_renew_count', 0), $x);
			$this->assertNotEqual($originalToken, Hash::get($result, 'ApplicationGroup.access_token'));
			$this->assertSame(64, strlen($result['ApplicationGroup']['access_token']));
		}
		$id = Hash::get($result, 'ApplicationGroup.id');
		//renew and reset count
		$result = $this->ApplicationGroup->renewAccessToken(Hash::get($result, 'ApplicationGroup.id'), true);

		$this->assertSame($id, Hash::get($result, 'ApplicationGroup.id'));
		$this->assertSame(0, Hash::get($result, 'ApplicationGroup.token_renew_count'));
	}


}














