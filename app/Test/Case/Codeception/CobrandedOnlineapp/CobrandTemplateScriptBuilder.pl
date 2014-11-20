#!/usr/bin/perl -w

use strict;


my $cobrand = $ARGV[0] || die "\nmust pass cobrand name as first argument: ./CobrandTemplateScriptBuilder.pl CobrandName\n\n";

my $pages = [
    'General Information',
    'Products and Services Information',
    'ACH Bank and Trade Reference',
    'Set Up Information',
    'Ownership Information',
#    'Merchant Referral Program'
];

my $sections = {
    'General Information' => [
        'OWNERSHIP TYPE',
        'CORPORATE INFORMATION',
        'LOCATION INFORMATION',
        'ADDITIONAL BUSINESS INFORMATION',
        'LOCATION TYPE',
        'MERCHANT'
    ],
    'Products and Services Information' => [
        'General Underwriting Profile',
        'High Volume Months',
        'MOTO/Internet Questionnaire'
    ],
    'ACH Bank and Trade Reference' => [
        'Bank Information',
        'Depository Account',
        'Fees Account',
        'Trade Reference 1',
        'Trade Reference 2'
    ],
    'Set Up Information' => [
        'American Express Information',
        'Discover Information',
        'Terminal/Software Type(1)',
        'Terminal/Software Type(2)'
    ],
    'Ownership Information' => [
        'OWNER / OFFICER (1)',
        'OWNER / OFFICER (2)'
    ],
#    'Merchant Referral Program' => [
#        'Any successful referrals will result in $100 credit to the Merchant bank account provided. Visit our referral program page for details.',
#    ]
};

