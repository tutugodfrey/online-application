<?php
/**
 * OnlineappCoversheetFixture
 *
 */
class OnlineappCoversheetFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'onlineapp_application_id' => array('type' => 'integer', 'null' => false),
		'user_id' => array('type' => 'integer', 'null' => false),
		'status' => array('type' => 'string', 'null' => false, 'length' => 10),
		'setup_existing_merchant' => array('type' => 'boolean', 'null' => true),
		'setup_banking' => array('type' => 'boolean', 'null' => true),
		'setup_statements' => array('type' => 'boolean', 'null' => true),
		'setup_drivers_license' => array('type' => 'boolean', 'null' => true),
		'setup_new_merchant' => array('type' => 'boolean', 'null' => true),
		'setup_business_license' => array('type' => 'boolean', 'null' => true),
		'setup_other' => array('type' => 'boolean', 'null' => true),
		'setup_field_other' => array('type' => 'string', 'null' => true, 'length' => 20),
		'setup_tier_select' => array('type' => 'string', 'null' => true, 'length' => 1),
		'setup_tier3' => array('type' => 'boolean', 'null' => true),
		'setup_tier4' => array('type' => 'boolean', 'null' => true),
		'setup_tier5_financials' => array('type' => 'boolean', 'null' => true),
		'setup_tier5_processing_statements' => array('type' => 'boolean', 'null' => true),
		'setup_tier5_bank_statements' => array('type' => 'boolean', 'null' => true),
		'setup_equipment_terminal' => array('type' => 'boolean', 'null' => true),
		'setup_equipment_gateway' => array('type' => 'boolean', 'null' => true),
		'setup_install' => array('type' => 'string', 'null' => true, 'length' => 10),
		'setup_starterkit' => array('type' => 'string', 'null' => true, 'length' => 10),
		'setup_equipment_payment' => array('type' => 'string', 'null' => true, 'length' => 10),
		'setup_lease_price' => array('type' => 'float', 'null' => true),
		'setup_lease_months' => array('type' => 'float', 'null' => true),
		'setup_debit_volume' => array('type' => 'float', 'null' => true),
		'setup_item_count' => array('type' => 'float', 'null' => true),
		'setup_referrer' => array('type' => 'string', 'null' => true, 'length' => 20),
		'setup_referrer_type' => array('type' => 'string', 'null' => true, 'length' => 2),
		'setup_referrer_pct' => array('type' => 'float', 'null' => true),
		'setup_reseller' => array('type' => 'string', 'null' => true, 'length' => 20),
		'setup_reseller_type' => array('type' => 'string', 'null' => true, 'length' => 2),
		'setup_reseller_pct' => array('type' => 'float', 'null' => true),
		'setup_notes' => array('type' => 'string', 'null' => true),
		'cp_encrypted_sn' => array('type' => 'string', 'null' => true, 'length' => 12),
		'cp_pinpad_ra_attached' => array('type' => 'boolean', 'null' => true),
		'cp_giftcards' => array('type' => 'string', 'null' => true, 'length' => 10),
		'cp_check_guarantee' => array('type' => 'string', 'null' => true, 'length' => 10),
		'cp_check_guarantee_info' => array('type' => 'string', 'null' => true, 'length' => 50),
		'cp_pos' => array('type' => 'string', 'null' => true, 'length' => 10),
		'cp_pos_contact' => array('type' => 'string', 'null' => true, 'length' => 50),
		'micros' => array('type' => 'string', 'null' => true, 'length' => 10),
		'micros_billing' => array('type' => 'string', 'null' => true, 'length' => 10),
		'gateway_option' => array('type' => 'string', 'null' => true, 'length' => 10),
		'gateway_package' => array('type' => 'string', 'null' => true, 'length' => 10),
		'gateway_gold_subpackage' => array('type' => 'string', 'null' => true, 'length' => 10),
		'gateway_epay' => array('type' => 'string', 'null' => true, 'length' => 10),
		'gateway_billing' => array('type' => 'string', 'null' => true, 'length' => 10),
		'moto_online_chd' => array('type' => 'string', 'null' => true, 'length' => 10),
		'moto_developer' => array('type' => 'string', 'null' => true, 'length' => 40),
		'moto_company' => array('type' => 'string', 'null' => true, 'length' => 40),
		'moto_gateway' => array('type' => 'string', 'null' => true, 'length' => 40),
		'moto_contact' => array('type' => 'string', 'null' => true, 'length' => 40),
		'moto_phone' => array('type' => 'string', 'null' => true, 'length' => 40),
		'moto_email' => array('type' => 'string', 'null' => true, 'length' => 40),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'cobranded_application_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'onlineapp_application_id_key' => array('unique' => true, 'column' => 'onlineapp_application_id')
		),
		'tableParameters' => array()
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'onlineapp_application_id' => 1,
			'user_id' => 1,
			'status' => 'Lorem ip',
			'setup_existing_merchant' => 1,
			'setup_banking' => 1,
			'setup_statements' => 1,
			'setup_drivers_license' => 1,
			'setup_new_merchant' => 1,
			'setup_business_license' => 1,
			'setup_other' => 1,
			'setup_field_other' => 'Lorem ipsum dolor ',
			'setup_tier_select' => '1',
			'setup_tier3' => 1,
			'setup_tier4' => 1,
			'setup_tier5_financials' => 1,
			'setup_tier5_processing_statements' => 1,
			'setup_tier5_bank_statements' => 1,
			'setup_equipment_terminal' => 1,
			'setup_equipment_gateway' => 1,
			'setup_install' => 'Lorem ip',
			'setup_starterkit' => 'Lorem ip',
			'setup_equipment_payment' => 'Lorem ip',
			'setup_lease_price' => 1,
			'setup_lease_months' => 1,
			'setup_debit_volume' => 1,
			'setup_item_count' => 1,
			'setup_referrer' => 'Lorem ipsum dolor ',
			'setup_referrer_type' => '',
			'setup_referrer_pct' => 1,
			'setup_reseller' => 'Lorem ipsum dolor ',
			'setup_reseller_type' => '',
			'setup_reseller_pct' => 1,
			'setup_notes' => 'Lorem ipsum dolor sit amet',
			'cp_encrypted_sn' => 'Lorem ipsu',
			'cp_pinpad_ra_attached' => 1,
			'cp_giftcards' => 'Lorem ip',
			'cp_check_guarantee' => 'Lorem ip',
			'cp_check_guarantee_info' => 'Lorem ipsum dolor sit amet',
			'cp_pos' => 'Lorem ip',
			'cp_pos_contact' => 'Lorem ipsum dolor sit amet',
			'micros' => 'Lorem ip',
			'micros_billing' => 'Lorem ip',
			'gateway_option' => 'Lorem ip',
			'gateway_package' => 'Lorem ip',
			'gateway_gold_subpackage' => 'Lorem ip',
			'gateway_epay' => 'Lorem ip',
			'gateway_billing' => 'Lorem ip',
			'moto_online_chd' => 'Lorem ip',
			'moto_developer' => 'Lorem ipsum dolor sit amet',
			'moto_company' => 'Lorem ipsum dolor sit amet',
			'moto_gateway' => 'Lorem ipsum dolor sit amet',
			'moto_contact' => 'Lorem ipsum dolor sit amet',
			'moto_phone' => 'Lorem ipsum dolor sit amet',
			'moto_email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-12-31 12:13:36',
			'modified' => '2013-12-31 12:13:36',
			'cobranded_application_id' => 1
		),
	);
