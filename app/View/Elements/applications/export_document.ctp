"MID","Owner Type - Corp","Owner Type - Sole Prop","Owner Type - LLC","Owner Type - Partnership","Owner Type - Non Profit","Owner Type - Other","CorpStatus","CorpName","CorpAddress","CorpCity","CorpState","CorpZip","CorpPhone","CorpFax","TaxID","CorpContact","Title","EMail","Existing Axia Merchant - Yes","Existing Axia Merchant - No","Current MID","DBA","Address","City","State","Zip","PhoneNum","FaxNum","Customer Service Phone","WebAddress","Contact","LocTitle","OpenDate","Ownership Length","General Comments","OwnerEquity","Principal","Owner1Title","Owner1Address","Owner1City","Owner1State","Owner1Zip","Owner1Phone","Owner1Fax","Owner1Email","OwnerSSN","Owner1DOB","Owner2Equity","Owner2Name","Owner2Title","Owner2Address","Owner2City","Owner2State","Owner2Zip","Owner2Phone","Owner2Fax","Owner2Email","Owner2SSN","Owner2DOB","TradeRefName","TradeRefContact","TradeRefPhone1","TradeRefAccount1","TradeRefCity1","TradeRefSt1","TradeRef2","TradeRefContact2","TradeRefPhone2","TradeRefAccount2","TradeRefCity2","TradeRefSt2","Retail","Restaurant","Lodging","MOTO","Internet","Grocery","Products Services Sold","Return Policy","Days Until Product Delivery","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec","MonthlyVol","AvgTicket","MaxSalesAmt","Previous Processor","Card Present Swiped","Card Present Imprint","Card Not Present - Keyed","Card Not Present - Internet","Direct to Consumer","Direct to Business","Direct to Government","BankName","BankContact","BankPhone","BankAddress","BankCity","BankState","BankZip","RoutingNum","AccountNum","FeesRoutingNum","FeesAccountNum","Check Box 8","Check Box 9","Check Box 14","Check Box 13","Where is inventory housed","Customer Service","Product Shipment","Handling of Returns","Cardholder Billing","By what methods to sales take place ie Internet trade shows etc ","Monthly Recurring","QUARTERLY","SEMIANUALLY","ANNUALLY","Text83","Text86","Text84","Text87","Text88","Text85","AEExist","AENew","AmexNum","DiscNew","Referral1","Referral2","Referral3","QTY1","Terminal1","Axia","Merchant","PinPad1","AVS","Server","Invoice","Tips","Purchasing Cards","Autoclose","Autoclose Time 1","QTY2","Terminal2","Axia_3","Merchant_3","PinPad2","AVS_2","Server_2","Invoice_2","Tips_2","Purchasing Cards_2","Autoclose_2","Autoclose Time 2","Retail Store","Office","Industrial","Residence","Trade","Site Inspection Other","Owns","Leases","Landlord","Landlord Phone","DiscRate1","Rate Structure","Downgrades","CreditAppFee","TranFee","StatementFee","AnnualFee","CreditEquipmentFee","AmexPerItem","MinimumFee","ChargebackFee","CreditExpediteFee","VoiceAuth","DebitMonthlyAccessFee","CreditReprogramFee","MobileTran","EBTStmtFee","CreditVirtualTrainingFee","GatewayFee","CreditMobileSetupFee","MobileFee","Tax","Total","DebitTranFee","EBTDiscRate","DebitDiscountRate","EBTTranFee","ContractorID","Amex Discount Rate","Check Box150","Check Box151","Check Box158","Check Box159","Check Box154","Check Box155","Check Box156","Check Box157","Check Box158","Check Box159","Check Box160","Check Box161","Check Box162","Check Box163","oaID","api","aggregated"
<?php
    if ($data['Multipass']['merchant_id']) {
        echo '"' . $data['Multipass']['merchant_id'] . '",';
    } else {
        echo '"",';
    }
    echo ($data['Application']['ownership_type'] == 'corporation' ? '"Yes",' : '"Off",');
    echo ($data['Application']['ownership_type'] == 'sole prop' ? '"Yes",' : '"Off",');
    echo ($data['Application']['ownership_type'] == 'llc' ? '"Yes",' : '"Off",');
    echo ($data['Application']['ownership_type'] == 'partnership' ? '"Yes",' : '"Off",');
    echo ($data['Application']['ownership_type'] == 'non profit' ? '"Yes",' : '"Off",');
    echo ($data['Application']['ownership_type'] == 'other' ? '"Yes",' : '"Off",');
        if ($data['Application']['ownership_type'] == 'corporation'){
            echo '"Corporation",';
        }
        if ($data['Application']['ownership_type'] == 'sole prop'){
             echo '"Sole Prop",';
        }
        if ($data['Application']['ownership_type'] == 'llc'){
            echo '"Limited Liability",';
        }
        if ($data['Application']['ownership_type'] == 'partnership'){
            echo '"Partnership",';
        }
        if ($data['Application']['ownership_type'] == 'non profit'){
            echo '"Exempt",';
        }
        
    echo '"' . $data['Application']['legal_business_name'] . '",';
    echo '"' . $data['Application']['mailing_address'] . '",';
    echo '"' . $data['Application']['mailing_city'] . '",';
    echo '"' . $data['Application']['mailing_state'] . '",';
    echo '"' . $data['Application']['mailing_zip'] . '",';
    echo '"' . $data['Application']['mailing_phone'] . '",';
    echo '"' . $data['Application']['mailing_fax'] . '",';
    echo '"' . $data['Application']['federal_taxid'] . '",';
    echo '"' . $data['Application']['corp_contact_name'] . '",';
    echo '"' . $data['Application']['corp_contact_name_title'] . '",';
    echo '"' . $data['Application']['corporate_email'] . '",';
    if ($data['Application']['existing_axia_merchant'] == 'yes') echo '"Yes","Off",';
    else echo '"Off","Yes",';
    echo '"' . $data['Application']['current_mid_number'] . '",';
    echo '"' . $data['Application']['dba_business_name'] . '",';
    echo '"' . $data['Application']['location_address'] . '",';
    echo '"' . $data['Application']['location_city'] . '",';
    echo '"' . $data['Application']['location_state'] . '",';
    echo '"' . $data['Application']['location_zip'] . '",';
    echo '"' . $data['Application']['location_phone'] . '",';
    echo '"' . $data['Application']['location_fax'] . '",';
    echo '"' . $data['Application']['customer_svc_phone'] . '",';
    echo '"' . $data['Application']['website'] . '",';
    echo '"' . $data['Application']['loc_contact_name'] . '",';
    echo '"' . $data['Application']['loc_contact_name_title'] . '",';
    echo '"' . $data['Application']['bus_open_date'] . '",';
    echo '"' . $data['Application']['length_current_ownership'] . '",';
    echo '"' . $data['Application']['general_comments'] . '",';
    
    /* -------------------------------- */
    
    echo '"' . $data['Application']['owner1_percentage'] . '",';
    echo '"' . $data['Application']['owner1_fullname'] . '",';
    echo '"' . $data['Application']['owner1_title'] . '",';
    echo '"' . $data['Application']['owner1_address'] . '",';
    echo '"' . $data['Application']['owner1_city'] . '",';
    echo '"' . $data['Application']['owner1_state'] . '",';
    echo '"' . $data['Application']['owner1_zip'] . '",';
    echo '"' . $data['Application']['owner1_phone'] . '",';
    echo '"' . $data['Application']['owner1_fax'] . '",';
    echo '"' . $data['Application']['owner1_email'] . '",';
    echo '"' . $data['Application']['owner1_ssn'] . '",';
    echo '"' . $data['Application']['owner1_dob'] . '",';
    echo '"' . $data['Application']['owner2_percentage'] . '",';
    echo '"' . $data['Application']['owner2_fullname'] . '",';
    echo '"' . $data['Application']['owner2_title'] . '",';
    echo '"' . $data['Application']['owner2_address'] . '",';
    echo '"' . $data['Application']['owner2_city'] . '",';
    echo '"' . $data['Application']['owner2_state'] . '",';
    echo '"' . $data['Application']['owner2_zip'] . '",';
    echo '"' . $data['Application']['owner2_phone'] . '",';
    echo '"' . $data['Application']['owner2_fax'] . '",';
    echo '"' . $data['Application']['owner2_email'] . '",';
    echo '"' . $data['Application']['owner2_ssn'] . '",';
    echo '"' . $data['Application']['owner2_dob'] . '",';
    echo '"' . $data['Application']['trade1_business_name'] . '",';
    echo '"' . $data['Application']['trade1_contact_person'] . '",';
    echo '"' . $data['Application']['trade1_phone'] . '",';
    echo '"' . $data['Application']['trade1_acct_num'] . '",';
    echo '"' . $data['Application']['trade1_city'] . '",';
    echo '"' . $data['Application']['trade1_state'] . '",';
    echo '"' . $data['Application']['trade2_business_name'] . '",';
    echo '"' . $data['Application']['trade2_contact_person'] . '",';
    echo '"' . $data['Application']['trade2_phone'] . '",';
    echo '"' . $data['Application']['trade2_acct_num'] . '",';
    echo '"' . $data['Application']['trade2_city'] . '",';
    echo '"' . $data['Application']['trade2_state'] . '",';
    
    /* -------------------------------- */
    
    echo ($data['Application']['business_type'] == 'retail' ? '"On",' : '"Off",');
    echo ($data['Application']['business_type'] == 'restaurant' ? '"On",' : '"Off",');
    echo ($data['Application']['business_type'] == 'lodging' ? '"On",' : '"Off",');
    echo ($data['Application']['business_type'] == 'MOTO' ? '"On",' : '"Off",');
    echo ($data['Application']['business_type'] == 'internet' ? '"On",' : '"Off",');
    echo ($data['Application']['business_type'] == 'grocery' ? '"On",' : '"Off",');
    echo '"' . $data['Application']['products_services_sold'] . '",';
    echo '"' . $data['Application']['return_policy'] . '",';
    echo '"' . $data['Application']['days_until_prod_delivery'] . '",';
    echo ($data['Application']['high_volume_january'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_february'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_march'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_april'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_may'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_june'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_july'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_august'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_september'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_october'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_november'] ? '"On",' : '"Off",');
    echo ($data['Application']['high_volume_december'] ? '"On",' : '"Off",');
    echo '"' . $data['Application']['monthly_volume'] . '",';
    echo '"' . $data['Application']['average_ticket'] . '",';
    echo '"' . $data['Application']['highest_ticket'] . '",';
    echo '"' . $data['Application']['current_processor'] . '",';
    echo '"' . $data['Application']['card_present_swiped'] . '",';
    echo '"' . $data['Application']['card_present_imprint'] . '",';
    echo '"' . $data['Application']['card_not_present_keyed'] . '",';
    echo '"' . $data['Application']['card_not_present_internet'] . '",';
    echo '"' . $data['Application']['direct_to_customer'] . '",';
    echo '"' . $data['Application']['direct_to_business'] . '",';
    echo '"' . $data['Application']['direct_to_govt'] . '",';
    echo '"' . $data['Application']['bank_name'] . '",';
    echo '"' . $data['Application']['bank_contact_name'] . '",';
    echo '"' . $data['Application']['bank_phone'] . '",';
    echo '"' . $data['Application']['bank_address'] . '",';
    echo '"' . $data['Application']['bank_city'] . '",';
    echo '"' . $data['Application']['bank_state'] . '",';
    echo '"' . $data['Application']['bank_zip'] . '",';
    echo '"' . $data['Application']['depository_routing_number'] . '",';
    echo '"' . $data['Application']['depository_account_number'] . '",';
    echo '"' . $data['Application']['fees_routing_number'] . '",';
    echo '"' . $data['Application']['fees_account_number'] . '",';
    
    /* -------------------------------- */
    
    if ($data['Application']['moto_storefront_location'] == 'yes') echo '"On","Off",';
    elseif ($data['Application']['moto_storefront_location'] == 'no') echo '"Off","On",';
    else echo '"","",';
    if ($data['Application']['moto_orders_at_location'] == 'yes') echo '"On","Off",';
    elseif ($data['Application']['moto_orders_at_location'] == 'no') echo '"Off","On",';
    else echo '"","",';
    echo '"' . $data['Application']['moto_inventory_housed'] . '",';
    echo ($data['Application']['moto_outsourced_customer_service'] ? '"On",' : '"Off",');
    echo ($data['Application']['moto_outsourced_shipment'] ? '"On",' : '"Off",');
    echo ($data['Application']['moto_outsourced_returns'] ? '"On",' : '"Off",');
    echo ($data['Application']['moto_outsourced_billing'] ? '"On",' : '"Off",');
    echo '"' . $data['Application']['moto_sales_methods'] . '",';
    echo ($data['Application']['moto_billing_monthly'] ? '"On",' : '"Off",');
    echo ($data['Application']['moto_billing_quarterly'] ? '"On",' : '"Off",');
    echo ($data['Application']['moto_billing_semiannually'] ? '"On",' : '"Off",');
    echo ($data['Application']['moto_billing_annually'] ? '"On",' : '"Off",');
    echo '"' . $data['Application']['moto_policy_full_up_front'] . '",';
    echo '"' . $data['Application']['moto_policy_days_until_delivery'] . '",';
    echo '"' . $data['Application']['moto_policy_partial_up_front'] . '",';
    echo '"' . $data['Application']['moto_policy_partial_with'] . '",';
    echo '"' . $data['Application']['moto_policy_days_until_final'] . '",';
    echo '"' . $data['Application']['moto_policy_after'] . '",';
    
    /* -------------------------------- */
    
    echo ($data['Application']['currently_accept_amex'] == 'yes' ? '"On",' : '"Off",');
    echo ($data['Application']['want_to_accept_amex'] == 'yes' ? '"On",' : '"Off",');
    echo '"' . $data['Application']['existing_se_num'] . '",';
    echo ($data['Application']['want_to_accept_discover'] == 'yes' ? '"On",' : '"Off",');
    echo '"' . $data['Application']['referral1_business'] . ' / ' . $data['Application']['referral1_owner_officer'] . ' / ' . $data['Application']['referral1_phone'] . '",';
    echo '"' . $data['Application']['referral2_business'] . ' / ' . $data['Application']['referral2_owner_officer'] . ' / ' . $data['Application']['referral2_phone'] . '",';
    echo '"' . $data['Application']['referral3_business'] . ' / ' . $data['Application']['referral3_owner_officer'] . ' / ' . $data['Application']['referral3_phone'] . '",';
    echo '"' . $data['Application']['term1_quantity'] . '",';
    echo '"' . $data['Application']['term1_type'] . '",';
    echo ($data['Application']['term1_provider'] == 'axia' ? '"On",' : '"Off",');
    echo ($data['Application']['term1_provider'] == 'merchant' ? '"On",' : '"Off",');
    echo '"' . $data['Application']['term1_pin_pad_type'] . '",';
    echo ($data['Application']['term1_programming_avs'] ? '"On",' : '"Off",');
    echo ($data['Application']['term1_programming_server_nums'] ? '"On",' : '"Off",');
    echo ($data['Application']['term1_programming_invoice_num'] ? '"On",' : '"Off",');
    echo ($data['Application']['term1_programming_tips'] ? '"On",' : '"Off",');
    echo ($data['Application']['term1_programming_purchasing_cards'] ? '"On",' : '"Off",');
    echo ($data['Application']['term1_use_autoclose'] == 'yes' ? '"On",' : '"Off",');
    echo '"' . $data['Application']['term1_what_time'] . '",';
    echo '"' . $data['Application']['term2_quantity'] . '",';
    echo '"' . $data['Application']['term2_type'] . '",';
    echo ($data['Application']['term2_provider'] == 'axia' ? '"On",' : '"Off",');
    echo ($data['Application']['term2_provider'] == 'merchant' ? '"On",' : '"Off",');
    echo '"' . $data['Application']['term2_pin_pad_type'] . '",';
    echo ($data['Application']['term2_programming_avs'] ? '"On",' : '"Off",');
    echo ($data['Application']['term2_programming_server_nums'] ? '"On",' : '"Off",');
    echo ($data['Application']['term2_programming_invoice_num'] ? '"On",' : '"Off",');
    echo ($data['Application']['term2_programming_tips'] ? '"On",' : '"Off",');
    echo ($data['Application']['term2_programming_purchasing_cards'] ? '"On",' : '"Off",');
    echo ($data['Application']['term2_use_autoclose'] == 'yes' ? '"On",' : '"Off",');
    echo '"' . $data['Application']['term2_what_time'] . '",';
    
    /* -------------------------------- */
    
    echo ($data['Application']['location_type'] == 'retail store' ? '"On",' : '"Off",');
    echo ($data['Application']['location_type'] == 'office' ? '"On",' : '"Off",');
    echo ($data['Application']['location_type'] == 'industrial' ? '"On",' : '"Off",');
    echo ($data['Application']['location_type'] == 'residence' ? '"On",' : '"Off",');
    echo ($data['Application']['location_type'] == 'trade' ? '"On",' : '"Off",');
    echo ($data['Application']['location_type'] == 'other' ? '"On",' : '"Off",');
    echo ($data['Application']['merchant_status'] == 'owns' ? '"On",' : '"Off",');
    echo ($data['Application']['merchant_status'] == 'leases' ? '"On",' : '"Off",');
    echo '"' . $data['Application']['landlord_name'] . '",';
    echo '"' . $data['Application']['landlord_phone'] . '",';
    echo '"' . $data['Application']['fees_rate_discount'] . '",';
    echo '"' . $data['Application']['fees_rate_structure'] . '",';
    echo '"' . $data['Application']['fees_qualification_exemptions'] . '",';
    echo '"' . $data['Application']['fees_startup_application'] . '",';
    echo '"' . $data['Application']['fees_auth_transaction'] . '",';
    echo '"' . $data['Application']['fees_monthly_statement'] . '",';
    echo '"' . $data['Application']['fees_misc_annual_file'] . '",';
    echo '"' . $data['Application']['fees_startup_equipment'] . '",';
    echo '"' . $data['Application']['fees_auth_amex'] . '",';
    echo '"' . $data['Application']['fees_monthly_minimum'] . '",';
    echo '"' . $data['Application']['fees_misc_chargeback'] . '",';
    echo '"' . $data['Application']['fees_startup_expedite'] . '",';
    echo '"' . $data['Application']['fees_auth_aru_voice'] . '",';
    echo '"' . $data['Application']['fees_monthly_debit_access'] . '",';
    echo '"' . $data['Application']['fees_startup_reprogramming'] . '",';
    echo '"' . $data['Application']['fees_auth_wireless'] . '",';
    echo '"' . $data['Application']['fees_monthly_ebt'] . '",';
    echo '"' . $data['Application']['fees_startup_training'] . '",';
    echo '"' . $data['Application']['fees_monthly_gateway_access'] . '",';
    echo '"' . $data['Application']['fees_startup_wireless_activation'] . '",';
    echo '"' . $data['Application']['fees_monthly_wireless_access'] . '",';
    echo '"' . $data['Application']['fees_startup_tax'] . '",';
    echo '"' . $data['Application']['fees_startup_total'] . '",';
    echo '"' . $data['Application']['fees_pin_debit_auth'] . '",';
    echo '"' . $data['Application']['fees_ebt_discount'] . '",';
    echo '"' . $data['Application']['fees_pin_debit_discount'] . '",';
    echo '"' . $data['Application']['fees_ebt_auth'] . '",';
    
    /* -------------------------------- */
    
    echo '"' . $data['Application']['rep_contractor_name'] . '",';
    echo '"' . $data['Application']['rep_amex_discount_rate'] . '",';
    echo ($data['Application']['rep_business_legitimate'] == 'yes' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_business_legitimate'] == 'no' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_photo_included'] == 'yes' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_photo_included'] == 'no' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_inventory_sufficient'] == 'yes' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_inventory_sufficient'] == 'no' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_goods_delivered'] == 'yes' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_goods_delivered'] == 'no' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_bus_open_operating'] == 'yes' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_bus_open_operating'] == 'no' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_visa_mc_decals_visible'] == 'yes' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_visa_mc_decals_visible'] == 'no' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_mail_tel_activity'] == 'yes' ? '"On",' : '"Off",');
    echo ($data['Application']['rep_mail_tel_activity'] == 'no' ? '"On",' : '"Off",');
    echo '"' . $data['Application']['id'] . '",';
    echo '"' . $data['Application']['api'] . '",';
    if ($data['Multipass']['merchant_id']) {
         echo '"true",';
    }
?>
