<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">
<?php echo '<f href="https://app.axiapayments.com/axia_' . $data['Coversheet']['id'] . '_coversheet.pdf"/>';?>
<fields>
<field name="10">
<field name="1"><value><?php echo ($data['Coversheet']['micros_billing'] == 'statement')? 'Yes': 'Off';?></value></field>
<field name="2"><value><?php echo ($data['Coversheet']['micros_billing'] == 'merchant')? 'Yes': 'Off';?></value></field>
<field name="3"><value><?php echo ($data['Coversheet']['micros_billing'] == 'rep')? 'Yes': 'Off';?></value></field>
</field>
<field name="11">
<field name="1"><value><?php echo ($data['Coversheet']['gateway_billing'] == 'statement')? 'Yes': 'Off';?></value></field>
<field name="2"><value><?php echo ($data['Coversheet']['gateway_billing'] == 'merchant')? 'Yes': 'Off';?></value></field>
<field name="3"><value><?php echo ($data['Coversheet']['gateway_billing'] == 'rep')? 'Yes': 'Off';?></value></field>
</field>
    <?php if($cp === false) { ?>
<field name="12">
<field name="1"><value><?php echo ($data['Application']['currently_accept_amex'] == 'yes') ? 'Yes' : 'Off';?></value></field>
<field name="2"><value><?php echo ($data['Application']['currently_accept_amex'] == 'no') ? 'Yes' : 'Off';?></value></field>
<field name="3"><value><?php echo ($data['Application']['currently_accept_amex'] == 'no' && $data['Application']['want_to_accept_amex'] == 'yes')? 'Yes': 'Off';?></value></field>
</field>
<field name="13">
<field name="1"><value><?php echo ($data['Application']['want_to_accept_discover'] == 'yes')? 'Yes': 'Off';?></value></field>
<field name="2"><value><?php echo ($data['Application']['want_to_accept_discover'] == 'no')? 'Yes': 'Off';?></value></field>
</field>
<field name="14">
<field name="1"><value><?php echo ($data['Coversheet']['moto_online_chd'] == 'yes')? 'Yes': 'Off';?></value></field>
<field name="2"><value><?php echo ($data['Coversheet']['moto_online_chd'] == 'no')? 'Yes': 'Off';?></value></field>
</field>
    <?php } if($cp === true) { ?>
<field name="1_N"><value><?php echo ($data['Application']['term1_accept_debit'] == 'no')? 'Yes': 'Off';?></value></field>
<field name="1_Y"><value><?php echo ($data['Application']['term1_accept_debit'] == 'yes')? 'Yes': 'Off';?></value></field>
<field name="2_N"><value><?php echo ($data['Application']['term1_use_autoclose'] == 'no')? 'Yes': 'Off';?></value></field>
<field name="2_P"><value><?php echo ($data['Coversheet']['cp_pinpad_ra_attached'] === true)? 'Yes': 'Off';?></value></field>
<field name="2_Y"><value><?php echo ($data['Application']['term1_use_autoclose'] == 'yes')? 'Yes': 'Off';?></value></field>
<?php } ?>
<field name="3MoStmts"><value><?php echo ($data['Coversheet']['setup_banking'] === true)? 'Yes': 'Off';?></value></field>
    <?php if($cp === true) { ?>
<field name="3_N"><value><?php echo ($data['Coversheet']['cp_giftcards'] == 'no')? 'Yes': 'Off';?></value></field>
<field name="3_Y"><value><?php echo ($data['Coversheet']['cp_giftcards'] == 'yes')? 'Yes': 'Off';?></value></field>
<field name="4_N"><value><?php echo ($data['Coversheet']['cp_check_guarantee'] == 'no')? 'Yes': 'Off';?></value></field>
<field name="4_Y"><value><?php echo ($data['Coversheet']['cp_check_guarantee'] == 'yes')? 'Yes': 'Off';?></value></field>
<field name="5_N"><value><?php echo ($data['Application']['currently_accept_amex'] == 'no') ? 'Yes' : 'Off';?></value></field>
<field name="5_NEW"><value><?php echo ($data['Application']['currently_accept_amex'] == 'no' && $data['Application']['want_to_accept_amex'] == 'yes')? 'Yes': 'Off';?></value></field>
<field name="5_Y"><value><?php echo ($data['Application']['currently_accept_amex'] == 'yes') ? 'Yes' : 'Off';?></value></field>
<field name="6_N"><value><?php echo ($data['Application']['want_to_accept_discover'] == 'no')? 'Yes': 'Off';?></value></field>
<field name="6_Y"><value><?php echo ($data['Application']['want_to_accept_discover'] == 'yes')? 'Yes': 'Off';?></value></field>
<field name="7_N"><value><?php echo ($data['Coversheet']['cp_pos'] == 'no')? 'Yes': 'Off';?></value></field>
<field name="7_Y"><value><?php echo ($data['Coversheet']['cp_pos'] == 'yes')? 'Yes': 'Off';?></value></field>
<field name="8_N"><value><?php echo ($data['Application']['term1_programming_server_nums'] === false)? 'Yes': 'Off';?></value></field>
<field name="8_Y"><value><?php echo ($data['Application']['term1_programming_server_nums'] === true)? 'Yes': 'Off';?></value></field>
<field name="9_N"><value><?php echo ($data['Application']['term1_programming_tips'] === false)? 'Yes': 'Off';?></value></field>
<field name="9_Y"><value><?php echo ($data['Application']['term1_programming_tips'] === true)? 'Yes': 'Off';?></value></field>
<?php } ?>
<field name="Banking"><value><?php echo ($data['Coversheet']['setup_banking'] === true)? 'Yes': 'Off';?></value></field>
<field name="BusLicenseUtilityBill"><value>><?php echo ($data['Coversheet']['setup_business_license'] === true)? 'Yes': 'Off';?></value></field>
<field name="Company"><value><?php echo htmlspecialchars($data['Coversheet']['moto_company']); ?></value></field>
<field name="Contact"><value><?php echo htmlspecialchars($data['Coversheet']['moto_contact']); ?></value></field>
<field name="CustomerDB"><value><?php echo ($data['Coversheet']['gateway_gold_subpackage'] == 'cust_db')? 'Yes': 'Off';?></value></field>
<field name="DebitMoItem"><value><?php echo htmlspecialchars($data['Coversheet']['setup_item_count']); ?></value></field>
<field name="DebitVolume"><value><?php echo htmlspecialchars($data['Coversheet']['setup_debit_volume']); ?></value></field>
<field name="Driver's License"><value><?php echo ($data['Coversheet']['setup_drivers_license'] === true)? 'Yes': 'Off';?></value></field>
<field name="Email"><value><?php echo htmlspecialchars($data['Coversheet']['moto_email']); ?></value></field>
<field name="Equipment_ACH"><value><?php echo ($data['Coversheet']['setup_equipment_payment'] == 'ach')? 'Yes': 'Off';?></value></field>
<field name="Equipment_Lease"><value><?php echo ($data['Coversheet']['setup_equipment_payment'] == 'lease')? 'Yes': 'Off';?></value></field>
<field name="Equipment_Months"><value><?php echo htmlspecialchars($data['Coversheet']['setup_lease_months']); ?></value></field>
<field name="Equipment_MonthlyFee"><value><?php echo htmlspecialchars($data['Coversheet']['setup_lease_price']); ?></value></field>
<field name="ExistingAMEX"><value><?php echo htmlspecialchars($data['Application']['existing_se_num']); ?></value></field>
<field name="Fraud"><value><?php echo ($data['Coversheet']['gateway_gold_subpackage'] == 'fraud')? 'Yes': 'Off';?></value></field>
<field name="Gateway"><value><?php echo htmlspecialchars($data['Coversheet']['moto_gateway']); ?></value></field>
<field name="Gold"><value><?php echo ($data['Coversheet']['gateway_package'] == 'gold')? 'Yes': 'Off';?></value></field>
<field name="If applicable please fill in name of web developer  Gateway"><value><?php echo htmlspecialchars($data['Coversheet']['moto_developer']); ?></value></field>
    <?php if($cp === true) { ?>
<field name="Info"><value><?php echo htmlspecialchars($data['Coversheet']['cp_check_guarantee_info']); ?></value></field>
<?php } ?>
<field name="Install_Axia"><value><?php echo ($data['Coversheet']['setup_install'] == 'axia')? 'Yes': 'Off';?></value></field>
<field name="Install_POS"><value><?php echo ($data['Coversheet']['setup_install'] == 'pos')? 'Yes': 'Off';?></value></field>
<field name="Install_Rep"><value><?php echo ($data['Coversheet']['setup_install'] == 'rep')? 'Yes': 'Off';?></value></field>
    <?php if($cp === true) { ?>
<field name="JRs encryptedSN"><value><?php echo htmlspecialchars($data['Coversheet']['cp_encrypted_sn']); ?></value></field>
<?php } ?>
<field name="Merchant"><value><?php echo htmlspecialchars($data['Application']['dba_business_name']); ?></value></field>
<field name="Micros_Dial"><value><?php echo ($data['Coversheet']['micros'] == 'dial')? 'Yes': 'Off';?></value></field>
<field name="Micros_IP"><value><?php echo ($data['Coversheet']['micros'] == 'ip')? 'Yes': 'Off';?></value></field>
<field name="Option1"><value><?php echo ($data['Coversheet']['gateway_option'] == 'option1')? 'Yes': 'Off';?></value></field>
<field name="Option2"><value><?php echo ($data['Coversheet']['gateway_option'] == 'option2')? 'Yes': 'Off';?></value></field>
<field name="Other"><value><?php echo ($data['Coversheet']['setup_other'] === true)? 'Yes': 'Off';?></value></field>
<field name="OtherText"><value><?php echo htmlspecialchars($data['Coversheet']['setup_field_other']); ?></value></field>
<field name="Notes1"><value><?php echo htmlspecialchars($data['Coversheet']['setup_notes']); ?></value></field>
<field name="Phone"><value><?php echo htmlspecialchars($data['Coversheet']['moto_phone']); ?></value></field>
    <?php if($cp === true) { ?>
<field name="PinPadType"><value><?php echo htmlspecialchars($data['Application']['term1_pin_pad_type']);?></value></field>
<?php } ?>
<field name="POSContactInfo"><value><?php echo htmlspecialchars($data['Coversheet']['cp_pos_contact']);?></value></field>
<field name="POSGateway"><value><?php echo ($data['Coversheet']['setup_equipment_gateway'] === true)? 'Yes': 'Off';?></value></field>
<field name="Platinum"><value><?php echo ($data['Coversheet']['gateway_package'] == 'platinum')? 'Yes': 'Off';?></value></field>
<field name="Ref_BP"><value><?php echo ($data['Coversheet']['setup_referrer_type'] == 'bp')? 'Yes': 'Off';?></value></field>
<field name="Ref_GP"><value><?php echo ($data['Coversheet']['setup_referrer_type'] == 'gp')? 'Yes': 'Off';?></value></field>
<field name="Ref_value"><value><?php echo htmlspecialchars($data['Coversheet']['setup_referrer_pct']); ?></value></field>
<field name="Referrer"><value><?php echo htmlspecialchars($data['Coversheet']['setup_referrer']); ?></value></field>
<field name="RepName"><value><?php echo htmlspecialchars($data['User']['fullname']); ?></value></field>
<field name="Res_BP"><value><?php echo ($data['Coversheet']['setup_reseller_type'] == 'bp')? 'Yes': 'Off';?></value></field>
<field name="Res_GP"><value><?php echo ($data['Coversheet']['setup_reseller_type'] == 'gp')? 'Yes': 'Off';?></value></field>
<field name="Res_Value"><value><?php echo htmlspecialchars($data['Coversheet']['setup_reseller_pct']); ?></value></field>
<field name="Reseller"><value><?php echo htmlspecialchars($data['Coversheet']['setup_reseller']); ?></value></field>
<field name="Silver"><value><?php echo ($data['Coversheet']['gateway_package'] == 'silver')? 'Yes': 'Off';?></value></field>
<field name="StarterKit_Axia"><value><?php echo ($data['Coversheet']['setup_starterkit'] == 'axia')? 'Yes': 'Off';?></value></field>
<field name="StarterKit_Rep"><value><?php echo ($data['Coversheet']['setup_starterkit'] == 'rep')? 'Yes': 'Off';?></value></field>
<field name="Terminal"><value><?php echo ($data['Coversheet']['setup_equipment_terminal'] === true)? 'Yes': 'Off';?></value></field>
<field name="Tier3"><value><?php echo ($data['Coversheet']['setup_tier3'] === true)? 'Yes': 'Off';?></value></field>
<field name="Tier4"><value><?php echo ($data['Coversheet']['setup_tier4'] === true)? 'Yes': 'Off';?></value></field>
<field name="Tier5">
<field name="1"><value><?php echo ($data['Coversheet']['setup_tier5_financials'] === true)? 'Yes': 'Off';?></value></field>
<field name="2"><value><?php echo ($data['Coversheet']['setup_tier5_processing_statements'] === true)? 'Yes': 'Off';?></value></field>
<field name="3"><value><?php echo ($data['Coversheet']['setup_tier5_bank_statements'] === true)? 'Yes': 'Off';?></value></field>
</field>
    <?php if($cp === true) { ?>
<field name="Time"><value><?php echo htmlspecialchars($data['Application']['term1_what_time']);?></value></field>
<?php } ?>
<field name="ePay_N"><value><?php echo ($data['Coversheet']['gateway_epay'] == 'no')? 'Yes': 'Off';?></value></field>
<field name="ePay_Y"><value><?php echo ($data['Coversheet']['gateway_epay'] == 'yes')? 'Yes': 'Off';?></value></field>
</fields>
<ids original="291B2C87DCA55A4CAC8DCAFD67242545" modified="074A08C5ACD10C4182DEE27E650DAB68"/>
</xfdf>
