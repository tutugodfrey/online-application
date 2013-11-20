<?php

App::uses('User', 'Model');
App::uses('Setting', 'Model');
App::uses('CakeTime', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class Application extends AppModel {

    const API_ENABLED = 1;

    public $useTable = 'onlineapp_applications';
    
    /**
     * Attach Model Behavoirs
     * @var array
     */
    public $actsAs = array('Multivalidatable', 'Search.Searchable', 'Containable', 'Cryptable' => array(
            'fields' => array(
                'federal_taxid', 'depository_routing_number', 'depository_account_number', 'fees_routing_number', 'fees_account_number', 'owner1_ssn', 'owner2_ssn'
            )
        )
    );

    /**
     * Check's to see whether an application should still be accessible or if it
     * should be expired
     * @param date $data
     * @return boolean
     */
    public function expiration($data) {
        if (CakeTime::wasWithinLast('30 days', $data['Application']['modified'])) {
            return true;
        } return false;
    }

    /**
     * Standard Validation Methods
     * @var type 
     */
    public $validate = array(
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        // ******************
        // STEP #1 VALIDATION
        // ******************
        'ownership_type' => array('rule' => 'notEmpty'),
        'legal_business_name' => array('rule' => 'notEmpty', 'required' => true),
        'mailing_address' => array('rule' => 'notEmpty', 'required' => true),
        'mailing_city' => array('rule' => 'notEmpty', 'required' => true),
        'mailing_state' => array('rule' => 'notEmpty', 'required' => true),
        'mailing_zip' => array(
            'rule' => array('postal', null, 'us'),
            'required' => true),
        'mailing_phone' => array(
            'rule' => array('phone', null, 'us'),
            'required' => true),
        'mailing_fax' => array(
            'rule' => array('phone', null, 'us')
        ),
        'corp_contact_name' => array('rule' => 'notEmpty', 'required' => true),
        'corp_contact_name_title' => array('rule' => 'notEmpty', 'required' => true),
        'corporate_email' => array(
            'rule' => array('email', true),
            'required' => true,
            'message' => 'Please supply a valid email address'
        ),
        'dba_business_name' => array('rule' => 'notEmpty', 'required' => true),
        'location_address' => array('rule' => 'notEmpty'),
        'location_city' => array('rule' => 'notEmpty'),
        'location_state' => array('rule' => 'notEmpty'),
        'location_zip' => array(
            'rule' => array('postal', null, 'us'),
            'required' => true),
        'location_phone' => array(
            'rule' => array('phone', null, 'us'),
            'required' => true
        ),
        'location_fax' => array(
            'rule' => array('phone', null, 'us'),
            'required' => true
        ),
        'loc_contact_name' => array('rule' => 'notEmpty'),
        'loc_contact_name_title' => array('rule' => 'notEmpty'),
        'location_email' => array(
            'rule' => 'email',
            'required' => true
        ),
        'federal_taxid' => array(
            'minLength' => array('rule' => array('minLength', 9), 'required' => true),
            'maxLength' => array('rule' => array('maxLength', 9)),
            'numeric' => array('rule' => 'numeric')
        ),
//        'federal_taxid' => array(
//            'rule' => array('minLength',9),
//            'rule' => array('maxLength',9),
//            'rule' => 'numeric',
//            'required' => true,
//            'message' => 'Please enter valid Tax ID'
//        ),
        'website' => array('rule' => 'url', 'required' => true),
        'customer_svc_phone' => array(
            'rule' => array('phone', null, 'us'),
            'required' => true
        ),
        'bus_open_date' => array('rule' => array('date', 'mdy')),
        'length_current_ownership' => array('rule' => 'notEmpty'),
        'existing_axia_merchant' => array('rule' => 'notEmpty'),
        // 'current_mid_number' => array('rule' => 'notEmpty'),
        'location_type' => array('rule' => 'notEmpty'),
        // 'landlord_name' => array('rule' => 'notEmpty'),
        // ******************
        // STEP #2 VALIDATION
        // ******************
        'business_type' => array('rule' => 'notEmpty'),
        'products_services_sold' => array('rule' => 'notEmpty'),
        'return_policy' => array('rule' => 'notEmpty'),
        'days_until_prod_delivery' => array('rule' => 'notEmpty'),
        'monthly_volume' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'average_ticket' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'highest_ticket' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'current_processor' => array('rule' => 'notEmpty'),
        'card_present_swiped' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'card_present_imprint' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'card_not_present_keyed' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'card_not_present_internet' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'method_total' => array(
            'rule' => array('comparison', '==', '100'),
            'message' => 'Must equal 100%'
        ),
        'direct_to_customer' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'direct_to_business' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'direct_to_govt' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'products_total' => array(
            'rule' => array('comparison', '==', '100'),
            'message' => 'Must equal 100%'
        ),
                    'moto_storefront_location' => array('rule' => 'notEmpty'),
            'moto_orders_at_location' => array('rule' => 'notEmpty'),
            'moto_inventory_housed' => array('rule' => 'notEmpty'),
            'moto_policy_full_up_front' => array(
                'rule' => array('numeric', 'notEmpty')
            ),
            'moto_policy_partial_up_front' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'moto_policy_after' => array(
                'rule' => array('numeric',
                'notEmpty',
                'required' => true),
            ),
            'moto_policy_days_until_delivery' => array(
                'rule' => array('numeric', 'notEmpty')
            ),
            'moto_policy_partial_with' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'moto_policy_days_until_final' => array(
                'rule' => 'numeric',
                'required' => true
            ),
                    'billing_delivery_policy' => array(
                'rule' => 'billingDeliveryPolicy',
                'message' => 'Total must equal 100%:'
            ),
        // ******************
        // STEP #3 VALIDATION
        // ******************
        'bank_name' => array('rule' => 'notEmpty', 'required' => true),
        'depository_routing_number' => array(
            'rule' => 'checkDepositRoutingNumber',
            'required' => true,
            'message' => ' Invalid Routing Number'
        ),
        'depository_account_number' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'fees_routing_number' => array(
            'rule' => 'checkFeesRoutingNumber',
            'required' => true,
            'message' => ' Invalid Routing Number'
        ),
        'fees_account_number' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'bank_zip' => array(
            'rule' => array('postal', null, 'us'),
            'allowEmpty' => true),
        
        'trade1_business_name' => array('rule' => 'notEmpty'),
        'trade1_contact_person' => array('rule' => 'notEmpty'),
        'trade1_phone' => array(
            'rule' => array('phone', null, 'us'),
            'required' => true
        ),
        'trade1_acct_num' => array('rule' => 'notEmpty'),
        'trade1_city' => array('rule' => 'notEmpty'),
        'trade1_state' => array('rule' => 'notEmpty'),
        // 'trade2_business_name' => array('rule' => 'notEmpty'),
        // 'trade2_contact_person' => array('rule' => 'notEmpty'),
        // 'trade2_phone' => array('rule' => 'notEmpty'),
        // 'trade2_acct_num' => array('rule' => 'notEmpty'),
        // 'trade2_city' => array('rule' => 'notEmpty'),
        // 'trade2_state' => array('rule' => 'notEmpty'),
        // ******************
        // STEP #4 VALIDATION
        // ******************
        'currently_accept_amex' => array('rule' => 'notEmpty'),
        // 'existing_se_num' => array('rule' => 'notEmpty'),
        // 'want_to_accept_amex' => array('rule' => 'notEmpty'),
        'want_to_accept_discover' => array('rule' => 'notEmpty'),
        'term1_type' => array('rule' => 'notEmpty'),
        'term1_quantity' => array(
            'rule' => 'numeric',
            'required' => true
        ),
        'term1_provider' => array('rule' => 'notEmpty'),
        'term1_use_autoclose' => array('rule' => 'notEmpty'),
        // 'term1_what_time' => array('rule' => 'notEmpty'),
        // 'term1_programming_avs' => array('rule' => 'notEmpty'),
        // 'term1_programming_server_nums' => array('rule' => 'notEmpty'),
        // 'term1_programming_tips' => array('rule' => 'notEmpty'),
        // 'term1_programming_invoice_num' => array('rule' => 'notEmpty'),
        // 'term1_programming_purchasing_cards' => array('rule' => 'notEmpty'),
        'term1_accept_debit' => array('rule' => 'notEmpty'),
        // 'term1_pin_pad_type' => array('rule' => 'notEmpty'),
        // ******************
        // STEP #5 VALIDATION
        // ******************
        'owner1_percentage' => array(
            'rule' => array('comparison', '<=', 100),
            'message' => 'Please enter a number between 0 and 100',
            'required' => true,
            'allowEmpty' => false
        ),
        'owner1_fullname' => array('rule' => 'notEmpty'),
        'owner1_title' => array('rule' => 'notEmpty'),
        'owner1_address' => array('rule' => 'notEmpty'),
        'owner1_city' => array('rule' => 'notEmpty'),
        'owner1_state' => array('rule' => 'notEmpty'),
        'owner1_zip' => array(
            'rule' => array('postal', null, 'us'),
            'required' => true),
        'owner1_phone' => array(
            'rule' => array('phone', null, 'us'),
            'required' => true),
        'owner1_fax' => array(
            'rule' => array('phone', null, 'us'),
            'allowEmpty' => true),
        'owner1_email' => array(
            'rule' => 'email',
            'required' => true
        ),
        //'owner1_ssn' => array('rule' => array('ssn', null, 'us')),
//        'owner1_ssn' => array(
//            'minLength' => array('rule' => array('minLength',9)),
//            'maxLength' => array('rule' => array('maxLength',9)),
//            'numeric' => array('rule' => 'numeric'),
//            'required' => true,
//            ),
        'owner1_ssn' => array(
            'minLength' => array('rule' => array('minLength', 9),
                'required' => true),
            'maxLength' => array('rule' => array('maxLength', 9),
                'required' => true),
            'numeric' => array('rule' => 'numeric',
                'required' => true),
        ),
        'owner1_dob' => array('rule' => array('date', 'mdy')),
        // ******************
        // STEP #6 VALIDATION
        // ******************
        // 'referral1_business' => array('rule' => 'notEmpty'),
        // 'referral1_owner_officer' => array('rule' => 'notEmpty'),
        // 'referral1_phone' => array('rule' => 'notEmpty'),
        // 'referral2_business' => array('rule' => 'notEmpty'),
        // 'referral2_owner_officer' => array('rule' => 'notEmpty'),
        // 'referral2_phone' => array('rule' => 'notEmpty'),
        // 'referral3_business' => array('rule' => 'notEmpty'),
        // 'referral3_owner_officer' => array('rule' => 'notEmpty'),
        // 'referral3_phone' => array('rule' => 'notEmpty'),
        'fees_rate_discount' => array('rule' => 'notEmpty'),
        'fees_rate_structure' => array('rule' => 'notEmpty'),
        'fees_qualification_exemptions' => array('rule' => 'notEmpty'),
        'fees_startup_application' => array('rule' => 'notEmpty'),
        'fees_auth_transaction' => array('rule' => 'notEmpty'),
        'fees_monthly_statement' => array('rule' => 'notEmpty'),
        'fees_misc_annual_file' => array('rule' => 'notEmpty'),
        'fees_startup_equipment' => array('rule' => 'notEmpty'),
        'fees_auth_amex' => array('rule' => 'notEmpty'),
        'fees_monthly_minimum' => array('rule' => 'notEmpty'),
        'fees_misc_chargeback' => array('rule' => 'notEmpty'),
        'fees_startup_expedite' => array('rule' => 'notEmpty'),
        'fees_auth_aru_voice' => array('rule' => 'notEmpty'),
        'fees_monthly_debit_access' => array('rule' => 'notEmpty'),
        'fees_startup_reprogramming' => array('rule' => 'notEmpty'),
        'fees_auth_wireless' => array('rule' => 'notEmpty'),
        'fees_monthly_ebt' => array('rule' => 'notEmpty'),
        'fees_startup_training' => array('rule' => 'notEmpty'),
        'fees_monthly_gateway_access' => array('rule' => 'notEmpty'),
        'fees_startup_wireless_activation' => array('rule' => 'notEmpty'),
        'fees_monthly_wireless_access' => array('rule' => 'notEmpty'),
        //'fees_startup_tax'  => array('rule' => 'notEmpty'),
        'fees_startup_total' => array('rule' => 'notEmpty'),
        'fees_pin_debit_auth' => array('rule' => 'notEmpty'),
        //'fees_ebt_discount'  => array('rule' => 'notEmpty'),
        'fees_pin_debit_discount' => array('rule' => 'notEmpty'),
        //'fees_ebt_auth'  => array('rule' => 'notEmpty'),
        'rep_contractor_name' => array('rule' => 'notEmpty'),
        'rep_amex_discount_rate' => array('rule' => 'notEmpty'),
        'callback_url' => array('rule' => array('url', true), 'message' => 'Did You Enter A Valid URL?'),
        'redirect_url' => array('rule' => array('url', true), 'message' => 'Did You Enter A Valid URL?'),
    );
    //Added 3-15-2011 SJT Test Skipping Validation for certian elements
    /**
     * Uses multivalidatable behavoir to set seperate validation for HOOZA onlineapp
     * @var type 
     */
    public $validationSets = array(
        'app' => array(
            'user_id' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    //'message' => 'Your custom message here',
                    //'allowEmpty' => false,
                    'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
                ),
            ),
            // ******************
            // STEP #1 VALIDATION
            // ******************
            'ownership_type' => array('rule' => 'notEmpty'),
            'legal_business_name' => array('rule' => 'notEmpty'),
            'mailing_address' => array('rule' => 'notEmpty'),
            'mailing_city' => array('rule' => 'notEmpty'),
            'mailing_state' => array('rule' => 'notEmpty'),
            'mailing_zip' => array('rule' => 'notEmpty'),
            'mailing_phone' => array('rule' => 'notEmpty'),
            'mailing_fax' => array('rule' => 'notEmpty'),
            'corp_contact_name' => array('rule' => 'notEmpty'),
            'corp_contact_name_title' => array('rule' => 'notEmpty'),
            'corporate_email' => array(
                'rule' => 'email',
                'required' => true
            ),
            'dba_business_name' => array('rule' => 'notEmpty'),
            'location_address' => array('rule' => 'notEmpty'),
            'location_city' => array('rule' => 'notEmpty'),
            'location_state' => array('rule' => 'notEmpty'),
            'location_zip' => array('rule' => 'notEmpty'),
            'location_phone' => array('rule' => 'notEmpty'),
            'location_fax' => array('rule' => 'notEmpty'),
            'loc_contact_name' => array('rule' => 'notEmpty'),
            'loc_contact_name_title' => array('rule' => 'notEmpty'),
            'location_email' => array(
                'rule' => 'email',
                'required' => true
            ),
            'federal_taxid' => array(
                'minLength' => array('rule' => array('minLength', 9),
                    'required' => true),
                'maxLength' => array('rule' => array('maxLength', 9),
                    'required' => true),
                'numeric' => array('rule' => 'numeric',
                    'required' => true),
            ),
            'website' => array('rule' => 'notEmpty'),
            'customer_svc_phone' => array('rule' => 'notEmpty'),
            'bus_open_date' => array('rule' => 'notEmpty'),
            'length_current_ownership' => array('rule' => 'notEmpty'),
            //'existing_axia_merchant' => array('rule' => 'notEmpty'),
            // 'current_mid_number' => array('rule' => 'notEmpty'),
            //'location_type' => array('rule' => 'notEmpty'),
            // 'landlord_name' => array('rule' => 'notEmpty'),
            // ******************
            // STEP #2 VALIDATION
            // ******************
            'business_type' => array('rule' => 'notEmpty'),
            'products_services_sold' => array('rule' => 'notEmpty'),
            'return_policy' => array('rule' => 'notEmpty'),
            'days_until_prod_delivery' => array('rule' => 'notEmpty'),
            'monthly_volume' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'average_ticket' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'highest_ticket' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'current_processor' => array('rule' => 'notEmpty'),
            'card_present_swiped' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'card_present_imprint' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'card_not_present_keyed' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'card_not_present_internet' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'method_total' => array(
                'rule' => array('comparison', '==', '100'),
                'message' => 'Must equal 100%'
            ),
            'direct_to_customer' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'direct_to_business' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'direct_to_govt' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'products_total' => array(
                'rule' => array('comparison', '==', '100'),
                'message' => 'Must equal 100%'
            ),
            'moto_storefront_location' => array('rule' => 'notEmpty'),
            'moto_orders_at_location' => array('rule' => 'notEmpty'),
            'moto_inventory_housed' => array('rule' => 'notEmpty'),
            'moto_policy_full_up_front' => array(
                'rule' => array('numeric', 'notEmpty')
            ),
            'moto_policy_partial_up_front' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'moto_policy_after' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'moto_policy_days_until_delivery' => array(
                'rule' => array('numeric', 'notEmpty')
            ),
            'moto_policy_partial_with' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'moto_policy_days_until_final' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            // ******************
            // STEP #3 VALIDATION
            // ******************
            'bank_name' => array('rule' => 'notEmpty'),
            'depository_routing_number' => array(
                'rule' => 'checkDepositRoutingNumber',
                'required' => true,
                'message' => ' Invalid Routing Number'
            ),
            'depository_account_number' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'fees_routing_number' => array(
                'rule' => 'checkFeesRoutingNumber',
                'required' => true,
                'message' => ' Invalid Routing Number'
            ),
            'fees_account_number' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'trade1_business_name' => array('rule' => 'notEmpty'),
            'trade1_contact_person' => array('rule' => 'notEmpty'),
            'trade1_phone' => array('rule' => 'notEmpty'),
            'trade1_acct_num' => array('rule' => 'notEmpty'),
            'trade1_city' => array('rule' => 'notEmpty'),
            'trade1_state' => array('rule' => 'notEmpty'),
            // 'trade2_business_name' => array('rule' => 'notEmpty'),
            // 'trade2_contact_person' => array('rule' => 'notEmpty'),
            // 'trade2_phone' => array('rule' => 'notEmpty'),
            // 'trade2_acct_num' => array('rule' => 'notEmpty'),
            // 'trade2_city' => array('rule' => 'notEmpty'),
            // 'trade2_state' => array('rule' => 'notEmpty'),
            // ******************
            // STEP #4 VALIDATION
            // ******************
            //'currently_accept_amex' => array('rule' => 'notEmpty'),
            // 'existing_se_num' => array('rule' => 'notEmpty'),
            'want_to_accept_amex' => array('rule' => 'notEmpty'),
            'want_to_accept_discover' => array('rule' => 'notEmpty'),
            'term1_type' => array('rule' => 'notEmpty'),
            'term1_quantity' => array(
                'rule' => 'numeric',
                'required' => true
            ),
            'term1_provider' => array('rule' => 'notEmpty'),
            'term1_use_autoclose' => array('rule' => 'notEmpty'),
            // 'term1_what_time' => array('rule' => 'notEmpty'),
            // 'term1_programming_avs' => array('rule' => 'notEmpty'),
            // 'term1_programming_server_nums' => array('rule' => 'notEmpty'),
            // 'term1_programming_tips' => array('rule' => 'notEmpty'),
            // 'term1_programming_invoice_num' => array('rule' => 'notEmpty'),
            // 'term1_programming_purchasing_cards' => array('rule' => 'notEmpty'),
            'term1_accept_debit' => array('rule' => 'notEmpty'),
            // 'term1_pin_pad_type' => array('rule' => 'notEmpty'),
            // ******************
            // STEP #5 VALIDATION
            // ******************
            'owner1_percentage' => array(
                'rule' => array('comparison', '<=', '100'),
                'message' => 'Please enter a number between 1 and 100',
                'required' => true
            ),
            'owner1_fullname' => array('rule' => 'notEmpty'),
            'owner1_title' => array('rule' => 'notEmpty'),
            'owner1_address' => array('rule' => 'notEmpty'),
            'owner1_city' => array('rule' => 'notEmpty'),
            'owner1_state' => array('rule' => 'notEmpty'),
            'owner1_zip' => array('rule' => 'notEmpty'),
            'owner1_phone' => array('rule' => 'notEmpty'),
            // 'owner1_fax' => array('rule' => 'notEmpty'),
            'owner1_email' => array(
                'rule' => 'email',
                'required' => true
            ),
            'federal_taxid' => array(
                'minLength' => array('rule' => array('minLength', 9),
                    'required' => true),
                'maxLength' => array('rule' => array('maxLength', 9),
                    'required' => true),
                'numeric' => array('rule' => 'numeric',
                    'required' => true),
            ),
            'owner1_dob' => array('rule' => array('date', 'mdy')),
            //'owner1_ssn' => array('rule' => 'notEmpty'),
            //'owner1_dob' => array('rule' => 'notEmpty'),
            // ******************
            // STEP #6 VALIDATION
            // ******************
            // 'referral1_business' => array('rule' => 'notEmpty'),
            // 'referral1_owner_officer' => array('rule' => 'notEmpty'),
            // 'referral1_phone' => array('rule' => 'notEmpty'),
            // 'referral2_business' => array('rule' => 'notEmpty'),
            // 'referral2_owner_officer' => array('rule' => 'notEmpty'),
            // 'referral2_phone' => array('rule' => 'notEmpty'),
            // 'referral3_business' => array('rule' => 'notEmpty'),
            // 'referral3_owner_officer' => array('rule' => 'notEmpty'),
            // 'referral3_phone' => array('rule' => 'notEmpty'),
            'fees_rate_discount' => array('rule' => 'notEmpty'),
            'fees_rate_structure' => array('rule' => 'notEmpty'),
            'fees_qualification_exemptions' => array('rule' => 'notEmpty'),
            'fees_startup_application' => array('rule' => 'notEmpty'),
            'fees_auth_transaction' => array('rule' => 'notEmpty'),
            'fees_monthly_statement' => array('rule' => 'notEmpty'),
            'fees_misc_annual_file' => array('rule' => 'notEmpty'),
            'fees_startup_equipment' => array('rule' => 'notEmpty'),
            'fees_auth_amex' => array('rule' => 'notEmpty'),
            'fees_monthly_minimum' => array('rule' => 'notEmpty'),
            'fees_misc_chargeback' => array('rule' => 'notEmpty'),
            'fees_startup_expedite' => array('rule' => 'notEmpty'),
            'fees_auth_aru_voice' => array('rule' => 'notEmpty'),
            'fees_monthly_debit_access' => array('rule' => 'notEmpty'),
            'fees_startup_reprogramming' => array('rule' => 'notEmpty'),
            'fees_auth_wireless' => array('rule' => 'notEmpty'),
            'fees_monthly_ebt' => array('rule' => 'notEmpty'),
            'fees_startup_training' => array('rule' => 'notEmpty'),
            'fees_monthly_gateway_access' => array('rule' => 'notEmpty'),
            'fees_startup_wireless_activation' => array('rule' => 'notEmpty'),
            'fees_monthly_wireless_access' => array('rule' => 'notEmpty'),
            //'fees_startup_tax'  => array('rule' => 'notEmpty'),
            'fees_startup_total' => array('rule' => 'notEmpty'),
            'fees_pin_debit_auth' => array('rule' => 'notEmpty'),
            //'fees_ebt_discount'  => array('rule' => 'notEmpty'),
            'fees_pin_debit_discount' => array('rule' => 'notEmpty'),
            //'fees_ebt_auth'  => array('rule' => 'notEmpty'),
            'rep_contractor_name' => array('rule' => 'notEmpty'),
            'rep_amex_discount_rate' => array('rule' => 'notEmpty')
        ),
        'install_var_select' => array(
            //'select_email_address' => array(
            'rule' => 'email',
            'required' => true
        //)
        ),
        'install_var_enter' => array(
            //   'enter_email_address' => array(
            'rule' => 'email',
            'required' => true
        //)
        )
    );

    /**
     * Create everything needed for making an OAuth secured connection to
     * the rightSignature API
     * @todo make the request tokens constants
     */
    function createOAuth() {
        App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'rightsignature.php'));
        App::import('Vendor', 'oauth', array('file' => 'OAuth' . DS . 'OAuth.php'));
        App::uses('HttpSocket', 'Network/Http');
        $HttpSocket = new HttpSocket();
        $rightsignature = $this->createConsumer();
        $rightsignature = new RightSignature('J7PQlPSlm3jaa2DbfCP989mIFrKRHUH1NqcjJugT', 'ZAYx4jEy6BVYPuad4kPQAw6lTrOxAeqWU8DGT6A1');
        $this->set('rightsignature', $rightsignature);
        $rightsignature->request_token = new OAuthConsumer('v1cfHXdnHbD8in6ruqsb3MDVbuhdtZMaHTKVw1XI', 'tTyOsXYMAgoPQY5NXlsB9sKAYRZXsuLIcBzTiOpB', 1);
        $rightsignature->access_token = new OAuthConsumer('FvpRze1k6JbP7HHm64IxQiWLHL9p0Jl4pw3x7PBP', 'cHrzepxhF7t9QMyO8CGUJlbSg4Lon23JEVYnD70Z', 1);
        $rightsignature->oauth_verifier = 'jmV0StucajLmdz2gc7hw';
    }

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    function billingDeliveryPolicy () {
        if ($this->data[$this->alias]['card_not_present_keyed'] + $this->data[$this->alias]['card_not_present_internet'] >= '30') {
             if(($this->data[$this->alias]['moto_policy_partial_with'] + $this->data[$this->alias]['moto_policy_full_up_front'] + $this->data[$this->alias]['moto_policy_partial_up_front'] + $this->data[$this->alias]['moto_policy_after']) == '100') {
                 return true;
             } else {
                 return false;
             }
        } return true;
    }
    /**
     * Custom Validation Rule
     * Checks to see if the routing number entered passes Mod 10 (LUHN)
     * @param numeric $routingNumber
     * @return boolean
     * @todo cake core validation now supports LUHN,  maybe this should be 
     * deprecated soon
     */
    function checkDepositRoutingNumber($routingNumber = 0) {
        $routingNumber = preg_replace('[\D]', '', $routingNumber['depository_routing_number']); //only digits
        if (strlen($routingNumber) != 9) {
            return false;
        }

        $checkSum = 0;
        for ($i = 0, $j = strlen($routingNumber); $i < $j; $i+= 3) {
            //loop through routingNumber character by character
            $checkSum += ($routingNumber[$i] * 3);
            $checkSum += ($routingNumber[$i + 1] * 7);
            $checkSum += ($routingNumber[$i + 2]);
        }

        if ($checkSum != 0 and ($checkSum % 10) == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Custom Validation Rule
     * Checks to see if the routing number entered passes Mod 10 (LUHN)
     * @param numeric $routingNumber
     * @return boolean
     * @todo cake core validation now supports LUHN,  maybe this should be 
     * deprecated soon
     */
    function checkFeesRoutingNumber($routingNumber = 0) {

        $routingNumber = preg_replace('[\D]', '', $routingNumber['fees_routing_number']); //only digits
        if (strlen($routingNumber) != 9) {
            return false;
        }

        $checkSum = 0;
        for ($i = 0, $j = strlen($routingNumber); $i < $j; $i+= 3) {
            //loop through routingNumber character by character
            $checkSum += ($routingNumber[$i] * 3);
            $checkSum += ($routingNumber[$i + 1] * 7);
            $checkSum += ($routingNumber[$i + 2]);
        }

        if ($checkSum != 0 and ($checkSum % 10) == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Create the CSV file to email to data entry
     * @param array $data
     * @param varchar $multipassMerchantId
     * @return mixed (temporary file location)
     */
    function multipassCsv($data, $multipassMerchantId) {
        //create header array
        $rows = array(
            array(__('merchant_id'), __('dba_business_name'),
                __('legal_business_name'), __('mailing_address'),
                __('mailing_city'), __('mailing_state'),
                __('mailing_zip'), __('location_address'),
                __('location_city'), __('location_state'),
                __('location_zip'), __('location_phone'),
                __('location_fax'), __('federal_taxid'),
                __('corp_contact_name'), __('customer_svc_phone'),
                __('average_ticket'), __('monthly_volume')
            )
        );
        //create body array
        $row = array();
        $row['merchant_id'] = $multipassMerchantId;
        $row['dba_business_name'] = $data['Application']['dba_business_name'];
        $row['legal_business_name'] = $data['Application']['legal_business_name'];
        $row['mailing_address'] = $data['Application']['mailing_address'];
        $row['mailing_city'] = $data['Application']['mailing_city'];
        $row['mailing_state'] = $data['Application']['mailing_state'];
        $row['mailing_zip'] = $data['Application']['mailing_zip'];
        $row['location_address'] = $data['Application']['location_address'];
        $row['location_city'] = $data['Application']['location_city'];
        $row['location_state'] = $data['Application']['location_state'];
        $row['location_zip'] = $data['Application']['location_zip'];
        $row['location_phone'] = $data['Application']['location_phone'];
        $row['location_fax'] = $data['Application']['location_fax'];
        $row['federal_taxid'] = $data['Application']['federal_taxid'];
        $row['corp_contact_name'] = $data['Application']['corp_contact_name'];
        $row['customer_svc_phone'] = $data['Application']['customer_svc_phone'];
        $row['average_ticket'] = $data['Application']['average_ticket'];
        $row['monthly_volume'] = $data['Application']['monthly_volume'];
        $rows[] = $row;

        //set location tmp location for saving file
        $filepath = '/tmp/multipass_' . $data['Application']['dba_business_name'] . '.csv';
        $fp = fopen($filepath, 'w');

        //loop through to create the csv file
        foreach ($rows as $row) {
            fputcsv($fp, $row, $delimiter = ',', $enclosure = '"');
        }
        fclose($fp);
        //return the file location so that it can be attached to an email
        return($filepath);
    }

    /**
     * update the modified date stamp any time data changes
     * @param type $data
     * @param type $validate
     * @param type $fieldList
     * @return type
     */
    function save($data = null, $validate = true, $fieldList = array()) {
        //clear modified field value before each save
        if (isset($this->data) && isset($this->data[$this->name]))
            unset($this->data[$this->name]['modified']);
        if (isset($data) && isset($data[$this->name]))
            unset($data[$this->name]['modified']);
        return parent::save($data, $validate, $fieldList);
    }
    
//    public function formatPhone($data) {
//        $phoneNumbers = Set::classicExtract($data, '')
//    }
    /**
     * Strip commas from the volume field before it goes through validation
     * @param type $options
     */
    public function beforeValidate($options = array()) {
        //Strip non-numeric characters
        if (isset($this->data[$this->alias]['monthly_volume'])) {
            $this->_stripNonNumeric();
        }
        
    }

    private function _stripNonNumeric() {
            $volume = $this->data[$this->alias]['monthly_volume'];
            $average = $this->data[$this->alias]['average_ticket'];
            $high = $this->data[$this->alias]['highest_ticket'];
            $this->data[$this->alias]['monthly_volume'] = preg_replace("/[^0-9.]/", "", $volume);
            $this->data[$this->alias]['average_ticket'] = preg_replace("/[^0-9.]/", "", $average);
            $this->data[$this->alias]['highest_ticket'] = preg_replace("/[^0-9.]/", "", $high);
    }
    
    public function paginationRules(){
	    $paginateArray = array(
		'limit' => 50,
		'order' => array('Application.id' => 'desc'),
		'fields' => array(
		    'Application.id',
		    'Application.user_id',
		    'Application.status',
		    'Application.legal_business_name',
		    'Application.corp_contact_name',
		    'Application.dba_business_name',
		    'Application.business_type',
		    'Application.modified',
		    'Application.hash',
		    'Application.owner1_email',
		    'Application.owner2_email'
		    ),
		'contain' => array(
		    'EmailTimeline' => array(
			'fields' => array('EmailTimeline.id'),
		    ),
		    'User' => array(
			'fields' => array('User.id','User.email'),
		    ),
		    'Coversheet' => array(
			'fields' => array('Coversheet.id'),
		    ),
		),
	    );
	    return $paginateArray;
    }
    /**
     * massage data prior to saving
     * @param array $options
     * @return boolean
     */
    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        //when FireSpring/PaymentSpring hits the REST API copy location data to corp data
        if ($this->data[$this->alias]['user_id'] === User::FIRE_SPRING && $this->data[$this->alias]['api'] === true) {
            $this->data[$this->alias]['location_address'] = $this->data[$this->alias]['mailing_address'];
            $this->data[$this->alias]['location_city'] = $this->data[$this->alias]['mailing_city'];
            $this->data[$this->alias]['location_state'] = $this->data[$this->alias]['mailing_state'];
            $this->data[$this->alias]['location_zip'] = $this->data[$this->alias]['mailing_zip'];
            $this->data[$this->alias]['location_phone'] = $this->data[$this->alias]['mailing_phone'];
            $this->data[$this->alias]['location_fax'] = $this->data[$this->alias]['mailing_fax'];
            $this->data[$this->alias]['loc_contact_name'] = $this->data[$this->alias]['corp_contact_name'];
            $this->data[$this->alias]['loc_contact_name_title'] = $this->data[$this->alias]['corp_contact_name_title'];
            $this->data[$this->alias]['location_email'] = $this->data[$this->alias]['corporate_email'];
            $this->data[$this->alias]['fees_routing_number'] = $this->data[$this->alias]['depository_routing_number'];
            $this->data[$this->alias]['fees_account_number'] = $this->data[$this->alias]['depository_account_number'];
            $this->data[$this->alias]['owner1_fullname'] = $this->data[$this->alias]['corp_contact_name'];
            $this->data[$this->alias]['owner1_title'] = $this->data[$this->alias]['corp_contact_name_title'];
            $this->data[$this->alias]['owner1_address'] = $this->data[$this->alias]['mailing_address'];
            $this->data[$this->alias]['owner1_city'] = $this->data[$this->alias]['mailing_city'];
            $this->data[$this->alias]['owner1_state'] = $this->data[$this->alias]['mailing_state'];
            $this->data[$this->alias]['owner1_zip'] = $this->data[$this->alias]['mailing_zip'];
            $this->data[$this->alias]['owner1_phone'] = $this->data[$this->alias]['mailing_phone'];
            $this->data[$this->alias]['owner1_fax'] = $this->data[$this->alias]['mailing_fax'];
            $this->data[$this->alias]['owner1_email'] = $this->data[$this->alias]['corporate_email'];
            $this->data[$this->alias]['highest_ticket'] = $this->data[$this->alias]['average_ticket'] * 10;
        }
        if(isset($this->data[$this->alias]['location_type']) && $this->data[$this->alias]['location_type'] != 'other') {
            $this->data[$this->alias]['location_type_other'] = null;
        }
        if(isset($this->data[$this->alias]['term1_use_autoclose']) && ($this->data[$this->alias]['term1_use_autoclose'] == 'no' || $this->data[$this->alias]['term1_use_autoclose'] == null)) {
            $this->data[$this->alias]['term1_what_time'] = null;
        }
        if(isset($this->data[$this->alias]['term1_use_autoclose']) && $this->data[$this->alias]['term1_use_autoclose'] == 'yes') {
            $this->data[$this->alias]['term1_what_time'] = $this->data[$this->alias]['term1_what_time']['hour'] . ":" . $this->data[$this->alias]['term1_what_time']['min'];
        }
        if(isset($this->data[$this->alias]['term2_use_autoclose']) && ($this->data[$this->alias]['term2_use_autoclose'] == 'no' || $this->data[$this->alias]['term2_use_autoclose'] == null)) {
            $this->data[$this->alias]['term2_what_time'] = null;
        }
        if(isset($this->data[$this->alias]['term2_use_autoclose']) && $this->data[$this->alias]['term2_use_autoclose'] == 'yes') {
            $this->data[$this->alias]['term2_what_time'] = $this->data[$this->alias]['term2_what_time']['hour'] . ":" . $this->data[$this->alias]['term2_what_time']['min'];
        }
                //Strip non-numeric characters
        if (isset($this->data[$this->alias]['monthly_volume'])) {
            $this->_stripNonNumeric();
        }
        return true;
    }

    /**
     * Set up Model Relationships
     * @var array 
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasMany = array(
        'EmailTimeline' => array(
            'className' => 'EmailTimeline',
            'foreignKey' => 'app_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => 'EmailTimeline.date DESC',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    public $hasOne = array(
        'Merchant',
        'Coversheet' => array(
	    'className' => 'Coversheet',
	    'foreignKey' => 'onlineapp_application_id'
	),
        'Multipass' => array(
            'className' => 'Multipass',
            'foreignKey' => 'application_id'
        )
    );

}

?>