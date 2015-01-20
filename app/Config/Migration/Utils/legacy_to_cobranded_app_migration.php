#!/usr/bin/php

<?php

    // get legacy application records
    // determine appropriate template
    // create cobranded application - use same id from legacy app so that foreign keys in other tables work
    // create cobranded application values associated to previously created application

    $applicationMap = array(
	'ownership_type'                        	=>		'OwnerType-:Corp,SoleProp,LLC,Partnership,NonProfit,Other', 
	'legal_business_name'                   	=>		'CorpName', 
	'mailing_address'                       	=>		'CorpAddress',
	'mailing_city'                          	=>		'CorpCity', 
	'mailing_state'                         	=>		'CorpState',
	'mailing_zip'                           	=>		'CorpZip', 
	'mailing_phone'                         	=>		'CorpPhone',
	'mailing_fax'                           	=>		'CorpFax',
	'federal_taxid'                         	=>		'TaxID', 
	'corp_contact_name'                     	=>		'CorpContact', 
	'corp_contact_name_title'              		=>		'Title',
	'corporate_email'                       	=>		'EMail', 
	'dba_business_name'                     	=>		'DBA', 
	'location_address'                      	=>		'Address', 
	'location_city'                         	=>		'City', 
	'location_state'                        	=>		'State', 
	'location_zip'                          	=>		'Zip', 
	'location_phone'                        	=>		'PhoneNum', 
	'location_fax'                          	=>		'FaxNum', 
	'customer_svc_phone'                            =>		'Customer Service Phone',
	'loc_contact_name'                              =>		'Contact',
	'loc_contact_name_title'                        =>		'LocTitle',
	'location_email'                                =>		'LocEMail',
	'website'      					=>		'WebAddress',                          
	'bus_open_date'                           	=>		'OpenDate',
	'length_current_ownership'                	=>		'Ownership Length',
	'existing_axia_merchant'                  	=>		'ExistingAxiaMerchant-:Yes,No',
	'current_mid_number'                            =>		'Current MID',
	'general_comments'                              =>		'General Comments',
	'location_type'                                 =>		'LocationType-:RetailStore,Industrial,Trade,Office,Residence,SiteInspectionOther',
	'location_type_other'                           =>		'LocationTypeOther',
	'merchant_status'                               =>		'MerchantOwns/Leases-:Owns,Leases',
	'landlord_name'                          	=>		'Landlord',
	'landlord_phone'                         	=>		'Landlord Phone',
	'business_type'                                 =>		'BusinessType-:Retail,Restaurant,Lodging,MOTO,Internet,Grocery',
	'products_services_sold'                        =>		'Products Services Sold',
	'return_policy'                                 =>		'Return Policy',
	'days_until_prod_delivery'                      =>		'Days Until Product Delivery',
	'monthly_volume'                                =>		'MonthlyVol',
	'average_ticket'                                =>		'AvgTicket',
	'highest_ticket'                                =>		'MaxSalesAmt',
	'current_processor'                             =>		'Previous Processor',
	'card_present_swiped'                           =>		'MethodofSales-CardPresentSwiped',
	'card_present_imprint'                          =>		'MethodofSales-CardPresentImprint',
	'card_not_present_keyed'                        =>		'MethodofSales-CardNotPresent-Keyed',
	'card_not_present_internet'                     =>		'MethodofSales-CardNotPresent-Internet',
	'direct_to_customer'                            =>		'%OfProductSold-DirectToCustomer',
	'direct_to_business'                            =>		'%OfProductSold-DirectToBusiness',
	'direct_to_govt'                                =>		'%OfProductSold-DirectToGovernment',
	'high_volume_january'                    	=>		'Jan',
	'high_volume_february'                   	=>		'Feb',
	'high_volume_march'                      	=>		'Mar',
	'high_volume_april'                      	=>		'Apr',
	'high_volume_may'                        	=>		'May',
	'high_volume_june'                       	=>		'Jun',
	'high_volume_july'                       	=>		'Jul',
	'high_volume_august'                     	=>		'Aug',
	'high_volume_september'                  	=>		'Sep',
	'high_volume_october'                    	=>		'Oct',
	'high_volume_november'                   	=>		'Nov',
	'high_volume_december'                   	=>		'Dec',
	'moto_storefront_location'                      =>		'StoreFrontLoc-:Yes,No',
	'moto_orders_at_location'                       =>		'OrdersProcAtBusinessLoc-:Yes,No',
	'moto_inventory_housed'                         =>		'Where is inventory housed',
	'moto_outsourced_customer_service'              =>		'Customer Service',
	'moto_outsourced_shipment'                      =>		'Product Shipment',
	'moto_outsourced_returns'                       =>		'Handling of Returns',
	'moto_sales_methods'                            =>		'By what methods to sales take place ie Internet trade shows etc',
	'moto_billing_monthly'                          =>		'Monthly Recurring',
	'moto_billing_quarterly'                        =>		'QUARTERLY',
	'moto_billing_semiannually'                     =>		'SEMIANNUALLY',
	'moto_billing_annually'                         =>		'ANNUALLY',
	'moto_policy_full_up_front'                     =>		'PercentFullPayUpFront',
	'moto_policy_days_until_delivery'               =>		'DaysUntilDelivery',
	'moto_policy_partial_up_front'                  =>		'PercentPartialPayUpFront',
	'moto_policy_partial_with'                      =>		'PercentAndWithin',
	'moto_policy_days_until_final'                  =>		'DaysUntilFinalDelivery',
	'moto_policy_after'                             =>		'PercentPayReceivedAfter',
	'bank_name'                                     =>		'BankName',
	'bank_contact_name'                             =>		'BankContact',
	'bank_phone'                                    =>		'BankPhone',
	'bank_address'                                  =>		'BankAddress',
	'bank_city'                                     =>		'BankCity',
	'bank_state'                                    =>		'BankState',
	'bank_zip'                                      =>		'BankZip',
	'depository_routing_number'                     =>		'RoutingNum',
	'depository_account_number'                     =>		'AccountNum',
	'fees_routing_number'                           =>		'FeesRoutingNum',
	'fees_account_number'                           =>		'FeesAccountNum',
	'trade1_business_name'                          =>		'TradeRef1',
	'trade1_contact_person'                         =>		'TradeRefContact1',
	'trade1_phone'                                  =>		'TradeRefPhone1',
	'trade1_acct_num'                               =>		'TradeRefAccount1',
	'trade1_city'                                   =>		'TradeRefCity1',
	'trade1_state'                                  =>		'TradeRefSt1',
	'trade2_business_name'                          =>		'TradeRef2',
	'trade2_contact_person'                         =>		'TradeRefContact2',
	'trade2_phone'                                  =>		'TradeRefPhone2',
	'trade2_acct_num'                               =>		'TradeRefAccount2',
	'trade2_city'                                   =>		'TradeRefCity2',
	'trade2_state'                                  =>		'TradeRefSt2',
	'currently_accept_amex'                         =>		'DoYouAcceptAE-:Yes,No',
	'existing_se_num'                               =>		'AmexNum',
	'want_to_accept_amex'                           =>		'DoYouWantToAcceptAE-:Yes,No',
	'want_to_accept_discover'                       =>		'DoYouWantToAcceptDisc-:Yes,No',
	'term1_quantity'                                =>		'QTY1',
	'term1_type'                                    =>		'Terminal1',
	'term1_provider'                                =>		'Provider-:Axia,Merchant',
	'term1_use_autoclose'                           =>		'DoYouUseAutoclose-:Yes,No',
	'term1_what_time'                               =>		'Autoclose Time 1',
	'term1_programming_avs'                         =>		'AVS',
	'term1_programming_server_nums'                 =>		'Server',
	'term1_programming_tips'                        =>		'Tips',
	'term1_programming_invoice_num'                 =>		'Invoice',
	'term1_programming_purchasing_cards'            =>		'Purchasing Cards',
	'term1_accept_debit'                            =>		'TermAcceptDebit-:Yes,No',
	'term1_pin_pad_type'                            =>		'PinPad1',
	'term1_pin_pad_qty'                             =>		'QTY - PP1',
	'term2_quantity'                                =>		'QTY2',
	'term2_type'                      		=> 		'Terminal2',
	'term2_provider'                        	=> 		'Provider2-:Axia,Merchant',
	'term2_use_autoclose'                   	=> 		'DoYouUseAutoclose2-:Yes,No',
	'term2_what_time'                       	=> 		'Autoclose Time 2',
	'term2_programming_avs'                 	=> 		'AVS_2',
	'term2_programming_server_nums'         	=> 		'Server_2',
	'term2_programming_tips'                	=> 		'Tips_2',
	'term2_programming_invoice_num'         	=> 		'Invoice_2',
	'term2_programming_purchasing_cards'    	=> 		'Purchasing Cards_2',
	'term2_accept_debit'                    	=> 		'TermAcceptDebit2-:Yes,No',
	'term2_pin_pad_type'                    	=> 		'PinPad2',
	'term2_pin_pad_qty'                     	=> 		'QTY - PP2',
	'owner1_percentage'                     	=> 		'Owner1Equity',
	'owner1_fullname'                       	=> 		'Owner1Name',
	'owner1_title'                          	=>		'Owner1Title',
	'owner1_address'                        	=> 		'Owner1Address',
	'owner1_city'                           	=> 		'Owner1City',
	'owner1_state'                          	=> 		'Owner1State',
	'owner1_zip'                            	=> 		'Owner1Zip',
	'owner1_phone'                          	=> 		'Owner1Phone',
	'owner1_fax'                            	=>		'Owner1Fax', 
	'owner1_email'                          	=>		'Owner1Email', 
	'owner1_ssn'                            	=> 		'OwnerSSN',
	'owner1_dob'                            	=>		'Owner1DOB', 
	'owner2_percentage'                     	=> 		'Owner2Equity',
	'owner2_fullname'                       	=>		'Owner2Name',
	'owner2_title'                          	=> 		'Owner2Title',
	'owner2_address'                        	=> 		'Owner2Address',
	'owner2_city'                           	=> 		'Owner2City',
	'owner2_state'                          	=> 		'Owner2State',
	'owner2_zip'                            	=> 		'Owner2Zip',
	'owner2_phone'                          	=> 		'Owner2Phone',
	'owner2_fax'                            	=> 		'Owner2Fax',
	'owner2_email'                          	=> 		'Owner2Email',
	'owner2_ssn'                            	=>		'Owner2SSN', 
	'owner2_dob'                            	=> 		'Owner2DOB',
	'rep_contractor_name'                   	=> 		'ContractorID',
	'fees_rate_discount'                    	=> 		'DiscRate1',
	'fees_rate_structure'                   	=> 		'Rate Structure-:Interchange Pass Thru,Downgrades At Cost,Cost Plus,Bucketed,Bucketed (Rewards),Simply Swipe It Rates',
	'fees_qualification_exemptions'         	=> 		'Downgrades-:Visa/MC Interchange at Pass Thru,Non-Qualified Transactions at Additional Visa/MC Cost Based on Regulated Check Cards,Non-Qualified Transactions at Additional Visa/MC Cost Based on Qualified Consumer Cards,Non-Qualified Transactions at Additional Visa/MC Cost Based on Non-Regulated Qualified Check Cards,Visa/MC Cost Plus 0.05%,Visa/MC Cost Plus 0.10%,Visa/MC Cost Plus 0.15%,Visa/MC Cost Plus 0.20%,Visa/MC Cost Plus 0.25%,Visa/MC Cost Plus 0.30%,Visa/MC Cost Plus 0.35%,Visa/MC Cost Plus 0.40%,Visa/MC Cost Plus 0.45%,Visa/MC Cost Plus 0.50%,Visa/MC Cost Plus 0.55%,Visa/MC Cost Plus 0.60%,Visa/MC Cost Plus 0.65%,Visa/MC Cost Plus 0.70%,Visa/MC Cost Plus 0.75%,      (SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%::(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%,RATE 2:  0.45%            RATE 3:  1.15% + $0.10             BUS 1:  1.05% + $0.10            BUS 2:  1.95% + $0.10::RATE 2:  0.45%            RATE 3:  1.15% + $0.10             BUS 1:  1.05% + $0.10            BUS 2:  1.95% + $0.10,RATE 2:  0.85%            RATE 3:  1.79% + $0.10             BUS 1:  1.15% + $0.10            BUS 2:  1.75% + $0.10::RATE 2:  0.85%            RATE 3:  1.79% + $0.10             BUS 1:  1.15% + $0.10            BUS 2:  1.75% + $0.10,REWARDS:  0.36%            MID:  0.85%             BUS 1:  1.15% + $0.10               NON:  1.79% + $0.10         ::REWARDS:  0.36%            MID:  0.85%             BUS 1:  1.15% + $0.10               NON:  1.79% + $0.10',
	'fees_startup_application'              	=> 		'CreditAppFee',
	'fees_auth_transaction'                 	=> 		'TranFee',
	'fees_monthly_statement'                	=> 		'StatementFee',
	'fees_misc_annual_file'                 	=> 		'AnnualFee',
	'fees_startup_equipment'                	=>		'CreditEquipmentFee',
	'fees_auth_amex'                        	=> 		'AmexPerItem',
	'fees_monthly_minimum'                  	=> 		'MinimumFee',
	'fees_misc_chargeback'                  	=> 		'ChargebackFee',
	'fees_startup_expedite'                 	=> 		'CreditExpediteFee',
	'fees_auth_aru_voice'                   	=> 		'VoiceAuth',
	'fees_monthly_debit_access'             	=> 		'DebitMonthlyAccessFee',
	'fees_startup_reprogramming'            	=> 		'CreditReprogramFee',
	'fees_auth_wireless'                    	=> 		'MobileTran',
	'fees_monthly_ebt'                      	=> 		'EBTStmtFee',
	'fees_startup_training'                 	=> 		'CreditVirtualTrainingFee',
	'fees_monthly_gateway_access'           	=> 		'GatewayFee',
	'fees_startup_wireless_activation'      	=> 		'CreditMobileSetupFee',
	'fees_monthly_wireless_access'          	=> 		'MobileFee',
	'fees_startup_tax'                      	=> 		'Tax',
	'fees_startup_total'                    	=> 		'Total',
	'fees_pin_debit_auth'                   	=> 		'DebitTranFee',
	'fees_ebt_discount'                     	=> 		'EBTDiscRate',
	'fees_pin_debit_discount'               	=> 		'DebitDiscountRate',
	'fees_ebt_auth'                         	=> 		'EBTTranFee',
	'rep_discount_paid'                     	=> 		'',
	'rep_amex_discount_rate'                	=> 		'Amex Discount Rate',
	'rep_business_legitimate'               	=> 		'BusinessAppearLegit-:Yes,No',
	'rep_photo_included'                    	=> 		'SitePhotoInc-:Yes,No',
	'rep_inventory_sufficient'              	=> 		'InventorySufficient-:Yes,No',
	'rep_goods_delivered'                   	=> 		'DeliveredAtTimeOfSale-:Yes,No',
	'rep_bus_open_operating'                	=>		'BusinessOpen-:Yes,No', 
	'rep_visa_mc_decals_visible'            	=>		'CardDecalsVisible-:Yes,No', 
	'rep_mail_tel_activity'                 	=> 		'MailTeleOrderActivity-:Yes,No',
	'moto_inventory_owned'                  	=>		'OwnInventory-:Yes,No', 
	'moto_outsourced_customer_service_field'	=> 		'Customer Service',
	'moto_outsourced_shipment_field'        	=> 		'Product Shipment',
	'moto_outsourced_returns_field'         	=> 		'Handling of Returns',
	'moto_sales_local'                      	=>		'locally', 
	'moto_sales_national'                   	=> 		'nationally',
	'site_survey_signature'                 	=> 		'site_survey_signature',
    );

    $file = "/tmp/legacy_to_cobranded_app_migration.txt"; 
    $filehandle = fopen($file, 'w');

    $conn_string = "host=localhost port=5432 dbname=axia_legacy user=axia password=ax!a";
    $conn = pg_connect($conn_string);

    if ($conn) {
        fwrite($filehandle, "successfully connected to db: $conn_string\n");
    }

    $appResult = pg_query($conn, "SELECT * FROM onlineapp_applications");

    while ($row = pg_fetch_assoc($appResult)) {
        createNewApp($row);
    }

    fclose($filehandle);

    function createNewApp($data) {
        global $conn;
        global $applicationMap;
        global $filehandle;

        $ownershipTypeMap = array(
            'corporation' => 'Corp',
            'sole prop' => 'SoleProp',
            'llc' => 'LLC',
            'partnership' => 'Partnership',
            'non profit' => 'NonProfit',
            'other' => 'Other'
        );

        $locationTypeMap = array(
            'retail store' => 'RetailStore',
            'industrial' => 'Industrial',
            'trade' => 'Trade',
            'office' => 'Office',
            'residence' => 'Residence',
            'other' => 'SiteInspectionOther'
        );

	$templateQuery = "
	    SELECT users.template_id, cobrands.partner_name
	      FROM onlineapp_users as users
              JOIN onlineapp_templates as templates
                ON users.template_id = templates.id
              JOIN onlineapp_cobrands as cobrands
                ON templates.cobrand_id = cobrands.id
	     WHERE users.id = $data[user_id]
	";

	$templateResult = pg_query($conn, $templateQuery);
	$template_id = null;
	$partner_name = null;

	if ($row = pg_fetch_assoc($templateResult)) {
	    $template_id = $row['template_id'];
	    $partner_name = $row['partner_name'];
	}

	if (empty($template_id)) {
	    fwrite($filehandle, "skipping appId: $data[id] - no template_id found\n");
	    return;
	}

	// create UUID
        $uuid = guidv4();

        $newAppQuery = "
            INSERT INTO onlineapp_cobranded_applications (
                id,
		user_id,
		template_id,
		uuid,    
		created,
		modified,
		rightsignature_document_guid,
		status,
		rightsignature_install_document_guid,
		rightsignature_install_status
            )
            VALUES (
		$data[id],
		$data[user_id],
		$template_id,
		'$uuid',
		'$data[created]',
		'$data[modified]',
		'$data[rs_document_guid]',
		'$data[status]',
		'$data[install_var_rs_document_guid]',
		''
            )
        ";
    
        $newAppResult = pg_query($conn, $newAppQuery);

        if ($newAppResult != false) {
            $updateCoversheetsQuery = "
                UPDATE onlineapp_coversheets
                   SET cobranded_application_id = $data[id]
                 WHERE onlineapp_application_id = $data[id]
            ";
    
            $updateCoversheetsResult = pg_query($conn, $updateCoversheetsQuery);

            if ($updateCoversheetsResult == false) {
                fwrite($filehandle, "problem updating onlineapp_coversheets, can't set cobranded_application_id\n");
            }

            $updateEmailTimelinesQuery = "
                UPDATE onlineapp_email_timelines
                   SET cobranded_application_id = $data[id]
                 WHERE app_id = $data[id]
            ";
    
            $updateEmailTimelinesResult = pg_query($conn, $updateEmailTimelinesQuery);

            if ($updateEmailTimelinesResult == false) {
                fwrite($filehandle, "problem updating onlineapp_email_timelines, can't set cobranded_application_id\n");
            }

	    fwrite($filehandle, "adding application values to appId: $data[id]\n");
	    // this foreach block creates the application value records
            foreach ($applicationMap as $key => $val) {

                $mergeFieldName = $val;
		$optionList = '';

		// is this a multi-option field
                $multi = false;
		if (preg_match('/(.+?-):(.*)/', $val, $matches)) {
		    $multi = true;
                    $mergeFieldName = $matches[1];
		    $optionList = $matches[2];
                }

		if ($key == 'fees_startup_application' ||
		    $key == 'fees_auth_transaction' ||
		    $key == 'fees_monthly_statement' ||
		    $key == 'fees_misc_annual_file' ||
		    $key == 'fees_startup_equipment' ||
		    $key == 'fees_auth_amex' ||
		    $key == 'fees_monthly_minimum' ||
		    $key == 'fees_misc_chargeback' ||
		    $key == 'fees_startup_expedite' ||
		    $key == 'fees_auth_aru_voice' ||
		    $key == 'fees_monthly_debit_access' ||
		    $key == 'fees_startup_reprogramming' ||
		    $key == 'fees_auth_wireless' ||
		    $key == 'fees_monthly_ebt' ||
		    $key == 'fees_startup_training' ||
		    $key == 'fees_monthly_gateway_access' ||
		    $key == 'fees_startup_wireless_activation' ||
		    $key == 'fees_monthly_wireless_access' ||
		    $key == 'fees_startup_tax' ||
		    $key == 'fees_startup_total') {

		    $getTemplateFieldIdQuery = "
                        SELECT otf.id
                          FROM onlineapp_template_fields otf
                          JOIN onlineapp_template_sections ots ON otf.section_id = ots.id
                          JOIN onlineapp_template_pages otp ON ots.page_id = otp.id
                         WHERE otp.template_id = $template_id
                           AND otf.default_value ilike '%$val%'";
		}
		else {
                    $tmpMergeFieldName = $mergeFieldName;

                    if (preg_match('/^MethodofSales-/', $mergeFieldName, $matches)) {
                        $tmpMergeFieldName = 'MethodofSales-';
                    }

                    if (preg_match('/^%OfProductSold-/', $mergeFieldName, $matches)) {
                        $tmpMergeFieldName = '%OfProductSold';
                    }

                    if (preg_match('/^Rate Structure-/', $mergeFieldName, $matches)) {
                        $tmpMergeFieldName = 'Rate Structure';
                    }

                    if (preg_match('/^Downgrades-/', $mergeFieldName, $matches)) {
                        $tmpMergeFieldName = 'Downgrades';
                    }

                    $getTemplateFieldIdQuery = "
	                SELECT otf.id
                          FROM onlineapp_template_fields otf
	                  JOIN onlineapp_template_sections ots ON otf.section_id = ots.id
	                  JOIN onlineapp_template_pages otp ON ots.page_id = otp.id
	                 WHERE otp.template_id = $template_id
	                   AND otf.merge_field_name = '$tmpMergeFieldName'";
		}

                $getFieldIdResult = pg_query($conn, $getTemplateFieldIdQuery);

	        if ($row = pg_fetch_row($getFieldIdResult)) {
                    $templateFieldId = $row[0];
		    $value = $data[$key];

		    if (!empty($optionList)) {

                        if ($key == 'fees_rate_structure' && ($partner_name == 'FireSpring' || $partner_name == 'Shortcuts' || $partner_name == 'Inspire Commerce')) {
                            $optionList .= ',Flat Rate';
                        }

                        if ($key == 'fees_qualification_exemptions' && ($partner_name == 'FireSpring' || $partner_name == 'Shortcuts' || $partner_name == 'Inspire Commerce')) {
                            $optionList .= ',Flat Rate';
                        }

		        $array = preg_split('/,/', $optionList);

		        foreach ($array as $element) {
			    $tmpValue = $value;
			    $tmpValue = str_replace(' ', '', $tmpValue);

                            if (!empty($ownershipTypeMap[$tmpValue])) {
                                $tmpValue = $ownershipTypeMap[$tmpValue];
                            }

                            if (!empty($locationTypeMap[$tmpValue])) {
                                $tmpValue = $locationTypeMap[$tmpValue];
                            }

			    $booleanVal = '';
			    if (!empty($tmpValue)) {
			        if (preg_match("~$tmpValue~i", $element)) {
			            $booleanVal = 'true';
                                }
			    }

		            $concatName = "$mergeFieldName"."$element";

	                    $newAppValQuery = "
                                INSERT INTO onlineapp_cobranded_application_values (
                                    cobranded_application_id,
                                    template_field_id,
                                    name,    
                                    value,
                                    created,
                                    modified
                                )
                                VALUES (
                                    $data[id],
                                    $templateFieldId,
                                    '$concatName',
                                    '$booleanVal',
		                    '$data[created]',
		                    '$data[modified]'
                                )
                            ";

                            $newAppValResult = pg_query($conn, $newAppValQuery);
		        }
		    }
		    else {
                        if ($value == 't') { $value = 'true'; }
                        if ($value == 'f') { $value = ''; }

			$value = pg_escape_string($value);

	                $newAppValQuery = "
                            INSERT INTO onlineapp_cobranded_application_values (
                                cobranded_application_id,
                                template_field_id,
                                name,    
                                value,
                                created,
                                modified
                            )
                            VALUES (
                                $data[id],
                                $templateFieldId,
                                '$mergeFieldName',
                                '$value',
		                '$data[created]',
		                '$data[modified]'
                            )
                        ";

                        $newAppValResult = pg_query($conn, $newAppValQuery);
		    }
	        }
                else {
	            fwrite($filehandle, "skipping app value for: $key - can't determine template field id for merge field: $mergeFieldName\n");
	        }
            }
        }
        else {
	    fwrite($filehandle, "skipping appId: $data[id] - problem creating new cobranded application\n");
	    return;
        }
    }

    function guidv4() {
        $data = openssl_random_pseudo_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

?>
