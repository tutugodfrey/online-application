<?php
$hash = md5(String::uuid());
$guid = Security::hash(String::uuid(),'sha1', true);
$config = array(
    'Application' => array(
                        'status' => 'saved',
                        'hash' => $hash,
                        'guid' => $guid,
                        //Step 1
                        //'corporate_email' => $this->request->data['corporate_email'],
                        'existing_axia_merchant' => 'no',
                        'location_type' => 'other',
                        'location_type_other' => 'Internet',
                        //Step 2
                        'business_type' => 'internet',
                        'current_processor' => 'no',
                        'card_present_swiped' => '0',
                        'card_present_imprint' => '0',
                        'card_not_present_keyed' => '0',
                        'card_not_present_internet' => '100',
                        'method_total' => '100',
                        'direct_to_customer' => '100',
                        'direct_to_business' => '0',
                        'direct_to_govt' => '0',
                        'products_total' => '100',
                        'trade1_business_name' => 'Hooza',
                        'trade1_contact_person' => 'Hooza',
                        'trade1_phone' => 'Hooza',
                        'trade1_acct_num' => '14',
                        'trade1_city' => 'Santa Ynez',
                        'trade1_state' => 'CA',
                        //get acct number from hooza
                        //Step 4
                        'currently_accept_amex' => 'no',
                        'want_to_accept_discover' => 'yes',
                        'term1_quantity' => '1',
                        'term1_type' => 'Axia Gateway',
                        'term1_provider' => 'axia',
                        'term1_use_autoclose' => 'yes',
                        'term1_what_time' => '23:30',
                        'term1_programming_avs' => 'f',
                        'term1_programming_server_nums' => 'f',
                        'term1_programming_tips' => 'f',
                        'term1_programming_invoice_num' => 'f',
                        'term1_programming_purchasing_cards' => 'f',
                        'term1_accept_debit' => 'no',
                        //Step 5
                        'rep_contractor_name' => 'Hooza',
                        'fees_rate_discount' => 'Tiered',
                        'fees_rate_structure' => 'Hooza Pricing Tiers',
                        'fees_qualification_exemptions' => 'Monthly Volume: exceeds $1,000, rate = 2.75%; exceeds $3,000, rate = 2.40%; exceeds $10,000, rate = 2.19%',
                        'fees_startup_application' => '0.00',
                        'fees_auth_transaction' => '0.29',
                        'fees_monthly_statement' => '0.00',
                        'fees_misc_annual_file' => '0.00',
                        'fees_startup_equipment' => '0.00',
                        'fees_auth_amex' => '0.29',
                        'fees_monthly_minimum' => '0.00',
                        'fees_misc_chargeback' => '15.00',
                        'fees_startup_expedite' => '0.00',
                        'fees_auth_aru_voice' => '0.55',
                        'fees_monthly_debit_access' => '0.00',
                        'fees_startup_reprogramming' => '0.00',
                        'fees_auth_wireless' => '0.00',
                        'fees_monthly_ebt' => '0.00',
                        'fees_startup_training' => '0.00',
                        'fees_monthly_gateway_access' => '25.00',
                        'fees_startup_wireless_activation' => '0.00',
                        'fees_monthly_wireless_access' => '0.00',
                        'fees_startup_total' => '0.00',
                        'fees_pin_debit_auth' => '0.00',
                        'fees_ebt_discount' => '0.00',
                        'fees_pin_debit_discount' => '0.00',
                        'fees_ebt_auth' => '0.00',
                        'rep_contractor_name' => 'Hooza',
                        'rep_discount_paid' => 'monthly',
                        //'rep_amex_discount_rate' => '3.50%',
                        'rep_mail_tel_activity' => 'yes',
                        'api' => true
                    )
    );
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
