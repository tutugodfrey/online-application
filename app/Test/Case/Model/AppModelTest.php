<?php
App::uses('AppModel', 'Model');

/**
 * AppModel Test Test Case
 *
 */
class AppModelTest extends CakeTestCase {

	public $autoFixtures = false;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AppModel = ClassRegistry::init('AppModel');

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AppModel);
		parent::tearDown();
	}

/**
 * testWordsAreWithinMinLevenshtein
 *
 * @covers AppModel::wordsAreWithinMinLevenshtein()
 * @return void
 */
	public function testWordsAreWithinMinLevenshtein() {
		$tstStrings = ['hello', 'world', 'good bye', 'earth'];
		$actual = $this->AppModel->wordsAreWithinMinLevenshtein('Helo', $tstStrings, 2);
		$this->assertSame('hello', $actual);

		$actual = $this->AppModel->wordsAreWithinMinLevenshtein('word', $tstStrings, 2);
		$this->assertSame('world', $actual);

		$actual = $this->AppModel->wordsAreWithinMinLevenshtein('god bye', $tstStrings, 2);
		$this->assertSame('good bye', $actual);

		$actual = $this->AppModel->wordsAreWithinMinLevenshtein('dirt', $tstStrings, 2);
		$this->assertFalse($actual);

		$actual = $this->AppModel->wordsAreWithinMinLevenshtein('erth', $tstStrings, 2);
		$this->assertSame('earth', $actual);

		$actual = $this->AppModel->wordsAreWithinMinLevenshtein('stuff', $tstStrings, 2);
		$this->assertFalse($actual);
	}
/**
 * testTrimExtra
 *
 * @covers AppModel::trimExtra()
 * @return void
 */
	public function testTrimExtra() {
		$actual = $this->AppModel->trimExtra('    space      the final    frontier  .  .  .   ');
		$expected = 'space the final frontier . . .';
		$this->assertSame($expected, $actual);
	}

/**
 * testGetRandHash
 *
 * @covers AppModel::getRandHash()
 * @return void
 */
	public function testGetRandHash() {
		$result = $this->AppModel->getRandHash();
		$this->assertNotEmpty($result);
	}

/**
 * testCreateAxiaDbApiAuthClient
 *
 * @covers AppModel::createAxiaDbApiAuthClient()
 * @return void
 */
	public function testCreateAxiaDbApiAuthClient() {
		$result = $this->AppModel->createAxiaDbApiAuthClient();
		
		$this->assertNotEmpty($result);
		$this->assertTrue(is_object($result));
	}

/**
 * testValidateFieldsEqual
 *
 * @covers AppModel::validateFieldsEqual()
 * @return void
 */
	public function testValidateFieldsEqual() {
		$fieldName1 = 'field1';
		$fieldName2 = 'field2';
		$check = ['AppModel' => [$fieldName1 => 25, $fieldName2 => 26]];
		$this->AppModel->set($check);
		$this->assertFalse($this->AppModel->validateFieldsEqual($check, $fieldName1, $fieldName2));
		
		$check = ['AppModel' => [$fieldName1 => 25, $fieldName2 => 25]];
		$this->AppModel->clear();
		$this->AppModel->set($check);
		$this->assertTrue($this->AppModel->validateFieldsEqual($check, $fieldName1, $fieldName2));

		$check = null;
		$this->AppModel->clear();
		$this->AppModel->set($check);
		$this->assertFalse($this->AppModel->validateFieldsEqual($check, $fieldName1, $fieldName2));
	}

}