/*		array(
			'id' => 2,
			'onlineapp_application_id' => 2,
			'user_id' => 2,
			'status' => 'Lorem ip',
			'setup_existing_merchant' => 1,
			'setup_banking' => 1,
			'setup_statements' => 1,
			'setup_drivers_license' => 1,
			'setup_new_merchant' => 1,
			'setup_business_license' => 1,
			'setup_other' => 1,
			'setup_field_other' => 'Lorem ipsum dolor ',
			'setup_tier_select' => '1',
			'setup_tier3' => 1,
			'setup_tier4' => 1,
			'setup_tier5_financials' => 1,
			'setup_tier5_processing_statements' => 1,
			'setup_tier5_bank_statements' => 1,
			'setup_equipment_terminal' => 1,
			'setup_equipment_gateway' => 1,
			'setup_install' => 'Lorem ip',
			'setup_starterkit' => 'Lorem ip',
			'setup_equipment_payment' => 'Lorem ip',
			'setup_lease_price' => 2,
			'setup_lease_months' => 2,
			'setup_debit_volume' => 2,
			'setup_item_count' => 2,
			'setup_referrer' => 'Lorem ipsum dolor ',
			'setup_referrer_type' => '',
			'setup_referrer_pct' => 2,
			'setup_reseller' => 'Lorem ipsum dolor ',
			'setup_reseller_type' => '',
			'setup_reseller_pct' => 2,
			'setup_notes' => 'Lorem ipsum dolor sit amet',
			'cp_encrypted_sn' => 'Lorem ipsu',
			'cp_pinpad_ra_attached' => 1,
			'cp_giftcards' => 'Lorem ip',
			'cp_check_guarantee' => 'Lorem ip',
			'cp_check_guarantee_info' => 'Lorem ipsum dolor sit amet',
			'cp_pos' => 'Lorem ip',
			'cp_pos_contact' => 'Lorem ipsum dolor sit amet',
			'micros' => 'Lorem ip',
			'micros_billing' => 'Lorem ip',
			'gateway_option' => 'Lorem ip',
			'gateway_package' => 'Lorem ip',
			'gateway_gold_subpackage' => 'Lorem ip',
			'gateway_epay' => 'Lorem ip',
			'gateway_billing' => 'Lorem ip',
			'moto_online_chd' => 'Lorem ip',
			'moto_developer' => 'Lorem ipsum dolor sit amet',
			'moto_company' => 'Lorem ipsum dolor sit amet',
			'moto_gateway' => 'Lorem ipsum dolor sit amet',
			'moto_contact' => 'Lorem ipsum dolor sit amet',
			'moto_phone' => 'Lorem ipsum dolor sit amet',
			'moto_email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-12-31 12:13:36',
			'modified' => '2013-12-31 12:13:36'
		),
		array(
			'id' => 3,
			'onlineapp_application_id' => 3,
			'user_id' => 3,
			'status' => 'Lorem ip',
			'setup_existing_merchant' => 1,
			'setup_banking' => 1,
			'setup_statements' => 1,
			'setup_drivers_license' => 1,
			'setup_new_merchant' => 1,
			'setup_business_license' => 1,
			'setup_other' => 1,
			'setup_field_other' => 'Lorem ipsum dolor ',
			'setup_tier_select' => '1',
			'setup_tier3' => 1,
			'setup_tier4' => 1,
			'setup_tier5_financials' => 1,
			'setup_tier5_processing_statements' => 1,
			'setup_tier5_bank_statements' => 1,
			'setup_equipment_terminal' => 1,
			'setup_equipment_gateway' => 1,
			'setup_install' => 'Lorem ip',
			'setup_starterkit' => 'Lorem ip',
			'setup_equipment_payment' => 'Lorem ip',
			'setup_lease_price' => 3,
			'setup_lease_months' => 3,
			'setup_debit_volume' => 3,
			'setup_item_count' => 3,
			'setup_referrer' => 'Lorem ipsum dolor ',
			'setup_referrer_type' => '',
			'setup_referrer_pct' => 3,
			'setup_reseller' => 'Lorem ipsum dolor ',
			'setup_reseller_type' => '',
			'setup_reseller_pct' => 3,
			'setup_notes' => 'Lorem ipsum dolor sit amet',
			'cp_encrypted_sn' => 'Lorem ipsu',
			'cp_pinpad_ra_attached' => 1,
			'cp_giftcards' => 'Lorem ip',
			'cp_check_guarantee' => 'Lorem ip',
			'cp_check_guarantee_info' => 'Lorem ipsum dolor sit amet',
			'cp_pos' => 'Lorem ip',
			'cp_pos_contact' => 'Lorem ipsum dolor sit amet',
			'micros' => 'Lorem ip',
			'micros_billing' => 'Lorem ip',
			'gateway_option' => 'Lorem ip',
			'gateway_package' => 'Lorem ip',
			'gateway_gold_subpackage' => 'Lorem ip',
			'gateway_epay' => 'Lorem ip',
			'gateway_billing' => 'Lorem ip',
			'moto_online_chd' => 'Lorem ip',
			'moto_developer' => 'Lorem ipsum dolor sit amet',
			'moto_company' => 'Lorem ipsum dolor sit amet',
			'moto_gateway' => 'Lorem ipsum dolor sit amet',
			'moto_contact' => 'Lorem ipsum dolor sit amet',
			'moto_phone' => 'Lorem ipsum dolor sit amet',
			'moto_email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-12-31 12:13:36',
			'modified' => '2013-12-31 12:13:36'
		),
		array(
			'id' => 4,
			'onlineapp_application_id' => 4,
			'user_id' => 4,
			'status' => 'Lorem ip',
			'setup_existing_merchant' => 1,
			'setup_banking' => 1,
			'setup_statements' => 1,
			'setup_drivers_license' => 1,
			'setup_new_merchant' => 1,
			'setup_business_license' => 1,
			'setup_other' => 1,
			'setup_field_other' => 'Lorem ipsum dolor ',
			'setup_tier_select' => '1',
			'setup_tier3' => 1,
			'setup_tier4' => 1,
			'setup_tier5_financials' => 1,
			'setup_tier5_processing_statements' => 1,
			'setup_tier5_bank_statements' => 1,
			'setup_equipment_terminal' => 1,
			'setup_equipment_gateway' => 1,
			'setup_install' => 'Lorem ip',
			'setup_starterkit' => 'Lorem ip',
			'setup_equipment_payment' => 'Lorem ip',
			'setup_lease_price' => 4,
			'setup_lease_months' => 4,
			'setup_debit_volume' => 4,
			'setup_item_count' => 4,
			'setup_referrer' => 'Lorem ipsum dolor ',
			'setup_referrer_type' => '',
			'setup_referrer_pct' => 4,
			'setup_reseller' => 'Lorem ipsum dolor ',
			'setup_reseller_type' => '',
			'setup_reseller_pct' => 4,
			'setup_notes' => 'Lorem ipsum dolor sit amet',
			'cp_encrypted_sn' => 'Lorem ipsu',
			'cp_pinpad_ra_attached' => 1,
			'cp_giftcards' => 'Lorem ip',
			'cp_check_guarantee' => 'Lorem ip',
			'cp_check_guarantee_info' => 'Lorem ipsum dolor sit amet',
			'cp_pos' => 'Lorem ip',
			'cp_pos_contact' => 'Lorem ipsum dolor sit amet',
			'micros' => 'Lorem ip',
			'micros_billing' => 'Lorem ip',
			'gateway_option' => 'Lorem ip',
			'gateway_package' => 'Lorem ip',
			'gateway_gold_subpackage' => 'Lorem ip',
			'gateway_epay' => 'Lorem ip',
			'gateway_billing' => 'Lorem ip',
			'moto_online_chd' => 'Lorem ip',
			'moto_developer' => 'Lorem ipsum dolor sit amet',
			'moto_company' => 'Lorem ipsum dolor sit amet',
			'moto_gateway' => 'Lorem ipsum dolor sit amet',
			'moto_contact' => 'Lorem ipsum dolor sit amet',
			'moto_phone' => 'Lorem ipsum dolor sit amet',
			'moto_email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-12-31 12:13:36',
			'modified' => '2013-12-31 12:13:36'
		),
		array(
			'id' => 5,
			'onlineapp_application_id' => 5,
			'user_id' => 5,
			'status' => 'Lorem ip',
			'setup_existing_merchant' => 1,
			'setup_banking' => 1,
			'setup_statements' => 1,
			'setup_drivers_license' => 1,
			'setup_new_merchant' => 1,
			'setup_business_license' => 1,
			'setup_other' => 1,
			'setup_field_other' => 'Lorem ipsum dolor ',
			'setup_tier_select' => '1',
			'setup_tier3' => 1,
			'setup_tier4' => 1,
			'setup_tier5_financials' => 1,
			'setup_tier5_processing_statements' => 1,
			'setup_tier5_bank_statements' => 1,
			'setup_equipment_terminal' => 1,
			'setup_equipment_gateway' => 1,
			'setup_install' => 'Lorem ip',
			'setup_starterkit' => 'Lorem ip',
			'setup_equipment_payment' => 'Lorem ip',
			'setup_lease_price' => 5,
			'setup_lease_months' => 5,
			'setup_debit_volume' => 5,
			'setup_item_count' => 5,
			'setup_referrer' => 'Lorem ipsum dolor ',
			'setup_referrer_type' => '',
			'setup_referrer_pct' => 5,
			'setup_reseller' => 'Lorem ipsum dolor ',
			'setup_reseller_type' => '',
			'setup_reseller_pct' => 5,
			'setup_notes' => 'Lorem ipsum dolor sit amet',
			'cp_encrypted_sn' => 'Lorem ipsu',
			'cp_pinpad_ra_attached' => 1,
			'cp_giftcards' => 'Lorem ip',
			'cp_check_guarantee' => 'Lorem ip',
			'cp_check_guarantee_info' => 'Lorem ipsum dolor sit amet',
			'cp_pos' => 'Lorem ip',
			'cp_pos_contact' => 'Lorem ipsum dolor sit amet',
			'micros' => 'Lorem ip',
			'micros_billing' => 'Lorem ip',
			'gateway_option' => 'Lorem ip',
			'gateway_package' => 'Lorem ip',
			'gateway_gold_subpackage' => 'Lorem ip',
			'gateway_epay' => 'Lorem ip',
			'gateway_billing' => 'Lorem ip',
			'moto_online_chd' => 'Lorem ip',
			'moto_developer' => 'Lorem ipsum dolor sit amet',
			'moto_company' => 'Lorem ipsum dolor sit amet',
			'moto_gateway' => 'Lorem ipsum dolor sit amet',
			'moto_contact' => 'Lorem ipsum dolor sit amet',
			'moto_phone' => 'Lorem ipsum dolor sit amet',
			'moto_email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-12-31 12:13:36',
			'modified' => '2013-12-31 12:13:36'
		),
		array(
			'id' => 6,
			'onlineapp_application_id' => 6,
			'user_id' => 6,
			'status' => 'Lorem ip',
			'setup_existing_merchant' => 1,
			'setup_banking' => 1,
			'setup_statements' => 1,
			'setup_drivers_license' => 1,
			'setup_new_merchant' => 1,
			'setup_business_license' => 1,
			'setup_other' => 1,
			'setup_field_other' => 'Lorem ipsum dolor ',
			'setup_tier_select' => '1',
			'setup_tier3' => 1,
			'setup_tier4' => 1,
			'setup_tier5_financials' => 1,
			'setup_tier5_processing_statements' => 1,
			'setup_tier5_bank_statements' => 1,
			'setup_equipment_terminal' => 1,
			'setup_equipment_gateway' => 1,
			'setup_install' => 'Lorem ip',
			'setup_starterkit' => 'Lorem ip',
			'setup_equipment_payment' => 'Lorem ip',
			'setup_lease_price' => 6,
			'setup_lease_months' => 6,
			'setup_debit_volume' => 6,
			'setup_item_count' => 6,
			'setup_referrer' => 'Lorem ipsum dolor ',
			'setup_referrer_type' => '',
			'setup_referrer_pct' => 6,
			'setup_reseller' => 'Lorem ipsum dolor ',
			'setup_reseller_type' => '',
			'setup_reseller_pct' => 6,
			'setup_notes' => 'Lorem ipsum dolor sit amet',
			'cp_encrypted_sn' => 'Lorem ipsu',
			'cp_pinpad_ra_attached' => 1,
			'cp_giftcards' => 'Lorem ip',
			'cp_check_guarantee' => 'Lorem ip',
			'cp_check_guarantee_info' => 'Lorem ipsum dolor sit amet',
			'cp_pos' => 'Lorem ip',
			'cp_pos_contact' => 'Lorem ipsum dolor sit amet',
			'micros' => 'Lorem ip',
			'micros_billing' => 'Lorem ip',
			'gateway_option' => 'Lorem ip',
			'gateway_package' => 'Lorem ip',
			'gateway_gold_subpackage' => 'Lorem ip',
			'gateway_epay' => 'Lorem ip',
			'gateway_billing' => 'Lorem ip',
			'moto_online_chd' => 'Lorem ip',
			'moto_developer' => 'Lorem ipsum dolor sit amet',
			'moto_company' => 'Lorem ipsum dolor sit amet',
			'moto_gateway' => 'Lorem ipsum dolor sit amet',
			'moto_contact' => 'Lorem ipsum dolor sit amet',
			'moto_phone' => 'Lorem ipsum dolor sit amet',
			'moto_email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-12-31 12:13:36',
			'modified' => '2013-12-31 12:13:36'
		),
		array(
			'id' => 7,
			'onlineapp_application_id' => 7,
			'user_id' => 7,
			'status' => 'Lorem ip',
			'setup_existing_merchant' => 1,
			'setup_banking' => 1,
			'setup_statements' => 1,
			'setup_drivers_license' => 1,
			'setup_new_merchant' => 1,
			'setup_business_license' => 1,
			'setup_other' => 1,
			'setup_field_other' => 'Lorem ipsum dolor ',
			'setup_tier_select' => '1',
			'setup_tier3' => 1,
			'setup_tier4' => 1,
			'setup_tier5_financials' => 1,
			'setup_tier5_processing_statements' => 1,
			'setup_tier5_bank_statements' => 1,
			'setup_equipment_terminal' => 1,
			'setup_equipment_gateway' => 1,
			'setup_install' => 'Lorem ip',
			'setup_starterkit' => 'Lorem ip',
			'setup_equipment_payment' => 'Lorem ip',
			'setup_lease_price' => 7,
			'setup_lease_months' => 7,
			'setup_debit_volume' => 7,
			'setup_item_count' => 7,
			'setup_referrer' => 'Lorem ipsum dolor ',
			'setup_referrer_type' => '',
			'setup_referrer_pct' => 7,
			'setup_reseller' => 'Lorem ipsum dolor ',
			'setup_reseller_type' => '',
			'setup_reseller_pct' => 7,
			'setup_notes' => 'Lorem ipsum dolor sit amet',
			'cp_encrypted_sn' => 'Lorem ipsu',
			'cp_pinpad_ra_attached' => 1,
			'cp_giftcards' => 'Lorem ip',
			'cp_check_guarantee' => 'Lorem ip',
			'cp_check_guarantee_info' => 'Lorem ipsum dolor sit amet',
			'cp_pos' => 'Lorem ip',
			'cp_pos_contact' => 'Lorem ipsum dolor sit amet',
			'micros' => 'Lorem ip',
			'micros_billing' => 'Lorem ip',
			'gateway_option' => 'Lorem ip',
			'gateway_package' => 'Lorem ip',
			'gateway_gold_subpackage' => 'Lorem ip',
			'gateway_epay' => 'Lorem ip',
			'gateway_billing' => 'Lorem ip',
			'moto_online_chd' => 'Lorem ip',
			'moto_developer' => 'Lorem ipsum dolor sit amet',
			'moto_company' => 'Lorem ipsum dolor sit amet',
			'moto_gateway' => 'Lorem ipsum dolor sit amet',
			'moto_contact' => 'Lorem ipsum dolor sit amet',
			'moto_phone' => 'Lorem ipsum dolor sit amet',
			'moto_email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-12-31 12:13:36',
			'modified' => '2013-12-31 12:13:36'
		),
		array(
			'id' => 8,
			'onlineapp_application_id' => 8,
			'user_id' => 8,
			'status' => 'Lorem ip',
			'setup_existing_merchant' => 1,
			'setup_banking' => 1,
			'setup_statements' => 1,
			'setup_drivers_license' => 1,
			'setup_new_merchant' => 1,
			'setup_business_license' => 1,
			'setup_other' => 1,
			'setup_field_other' => 'Lorem ipsum dolor ',
			'setup_tier_select' => '1',
			'setup_tier3' => 1,
			'setup_tier4' => 1,
			'setup_tier5_financials' => 1,
			'setup_tier5_processing_statements' => 1,
			'setup_tier5_bank_statements' => 1,
			'setup_equipment_terminal' => 1,
			'setup_equipment_gateway' => 1,
			'setup_install' => 'Lorem ip',
			'setup_starterkit' => 'Lorem ip',
			'setup_equipment_payment' => 'Lorem ip',
			'setup_lease_price' => 8,
			'setup_lease_months' => 8,
			'setup_debit_volume' => 8,
			'setup_item_count' => 8,
			'setup_referrer' => 'Lorem ipsum dolor ',
			'setup_referrer_type' => '',
			'setup_referrer_pct' => 8,
			'setup_reseller' => 'Lorem ipsum dolor ',
			'setup_reseller_type' => '',
			'setup_reseller_pct' => 8,
			'setup_notes' => 'Lorem ipsum dolor sit amet',
			'cp_encrypted_sn' => 'Lorem ipsu',
			'cp_pinpad_ra_attached' => 1,
			'cp_giftcards' => 'Lorem ip',
			'cp_check_guarantee' => 'Lorem ip',
			'cp_check_guarantee_info' => 'Lorem ipsum dolor sit amet',
			'cp_pos' => 'Lorem ip',
			'cp_pos_contact' => 'Lorem ipsum dolor sit amet',
			'micros' => 'Lorem ip',
			'micros_billing' => 'Lorem ip',
			'gateway_option' => 'Lorem ip',
			'gateway_package' => 'Lorem ip',
			'gateway_gold_subpackage' => 'Lorem ip',
			'gateway_epay' => 'Lorem ip',
			'gateway_billing' => 'Lorem ip',
			'moto_online_chd' => 'Lorem ip',
			'moto_developer' => 'Lorem ipsum dolor sit amet',
			'moto_company' => 'Lorem ipsum dolor sit amet',
			'moto_gateway' => 'Lorem ipsum dolor sit amet',
			'moto_contact' => 'Lorem ipsum dolor sit amet',
			'moto_phone' => 'Lorem ipsum dolor sit amet',
			'moto_email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-12-31 12:13:36',
			'modified' => '2013-12-31 12:13:36'
		),
		array(
			'id' => 9,
			'onlineapp_application_id' => 9,
			'user_id' => 9,
			'status' => 'Lorem ip',
			'setup_existing_merchant' => 1,
			'setup_banking' => 1,
			'setup_statements' => 1,
			'setup_drivers_license' => 1,
			'setup_new_merchant' => 1,
			'setup_business_license' => 1,
			'setup_other' => 1,
			'setup_field_other' => '1 ipsum dolor ',
			'setup_tier_select' => '1',
			'setup_tier3' => 1,
			'setup_tier4' => 1,
			'setup_tier5_financials' => 1,
			'setup_tier5_processing_statements' => 1,
			'setup_tier5_bank_statements' => 1,
			'setup_equipment_terminal' => 1,
			'setup_equipment_gateway' => 1,
			'setup_install' => 'Lorem ip',
			'setup_starterkit' => 'Lorem ip',
			'setup_equipment_payment' => 'Lorem ip',
			'setup_lease_price' => 9,
			'setup_lease_months' => 9,
			'setup_debit_volume' => 9,
			'setup_item_count' => 9,
			'setup_referrer' => 'Lorem ipsum dolor ',
			'setup_referrer_type' => '',
			'setup_referrer_pct' => 9,
			'setup_reseller' => 'Lorem ipsum dolor ',
			'setup_reseller_type' => '',
			'setup_reseller_pct' => 9,
			'setup_notes' => 'Lorem ipsum dolor sit amet',
			'cp_encrypted_sn' => 'Lorem ipsu',
			'cp_pinpad_ra_attached' => 1,
			'cp_giftcards' => 'Lorem ip',
			'cp_check_guarantee' => 'Lorem ip',
			'cp_check_guarantee_info' => 'Lorem ipsum dolor sit amet',
			'cp_pos' => 'Lorem ip',
			'cp_pos_contact' => 'Lorem ipsum dolor sit amet',
			'micros' => 'Lorem ip',
			'micros_billing' => 'Lorem ip',
			'gateway_option' => 'Lorem ip',
			'gateway_package' => 'Lorem ip',
			'gateway_gold_subpackage' => 'Lorem ip',
			'gateway_epay' => 'Lorem ip',
			'gateway_billing' => 'Lorem ip',
			'moto_online_chd' => 'Lorem ip',
			'moto_developer' => 'Lorem ipsum dolor sit amet',
			'moto_company' => 'Lorem ipsum dolor sit amet',
			'moto_gateway' => 'Lorem ipsum dolor sit amet',
			'moto_contact' => 'Lorem ipsum dolor sit amet',
			'moto_phone' => 'Lorem ipsum dolor sit amet',
			'moto_email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-12-31 12:13:36',
			'modified' => '2013-12-31 12:13:36'
		),
		array(
			'id' => 10,
			'onlineapp_application_id' => 10,
			'user_id' => 10,
			'status' => 'Lorem ip',
			'setup_existing_merchant' => 1,
			'setup_banking' => 1,
			'setup_statements' => 1,
			'setup_drivers_license' => 1,
			'setup_new_merchant' => 1,
			'setup_business_license' => 1,
			'setup_other' => 1,
			'setup_field_other' => 'Lorem ipsum dolor ',
			'setup_tier_select' => '1',
			'setup_tier3' => 1,
			'setup_tier4' => 1,
			'setup_tier5_financials' => 1,
			'setup_tier5_processing_statements' => 1,
			'setup_tier5_bank_statements' => 1,
			'setup_equipment_terminal' => 1,
			'setup_equipment_gateway' => 1,
			'setup_install' => 'Lorem ip',
			'setup_starterkit' => 'Lorem ip',
			'setup_equipment_payment' => 'Lorem ip',
			'setup_lease_price' => 10,
			'setup_lease_months' => 10,
			'setup_debit_volume' => 10,
			'setup_item_count' => 10,
			'setup_referrer' => 'Lorem ipsum dolor ',
			'setup_referrer_type' => '',
			'setup_referrer_pct' => 10,
			'setup_reseller' => 'Lorem ipsum dolor ',
			'setup_reseller_type' => '',
			'setup_reseller_pct' => 10,
			'setup_notes' => 'Lorem ipsum dolor sit amet',
			'cp_encrypted_sn' => 'Lorem ipsu',
			'cp_pinpad_ra_attached' => 1,
			'cp_giftcards' => 'Lorem ip',
			'cp_check_guarantee' => 'Lorem ip',
			'cp_check_guarantee_info' => 'Lorem ipsum dolor sit amet',
			'cp_pos' => 'Lorem ip',
			'cp_pos_contact' => 'Lorem ipsum dolor sit amet',
			'micros' => 'Lorem ip',
			'micros_billing' => 'Lorem ip',
			'gateway_option' => 'Lorem ip',
			'gateway_package' => 'Lorem ip',
			'gateway_gold_subpackage' => 'Lorem ip',
			'gateway_epay' => 'Lorem ip',
			'gateway_billing' => 'Lorem ip',
			'moto_online_chd' => 'Lorem ip',
			'moto_developer' => 'Lorem ipsum dolor sit amet',
			'moto_company' => 'Lorem ipsum dolor sit amet',
			'moto_gateway' => 'Lorem ipsum dolor sit amet',
			'moto_contact' => 'Lorem ipsum dolor sit amet',
			'moto_phone' => 'Lorem ipsum dolor sit amet',
			'moto_email' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-12-31 12:13:36',
			'modified' => '2013-12-31 12:13:36'
 		),
	);
 */
}
