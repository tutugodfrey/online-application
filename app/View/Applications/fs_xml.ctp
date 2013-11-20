<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<template>
    <guid><?php echo $document_guid; ?></guid>
    <subject><?php echo htmlspecialchars($data['Application']['dba_business_name']); ?> Axia Merchant Application</subject>
    <description>Sent for signature by <?php echo $this->Session->read('Auth.User.email'); ?></description>
    <action>send</action>
    <expires_in>10 days</expires_in>
        <roles>
            <?php if ($data['Application']['owner2_fullname'] && $data['Application']['owner2_email']): ?>
        <role role_name="Owner/Officer 2 PG">
            <name><?php echo htmlspecialchars($data['Application']['owner2_fullname']); ?></name>
            <email><?php echo htmlspecialchars('noemail@rightsignature.com'); ?></email>
            <locked>true</locked>
        </role>
        <role role_name="Owner/Officer 2">
            <name><?php echo htmlspecialchars($data['Application']['owner2_fullname']); ?></name>
            <email><?php echo htmlspecialchars('noemail@rightsignature.com'); ?></email>
            <locked>true</locked>
        </role>
        <?php endif; ?>

        <!--<role role_name="Owner/Officer 1 PG">-->
            <role role_name="Personal Guarantor 1">
            <name><?php echo htmlspecialchars($data['Application']['owner1_fullname']); ?></name>
            <email><?php echo htmlspecialchars('noemail@rightsignature.com'); ?></email>
            <locked>true</locked>
        </role>
        <!--<role role_name="Owner/Officer 1">-->
            <role rol_name="Signer 1">
            <name><?php echo htmlspecialchars($data['Application']['owner1_fullname']); ?></name>
            <email><?php echo htmlspecialchars('noemail@rightsignature.com'); ?></email>
            <locked>true</locked>
        </role>
        </roles>
    <merge_fields>
        <?php switch ($data['Application']['ownership_type']):
            case 'corporation': ?>
                <merge_field merge_field_name="Owner Type - Corp">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'sole prop': ?>
                <merge_field merge_field_name="Owner Type - Sole Prop">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'llc': ?>
                <merge_field merge_field_name="Owner Type - LLC">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'partnership': ?>
                <merge_field merge_field_name="Owner Type - Partnership">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'non profit': ?>
                <merge_field merge_field_name="Owner Type - Non Profit">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'other': ?>
                <merge_field merge_field_name="Owner Type - Other">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
        <?php endswitch; ?>
        <merge_field merge_field_name="CorpName">
            <value><?php echo htmlspecialchars($data['Application']['legal_business_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CorpAddress">
            <value><?php echo htmlspecialchars($data['Application']['mailing_address']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CorpCity">
            <value><?php echo htmlspecialchars($data['Application']['mailing_city']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CorpState">
            <value><?php echo htmlspecialchars($data['Application']['mailing_state']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CorpZip">
            <value><?php echo htmlspecialchars($data['Application']['mailing_zip']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CorpPhone">
            <value><?php echo htmlspecialchars($data['Application']['mailing_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CorpFax">
            <value><?php echo htmlspecialchars($data['Application']['mailing_fax']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Tax ID">
            <value><?php echo htmlspecialchars($data['Application']['federal_taxid']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CorpContact">
            <value><?php echo htmlspecialchars($data['Application']['corp_contact_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CorpTitle">
            <value><?php echo htmlspecialchars($data['Application']['corp_contact_name_title']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Email">
            <value><?php echo htmlspecialchars($data['Application']['corporate_email']); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['existing_axia_merchant'] == 'yes'): ?>
            <merge_field merge_field_name="Existing_Axia_Merchant_Yes">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['existing_axia_merchant'] == 'no'): ?>
            <merge_field merge_field_name="Existing_Axia_Merchant_No">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="Current MID">
            <value><?php echo htmlspecialchars($data['Application']['current_mid_number']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="DBA">
            <value><?php echo htmlspecialchars($data['Application']['dba_business_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Address">
            <value><?php echo htmlspecialchars($data['Application']['location_address']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="City">
            <value><?php echo htmlspecialchars($data['Application']['location_city']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="State">
            <value><?php echo htmlspecialchars($data['Application']['location_state']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Zip">
            <value><?php echo htmlspecialchars($data['Application']['location_zip']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="PhoneNumber">
            <value><?php echo htmlspecialchars($data['Application']['location_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="FaxNumber">
            <value><?php echo htmlspecialchars($data['Application']['location_fax']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Customer Service Phone">
            <value><?php echo htmlspecialchars($data['Application']['customer_svc_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="WebAddress">
            <value><?php echo htmlspecialchars($data['Application']['website']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Contact">
            <value><?php echo htmlspecialchars($data['Application']['loc_contact_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Title">
            <value><?php echo htmlspecialchars($data['Application']['loc_contact_name_title']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="OpenDate">
            <value><?php echo htmlspecialchars($data['Application']['bus_open_date']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Ownership Length">
            <value><?php echo htmlspecialchars($data['Application']['length_current_ownership']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="General Comments">
            <value><?php echo htmlspecialchars($data['Application']['general_comments']); ?></value>
            <locked>true</locked>
        </merge_field>
        
        <?php /* -------------------------------- */ ?>
        
        <merge_field merge_field_name="OwnerEquity">
            <value><?php echo htmlspecialchars($data['Application']['owner1_percentage']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Principal">
            <value><?php echo htmlspecialchars($data['Application']['owner1_fullname']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner1Title">
            <value><?php echo htmlspecialchars($data['Application']['owner1_title']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner1Address">
            <value><?php echo htmlspecialchars($data['Application']['owner1_address']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner1City">
            <value><?php echo htmlspecialchars($data['Application']['owner1_city']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner1State">
            <value><?php echo htmlspecialchars($data['Application']['owner1_state']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner1Zip">
            <value><?php echo htmlspecialchars($data['Application']['owner1_zip']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner1Phone">
            <value><?php echo htmlspecialchars($data['Application']['owner1_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner1Fax">
            <value><?php echo htmlspecialchars($data['Application']['owner1_fax']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner1Email">
            <value><?php echo htmlspecialchars($data['Application']['owner1_email']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="OwnerSSN">
            <value><?php echo htmlspecialchars($data['Application']['owner1_ssn']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner1DOB">
            <value><?php echo htmlspecialchars($data['Application']['owner1_dob']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2Equity">
            <value><?php echo htmlspecialchars($data['Application']['owner2_percentage']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2Name">
            <value><?php echo htmlspecialchars($data['Application']['owner2_fullname']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2Title">
            <value><?php echo htmlspecialchars($data['Application']['owner2_title']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2Address">
            <value><?php echo htmlspecialchars($data['Application']['owner2_address']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2City">
            <value><?php echo htmlspecialchars($data['Application']['owner2_city']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2State">
            <value><?php echo htmlspecialchars($data['Application']['owner2_state']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2Zip">
            <value><?php echo htmlspecialchars($data['Application']['owner2_zip']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2Phone">
            <value><?php echo htmlspecialchars($data['Application']['owner2_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2Fax">
            <value><?php echo htmlspecialchars($data['Application']['owner2_fax']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2Email">
            <value><?php echo htmlspecialchars($data['Application']['owner2_email']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2SSN">
            <value><?php echo htmlspecialchars($data['Application']['owner2_ssn']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Owner2DOB">
            <value><?php echo htmlspecialchars($data['Application']['owner2_dob']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefName">
            <value><?php echo htmlspecialchars($data['Application']['trade1_business_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefContact">
            <value><?php echo htmlspecialchars($data['Application']['trade1_contact_person']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefPhone1">
            <value><?php echo htmlspecialchars($data['Application']['trade1_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefAccount1">
            <value><?php echo htmlspecialchars($data['Application']['trade1_acct_num']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefCity1">
            <value><?php echo htmlspecialchars($data['Application']['trade1_city']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefSt1">
            <value><?php echo htmlspecialchars($data['Application']['trade1_state']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRef2">
            <value><?php echo htmlspecialchars($data['Application']['trade2_business_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefContact2">
            <value><?php echo htmlspecialchars($data['Application']['trade2_contact_person']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefPhone2">
            <value><?php echo htmlspecialchars($data['Application']['trade2_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefAccount2">
            <value><?php echo htmlspecialchars($data['Application']['trade2_acct_num']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefCity2">
            <value><?php echo htmlspecialchars($data['Application']['trade2_city']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TradeRefSt2">
            <value><?php echo htmlspecialchars($data['Application']['trade2_state']); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php switch ($data['Application']['business_type']):
            case 'retail': ?>
                <merge_field merge_field_name="Retail">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'restaurant': ?>
                <merge_field merge_field_name="Restaurant">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'lodging': ?>
                <merge_field merge_field_name="Lodging">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'MOTO': ?>
                <merge_field merge_field_name="MOTO">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'internet': ?>
                <merge_field merge_field_name="Internet">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'grocery': ?>
                <merge_field merge_field_name="Grocery">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
        <?php endswitch; ?>
        <merge_field merge_field_name="Products Services Sold">
            <value><?php echo htmlspecialchars($data['Application']['products_services_sold']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Return Policy">
            <value><?php echo htmlspecialchars($data['Application']['return_policy']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Days Until Product Delivery">
            <value><?php echo htmlspecialchars($data['Application']['days_until_prod_delivery']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Jan">
            <value><?php echo ($data['Application']['high_volume_january'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Feb">
            <value><?php echo ($data['Application']['high_volume_february'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Mar">
            <value><?php echo ($data['Application']['high_volume_march'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Apr">
            <value><?php echo ($data['Application']['high_volume_april'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="May">
            <value><?php echo ($data['Application']['high_volume_may'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Jun">
            <value><?php echo ($data['Application']['high_volume_june'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Jul">
            <value><?php echo ($data['Application']['high_volume_july'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Aug">
            <value><?php echo ($data['Application']['high_volume_august'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Sep">
            <value><?php echo ($data['Application']['high_volume_september'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Oct">
            <value><?php echo ($data['Application']['high_volume_october'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Nov">
            <value><?php echo ($data['Application']['high_volume_november'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Dec">
            <value><?php echo ($data['Application']['high_volume_december'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="MonthlyVol">
            <value><?php echo htmlspecialchars($data['Application']['monthly_volume']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="AvgTicket">
            <value><?php echo htmlspecialchars($data['Application']['average_ticket']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="MaxSalesAmt">
            <value><?php echo htmlspecialchars($data['Application']['highest_ticket']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Previous Processor">
            <value><?php echo htmlspecialchars($data['Application']['current_processor']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Card Present Swiped">
            <value><?php echo htmlspecialchars($data['Application']['card_present_swiped']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Card Present Imprint">
            <value><?php echo htmlspecialchars($data['Application']['card_present_imprint']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Card Not Present Keyed">
            <value><?php echo htmlspecialchars($data['Application']['card_not_present_keyed']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Card Not Present Internet">
            <value><?php echo htmlspecialchars($data['Application']['card_not_present_internet']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Direct to Consumer">
            <value><?php echo htmlspecialchars($data['Application']['direct_to_customer']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Direct to Business">
            <value><?php echo htmlspecialchars($data['Application']['direct_to_business']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Direct to Government">
            <value><?php echo htmlspecialchars($data['Application']['direct_to_govt']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="BankName">
            <value><?php echo htmlspecialchars($data['Application']['bank_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="BankContact">
            <value><?php echo htmlspecialchars($data['Application']['bank_contact_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="BankPhone">
            <value><?php echo htmlspecialchars($data['Application']['bank_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="BankAddress">
            <value><?php echo htmlspecialchars($data['Application']['bank_address']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="BankCity">
            <value><?php echo htmlspecialchars($data['Application']['bank_city']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="BankState">
            <value><?php echo htmlspecialchars($data['Application']['bank_state']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="BankZip">
            <value><?php echo htmlspecialchars($data['Application']['bank_zip']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="RoutingNum">
            <value><?php echo htmlspecialchars($data['Application']['depository_routing_number']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="AccountNum">
            <value><?php echo htmlspecialchars($data['Application']['depository_account_number']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="FeesRoutingNum">
            <value><?php echo htmlspecialchars($data['Application']['fees_routing_number']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="FeesAccountNum">
            <value><?php echo htmlspecialchars($data['Application']['fees_account_number']); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['moto_storefront_location'] == 'yes'): ?>
            <merge_field merge_field_name="Check Box 8">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php elseif ($data['Application']['moto_storefront_location'] == 'no'): ?>
        <merge_field merge_field_name="Check Box 9">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['moto_orders_at_location'] == 'yes'): ?>
            <merge_field merge_field_name="Check Box 14">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php elseif ($data['Application']['moto_orders_at_location'] == 'no'): ?>
        <merge_field merge_field_name="Check Box 13">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="Where is inventory housed">
            <value><?php echo htmlspecialchars($data['Application']['moto_inventory_housed']); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php if($data['Application']['moto_inventory_owned'] == yes): ?>
        <merge_field merge_field_name="product_yes">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if($data['Application']['moto_inventory_owned'] == no): ?>
        <merge_field merge_field_name="product_no">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['moto_outsourced_customer_service']): ?>
            <merge_field merge_field_name="Customer Service">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="customer_service">
            <value><?php echo htmlspecialchars($data['Application']['moto_outsourced_customer_service_field']);?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['moto_outsourced_shipment']): ?>
            <merge_field merge_field_name="Product Shipment">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="product_service">
            <value><?php echo htmlspecialchars($data['Application']['moto_outsourced_shipment_field']);?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['moto_outsourced_returns']): ?>
            <merge_field merge_field_name="Handling of Returns">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="handling_of_returns">
            <value><?php echo htmlspecialchars($data['Application']['moto_outsourced_returns_field']);?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['moto_outsourced_billing']): ?>
            <merge_field merge_field_name="Cardholder Billing">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="By what methods to sales take place ie Internet trade shows etc ">
            <value><?php echo htmlspecialchars($data['Application']['moto_sales_methods']); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['moto_sales_local']): ?>
        <merge_field merge_field_name="locally">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['moto_sales_national']): ?>
        <merge_field merge_field_name="nationally">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['moto_billing_monthly']): ?>
            <merge_field merge_field_name="Monthly Recurring">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['moto_billing_quarterly']): ?>
            <merge_field merge_field_name="QUARTERLY">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['moto_billing_semiannually']): ?>
            <merge_field merge_field_name="SEMIANNUALLY">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['moto_billing_annually']): ?>
            <merge_field merge_field_name="ANNUALLY">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="Text83">
            <value><?php echo htmlspecialchars($data['Application']['moto_policy_full_up_front']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Text86">
            <value><?php echo htmlspecialchars($data['Application']['moto_policy_days_until_delivery']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Text84">
            <value><?php echo htmlspecialchars($data['Application']['moto_policy_partial_up_front']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Text87">
            <value><?php echo htmlspecialchars($data['Application']['moto_policy_partial_with']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Text88">
            <value><?php echo htmlspecialchars($data['Application']['moto_policy_days_until_final']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Text85">
            <value><?php echo htmlspecialchars($data['Application']['moto_policy_after']); ?></value>
            <locked>true</locked>
        </merge_field>
        
        <?php /* -------------------------------- */ ?>
        
        <?php if ($data['Application']['currently_accept_amex'] == 'yes'): ?>
            <merge_field merge_field_name="AEExist">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
        <?php else: ?>
            <?php if ($data['Application']['want_to_accept_amex'] == 'yes'): ?>
            <merge_field merge_field_name="AENew">
                <value>X</value>
                <locked>true</locked>
            </merge_field>
            <?php endif; ?>
        <?php endif; ?>
        <merge_field merge_field_name="AmexNum">
            <value><?php echo htmlspecialchars($data['Application']['existing_se_num']); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['want_to_accept_discover'] == 'yes'): ?>
        <merge_field merge_field_name="DiscNew">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        
        <merge_field merge_field_name="Referral1">
            <value><?php echo htmlspecialchars($data['Application']['referral1_business']); ?> / <?php echo htmlspecialchars($data['Application']['referral1_owner_officer']); ?> / <?php echo htmlspecialchars($data['Application']['referral1_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Referral2">
            <value><?php echo htmlspecialchars($data['Application']['referral2_business']); ?> / <?php echo htmlspecialchars($data['Application']['referral2_owner_officer']); ?> / <?php echo htmlspecialchars($data['Application']['referral2_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Referral3">
            <value><?php echo htmlspecialchars($data['Application']['referral3_business']); ?> / <?php echo htmlspecialchars($data['Application']['referral3_owner_officer']); ?> / <?php echo htmlspecialchars($data['Application']['referral3_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        
        <merge_field merge_field_name="QTY1">
            <value><?php echo htmlspecialchars($data['Application']['term1_quantity']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Terminal1">
            <value><?php echo htmlspecialchars($data['Application']['term1_type']); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['term1_provider'] == 'axia'): ?>
        <merge_field merge_field_name="Axia">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['term1_provider'] == 'merchant'): ?>
        <merge_field merge_field_name="Merchant">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="PinPad1">
            <value><?php echo htmlspecialchars($data['Application']['term1_pin_pad_type']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="QTY - PP1">
            <value><?php echo htmlspecialchars($data['Application']['term1_pin_pad_qty']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="AVS">
            <value><?php echo ($data['Application']['term1_programming_avs'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Server">
            <value><?php echo ($data['Application']['term1_programming_server_nums'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Invoice">
            <value><?php echo ($data['Application']['term1_programming_invoice_num'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Tips">
            <value><?php echo ($data['Application']['term1_programming_tips'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Purchasing Cards">
            <value><?php echo ($data['Application']['term1_programming_purchasing_cards'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['term1_use_autoclose'] == 'yes'): ?>
        <merge_field merge_field_name="Autoclose">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Autoclose Time 1">
            <value><?php echo htmlspecialchars($data['Application']['term1_what_time']); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        
        <?php /* -------------------------------- */ ?>
        
        <merge_field merge_field_name="QTY2">
            <value><?php echo htmlspecialchars($data['Application']['term2_quantity']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Terminal2">
            <value><?php echo htmlspecialchars($data['Application']['term2_type']); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['term2_provider'] == 'axia'): ?>
        <merge_field merge_field_name="Axia_3">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['term2_provider'] == 'merchant'): ?>
        <merge_field merge_field_name="Merchant_3">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="PinPad2">
            <value><?php echo htmlspecialchars($data['Application']['term2_pin_pad_type']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="QTY - PP2">
            <value><?php echo htmlspecialchars($data['Application']['term2_pin_pad_qty']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="AVS_2">
            <value><?php echo ($data['Application']['term2_programming_avs'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Server_2">
            <value><?php echo ($data['Application']['term2_programming_server_nums'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Invoice_2">
            <value><?php echo ($data['Application']['term2_programming_invoice_num'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Tips_2">
            <value><?php echo ($data['Application']['term2_programming_tips'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Purchasing Cards_2">
            <value><?php echo ($data['Application']['term2_programming_purchasing_cards'] ? 'X' : ''); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php if ($data['Application']['term2_use_autoclose'] == 'yes'): ?>
        <merge_field merge_field_name="Autoclose_2">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Autoclose Time 2">
            <value><?php echo htmlspecialchars($data['Application']['term2_what_time']); ?></value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        
        <?php /* -------------------------------- */ ?>
        
        <?php switch ($data['Application']['location_type']):
            case 'retail store': ?>
                <merge_field merge_field_name="Retail Store">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'office': ?>
                <merge_field merge_field_name="Office">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'industrial': ?>
                <merge_field merge_field_name="Industrial">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'residence': ?>
                <merge_field merge_field_name="Residence">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'trade': ?>
                <merge_field merge_field_name="Trade">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
            <?php case 'other': ?>
                <merge_field merge_field_name="Site Inspection Other">
                    <value>X</value>
                    <locked>true</locked>
                </merge_field>
                <?php break; ?>
        <?php endswitch; ?>
        <?php if ($data['Application']['merchant_status'] == 'owns'): ?>
        <merge_field merge_field_name="Owns">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['merchant_status'] == 'leases'): ?>
        <merge_field merge_field_name="Leases">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="Landlord">
            <value><?php echo htmlspecialchars($data['Application']['landlord_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Landlord Phone">
            <value><?php echo htmlspecialchars($data['Application']['landlord_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        
        <?php /* -------------------------------- */ ?>
        
        <merge_field merge_field_name="DiscRate">
            <value><?php echo htmlspecialchars($data['Application']['fees_rate_discount']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Rate Structure">
            <value><?php echo htmlspecialchars($data['Application']['fees_rate_structure']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Downgrades">
            <value><?php echo htmlspecialchars($data['Application']['fees_qualification_exemptions']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CreditAppFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_startup_application']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="TranFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_auth_transaction']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="StatementFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_monthly_statement']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="AnnualFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_misc_annual_file']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CreditEquipmentFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_startup_equipment']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="AmexPerItem">
            <value><?php echo htmlspecialchars($data['Application']['fees_auth_amex']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="MinimumFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_monthly_minimum']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="ChargebackFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_misc_chargeback']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CreditExpediteFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_startup_expedite']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="VoiceAuth">
            <value><?php echo htmlspecialchars($data['Application']['fees_auth_aru_voice']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="DebitMonthlyAccessFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_monthly_debit_access']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CreditReprogramFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_startup_reprogramming']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="WirelessPerItem">
            <value><?php echo htmlspecialchars($data['Application']['fees_auth_wireless']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="EBTAccess">
            <value><?php echo htmlspecialchars($data['Application']['fees_monthly_ebt']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CreditVirtualTrainingFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_startup_training']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="GatewayFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_monthly_gateway_access']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="CreditMobileSetupFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_startup_wireless_activation']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="WirelessFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_monthly_wireless_access']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Tax">
            <value><?php echo htmlspecialchars($data['Application']['fees_startup_tax']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Total">
            <value><?php echo htmlspecialchars($data['Application']['fees_startup_total']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="DebitPerItem">
            <value><?php echo htmlspecialchars($data['Application']['fees_pin_debit_auth']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="EBTDiscRate">
            <value><?php echo htmlspecialchars($data['Application']['fees_ebt_discount']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="DebitDiscountRate">
            <value><?php echo htmlspecialchars($data['Application']['fees_pin_debit_discount']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="EBTTranFee">
            <value><?php echo htmlspecialchars($data['Application']['fees_ebt_auth']); ?></value>
            <locked>true</locked>
        </merge_field>
        
        <?php /* -------------------------------- */ ?>
        <merge_field merge_field_name="Application Date">
            <value><?php echo htmlspecialchars(date("m/d/Y")); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="ContractorID">
            <value><?php echo htmlspecialchars($data['Application']['rep_contractor_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Amex Discount Rate">
            <value><?php echo htmlspecialchars($data['Application']['rep_amex_discount_rate']); ?></value>
            <locked>true</locked>
        </merge_field>
                <?php if ($data['Application']['rep_discount_paid'] == 'monthly'): ?>
        <merge_field merge_field_name="Monthly Discount">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['rep_discount_paid'] == 'daily'): ?>
        <merge_field merge_field_name="Daily Discount">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['rep_business_legitimate'] == 'yes'): ?>
        <merge_field merge_field_name="CheckBox150">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['rep_business_legitimate'] == 'no'): ?>
        <merge_field merge_field_name="CheckBox151">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['rep_photo_included'] == 'yes'): ?>
        <merge_field merge_field_name="CheckBox152">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['rep_photo_included'] == 'no'): ?>
        <merge_field merge_field_name="CheckBox153">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['rep_inventory_sufficient'] == 'yes'): ?>
        <merge_field merge_field_name="CheckBox154">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['rep_inventory_sufficient'] == 'no'): ?>
        <merge_field merge_field_name="CheckBox155">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['rep_goods_delivered'] == 'yes'): ?>
        <merge_field merge_field_name="CheckBox156">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['rep_goods_delivered'] == 'no'): ?>
        <merge_field merge_field_name="CheckBox157">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['rep_bus_open_operating'] == 'yes'): ?>
        <merge_field merge_field_name="CheckBox158">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['rep_bus_open_operating'] == 'no'): ?>
        <merge_field merge_field_name="CheckBox159">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['rep_visa_mc_decals_visible'] == 'yes'): ?>
        <merge_field merge_field_name="CheckBox160">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['rep_visa_mc_decals_visible'] == 'no'): ?>
        <merge_field merge_field_name="CheckBox161">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <?php if ($data['Application']['rep_mail_tel_activity'] == 'yes'): ?>
        <merge_field merge_field_name="CheckBox162">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php elseif ($data['Application']['rep_mail_tel_activity'] == 'no'): ?>
        <merge_field merge_field_name="CheckBox163">
            <value>X</value>
            <locked>true</locked>
        </merge_field>
        <?php endif; ?>
        <merge_field merge_field_name="site_survey_signature">
            <value><?php echo htmlspecialchars($data['Application']['site_survey_signature']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="site_survey_date">
            <value><?php echo htmlspecialchars(date('m/d/Y')); ?></value>
            <locked>true</locked>
        </merge_field>
    </merge_fields>
    <?php /*
    <tags>
        <tag>
            <name>sent_from_api</name>
        </tag>
        <tag>
            <name>user_id</name>
            <value>123456</value>
        </tag>
    </tags>
    */ ?>
    <callback_location><? echo "http://" . $_SERVER['SERVER_NAME'] . "/applications/document_callback" ?></callback_location>
</template>