my $templateMap = {
    'General Information' => {
        'OWNERSHIP TYPE' => [
            "'Ownership Type', \$sectionId, 12, 4, true, 2, 'Corporation::Corp,Sole Prop::SoleProp,LLC::LLC,Partnership::Partnership,Non Profit/Tax Exempt (fed form 501C)::NonProfit,Other::Other', 'OwnerType-', '', null",
            "'Other', \$sectionId, 6, 0, false, 2, '', 'OwnerTypeOther', '', null",
            "'Corporate Status', \$sectionId, 12, 0, false, 2, '', 'CorpStatus', '', null, true"
        ],
        'CORPORATE INFORMATION' => [
            "'Legal Business Name', \$sectionId, 12, 0, true, 2, '', 'CorpName', '', null",
            "'Mailing Address', \$sectionId, 12, 0, true, 2, '', 'CorpAddress', '', null",
            "'City', \$sectionId, 4, 0, true, 2, '', 'CorpCity', '', null",
            "'State', \$sectionId, 4, 20, true, 2, \$FP::STATES, 'CorpState', '', null",
            "'Zip', \$sectionId, 4, 13, true, 2, '', 'CorpZip', '', null",
            "'Phone', \$sectionId, 6, 9, true, 2, '', 'CorpPhone', '', null",
            "'Fax', \$sectionId, 6, 9, false, 2,'', 'CorpFax', '', null",
            "'Corp Contact Name', \$sectionId, 8, 0, true, 2, '', 'CorpContact', '', null",
            "'Title', \$sectionId, 4, 0, true, 2, '', 'Title', '', null",
            "'Email', \$sectionId, 12, 14, true, 2, '', 'EMail', '', null"
        ],
        'LOCATION INFORMATION' => [
            "'Business Name (DBA)', \$sectionId, 12, 0, true, 2, '', 'DBA', '', null",
            "'Location Address', \$sectionId, 12, 0, true, 2, '', 'Address', '', null",
            "'City', \$sectionId, 4, 0, true, 2, '', 'City', '', null",
            "'State', \$sectionId, 4, 20, true, 2, \$FP::STATES, 'State', '', null",
            "'Zip', \$sectionId, 4, 13, true, 2, '', 'Zip', '', null",
            "'Phone', \$sectionId, 6, 9, true, 2, '', 'PhoneNum', '', null",
            "'Fax', \$sectionId, 6, 9, false, 2,'', 'FaxNum', '', null",
            "'Location Contact Name', \$sectionId, 8, 0, true, 2, '', 'Contact', '', null",
            "'Title', \$sectionId, 4, 0, true, 2, '', 'LocTitle', '', null",
            "'Email', \$sectionId, 12, 14, true, 2, '', 'LocEMail', '', null"
        ],
        'ADDITIONAL BUSINESS INFORMATION' => [
            "'Federal Tax ID',\$sectionId, 12, 0, true, 2, '', 'TaxID', '', null, null, true",
            "'Website',\$sectionId, 12, 17, true, 2, '', 'WebAddress', '', null",
            "'Customer Service Phone',\$sectionId, 12, 9, true, 2, '', 'Customer Service Phone', '', null",
            "'Business Open Date', \$sectionId, 12, 1, true, 2, '', 'OpenDate', '', null",
            "'Length of Current Ownership',\$sectionId, 12, 0, true, 2, '', 'Ownership Length', '', null",
            "'Existing Axia Merchant?', \$sectionId, 12, 4, true, 2, 'Yes::Yes,No::No', 'ExistingAxiaMerchant-', '', null",
            "'Current MID #',\$sectionId, 12, 0, false, 2, '', 'Current MID', '', null",
            "'General Comments',\$sectionId, 12, 21, true, 2, '', 'General Comments', '', null"
        ],
        'LOCATION TYPE' => [
            "'Location Type', \$sectionId, 6, 4, true, 2, 'Retail Store::RetailStore,Industrial::Industrial,Trade::Trade,Office::Office,Residence::Residence,Other::SiteInspectionOther', 'LocationType-', '', null",
            "'Other', \$sectionId, 6, 0, false, 2, '', 'LocationTypeOther', '', null"
        ],
        'MERCHANT' => [
            "'Merchant Owns/Leases', \$sectionId, 12,4, true, 2, 'Owns::Owns,Leases::Leases', 'MerchantOwns/Leases-', '', null",
            "'Landlord Name', \$sectionId, 6, 0, false, 2, '', 'Landlord', '', null",
            "'Landlord Phone', \$sectionId, 6, 9, false, 2, '', 'Landlord Phone', '', null"
        ]
    },
    'Products and Services Information' => {
        'General Underwriting Profile' => [
            "'Business Type', \$sectionId, 12, 4, true, 2, 'Retail::Retail,Restaurant::Restaurant,Lodging::Lodging,MOTO::MOTO,Internet::Internet,Grocery::Grocery', 'BusinessType-', '', null",
            "'Products/Services Sold', \$sectionId, 12, 0, true, 2, '', 'Products Services Sold', '', null",
            "'Return Policy', \$sectionId, 12, 0, true, 2, '', 'Return Policy', '', null",
            "'Days Until Product Delivery', \$sectionId, 12, 0, true, 2, '', 'Days Until Product Delivery', '', null",
            "'Monthly Volume', \$sectionId, 4, 10, true, 2, '', 'MonthlyVol', '', null",
            "'Average Ticket', \$sectionId, 4, 10, true, 2, '', 'AvgTicket', '', null",
            "'Highest Ticket', \$sectionId, 4, 10, true, 2, '', 'MaxSalesAmt', '', null",
            "'Current Processor', \$sectionId, 12, 0, true, 2, '', 'Previous Processor', '', null",
            "'Method of Sales', \$sectionId, 6, 5, true, 2, 'Card Present Swiped::CardPresentSwiped{100},Card Present Imprint::CardPresentImprint,Card Not Present (Keyed)::CardNotPresent-Keyed,Card Not Present (Internet)::CardNotPresent-Internet', 'MethodofSales-', '', null",
            "'% of Product Sold', \$sectionId, 6, 5, true, 2, 'Direct To Customer::DirectToCustomer{100},Direct To Business::DirectToBusiness,Direct To Government::DirectToGovernment', '%OfProductSold', '', null"
        ],
        'High Volume Months' => [
            "'Jan', \$sectionId, 1, 3, false, 2, '', 'Jan', '', null",
            "'Feb', \$sectionId, 1, 3, false, 2, '', 'Feb', '', null",
            "'Mar', \$sectionId, 1, 3, false, 2, '', 'Mar', '', null",
            "'Apr', \$sectionId, 1, 3, false, 2, '', 'Apr', '', null",
            "'May', \$sectionId, 1, 3, false, 2, '', 'May', '', null",
            "'Jun', \$sectionId, 1, 3, false, 2, '', 'Jun', '', null",
            "'Jul', \$sectionId, 1, 3, false, 2, '', 'Jul', '', null",
            "'Aug', \$sectionId, 1, 3, false, 2, '', 'Aug', '', null",
            "'Sep', \$sectionId, 1, 3, false, 2, '', 'Sep', '', null",
            "'Oct', \$sectionId, 1, 3, false, 2, '', 'Oct', '', null",
            "'Nov', \$sectionId, 1, 3, false, 2, '', 'Nov', '', null",
            "'Dec', \$sectionId, 1, 3, false, 2, '', 'Dec', '', null"
        ],
        'MOTO/Internet Questionnaire' => [
            "'Does your organization have a store front location?', \$sectionId, 12, 4, true, 2, 'Yes::Yes,No::No', 'StoreFrontLoc-', '', null",
            "'Are orders received and processed at business location?', \$sectionId, 12, 4, true, 2, 'Yes::Yes,No::No', 'OrdersProcAtBusinessLoc-', '', null",
            "'Where is inventory housed?', \$sectionId, 12, 0, true, 2, '', 'Where is inventory housed', '', null",
            "'Are any of the following aspects of your business outsourced to other companies? (please select all that apply)', \$sectionId, 12, 6, false, 2, '', 'label', '', null",
            "'Customer Service (Desc)', \$sectionId, 4, 0, true, 2, '', 'Customer Service', '', null",
            "'Product Shipment (Desc)', \$sectionId, 4, 0, true, 2, '', 'Product Shipment', '', null",
            "'Handling of Returns (Desc)', \$sectionId, 4, 0, true, 2, '', 'Handling of Returns', '', null",
            "'Cardholder Billing', \$sectionId, 12, 0, true, 0, '', 'Cardholder Billing', '', null",
            "'By what methods do sales take place? (i.e. internet, trade shows, etc.)', \$sectionId, 12, 0, true, 2, '', 'By what methods to sales take place ie Internet trade shows etc ', '', null",
            "'Are sales done:', \$sectionId, 12, 6, false, 3, '', 'label', '', null",
            "'Locally', \$sectionId, 6, 3, false, 2, '', 'locally', '', null",
            "'Nationally', \$sectionId, 6, 3, false, 2, '', 'nationally', '', null",
            "'If product/service delivery requires recurring billing, please explain available billing options:', \$sectionId, 12, 6, false, 3, '', 'label', '', null",
            "'Monthly', \$sectionId, 3, 3, false, 2, '', 'Monthly Recurring', '', null",
            "'Quarterly', \$sectionId, 3, 3, false, 2, '', 'QUARTERLY', '', null",
            "'Semi-Annually', \$sectionId, 3, 3, false, 2, '', 'SEMIANUALLY', '', null",
            "'Annually', \$sectionId, 3, 3, false, 2, '', 'ANNUALLY', '', null",
            "'If product/service delivery requires recurring billing, please explain available billing options:', \$sectionId, 12, 6, false, 3, '', 'label', '', null",
            "'Total must equal 100%', \$sectionId, 12, 6, false, 3, '', 'label', '', null",
            "'% FULL PAYMENT UP FRONT WITH', \$sectionId, 6, 11, false, 2, '', 'PercentFullPayUpFront', '', null",
            "'DAYS UNTIL PRODUCT/SERVICE DELIVERY', \$sectionId, 6, 19, false, 2, '', 'DaysUntilDelivery', '', null",
            "'% PARTIAL PAYMENT REQUIRED UP FRONT WITH', \$sectionId, 6, 11, false, 2, '', 'PercentPartialPayUpFront', '', null",
            "'% AND WITHIN', \$sectionId, 3, 11, false, 2, '', 'PercentAndWithin', '', null",
            "'DAYS UNTIL FINAL DELIVERY', \$sectionId, 3, 19, false, 2, '', 'DaysUntilFinalDelivery', '', null",
            "'% PAYMENT RECEIVED AFTER PRODUCT/SERVICE IS PROVIDED', \$sectionId, 12, 11, false, 2, '', 'PercentPayReceivedAfter', '', null"
        ], 
    },
    'ACH Bank and Trade Reference' => {
        'Bank Information' => [
            "'Bank Name', \$sectionId, 12, 0, true, 2, '', 'BankName', '', null",
            "'Contact Name', \$sectionId, 12, 0, false, 2, '', 'BankContact', '', null",
            "'Phone', \$sectionId, 12, 9, false, 2, '', 'BankPhone', '', null",
            "'Address', \$sectionId, 12, 0, false, 2, '', 'BankAddress', '', null",
            "'City', \$sectionId, 4, 0, false, 2, '', 'BankCity', '', null",
            "'State', \$sectionId, 4, 20, false, 2, \$FP::STATES, 'BankState', '', null",
            "'Zip', \$sectionId, 4, 13, false, 2, '', 'BankZip', '', null"
        ],
        'Depository Account' => [
            "'Routing Number', \$sectionId, 12, 23, true, 2, '', 'RoutingNum', '', null, null, true",
            "'Account Number', \$sectionId, 12, 0, true, 2, '', 'AccountNum', '', null, null, true"
        ],
        'Fees Account' => [
            "'Routing Number', \$sectionId, 12, 23, true, 2, '', 'FeesRoutingNum', '', null, null, true",
            "'Account Number', \$sectionId, 12, 0, true, 2, '', 'FeesAccountNum', '', null, null, true"
        ],
        'Trade Reference 1' => [
            "'Business Name', \$sectionId, 12, 0, true, 2, '', 'TradeRef1', '', null",
            "'Contact Person', \$sectionId, 12, 0, true, 2, '', 'TradeRefContact1', '', null",
            "'Phone', \$sectionId, 6, 9, true, 2, '', 'TradeRefPhone1', '', null",
            "'Acct #', \$sectionId, 6, 0, true, 2, '', 'TradeRefAccount1', '', null",
            "'City', \$sectionId, 6, 0, true, 2, '', 'TradeRefCity1', '', null",
            "'State', \$sectionId, 6, 20, true, 2, \$FP::STATES, 'TradeRefSt1', '', null"
        ],
        'Trade Reference 2' => [
            "'Business Name', \$sectionId, 12, 0, false, 2, '', 'TradeRef2', '', null",
            "'Contact Person', \$sectionId, 12, 0, false, 2, '', 'TradeRefContact2', '', null",
            "'Phone', \$sectionId, 6, 9, false, 2, '', 'TradeRefPhone2', '', null",
            "'Acct #', \$sectionId, 6, 0, false, 2, '', 'TradeRefAccount2', '', null",
            "'City', \$sectionId, 6, 0, false, 2, '', 'TradeRefCity2', '', null",
            "'State', \$sectionId, 6, 20, false, 2, \$FP::STATES, 'TradeRefSt2', '', null"
        ]
    },
    'Set Up Information' => {
        'American Express Information' => [
            "'Do you currently accept American Express?', \$sectionId, 6, 4, true, 2,  'Yes::Exist,No::NotExisting', 'DoYouAcceptAE-', '', null",
            "'Please Provide Existing SE#', \$sectionId, 6, 0, false, 2, '', 'AmexNum', '', null",
            "'Do you want to accept American Express?', \$sectionId, 12, 4, false, 2, 'Yes::New,No::NotNew', 'DoYouWantToAcceptAE-', '', null"
        ],
        'Discover Information' => [
            "'Do you want to accept Discover?', \$sectionId, 12, 4, true, 2,  'Yes::New,No::NotNew', 'DoYouWantToAcceptDisc-', '', null"
        ],
        'Terminal/Software Type(1)' => [
            "'Quantity', \$sectionId, 3, 19, true, 2, '', 'QTY1', '', null",
            "'Type', \$sectionId, 3, 0, true, 2, '', 'Terminal1', '', null",
            "'Provider', \$sectionId, 6, 4, true, 2, 'Axia::Axia,Merchant::Merchant', 'Provider-', '', null",
            "'Do You Use Autoclose?', \$sectionId, 6, 4, true, 2, 'Yes::Autoclose,No::NoAutoclose', 'DoYouUseAutoclose-', '', null",
            "'If Yes, What Time?', \$sectionId, 6, 2, false, 2, '', 'Autoclose Time 1', '', null",
            "'Terminal Programming Information (please select all that apply)', \$sectionId, 12, 6, false, 3, '', 'label', '', null",
            "'AVS', \$sectionId, 2, 3, false, 2, '', 'AVS', '', null",
            "'Server #s', \$sectionId, 2, 3, false,  2, '', 'Server', '', null",
            "'Invoice #', \$sectionId, 2, 3, false, 2, '', 'Invoice', '', null",
            "'Tips', \$sectionId, 2, 3, false, 2, '', 'Tips', '', null",
            "'Purchasing Cards', \$sectionId, 2, 3, false, 2, '', 'Purchasing Cards', '', null",
            "'Do you accept Debit on this terminal?', \$sectionId, 4, 4, true, 2, 'Yes::Yes,No::No', 'TermAcceptDebit-', '', null",
            "'If Yes, what type of PIN Pad?', \$sectionId, 4, 0, false, 2, '', 'PinPad1', '', null",
            "'PIN Pad Quantity', \$sectionId, 4, 19, false, 2, '', 'QTY - PP1', '', null"
        ],
        'Terminal/Software Type(2)' => [
            "'Quantity', \$sectionId, 3, 19, false, 2, '', 'QTY2', '', null",
            "'Type', \$sectionId, 3, 0, false, 2, '', 'Terminal2', '', null",
            "'Provider', \$sectionId, 6, 4, false, 2, 'Axia::Axia,Merchant::Merchant', 'Provider2-', '', null",
            "'Do You Use Autoclose?', \$sectionId, 6, 4, false, 2, 'Yes::AutoClose_2,No::NoAutoClose_2', 'DoYouUseAutoclose2-', '', null",
            "'If Yes, What Time?', \$sectionId, 6, 2, false, 2, '', 'Autoclose Time 2', '', null",
            "'Terminal Programming Information (please select all that apply)', \$sectionId, 12, 6, false, 3, '', 'label', '', null",
            "'AVS', \$sectionId, 2, 3, false, 2, '', 'AVS_2', '', null",
            "'Server #s', \$sectionId, 2, 3, false,  2, '', 'Server_2', '', null",
            "'Invoice #', \$sectionId, 2, 3, false, 2, '', 'Invoice_2', '', null",
            "'Tips', \$sectionId, 2, 3, false, 2, '', 'Tips_2', '', null",
            "'Purchasing Cards', \$sectionId, 2, 3, false, 2, '', 'Purchasing Cards_2', '', null",
            "'Do you accept Debit on this terminal?', \$sectionId, 4, 4, false, 2, 'Yes::Yes,No::No', 'TermAcceptDebit2-', '', null",
            "'If Yes, what type of PIN Pad?', \$sectionId, 4, 0, false, 2, '', 'PinPad2', '', null",
            "'PIN Pad Quantity', \$sectionId, 4, 19, false, 2, '', 'QTY - PP2', '', null"
        ]
    },
    'Ownership Information' => {
        'OWNER / OFFICER (1)' => [
            "'Percentage Ownership', \$sectionId, 12, 11, true, 2, '', 'Owner1Equity', '', null",
            "'Full Name', \$sectionId, 12, 0, true, 2, '', 'Owner1Name', '', null",
            "'Title', \$sectionId, 12, 0, true, 2, '', 'Owner1Title', '', null",
            "'Address', \$sectionId, 12, 0, true, 2, '', 'Owner1Address', '', null",
            "'City', \$sectionId, 4, 0, true, 2, '', 'Owner1City', '', null",
            "'State', \$sectionId, 4, 20, true, 2, \$FP::STATES, 'Owner1State', '', null",
            "'Zip', \$sectionId, 4, 13, true, 2, '', 'Owner1Zip', '', null",
            "'Phone', \$sectionId, 6, 9, true, 2, '', 'Owner1Phone', '', null",
            "'Fax', \$sectionId, 6, 9, false, 2, '', 'Owner1Fax', '', null",
            "'Email', \$sectionId, 6, 14, true, 2, '', 'Owner1Email', '', null",
            "'SSN', \$sectionId, 6, 12, true, 2, '', 'OwnerSSN', '', null, null, true",
            "'Date of Birth', \$sectionId, 8,  1, true, 2, '', 'Owner1DOB', '', null"
        ],
        'OWNER / OFFICER (2)' => [
            "'Percentage Ownership', \$sectionId, 12, 11, false, 2, '', 'Owner2Equity', '', null",
            "'Full Name', \$sectionId, 12, 0, false, 2, '', 'Owner2Name', '', null",
            "'Title', \$sectionId, 12, 0, false, 2, '', 'Owner2Title', '', null",
            "'Address', \$sectionId, 12, 0, false, 2, '', 'Owner2Address', '', null",
            "'City', \$sectionId, 4, 0, false, 2, '', 'Owner2City', '', null",
            "'State', \$sectionId, 4, 20, false, 2, \$FP::STATES, 'Owner2State', '', null",
            "'Zip', \$sectionId, 4, 13, false, 2, '', 'Owner2Zip', '', null",
            "'Phone', \$sectionId, 6, 9, false, 2, '', 'Owner2Phone', '', null",
            "'Fax', \$sectionId, 6, 9, false, 2, '', 'Owner2Fax', '', null",
            "'Email', \$sectionId, 6, 14, false, 2, '', 'Owner2Email', '', null",
            "'SSN', \$sectionId, 6, 12, false, 2, '', 'Owner2SSN', '', null, null, true",
            "'Date of Birth', \$sectionId, 8,  1, false, 2, '', 'Owner2DOB', '', null"
        ]
    },
#    'Merchant Referral Program' => {
#        "Any successful referrals will result in \$100 credit to the Merchant bank account provided. Visit our referral program page for details." => [
#            "'Referral Business #1', \$sectionId, 4, 0, false, 2, '', 'Referral1Business', '', null",
#            "'Owner/Officer', \$sectionId, 4, 0, false, 2, '', 'Referral1Owner/Officer', '', null",
#            "'Phone #', \$sectionId, 4, 9, false, 2, '', 'Referral1Phone', '', null",
#            "'Referral Business #2', \$sectionId, 4, 0, false, 2, '', 'Referral2Business', '', null",
#            "'Owner/Officer 2', \$sectionId, 4, 0, false, 2, '', 'Referral2Owner/Officer', '', null",
#            "'Phone # 2', \$sectionId, 4, 9, false, 2, '', 'Referral2Phone', '', null",
#            "'Referral Business #3', \$sectionId, 4, 0, false, 2, '', 'Referral3Business', '', null",
#            "'Owner/Officer 3', \$sectionId, 4, 0, false, 2, '', 'Referral3Owner/Officer', '', null",
#            "'Phone # 3', \$sectionId, 4, 9, false, 2, '', 'Referral3Phone', '', null"
#        ]
#    }
};

