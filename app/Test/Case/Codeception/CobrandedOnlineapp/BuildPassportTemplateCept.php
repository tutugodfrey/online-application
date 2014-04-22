<?php
use Codeception\Util\Debug;

$I = new WebGuy2($scenario);
$ULP = new UsersLoginPage($I);
$CP = new CobrandPage($I);
$TP = new TemplatePage($I);
$PP = new PagePage($I);
$SP = new SectionPage($I);
$FP = new FieldPage($I);

// authenticate
$I->wantTo("build the default Passport template");
$ULP->login();

// the axia cobrand should already be there for us
// build the default template...

// start with the cobrand
$cobrandId = $CP->createIfMissing('Passport');

// next go to the template for this cobrand
$I->amOnPage('/admin/cobrands/'.$cobrandId.'/templates');

// create a new template for this cobrand
$templateId = $TP->createIfMissing('Default', $cobrandId);

// next go to the pages for this template
$I->amOnPage('/admin/templates/'.$templateId.'/templatepages');

// create a new page for the template
$pageId = $PP->createIfMissing('General Information', $templateId);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('OWNERSHIP TYPE', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create a new field
$fieldId = $FP->createIfMissing('Ownership Type', $sectionId, 12, 4, true, 2, 'Corporation::Corp,Sole Prop::Sole Prop,LLC::LLC,Partnership::Partnership,Non Profit/Tax Exempt (fed form 501C)::Non Profit,Other::Other', 'Owner Type - ', '', null);
$fieldId = $FP->createIfMissing('Corporate Status', $sectionId, 12, 0, false, 2, '', 'CorpStatus', '', null, true);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('CORPORATE INFORMATION', $pageId, 6);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create a new field
$fieldId = $FP->createIfMissing('Legal Business Name', $sectionId, 12, 0, true, 2, '', 'CorpName', '', null);
$fieldId = $FP->createIfMissing('Mailing Address', $sectionId, 12, 0, true, 2, '', 'CorpAddress', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 2, '', 'CorpCity', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, true, 2, $FP::STATES, 'CorpState', '', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, true, 2, '', 'CorpZip', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 6, 9, true, 2, '', 'CorpPhone', '', null);
$fieldId = $FP->createIfMissing('Fax', $sectionId, 6, 9, true, 2,'', 'CorpFax', '', null);
$fieldId = $FP->createIfMissing('Corp Contact Name', $sectionId, 8, 0, true, 2, '', 'CorpContact', '', null);
$fieldId = $FP->createIfMissing('Title', $sectionId, 4, 0, true, 2, '', 'Title', '', null);
$fieldId = $FP->createIfMissing('Email', $sectionId, 12, 14, true, 2, '', 'EMail', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('LOCATION INFORMATION', $pageId, 6);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create new fields
//$fieldId = $FP->createIfMissing('Same As Corporate Information', $sectionId, 12, 3, false, 2, '', 'SameAsCorpInfo', '', null);
$fieldId = $FP->createIfMissing('Business Name (DBA)', $sectionId, 12, 0, true, 2, '', 'DBA', '', null);
$fieldId = $FP->createIfMissing('Location Address', $sectionId, 12, 0, true, 2, '', 'Address', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 2, '', 'City', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, true, 2, $FP::STATES, 'State', '', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, true, 2, '', 'Zip', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 6, 9, true, 2, '', 'PhoneNum', '', null);
$fieldId = $FP->createIfMissing('Fax', $sectionId, 6, 9, true, 2,'', 'FaxNum', '', null);
$fieldId = $FP->createIfMissing('Location Contact Name', $sectionId, 8, 0, true, 2, '', 'Contact', '', null);
$fieldId = $FP->createIfMissing('Title', $sectionId, 4, 0, true, 2, '', 'LocTitle', '', null);
// not sure if this VVVVV info is used in the export
//$fieldId = $FP->createIfMissing('Email', $sectionId, 12, 14, true, 2, '', 'LocEmail', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('ADDITIONAL BUSINESS INFORMATION', $pageId, 12);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create new fields
$fieldId = $FP->createIfMissing('Federal Tax ID',$sectionId, 12, 0, true, 2, '', 'TaxID', '', null, null, true);
$fieldId = $FP->createIfMissing('Website',$sectionId, 12, 17, true, 2, '', 'WebAddress', '', null);
$fieldId = $FP->createIfMissing('Customer Service Phone',$sectionId, 12, 9, true, 2, '', 'Customer Service Phone', '', null);
$fieldId = $FP->createIfMissing('Business Open Date', $sectionId, 12, 1, true, 2, '', 'OpenDate', '', null);
$fieldId = $FP->createIfMissing('Length of Current Ownership',$sectionId, 12, 0, true, 2, '', 'Ownership Length', '', null);
$fieldId = $FP->createIfMissing('Existing Axia Merchant?', $sectionId, 12, 4, true, 2, 'Yes::Yes,No::No', 'Existing Axia Merchant - ', '', null);
$fieldId = $FP->createIfMissing('Current MID #',$sectionId, 12, 0, false, 2, '', 'Current MID', '', null);
$fieldId = $FP->createIfMissing('General Comments',$sectionId, 12, 21, true, 2, '', 'General Comments', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('LOCATION TYPE', $pageId);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Location Type', $sectionId, 12, 4, true, 2, 'Retail Store::Retail Store,Industrial::Industrial,Trade::Trade,Office::Office,Residence::Residence,Other::Site Inspection Other', '', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('MERCHANT', $pageId);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Merchant Ownes/Leases', $sectionId, 12,4, true, 2, 'Owns::Owns,Leases::Leases', '', '', null);
$fieldId = $FP->createIfMissing('Landlord Name', $sectionId, 6, 0, false, 2, '', 'Landlord', '', null);
$fieldId = $FP->createIfMissing('Landlord Phone', $sectionId, 6, 9, false, 2, '', 'Landlord Phone', '', null);


// add another page for this template
$I->amOnPage('/admin/templates/'.$templateId.'/templatepages');

// create a new page for the template
$pageId = $PP->createIfMissing('Products and Services Information', $templateId);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('General Underwriting Profile', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Business Type', $sectionId, 12, 4, true, 2, 'Retail::Retail,Restaurant::Restaurant,Lodging::Lodging,MOTO::MOTO,Internet::Internet,Grocery::Grocery', '', '', null);

$fieldId = $FP->createIfMissing('Products/Services Sold', $sectionId, 12, 0, true, 2, '', 'Products Services Sold', '', null);
$fieldId = $FP->createIfMissing('Return Policy', $sectionId, 12, 0, true, 2, '', 'Return Policy', '', null);
$fieldId = $FP->createIfMissing('Days Until Product Delivery', $sectionId, 12, 0, true, 2, '', 'Days Until Product Delivery', '', null);
$fieldId = $FP->createIfMissing('Monthly Volume', $sectionId, 4, 10, true, 2, '', 'MonthlyVol', '', null);
$fieldId = $FP->createIfMissing('Average Ticket', $sectionId, 4, 10, true, 2, '', 'AvgTicket', '', null);
$fieldId = $FP->createIfMissing('Highest Ticket', $sectionId, 4, 10, true, 2, '', 'MaxSalesAmt', '', null);
$fieldId = $FP->createIfMissing('Current Processor', $sectionId, 12, 0, true, 2, '', 'Previous Processor', '', null);
$fieldId = $FP->createIfMissing('Method of Sales', $sectionId, 6, 5, true, 2, 'Card Present Swiped::Card Present Swiped,Card Present Imprint::Card Present Imprint,Card Not Present (Keyed)::Card Not Present - Keyed,Card Not Present (Internet)::Card Not Present - Internet', '', '', null);
$fieldId = $FP->createIfMissing('% of Product Sold', $sectionId, 6, 5, true, 2, 'Direct To Customer::Direct To Customer,Direct To Business::Direct To Business,Direct To Government::Direct To Government', '', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('High Volume Months', $pageId);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create fields
$fieldId = $FP->createIfMissing('Jan', $sectionId, 1, 3, false, 2, '', 'Jan', '', null);
$fieldId = $FP->createIfMissing('Feb', $sectionId, 1, 3, false, 2, '', 'Feb', '', null);
$fieldId = $FP->createIfMissing('Mar', $sectionId, 1, 3, false, 2, '', 'Mar', '', null);
$fieldId = $FP->createIfMissing('Apr', $sectionId, 1, 3, false, 2, '', 'Apr', '', null);
$fieldId = $FP->createIfMissing('May', $sectionId, 1, 3, false, 2, '', 'May', '', null);
$fieldId = $FP->createIfMissing('Jun', $sectionId, 1, 3, false, 2, '', 'Jun', '', null);
$fieldId = $FP->createIfMissing('Jul', $sectionId, 1, 3, false, 2, '', 'Jul', '', null);
$fieldId = $FP->createIfMissing('Aug', $sectionId, 1, 3, false, 2, '', 'Aug', '', null);
$fieldId = $FP->createIfMissing('Sep', $sectionId, 1, 3, false, 2, '', 'Sep', '', null);
$fieldId = $FP->createIfMissing('Oct', $sectionId, 1, 3, false, 2, '', 'Oct', '', null);
$fieldId = $FP->createIfMissing('Nov', $sectionId, 1, 3, false, 2, '', 'Nov', '', null);
$fieldId = $FP->createIfMissing('Dec', $sectionId, 1, 3, false, 2, '', 'Dec', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('MOTO/Internet Questionnaire', $pageId);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Does your organization have a store front location?', $sectionId, 12, 4, true, 2, 'Yes::8,No::9', 'Check Box ', '', null);
$fieldId = $FP->createIfMissing('Are orders received and processed at business location?', $sectionId, 12, 4, true, 2, 'Yes::14,No::13', 'Check Box ', '', null);
$fieldId = $FP->createIfMissing('Where is inventory housed?', $sectionId, 12, 0, true, 2, '', 'Where is inventory housed', '', null);
// not used in the export
//$fieldId = $FP->createIfMissing('Do you own the product/inventory?', $sectionId, 12, 4, true, 2, 'Yes::yes,No::no', 'product_', '', null);


$fieldId = $FP->createIfMissing('Are any of the following aspects of your business outsourced to other companies? (please select all that apply)', $sectionId, 12, 6, false, 2, '', 'label', '', null);
//$fieldId = $FP->createIfMissing('Customer Service', $sectionId, 4, 3, false, 2, '', 'Customer Service', '', null);
//$fieldId = $FP->createIfMissing('Product Shipment', $sectionId, 4, 3, false, 2, '', 'Product Shipment', '', null);
//$fieldId = $FP->createIfMissing('Handling of Returns', $sectionId, 4, 3, false, 2, '', 'Handling of Returns', '', null);
$fieldId = $FP->createIfMissing('Customer Service (Desc)', $sectionId, 4, 0, true, 2, '', 'Customer Service', '', null);
$fieldId = $FP->createIfMissing('Product Shipment (Desc)', $sectionId, 4, 0, true, 2, '', 'Product Shipment', '', null);
$fieldId = $FP->createIfMissing('Handling of Returns (Desc)', $sectionId, 4, 0, true, 2, '', 'Handling of Returns', '', null);
$fieldId = $FP->createIfMissing('Cardholder Billing', $sectionId, 12, 0, true, 0, '', 'Cardholder Billing', '', null);
$fieldId = $FP->createIfMissing('By what methods do sales take place? (i.e. internet, trade shows, etc.)', $sectionId, 12, 0, true, 2, '', 'By what methods to sales take place ie Internet trade shows etc ', '', null);


$fieldId = $FP->createIfMissing('Are sales done:', $sectionId, 12, 6, false, 3, '', 'label', '', null);
$fieldId = $FP->createIfMissing('Locally', $sectionId, 6, 3, false, 2, '', 'locally', '', null);
$fieldId = $FP->createIfMissing('Nationally', $sectionId, 6, 3, false, 2, '', 'nationally', '', null);

$fieldId = $FP->createIfMissing('If product/service delivery requires recurring billing, please explain available billing options:', $sectionId, 12, 6, false, 3, '', 'label', '', null);
$fieldId = $FP->createIfMissing('Monthly', $sectionId, 3, 3, false, 2, '', 'Monthly Recurring', '', null);
$fieldId = $FP->createIfMissing('Quarterly', $sectionId, 3, 3, false, 2, '', 'QUARTERLY', '', null);
$fieldId = $FP->createIfMissing('Semi-Annually', $sectionId, 3, 3, false, 2, '', 'SEMIANUALLY', '', null);
$fieldId = $FP->createIfMissing('Annually', $sectionId, 3, 3, false, 2, '', 'ANNUALLY', '', null);

$fieldId = $FP->createIfMissing('If product/service delivery requires recurring billing, please explain available billing options:', $sectionId, 12, 6, false, 3, '', 'label', '', null);
$fieldId = $FP->createIfMissing('Total must equal 100%', $sectionId, 12, 6, false, 3, '', 'label', '', null);

$fieldId = $FP->createIfMissing('% FULL PAYMENT UP FRONT WITH', $sectionId, 6, 11, false, 2, '', 'Text83', '', null);
$fieldId = $FP->createIfMissing('DAYS UNTIL PRODUCT/SERVICE DELIVERY', $sectionId, 6, 19, false, 2, '', 'Text86', '', null);

$fieldId = $FP->createIfMissing('% PARTIAL PAYMENT REQUIRED UP FRONT WITH', $sectionId, 6, 11, false, 2, '', 'Text84', '', null);
$fieldId = $FP->createIfMissing('% AND WITHIN', $sectionId, 3, 11, false, 2, '', 'Text87', '', null);
$fieldId = $FP->createIfMissing('DAYS UNTIL FINAL DELIVERY', $sectionId, 3, 19, false, 2, '', 'Text88', '', null);

$fieldId = $FP->createIfMissing('% PAYMENT RECEIVED AFTER PRODUCT/SERVICE IS PROVIDED', $sectionId, 12, 11, false, 2, '', 'Text85', '', null);

// add another page for this template
$I->amOnPage('/admin/templates/'.$templateId.'/templatepages');

// create a new page for the template
$pageId = $PP->createIfMissing('ACH Bank and Trade Reference', $templateId);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Bank Information', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Bank Name', $sectionId, 12, 0, true, 2, '', 'BankName', '', null);
$fieldId = $FP->createIfMissing('Contact Name', $sectionId, 12, 0, false, 2, '', 'BankContact', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 12, 9, false, 2, '', 'BankPhone', '', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, false, 2, '', 'BankAddress', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, false, 2, '', 'BankCity', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, false, 2, $FP::STATES, 'BankState', '', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, false, 2, '', 'BankZip', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Depository Account', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Routing Number', $sectionId, 12, 0, true, 2, '', 'RoutingNum', '', null, null, true);
$fieldId = $FP->createIfMissing('Account Number', $sectionId, 12, 0, true, 2, '', 'AccountNum', '', null, null, true);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Fees Account', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Routing Number', $sectionId, 12, 0, true, 2, '', 'FeesRoutingNum', '', null, null, true);
$fieldId = $FP->createIfMissing('Account Number', $sectionId, 12, 0, true, 2, '', 'FeesAccountNum', '', null, null, true);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Trade Reference 2', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Business Name', $sectionId, 12, 0, true, 2, '', 'TradeRefName', '', null);
$fieldId = $FP->createIfMissing('Contact Person', $sectionId, 12, 0, true, 2, '', 'TradeRefContact', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 6, 9, true, 2, '', 'TradeRefPhone1', '', null);
$fieldId = $FP->createIfMissing('Acct #', $sectionId, 6, 0, true, 2, '', 'TradeRefAccount1', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 6, 0, true, 2, '', 'TradeRefCity1', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 6, 20, true, 2, $FP::STATES, 'TradeRefSt1', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Trade Reference 2', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Business Name', $sectionId, 12, 0, false, 2, '', 'TradeRef2', '', null);
$fieldId = $FP->createIfMissing('Contact Person', $sectionId, 12, 0, false, 2, '', 'TradeRefContact2', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 6, 9, false, 2, '', 'TradeRefPhone2', '', null);
$fieldId = $FP->createIfMissing('Acct #', $sectionId, 6, 0, false, 2, '', 'TradeRefAccount2', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 6, 0, false, 2, '', 'TradeRefCity2', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 6, 20, false, 2, $FP::STATES, 'TradeRefSt2', '', null);

// add another page for this template
$I->amOnPage('/admin/templates/'.$templateId.'/templatepages');

// create a new page for the template
$pageId = $PP->createIfMissing('Set Up Information', $templateId);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('American Express Information', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

//$fieldId = $FP->createIfMissing('Bank Name', $sectionId, 12, 0, true, 2, '', 'BankName', '', null);
$fieldId = $FP->createIfMissing('Do you currently accept American Express?', $sectionId, 6, 4, true, 2,  'Yes::Exist,No::NotExisting', 'AE', '', null);
$fieldId = $FP->createIfMissing('Please Provide Existing SE#', $sectionId, 6, 0, false, 2, '', 'AmexNum', '', null);
$fieldId = $FP->createIfMissing('Do you want to accept American Express?', $sectionId, 12, 4, false, 2, 'Yes::New,No::NotNew', 'AE', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Discover Information', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Do you want to accept Discover?', $sectionId, 12, 4, true, 2,  'Yes::New,No::NotNew', 'Disc', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Terminal/Software Type(1)', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Quantity', $sectionId, 3, 19, true, 2, '', 'QTY1', '', null);
$fieldId = $FP->createIfMissing('Type', $sectionId, 3, 0, true, 2, '', 'Terminal1', '', null);
$fieldId = $FP->createIfMissing('Provider', $sectionId, 6, 4, true, 2, 'Axia::Axia,Merchant::Merchant', '', '', null);
$fieldId = $FP->createIfMissing('Do You Use Autoclose?', $sectionId, 6, 4, true, 2, 'Yes::Autoclose,No::NoAutoclose', '', '', null);
$fieldId = $FP->createIfMissing('If Yes, What Time?', $sectionId, 6, 2, false, 2, '', 'Autoclose Time 2', '', null);

$fieldId = $FP->createIfMissing('Terminal Programming Information (please select all that apply)', $sectionId, 12, 6, false, 3, '', 'label', '', null);
$fieldId = $FP->createIfMissing('AVS', $sectionId, 2, 3, false, 2, '', 'AVS', '', null);
$fieldId = $FP->createIfMissing('Server #s', $sectionId, 2, 3, false,  2, '', 'Server', '', null);
$fieldId = $FP->createIfMissing('Invoice #', $sectionId, 2, 3, false, 2, '', 'Invoice', '', null);
$fieldId = $FP->createIfMissing('Tips', $sectionId, 2, 3, false, 2, '', 'Tips', '', null);
$fieldId = $FP->createIfMissing('Purchasing Cards', $sectionId, 2, 3, false, 2, '', 'Purchasing Cards', '', null);
$fieldId = $FP->createIfMissing('Do you accept Debit on this terminal?', $sectionId, 4, 4, true, 2, 'Yes::Yes,No::No', 'term_accept_debit', '', null);
$fieldId = $FP->createIfMissing('If Yes, what type of PIN Pad?', $sectionId, 4, 0, false, 2, '', 'PinPad1', '', null);
$fieldId = $FP->createIfMissing('PIN Pad Quantity', $sectionId, 4, 19, false, 2, '', 'QTY - PP1', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Terminal/Software Type(2)', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Quantity', $sectionId, 3, 19, false, 2, '', 'QTY2', '', null);
$fieldId = $FP->createIfMissing('Type', $sectionId, 3, 0, false, 2, '', 'Terminal2', '', null);
$fieldId = $FP->createIfMissing('Provider', $sectionId, 6, 4, false, 2, 'Axia::Axia_3,Merchant::Merchant_3', '', '', null);
$fieldId = $FP->createIfMissing('Do You Use Autoclose?', $sectionId, 6, 4, false, 2, 'Yes::AutoClose_2,No::NoAutoClose_2', '', '', null);
$fieldId = $FP->createIfMissing('If Yes, What Time?', $sectionId, 6, 2, false, 2, '', 'Autoclose Time 2', '', null);
$fieldId = $FP->createIfMissing('Terminal Programming Information (please select all that apply)', $sectionId, 12, 6, false, 3, '', 'label', '', null);

$fieldId = $FP->createIfMissing('AVS', $sectionId, 2, 3, false, 2, '', 'AVS_2', '', null);
$fieldId = $FP->createIfMissing('Server #s', $sectionId, 2, 3, false,  2, '', 'Server_2', '', null);
$fieldId = $FP->createIfMissing('Invoice #', $sectionId, 2, 3, false, 2, '', 'Invoice_2', '', null);
$fieldId = $FP->createIfMissing('Tips', $sectionId, 2, 3, false, 2, '', 'Tips_2', '', null);
$fieldId = $FP->createIfMissing('Purchasing Cards', $sectionId, 2, 3, false, 2, '', 'Purchasing Cards_2', '', null);
$fieldId = $FP->createIfMissing('Do you accept Debit on this terminal?', $sectionId, 4, 4, true, 2, 'Yes::Yes,No::No', 'term_accept_debit_2', '', null);
$fieldId = $FP->createIfMissing('If Yes, what type of PIN Pad?', $sectionId, 4, 0, false, 2, '', 'PinPad2', '', null);
$fieldId = $FP->createIfMissing('PIN Pad Quantity', $sectionId, 4, 19, false, 2, '', 'QTY - PP2', '', null);

// add another page for this template
$I->amOnPage('/admin/templates/'.$templateId.'/templatepages');

// create a new page for the template
$pageId = $PP->createIfMissing('Ownership Information', $templateId);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('OWNER / OFFICER (2)', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Percentage Ownership', $sectionId, 12, 11, true, 2, '', 'OwnerEquity', '', null);
$fieldId = $FP->createIfMissing('Full Name', $sectionId, 12, 0, true, 2, '', 'Principal', '', null);
$fieldId = $FP->createIfMissing('Title', $sectionId, 12, 0, true, 2, '', 'Owner1Title', '', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, true, 2, '', 'Owner1Address', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 2, '', 'Owner1City', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, true, 2, $FP::STATES, 'Owner1State', '', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, true, 2, '', 'Owner1Zip', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 6, 9, true, 2, '', 'Owner1Phone', '', null);
$fieldId = $FP->createIfMissing('Fax', $sectionId, 6, 9, true, 2, '', 'Owner1Fax', '', null);
$fieldId = $FP->createIfMissing('Email', $sectionId, 6, 14, true, 2, '', 'Owner1Email', '', null);
$fieldId = $FP->createIfMissing('SSN', $sectionId, 6, 12, true, 2, '', 'OwnerSSN', '', null, null, true);
$fieldId = $FP->createIfMissing('Date of Birth', $sectionId, 8,  1, true, 2, '', 'Owner1DOB', '', null, null, true);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('OWNER / OFFICER (2)', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Percentage Ownership', $sectionId, 12, 11, false, 2, '', 'Owner2Equity', '', null);
$fieldId = $FP->createIfMissing('Full Name', $sectionId, 12, 0, false, 2, '', 'Owner2Name', '', null);
$fieldId = $FP->createIfMissing('Title', $sectionId, 12, 0, false, 2, '', 'Owner2Title', '', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, false, 2, '', 'Owner2Address', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, false, 2, '', 'Owner2City', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, false, 2, $FP::STATES, 'Owner2State', '', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, false, 2, '', 'Owner2Zip', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 6, 9, false, 2, '', 'Owner2Phone', '', null);
$fieldId = $FP->createIfMissing('Fax', $sectionId, 6, 9, false, 2, '', 'Owner2Fax', '', null);
$fieldId = $FP->createIfMissing('Email', $sectionId, 6, 14, false, 2, '', 'Owner2Email', '', null);
$fieldId = $FP->createIfMissing('SSN', $sectionId, 6, 12, false, 2, '', 'Owner2SSN', '', null, null, true);
$fieldId = $FP->createIfMissing('Date of Birth', $sectionId, 8,  1, false, 2, '', 'Owner2DOB', '', null, null, true);

// add another page for this template
$I->amOnPage('/admin/templates/'.$templateId.'/templatepages');

// create a new page for the template
$pageId = $PP->createIfMissing('Merchant Referral Program', $templateId);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing("Any successful referrals will result in $100 credit to the Merchant bank account provided. Visit our referral program page for details.", $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Referral Business #1', $sectionId, 4, 0, false, 2, '', 'Referral1Business', '', null);
$fieldId = $FP->createIfMissing('Owner/Officer', $sectionId, 4, 0, false, 2, '', 'Referral1Owner/Officer', '', null);
$fieldId = $FP->createIfMissing('Phone #', $sectionId, 4, 9, false, 2, '', 'Referral1Phone', '', null);
$fieldId = $FP->createIfMissing('Referral Business #2', $sectionId, 4, 0, false, 2, '', 'Referral2Business', '', null);
$fieldId = $FP->createIfMissing('Owner/Officer 2', $sectionId, 4, 0, false, 2, '', 'Referral2Owner/Officer', '', null);
$fieldId = $FP->createIfMissing('Phone # 2', $sectionId, 4, 9, false, 2, '', 'Referral2Phone', '', null);
$fieldId = $FP->createIfMissing('Referral Business #3', $sectionId, 4, 0, false, 2, '', 'Referral3Business', '', null);
$fieldId = $FP->createIfMissing('Owner/Officer 3', $sectionId, 4, 0, false, 2, '', 'Referral3Owner/Officer', '', null);
$fieldId = $FP->createIfMissing('Phone # 3', $sectionId, 4, 9, false, 2, '', 'Referral3Phone', '', null);
