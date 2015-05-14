#!/usr/bin/php

<?php

    $cobrandName = null;

    $skip = 0;

    foreach ($argv as $arg) {
        if ($skip == 0) {
            $skip++;
            continue;
        }

        $array = preg_split('/-/', $arg);
        $key = $array[0];
        $val = $array[1];

        if ($key == 'cobrand_name') {
            $cobrandName = $val;
        }
    }

    if ($cobrandName == null) {
        print "\n";
        print "missing required argument:\n";
        print "\tadd_optblue_changes_to_template.php cobrand_name-TheCobrand'\n\n";
        exit;
    }

    $file = "/tmp/add_optblue_changes_to_template.txt"; 
    $filehandle = fopen($file, 'w');

    $conn_string = 'host=localhost port=5432 dbname=axia_legacy user=axia password=ax!a';
    $conn = pg_connect($conn_string);

    if ($conn) {
        fwrite($filehandle, "successfully connected to db: $conn_string\n");
    }
    else {
        fwrite($filehandle, "could not connect to db: $conn_string\n");
    }

    $cobrandId = null;

    $cobrandIdQuery = pg_query($conn, "SELECT * FROM onlineapp_cobrands WHERE partner_name = '$cobrandName'");

    if ($row = pg_fetch_assoc($cobrandIdQuery)) {
        $cobrandId = $row['id'];
    }

    $templateQuery = pg_query($conn, "SELECT * FROM onlineapp_templates WHERE cobrand_id = ".$cobrandId);

    while ($templateRow = pg_fetch_assoc($templateQuery)) {
        $GUPflag = true;
        $TST1flag = true;
        $TST2flag = true;
        $SOF1flag = true;
        $SOF2flag = true;

        $newTemplateId = createTemplate($cobrandId, $templateRow);

	grantUsersToNewTemplate($templateRow['id'], $newTemplateId);

        $pageQuery = pg_query($conn, "SELECT * FROM onlineapp_template_pages WHERE template_id = ".$templateRow['id']);

        while ($pageRow = pg_fetch_assoc($pageQuery)) {
            $newPageId = createTemplatePage($newTemplateId, $pageRow);

            $sectionQuery = pg_query($conn, "SELECT * FROM onlineapp_template_sections WHERE page_id = ".$pageRow['id']);

            while ($sectionRow = pg_fetch_assoc($sectionQuery)) {
                $newSectionId = createTemplateSection($newPageId, $sectionRow);

                $fieldQuery = pg_query($conn, "SELECT * FROM onlineapp_template_fields WHERE section_id = ".$sectionRow['id']);

                while ($fieldRow = pg_fetch_assoc($fieldQuery)) {
			if (preg_match('/General Underwriting Profile/i', $sectionRow['name'])) {
			    if (preg_match('/MonthlyVol/i', $fieldRow['merge_field_name'])) {
				$fieldRow['name'] = 'Visa/MC Vol';
			    }
			}

			if (preg_match('/Bank Information/i', $sectionRow['name'])) {
			    if ($fieldRow['merge_field_name'] != 'BankName' && $fieldRow['merge_field_name'] != 'BankContact' && $fieldRow['merge_field_name'] != 'BankPhone') {
				continue;
			    }
			}

			if (preg_match('/Schedule of Fees Part I/i', $sectionRow['name'])) {
			    if (preg_match('/Rate Structure/i', $fieldRow['merge_field_name'])) {
				if (preg_match('/(Flat Rate::Flat Rate\{default\})/i', $fieldRow['default_value'], $matches)) {
				    $fieldRow['default_value'] = $matches[1].',Pass Thru::Pass Thru,Cost Plus::Cost Plus,Downgrades at Cost::Downgrades at Cost,Bucketed::Bucketed';
            			}
				else if (preg_match('/(Flat Rate::Flat Rate)/i', $fieldRow['default_value'], $matches)) {
				    $fieldRow['default_value'] = $matches[1].',Pass Thru::Pass Thru,Cost Plus::Cost Plus,Downgrades at Cost::Downgrades at Cost,Bucketed::Bucketed';
            			}
				else {
				    $fieldRow['default_value'] = 'Pass Thru::Pass Thru,Cost Plus::Cost Plus,Downgrades at Cost::Downgrades at Cost,Bucketed::Bucketed';
				}
			    }

			    if (preg_match('/Downgrades/i', $fieldRow['merge_field_name'])) {
				if (preg_match('/(Flat Rate::Flat Rate\{default\})/i', $fieldRow['default_value'], $matches)) {
				    $fieldRow['default_value'] = $matches[1].',Visa/MasterCard/Discover Interchange at Pass Thru::Visa/MasterCard/Discover Interchange at Pass Thru,Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Regulated Check Cards::Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Regulated Check Cards,Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Non-Regulated Check Cards::Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Non-Regulated Check Cards,Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Qualified Consumer Cards::Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Qualified Consumer Cards,Visa/MasterCard/Discover Cost Plus .05%::Visa/MasterCard/Discover Cost Plus .05%,Visa/MasterCard/Discover Cost Plus .10%::Visa/MasterCard/Discover Cost Plus .10%,Visa/MasterCard/Discover Cost Plus .15%::Visa/MasterCard/Discover Cost Plus .15%,Visa/MasterCard/Discover Cost Plus .20%::Visa/MasterCard/Discover Cost Plus .20%,Visa/MasterCard/Discover Cost Plus .25%::Visa/MasterCard/Discover Cost Plus .25%,Visa/MasterCard/Discover Cost Plus .30%::Visa/MasterCard/Discover Cost Plus .30%,Visa/MasterCard/Discover Cost Plus .35%::Visa/MasterCard/Discover Cost Plus .35%,Visa/MasterCard/Discover Cost Plus .40%::Visa/MasterCard/Discover Cost Plus .40%,Visa/MasterCard/Discover Cost Plus .45%::Visa/MasterCard/Discover Cost Plus .45%,Visa/MasterCard/Discover Cost Plus .50%::Visa/MasterCard/Discover Cost Plus .50%,Visa/MasterCard/Discover Cost Plus .55%::Visa/MasterCard/Discover Cost Plus .55%,Visa/MasterCard/Discover Cost Plus .60%::Visa/MasterCard/Discover Cost Plus .60%,Visa/MasterCard/Discover Cost Plus .65%::Visa/MasterCard/Discover Cost Plus .65%,Visa/MasterCard/Discover Cost Plus .70%::Visa/MasterCard/Discover Cost Plus .70%,Visa/MasterCard/Discover Cost Plus .55%::Visa/MasterCard/Discover Cost Plus .55%,(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%::(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%,RATE 2: 0.85% RATE 3: 1.15% + $0.10 BUS 1: 1.05% + $0.10 BUS 2: 1.95% + $0.10::RATE 2: 0.85% RATE 3: 1.15% + $0.10 BUS 1: 1.05% + $0.10 BUS 2: 1.95% + $0.10';
            			}
				else if (preg_match('/(Flat Rate::Flat Rate)/i', $fieldRow['default_value'], $matches)) {
				    $fieldRow['default_value'] = $matches[1].',Visa/MasterCard/Discover Interchange at Pass Thru::Visa/MasterCard/Discover Interchange at Pass Thru,Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Regulated Check Cards::Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Regulated Check Cards,Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Non-Regulated Check Cards::Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Non-Regulated Check Cards,Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Qualified Consumer Cards::Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Qualified Consumer Cards,Visa/MasterCard/Discover Cost Plus .05%::Visa/MasterCard/Discover Cost Plus .05%,Visa/MasterCard/Discover Cost Plus .10%::Visa/MasterCard/Discover Cost Plus .10%,Visa/MasterCard/Discover Cost Plus .15%::Visa/MasterCard/Discover Cost Plus .15%,Visa/MasterCard/Discover Cost Plus .20%::Visa/MasterCard/Discover Cost Plus .20%,Visa/MasterCard/Discover Cost Plus .25%::Visa/MasterCard/Discover Cost Plus .25%,Visa/MasterCard/Discover Cost Plus .30%::Visa/MasterCard/Discover Cost Plus .30%,Visa/MasterCard/Discover Cost Plus .35%::Visa/MasterCard/Discover Cost Plus .35%,Visa/MasterCard/Discover Cost Plus .40%::Visa/MasterCard/Discover Cost Plus .40%,Visa/MasterCard/Discover Cost Plus .45%::Visa/MasterCard/Discover Cost Plus .45%,Visa/MasterCard/Discover Cost Plus .50%::Visa/MasterCard/Discover Cost Plus .50%,Visa/MasterCard/Discover Cost Plus .55%::Visa/MasterCard/Discover Cost Plus .55%,Visa/MasterCard/Discover Cost Plus .60%::Visa/MasterCard/Discover Cost Plus .60%,Visa/MasterCard/Discover Cost Plus .65%::Visa/MasterCard/Discover Cost Plus .65%,Visa/MasterCard/Discover Cost Plus .70%::Visa/MasterCard/Discover Cost Plus .70%,Visa/MasterCard/Discover Cost Plus .55%::Visa/MasterCard/Discover Cost Plus .55%,(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%::(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%,RATE 2: 0.85% RATE 3: 1.15% + $0.10 BUS 1: 1.05% + $0.10 BUS 2: 1.95% + $0.10::RATE 2: 0.85% RATE 3: 1.15% + $0.10 BUS 1: 1.05% + $0.10 BUS 2: 1.95% + $0.10';
            			}
				else {
				    $fieldRow['default_value'] = 'Visa/MasterCard/Discover Interchange at Pass Thru::Visa/MasterCard/Discover Interchange at Pass Thru,Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Regulated Check Cards::Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Regulated Check Cards,Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Non-Regulated Check Cards::Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Non-Regulated Check Cards,Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Qualified Consumer Cards::Non-Qualified Transactions at Additional Visa/MasterCard/Discover Cost Based on Qualified Consumer Cards,Visa/MasterCard/Discover Cost Plus .05%::Visa/MasterCard/Discover Cost Plus .05%,Visa/MasterCard/Discover Cost Plus .10%::Visa/MasterCard/Discover Cost Plus .10%,Visa/MasterCard/Discover Cost Plus .15%::Visa/MasterCard/Discover Cost Plus .15%,Visa/MasterCard/Discover Cost Plus .20%::Visa/MasterCard/Discover Cost Plus .20%,Visa/MasterCard/Discover Cost Plus .25%::Visa/MasterCard/Discover Cost Plus .25%,Visa/MasterCard/Discover Cost Plus .30%::Visa/MasterCard/Discover Cost Plus .30%,Visa/MasterCard/Discover Cost Plus .35%::Visa/MasterCard/Discover Cost Plus .35%,Visa/MasterCard/Discover Cost Plus .40%::Visa/MasterCard/Discover Cost Plus .40%,Visa/MasterCard/Discover Cost Plus .45%::Visa/MasterCard/Discover Cost Plus .45%,Visa/MasterCard/Discover Cost Plus .50%::Visa/MasterCard/Discover Cost Plus .50%,Visa/MasterCard/Discover Cost Plus .55%::Visa/MasterCard/Discover Cost Plus .55%,Visa/MasterCard/Discover Cost Plus .60%::Visa/MasterCard/Discover Cost Plus .60%,Visa/MasterCard/Discover Cost Plus .65%::Visa/MasterCard/Discover Cost Plus .65%,Visa/MasterCard/Discover Cost Plus .70%::Visa/MasterCard/Discover Cost Plus .70%,Visa/MasterCard/Discover Cost Plus .55%::Visa/MasterCard/Discover Cost Plus .55%,(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%::(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%,RATE 2: 0.85% RATE 3: 1.15% + $0.10 BUS 1: 1.05% + $0.10 BUS 2: 1.95% + $0.10::RATE 2: 0.85% RATE 3: 1.15% + $0.10 BUS 1: 1.05% + $0.10 BUS 2: 1.95% + $0.10';
				}
			    }
			}

			if (preg_match('/Schedule of Fees Part II/i', $sectionRow['name'])) {
			    if (preg_match('/Amex Discount Rate/i', $fieldRow['merge_field_name'])) {
				continue;
			    }

			    if (preg_match('/Discount Paid/i', $fieldRow['merge_field_name'])) {
			        $fieldRow['width'] = 3;
			    }
			}

                        $newFieldId = createTemplateField($newSectionId, $fieldRow);
                }

		if (preg_match('/General Underwriting Profile/i', $sectionRow['name'])) {
		    if ($GUPflag == true) {
		        $DiscVol = array(
			    'id' => 0,
			    'name' => 'Discover Volume',
			    'description' => '',
			    'rep_only' => 'f',
			    'width' => 4,
			    'type' => 10,
			    'required' => 't',
			    'source' => 2,
			    'default_value' => '',
			    'merge_field_name' => 'DiscVol',
			    'order' => '5',
			    'encrypt' => 'f'
		        );

			updateOrder($newSectionId, 5);
                        $result = createTemplateField($newSectionId, $DiscVol);

		        $AEMoVol = array(
			    'id' => 0,
			    'name' => 'American Express Volume',
			    'description' => '',
			    'rep_only' => 'f',
			    'width' => 4,
			    'type' => 10,
			    'required' => 't',
			    'source' => 2,
			    'default_value' => '',
			    'merge_field_name' => 'AEMoVol',
			    'order' => '6',
			    'encrypt' => 'f'
		        );

			updateOrder($newSectionId, 6);
                        $result = createTemplateField($newSectionId, $AEMoVol);

		        $PINMoVol = array(
			    'id' => 0,
			    'name' => 'Pin Debit Volume',
			    'description' => '',
			    'rep_only' => 'f',
			    'width' => 4,
			    'type' => 10,
			    'required' => 't',
			    'source' => 2,
			    'default_value' => '',
			    'merge_field_name' => 'PINMoVol',
			    'order' => '7',
			    'encrypt' => 'f'
		        );

			updateOrder($newSectionId, 7);
                        $result = createTemplateField($newSectionId, $PINMoVol);

			$GUPflag = false;
		    }
		}

		if (preg_match('/Terminal\/Software Type\(1\)/i', $sectionRow['name'])) {
                    if ($TST1flag == true) {
                        $Term1 = array(
                            'id' => 0,
                            'name' => 'Primary Communication',
                            'description' => '',
                            'rep_only' => 'f',
                            'width' => 12,
                            'type' => 4,
                            'required' => 't',
                            'source' => 2,
                            'default_value' => 'IP/SSL::IP,Dial::Dial',
                            'merge_field_name' => 'Term1-',
                            'order' => '11',
                            'encrypt' => 'f'
                        );

			updateOrder($newSectionId, 11);
                        $result = createTemplateField($newSectionId, $Term1);

                        $HR = array(
                            'id' => 0,
                            'name' => 'Horizontal Rule',
                            'description' => '',
                            'rep_only' => 'f',
                            'width' => 12,
                            'type' => 8,
                            'required' => 'f',
                            'source' => 1,
                            'default_value' => '',
                            'merge_field_name' => 'HR',
                            'order' => '12',
                            'encrypt' => 'f'
                        );

			updateOrder($newSectionId, 12);
                        $result = createTemplateField($newSectionId, $HR);

                        $PinPadProvider = array(
                            'id' => 0,
                            'name' => 'Pin Pad Provider',
                            'description' => '',
                            'rep_only' => 'f',
                            'width' => 3,
                            'type' => 4,
                            'required' => 'f',
                            'source' => 2,
                            'default_value' => 'Axia::Axia,Merchant::Merchant',
                            'merge_field_name' => 'PinPadProvider-',
                            'order' => '16',
                            'encrypt' => 'f'
                        );

			updateOrder($newSectionId, 16);
                        $result = createTemplateField($newSectionId, $PinPadProvider);

                        $TST1flag = false;
                    }
                }

		if (preg_match('/Terminal\/Software Type\(2\)/i', $sectionRow['name'])) {
                    if ($TST2flag == true) {
                        $Term2 = array(
                            'id' => 0,
                            'name' => 'Primary Communication',
                            'description' => '',
                            'rep_only' => 'f',
                            'width' => 12,
                            'type' => 4,
                            'required' => 'f',
                            'source' => 2,
                            'default_value' => 'IP/SSL::IP,Dial::Dial',
                            'merge_field_name' => 'Term2-',
                            'order' => '11',
                            'encrypt' => 'f'
                        );

			updateOrder($newSectionId, 11);
                        $result = createTemplateField($newSectionId, $Term2);

                        $HR2 = array(
                            'id' => 0,
                            'name' => 'Horizontal Rule',
                            'description' => '',
                            'rep_only' => 'f',
                            'width' => 12,
                            'type' => 8,
                            'required' => 'f',
                            'source' => 1,
                            'default_value' => '',
                            'merge_field_name' => 'HR2',
                            'order' => '12',
                            'encrypt' => 'f'
                        );

			updateOrder($newSectionId, 12);
                        $result = createTemplateField($newSectionId, $HR2);

                        $PinPadProvider2 = array(
                            'id' => 0,
                            'name' => 'Pin Pad Provider',
                            'description' => '',
                            'rep_only' => 'f',
                            'width' => 3,
                            'type' => 4,
                            'required' => 'f',
                            'source' => 2,
                            'default_value' => 'Axia::Axia,Merchant::Merchant',
                            'merge_field_name' => 'PinPadProvider2-',
                            'order' => '16',
                            'encrypt' => 'f'
                        );

			updateOrder($newSectionId, 16);
                        $result = createTemplateField($newSectionId, $PinPadProvider2);

                        $TST2flag = false;
                    }
                }

	        if (preg_match('/Schedule of Fees Part I/i', $sectionRow['name'])) {
                    if ($SOF1flag == true) {
                        $AmexDiscountRate = array(
                            'id' => 0,
                            'name' => 'Amex Discount Rate',
                            'description' => '',
                            'rep_only' => 't',
                            'width' => 6,
                            'type' => 11,
                            'required' => 't',
                            'source' => 1,
                            'default_value' => '',
                            'merge_field_name' => 'Amex Discount Rate',
                            'order' => '3',
                            'encrypt' => 'f'
                        );

			updateOrder($newSectionId, 3);
                        $result = createTemplateField($newSectionId, $AmexDiscountRate);

                        $AmexRateStructure = array(
                            'id' => 0,
                            'name' => 'Amex Rate Structure',
                            'description' => '',
                            'rep_only' => 't',
                            'width' => 6,
                            'type' => 20,
                            'required' => 't',
                            'source' => 1,
                            'default_value' => 'Pass Thru::Pass Thru,Cost Plus::Cost Plus',
                            'merge_field_name' => 'Amex Rate Structure',
                            'order' => '4',
                            'encrypt' => 'f'
                        );

			updateOrder($newSectionId, 4);
                        $result = createTemplateField($newSectionId, $AmexRateStructure);

                        $AmexDowngrades = array(
                            'id' => 0,
                            'name' => 'Amex Downgrades',
                            'description' => '',
                            'rep_only' => 't',
                            'width' => 12,
                            'type' => 20,
                            'required' => 't',
                            'source' => 1,
                            'default_value' => 'American Express Fees Passed Thru::American Express Fees Passed Thru,American Express Cost Plus .05%::American Express Cost Plus .05%,American Express Cost Plus .10%::American Express Cost Plus .10%,American Express Cost Plus .15%::American Express Cost Plus .15%,American Express Cost Plus .20%::American Express Cost Plus .20%,American Express Cost Plus .25%::American Express Cost Plus .25%,American Express Cost Plus .30%::American Express Cost Plus .30%,American Express Cost Plus .35%::American Express Cost Plus .35%,American Express Cost Plus .40%::American Express Cost Plus .40%,American Express Cost Plus .45%::American Express Cost Plus .45%,American Express Cost Plus .50%::American Express Cost Plus .50%,American Express Cost Plus .55%::American Express Cost Plus .55%,American Express Cost Plus .60%::American Express Cost Plus .60%,American Express Cost Plus .65%::American Express Cost Plus .65%,American Express Cost Plus .70%::American Express Cost Plus .70%,American Express Cost Plus .75%::American Express Cost Plus .75%',
                            'merge_field_name' => 'Amex Qualified Exemptions',
                            'order' => '5',
                            'encrypt' => 'f'
                        );

			updateOrder($newSectionId, 5);
                        $result = createTemplateField($newSectionId, $AmexDowngrades);

		        $SOF1flag = false;
		    }
		}

	        if (preg_match('/Schedule of Fees Part II/i', $sectionRow['name'])) {
                    if ($SOF2flag == true) {
                        $Tax = array(
                            'id' => 0,
                            'name' => 'Tax',
                            'description' => '',
                            'rep_only' => 't',
                            'width' => 1,
                            'type' => 0,
                            'required' => 't',
                            'source' => 3,
                            'default_value' => 'TBD',
                            'merge_field_name' => 'Tax',
                            'order' => '11',
                            'encrypt' => 'f'
                        );

			updateOrder($newSectionId, 11);
                        $result = createTemplateField($newSectionId, $Tax);

		        $SOF2flag = false;
		    }
		}
            }
        }
    }

    fclose($filehandle);

    function createTemplate($id, $data) {
        global $conn;

        $equityThreshold = 0;

        if (isset($data['owner_equity_threshold'])) {
            $equityThreshold = $data['owner_equity_threshold'];
        }

        $newTemplateQuery = "
            INSERT INTO onlineapp_templates (
                name,
                logo_position,
                include_brand_logo,
                description,
                cobrand_id,
                created,
                modified,
                rightsignature_template_guid,
                rightsignature_install_template_guid,
		owner_equity_threshold
            )
            VALUES (
                '$data[name]',
                '$data[logo_position]',
                '$data[include_brand_logo]',
                '$data[description]',
                $id,
                now()::timestamptz(0),
                now()::timestamptz(0),
                '$data[rightsignature_template_guid]',
                '$data[rightsignature_install_template_guid]',
                $equityThreshold
            )
            RETURNING Currval('onlineapp_templates_id_seq')
        ";

        $newTemplateResult = pg_query($conn, $newTemplateQuery);
        $row = pg_fetch_row($newTemplateResult);

	$newName = $data['name'].' - pre opt-blue';
	$updateTemplateNameQuery = "
		UPDATE onlineapp_templates
		   SET name = '".$newName."',
		       modified = now()::timestamptz(0)
		 WHERE id = ".$data['id'];
	$updateResult = pg_query($conn, $updateTemplateNameQuery);

        return $row[0];
    }

    function createTemplatePage($id, $data) {
        global $conn;

        $newTemplatePageQuery = "
            INSERT INTO onlineapp_template_pages (
                name,
                description,
                rep_only,
                template_id,
                \"order\",
                created,
                modified
            )
            VALUES (
                '$data[name]',
                '$data[description]',
                '$data[rep_only]',
                $id,
                $data[order],
                now()::timestamptz(0),
                now()::timestamptz(0)
            )
            RETURNING Currval('onlineapp_template_pages_id_seq')
        ";

        $newTemplatePageResult = pg_query($conn, $newTemplatePageQuery);
        $row = pg_fetch_row($newTemplatePageResult);
        return $row[0];
    }

    function createTemplateSection($id, $data) {
        global $conn;

        $newTemplateSectionQuery = "
            INSERT INTO onlineapp_template_sections (
                name,
                description,
                rep_only,
                width,
                page_id,
                \"order\",
                created,
                modified
            )
            VALUES (
                '$data[name]',
                '$data[description]',
                '$data[rep_only]',
                $data[width],
                $id,
                $data[order],
                now()::timestamptz(0),
                now()::timestamptz(0)
            )
            RETURNING Currval('onlineapp_template_sections_id_seq')
        ";

        $newTemplateSectionResult = pg_query($conn, $newTemplateSectionQuery);
        $row = pg_fetch_row($newTemplateSectionResult);

        return $row[0];
    }

    function createTemplateField($id, $data) {
        global $conn;

        $newTemplateFieldQuery = "
            INSERT INTO onlineapp_template_fields (
                name,
                description,
                rep_only,
                width,
                type,
                required,
                source,
                default_value,
                merge_field_name,
                \"order\",
                section_id,
                created,
                modified,
                encrypt
            )
            VALUES (
                '$data[name]',
                '$data[description]',
                '$data[rep_only]',
                $data[width],
                $data[type],
                '$data[required]',
                $data[source],
                '$data[default_value]',
                '$data[merge_field_name]',
                $data[order],
                $id,
                now()::timestamptz(0),
                now()::timestamptz(0),
                '$data[encrypt]'
            )
            RETURNING Currval('onlineapp_template_fields_id_seq')
        ";

        $newTemplateFieldResult = pg_query($conn, $newTemplateFieldQuery);
        $row = pg_fetch_row($newTemplateFieldResult);
        return $row[0];
    }

    function updateOrder($sectionId, $order) {
        global $conn;

        $selectQuery = '
	    SELECT id, "order"
	      FROM onlineapp_template_fields
	     WHERE section_id = '.$sectionId.'
	       AND "order" >= '.$order;

        $results = pg_query($conn, $selectQuery);

	while ($row = pg_fetch_assoc($results)) {
            $updateQuery = '
                UPDATE onlineapp_template_fields
                   SET "order" = '.($row["order"] + 1).'
                 WHERE id = '.$row["id"];

            pg_query($conn, $updateQuery);
	}
    }

    function grantUsersToNewTemplate($templateId, $newTemplateId) {
        global $conn;

        $selectQuery = '
	    SELECT user_id
	      FROM onlineapp_users_onlineapp_templates
	     WHERE template_id = '.$templateId;

        $results = pg_query($conn, $selectQuery);

	while ($row = pg_fetch_assoc($results)) {
            $insertQuery = '
                INSERT INTO onlineapp_users_onlineapp_templates (user_id, template_id)
		            VALUES('.$row["user_id"].','.$newTemplateId.')';

            pg_query($conn, $insertQuery);
	}
    }

?>