my $filename = "Build".$cobrand."TemplateCept.php";
open(OUTFILE, "> $filename") || die "\ncan't open $filename for writing: $!\n\n";

print OUTFILE '<?php
use Codeception\Util\Debug;

$I = new WebGuy2($scenario);
$ULP = new UsersLoginPage($I);
$CP = new CobrandPage($I);
$TP = new TemplatePage($I);
$PP = new PagePage($I);
$SP = new SectionPage($I);
$FP = new FieldPage($I);

// authenticate
$I->wantTo("build the default '.$cobrand.' template");
$ULP->login();

// start with the cobrand
$cobrandId = $CP->createIfMissing(\''.$cobrand.'\');

// next go to the template for this cobrand
$I->amOnPage(\'/admin/cobrands/\'.$cobrandId.\'/templates\');

// create a new template for this cobrand
$templateId = $TP->createIfMissing(\'Default\', $cobrandId);
';

foreach my $page (@$pages) {
    print OUTFILE "\n// go to the pages for this template
\$I->amOnPage('/admin/templates/'.\$templateId.'/templatepages');

// create a new page for the template
\$pageId = \$PP->createIfMissing('$page', \$templateId);\n";

    ## create the sections for each page
    foreach my $section (@{$sections->{$page}}) {
        print OUTFILE "\n// go to the sections for this page
\$I->amOnPage('/admin/templatepages/'.\$pageId.'/templatesections');

// create a new section for the page
\$sectionId = \$SP->createIfMissing('$section', \$pageId);

// next go to the fields for this section
\$I->amOnPage('/admin/templatesections/'.\$sectionId.'/templatefields');

// create new fields\n";

        ## create the fields for each section
        foreach my $field (@{$templateMap->{$page}->{$section}}) {
            print OUTFILE "\$fieldId = \$FP->createIfMissing($field);\n";
        }
    }
}
