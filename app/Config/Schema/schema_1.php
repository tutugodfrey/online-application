<?php 
class AppSchema extends CakeSchema {

	public $file = 'schema_1.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $equipment_programming = array(
		'programming_id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'merchant_id' => array('type' => 'string', 'null' => false, 'length' => 50),
		'terminal_number' => array('type' => 'string', 'null' => true, 'length' => 20),
		'hardware_serial' => array('type' => 'string', 'null' => true, 'length' => 20),
		'terminal_type' => array('type' => 'string', 'null' => true, 'length' => 20),
		'network' => array('type' => 'string', 'null' => true, 'length' => 20),
		'provider' => array('type' => 'string', 'null' => true, 'length' => 20),
		'app_id' => array('type' => 'string', 'null' => true, 'length' => 20),
		'status' => array('type' => 'string', 'null' => true, 'length' => 5),
		'date_entered' => array('type' => 'date', 'null' => true),
		'date_changed' => array('type' => 'date', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'serial_number' => array('type' => 'string', 'null' => true, 'length' => 20),
		'pin_pad' => array('type' => 'string', 'null' => true, 'length' => 20),
		'printer' => array('type' => 'string', 'null' => true, 'length' => 20),
		'auto_close' => array('type' => 'string', 'null' => true, 'length' => 20),
		'chain' => array('type' => 'string', 'null' => true, 'length' => 6),
		'agent' => array('type' => 'string', 'null' => true, 'length' => 6),
		'gateway_id' => array('type' => 'integer', 'null' => true),
		'version' => array('type' => 'string', 'null' => true, 'length' => 20),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'programming_id'),
			'equipment_programming_appid' => array('unique' => false, 'column' => 'app_id'),
			'equipment_programming_merch_idx' => array('unique' => false, 'column' => 'merchant_id'),
			'equipment_programming_serialnum' => array('unique' => false, 'column' => 'serial_number'),
			'equipment_programming_status' => array('unique' => false, 'column' => 'status'),
			'equipment_programming_userid' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);

	public $merchant = array(
		'merchant_id' => array('type' => 'string', 'null' => false, 'length' => 50, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false),
		'merchant_mid' => array('type' => 'string', 'null' => false, 'length' => 20),
		'merchant_name' => array('type' => 'string', 'null' => true, 'length' => 100),
		'merchant_dba' => array('type' => 'string', 'null' => true, 'length' => 100),
		'merchant_contact' => array('type' => 'string', 'null' => true, 'length' => 50),
		'merchant_email' => array('type' => 'string', 'null' => true, 'length' => 50),
		'merchant_ownership_type' => array('type' => 'string', 'null' => true, 'length' => 30),
		'merchant_fed_tax_id' => array('type' => 'string', 'null' => true),
		'merchant_d_and_b' => array('type' => 'string', 'null' => true),
		'inactive_date' => array('type' => 'date', 'null' => true),
		'active_date' => array('type' => 'date', 'null' => true),
		'ref_seq_number' => array('type' => 'integer', 'null' => true),
		'network_id' => array('type' => 'integer', 'null' => true),
		'merchant_buslevel' => array('type' => 'string', 'null' => true, 'length' => 50),
		'merchant_sic' => array('type' => 'integer', 'null' => true),
		'entity' => array('type' => 'string', 'null' => true, 'length' => 10),
		'group_id' => array('type' => 'string', 'null' => true, 'length' => 10),
		'merchant_bustype' => array('type' => 'string', 'null' => true, 'length' => 100),
		'merchant_url' => array('type' => 'string', 'null' => true, 'length' => 100),
		'merchant_fed_tax_id_disp' => array('type' => 'string', 'null' => true, 'length' => 4),
		'merchant_d_and_b_disp' => array('type' => 'string', 'null' => true, 'length' => 4),
		'active' => array('type' => 'integer', 'null' => true),
		'cancellation_fee_id' => array('type' => 'integer', 'null' => true),
		'merchant_contact_position' => array('type' => 'string', 'null' => true, 'length' => 50),
		'merchant_mail_contact' => array('type' => 'string', 'null' => true, 'length' => 50),
		'res_seq_number' => array('type' => 'integer', 'null' => true),
		'bin_id' => array('type' => 'integer', 'null' => true),
		'acquirer_id' => array('type' => 'integer', 'null' => true),
		'reporting_user' => array('type' => 'string', 'null' => true, 'length' => 65),
		'merchant_ps_sold' => array('type' => 'string', 'null' => true, 'length' => 100),
		'ref_p_type' => array('type' => 'text', 'null' => true),
		'ref_p_value' => array('type' => 'float', 'null' => true),
		'res_p_type' => array('type' => 'text', 'null' => true),
		'res_p_value' => array('type' => 'float', 'null' => true),
		'ref_p_pct' => array('type' => 'integer', 'null' => true),
		'res_p_pct' => array('type' => 'integer', 'null' => true),
		'onlineapp_application_id' => array('type' => 'integer', 'null' => true),
		'partner_id' => array('type' => 'integer', 'null' => true),
		'partner_exclude_volume' => array('type' => 'boolean', 'null' => true),
		'aggregated' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'merchant_id'),
			'oaid_unique' => array('unique' => true, 'column' => 'onlineapp_application_id'),
			'merchant_active' => array('unique' => false, 'column' => 'active'),
			'merchant_dba_idx' => array('unique' => false, 'column' => 'merchant_dba'),
			'merchant_dbalower_idx' => array('unique' => false, 'column' => 'merchant_dba'),
			'merchant_entity' => array('unique' => false, 'column' => 'entity'),
			'merchant_groupid' => array('unique' => false, 'column' => 'group_id'),
			'merchant_netid' => array('unique' => false, 'column' => 'network_id'),
			'merchant_userid' => array('unique' => false, 'column' => 'user_id'),
			'mpid_index1' => array('unique' => false, 'column' => 'partner_id')
		),
		'tableParameters' => array()
	);

	public $onlineapp_api_logs = array(
		'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true),
		'user_token' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 40),
		'ip_address' => array('type' => 'inet', 'null' => true),
		'request_string' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'request_url' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'request_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'created' => array('type' => 'datetime', 'null' => true),
		'auth_status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 7),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'onlineapp_api_logs_user_id_key' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);

	public $onlineapp_apips = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true),
		'ip_address' => array('type' => 'inet', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);

	public $onlineapp_applications = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true),
		'status' => array('type' => 'string', 'null' => true, 'length' => 10),
		'hash' => array('type' => 'string', 'null' => true, 'length' => 32),
		'rs_document_guid' => array('type' => 'string', 'null' => true, 'length' => 40),
		'ownership_type' => array('type' => 'string', 'null' => true),
		'legal_business_name' => array('type' => 'string', 'null' => true),
		'mailing_address' => array('type' => 'string', 'null' => true),
		'mailing_city' => array('type' => 'string', 'null' => true),
		'mailing_state' => array('type' => 'string', 'null' => true),
		'mailing_zip' => array('type' => 'string', 'null' => true, 'length' => 10),
		'mailing_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'mailing_fax' => array('type' => 'string', 'null' => true, 'length' => 20),
		'federal_taxid' => array('type' => 'string', 'null' => true),
		'corp_contact_name' => array('type' => 'string', 'null' => true),
		'corp_contact_name_title' => array('type' => 'string', 'null' => true, 'length' => 50),
		'corporate_email' => array('type' => 'string', 'null' => true),
		'loc_same_as_corp' => array('type' => 'boolean', 'null' => true),
		'dba_business_name' => array('type' => 'string', 'null' => true),
		'location_address' => array('type' => 'string', 'null' => true),
		'location_city' => array('type' => 'string', 'null' => true),
		'location_state' => array('type' => 'string', 'null' => true),
		'location_zip' => array('type' => 'string', 'null' => true, 'length' => 10),
		'location_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'location_fax' => array('type' => 'string', 'null' => true, 'length' => 20),
		'customer_svc_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'loc_contact_name' => array('type' => 'string', 'null' => true),
		'loc_contact_name_title' => array('type' => 'string', 'null' => true),
		'location_email' => array('type' => 'string', 'null' => true),
		'website' => array('type' => 'string', 'null' => true),
		'bus_open_date' => array('type' => 'string', 'null' => true),
		'length_current_ownership' => array('type' => 'string', 'null' => true),
		'existing_axia_merchant' => array('type' => 'string', 'null' => true, 'length' => 10),
		'current_mid_number' => array('type' => 'string', 'null' => true, 'length' => 50),
		'general_comments' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'location_type' => array('type' => 'string', 'null' => true),
		'location_type_other' => array('type' => 'string', 'null' => true),
		'merchant_status' => array('type' => 'string', 'null' => true),
		'landlord_name' => array('type' => 'string', 'null' => true),
		'landlord_phone' => array('type' => 'string', 'null' => true),
		'business_type' => array('type' => 'string', 'null' => true, 'length' => 50),
		'products_services_sold' => array('type' => 'string', 'null' => true),
		'return_policy' => array('type' => 'string', 'null' => true),
		'days_until_prod_delivery' => array('type' => 'string', 'null' => true),
		'monthly_volume' => array('type' => 'string', 'null' => true, 'length' => 50),
		'average_ticket' => array('type' => 'string', 'null' => true, 'length' => 50),
		'highest_ticket' => array('type' => 'string', 'null' => true, 'length' => 50),
		'current_processor' => array('type' => 'string', 'null' => true, 'length' => 50),
		'card_present_swiped' => array('type' => 'integer', 'null' => true),
		'card_present_imprint' => array('type' => 'integer', 'null' => true),
		'card_not_present_keyed' => array('type' => 'integer', 'null' => true),
		'card_not_present_internet' => array('type' => 'integer', 'null' => true),
		'method_total' => array('type' => 'integer', 'null' => true),
		'direct_to_customer' => array('type' => 'integer', 'null' => true),
		'direct_to_business' => array('type' => 'integer', 'null' => true),
		'direct_to_govt' => array('type' => 'integer', 'null' => true),
		'products_total' => array('type' => 'integer', 'null' => true),
		'high_volume_january' => array('type' => 'boolean', 'null' => true),
		'high_volume_february' => array('type' => 'boolean', 'null' => true),
		'high_volume_march' => array('type' => 'boolean', 'null' => true),
		'high_volume_april' => array('type' => 'boolean', 'null' => true),
		'high_volume_may' => array('type' => 'boolean', 'null' => true),
		'high_volume_june' => array('type' => 'boolean', 'null' => true),
		'high_volume_july' => array('type' => 'boolean', 'null' => true),
		'high_volume_august' => array('type' => 'boolean', 'null' => true),
		'high_volume_september' => array('type' => 'boolean', 'null' => true),
		'high_volume_october' => array('type' => 'boolean', 'null' => true),
		'high_volume_november' => array('type' => 'boolean', 'null' => true),
		'high_volume_december' => array('type' => 'boolean', 'null' => true),
		'moto_storefront_location' => array('type' => 'string', 'null' => true, 'length' => 10),
		'moto_orders_at_location' => array('type' => 'string', 'null' => true, 'length' => 10),
		'moto_inventory_housed' => array('type' => 'string', 'null' => true),
		'moto_outsourced_customer_service' => array('type' => 'boolean', 'null' => true),
		'moto_outsourced_shipment' => array('type' => 'boolean', 'null' => true),
		'moto_outsourced_returns' => array('type' => 'boolean', 'null' => true),
		'moto_outsourced_billing' => array('type' => 'boolean', 'null' => true),
		'moto_sales_methods' => array('type' => 'string', 'null' => true),
		'moto_billing_monthly' => array('type' => 'boolean', 'null' => true),
		'moto_billing_quarterly' => array('type' => 'boolean', 'null' => true),
		'moto_billing_semiannually' => array('type' => 'boolean', 'null' => true),
		'moto_billing_annually' => array('type' => 'boolean', 'null' => true),
		'moto_policy_full_up_front' => array('type' => 'string', 'null' => true, 'length' => 10),
		'moto_policy_days_until_delivery' => array('type' => 'string', 'null' => true, 'length' => 10),
		'moto_policy_partial_up_front' => array('type' => 'string', 'null' => true, 'length' => 10),
		'moto_policy_partial_with' => array('type' => 'string', 'null' => true, 'length' => 10),
		'moto_policy_days_until_final' => array('type' => 'string', 'null' => true, 'length' => 10),
		'moto_policy_after' => array('type' => 'string', 'null' => true, 'length' => 10),
		'bank_name' => array('type' => 'string', 'null' => true),
		'bank_contact_name' => array('type' => 'string', 'null' => true),
		'bank_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'bank_address' => array('type' => 'string', 'null' => true),
		'bank_city' => array('type' => 'string', 'null' => true),
		'bank_state' => array('type' => 'string', 'null' => true),
		'bank_zip' => array('type' => 'string', 'null' => true, 'length' => 20),
		'depository_routing_number' => array('type' => 'string', 'null' => true),
		'depository_account_number' => array('type' => 'string', 'null' => true),
		'same_as_depository' => array('type' => 'boolean', 'null' => true),
		'fees_routing_number' => array('type' => 'string', 'null' => true),
		'fees_account_number' => array('type' => 'string', 'null' => true),
		'trade1_business_name' => array('type' => 'string', 'null' => true),
		'trade1_contact_person' => array('type' => 'string', 'null' => true),
		'trade1_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'trade1_acct_num' => array('type' => 'string', 'null' => true),
		'trade1_city' => array('type' => 'string', 'null' => true),
		'trade1_state' => array('type' => 'string', 'null' => true),
		'trade2_business_name' => array('type' => 'string', 'null' => true),
		'trade2_contact_person' => array('type' => 'string', 'null' => true),
		'trade2_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'trade2_acct_num' => array('type' => 'string', 'null' => true),
		'trade2_city' => array('type' => 'string', 'null' => true),
		'trade2_state' => array('type' => 'string', 'null' => true),
		'currently_accept_amex' => array('type' => 'string', 'null' => true, 'length' => 10),
		'existing_se_num' => array('type' => 'string', 'null' => true),
		'want_to_accept_amex' => array('type' => 'string', 'null' => true, 'length' => 10),
		'want_to_accept_discover' => array('type' => 'string', 'null' => true, 'length' => 10),
		'term1_quantity' => array('type' => 'integer', 'null' => true),
		'term1_type' => array('type' => 'string', 'null' => true, 'length' => 30),
		'term1_provider' => array('type' => 'string', 'null' => true),
		'term1_use_autoclose' => array('type' => 'string', 'null' => true, 'length' => 10),
		'term1_what_time' => array('type' => 'string', 'null' => true),
		'term1_programming_avs' => array('type' => 'boolean', 'null' => true),
		'term1_programming_server_nums' => array('type' => 'boolean', 'null' => true),
		'term1_programming_tips' => array('type' => 'boolean', 'null' => true),
		'term1_programming_invoice_num' => array('type' => 'boolean', 'null' => true),
		'term1_programming_purchasing_cards' => array('type' => 'boolean', 'null' => true),
		'term1_accept_debit' => array('type' => 'string', 'null' => true, 'length' => 10),
		'term1_pin_pad_type' => array('type' => 'string', 'null' => true),
		'term1_pin_pad_qty' => array('type' => 'integer', 'null' => true),
		'term2_quantity' => array('type' => 'integer', 'null' => true),
		'term2_type' => array('type' => 'string', 'null' => true, 'length' => 30),
		'term2_provider' => array('type' => 'string', 'null' => true),
		'term2_use_autoclose' => array('type' => 'string', 'null' => true, 'length' => 10),
		'term2_what_time' => array('type' => 'string', 'null' => true),
		'term2_programming_avs' => array('type' => 'boolean', 'null' => true),
		'term2_programming_server_nums' => array('type' => 'boolean', 'null' => true),
		'term2_programming_tips' => array('type' => 'boolean', 'null' => true),
		'term2_programming_invoice_num' => array('type' => 'boolean', 'null' => true),
		'term2_programming_purchasing_cards' => array('type' => 'boolean', 'null' => true),
		'term2_accept_debit' => array('type' => 'string', 'null' => true, 'length' => 10),
		'term2_pin_pad_type' => array('type' => 'string', 'null' => true),
		'term2_pin_pad_qty' => array('type' => 'integer', 'null' => true),
		'owner1_percentage' => array('type' => 'integer', 'null' => true),
		'owner1_fullname' => array('type' => 'string', 'null' => true),
		'owner1_title' => array('type' => 'string', 'null' => true),
		'owner1_address' => array('type' => 'string', 'null' => true),
		'owner1_city' => array('type' => 'string', 'null' => true),
		'owner1_state' => array('type' => 'string', 'null' => true),
		'owner1_zip' => array('type' => 'string', 'null' => true, 'length' => 20),
		'owner1_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'owner1_fax' => array('type' => 'string', 'null' => true, 'length' => 20),
		'owner1_email' => array('type' => 'string', 'null' => true),
		'owner1_ssn' => array('type' => 'string', 'null' => true),
		'owner1_dob' => array('type' => 'string', 'null' => true, 'length' => 50),
		'owner2_percentage' => array('type' => 'integer', 'null' => true),
		'owner2_fullname' => array('type' => 'string', 'null' => true),
		'owner2_title' => array('type' => 'string', 'null' => true),
		'owner2_address' => array('type' => 'string', 'null' => true),
		'owner2_city' => array('type' => 'string', 'null' => true),
		'owner2_state' => array('type' => 'string', 'null' => true),
		'owner2_zip' => array('type' => 'string', 'null' => true, 'length' => 20),
		'owner2_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'owner2_fax' => array('type' => 'string', 'null' => true, 'length' => 20),
		'owner2_email' => array('type' => 'string', 'null' => true),
		'owner2_ssn' => array('type' => 'string', 'null' => true),
		'owner2_dob' => array('type' => 'string', 'null' => true, 'length' => 50),
		'referral1_business' => array('type' => 'string', 'null' => true),
		'referral1_owner_officer' => array('type' => 'string', 'null' => true),
		'referral1_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'referral2_business' => array('type' => 'string', 'null' => true),
		'referral2_owner_officer' => array('type' => 'string', 'null' => true),
		'referral2_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'referral3_business' => array('type' => 'string', 'null' => true),
		'referral3_owner_officer' => array('type' => 'string', 'null' => true),
		'referral3_phone' => array('type' => 'string', 'null' => true, 'length' => 20),
		'rep_contractor_name' => array('type' => 'string', 'null' => true),
		'fees_rate_discount' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_rate_structure' => array('type' => 'string', 'null' => true),
		'fees_qualification_exemptions' => array('type' => 'string', 'null' => true),
		'fees_startup_application' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_auth_transaction' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_monthly_statement' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_misc_annual_file' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_startup_equipment' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_auth_amex' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_monthly_minimum' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_misc_chargeback' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_startup_expedite' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_auth_aru_voice' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_monthly_debit_access' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_startup_reprogramming' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_auth_wireless' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_monthly_ebt' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_startup_training' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_monthly_gateway_access' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_startup_wireless_activation' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_monthly_wireless_access' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_startup_tax' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_startup_total' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_pin_debit_auth' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_ebt_discount' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_pin_debit_discount' => array('type' => 'string', 'null' => true, 'length' => 20),
		'fees_ebt_auth' => array('type' => 'string', 'null' => true, 'length' => 20),
		'rep_discount_paid' => array('type' => 'string', 'null' => true, 'length' => 10),
		'rep_amex_discount_rate' => array('type' => 'string', 'null' => true, 'length' => 20),
		'rep_business_legitimate' => array('type' => 'string', 'null' => true, 'length' => 10),
		'rep_photo_included' => array('type' => 'string', 'null' => true, 'length' => 10),
		'rep_inventory_sufficient' => array('type' => 'string', 'null' => true, 'length' => 10),
		'rep_goods_delivered' => array('type' => 'string', 'null' => true, 'length' => 10),
		'rep_bus_open_operating' => array('type' => 'string', 'null' => true, 'length' => 10),
		'rep_visa_mc_decals_visible' => array('type' => 'string', 'null' => true, 'length' => 10),
		'rep_mail_tel_activity' => array('type' => 'string', 'null' => true, 'length' => 10),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'moto_inventory_owned' => array('type' => 'string', 'null' => true, 'length' => 10),
		'moto_outsourced_customer_service_field' => array('type' => 'string', 'null' => true, 'length' => 40),
		'moto_outsourced_shipment_field' => array('type' => 'string', 'null' => true, 'length' => 40),
		'moto_outsourced_returns_field' => array('type' => 'string', 'null' => true, 'length' => 40),
		'moto_sales_local' => array('type' => 'boolean', 'null' => true),
		'moto_sales_national' => array('type' => 'boolean', 'null' => true),
		'site_survey_signature' => array('type' => 'string', 'null' => true, 'length' => 40),
		'api' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'var_status' => array('type' => 'string', 'null' => true, 'length' => 10),
		'install_var_rs_document_guid' => array('type' => 'string', 'null' => true, 'length' => 32),
		'tickler_id' => array('type' => 'string', 'null' => true, 'length' => 36),
		'callback_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'guid' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 40),
		'redirect_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'api_idx' => array('unique' => false, 'column' => 'api'),
			'corp_contact_name_idx' => array('unique' => false, 'column' => 'corp_contact_name'),
			'dba_business_name_idx' => array('unique' => false, 'column' => 'dba_business_name'),
			'hash_idx' => array('unique' => false, 'column' => 'hash'),
			'install_var_rs_document_guid_idx' => array('unique' => false, 'column' => 'install_var_rs_document_guid'),
			'legal_business_name_idx' => array('unique' => false, 'column' => 'legal_business_name'),
			'mailing_city_idx' => array('unique' => false, 'column' => 'mailing_city'),
			'modified_idx' => array('unique' => false, 'column' => 'modified'),
			'owner1_fullname_idx' => array('unique' => false, 'column' => 'owner1_fullname'),
			'owner2_fullname_idx' => array('unique' => false, 'column' => 'owner2_fullname'),
			'rs_document_guid_idx' => array('unique' => false, 'column' => 'rs_document_guid'),
			'status_idx' => array('unique' => false, 'column' => 'status')
		),
		'tableParameters' => array()
	);

	public $onlineapp_coversheets = array(
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
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'onlineapp_application_id_key' => array('unique' => true, 'column' => 'onlineapp_application_id')
		),
		'tableParameters' => array()
	);

	public $onlineapp_email_timeline_subjects = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'subject' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 40),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);

	public $onlineapp_email_timelines = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'app_id' => array('type' => 'integer', 'null' => true),
		'date' => array('type' => 'datetime', 'null' => true),
		'subject_id' => array('type' => 'integer', 'null' => true),
		'recipient' => array('type' => 'string', 'null' => true, 'length' => 50),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);

	public $onlineapp_epayments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'pin' => array('type' => 'integer', 'null' => false),
		'application_id' => array('type' => 'integer', 'null' => false),
		'merchant_id' => array('type' => 'string', 'null' => true, 'length' => 40),
		'user_id' => array('type' => 'integer', 'null' => true),
		'onlineapp_application_id' => array('type' => 'integer', 'null' => true),
		'date_boarded' => array('type' => 'datetime', 'null' => false),
		'date_retrieved' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'onlineapp_epayments_merchant_id_key' => array('unique' => true, 'column' => 'merchant_id'),
			'onlineapp_epayments_onlineapp_applications_id_key' => array('unique' => true, 'column' => 'onlineapp_application_id')
		),
		'tableParameters' => array()
	);

	public $onlineapp_groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 100),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);

	public $onlineapp_multipasses = array(
		'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
		'merchant_id' => array('type' => 'string', 'null' => true, 'length' => 16),
		'device_number' => array('type' => 'string', 'null' => true, 'length' => 14),
		'username' => array('type' => 'string', 'null' => true, 'length' => 20),
		'pass' => array('type' => 'string', 'null' => true, 'length' => 20),
		'in_use' => array('type' => 'boolean', 'null' => false),
		'application_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'application_id_key' => array('unique' => true, 'column' => 'application_id'),
			'device_number_key' => array('unique' => true, 'column' => 'device_number'),
			'merchant_id_key' => array('unique' => true, 'column' => 'merchant_id'),
			'username_key' => array('unique' => true, 'column' => 'username')
		),
		'tableParameters' => array()
	);

	public $onlineapp_settings = array(
		'key' => array('type' => 'string', 'null' => false, 'key' => 'primary'),
		'value' => array('type' => 'string', 'null' => true),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'key')
		),
		'tableParameters' => array()
	);

	public $onlineapp_users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'email' => array('type' => 'string', 'null' => false),
		'password' => array('type' => 'string', 'null' => false, 'length' => 40),
		'group_id' => array('type' => 'integer', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'token' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 40),
		'token_used' => array('type' => 'datetime', 'null' => true),
		'token_uses' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'firstname' => array('type' => 'string', 'null' => true, 'length' => 40),
		'lastname' => array('type' => 'string', 'null' => true, 'length' => 40),
		'extension' => array('type' => 'integer', 'null' => true),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'api_password' => array('type' => 'string', 'null' => true, 'length' => 50),
		'api_enabled' => array('type' => 'boolean', 'null' => true),
		'api' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'onlineapp_users_email_key' => array('unique' => true, 'column' => 'email'),
			'token' => array('unique' => true, 'column' => 'token')
		),
		'tableParameters' => array()
	);

	public $onlineapp_users_managers = array(
		'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true),
		'manager_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);

	public $timeline_entries = array(
		'merchant_id' => array('type' => 'string', 'null' => false, 'length' => 50, 'key' => 'primary'),
		'timeline_item' => array('type' => 'string', 'null' => false, 'length' => 50),
		'timeline_date_completed' => array('type' => 'date', 'null' => true),
		'action_flag' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => array('merchant_id', 'timeline_item')),
			'tl_ent_merchant_idx' => array('unique' => false, 'column' => 'merchant_id'),
			'tl_timeline_date_completed' => array('unique' => false, 'column' => 'timeline_date_completed'),
			'tl_timeline_item' => array('unique' => false, 'column' => 'timeline_item')
		),
		'tableParameters' => array()
	);

	public $timeline_item = array(
		'timeline_item' => array('type' => 'string', 'null' => false, 'length' => 50, 'key' => 'primary'),
		'timeline_item_description' => array('type' => 'string', 'null' => true, 'length' => 100),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'timeline_item')
		),
		'tableParameters' => array()
	);

}
