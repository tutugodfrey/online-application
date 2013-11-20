<?php
class Merchant extends AppModel {
	public $useTable = 'merchant';
	public $primaryKey = 'merchant_id';
	public $displayField = 'merchant_dba';
        public $hasMany = array(
            'EquipmentProgramming' => array(
            'className' => 'EquipmentProgramming',
            'foreignKey' => 'merchant_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
            'TimelineEntry' => array(
            'className' => 'TimelineEntry',
            'foreignKey' => 'merchant_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
                )
    );
        /*
	public $validate = array(
		'key' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'value' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'description' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);*/
        
        function import($id, $mid) {
        $applicationData = $this->Application->findById($id);
        if ($data['Application']['ownership_type'] == 'corporation'){
            $data['Application']['ownership_type'] = 'Corporation';
        }
        if ($data['Application']['ownership_type'] == 'sole prop'){
             $data['Application']['ownership_type'] = 'Sole Prop';
        }
        if ($data['Application']['ownership_type'] == 'llc'){
            $data['Application']['ownership_type'] = 'Limited Liability';
        }
        if ($data['Application']['ownership_type'] == 'partnership'){
            $data['Application']['ownership_type'] = 'Partnership';
        }
        if ($data['Application']['ownership_type'] == 'non profit'){
            $data['Application']['ownership_type'] = 'Exempt';
        }
        if ($data['Application']['business_type'] == 'retail') {}
    if ($data['Application']['business_type'] == 'restaurant') {}
    if ($data['Application']['business_type'] == 'lodging') {}
    if ($data['Application']['business_type'] == 'MOTO') {}
    if ($data['Application']['business_type'] == 'internet') {}
    if ($data['Application']['business_type'] == 'grocery') {}
        $merchantData = array(
            'Merchant' => array(
                'merchant_id' => $mid,
                'user_id' => $applicationData['Application'][''],
                'merchant_mid' => $mid,
                'merchant_name' => $applicationData['Application'][''],
                'merchant_dba' => $applicationData['Application'][''],
                'merchant_contact' => $applicationData['Application'][''],
                'merchant_email' => $applicationData['Application'][''],
                'merchant_ownership_type' => $applicationData['Application']['ownership_type'],
                'merchant_fed_tax_id' => $applicationData['Application'][''],
//                'merchant_d_and_b' => $applicationData['Application'][''],
//                'inactive_date' => $applicationData['Application'][''],
                'active_date' => $applicationData['Application'][''],
                'ref_seq_number' => $applicationData['Application'][''],
                'network_id' => $applicationData['Application'][''],
                'merchant_buslevel' => $applicationData['Application'][''],
                'merchant_sic' => $applicationData['Application'][''],
                'entity' => $applicationData['Application'][''],
                'group_id' => $applicationData['Application'][''],
                'merchant_bustype' => $applicationData['Application'][''],
                'merchant_url' => $applicationData['Application'][''],
                'merchant_fed_tax_id_disp' => $applicationData['Application'][''],
//                'merchant_d_and_b_disp' => $applicationData['Application'][''],
                'active' => 1,
                'cancellation_fee_id' => $applicationData['Application'][''],
                'merchant_contact_position' => $applicationData['Application'][''],
                'merchant_mail_contact' => $applicationData['Application'][''],
                'res_seq_number' => $applicationData['Application'][''],
                'bin_id' => $applicationData['Application'][''],
                'acquirer_id' => $applicationData['Application'][''],
                'reporting_user' => $applicationData['Application'][''],
                'merchant_ps_sold' => $applicationData['Application'][''],
                'ref_p_type' => $applicationData['Application'][''],
                'ref_p_value' => $applicationData['Application'][''],
                'res_p_type' => $applicationData['Application'][''],
                'res_p_value' => $applicationData['Application'][''],
                'ref_p_pct' => $applicationData['Application'][''],
                'res_p_pct' => $applicationData['Application'][''],
                'onlineapp_application_id' => $applicationData['Application'][''],
                'partner_id' => $applicationData['Application'][''],
                'partner_exclude_volume' => $applicationData['Application']['']
        ));
        $this->create($merchantData);
    }
            

            function spImportMerchant($values, $salt) {
	//create merchant object
        $merch = new AxiaMerchant(trim($values[getXcelKey('MID')]));
   $merch->loadData('BANK');
   $merch->loadData('TIMELINE');
   $merch->loadData('OWNER');
   $merch->loadData('ACCOUNT_INFO');
   $merch->loadData('ADDRESS_BUSINESS');
   $merch->loadData('ADDRESS_CORPORATE');
   $merch->loadData('ADDRESS_MAIL');
   $merch->loadDataProducts();

	// account info
	$rep = new AxiaUser($merch->rep);
	$rep->loadData('PROFILE');
	$merch->appLocation = $rep->entity;

	$merch->ID = trim($values[getXcelKey('MID')]);
	$merch->active = 1; //set merchant to active
	$merch->name = NULL;
	$merchRep = spUserGetIdByName(trim($values[getXcelKey('ContractorID')]));
	$merch->rep = $merchRep[NAME_USER_ID];
	$merch->repName = NULL;
	$merch->group = NULL;

	$merch->MID = trim($values[getXcelKey('MID')]);
	$merch->DBA = trim($values[getXcelKey('DBA')]);
	$merch->contact = trim($values[getXcelKey('Contact')]);
        if (trim($values[getXcelKey('Title')]) !=""){
        $merch->contactPosition = trim($values[getXcelKey('Title')]);
        }
        if (trim($values[getXcelKey('LocTitle')]) !=""){
        $merch->contactPosition = trim($values[getXcelKey('LocTitle')]);
        }
	$merch->email = trim($values[getXcelKey('EMail')]);
	$merch->url = trim($values[getXcelKey('WebAddress')]);
	$merch->reportingUser = trim($values[getXcelKey('ReportingUser')]);
	$merch->referer = NULL;
	$merch->refererDesc = NULL;
	$merch->refererPct = NULL;
	$merch->bustype = trim($values[getXcelKey('BusinessType')]);
        $merch->pssold = trim($values[getXcelKey('Products Services Sold')]);
	if (trim($values[getXcelKey('BusinessLevel')]) !=""){
        $merch->buslevel = trim($values[getXcelKey('BusinessLevel')]);
        }
        if (trim($values[getXcelKey('Retail')]) == On){
        $merch->buslevel = 'Retail';
        }
        if (trim($values[getXcelKey('Restaurant')]) == On){
        $merch->buslevel = 'Restaurant';
        }
        if (trim($values[getXcelKey('Lodging')]) == On){
        $merch->buslevel = 'Lodging';
        }
        if (trim($values[getXcelKey('MOTO')]) == On){
        $merch->buslevel = 'MOTO';
        }
        if (trim($values[getXcelKey('Internet')]) == On){
        $merch->buslevel = 'Internet';
        }
        if (trim($values[getXcelKey('Grocery')]) == On){
        $merch->buslevel = 'Grocery';
        }
	$merch->SIC = trim($values[getXcelKey('SIC')]);
	$merchNetworkName = trim($values[getXcelKey('TID')]);
	if(trim($merchNetworkName) == "Imperial-Envoy") $merchNetworkName = "ENVOY";
	else if(strtoupper(trim($merchNetworkName)) == "GENSAR") $merchNetworkName = "PTI";
	$merchNetwork = spMerchantGetNetworkIdByName($merchNetworkName);
	$merch->network = $merchNetwork[NAME_NETWORK_ID];


	// business / site address
	$merch->addressBusTitle = trim($values[getXcelKey('DBA')]);
	$merch->addressBusStreet = trim($values[getXcelKey('Address')]);
	$merch->addressBusCity = trim($values[getXcelKey('City')]);
	$merch->addressBusState = strtoupper(trim($values[getXcelKey('State')]));
	$merch->addressBusZip = trim($values[getXcelKey('Zip')]);
	$merch->addressBusPhone = trim($values[getXcelKey('PhoneNum')]);
	$merch->addressBusFax = trim($values[getXcelKey('FaxNum')]);
	// corporate address
	$merch->addressCorpTitle = trim($values[getXcelKey('CorpName')]);
	$merch->addressCorpStreet = trim($values[getXcelKey('CorpAddress')]);
	$merch->addressCorpCity = trim($values[getXcelKey('CorpCity')]);
	$merch->addressCorpState = strtoupper(trim($values[getXcelKey('CorpState')]));
	$merch->addressCorpZip = trim($values[getXcelKey('CorpZip')]);
	$merch->addressCorpPhone = trim($values[getXcelKey('CorpPhone')]);
	$merch->addressCorpFax = trim($values[getXcelKey('CorpFax')]);
	// mail address
	$merch->addressMailTitle = trim($values[getXcelKey('CorpName')]);
	$merch->addressMailStreet = trim($values[getXcelKey('CorpAddress')]);
	$merch->addressMailCity = trim($values[getXcelKey('CorpCity')]);
	$merch->addressMailState = strtoupper(trim($values[getXcelKey('CorpState')]));
	$merch->addressMailZip = trim($values[getXcelKey('CorpZip')]);
        //$merch->email = trim($values[getXcelKey('Email')]);
	$merch->mailContact = trim($values[getXcelKey('Principal')]);
        //$merch->contactPosition = trim($values[getXcelKey('Title2')]);

	//owner info
        if (trim($values[getXcelKey('Owner Type - Corp')]) == "Yes"){
            $merch->ownershipType = 'Corporation';
        }
        if (trim($values[getXcelKey('Owner Type - Sole Prop')]) == "Yes"){
             $merch->ownershipType = 'Sole Proprietor';
        }
        if (trim($values[getXcelKey('Owner Type - LLC')]) == "Yes"){
            $merch->ownershipType = 'Corporation - LLC';
        }
        if (trim($values[getXcelKey('Owner Type - Partnership')]) == "Yes"){
            $merch->ownershipType = 'Partnership';
        }
        if (trim($values[getXcelKey('Owner Type - Non Profit')]) == "Yes"){
            $merch->ownershipType = 'Tax Expempt (501c)';
        }
        if (trim($values[getXcelKey('Owner Type - Other')]) == "Yes"){
            $merch->ownershipType = 'Other';
        }
        if (trim($values[getXcelKey('CorpStatus')]) !=""){
	$merch->ownershipType = trim($values[getXcelKey('CorpStatus')]);
        }
	$merch->fedTaxID = bin2hex(encrypt(trim($values[getXcelKey('TaxID')]), $salt));
	$merch->fedTaxIDDisp = substr(trim($values[getXcelKey('TaxID')]), -4);
	$merch->dAndB = NULL;
	$merch->dAndBDisp = NULL;
	$owner = array();
	$owner[NAME_OWNER_NAME] = trim($values[getXcelKey('Principal')]);
	$owner[NAME_OWNER_TITLE] = trim($values[getXcelKey('Title')]);
	$owner[NAME_OWNER_EQUITY] = trim($values[getXcelKey('OwnerEquity')]);
	$owner[NAME_OWNER_SSN] = bin2hex(encrypt(trim($values[getXcelKey('OwnerSSN')]), $salt));
	$owner[NAME_OWNER_SSN_DISPLAY] = substr(trim($values[getXcelKey('OwnerSSN')]), -4);
	$merch->arrOwners = array(); //clean out array
	array_push($merch->arrOwners, $owner);
	//timeline info
	//fxed timeline

	$timelineFaxed = array();
	$timelineFaxed[NAME_TIMELINE_ID] = "FAX";
	list($tMonth, $tDay, $tYear) = split("/", trim($values[getXcelKey('AppRecdDate')])); //breakup the date
	$timelineFaxed[NAME_TIMELINE_COMPLETED] = $tYear . "-" . $tMonth . "-" . $tDay;
	$merch->arrTimeline[$timelineFaxed[NAME_TIMELINE_ID]] = $timelineFaxed[NAME_TIMELINE_COMPLETED];
	//approved timeline
	if(trim($values[getXcelKey('CreditApprovalDate')]) != "") {
		$timelineApproved = array();
		$timelineApproved[NAME_TIMELINE_ID] = "APP";
		list($tMonth, $tDay, $tYear) = split("/", trim($values[getXcelKey('CreditApprovalDate')])); //breakup the date
		$timelineApproved[NAME_TIMELINE_COMPLETED] = $tYear . "-" . $tMonth . "-" . $tDay;
		$merch->arrTimeline[$timelineApproved[NAME_TIMELINE_ID]] = $timelineApproved[NAME_TIMELINE_COMPLETED];
	}
	//declined timeline (if not approved)
	else if (trim($values[getXcelKey('DeclinedDate')]) != "") {
		$timelineDeclined = array();
		$timelineDeclined[NAME_TIMELINE_ID] = "DEC";
		list($tMonth, $tDay, $tYear) = split("/", trim($values[getXcelKey('DeclinedDate')])); //breakup the date
		$timelineDeclined[NAME_TIMELINE_COMPLETED] = $tYear . "-" . $tMonth . "-" . $tDay;
		$merch->arrTimeline[$timelineDeclined[NAME_TIMELINE_ID]] = $timelineDeclined[NAME_TIMELINE_COMPLETED];
	}


	// bank info
	$merch->bankName = addslashes(trim($values[getXcelKey('BankName')]));
	$merch->bankStreet = trim($values[getXcelKey('BankAddress')]);
	$merch->bankCity = trim($values[getXcelKey('BankCity')]);
	$merch->bankState = trim($values[getXcelKey('BankState')]);
	$merch->bankZip = trim($values[getXcelKey('BankZip')]);
	$merch->bankPhone = trim($values[getXcelKey('BankPhone')]);
	$merch->bankRoutingNumber = bin2hex(encrypt(trim($values[getXcelKey('RoutingNum')]), $salt));
	$merch->bankRoutingNumberDisp = substr(trim($values[getXcelKey('RoutingNum')]), -4);
	$merch->bankDDANumber = bin2hex(encrypt(trim($values[getXcelKey('AccountNum')]), $salt));
	$merch->bankDDANumberDisp = substr(trim($values[getXcelKey('AccountNum')]), -4);

	$merch->feesRoutingNumber = bin2hex(encrypt(trim($values[getXcelKey('FeesRoutingNum')]), $salt));
	$merch->feesRoutingNumberDisp = substr(trim($values[getXcelKey('FeesRoutingNum')]), -4);
	$merch->feesDDANumber = bin2hex(encrypt(trim($values[getXcelKey('FeesAccountNum')]), $salt));
	$merch->feesDDANumberDisp = substr(trim($values[getXcelKey('FeesAccountNum')]), -4);
        
	$betMc = "";
	$betVisa = "";
	$betMc = trim($values[getXcelKey('BETMCInterchange')]);
		if($betMc == "5120")
			$betVisa = str_replace("5120","7120",$betMc);
		if($betMc == "5121")
			$betVisa = str_replace("5121","7121",$betMc);
		if($betMc == "5122")
			$betVisa = str_replace("5122","7122",$betMc);
		if($betMc == "5123")
			$betVisa = str_replace("5123","7123",$betMc);
		if($betMc == "5143")
			$betVisa = str_replace("5143","7143",$betMc);
		if($betMc == "5149")
			$betVisa = str_replace("5149","7149",$betMc);
		if($betMc == "6068")
			$betVisa = str_replace("6068","8068",$betMc);

	$merch->bcBetCode =  $betMc . "/" . $betVisa;


	//T and E numbers
	if(trim($values[getXcelKey('AmexNum')]) != "") {
		$merch->bcTeAmexNumber = bin2hex(encrypt(trim($values[getXcelKey('AmexNum')]), $salt));
		$merch->bcTeAmexNumberDisp = substr(trim($values[getXcelKey('AmexNum')]), -4);
	    $merch->bcTeAmexAuth = trim($values[getXcelKey('AmexPerItem')]);
	    $triggerProductTe = true;
	}
	if(trim($values[getXcelKey('DinersNum')]) != "") {
	   $merch->bcTeDinersNumber = bin2hex(encrypt(trim($values[getXcelKey('DinersNum')]), $salt));
	   $merch->bcTeDinersNumberDisp = substr(trim($values[getXcelKey('DinersNum')]), -4);
	   $merch->bcTeDinersAuth = trim($values[getXcelKey('DinersPerItem')]);
	   $triggerProductTe = true;
	}
	if(trim($values[getXcelKey('DiscoverNum')]) != "") {
	   $merch->bcTeDiscNumber = bin2hex(encrypt(trim($values[getXcelKey('DiscoverNum')]), $salt));
	   $merch->bcTeDiscNumberDisp = substr(trim($values[getXcelKey('DiscoverNum')]), -4);
	   $merch->bcTeDiscAuth = trim($values[getXcelKey('DiscoverPerItem')]);
	   $triggerProductTe = true;
	}

	//add annual fee?
	if(trim($values[getXcelKey('AnnualFee')]) > 0) {
		$triggerProductAf = true;
	}

	//bank card numbers & fees
	$merch->bcBetCode = trim($values[getXcelKey('BETMCInterchange')]) . "/" . trim($values[getXcelKey('BETVisaInterchange')]);
	$merch->bcMID = trim($values[getXcelKey('MID')]);
	$merch->bcRate = trim($values[getXcelKey('DiscRate1')]);
	$merch->bcPerItem = trim($values[getXcelKey('TranFee')]);
	$merch->bcARU = trim($values[getXcelKey('ARU')]);
	$merch->bcPtMonthlySupport = trim($values[getXcelKey('TerminalMthlySupportFee')]);
	$merch->bcVtMonthSupport = trim($values[getXcelKey('VTerminalMthlySupportFee')]);
	$merch->bcPtOnlineMerReport = trim($values[getXcelKey('OnlineRptFee')]);
	$merch->bcVtApplication = trim($values[getXcelKey('VTerminalAppFee')]);
	$merch->bcVtExpedite = trim($values[getXcelKey('VTerminalExpediteFee')]);
		//lease deposit goes here
	$merch->bcVtPhysProdTeleTrain = trim($values[getXcelKey('VTerminalVirtualTrainingFee')]);
	$merch->bcPtApplication = trim($values[getXcelKey('CreditAppFee')]);
	$merch->bcPtEquip = trim($values[getXcelKey('CreditEquipmentFee')]);
	$merch->bcPtExpedite = trim($values[getXcelKey('CreditExpediteFee')]);
	$merch->bcPtMobileSetup = trim($values[getXcelKey('CreditMobileSetupFee')]);
	$merch->bcPtEquipReprog = trim($values[getXcelKey('CreditReprogramFee')]);
	$merch->bcPtPhysProdTeleTrain = trim($values[getXcelKey('CreditVirtualTrainingFee')]);
	$merch->bcStatement = trim($values[getXcelKey('StatementFee')]);
	$merch->bcPtMobileAccess = trim($values[getXcelKey('MobileFee')]);
	$merch->bcAnnual = trim($values[getXcelKey('AnnualFee')]);
	$merch->bcVtGateway = trim($values[getXcelKey('GatewayFee')]);
	$merch->bcChargeback = trim($values[getXcelKey('ChargebackFee')]);
	$merch->bcMinMonthProcess = trim($values[getXcelKey('MinimumFee')]);
	$merch->bcAverageTicket = trim($values[getXcelKey('AvgTicket')]);
	$merch->bcMonthlyVolume = trim($values[getXcelKey('MonthlyVol')]);
	$merch->bcMaxTransAmount = trim($values[getXcelKey('MaxSalesAmt')]);
	$merch->bcPtMobileTrans = trim($values[getXcelKey('MobileTran')]);
        $merch->bcARU = trim($values[getXcelKey('VoiceAuth')]);
	$merch->bcVoiceAuth = trim($values[getXcelKey('VoiceAuth')]);
        $merch->bcCardPresent = trim($values[getXcelKey('Card Present Swiped')]);
        $merch->bcCardNotPresentInternet = trim($values[getXcelKey('Card Not Present - Internet')]);
        $merch->bcCardNotPresent = trim($values[getXcelKey('Card Not Present - Keyed')]);
        $merch->bcCardImprint = trim($values[getXcelKey('Card Present Imprint')]);
        $merch->bcDirectToConsumer = trim($values[getXcelKey('Direct to Consumer')]);
        $merch->bcBusToBus = trim($values[getXcelKey('Direct to Business')]);
        $merch->bcGovt = trim($values[getXcelKey('Direct to Government')]);
        if(trim($values[getXcelKey('MobileTran')]) != 0 || trim($values[getXcelKey('MobileFee')]) != 0) {
            $merch-> bcWireless = 1;
        }
        

	//debit fees
	$merch->dbtTransaction = trim($values[getXcelKey('DebitTranFee')]);
	$merch->dbtMonthly = trim($values[getXcelKey('DebitMonthlyAccessFee')]);
        
	//ebt fees
	$merch->ebtTransaction = trim($values[getXcelKey('EBTTranFee')]);
	$merch->ebtMonthly = trim($values[getXcelKey('EBTStmtFee')]);
        $merch->oaID = trim($values[getXcelKey('oaID')]);
        

	//let's do some basic error checking
	$msg = '';

	if($merch->MID == "") {
		$msg .= "<span style=\"color: #990000; font-weight: bold;\">Unable to load " . $merch->DBA . ": Missing Merchant ID!</span><br>\n";
	}
	else if($merch->rep == "") {
		$msg .= "<span style=\"color: #990000; font-weight: bold;\">Unable to load " . $merch->DBA . " (" . $merch->MID . "): Rep not in database!</span><br>\n";
	}

    // need to insert merchant?
    if ($msg == '') {
	    // if merchant record does not exist, add before update
	    $query = "SELECT count(*) as exists FROM \"merchant\"";
	    $query .= " WHERE merchant_mid = '" . $merch->MID . "'";
	    $result = dbGetResultsSingle($query);
	    if ($result['exists'] == 0) {
	        $merch->putNewMerchant();
         if (strlen($merch->MID) == 16 ) {       
                $password = LibCrypt::crypt(substr(base_convert(md5(uniqid(mt_rand(), true)), 16, 36), 0, 8), true);
        $sql  = "INSERT INTO saq_merchant (id, merchant_id, password, merchant_email, merchant_name) VALUES (";
        $sql .= "NEXTVAL('saq_merchant_id_seq'), ";
        $sql .= "'" . $merch->MID . "'" . ", ";
        $sql .= "'" . $password . "'" . ", ";
        $sql .= "'" . $merch->email . "'" . ", ";
        $sql .= "$$" . $merch->mailContact . "$$" . "";
        $sql .= ")";
        $db = dbQueryNoResults($sql);
	//also insert them into the merchant_pci table with defaults
         
	$sql = "INSERT INTO merchant_pci (merchant_id, compliance_level, insurance_fee, scanning_company) VALUES (";
	$sql .= $merch->MID . ",";
	$sql .= "4,6.95,'Control Scan')";
        }
	$db = dbQueryNoResults($sql);
        


	        // -------------------------------------------------------------------------------------
	        // added by jeremy 12/9/03
	        // axia invoice added for new merchants
	        // -------------------------------------------------------------------------------------
	        if (is_numeric($merch->bcPtEquip) && $merch->bcPtEquip > 0) {
	            $merch->putACH(-1, date("Y-m-d"), '', $merch->bcPtEquip, 'INT', '', CFG_STS_PENDING, CFG_CRDB_DEBIT, $merch->rep, 'AUTO', '', 1, '');
	        }
            $merch->putACH(-1, date("Y-m-d"), '', $merch->bcPtApplication, 'APP', '', CFG_STS_PENDING, CFG_CRDB_DEBIT, $merch->rep, 'AUTO', '', 1, '');
	    }
		    // update info
		    $merch->putAccountInfoFromImport();
                    $merch->putAddress(CFG_ADDRTYPE_CORPORATE);
		    $merch->putAddress(CFG_ADDRTYPE_BUSINESS);
			 $merch->putTimeline();
			 $merch->putBankcard();
			 $merch->putBankInfo();
			 $merch->putOwnership();
 			 $merch->putOwnershipSSN();
			 $merch->putTaxIDandDB();
			 $merch->putBankRoutingDDA();
			 $merch->putBankcardTandE();

			 if($merch->dbtTransaction != 0 || $merch->dbtMonthly != 0) {
			 	 $merch->putDebit();
			 }
			 if($merch->ebtTransaction != 0 || $merch->ebtMonthly != 0) {
			 	 $merch->putEBT();
			 }
			 //if t&e triggered
			 if($triggerProductTe === true) {
			 	spMerchantAddProduct($merch->MID, CFG_PRODTYPE_TE);
			 }
			 //if annual fee triggered
			 if($triggerProductAf === true) {
			 	spMerchantAddProduct($merch->MID, CFG_PRODTYPE_ANNUALFEE);
			 }
		 $msg .= $merch->DBA . " (" . $merch->MID . ") imported into database.<br>\n";
                 
    }
	return $msg;
        }
        function spUserGetIdByName($fullname) {
	list($firstname, $lastname, $nobility) = split(" ", $fullname);
	//tack on last part (Sr., Jr., III, IV) if it exists
	if($nobility != "")
		$lastname .= " " . $nobility;
	#Special Cases
	//if only one name, this must be an orginization
	if (strtolower($fullname) == "house") {
		$firstname = "Erik";
		$lastname = "Krueger";
	}
	else if ($fullname == "Josh James Larkin") {
		$firstname = "Staff";
		$lastname = "(int)";
	}            
        else if ($fullname == "Susan Wigeri Van Edema") {
		$firstname = "Susan";
		$lastname = "Wigeri Van Edema";
	}
	else if ($nobility == "Novak") {
		$lastname = $nobility;
	}
	else if ($nobility == "Schlobohm") {
		$firstname = "Jon Ryan";
		$lastname = "Scholbohm";
	}
	else if ($nobility == "Teixeira") {
		$lastname = $nobility;
	}
	else if ($firstname == "Christopher") {
		$firstname = "Chris";
	}
	else if ($nobility == "Patterson") {
		$lastname = $nobility;
	}
	else if ($nobility == "Cook") {
		$lastname = $nobility;
	}
	else if ($nobility == "McLanhorn") {
		$lastname = $nobility;
	}
	else if ($nobility == "Falconer") {
		$lastname = $nobility;
	}
	else if ($lastname == "Martin") {
		$firstname = "Robert";
		$lastname = "Martin, Jr.";
	}
	else if ($firstname == "William" && $lastname == "McAbee") {
		$firstname = "Bill";
	}
	else if ($firstname == "Matthew" && $lastname == "Diehl") {
		$firstname = "James";
		$lastname = "Diel";
	}
	else if($lastname == "") {
		$lastname .= "(org)";
	}
        $result = $this->query("SELECT user_id FROM \"user\" WHERE LOWER(user_first_name) = '" . strtolower($firstname) . "' AND LOWER(user_last_name) = '" . strtolower($lastname) . "';");

	return $result;
}
}
?>