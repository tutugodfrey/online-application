<?php
App::uses('CobrandedApplication', 'Model');

/**
 * CobrandedApplication Test Case
 *
 */
class CobrandedApplicationTest extends CakeTestCase {

	public $autoFixtures = false;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.onlineappApiConfiguration',
		'app.onlineappApip',
		'app.onlineappUser',
		'app.onlineappGroup',
		'app.onlineappApplicationGroup',
		'app.onlineappCobrand',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappTemplateSection',
		'app.onlineappTemplateField',
		'app.onlineappCobrandedApplication',
		'app.onlineappCobrandedApplicationValue',
		'app.onlineappCobrandedApplicationAch',
		'app.onlineappCoversheet',
		'app.onlineappEmailTimelineSubject',
		'app.onlineappEmailTimeline',
		'app.onlineappUsersManager',
		'app.onlineappUsersCobrand',
		'app.onlineappUsersTemplate',
		'app.merchant',
	);

	private $__template;

	private $__user;

	private $__cobrandedApplication;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Group = ClassRegistry::init('Group');
		$this->User = ClassRegistry::init('User');
		$this->Coversheet = ClassRegistry::init('Coversheet');
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');
		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
		$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');
		$this->CobrandedApplicationAch = ClassRegistry::init('CobrandedApplicationAch');
		$this->EmailTimelineSubject = ClassRegistry::init('EmailTimelineSubject');
		$this->EmailTimeline = ClassRegistry::init('EmailTimeline');
		$this->Merchant = ClassRegistry::init('Merchant');

		// load data
		$this->loadFixtures('OnlineappApiConfiguration');
		$this->loadFixtures('OnlineappApplicationGroup');
		$this->loadFixtures('OnlineappApip');
		$this->loadFixtures('OnlineappUser');
		$this->loadFixtures('OnlineappGroup');
		$this->loadFixtures('OnlineappCobrand');
		$this->loadFixtures('OnlineappTemplate');
		$this->loadFixtures('OnlineappTemplatePage');
		$this->loadFixtures('OnlineappTemplateSection');
		$this->loadFixtures('OnlineappTemplateField');
		$this->loadFixtures('OnlineappCobrandedApplication');
		$this->loadFixtures('OnlineappCobrandedApplicationValue');
		$this->loadFixtures('OnlineappCobrandedApplicationAch');
		$this->loadFixtures('OnlineappEmailTimelineSubject');
		$this->loadFixtures('OnlineappEmailTimeline');
		$this->loadFixtures('OnlineappUsersManager');
		$this->loadFixtures('OnlineappUsersCobrand');
		$this->loadFixtures('OnlineappUsersTemplate');
		$this->loadFixtures('Merchant');

		$this->__template = $this->Template->find(
			'first',
			array(
				'conditions' => array(
					'name' => 'Template used to test afterSave of app values',
				)
			)
		);

		//$this->User->create(
		$user =	array(
			'id' => 1,
				'email' => 'testing@axiapayments.com',
				'password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
				'group_id' => 1,
				'created' => '2014-01-24 11:02:22',
				'modified' => '2014-01-24 11:02:22',
				'token' => 'sometokenvalue',
				'token_used' => '2014-01-24 11:02:22',
				'token_uses' => 1,
				'firstname' => 'testuser1firstname',
				'lastname' => 'testuser1lastname',
				'extension' => 1,
				'active' => 1,
				'api_password' => 'notset',
				'api_enabled' => 1,
				'template_id' => $this->__template['Template']['id'],
		);

		$this->__user = $this->User->save($user);
		$cobrandedApplication = $this->CobrandedApplication->find('first', array('recursive' => -1));
		$this->CobrandedApplication->id = $cobrandedApplication['CobrandedApplication']['id'];
		$this->__cobrandedApplication = $this->CobrandedApplication->saveField('user_id', $this->__user['User']['id']);
		$this->loadFixtures('OnlineappCoversheet');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		$this->Coversheet->deleteAll(true, false);
		$this->EmailTimeline->deleteAll(true, false);
		$this->EmailTimelineSubject->deleteAll(true, false);
		$this->CobrandedApplicationAch->deleteAll(true, false);
		$this->CobrandedApplicationValue->deleteAll(true, false);
		$this->CobrandedApplication->deleteAll(true, false);
		$this->User->delete($this->__user['User']['id']);
		$this->Group->deleteAll(true, false);
		$this->TemplateField->deleteAll(true, false);
		$this->TemplateSection->deleteAll(true, false);
		$this->TemplatePage->deleteAll(true, false);
		$this->Template->deleteAll(true, false);
		$this->Cobrand->deleteAll(true, false);
		unset($this->Coversheet);
		unset($this->CobrandedApplicationAch);
		unset($this->CobrandedApplicationValue);
		unset($this->CobrandedApplication);
		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);
		unset($this->User);
		unset($this->Group);
		unset($this->EmailTimeline);
		unset($this->EmailTimelineSubject);

		parent::tearDown();
	}

/**
 * testSyncAppValuesType4Change1()
 * Test that a change to one of the options specified in the TemplateField.default_value
 * syncs corresponding CobrandedApplicationValue
 *
 * @param array $existingTmpltFields TemplateFieldData emulating a Cobranded Aplication's existing template/TemplateFields
 * @param array $newTmpltFields TemplateFieldData to use to add new emplateFields to existing Cobranded Aplication's Template
 * @param array $changes Containes changes that will me appliced to he existing TemplateFields which will trigger desynchronizations of Applications
 * @param array $expectations Espected results
 * @covers CobrandedApplication::syncAppValues()
 * @dataProvider providerSyncAppValuesType4And5
 * @return void
 */
	public function testSyncAppValuesTypes4Andn5($existingTmpltFields, $newTmpltFields, $changes, $expectations) {
		//*********BEGIN TEST SETUP
		//Detach behavior irrelevant for this test
		$this->__detachBehavior('OrderableChild');
		//Create all data needed
		$response = $this->__saveTstDataForAppSyncProcedure($existingTmpltFields);
		$appId = $response['cobrandedApplication']['id'];
		//Create counter of how many CAVs we expect to be created
		$expectedCountOfCreatedCAVs = 0;
		//*********END SETUP
		//Apply all changes that will cause desynchronization with app
		foreach ($existingTmpltFields as $idx => $tField) {
			//Apply all changes
			foreach ($changes as $change) {
				//Apply changes to default_value
				if (!empty($change['default_value'])) {
					$existingTmpltFields[$idx]['default_value'] = $change['default_value'];
				}
				//Apply change merge_field_name
				if (!empty($change['merge_field_name'])) {
					$existingTmpltFields[$idx]['merge_field_name'] = $change['merge_field_name'];
				}
			}
		}
		//Count exptected CAVs
		foreach ($existingTmpltFields as $idx => $tField) {
			if ($tField['type'] === 4 || $tField['type'] === 5 || $tField['type'] === 7) {
				$choices = explode(',', $tField['default_value']);
				//For each choice of each one of these types we expect a new CAV to be created
				$expectedCountOfCreatedCAVs += count($choices);
			} else {
				$expectedCountOfCreatedCAVs += 1;
			}
		}

		$original = $this->CobrandedApplication->find('first', array(
			'recursive' => -1,
			'conditions' => array('id' => $appId),
			'contain' => array('CobrandedApplicationValues')
		));

		//App should not be out of sync
		$this->assertEmpty($original['CobrandedApplication']['data_to_sync']);

		//Save changes to TemplateField
		$this->TemplateField->saveMany($existingTmpltFields);

		//App should now be out of sync
		$dataToSync = $this->CobrandedApplication->field('data_to_sync', array('id' => $appId));
		$this->assertNotEmpty($dataToSync);

		//Perform sync.
		$this->assertTrue($this->CobrandedApplication->syncApp($appId));

		//Get synced App and CAVs
		$syncedData = $this->CobrandedApplication->find('first', array(
			'recursive' => -1,
			'conditions' => array('id' => $appId),
			'contain' => array('CobrandedApplicationValues')
		));

		//Count created CobrandedApplicationValues
		$countTotalCreatedCAVs = count($syncedData['CobrandedApplicationValues']);

		$hasCavNameExpectation = !empty(Hash::extract($expectations, '{n}.synced_CAV_name'));
		$hasDefaultValsExpectation = !empty(Hash::extract($expectations, '{n}.expected_defaults'));

		//Iterate through each synced CAV
		foreach ($syncedData['CobrandedApplicationValues'] as $cav) {
			$cavNameExpectationFulfilled = false;
			$defaultValExpectationFulfilled = false;

			foreach ($expectations as $expected) {
				//Expected synhronized CobrandedApplicationValue.name (CAV)
				if ($hasCavNameExpectation && $cavNameExpectationFulfilled === false) {
					$expectedCAVname = $expected['synced_CAV_name'];
					//Actual synhronized name CobrandedApplicationValue
					$actualSyncedCavName = Hash::get($cav, "name");
					$cavNameExpectationFulfilled = ($expectedCAVname === $actualSyncedCavName);
					if ($cavNameExpectationFulfilled) {
						$this->assertEquals($expectedCAVname, $actualSyncedCavName);
					}
				}

				//Are we expecting default values being set?
				if ($hasDefaultValsExpectation && $defaultValExpectationFulfilled === false) {
					$defaultValExpectationFulfilled = ($expected['expected_defaults'] === $cav['value']);
					if ($defaultValExpectationFulfilled) {
						$this->assertEquals($expected['expected_defaults'], $cav['value']);
					}
				}

			}
			if ($hasCavNameExpectation && $cavNameExpectationFulfilled === false) {
				$this->fail("Failed asserting that {$cav['name']} matches one of the exptected name values in: \n" . print_r(Hash::extract($expectations, '{n}.synced_CAV_name'), true));
			}

			if ($hasDefaultValsExpectation && $defaultValExpectationFulfilled === false) {
				$this->fail("Failed asserting that syncronized CobrandedApplicationValues.value(s) match any expected defaults in: \n" . print_r(Hash::extract($expectations, '{n}.expected_defaults'), true));
			}
		}

		/*---------------------------------------------------------------------------------------------
			Next, test adding new TemplateFields to the existing CobrandedApplication's Template and sync
		-----------------------------------------------------------------------------------------------*/
		if (!empty($newTmpltFields)) {
			$this->TemplateField->saveMany($newTmpltFields);

			$outOfSync = $this->CobrandedApplication->find('first', array(
				'recursive' => -1,
				'conditions' => array('id' => $appId),
				'contain' => array('CobrandedApplicationValues')
			));

			//App should now be out of sync
			$this->assertNotEmpty($outOfSync['CobrandedApplication']['data_to_sync']);

			//Perform sync.
			$this->assertTrue($this->CobrandedApplication->syncApp($appId));

			//Get synced App and CAVs
			$syncedData = $this->CobrandedApplication->find('first', array(
				'recursive' => -1,
				'conditions' => array('id' => $appId),
				'contain' => array('CobrandedApplicationValues')
			));

			//Verify CAVs that where already there before new ones were added did not get modified at all by the sync process
			foreach (Hash::extract($syncedData, 'CobrandedApplicationValues') as $idx => $syncedVal) {
				foreach ($outOfSync['CobrandedApplicationValues'] as $existingCav) {
					if ($syncedVal['id'] === $existingCav['id']) {
						$this->assertEquals($existingCav['cobranded_application_id'], $syncedVal['cobranded_application_id']);
						$this->assertEquals($existingCav['template_field_id'], $syncedVal['template_field_id']);
						$this->assertEquals($existingCav['name'], $syncedVal['name']);
						$this->assertEquals($existingCav['value'], $syncedVal['value']);
						unset($syncedData['CobrandedApplicationValues'][$idx]);
					}
				}
			}

			//Add new CAVs to counter
			$countTotalCreatedCAVs += count($syncedData['CobrandedApplicationValues']);

			//check new CAV are created for TemplateField
			foreach ($newTmpltFields as $templateField) {
				$type = $templateField['type'];

				//Increment counters
				if ($type === 4 || $type === 5 || $type === 7) {
					$choices = explode(',', $templateField['default_value']);
					//For each choice of each one of these types we expect a new CAV to be created
					$expectedCountOfCreatedCAVs += count($choices);
				} else {
					$expectedCountOfCreatedCAVs += 1;
				}

				//Evalutate synchronicity
				foreach ($syncedData['CobrandedApplicationValues'] as $syncedCav) {
					if ($syncedCav['template_field_id'] === $templateField['id']) {
						//check new CAV are created for each mult-choice template field
						if ($type === 4 || $type === 5 || $type === 7) {
							$choices = explode(',', $templateField['default_value']);
							foreach ($choices as $keyValStr) {
								//Extact options' values keys and any defaults
								$key = Hash::get(explode('::', $keyValStr), '0');
								$val = Hash::get(explode('::', $keyValStr), '1');
								$val = preg_replace('/\{.*\}$/', '', $val);//remove default option value from $val
								$default = null;
								if ($type === 4 && preg_match('/\{default\}/i', $keyValStr)) {
									$default = 'true';
								} else {
									//For all others the default is potentially present in $keyValStr
									preg_match('/\{(.+)\}/', $keyValStr, $matches); //$matches array will be filled with 2 entries
									$default = Hash::get($matches, '1'); //we want the second match or null
								}

								//Find name matching current CAV.name
								if (($type === 4 || $type === 5) && $templateField['merge_field_name'] . $val === $syncedCav['name']) {
									$this->assertEquals($templateField['merge_field_name'] . $val, $syncedCav['name']);
									$matchFound = true;
									break; //innermost for loop
								} elseif ($type === 7 && $syncedCav['name'] === $val) {
									$this->assertEquals($val, $syncedCav['name']);
									$matchFound = true;
									break; //innermost for loop
								}
							}// end innermost for loop

							if (isset($matchFound)) {
								unset($matchFound);
								////Are we expecting default values being set?
								if (!is_null($default)) {
									$this->assertEquals($syncedCav['value'], $default);
								}
							} else {
								$this->fail("Failed asserting CobrandedApplicationValues.name:\n '{$syncedCav['name']}' \nmatches any [TemplateField.merge_field_name] + [OptionKey] combination");
							}
						} else {
							$default = null;
							//check new CAV are created for each non-mult-choice template field
							if ($type === 20) { //select - special case is multi-choice but not multi-CAV

								foreach (explode(',', $templateField['default_value']) as $keyValStr) {
									if (preg_match('/\{default\}/i', $keyValStr)) {
										$default = Hash::get(explode('::', $keyValStr), '1'); //we want the option value set as the defalut
										$default = preg_replace('/\{.*\}$/', '', $default);
										break;
									}
								}
							} else {
								$default = $templateField['default_value'];
							}
							$this->assertEquals($templateField['merge_field_name'], $syncedCav['name']);
							////Are we expecting default values being set?
							if (!empty($default)) {
								$this->assertEquals($syncedCav['value'], $default);
							}
						}
					}
				}
			}
		}
		//The total number of crated CAVs must always match the number of TemplateFields and/or
		//the number Key::Val options set in TemplateFields.default_value for multi-choice data types
		$this->assertEquals($countTotalCreatedCAVs, $expectedCountOfCreatedCAVs);
	}

