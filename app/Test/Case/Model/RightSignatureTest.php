<?php
App::uses('RightSignature', 'Model');

/**
 * RightSignature Test Test Case
 *
 */
class RightSignatureTest extends CakeTestCase {

	public $autoFixtures = false;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
			'app.onlineappApiConfiguration',
		];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		// load data
		$this->loadFixtures('OnlineappApiConfiguration');

		$this->RightSignature = ClassRegistry::init('RightSignature');
		$this->ApiConfiguration = ClassRegistry::init('ApiConfiguration');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RightSignature);
		parent::tearDown();
	}

/**
 * testGetApiConfig
 *
 * @covers RightSignature::getApiConfig()
 * @return void
 */
	public function testGetApiConfig() {
		$expected = $this->ApiConfiguration->find('first', ['conditions' => ['configuration_name' => RightSignature::RS_CONFIG_NAME_DEV]]);
		$actual = $this->RightSignature->getApiConfig();
		$this->assertEqual($expected, $actual);
	}

/**
 * testRenewAccessTokenIfExpired
 *
 * @covers RightSignature::renewAccessTokenIfExpired()
 * @return void
 */
	public function testRenewAccessTokenIfExpired() {
		//Test no config data passes
		$config = [];
		$this->assertFalse($this->RightSignature->renewAccessTokenIfExpired($config));

		//Saved configuration in fixture will never expire so this reurns true
		$config = $this->RightSignature->getApiConfig();
		$this->assertTrue($this->RightSignature->renewAccessTokenIfExpired($config));
	}

/**
 * test_GetRefreshTokenParamsStr
 *
 * @covers RightSignature::_getRefreshTokenParamsStr()
 * @return void
 */
	public function test_GetRefreshTokenParamsStr() {
		$reflection = new ReflectionClass('RightSignature');
		$method = $reflection->getMethod('_getRefreshTokenParamsStr');
		$method->setAccessible(true);

		$config = $this->RightSignature->getApiConfig();
		$actual = $method->invokeArgs($this->RightSignature, [&$config]);
		$expected = 'grant_type=refresh_token&client_id=tstclientid123&client_secret=tstsecret&refresh_token=refreshtesttoken123abc';
		$this->assertEqual($expected, $actual);
	}

/**
 * testIsExpiredToken
 *
 * @covers RightSignature::isExpiredToken()
 * @return void
 */
	public function testIsExpiredToken() {
		//No timestamp parater interpreted as expired
		$this->assertTrue($this->RightSignature->isExpiredToken('', null));
		
		//Unspecified valid number if seconds is interpreted as never expires
		$this->assertFalse($this->RightSignature->isExpiredToken(time(), null));

		//Test with expired timestamp valid for only 100 secs from time() - 200
		$secondsValid = 100;
		$issuedTimestamp = time() - 200;
		//Unspecified valid number if seconds is interpreted as never expires
		$this->assertTrue($this->RightSignature->isExpiredToken($issuedTimestamp, $secondsValid));

		$secondsValid = 1000;
		$issuedTimestamp = time();
		//Unspecified valid number if seconds is interpreted as never expires
		$this->assertFalse($this->RightSignature->isExpiredToken($issuedTimestamp, $secondsValid));

	}
}
