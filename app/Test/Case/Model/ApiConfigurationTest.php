<?php
App::uses('ApiConfiguration', 'Model');

/**
 * ApiConfiguration Test Test Case
 *
 */
class ApiConfigurationTest extends CakeTestCase {

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
		$this->ApiConfiguration = ClassRegistry::init('ApiConfiguration');
		// load data
		$this->loadFixtures('OnlineappApiConfiguration');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ApiConfiguration);
		parent::tearDown();
	}

/**
 * testBeforeSave
 *
 * @covers ApiConfiguration::beforeSave()
 * @return void
 */
	public function testBeforeSave() {
		$data =[
			'ApiConfiguration' => [
				'client_secret' => 'client secret123',
				'access_token' => 'access token123',
				'refresh_token' =>  'refresh token123'
			]
		];
		$this->ApiConfiguration->set($data);
		$this->ApiConfiguration->beforeSave();

		$this->assertTrue($this->ApiConfiguration->isEncrypted($this->ApiConfiguration->data['ApiConfiguration']['client_secret']));
		$this->assertTrue($this->ApiConfiguration->isEncrypted($this->ApiConfiguration->data['ApiConfiguration']['access_token']));
		$this->assertTrue($this->ApiConfiguration->isEncrypted($this->ApiConfiguration->data['ApiConfiguration']['refresh_token']));
		
	}

/**
 * testAfterFind
 *
 * @covers ApiConfiguration::afterFind()
 * @return void
 */
	public function testAfterFind() {
		$actual = $this->ApiConfiguration->find('first');
		
		$this->assertFalse($this->ApiConfiguration->isEncrypted($actual['ApiConfiguration']['client_secret']));
		$this->assertFalse($this->ApiConfiguration->isEncrypted($actual['ApiConfiguration']['access_token']));
		$this->assertFalse($this->ApiConfiguration->isEncrypted($actual['ApiConfiguration']['refresh_token']));

		$data[] = [
			'ApiConfiguration' => [
				'client_secret' => 'Ywz6YRLkfNGC3SNpyiFcfSRMyNVhR+Mzftbxt0ytr1hUQtVjQipKOhS2g7E79C3x/pGREibHasSguKLL/VI1wnpSGaWXchHpkQA6nPvShbQ=',
                'access_token' => 'Ywz6YRLkfNGC3SNpyiFcfQkJNMI1Z+ErONeLb71bmWWdhgfmLz1MBrYKPj7bQjRsg0HxRC7crxotlnq95ri3mg==',
                'refresh_token' => 'Ywz6YRLkfNGC3SNpyiFcfW3w5ucyfURXaY3ji2WB/jm/fziU6QTPqwf8EhK1NY+fa9euEn9NkMKNcUYcsyHVjz/ncRykz21Hz+mdnSfihP8='
			]
		];
		$actual = $this->ApiConfiguration->afterFind($data, true);
		$this->assertFalse($this->ApiConfiguration->isEncrypted($actual[0]['ApiConfiguration']['client_secret']));
		$this->assertFalse($this->ApiConfiguration->isEncrypted($actual[0]['ApiConfiguration']['access_token']));
		$this->assertFalse($this->ApiConfiguration->isEncrypted($actual[0]['ApiConfiguration']['refresh_token']));

		$this->assertEqual('client secret123',$actual[0]['ApiConfiguration']['client_secret']);
		$this->assertEqual('access token123',$actual[0]['ApiConfiguration']['access_token']);
		$this->assertEqual('refresh token123',$actual[0]['ApiConfiguration']['refresh_token']);
	}

}