/**
 * Provider for testSyncAppValuesTypes4Andn5
 *
 * @return array
 */
	public function providerSyncAppValuesType4And5() {
		return array(
		//******************Data set 1
			array(
				//existingTmpltFields - Data Structure is as required for saveMany operation
				array(
					//TemplateField Data type 4 radio
					array(
						'id' => 12345,
						'name' => 'field sync',
						'width' => 12,
						'description' => 'Test sync',
						'type' => 4, //radio type
						'required' => 1,
						'source' => 1,
						'default_value' => 'Key1::Val1,Key2::Val2',
						'merge_field_name' => 'radio_from_user_without_default-',
						'order' => 2,
						'section_id' => 123,
						'rep_only' => false,
						'encrypt' => false,
					),
					//TemplateField Data type 5 percents
					array(
						'id' => 12346,
						'name' => 'field sync',
						'width' => 12,
						'description' => 'Test sync',
						'type' => 5, //percents type
						'required' => 1,
						'source' => 1,
						'default_value' => 'Key1::Val1,Key2::Val2',
						'merge_field_name' => 'pcts_from_user_without_default-',
						'order' => 2,
						'section_id' => 123,
						'rep_only' => false,
						'encrypt' => false,
					)
				),
				array(
					//newTmpltFields
					//none for this set
				),
				//Changes param
				array(
					array(
						//Change only default_value changing only Val1
						'default_value' => 'Key1::ChangedVal1,Key2::Val2,Key3::Val3',
						'merge_field_name' => null,
					)
				),
				//Expected params ALL EXPECTATIONS MUST BE FULLFILLED
				array(
					array( //first option type 4
						'synced_CAV_name' => 'radio_from_user_without_default-ChangedVal1',
						'expected_defaults' => null //array
					),
					array( //second opiton type 4 expect nothing changed
						'synced_CAV_name' => 'radio_from_user_without_default-Val2',
						'expected_defaults' => null //array
					),
					array( //first option type 5
						'synced_CAV_name' => 'pcts_from_user_without_default-ChangedVal1',
						'expected_defaults' => null //array
					),
					array( //second opiton type 5 expect nothing changed
						'synced_CAV_name' => 'pcts_from_user_without_default-Val2',
						'expected_defaults' => null //array
					),
					array( //second opiton type 5 expect nothing changed
						'synced_CAV_name' => 'pcts_from_user_without_default-Val3',
						'expected_defaults' => null //array
					),
					array( //second opiton type 5 expect nothing changed
						'synced_CAV_name' => 'radio_from_user_without_default-Val3',
						'expected_defaults' => null //array
					)
				)
			),
		//******************Data set 2
			array(
					//existingTmpltFields - Data Structure is as required for saveMany operation
				array(
					//TemplateField Data type 7 fees
					array(
						'id' => 12345,
						'name' => 'field sync',
						'width' => 12,
						'description' => 'Test sync',
						'type' => 7, //radio type
						'required' => 1,
						'source' => 1,
						'default_value' => 'Key1::Val1,Key2::Val2',
						'merge_field_name' => '',
						'order' => 2,
						'section_id' => 123,
						'rep_only' => false,
						'encrypt' => false,
					),
				),
				array(
					//newTmpltFields
					//none for this set
				),
				//Changes param
				array(
					array(
						//Change values and add default fees for both options
						'default_value' => 'Key1::ChangedVal1{5},Key2::ChangedVal2{6}',
						'merge_field_name' => null,
					)
				),
				//Expected params ALL EXPECTATIONS MUST BE FULLFILLED
				array(
					array( //first option type 7
						'synced_CAV_name' => 'ChangedVal1', //name should be just the option value
						'expected_defaults' => '5' //array
					),
					array( //second opiton type 7
						'synced_CAV_name' => 'ChangedVal2', //name should be just the option value
						'expected_defaults' => '6' //array
					),
				)
			),
		//******************Data set 3
			array(
					//existingTmpltFields - Data Structure is as required for saveMany operation
				array(
					//TemplateField Data type 20 fees
					array(
						'id' => 12345,
						'name' => 'field sync',
						'width' => 12,
						'description' => 'Test sync',
						'type' => 20, //select type
						'required' => 1,
						'source' => 1,
						'default_value' => 'Key1::Val1,Key2::Val2,Key3::Val3,Key4::Val4,Key5::Val5,Key6::Val6',
						'merge_field_name' => 'Select Menu',
						'order' => 2,
						'section_id' => 123,
						'rep_only' => false,
						'encrypt' => false,
					),
				),
				array(
					//newTmpltFields
					//none for this set
				),
				//Changes param
				array(
					array(
						//Change merge_field_name and add the very last option
						'default_value' => 'Key1::Val1,Key2::Val2,Key3::Val3,Key4::Val4,Key5::Val5,Key6::ThisChanged,Key5::ThisIsNew',
						'merge_field_name' => 'Changed Select Menu Name',
					)
				),
				//Expected params ALL EXPECTATIONS MUST BE FULLFILLED
				array(
					array( //type 20
						'synced_CAV_name' => 'Changed Select Menu Name', //name should be just the option value
						'expected_defaults' => null //array
					),
				)
			),
		//******************Data set 4
			array(
					//existingTmpltFields - Data Structure is as required for saveMany operation
				array(
					//TemplateField Data type 20 fees
					array(
						'id' => 12345,
						'name' => 'field sync',
						'width' => 12,
						'description' => 'Test sync',
						'type' => 0, //text type
						'required' => 1,
						'source' => 1,
						'default_value' => null,
						'merge_field_name' => 'TextField',
						'order' => 2,
						'section_id' => 123,
						'rep_only' => false,
						'encrypt' => false,
					),
				),
				array(
					//newTmpltFields add several new fields of diferent types
					array(
						'id' => 12345,
						'name' => 'field sync',
						'width' => 12,
						'description' => 'Test sync',
						'type' => 4, //radio type
						'required' => 1,
						'source' => 1,
						'default_value' => 'Key1::Val1,Key2::Val2{default}',
						'merge_field_name' => 'radio_from_user_with_default-',
						'order' => 2,
						'section_id' => 123,
						'rep_only' => false,
						'encrypt' => false,
					),
					array(
						'id' => 11223,
						'name' => 'field sync',
						'width' => 12,
						'description' => 'Test sync',
						'type' => 20, //select type
						'required' => 1,
						'source' => 1,
						'default_value' => 'Key1::Val1,Key2::Val2,Key3::Val3,Key4::Val4,Key5::Val5,Key6::Val6{default}',
						'merge_field_name' => 'Select Menu With Defauit',
						'order' => 2,
						'section_id' => 123,
						'rep_only' => false,
						'encrypt' => false,
					),
					array(
						'id' => 54321,
						'name' => 'Additional text field',
						'width' => 12,
						'description' => 'Test sync',
						'type' => 0, //text type
						'required' => 1,
						'source' => 1,
						'default_value' => null,
						'merge_field_name' => 'AdditionalTextField',
						'order' => 2,
						'section_id' => 123,
						'rep_only' => false,
						'encrypt' => false,
					),
					array(
						'id' => 44321,
						'name' => 'Additional Percents fields',
						'width' => 12,
						'description' => 'Test sync',
						'type' => 5, //percents type
						'required' => 1,
						'source' => 1,
						'default_value' => 'Key1::Val1,Key2::Val2{10}',
						'merge_field_name' => 'additional_pcts_with_default-',
						'order' => 2,
						'section_id' => 123,
						'rep_only' => false,
						'encrypt' => false,
					),
					array(
						'id' => 43321,
						'name' => 'Additional Fees fields',
						'width' => 12,
						'description' => 'Test sync',
						'type' => 7, //fees type
						'required' => 1,
						'source' => 1,
						'default_value' => 'Key1::Val1{2},Key2::Val2{15}',
						'merge_field_name' => 'additional_fees_with_default-',
						'order' => 2,
						'section_id' => 123,
						'rep_only' => false,
						'encrypt' => false,
					)
				),
				//Changes param
				array(
					array(
						//Change merge_field_name and set default
						'default_value' => 'Some text',
						'merge_field_name' => 'Changed Name',
					)
				),
				//Expected params ALL EXPECTATIONS MUST BE FULLFILLED
				array(
					array( //type 20
						'synced_CAV_name' => 'Changed Name', //name should be just the option value
						'expected_defaults' => 'Some text' //array
					),
				)
			),
		);
	}

/**
 * __detachBehavior()
 * Utility method to detach behavior
 *
 * @param array $templateFields template fields to save with test data, each in an its own array
 * @return void
 */
	private function __detachBehavior($name) {
		$this->TemplatePage->Behaviors->detach($name);
		$this->TemplateSection->Behaviors->detach($name);
		$this->TemplateField->Behaviors->detach($name);
	}

/**
 * __saveTstDataForAppSyncProcedure()
 * Utility method to create test associated Template data with 1 page, section and n field(s)
 *
 * @param array $templateFields template fields to save with test data, each in an its own array
 * @return void
 */
	private function __saveTstDataForAppSyncProcedure($templateFields) {
		//Create new Template with 1 page, section and 1 field
		$data = array(
			'id' => 123,
			'name' => 'Template 123',
			'description' => 'Tst Sync',
			'cobrand_id' => 123,
			'logo_position' => 0,
			'owner_equity_threshold' => 50,
			'requires_coversheet' => false
		);
		$this->Template->create();
		$this->Template->save($data);
		$data = array(
			'id' => 123,
			'name' => 'Page 1',
			'description' => 'Test Sync',
			'template_id' => 123,
			'order' => 0,
			'rep_only' => false,
		);
		$this->TemplatePage->create();
		$this->TemplatePage->save($data, array('validate' => false));
		$data = array(
			'id' => 123,
			'name' => 'Page Section 1',
			'width' => 12,
			'rep_only' => false,
			'description' => '',
			'page_id' => 123,
			'order' => 0,
		);
		$this->TemplateSection->create();
		$this->TemplateSection->save($data);
		$this->TemplateField->saveMany($templateFields);
		$uuid = CakeText::uuid();
		$user = array(
			'id' => 123,
			'uuid' => $uuid,
			'template_id' => 123

		);
		//Create App w/new template
		return $this->CobrandedApplication->createOnlineappForUser($user, ['uuid' => $uuid]);
	}

/**
 * __saveTstCAVData()
 * Utility method to create test CobrandedApplicationValues CAVs
 *
 * @param array $cav CobrandedApplicationValues (CAVs) data structure sjould be compliant and ready for a saveMany operation
 * @return void
 */
	private function __saveTstCAVData($cav) {
		//saveMany with many - Sequential
		if (array_keys($cav) === range(0, count($cav) -1)) {
			$data = $cav;
		} else {
			//saveMany with only one - associative
			//Make it sequential
			$data = array($cav);
		}
		$this->CobrandedApplicationValue->saveMany($data);
	}

/**
 * testSetDataExceptionThrown
 *
 * @covers CobrandedApplication::setDataToSync()
 * @expectedException InvalidArgumentException
 * @expectedExceptionMessage Expected TemplateField data is missing array argument.
 * @return void
 */
	public function testSetDataToSyncExceptionThrown() {
		$this->CobrandedApplication->setDataToSync(array('junk and stuff'));
	}

/**
 * testSetExportedDate()
 *
 * @covers CobrandedApplication::setExportedDate()
 * @return void
 */
	public function testSetExportedDate() {
		$expected = $this->CobrandedApplication->find('first', ['recursive' => -1, 'conditions' => ['CobrandedApplication.id' => 1]]);
		$this->assertEmpty($expected['CobrandedApplication']['api_exported_date']);
		$this->assertEmpty($expected['CobrandedApplication']['csv_exported_date']);
		
		$this->CobrandedApplication->setExportedDate(1, true);
		$expected = $this->CobrandedApplication->find('first', ['conditions' => ['CobrandedApplication.id' => 1]]);
		$this->assertNotEmpty($expected['CobrandedApplication']['api_exported_date']);
		$this->assertEmpty($expected['CobrandedApplication']['csv_exported_date']);

		$this->CobrandedApplication->setExportedDate(1, false);
		$expected = $this->CobrandedApplication->find('first', ['conditions' => ['CobrandedApplication.id' => 1]]);
		$this->assertNotEmpty($expected['CobrandedApplication']['api_exported_date']);
		$this->assertNotEmpty($expected['CobrandedApplication']['csv_exported_date']);
	}

