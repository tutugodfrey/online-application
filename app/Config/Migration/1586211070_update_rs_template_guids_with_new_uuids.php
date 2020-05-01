<?php
App::uses('RightSignature', 'Model');
App::uses('CobrandedApplication', 'Model');
App::uses('Template', 'Model');
class UpdateRSTemplateGUIDsWithNewUUIDs extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'update_RS_template_GUIDs_with_new_UUIDs';

	public $newIdsMap = [
		"a_12701676_30b90f5acb3e4c298f7812db54275da7" => "73bdafbc-41a9-4658-acf4-8d8c4489d057",
		"a_20408697_6cba965939d6441e8ce0b0ce49cdd8a5" => "ac86068b-d513-42da-8061-b7430e645152",
		"a_20941894_fe1a3fae1f4b40a38b8a7044da864ccb" => "c83dce47-3f94-4d71-9ad9-50dacacbde41",
		"a_22024756_ad8ea678bbec41ca9416109c2cdb021d" => "0ea2be33-a756-462b-8a42-d4f4b5baca15",
		"a_28523243_260142172ce143d2a27d072bade3d418" => "624b3cfa-7252-4504-a2c3-3484ce5309b9",
		"a_30575619_985e3cbfac9f45158a0dc972ed935fec" => "1c88936e-01bf-4055-a328-1ce6cbc93792",
		"a_33782701_7b69ac4b1d604ccf8ed91c4b574d7242" => "314e467a-066a-40a3-958a-a7e52558ae45",
		"a_38554290_f1d0dbab56dc440788652117283b6767" => "b5f8bcf5-7fc9-45f6-b8a2-024c527de48e",
		"a_22078031_6a53077ce3b84b8ca98d800b96f4ca9b" => "2b1aa57d-bf9c-44e3-ba12-f1a05a60a1c2",
		"a_29355281_6ca696ee2e51428ab0e7aebfbf7633db" => "76ca07bb-e666-49ef-97ee-16619d49db58",
		"a_40425847_13282f2791454e50b4986abe5f5037f6" => "96642387-f4e0-428a-8994-bbfb2c1a4153",
		"a_30562090_5588631814384de7a7a7c510dfb1c3a9" => "b618ca8c-24ba-43be-8a51-c0d8d716feb1",
		"a_17763983_ad33f7dbf44f47cc9793af93554019ef" => "479c7955-1885-4ad8-bdca-6d038297f385",
		"a_20941762_3d26dee147e84366a061cf49d8cf2956" => "bca6d386-da9c-446a-a146-df425384279c",
		"a_10422450_37b37aa07c084fb384d1fe74275e80ed" => "fc4da6bb-157c-4873-aa4b-bbfa9362343d",
		"a_12928505_274ee92971c64852bfe5e862a659b803" => "eb05dc84-c451-4c9a-af44-9b1fc97ed5a4",
		"a_10422496_0265c80643bb4978a29d765da11109c9" => "099a961a-c613-4ed3-8fcb-fdf3762a3d9f",
		"a_19945988_a0a6fb37d6ef4340bcdca6d6bf5a6e93" => "cf4a7347-5325-4505-9994-4b74a86f0e29",
		"a_30572563_f80ede174398422d9bfb4be72df93e64" => "6cd0ddea-7b17-46cf-bd5d-757c5805a097",
		"a_19945424_a94f48a9dfba41afa717fcaa32dc9b70" => "5bb39da3-be6c-4b85-b9c2-4bb0872415ea",
		"a_20498855_4d1b269add9c433ab81ad80cc65f091e" => "9ecc22ce-f577-468e-bfc0-6164cd01df82",
		"a_20499152_37c0bef9d50a4d059bc056b4b8913f4d" => "e2e011c0-bf29-4033-9703-9e3bd2bf07ce",
		"a_10509298_9a7acbbfeea4489c97c30717cac2407b" => "fd01916d-fa45-427a-b01b-730cdfe23a08",
		"a_10508841_8c16eeeefe42498e9eaf13bc5ca13ba7" => "94f47ab9-1967-4a38-be76-d3d4660377ca",
		"a_30637030_f2245e076cdd42ce85abc2f80dc04e50" => "f2fb4f57-f0f1-4045-8fb7-0fc411f08277",
		"a_29036353_dd7300c857634bd39abe0c3150913695" => "52a135bd-74a8-4ff1-a184-a70350cb0e55",
		"a_20552750_56c79090c00b401d9f17a44189ebe33f" => "ab216894-7610-4da3-8e85-f41d35922fc6",
		"a_24346808_245e15c640d14becb673e06ffbe65d3b" => "02e81e29-03d7-4c8c-bdcc-cc72f7c63c39",
		"a_20575209_75128c7ae617494eb6808a1cbbf6295f" => "d0567225-3317-49f2-9b99-33bb41f12802",
		"a_30508152_5670129ebf6c42c4b1c281eec21ff96e" => "9b662acb-8f83-408b-bc24-3983e5d49be2",
		"a_30573239_8b1ba45b5a024ddab78104164da328a1" => "5b778fb1-52f6-4e96-8434-ed0be0bd6e9f",
		"a_21933214_7e74fcd0e3b2475dbe4dbc9c0d85e38c" => "a4029c7e-0442-42be-89e4-0ae7c22373a5",
		"a_10382161_c7e20695693845f1b1f9fd3ddc79a3fa" => "90749339-cc21-476a-ac81-1ad6e2a71a66",
		"a_12819196_9fc4bb1d1f72498f86574153233d0d35" => "577c2371-931d-42c0-be43-b34a4dc4bbdb",
		"a_24089897_72dcb7c84b634cc3a6faae5e7ed2f8e3" => "285694e4-5474-44cd-b33a-bd421d38ffa8",
		"a_13392825_7b9cebb9f8db4e7abcdde06f5c980536" => "b994f74b-cecf-4c44-bca9-61fba47b197d",
		"a_20767716_2ac3106997d44d97a3bf05ca9418ca2c" => "d6b4162c-4a4c-46a5-ac9d-27ba8fffbedc",
		"a_39991502_67e64066b53c4a4ba8ec38d8ad6ba46c" => "c55e13c4-38c7-4197-a8c9-4826dd901f08",
		"a_41057456_b6f8c4fbfa3e43289d24b77fff236253" => "9494cf3f-4325-4f02-8044-ec6a6056db0c"
	];

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'onlineapp_templates' => array(
					'old_template_guid' => array(
						'type' => 'string',
						'length' => 50
					),
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'onlineapp_templates' => array(
					'old_template_guid',
				)
			)
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function before($direction) {
		if ($direction === 'down') {
			$Template = ClassRegistry::init('Template');
			$Template->query('UPDATE onlineapp_templates set rightsignature_template_guid = old_template_guid');
		}
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		if ($direction == 'up') {
			$Template = ClassRegistry::init('Template');
			//Back up old template guids
			$Template->query('UPDATE onlineapp_templates set old_template_guid = rightsignature_template_guid');

			foreach ($this->newIdsMap as $oldGuid => $newUuid) {
				$Template->updateAll(
					['rightsignature_template_guid' => "'". $newUuid ."'"],
					['rightsignature_template_guid' => $oldGuid]
				);
			}
			
			// assign the new UUID to all templates install template guids where not null
			if (!empty($installTemplateUuid)) {
				$Template->updateAll(
					['rightsignature_install_template_guid' => "'66040346-2579-49cc-8aae-f9a53aa7160d'"],
					['rightsignature_install_template_guid IS NOT NULL', "rightsignature_install_template_guid != ''"]
				);
			}

		}
		return true;
	}
}
