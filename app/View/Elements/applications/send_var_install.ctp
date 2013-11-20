<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<template>
    <guid><?php echo $document_guid; ?></guid>
    <subject><?php echo htmlspecialchars($data['Application']['dba_business_name']); ?> Axia Install Sheet - VAR</subject>
    <description>Sent for signature by <?php echo $this->Session->read('Auth.User.email'); ?></description>
    <action>send</action>
    <expires_in>10 days</expires_in>
        <roles>
        <role role_name="Signer">
            <name><?php echo htmlspecialchars($data['Application']['owner1_fullname']); ?></name>
            <email><?php echo htmlspecialchars('noemail@rightsignature.com'); ?></email>
            <locked>true</locked>
        </role>
        </roles>
    <merge_fields>
        <merge_field merge_field_name="RepInfo">
            <value><?php echo htmlspecialchars($data['Application']['rep_contractor_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Phone#">
            <?php if(($data['User']['extension']) != "") { ?>
            <value><?php echo htmlspecialchars('877.875.6114' . " x " . $data['User']['extension']); ?></value>
            <?php } else { ?>
            <value><?php echo htmlspecialchars('877.875.6114'); ?></value>
            <?php } ?>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="RepFax#">
            <value><?php echo htmlspecialchars('877.875.5135'); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Merchant">
            <value><?php echo htmlspecialchars($data['Application']['dba_business_name']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="Contact">
            <value><?php echo htmlspecialchars($data['Application']['mailing_zip']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="MerchantPhone">
            <value><?php echo htmlspecialchars($data['Application']['mailing_phone']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="MerchantFax">
            <value><?php echo htmlspecialchars($data['Application']['mailing_fax']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="BusinessAddress">
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
        <merge_field merge_field_name="MID">
            <value><?php echo htmlspecialchars($data['Merchant']['merchant_id']); ?></value>
            <locked>true</locked>
        </merge_field>
        <merge_field merge_field_name="SystemType">
            <?php foreach($data['Merchant']['EquipmentProgramming'] as $programming) { ?>
            <value><?php echo htmlspecialchars($programming['terminal_type']); ?></value>
            <?php } ?>
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