/**
 * testSetDataToSyncNewData()
 *
 * @covers CobrandedApplication::setDataToSync()
 * @return void
 */
	public function testSetDataToSyncNewData() {
		$newData = array(
			'user_id' => 1,
			'template_id' => 4,
			'uuid' => '59025600-cd20-40ae-820b-1e2934627ad4',
			'created' => '2014-01-24 09:07:08',
			'modified' => '2014-01-24 09:07:08',
			'status' => 'saved',
		);

		$this->CobrandedApplication->create();
		$this->CobrandedApplication->save($newData);
		//Expected should be in saveMany-like data structure
		$expected[] = array(
			'TemplateField' => array(
				'id' => 99,
				'name' => 'field type hr',
				'width' => 12,
				'description' => '',
				'type' => 8,
				'required' => 1,
				'source' => 1,
				'default_value' => '',
				'merge_field_name' => 'hr',
				'order' => 8,
				'section_id' => 4,
				'rep_only' => false,
				'encrypt' => false,
				'created' => '2013-12-18 14:10:17',
				'modified' => '2013-12-18 14:10:17'
			)
		);
		$this->assertTrue($this->CobrandedApplication->setDataToSync($expected[0]));

		$saved = $this->CobrandedApplication->find('first', array('recursive' => -1, 'conditions' => array('id' => $this->CobrandedApplication->id)));
		$this->assertSame(serialize($expected), $saved['CobrandedApplication']['data_to_sync']);
		$this->assertSame(unserialize($saved['CobrandedApplication']['data_to_sync']), $expected);
	}

/**
 * testSetDataToSyncUpdateExistingData()
 * Test that method updates existing data-to-be-synced and
 *
 * @covers CobrandedApplication::setDataToSync()
 * @return void
 */
	public function testSetDataToSyncUpdateExistingData() {
		//Expected should be in saveMany-like data structure
		$existing[] = array(
			'TemplateField' => array(
				'id' => 99,
				'name' => 'field type hr',
				'width' => 12,
				'description' => '',
				'type' => 8,
				'required' => 1,
				'source' => 1,
				'default_value' => '',
				'merge_field_name' => 'hr',
				'order' => 8,
				'section_id' => 4,
				'rep_only' => false,
				'encrypt' => false,
				'created' => '2013-12-18 14:10:17',
				'modified' => '2013-12-18 14:10:17'
			)
		);
		$newData = array(
			'user_id' => 1,
			'template_id' => 4,
			'uuid' => '59025600-cd20-40ae-820b-1e2934627ad4',
			'created' => '2014-01-24 09:07:08',
			'modified' => '2014-01-24 09:07:08',
			'status' => 'saved',
			'data_to_sync' => serialize($existing)
		);
		$this->CobrandedApplication->create();
		$this->CobrandedApplication->save($newData);

		$existing[0]['TemplateField']['width'] = 6;
		$existing[0]['TemplateField']['description'] = 'This field has been modified';
		$this->assertTrue($this->CobrandedApplication->setDataToSync($existing[0]));

		$saved = $this->CobrandedApplication->find('first', array('recursive' => -1, 'conditions' => array('id' => $this->CobrandedApplication->id)));
		$expected = $existing;

		//Assert that existing serialized data was found and updated
		$this->assertSame(serialize($expected), $saved['CobrandedApplication']['data_to_sync']);
		$this->assertSame(unserialize($saved['CobrandedApplication']['data_to_sync']), $expected);
		$this->assertContains('This field has been modified', $saved['CobrandedApplication']['data_to_sync']);
		$expectedCount = count($expected);
		$actualCount = count(unserialize($saved['CobrandedApplication']['data_to_sync']));
		$this->assertEquals($expectedCount, $actualCount);
	}

/**
 * testSetDataToSyncUpdateOneInManyExistingData()
 * Test that method updates only the specific field that was modified and leaves all other data-to-be-synced intact
 *
 * @covers CobrandedApplication::setDataToSync()
 * @return void
 */
	public function testSetDataToSyncUpdateOneInManyExistingData() {
		//Expected should be in saveMany-like data structure
		//This TemplateField data emulates TemplateFields that were modified at a point in time before a second modification, which takes place at a later point in time,
		//in which only the second TemplateField below is modified
		$existing = array(
			array(
				'TemplateField' => array(
					'id' => 98,
					'name' => 'field type hr',
					'width' => 12,
					'description' => 'field data-to-be-synced',
					'type' => 8,
					'required' => 1,
					'source' => 1,
					'default_value' => '',
					'merge_field_name' => 'hr',
					'order' => 8,
					'section_id' => 4,
					'rep_only' => false,
					'encrypt' => false,
					'created' => '2013-12-18 14:10:17',
					'modified' => '2013-12-18 14:10:17'
				)
			),
			array(
				'TemplateField' => array(
					'id' => 99,
					'name' => 'field type hr',
					'width' => 12,
					'description' => 'field data-to-be-synced',
					'type' => 8,
					'required' => 1,
					'source' => 1,
					'default_value' => '',
					'merge_field_name' => 'hr',
					'order' => 8,
					'section_id' => 4,
					'rep_only' => false,
					'encrypt' => false,
					'created' => '2014-12-18 14:10:17',
					'modified' => '2014-12-18 14:10:17'
				)
			),
		);
		$newData = array(
			'user_id' => 1,
			'template_id' => 4,
			'uuid' => '59025600-cd20-40ae-820b-1e2934627ad4',
			'created' => '2014-01-24 09:07:08',
			'modified' => '2014-01-24 09:07:08',
			'status' => 'saved',
			'data_to_sync' => serialize($existing)
		);
		$this->CobrandedApplication->create();
		$this->CobrandedApplication->save($newData);

		//*****This is the second mod to the moded field that was already awaiting synchronization
		$existing[1]['TemplateField']['width'] = 6;
		$existing[1]['TemplateField']['description'] = 'modify the modified field data-to-be-synced';

		//By passing only the field that was modified we are amulating a call that TemplateField::[beforeDelete/afterSave] makes tho this method
		$this->assertTrue($this->CobrandedApplication->setDataToSync($existing[1]));

		$saved = $this->CobrandedApplication->find('first', array('recursive' => -1, 'conditions' => array('id' => $this->CobrandedApplication->id)));
		$expected = $existing;

		//Assert that existing serialized data was found and updated and no new records were added
		$this->assertSame(serialize($expected), $saved['CobrandedApplication']['data_to_sync']);
		$this->assertSame(unserialize($saved['CobrandedApplication']['data_to_sync']), $expected);
		$this->assertContains('modify the modified field data-to-be-synced', $saved['CobrandedApplication']['data_to_sync']);
		$expectedCount = count($expected);
		$actualCount = count(unserialize($saved['CobrandedApplication']['data_to_sync']));
		$this->assertEquals($expectedCount, $actualCount);

		//Lastly test a new item to sync is added to the existing collection
		$newToSync = array(
			array(
				'TemplateField' => array(
					'id' => 97,
					'name' => 'field type hr',
					'width' => 12,
					'description' => 'New field data-to-be-synced',
					'type' => 8,
					'required' => 1,
					'source' => 1,
					'default_value' => '',
					'merge_field_name' => 'hr',
					'order' => 8,
					'section_id' => 4,
					'rep_only' => false,
					'encrypt' => false,
					'created' => '2013-12-18 14:10:17',
					'modified' => '2013-12-18 14:10:17'
				)
			),
		);
		$this->assertTrue($this->CobrandedApplication->setDataToSync($newToSync[0]));
		$expected = array_merge($existing, $newToSync);
		$saved = $this->CobrandedApplication->find('first', array('recursive' => -1, 'conditions' => array('id' => $this->CobrandedApplication->id)));

		$this->assertSame(serialize($expected), $saved['CobrandedApplication']['data_to_sync']);
		$this->assertSame(unserialize($saved['CobrandedApplication']['data_to_sync']), $expected);
		$this->assertContains('New field data-to-be-synced', $saved['CobrandedApplication']['data_to_sync']);
		$expectedCount = count($expected);
		$actualCount = count(unserialize($saved['CobrandedApplication']['data_to_sync']));
		$this->assertEquals($expectedCount, $actualCount);
	}

	public function testValidation() {
		// create a new appliction
		// only validation currently in place is for the uuid
		$expectedValidationErrors = array(
			'uuid' => array('Invalid UUID'),
			'user_id' => array('This field cannot be left blank'),
			'template_id' => array('This field cannot be left blank'),
		);
		$this->CobrandedApplication->create(array('uuid' => ''));
		$this->assertFalse($this->CobrandedApplication->validates());
		$this->assertEquals(
			$expectedValidationErrors,
			$this->CobrandedApplication->validationErrors,
			'testing expected validation errors for empty uuid'
		);

		// testing go right path
		$expectedValidationErrors = array();
		$this->CobrandedApplication->create(
			array(
				'uuid' => CakeText::uuid(),
				'user_id' => 1,
				'template_id' => 1,
			)
		);

		$this->assertTrue($this->CobrandedApplication->validates());
		$this->assertEquals(
			$expectedValidationErrors,
			$this->CobrandedApplication->validationErrors,
			'testing no validation errors for valid uuid'
		);
	}

	public function testGetTemplateAndAssociatedValues() {
		// expected was built via fixtures
		$expected = array(
			'CobrandedApplication' => array(
				'id' => (int)1,
				'user_id' => (int)1,
				'template_id' => (int)1,
				'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
				'created' => '2014-01-24 09:07:08',
				'modified' => $this->__cobrandedApplication['CobrandedApplication']['modified'],
				'rightsignature_document_guid' => null,
				'status' => null,
				'rightsignature_install_document_guid' => null,
				'rightsignature_install_status' => null,
				'data_to_sync' => null,
				'api_exported_date' => null,
				'csv_exported_date' => null,
				'external_foreign_id' => null,
				'sf_opportunity_id' => null,
				'application_group_id' => null,
				'client_id_global' => null,
				'client_name_global' => null,
				'doc_secret_token' => null,
			),
			'Template' => array(
				'id' => (int)1,
				'name' => 'Template 1 for PN1',
				'logo_position' => (int)0,
				'include_brand_logo' => true,
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'cobrand_id' => (int)1,
				'created' => '2007-03-18 10:41:31',
				'modified' => '2007-03-18 10:41:31',
				'rightsignature_template_guid' => null,
				'rightsignature_install_template_guid' => null,
				'owner_equity_threshold' => 50,
				'requires_coversheet' => false,
				'email_app_pdf' => true,
				'Cobrand' => array(
					'id' => (int)1,
					'partner_name' => 'Partner Name 1',
					'partner_name_short' => 'PN1',
					'cobrand_logo_url' => 'PN1 logo_url',
					'description' => 'Cobrand "Partner Name 1" description goes here.',
					'created' => '2007-03-18 10:41:31',
					'modified' => '2007-03-18 10:41:31',
					'response_url_type' => null,
					'brand_logo_url' => 'PN1 logo_url',
					'brand_name' => null
				),
				'TemplatePages' => array(
					(int)0 => array(
						'id' => (int)1,
						'name' => 'Page 1',
						'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
						'rep_only' => false,
						'template_id' => (int)1,
						'order' => (int)0,
						'created' => '2013-12-18 09:26:45',
						'modified' => '2013-12-18 09:26:45',
						'TemplateSections' => array(
							(int)0 => array(
								'id' => (int)1,
								'name' => 'Page Section 1',
								'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
								'rep_only' => false,
								'width' => (int)12,
								'page_id' => (int)1,
								'order' => (int)0,
								'created' => '2013-12-18 13:36:11',
								'modified' => '2013-12-18 13:36:11',
								'TemplateFields' => array(
									(int)0 => array(
										'id' => (int)1,
										'name' => 'field 1',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => (int)12,
										'type' => (int)1,
										'required' => true,
										'source' => (int)1,
										'default_value' => '',
										'merge_field_name' => 'required_text_from_user_without_default',
										'order' => (int)0,
										'section_id' => (int)1,
										'encrypt' => false,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array(
											(int)0 => array(
												'id' => (int)1,
												'cobranded_application_id' => (int)1,
												'template_field_id' => (int)1,
												'name' => 'Field 1',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15'
											)
										)
									),
									(int)1 => array(
										'id' => (int)2,
										'name' => 'field 2',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => (int)12,
										'type' => (int)0,
										'required' => true,
										'source' => (int)1,
										'default_value' => '',
										'merge_field_name' => 'required_text_from_user_without_default',
										'order' => (int)1,
										'section_id' => (int)1,
										'encrypt' => true,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array(
											(int)0 => array(
												'id' => (int)5,
												'cobranded_application_id' => (int)1,
												'template_field_id' => (int)2,
												'name' => 'Encrypt1',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15'
											)
										)
									),
									(int)2 => array(
										'id' => (int)3,
										'name' => 'field 3',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => (int)12,
										'type' => (int)0,
										'required' => true,
										'source' => (int)1,
										'default_value' => '',
										'merge_field_name' => 'required_text_from_user_without_default',
										'order' => (int)2,
										'section_id' => (int)1,
										'encrypt' => false,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array()
									),
									(int)3 => array(
										'id' => 4,
										'name' => 'field 4',
										'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
										'rep_only' => false,
										'width' => 12,
										'type' => 4,
										'required' => true,
										'source' => 1,
										'default_value' => 'name1::value1,name2::value2,name3::value3',
										'merge_field_name' => 'required_radio_from_user_without_default',
										'order' => 3,
										'section_id' => 1,
										'encrypt' => false,
										'created' => '2013-12-18 14:10:17',
										'modified' => '2013-12-18 14:10:17',
										'CobrandedApplicationValues' => array(
											array(
												'id' => 2,
												'cobranded_application_id' => 1,
												'template_field_id' => 4,
												'name' => 'name1',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15',
											),
											array(
												'id' => 3,
												'cobranded_application_id' => 1,
												'template_field_id' => 4,
												'name' => 'name2',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15',
											),
											array(
												'id' => 4,
												'cobranded_application_id' => 1,
												'template_field_id' => 4,
												'name' => 'name3',
												'value' => null,
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15',
											),
											array(
												'id' => 8,
												'cobranded_application_id' => 1,
												'template_field_id' => 4,
												'name' => 'Owner1Name',
												'value' => 'Owner1NameTest',
												'created' => '2014-01-23 17:28:15',
												'modified' => '2014-01-23 17:28:15'
											),
										),
									),
								)
							),
							(int)1 => array(
								'id' => (int)2,
								'name' => 'Page Section 2',
								'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
								'rep_only' => false,
								'width' => (int)12,
								'page_id' => (int)1,
								'order' => (int)1,
								'created' => '2013-12-18 13:36:11',
								'modified' => '2013-12-18 13:36:11',
								'TemplateFields' => array()
							),
							(int)2 => array(
								'id' => (int)3,
								'name' => 'Page Section 2',
								'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
								'rep_only' => false,
								'width' => (int)12,
								'page_id' => (int)1,
								'order' => (int)2,
								'created' => '2013-12-18 13:36:11',
								'modified' => '2013-12-18 13:36:11',
								'TemplateFields' => array()
							)
						)
					),
					(int)1 => array(
						'id' => (int)2,
						'name' => 'Page 2',
						'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
						'rep_only' => false,
						'template_id' => (int)1,
						'order' => (int)1,
						'created' => '2013-12-18 09:26:45',
						'modified' => '2013-12-18 09:26:45',
						'TemplateSections' => array()
					),
					(int)2 => array(
						'id' => (int)3,
						'name' => 'Page 3',
						'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
						'rep_only' => false,
						'template_id' => (int)1,
						'order' => (int)2,
						'created' => '2013-12-18 09:26:45',
						'modified' => '2013-12-18 09:26:45',
						'TemplateSections' => array()
					)
				),
			)
		);

		// We need an application
		$actual = $this->CobrandedApplication->getTemplateAndAssociatedValues(1);
		$expected['CobrandedApplication']['modified'] = $actual['CobrandedApplication']['modified'];
		$this->assertEquals($expected, $actual, 'getTemplateAndAssociatedValues test failed');

		// we should get rep_only pages, sections, and fields, if we're logged in
		$expected['Template']['TemplatePages'][3] = array(
			'id' => (int)6,
			'name' => 'Validate Application',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'rep_only' => true,
			'template_id' => (int)1,
			'order' => (int)3,
			'created' => '2013-12-18 09:26:45',
			'modified' => '2013-12-18 09:26:45',
			'TemplateSections' => array(
				(int)0 => array(
					'id' => (int)6,
					'name' => 'Page Section 1',
					'description' => '',
					'rep_only' => true,
					'width' => (int)12,
					'page_id' => (int)6,
					'order' => (int)0,
					'created' => '2013-12-18 13:36:11',
					'modified' => '2013-12-18 13:36:11',
					'TemplateFields' => array(
						(int)0 => array(
							'id' => (int)45,
							'name' => 'Text field 1',
							'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
							'rep_only' => true,
							'width' => (int)12,
							'type' => (int)0,
							'required' => true,
							'source' => (int)1,
							'default_value' => '',
							'merge_field_name' => 'rep_only_true_field_for_testing_rep_only_view_logic',
							'order' => (int)0,
							'section_id' => (int)6,
							'encrypt' => false,
							'created' => '2013-12-18 14:10:17',
							'modified' => '2013-12-18 14:10:17',
							'CobrandedApplicationValues' => array()
						)
					)
				)
			)
		);

		// We need an application... also pass in user id - mimicks logged in user
		$actual = $this->CobrandedApplication->getTemplateAndAssociatedValues(1, 1);
		$this->assertEquals($expected, $actual, 'getTemplateAndAssociatedValues test failed when using logged in user');
	}

   /**
 	* testSaveApplicationValue method
 	*
 	* @covers CobrandedApplication::saveApplicationValue();
 	* @return void
 	*/
	public function testSaveApplicationValue() {
		// TODO: handle application value not found

		// CUT (class under test) expects
		$data = array(
			'id' => 1,
			'value' => null
		);

		// pre-check - verify the value exists and is what we expect
		$expected = array(
			'CobrandedApplicationValue' => array(
				'id' => 1,
				'cobranded_application_id' => 1,
				'template_field_id' => 1,
				'name' => 'Field 1',
				'value' => null,
				'created' => '2014-01-23 17:28:15',
				'modified' => '2014-01-23 17:28:15',
			),
			'CobrandedApplication' => array(
				'id' => 1,
				'user_id' => 1,
				'template_id' => 1,
				'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
				'created' => '2014-01-24 09:07:08',
				'modified' => $this->__cobrandedApplication['CobrandedApplication']['modified'],
				'rightsignature_document_guid' => null,
				'status' => null,
				'rightsignature_install_document_guid' => null,
				'rightsignature_install_status' => null,
				'data_to_sync' => null,
				'api_exported_date' => null,
				'csv_exported_date' => null,
				'external_foreign_id' => null,
				'sf_opportunity_id' => null,
				'application_group_id' => null,
				'client_id_global' => null,
				'client_name_global' => null,
				'doc_secret_token' => null,
			),
			'TemplateField' => array(
				'id' => 1,
				'name' => 'field 1',
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'rep_only' => false,
				'width' => 12,
				'type' => 1,
				'required' => true,
				'source' => 1,
				'default_value' => '',
				'merge_field_name' => 'required_text_from_user_without_default',
				'order' => 0,
				'section_id' => 1,
				'encrypt' => false,
				'created' => '2013-12-18 14:10:17',
				'modified' => '2013-12-18 14:10:17',
			),
		);
		$actual = $this->CobrandedApplication->getApplicationValue(1);
		$expected['CobrandedApplication']['modified'] = $actual['CobrandedApplication']['modified'];
		$this->assertEquals($expected, $actual, 'expected to find application value with id of 1...');

		// change the value
		$expectedNewValue = '2014-01-01';
		$inputData = array('id' => 1, 'value' => '2014-01-01');
		$response = $this->CobrandedApplication->saveApplicationValue($inputData);

		// tests for the other types are performed by testBuildExportData
		// we should also test the other invalid types...
		$this->assertTrue($response['success'], 'Expected save operation to return true');
		$actual = $this->CobrandedApplication->getApplicationValue(1);
		$this->assertEquals($expectedNewValue, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');

		// save again witht the same data
		$response = $this->CobrandedApplication->saveApplicationValue($inputData);
		$this->assertFalse($response['success'], 'Expected save operation to return true');

		// also make sure the actual value is still newValue
		$actual = $this->CobrandedApplication->getApplicationValue(1);
		$this->assertEquals($expectedNewValue, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');

		// next save a app value with template type of 4 (radio)
		$expected = array(
			'CobrandedApplicationValue' => array(
				'id' => 2,
				'cobranded_application_id' => 1,
				'template_field_id' => 4,
				'name' => 'name1',
				'value' => null,
				'created' => '2014-01-23 17:28:15',
				'modified' => '2014-01-23 17:28:15',
			),
			'CobrandedApplication' => array(
				'id' => 1,
				'user_id' => 1,
				'template_id' => 1,
				'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
				'created' => '2014-01-24 09:07:08',
				'modified' => $this->__cobrandedApplication['CobrandedApplication']['modified'],
				'rightsignature_document_guid' => null,
				'status' => null,
				'rightsignature_install_document_guid' => null,
				'rightsignature_install_status' => null,
				'data_to_sync' => null,
				'api_exported_date' => null,
				'csv_exported_date' => null,
				'external_foreign_id' => null,
				'sf_opportunity_id' => null,
				'application_group_id' => null,
				'client_id_global' => null,
				'client_name_global' => null,
				'doc_secret_token' => null,
			),
			'TemplateField' => array(
				'id' => 4,
				'name' => 'field 4',
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'rep_only' => false,
				'width' => 12,
				'type' => 4,
				'required' => true,
				'source' => 1,
				'default_value' => 'name1::value1,name2::value2,name3::value3',
				'merge_field_name' => 'required_radio_from_user_without_default',
				'order' => 3,
				'section_id' => 1,
				'encrypt' => false,
				'created' => '2013-12-18 14:10:17',
				'modified' => '2013-12-18 14:10:17',
			),
		);
		$actual = $this->CobrandedApplication->getApplicationValue(2);
		$expected['CobrandedApplication']['modified'] = $actual['CobrandedApplication']['modified'];
		$this->assertEquals($expected, $actual, 'expected to find application value with id of 4...');

		$expectedNewValue = true;
		$inputData = array('id' => 2, 'value' => true);
		$response = $this->CobrandedApplication->saveApplicationValue($inputData);

		$this->assertTrue($response['success'], 'Expected save operation to return true');
		$actual = $this->CobrandedApplication->getApplicationValue(2);
		$this->assertEquals($expectedNewValue, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');

		// now update id 3
		$inputData = array('id' => 3, 'value' => true);
		$response = $this->CobrandedApplication->saveApplicationValue($inputData);

		$this->assertTrue($response['success'], 'Expected save operation to return true');
		$actual = $this->CobrandedApplication->getApplicationValue(3);
		$this->assertEquals($expectedNewValue, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');

		// and id 2 should have a value of  null
		$actual = $this->CobrandedApplication->getApplicationValue(2);
		$this->assertEquals(false, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');
		// and id 4 should have a value of  null
		$actual = $this->CobrandedApplication->getApplicationValue(4);
		$this->assertEquals(false, $actual['CobrandedApplicationValue']['value'], 'Expected updated [value] property.');
	}

   /**
 	* testBuildExportData method
 	*
 	* @covers CobrandedApplication::buildExportData();
 	* @return void
 	*/
	public function testBuildExportData() {
		// create a new application from template with id 4
		// or find the template with a name = 'Template used to test afterSave of app values'
		$applictionData = array(
			'user_id' => $this->__user['User']['id'],
			'template_id' => $this->__template['Template']['id'],
			'uuid' => CakeText::uuid(),
		);

		$this->CobrandedApplication->create($applictionData);
		$cobrandedApplication = $this->CobrandedApplication->save();

		$coversheet = array(
			'user_id' => $this->__user['User']['id'],
			'status' => 'saved',
			'created' => '2016-09-16 14:56:40',
			'modified' => '2016-09-16 14:56:40',
			'cobranded_application_id' => $this->CobrandedApplication->id
		);

		$this->Coversheet->create($coversheet);
		$newCoversheet = $this->Coversheet->save();

		// export an empty application
		$expectedKeys =
			'"MID",' .
			'"CompanyBrandName",' .
			'"required_text_from_user_without_default",' .
			'"required_date_from_user_without_default",' .
			'"required_time_from_user_without_default",' .
			'"required_checkbox_from_user_without_default",' .
			'"required_radio_from_user_without_defaultvalue1",' .
			'"required_radio_from_user_without_defaultvalue2",' .
			'"required_radio_from_user_without_defaultvalue3",' .
			'"required_percents_from_user_without_defaultvalue1",' .
			'"required_percents_from_user_without_defaultvalue2",' .
			'"required_percents_from_user_without_defaultvalue3",' .
			'"required_fees_from_user_without_defaultvalue1",' .
			'"required_fees_from_user_without_defaultvalue2",' .
			'"required_fees_from_user_without_defaultvalue3",' .
			'"required_phoneUS_from_user_without_default",' .
			'"required_money_from_user_without_default",' .
			'"required_percent_from_user_without_default",' .
			'"required_ssn_from_user_without_default",' .
			'"required_zipcodeUS_from_user_without_default",' .
			'"required_email_from_user_without_default",' .
			'"required_url_from_user_without_default",' .
			'"required_number_from_user_without_default",' .
			'"required_digits_from_user_without_default",' .
			'"required_select_from_user_without_default",' .
			'"required_textArea_from_user_without_default",' .
			'"Referral1",' .
			'"Referral2",' .
			'"Referral3",' .
			'"OwnerType-Corp",' .
			'"OwnerType-SoleProp",' .
			'"OwnerType-LLC",' .
			'"OwnerType-Partnership",' .
			'"OwnerType-NonProfit",' .
			'"OwnerType-Other",' .
			'"Unknown Type for testing",' .
			'"oaID",' .
			'"api",' .
			'"aggregated",' .
			'"onlineapp_application_id",' .
			'"user_id",' .
			'"status",' .
			'"setup_existing_merchant",' .
			'"setup_banking",' .
			'"setup_statements",' .
			'"setup_drivers_license",' .
			'"setup_new_merchant",' .
			'"setup_business_license",' .
			'"setup_other",' .
			'"setup_field_other",' .
			'"setup_tier_select",' .
			'"setup_tier3",' .
			'"setup_tier4",' .
			'"setup_tier5_financials",' .
			'"setup_tier5_processing_statements",' .
			'"setup_tier5_bank_statements",' .
			'"setup_equipment_terminal",' .
			'"setup_equipment_gateway",' .
			'"setup_install",' .
			'"setup_starterkit",' .
			'"setup_equipment_payment",' .
			'"setup_lease_price",' .
			'"setup_lease_months",' .
			'"setup_debit_volume",' .
			'"setup_item_count",' .
			'"setup_referrer",' .
			'"setup_referrer_type",' .
			'"setup_referrer_pct",' .
			'"setup_reseller",' .
			'"setup_reseller_type",' .
			'"setup_reseller_pct",' .
			'"setup_notes",' .
			'"cp_encrypted_sn",' .
			'"cp_pinpad_ra_attached",' .
			'"cp_giftcards",' .
			'"cp_check_guarantee",' .
			'"cp_check_guarantee_info",' .
			'"cp_pos",' .
			'"cp_pos_contact",' .
			'"micros",' .
			'"micros_billing",' .
			'"gateway_option",' .
			'"gateway_package",' .
			'"gateway_gold_subpackage",' .
			'"gateway_epay",' .
			'"gateway_billing",' .
			'"moto_online_chd",' .
			'"moto_developer",' .
			'"moto_company",' .
			'"moto_gateway",' .
			'"moto_contact",' .
			'"moto_phone",' .
			'"moto_email",' .
			'"created",' .
			'"modified",' .
			'"setup_referrer_pct_profit",' .
			'"setup_referrer_pct_volume",' .
			'"setup_referrer_pct_gross",' .
			'"setup_reseller_pct_profit",' .
			'"setup_reseller_pct_volume",' .
			'"setup_reseller_pct_gross",' .
			'"setup_partner",' .
			'"setup_partner_pct_profit",' .
			'"setup_partner_pct_volume",' .
			'"setup_partner_pct_gross",' .
			'"gateway_retail_swipe",' .
			'"gateway_epay_charge_licenses"';

		$expectedValues =
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"Off",' .
			'"Off",' .
			'"Off",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"Referral3",' .
			'"Off",' .
			'"Off",' .
			'"Off",' .
			'"Off",' .
			'"Off",' .
			'"Off",' .
			'"",' .
			'"5",' .
			'"",' .
			'"",' .
			'"",' .
			'"1",' .
			'"saved",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"2016-09-16 14:56:40",' .
			'"2016-09-16 14:56:40",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'""';

		$actualKeys = '';
		$actualValues = '';
		$this->CobrandedApplication->buildExportData($this->CobrandedApplication->id, $actualKeys, $actualValues);

		$this->assertEquals($expectedKeys, $actualKeys, 'Empty application keys were not what we expected');
		$this->assertEquals($expectedValues, $actualValues, 'Empty application values were not what we expected');

		// now insert values into the fields
		$app = $this->CobrandedApplication->read();

		$this->__setSomeValuesBasedOnType($app);

		// update the coversheet with some values
		$coversheetData = array(
			'Coversheet' => array(
				'setup_existing_merchant' => '',
				'setup_banking' => true,
				'setup_statements' => true,
				'setup_drivers_license' => true,
				'setup_new_merchant' => true,
				'setup_business_license' => true,
				'setup_other' => true,
				'setup_field_other' => '',
				'setup_tier_select' => true,
				'setup_tier3' => false,
				'setup_tier4' => false,
				'setup_tier5_financials' => false,
				'setup_tier5_processing_statements' => false,
				'setup_tier5_bank_statements' => false,
				'setup_equipment_terminal' => false,
				'setup_equipment_gateway' => true,
				'setup_install' => 'pos',
				'setup_starterkit' => '',
				'setup_equipment_payment' => '',
				'setup_lease_price' => '',
				'setup_lease_months' => '',
				'setup_debit_volume' => '',
				'setup_item_count' => '',
				'setup_referrer' => 'Xsilva',
				'setup_referrer_type' => 'gp',
				'setup_referrer_pct' => '20',
				'setup_reseller' => 'BFA Technologies',
				'setup_reseller_type' => 'gp',
				'setup_reseller_pct' => '30',
				'setup_notes' => '',
				'cp_encrypted_sn' => '',
				'cp_pinpad_ra_attached' => false,
				'cp_giftcards' => 'no',
				'cp_check_guarantee' => 'no',
				'cp_check_guarantee_info' => '',
				'cp_pos' => 'yes',
				'cp_pos_contact' => 'LightSpeed',
				'micros' => '',
				'micros_billing' => '',
				'gateway_option' => 'option2',
				'gateway_package' => 'silver',
				'gateway_gold_subpackage' => '',
				'gateway_epay' => true,
				'gateway_billing' => 'rep',
				'moto_online_chd' => '',
				'moto_developer' => '',
				'moto_company' => '',
				'moto_gateway' => '',
				'moto_contact' => '',
				'moto_phone' => '',
				'moto_email' => '',
				'modified' => '2016-09-16 14:56:40'
			)
		);

		$this->Coversheet->id = $newCoversheet['Coversheet']['id'];
		$this->Coversheet->save($coversheetData, false);

		$expectedValues =
			'"",' .
			'"",' .
			'"text",' .
			'"2000-01-01",' .
			'"08:00 pm",' .
			'"true",' .
			'"On",' .
			'"On",' .
			'"On",' .
			'"10",' .
			'"10",' .
			'"10",' .
			'"10.00",' .
			'"10.00",' .
			'"10.00",' .
			'"8005551234",' .
			'"10",' .
			'"50",' .
			'"123-45-6789",' .
			'"12345-1234",' .
			'"name@domain.com",' .
			'"http://www.domain.com",' .
			'"12.82234",' .
			'"1234567890",' .
			'"",' .
			'"a whole lot of text can go into this field...",' .
			'"text text text",' .
			'"text text text",' .
			'"text text text",' .
			'"Yes",' .
			'"Yes",' .
			'"Yes",' .
			'"Yes",' .
			'"Yes",' .
			'"Yes",' .
			'"",' .
			'"5",' .
			'"",' .
			'"",' .
			'"",' .
			'"1",' .
			'"saved",' .
			'"",' .
			'"1",' .
			'"1",' .
			'"1",' .
			'"1",' .
			'"1",' .
			'"1",' .
			'"",' .
			'"1",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"1",' .
			'"pos",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"Xsilva",' .
			'"gp",' .
			'"20",' .
			'"BFA Technologies",' .
			'"gp",' .
			'"30",' .
			'"",' .
			'"",' .
			'"",' .
			'"no",' .
			'"no",' .
			'"",' .
			'"yes",' .
			'"LightSpeed",' .
			'"",' .
			'"",' .
			'"option2",' .
			'"silver",' .
			'"",' .
			'"1",' .
			'"rep",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"2016-09-16 14:56:40",' .
			'"2016-09-16 14:56:40",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'"",' .
			'""';

		$actualKeys = '';
		$actualValues = '';
		$this->CobrandedApplication->buildExportData($this->CobrandedApplication->id, $actualKeys, $actualValues);

		// the keys should not have changed
		$this->assertEquals($expectedKeys, $actualKeys, 'Filled out application keys were not what we expected');
		$this->assertEquals($expectedValues, $actualValues, 'Filled out application values were not what we expected');

		$actualKeys = '';
		$actualValues = '';
		$this->CobrandedApplication->buildExportData(1, $actualKeys, $actualValues);

		// assertion
		$this->assertNotEmpty($actualValues, 'Expected values to not be empty');
	}

	/**
 	* testSaveFields method
 	*
 	* @covers CobrandedApplication::saveFields();
 	* @return void
 	*/
	public function testSaveFields() {
		// knowns:
		//     - user
		//     - fieldsData
		// pre-test:
		//     - should be one cobrandedApplication for this user
		$applications = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array('CobrandedApplication.user_id' => $this->__user['User']['id']),
			)
		);
		$this->assertEquals(1, count($applications), 'Expected to find 1 application for user with id [' . $this->__user['User']['id'] . ']');
		// set knowns
		$user = $this->__user['User'];
		// update the template_id to be 5
		$user['template_id'] = 5;

		$fieldsData = [];

		// test passinng no template_id
		$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);
		$this->assertEquals($actualResponse['status'], AppModel::API_FAILS);
		$this->assertEquals($actualResponse['messages'], "A Template with id = '' does not exist.");
		// test passinng invalid template_id
		$fieldsData = ['template_id' => 99999];
		$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);
		$this->assertEquals($actualResponse['status'], AppModel::API_FAILS);
		$this->assertEquals($actualResponse['messages'], "A Template with id = '99999' does not exist.");

		// pass valid template, emulate machine to machine API requiest with missing minimally requied values
		$fieldsData = array(
			'template_id' => 5,
			'm2m' => true,
		);
		$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);
		$this->assertEquals($actualResponse['status'], AppModel::API_FAILS);
		$this->assertContains("DBA is required.", $actualResponse['messages']);
		$this->assertContains("EMail is required.", $actualResponse['messages']);

		// pass valid template, emulate machine to machine API requiest with missing minimally requied values
		$fieldsData['DBA'] = "";
		$fieldsData['EMail'] = "";
		$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);

		$this->assertEquals($actualResponse['status'], AppModel::API_FAILS);
		$this->assertContains("DBA is required.", $actualResponse['messages']);
		$this->assertContains("EMail is required.", $actualResponse['messages']);

		// set expected results
		$expectedValidationErrors = array(
			'required_text_from_api_without_default' => 'required',
			'required_text_from_api_without_default_source_2' => 'required',
			'required_text_from_user_without_default_repOnly' => 'required',
			'required_text_from_user_without_default_textfield' => 'required',
			'required_text_from_user_without_default_textfield1' => 'required',
			'DBA' => 'required',
			'EMail' => 'required'
		);

		

		// first pass, use invalid fieldsData
		$fieldsData = array(
			'template_id' => 5,
			'required_text_from_api_without_default' => '' // this guy is required
		);

		// execute the method under test
		$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);
		// assertions
		$this->assertEquals($actualResponse['status'], AppModel::API_FAILS);
		$this->assertEquals($expectedValidationErrors, $actualResponse['validationErrors'], 'Expected validation errors did not match');
		$applications = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array('CobrandedApplication.user_id' => $this->__user['User']['id']),
			)
		);

		$this->assertEquals(1, count($applications), 'Expected to find 1 application for user with id [' . $this->__user['User']['id'] . ']');

		// this time use good data
		$fieldsData['DBA'] = 'any text will do';
		$fieldsData['EMail'] = 'any@text.com';
		$fieldsData['required_text_from_api_without_default'] = 'any text will do';
		$fieldsData['required_text_from_api_without_default_source_2'] = 'any text will do';
		$fieldsData['required_text_from_user_without_default_repOnly'] = 'any text will do';
		$fieldsData['required_text_from_user_without_default_textfield'] = 'any text will do';
		$fieldsData['required_text_from_user_without_default_textfield1'] = 'any text will do';

		$fieldsData['multirecord_from_api_with_default'] = array(
			array(
				'description' => 'Lorem ipsum dolor sit amet',
				'auth_type' => 'Lorem ipsum dolor sit amet',
				'routing_number' => '321174851',
				'account_number' => '9900000003',
				'bank_name' => 'Lorem ipsum dolor sit amet'
			),
		);

		// execute the method under test
		$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);

		$application = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array('CobrandedApplication.uuid' => $actualResponse['application_id']),
			)
		);

		// assertions
		//saveFields with valid data should succeed
		$this->assertEquals($actualResponse['status'], AppModel::API_SUCCESS);
		$this->assertEquals(array(), $actualResponse['validationErrors'], 'Expected no validation errors for valid $fieldsData');

		// test with bad routing number
		$fieldsData['multirecord_from_api_with_default'] = array(
			array(
				'description' => 'Lorem ipsum dolor sit amet',
				'auth_type' => 'Lorem ipsum dolor sit amet',
				'routing_number' => '3211',
				'account_number' => '9900000003',
				'bank_name' => 'Lorem ipsum dolor sit amet'
			),
		);

		// set expected results
		$expectedValidationErrors = array(
			array(
				'invalid record' => array(
					'description' => 'Lorem ipsum dolor sit amet',
					'auth_type' => 'Lorem ipsum dolor sit amet',
					'routing_number' => '3211',
					'account_number' => '9900000003',
					'bank_name' => 'Lorem ipsum dolor sit amet',
				),
				'routing_number' => array(
					'routing number is invalid'
				)
			)
		);

		// execute the method under test
		$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);

		// assertions
		//saveFields with invalid value for required field should fail
		$this->assertEquals($actualResponse['status'], AppModel::API_FAILS);
		$this->assertEquals($expectedValidationErrors, $actualResponse['validationErrors'], 'Expected validation errors did not match');

		unset($fieldsData);
		unset($expectedValidationErrors);

		$applications = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array('CobrandedApplication.user_id' => $this->__user['User']['id']),
			)
		);

		$this->assertEquals(2, count($applications), 'Expect to find two applications for user with id [' . $this->__user['User']['id'] . ']');

		$templateData = $this->CobrandedApplication->getTemplateAndAssociatedValues($applications[0]['CobrandedApplication']['id']);
		$templateField = $templateData['Template']['TemplatePages'][0]['TemplateSections'][0]['TemplateFields'][0];

		for ($index = 1; $index < 23; $index++) {
			// 15 and 16 are not implemented yet
			if ($index == 15 || $index == 16) {
				// ignore for now
			} else {
				if ($index == 0 ||
					$index == 3 ||
					$index == 4 ||
					$index == 5 ||
					$index == 6 ||
					$index == 7 ||
					$index == 8 ||
					$index == 20 ||
					$index == 21 ||
					$index == 22) {
					// no validation
				} else {
					// set the $expectedValidationErrors
					$expectedValidationErrors['required_text_from_api_without_default'] = 'date';
					$expectedValidationErrors['Text field'] = 'required';
					$expectedValidationErrors['Text field 1'] = 'required';

					// update templateField's type
					$templateField['type'] = $index;
					$this->TemplateField->save($templateField);

					// set the fieldsData
					$fieldsData['template_id'] = 5;
					$fieldsData['required_text_from_api_without_default'] = $this->__invalidApiTestValue[$index];

					// execute the method under test
					$actualResponse = $this->CobrandedApplication->saveFields($user, $fieldsData);
					$this->assertEquals($actualResponse['status'], AppModel::API_FAILS, 'saveFields with empty value for required field should fail. $index ['.$index.']');
// !! NEED TO REVISIT THE FOLLOWING TEST
					//$this->assertEquals($expectedValidationErrors, $actualResponse['validationErrors'], 'Expected validation errors did not match ['.$index.']');
				}
			}
		}
	}

	private $__invalidApiTestValue = array(
		'text type is not validated',										//  0 - free form
		'not a date',														//  1 - yyyy/mm/dd
		'not a time',														//  2 - hh:mm:ss
		'radio type is not validated',										//  3 -
		'label type is not validated',										//  4 -
		'100000',															//  5 - (group of percent)
		'label type is not validated',										//  6 - no validation
		'fees type is not validated',										//  7 - (group of money?)
		'hr type is not validated',											//  8 - no validation
		'phoneUS',															//  9 - (###) ###-####
		'money same as fees',												// 10 - $(#(1-3),)?(#(1-3)).## << needs work
		'percent should be > 0 < 100',										// 11 - (0-100)%
		'ssn value shoudl be ###-##-####',									// 12 - ###-##-####
		'zipcodeUS could include zip and the optional plus four value',		// 13 - #####[-####]
		'email value shoudld be name@domainname.com',						// 14 -
		'lengthoftime is not used yet',										// 15 - [#+] [year|month|day]s
		'creditcard is not used yet',										// 16 -
		'url value should look like http://domain.com',						// 17 -
		'number with a decimal',											// 18 - (#)+.(#)+
		'digits only, nothing else',										// 19 - (#)+
		'select... must be one of the default values',						// 20 - *** need to implement this ***
		'',																	// 21 - free form textarea
	);

	public function testCreateOnlineappForUser() {
		$expectedResponse = array(
			'success' => true,
			'cobrandedApplication' => array(
				'id' => 1, // testing for not null is the best we can do
				'uuid' => 'some uuid', // no way of knowing this value...
			),
		);

		$actualResponse = $this->CobrandedApplication->createOnlineappForUser($this->__user['User']);
		$this->assertTrue($actualResponse['success'], 'createOnlineappForUser did not create an application');
		$this->assertNotNull($actualResponse['cobrandedApplication']['id'], 'createOnlineappForUser should return a cobranded application id that is not null');

		// next pass a uuid and guess at the id (id++)
		$expectedResponse['cobrandedApplication']['id'] = $actualResponse['cobrandedApplication']['id'] + 1;
		$uuid = CakeText::uuid();
		$expectedResponse['cobrandedApplication']['uuid'] = $uuid;
		$actualResponse = $this->CobrandedApplication->createOnlineappForUser($this->__user['User'], ['uuid' => $uuid]);
		$this->assertEquals($expectedResponse, $actualResponse, 'createOnlineappForUser response did not match the expected');
	}

	public function testCopyApplication() {
		// knowns:
		//     applicationId of the app we want to copy
		// pre-test:
		//     - should have no cobrandedApplications for this template
		$apps = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array(
					'CobrandedApplication.template_id' => $this->__user['User']['template_id']
				),
			)
		);
		$this->assertEquals(0, count($apps), 'Expected to find no apps for user with id [' . $this->__user['User']['id'] . ']');

		// create an app
		$this->CobrandedApplication->create(
			array(
				'uuid' => CakeText::uuid(),
				'user_id' => $this->__user['User']['id'],
				'template_id' => $this->__user['User']['template_id'],
			)
		);
		$this->CobrandedApplication->save();
		$apps = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array(
					'CobrandedApplication.template_id' => $this->__user['User']['template_id']
				//	'CobrandedApplication.user_id' => $this->__user['User']['id']
				),
			)
		);

		// should now have 1 app
		$this->assertEquals(1, count($apps), 'Expected to find one app for user with id [' . $this->__user['User']['id'] . ']');

		// update the values
		$expectedApp = $apps[0];
		$this->__setSomeValuesBasedOnType($expectedApp);

		// next, copy this app
		$this->CobrandedApplication->copyApplication($expectedApp['CobrandedApplication']['id'], $this->__user['User']['id']);

		$apps = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array(
					'CobrandedApplication.template_id' => $this->__user['User']['template_id']
				//	'CobrandedApplication.user_id' => $this->__user['User']['id']
				),
			)
		);

		// should now have 2 apps
		$this->assertEquals(2, count($apps), 'Expected to find two apps for user with id [' . $this->__user['User']['id'] . ']');

		// and they should have the same user_id and template_id
		$this->assertEquals(
			$expectedApp['CobrandedApplication']['user_id'],
			$apps[0]['CobrandedApplication']['user_id'],
			'Copied application should have the same userId'
		);
		$this->assertEquals(
			$expectedApp['CobrandedApplication']['template_id'],
			$apps[1]['CobrandedApplication']['template_id'],
			'Copied application should have the same templateId'
		);

		// and CobrandedApplicationValues
		foreach ($expectedApp['CobrandedApplicationValues'] as $key => $value) {
			$expected = $apps[0]['CobrandedApplicationValues'][$key]['value'];
			$actual = $apps[1]['CobrandedApplicationValues'][$key]['value'];
			$this->assertEquals($expected, $actual,
				"Copied applications value [$expected] did not match original [$actual]"
			);
		}

		// make sure copy works when passing template_id
		$this->CobrandedApplication->copyApplication(
			$expectedApp['CobrandedApplication']['id'],
			$this->__user['User']['id'],
			$this->__user['User']['template_id']
		);

		$apps = $this->CobrandedApplication->find(
			'all',
			array(
				'conditions' => array(
					'CobrandedApplication.template_id' => $this->__user['User']['template_id']
				),
			)
		);

		// should now have 3 apps
		$this->assertEquals(3, count($apps), 'Expected to find three apps for user with id [' . $this->__user['User']['id'] . ']');

		$response = $this->CobrandedApplication->copyApplication(null, null, 999999);
		$this->assertFalse($response, 'copyApplication with bad template_id should fail.');
	}

	public function testSendFieldCompletionEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;
		$newAppGroup = $this->CobrandedApplication->ApplicationGroup->createNewGroup();
		$this->CobrandedApplication->save(['id' => 1, 'application_group_id' => $newAppGroup['ApplicationGroup']['id']], ['validate' => false]);
		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => '',
			'dba' => 'Doing Business As',
			'email' => 'testing@axiapayments.com',
			'fullname' => 'Corporate Contact'
		);

		$emailAddress = 'testing@axiapayments.com';
		$response = $this->CobrandedApplication->sendFieldCompletionEmail($emailAddress);

		// assertions
		$this->assertTrue($response['success'], 'sendFieldCompletionEmail with good email address should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'Could not find any applications with the specified email address.'
		);

		$emailAddress = 'nogood@assertfail.com';
		$response = $this->CobrandedApplication->sendFieldCompletionEmail($emailAddress);

		// assertions
		$this->assertFalse($response['success'], 'sendFieldCompletionEmail with bad email address should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => '',
			'dba' => 'Doing Business As',
			'email' => 'testing@axiapayments.com',
			'fullname' => 'Corporate Contact'
		);

		$emailAddress = 'testing@axiapayments.com';
		$response = $this->CobrandedApplication->sendFieldCompletionEmail($emailAddress, 1);

		// assertions
		$this->assertTrue($response['success'], 'sendFieldCompletionEmail with good email address should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => '',
			'dba' => 'Doing Business As',
			'email' => 'nogood@assertfail.com',
			'fullname' => 'Corporate Contact'
		);

		$emailAddress = 'nogood@assertfail.com';
		$response = $this->CobrandedApplication->sendFieldCompletionEmail($emailAddress, 1);

		// assertions
		$this->assertTrue($response['success'], 'sendFieldCompletionEmail with good id should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testSendNewApiApplicationEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		$args = array();
		$response = $this->CobrandedApplication->sendNewApiApplicationEmail($args);

		// assertions
		$this->assertTrue($response['success'], 'sendNewApiApplicationEmail using default values should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		$args = array(
			'from' => 'test@axiapayments.com',
			'cobrand' => 'test',
			'link' => 'test',
			'attachments' => array(
				'test' => array(
					'data' => 'test'
				)
			)
		);
		$response = $this->CobrandedApplication->sendNewApiApplicationEmail($args);

		// assertions
		$this->assertTrue($response['success'], 'sendNewApiApplicationEmail using default values should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'invalid email address submitted.'
		);

		$args = array('to' => '');
		$response = $this->CobrandedApplication->sendNewApiApplicationEmail($args);

		// assertions
		$this->assertFalse($response['success'], 'sendNewApiApplicationEmail with invalid email address should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testSendEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'from argument is missing.'
		);

		$args = array('to' => 'test@axiapayments.com');
		$response = $this->CobrandedApplication->sendEmail($args);

		// assertions
		$this->assertFalse($response['success'], 'sendEmail with missing from argument should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'to argument is missing.'
		);

		$args = array('from' => 'test@axiapayments.com');
		$response = $this->CobrandedApplication->sendEmail($args);

		// assertions
		$this->assertFalse($response['success'], 'sendEmail with missing to argument should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		$args = array(
			'from' => 'test@axiapayments.com',
			'to' => 'test@axiapayments.com',
			'attachments' => array(
				'test' => array(
					'data' => 'test'
				)
			)
		);
		$response = $this->CobrandedApplication->sendEmail($args);

		// assertions
		$this->assertTrue($response['success'], 'sendEmail using default values should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$this->CobrandedApplication->CakeEmail = null;

		// set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		$args = array(
			'from' => 'test@axiapayments.com',
			'to' => 'test@axiapayments.com'
		);
		$response = $this->CobrandedApplication->sendEmail($args);

		// assertions
		$this->assertTrue($response['success'], 'sendEmail using default values should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testSendApplicationForSigningEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);

		$response = $this->CobrandedApplication->sendApplicationForSigningEmail($app['CobrandedApplication']['id']);

		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		// assertions
		$this->assertTrue($response['success'], 'sendApplicationForSigningEmail with good email address should not fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->sendApplicationForSigningEmail('0');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'Invalid application.'
		);

		// assertions
		$this->assertFalse($response['success'], 'sendApplicationForSigningEmail with invalid application should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testRepNotifySignedEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);
		$response = $this->CobrandedApplication->repNotifySignedEmail($app['CobrandedApplication']['id']);

		$expectedResponse = array(
			'success' => true,
			'msg' => '',
		);

		// assertions
		$this->assertTrue($response['success'], 'repNotifySignedEmail with valid application should not fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->repNotifySignedEmail('0');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'Invalid application.'
		);

		// assertions
		$this->assertFalse($response['success'], 'repNotifySignedEmail with invalid application should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$optionalTemplate = 'rep_notify_signed';

		$response = $this->CobrandedApplication->repNotifySignedEmail($app['CobrandedApplication']['id'], $optionalTemplate);

		$expectedResponse = array(
			'success' => true,
			'msg' => '',
		);

		// assertions
		$this->assertTrue($response['success'], 'repNotifySignedEmail with valid application should not fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testSendRightsignatureInstallSheetEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);

		$response = $this->CobrandedApplication->sendRightsignatureInstallSheetEmail($app['CobrandedApplication']['id'], 'testing@axiapayments.com');

		$expectedResponse = array(
			'success' => true,
			'msg' => '',
		);

		// assertions
		$this->assertTrue($response['success'], 'sendRightsignatureInstallSheetEmail with valid application should not fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->sendRightsignatureInstallSheetEmail($app['CobrandedApplication']['id'], 'testing@nogood');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'invalid email address submitted.',
		);

		// assertions
		$this->assertFalse($response['success'], 'sendRightsignatureInstallSheetEmail with invalid email address should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->sendRightsignatureInstallSheetEmail('0', 'testing@axiapayments.com');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'Invalid application.'
		);

		// assertions
		$this->assertFalse($response['success'], 'sendRightsignatureInstallSheetEmail with invalid application should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testCreateNewApiApplicationEmailTimelineEntry() {
		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'Failed to create email timeline entry.'
		);

		// use bad data
		$args = array('cobranded_application_id' => '');
		$response = $this->CobrandedApplication->createNewApiApplicationEmailTimelineEntry($args);

		// assertions
		$this->assertFalse($response['success'], 'createNewApiApplicationEmailTimelineEntry with empty value for required field should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// use good data now - set expected results
		$expectedResponse = array(
			'success' => true,
			'msg' => ''
		);

		$args = array('cobranded_application_id' => '1');
		$response = $this->CobrandedApplication->createNewApiApplicationEmailTimelineEntry($args);

		// assertions
		$this->assertTrue($response['success'], 'createNewApiApplicationEmailTimelineEntry with good value for required field should succeed');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'cobranded_application_id argument is missing.'
		);

		// use bad data
		$args = array(
			'email_timeline_subject_id' => '',
			'recipient' => ''
		);
		$response = $this->CobrandedApplication->createEmailTimelineEntry($args);

		// assertions
		$this->assertFalse($response['success'], 'createEmailTimelineEntry with missing cobranded_application_id should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'email_timeline_subject_id argument is missing.'
		);

		// use bad data
		$args = array(
			'cobranded_application_id' => '1',
			'recipient' => ''
		);
		$response = $this->CobrandedApplication->createEmailTimelineEntry($args);

		// assertions
		$this->assertFalse($response['success'], 'createEmailTimelineEntry with missing email_timeline_subject_id should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		// set expected results
		$expectedResponse = array(
			'success' => false,
			'msg' => 'recipient argument is missing.'
		);

		// use bad data
		$args = array(
			'cobranded_application_id' => '1',
			'email_timeline_subject_id' => ''
		);
		$response = $this->CobrandedApplication->createEmailTimelineEntry($args);

		// assertions
		$this->assertFalse($response['success'], 'createEmailTimelineEntry with missing recipient should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testBuildCobrandedApplicationValuesMap() {
		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);

		$response = $this->CobrandedApplication->buildCobrandedApplicationValuesMap($app['CobrandedApplicationValues']);

		$expectedResponse = array(
			'Field 1' => null,
			'name1' => null,
			'name2' => null,
			'name3' => null,
			'Encrypt1' => null,
			'email' => 'testing@axiapayments.com',
			'Owner1Email' => 'testing@axiapayments.com',
			'Owner1Name' => 'Owner1NameTest',
			'Owner2Email' => 'testing@axiapayments.com',
			'Owner2Name' => 'Owner2NameTest',
			'TextField1' => 'text field 1',
			'TextField2' => 'text field 2',
			'TextField3' => 'text field 3',
			'TextField4' => 'text field 4',
			'DBA' => 'Doing Business As',
			'CorpName' => 'Corporate Name',
			'CorpContact' => 'Corporate Contact'
		);

		// assertions
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testValidateCobrandedApplication() {
		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);

		$response = $this->CobrandedApplication->validateCobrandedApplication($app);

		$expectedResponse = array(
			'success' => false,
			'validationErrors' => array(
				'Field 1' => 'required',
				'Encrypt1' => 'required'
			),
			'validationErrorsArray' => array(
				0 => array(
					'fieldName' => 'field 1',
					'mergeFieldName' => 'Field 1',
					'msg' => 'Required field is empty: field 1',
					'page' => 1,
					'rep_only' => false
				),
				1 => array(
					'fieldName' => 'field 2',
					'mergeFieldName' => 'Encrypt1',
					'msg' => 'Required field is empty: field 2',
					'page' => 1,
					'rep_only' => false
				)
			)
		);

		// assertions
		$this->assertFalse($response['success'], 'validateCobrandedApplication with empty required field should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testIndexInfo() {
		$responseTmp = $this->CobrandedApplication->find('index');

		$index = 0;
		$response = array();

		foreach ($responseTmp as $r) {
			if ($r['CobrandedApplication']['uuid'] == 'b118ac22d3cd4ab49148b05d5254ed59') {
				$response[$index] = $r;
				break;
			}
			$index++;
		}

		$expectedResponse = array(
			$index => array(
					'CobrandedApplication' => array(
						'id' => 1,
						'user_id' => 1,
						'template_id' => 1,
						'uuid' => 'b118ac22d3cd4ab49148b05d5254ed59',
						'modified' => $this->__cobrandedApplication['CobrandedApplication']['modified'],
						'rightsignature_document_guid' => null,
						'status' => null,
						'rightsignature_install_document_guid' => null,
						'rightsignature_install_status' => null,
						'data_to_sync' => null,
						'api_exported_date' => null,
						'csv_exported_date' => null,
						'client_id_global' => null,
						'client_name_global' => null,
						'application_group_id' => null
					),
					'Cobrand' => array(
						'id' => 1,
						'partner_name' => 'Partner Name 1',
					),
					'Template' => array(
						'id' => 1,
						'name' => 'Template 1 for PN1',
						'requires_coversheet' => false,
						'email_app_pdf' => true
					),
					'User' => array(
						'id' => 1,
						'firstname' => 'testuser1firstname',
						'lastname' => 'testuser1lastname',
						'email' => 'testing@axiapayments.com',
					),
					'Coversheet' => array(
						'id' => 1,
					),
					'Dba' => array(
						'value' => 'Doing Business As',
					),
					'CorpName' => array(
						'value' => 'Corporate Name',
					),
					'CorpContact' => array(
						'value' => 'Corporate Contact',
					),
					'Owner1Email' => array(
						'value' => 'testing@axiapayments.com',
					),
					'Owner2Email' => array(
						'value' => 'testing@axiapayments.com',
					),
					'EMail' => array(
						'value' => null,
					),
					'LocEMail' => array(
						'value' => null,
					),
					'Merchant' => array(
						'id' => null,
					),
					'Owner1Name' => array ('value' => 'Owner1NameTest'),
					'CorpPhone' => array ('value' => null),
					'PhoneNum' => array ('value' => null),
					'ApplicationGroup' => array(
						'access_token' => null,
						'client_access_token' => null,
						'client_password' => null

					)
			),
		);

		$expectedResponse[$index]['CobrandedApplication']['modified'] = $response[$index]['CobrandedApplication']['modified'];
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$responseTmp = $this->CobrandedApplication->find('index',
			array(
				'operation' => 'count',
				'sort' => 'test'
			)
		);

		// assertions
		$this->assertNotEmpty($responseTmp, 'Expected to find at least one app');
	}

	public function testBeforeFind() {
		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
				'sort' => 'CobrandedApplication.user_id',
				'direction' => 'ASC'
			)
		);

		// assertions
		$this->assertNotEmpty($app['CobrandedApplication'], 'Expected to find one app for user with id 1');
	}

	public function testIsExpired() {
		$response = $this->CobrandedApplication->isExpired('b118ac22d3cd4ab49148b05d5254ed59');
		// assertions
		$this->assertFalse($response, 'Expected isExpired to return false for non-signed application');

		$response = $this->CobrandedApplication->isExpired('c118ac22d3cd4ab49148b05d5254ed59');
		// assertions
		$this->assertTrue($response, 'Expected isExpired to return true for signed application');
	}

	public function testOrConditions() {
		$expectedResponse = array(
			'Dba.value ILIKE' => '%test%',
			'CorpName.value ILIKE' => '%test%',
			'CorpContact.value ILIKE' => '%test%',
			'Owner1Email.value ILIKE' => '%test%',
			'EMail.value ILIKE' => '%test%',
			'LocEMail.value ILIKE' => '%test%',
			"CobrandedApplication.uuid" => 'test',
			'CAST(CobrandedApplication.id AS TEXT) ILIKE' => '%test%'
		);

		$response = $this->CobrandedApplication->orConditions(array('search' => 'test'));

		// assertions
		$this->assertEquals($expectedResponse, $response['OR'], 'Expected response did not match response');
	}

	public function testCreateRightSignatureClient() {
		$response = $this->CobrandedApplication->createRightSignatureClient();

		// assertions
		$this->assertInstanceOf('RightSignature', $response, 'Expected response to be instance of RightSignature Object');
	}

	/**
	 * testSendSignedAppToUw
	 *
	 * @covers CobrandedApplication::sendSignedAppToUw()
	 * @return void
	 */
	public function testSendSignedAppToUw() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);
		$response = $this->CobrandedApplication->sendSignedAppToUw($app['CobrandedApplication']['id']);

		$expectedResponse = array(
			'success' => true,
			'msg' => '',
		);

		// assertions
		$this->assertTrue($response, 'testSendSignedAppToUw with valid application should not fail');

		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'recursive' => -1,
				'conditions' => array(
					'CobrandedApplication.user_id' => $app['CobrandedApplication']['id']
				),
			)
		);
		$this->assertNotEmpty($app['CobrandedApplication']['doc_secret_token']);
	}



	public function testSubmitForReviewEmail() {
		$CakeEmail = new CakeEmail('default');
		$CakeEmail->transport('Debug');
		$this->CobrandedApplication->CakeEmail = $CakeEmail;

		$app = $this->CobrandedApplication->find(
			'first',
			array(
				'conditions' => array(
					'CobrandedApplication.user_id' => '1'
				),
			)
		);
		$response = $this->CobrandedApplication->submitForReviewEmail($app['CobrandedApplication']['id']);

		$expectedResponse = array(
			'success' => true,
			'msg' => '',
		);

		// assertions
		$this->assertTrue($response['success'], 'submitForReviewEmail with valid application should not fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$response = $this->CobrandedApplication->submitForReviewEmail('0');

		$expectedResponse = array(
			'success' => false,
			'msg' => 'Invalid application.'
		);

		// assertions
		$this->assertFalse($response['success'], 'submitForReviewEmail with invalid application should fail');
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	public function testCreateRightSignatureApplicationXml() {
		$now = date('m/d/Y');
		$hostname = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : gethostname();

		$expectedResponse = "<?xml version='1.0' encoding='UTF-8'?>
	<template>
		<guid>1234</guid>
		<subject>Doing Business As Axia Merchant Application</subject>
		<description>Sent for signature by test@axiapayments.com</description>
		<action>send</action>
		<expires_in>10 days</expires_in>
		<roles>
			<role role_name='Owner/Officer 1 PG'>
				<name>Owner1NameTest</name>
				<email>noemail@rightsignature.com</email>
				<locked>true</locked>
			</role>
			<role role_name='Owner/Officer 1'>
				<name>Owner1NameTest</name>
				<email>noemail@rightsignature.com</email>
				<locked>true</locked>
			</role>
			<role role_name='Owner/Officer 2 PG'>
				<name>Owner2NameTest</name>
				<email>noemail@rightsignature.com</email>
				<locked>true</locked>
			</role>
			<role role_name='Owner/Officer 2'>
				<name>Owner2NameTest</name>
				<email>noemail@rightsignature.com</email>
				<locked>true</locked>
			</role>
		</roles>
		<merge_fields>
			<merge_field merge_field_name='SystemType'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='MID'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Customer Service Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Product Shipment Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Handling of Returns Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Application Date'>
				<value>" . htmlspecialchars($now) . "</value>
				<locked>true</locked>
			</merge_field>
		</merge_fields>
		<callback_location>https://" . $hostname . "/cobranded_applications/document_callback</callback_location>
	</template>
";

		$applicationId = 1;
		$sender = 'test@axiapayments.com';
		$subject = null;
		$terminalType = null;

		$rightSignatureTemplate = array();
		$rightSignatureTemplate['guid'] = '1234';
		$rightSignatureTemplate['merge_fields'] = array(
			array(
				'name' => 'SystemType'
			),
			array(
				'name' => 'MID'
			),
			array(
				'name' => 'Customer Service'
			),
			array(
				'name' => 'Product Shipment'
			),
			array(
				'name' => 'Handling of Returns'
			),
		);

		$response = $this->CobrandedApplication->createRightSignatureApplicationXml(
			$applicationId, $sender, $rightSignatureTemplate, $subject, $terminalType
		);

		// assertions
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$expectedResponse = "<?xml version='1.0' encoding='UTF-8'?>
	<template>
		<guid>1234</guid>
		<subject>Doing Business As Axia Install Sheet - VAR</subject>
		<description>Sent for signature by test@axiapayments.com</description>
		<action>send</action>
		<expires_in>10 days</expires_in>
		<roles>
			<role role_name='Signer'>
				<name>Owner1NameTest</name>
				<email>noemail@rightsignature.com</email>
				<locked>true</locked>
			</role>
		</roles>
		<merge_fields>
			<merge_field merge_field_name='SystemType'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='MID'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Customer Service Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Product Shipment Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Handling of Returns Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Phone#'>
				<value>877.875.6114 x 1</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='RepFax#'>
				<value>877.875.5135</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Application Date'>
				<value>" . htmlspecialchars($now) . "</value>
				<locked>true</locked>
			</merge_field>
		</merge_fields>
		<callback_location>https://" . $hostname . "/cobranded_applications/document_callback</callback_location>
	</template>
";

		$applicationId = 1;
		$sender = 'test@axiapayments.com';
		$subject = 'Axia Install Sheet - VAR';
		$terminalType = null;

		$rightSignatureTemplate = array();
		$rightSignatureTemplate['guid'] = '1234';
		$rightSignatureTemplate['merge_fields'] = array(
			array(
				'name' => 'SystemType'
			),
			array(
				'name' => 'MID'
			),
			array(
				'name' => 'Customer Service'
			),
			array(
				'name' => 'Product Shipment'
			),
			array(
				'name' => 'Handling of Returns'
			),
		);

		$response = $this->CobrandedApplication->createRightSignatureApplicationXml(
			$applicationId, $sender, $rightSignatureTemplate, $subject, $terminalType
		);

		// assertions
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');

		$expectedResponse = "<?xml version='1.0' encoding='UTF-8'?>
	<template>
		<guid>1234</guid>
		<subject> Axia Merchant Application</subject>
		<description>Sent for signature by test@axiapayments.com</description>
		<action>send</action>
		<expires_in>10 days</expires_in>
		<roles>
		</roles>
		<merge_fields>
			<merge_field merge_field_name='SystemType'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='MID'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Customer Service Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Product Shipment Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Handling of Returns Checkbox'>
				<value>x</value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Terminal2-'>
				<value></value>
				<locked>true</locked>
			</merge_field>
			<merge_field merge_field_name='Application Date'>
				<value>" . htmlspecialchars($now) . "</value>
				<locked>true</locked>
			</merge_field>
		</merge_fields>
		<callback_location>https://" . $hostname . "/cobranded_applications/document_callback</callback_location>
	</template>
";

		$applicationId = 3;
		$sender = 'test@axiapayments.com';
		$subject = null;
		$terminalType = null;

		$rightSignatureTemplate = array();
		$rightSignatureTemplate['guid'] = '1234';
		$rightSignatureTemplate['merge_fields'] = array(
			array(
				'name' => 'SystemType'
			),
			array(
				'name' => 'MID'
			),
			array(
				'name' => 'Customer Service'
			),
			array(
				'name' => 'Product Shipment'
			),
			array(
				'name' => 'Handling of Returns'
			),
			array(
				'name' => 'Terminal2-'
			),
		);

		$response = $this->CobrandedApplication->createRightSignatureApplicationXml(
			$applicationId, $sender, $rightSignatureTemplate, $subject, $terminalType
		);

		// assertions
		$this->assertEquals($expectedResponse, $response, 'Expected response did not match response');
	}

	private function __setSomeValuesBasedOnType(&$app) {
		foreach ($app['CobrandedApplicationValues'] as $key => $value) {
			$templateField = $this->TemplateField->find(
				'first',
				array(
					"conditions" => array(
						"TemplateField.id" => $value['template_field_id']
					)
				)
			);
			$newValue = '';
			switch ($templateField['TemplateField']['type']) {
				case 0:
					$newValue = 'text';
					break;
				case 1:
					$newValue = '2000-01-01';
					break;
				case 2:
					$newValue = '08:00 pm';
					break;
				case 3:
					$newValue = 'true';
					break;
				case 4:
					$newValue = 'true';
					break;
				case 5:
					$newValue = '10';
					break;
				case 6: // label
					$newValue = 'label';
					break;
				case 7:
					$newValue = '10.00';
					break;
				case 8: // hr
					$newValue = 'hr';
					break;
				case 9:
					$newValue = '8005551234';
					break;
				case 10:
					$newValue = '10.00';
					break;
				case 11:
					$newValue = '50';
					break;
				case 12:
					$newValue = '123-45-6789';
					break;
				case 13:
					$newValue = '12345-1234';
					break;
				case 14:
					$newValue = 'name@domain.com';
					break;
				//case 15:
				//	$newValue = '10 months';
				//	break;
				//case 16:
				//	$newValue = '4111-1111-1111-1111';
				//	break;
				case 17:
					$newValue = 'http://www.domain.com';
					break;
				case 18:
					$newValue = '12.82234';
					break;
				case 19:
					$newValue = '1234567890';
					break;
				case 20:
					$newValue = 'true';
					break;
				case 21:
					$newValue = 'a whole lot of text can go into this field...';
					break;
				case 22:
					$newValue = null;
					break;

				default:
					// ignore this
					break;
			}

			$value['value'] = $newValue;
			if ($templateField['TemplateField']['type'] != 9999) {
				$this->CobrandedApplicationValue->save($value);
			}
		}
	}

	/**
 	* testAddAppToGroup method
 	*
 	* @covers CobrandedApplication::addAppToGroup();
 	* @return void
 	*/
	public function testAddAppToGroup() {
		//Test that application gets assigned to ApplicationGroup
		$this->CobrandedApplication->addAppToGroup(1);
		$this->assertTrue($this->CobrandedApplication->hasAny(array("id" => 1, "application_group_id is not null")));

		//Save an email value for existing app with id=2 which matches that of app with id = 1 (defined in fixture)
		$value = array(
			'cobranded_application_id' => 2,
			'template_field_id' => 19,
			'name' => 'Owner1Email',
			'value' => 'testing@axiapayments.com',
		);
		$this->CobrandedApplicationValue->save($value);

		//Test that this call will puth both applications under the same ApplicationGroup
		$this->CobrandedApplication->addAppToGroup(2);
		$AppData = $this->CobrandedApplication->find('first', array('recursive' => -1, 'conditions' => array('id' => 1)));
		//The assigned application_group_id should be present in apps with ids 1 and 6 so total of 2 apps in the group
		$actual = $this->CobrandedApplication->find('all', array(
			'recursive' => -1, 
			'conditions' => array('application_group_id' => $AppData['CobrandedApplication']['application_group_id']),
			'order' => array('id ASC')
		));
		$this->assertEquals(2, count($actual));
		$this->assertEquals(1, $actual[0]['CobrandedApplication']['id']);
		$this->assertEquals(2, $actual[1]['CobrandedApplication']['id']);
		$this->assertEquals($actual[0]['CobrandedApplication']['application_group_id'], $actual[1]['CobrandedApplication']['application_group_id']);
	}

	/**
 	* testGetDataForCommonAppValueSearch method
 	*
 	* @covers CobrandedApplication::getDataForCommonAppValueSearch();
 	* @return void
 	*/
	public function testGetDataForCommonAppValueSearch() {
		$expected = array('testing@axiapayments.com');

		//Test that application id = 1 returns only owner email app value since this is the only value defined in the fixture
		$actual = $this->CobrandedApplication->getDataForCommonAppValueSearch(1);
		$this->assertContains('testing@axiapayments.com', $actual);

		//Save an additional email value for the same app id=1 and verify that the returned data is distinct
		$value = array(
			'cobranded_application_id' => 1,
			'template_field_id' => 19,
			'name' => 'ExtraEmailField',
			'value' => 'testing@axiapayments.com',
		);
		$this->CobrandedApplicationValue->save($value);

		//verify that the returned data is distinct, no duplicates
		$actual = $this->CobrandedApplication->getDataForCommonAppValueSearch(1);
		$this->assertCount(1, $actual);
		$this->assertSame($expected, $actual);

		//Save an additional different email value for the same app id=1 and verify it is added to the returned data
		$value = array(
			'cobranded_application_id' => 1,
			'template_field_id' => 19,
			'name' => 'CFOEmail',
			'value' => 'cfo@company.com',
		);
		$this->CobrandedApplicationValue->save($value);

		//verify that the returned data contains the additional email address, is distinct, no duplicates
		$actual = $this->CobrandedApplication->getDataForCommonAppValueSearch(1);
		$this->assertCount(2, $actual);
		$this->assertContains('cfo@company.com', $actual);
		$this->assertContains('testing@axiapayments.com', $actual);

		//Save the additional field values that are not used for this search for the same app id=1 and verify they are not added to the returned data
		$value = array(
			array(
				'cobranded_application_id' => 1,
				'template_field_id' => 0,
				'name' => 'SSN',
				'value' => '123456789',
			),
			array(
				'cobranded_application_id' => 1,
				'template_field_id' => 0,
				'name' => 'TaxID',
				'value' => '987654321',
			),
		);
		$this->CobrandedApplicationValue->saveMany($value);

		//verify that the returned data contains the additional email address, is distinct, no duplicates
		$actual = $this->CobrandedApplication->getDataForCommonAppValueSearch(1);
		$this->assertCount(2, $actual);
		$this->assertContains('cfo@company.com', $actual);
		$this->assertContains('testing@axiapayments.com', $actual);
		$this->assertNotContains('123456789', $actual);
		$this->assertNotContains('987654321', $actual);
		
	}

	/**
 	* testFindSameClientAppsUsingValuesInCommon method
 	*
 	* @covers CobrandedApplication::findSameClientAppsUsingValuesInCommon();
 	* @return void
 	*/
	public function testFindSameClientAppsUsingValuesInCommon() {
		$id =1;
		//Test that data is returned for Application with id = 1
		$this->assertNotEmpty($this->CobrandedApplication->findSameClientAppsUsingValuesInCommon($id));

		//Save an email value for existing app with id=2 which matches that of app with id = 1 (defined in fixture)
		$value = array(
			'cobranded_application_id' => 2,
			'template_field_id' => 19,
			'name' => 'Owner1Email',
			'value' => 'testing@axiapayments.com',
		);
		$this->CobrandedApplicationValue->save($value);

		//Test that only app all associated apps are returned since including self since current app is associated to iself
		$actual = $this->CobrandedApplication->findSameClientAppsUsingValuesInCommon($id);
		$this->assertCount(2, $actual);
		$actualIds = [];
		foreach ($actual as $relatedApps) {
			$actualIds[] = $relatedApps['CobrandedApplication']['id'];
		}
		$this->assertContains(2, $actualIds);
		$this->assertContains(1, $actualIds);

		//Save the additional field values that are used for thes search for the same app id=1 and 2 and verify they once again only app id = 2 is returned since
		$value = array(
			array(
				'cobranded_application_id' => 1,
				'template_field_id' => 0,
				'name' => 'SSN',
				'value' => '123456789',
			),
			array(
				'cobranded_application_id' => 2,
				'template_field_id' => 0,
				'name' => 'SSN',
				'value' => '123456789',
			),
		);
		$this->CobrandedApplicationValue->saveMany($value);
		//Test that all associated apps with common values are returned
		$actual = $this->CobrandedApplication->findSameClientAppsUsingValuesInCommon($id);
		$this->assertCount(2, $actual);
		$actualIds = [];
		foreach ($actual as $relatedApps) {
			$actualIds[] = $relatedApps['CobrandedApplication']['id'];
		}
		$this->assertContains(2, $actualIds);
		$this->assertContains(1, $actualIds);

		//Save an email value for existing app with id=3 which matches that of app with id = 1 and 2
		$value = array(
			'cobranded_application_id' => 3,
			'template_field_id' => 19,
			'name' => 'Owner1Email',
			'value' => 'testing@axiapayments.com',
		);
		$this->CobrandedApplicationValue->save($value);
		//Test that only app id = 1, 2 and 3 are returned since they have values in common with app with id=1
		$actual = $this->CobrandedApplication->findSameClientAppsUsingValuesInCommon($id);
		$this->assertCount(3, $actual);

		$actualIds = Hash::extract($actual, '{n}.CobrandedApplication.id');
		//check app ids are as expected
		$this->assertContains(1, $actualIds);
		$this->assertContains(2, $actualIds);
		$this->assertContains(3, $actualIds);
	}

	/**
 	* testBeforeDelete method
 	*
 	* @covers CobrandedApplication::beforeDelete();
 	* @return void
 	*/
	public function testBeforeDeleteUnreferenceApplicationGroupIsDeleted() {
		//Save an email value for existing app with id=2 which matches that of app with id = 1 (defined in fixture)
		$value = array(
			'cobranded_application_id' => 2,
			'template_field_id' => 19,
			'name' => 'Owner1Email',
			'value' => 'testing@axiapayments.com',
		);
		$this->CobrandedApplicationValue->save($value);
		//this call will puth both applications under the same ApplicationGroup
		$this->CobrandedApplication->addAppToGroup(1);
		$groupId = $this->CobrandedApplication->field('application_group_id', ['id' => 1]);

		$this->CobrandedApplication->deleteAll(['id' => 1], false, false);
		$this->assertTrue($this->CobrandedApplication->ApplicationGroup->hasAny(['id' => $groupId]));
		$this->CobrandedApplication->id = 2;
		//calling the callback method directly should delete the ApplicationGroup record
		$this->CobrandedApplication->beforeDelete(false);
		$this->assertFalse($this->CobrandedApplication->ApplicationGroup->hasAny(['id' => $groupId]));
	}

	/**
 	* testFindGroupedApps method
 	*
 	* @covers CobrandedApplication::findGroupedApps();
 	* @return void
 	*/
	public function testFindGroupedApps() {
		//Save common data in field values across 3 apps that are used to create app sibling groups
		$value = array(
			array(
				'cobranded_application_id' => 1,
				'template_field_id' => 5,
				'name' => 'SomeEmail',
				'value' => 'testing@axiapayments.com',
			),
			array(
				'cobranded_application_id' => 2,
				'template_field_id' => 5,
				'name' => 'SomeEmail',
				'value' => 'testing@axiapayments.com',
			),
			array(
				'cobranded_application_id' => 2,
				'template_field_id' => 40,
				'name' => 'DBA',
				'value' => 'Doing Business As',
				'created' => '2014-01-23 17:28:15',
				'modified' => '2014-01-23 17:28:15'
			),
			array(
				'cobranded_application_id' => 3,
				'template_field_id' => 5,
				'name' => 'SomeEmail',
				'value' => 'testing@axiapayments.com',
			),
			array(
				'cobranded_application_id' => 3,
				'template_field_id' => 40,
				'name' => 'DBA',
				'value' => 'Doing Business As',
				'created' => '2014-01-23 17:28:15',
				'modified' => '2014-01-23 17:28:15'
			),
		);
		$this->CobrandedApplicationValue->saveAll($value, array('validate' => false));
		//create a group that shold result in containing apps 1 and 6 since they have the same ssn
		$this->CobrandedApplication->addAppToGroup(1);
		//Get the data of the 3 apps
		$expected = $this->CobrandedApplication->find('all', 
			array(
				'recursive' => -1,
				'conditions' => array('id' => array(1,2,3)),
			));

		$expectedGroupId = $expected[0]['CobrandedApplication']['application_group_id'];
		$settings = array('CobrandedApplication.application_group_id' => $expectedGroupId);
		$actual = $this->CobrandedApplication->findGroupedApps($expectedGroupId, $settings);

		$this->assertCount(3, $actual);
		$this->assertSame($expectedGroupId, $actual[0]['CobrandedApplication']['application_group_id']);
		$this->assertSame($expectedGroupId, $actual[1]['CobrandedApplication']['application_group_id']);
		$this->assertSame($expectedGroupId, $actual[2]['CobrandedApplication']['application_group_id']);
		foreach ($actual as $data) {		
			$expectedAppIdFound = ($data['CobrandedApplication']['id'] == 1 || $data['CobrandedApplication']['id'] == 2 || $data['CobrandedApplication']['id'] == 3);
			$this->assertTrue($expectedAppIdFound);
		}

	}

}
