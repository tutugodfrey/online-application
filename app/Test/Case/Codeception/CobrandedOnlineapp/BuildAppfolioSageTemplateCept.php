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
$I->wantTo("build the default Axia template replacement");
$ULP->login();

// the axia cobrand should already be there for us
// build the default template...

// add the Appfolio ACH template
$cobrandId = $CP->createIfMissing('Appfolio', 'AF', '/img/amp-logo.png', '');

// next go to the template for this cobrand
$I->amOnPage('/admin/cobrands/'.$cobrandId.'/templates');

// create a new template for this cobrand
$templateId = $TP->createIfMissing('Sage Virtual Check Merchant Processing Agreement', $cobrandId);

// next go to the pages for this template
$I->amOnPage('/admin/templates/'.$templateId.'/templatepages');

// create a new page for the template
$pageId = $PP->createIfMissing('Page1', $templateId);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Business Information', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create a new field - copy from the default axia template
// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create a new field
$fieldId = $FP->createIfMissing('Legal Business Name', $sectionId, 12, 0, true, 0, '', 'CorpName', 'LEGAL Name of Business', null);
$fieldId = $FP->createIfMissing('Mailing Address', $sectionId, 12, 0, true, 0, '', 'CorpAddress', 'Mailing/Billing Address', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 0, '', 'CorpCity', 'City', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, true, 0, $FP::STATES, 'CorpState', 'State', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, true, 0, '', 'CorpZip', 'Zip', null);
$fieldId = $FP->createIfMissing('Corp Contact Name', $sectionId, 12, 0, true, 0, '', 'CorpContact', 'Contact Name', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 12, 9, true, 0, '', 'CorpPhone', 'Corporate Phone', null);
$fieldId = $FP->createIfMissing('Fax', $sectionId, 12, 0, true, 1, 'N/A', 'CorpFax', 'Corporate Fax', null);
$fieldId = $FP->createIfMissing('Email', $sectionId, 12, 14, true, 0, '', 'EMail', 'Email Address', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('LOCATION INFORMATION', $pageId, 6);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create new fields
//$fieldId = $FP->createIfMissing('Same As Corporate Information', $sectionId, 12, 3, false, 1, '', 'SameAsCorpInfo', '', null);
$fieldId = $FP->createIfMissing('Business Name (DBA)', $sectionId, 12, 0, true, 0, '', 'DBA', 'Busines Name/DBA Name', null);
$fieldId = $FP->createIfMissing('Location Address', $sectionId, 12, 0, true, 0, '', 'Address', 'Location Address', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 0, '', 'City', 'City', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, true, 0, $FP::STATES, 'State', 'State', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, true, 0, '', 'Zip', 'Zip', null);
$fieldId = $FP->createIfMissing('Location Contact Name', $sectionId, 12, 0, true, 0, '', 'Contact', 'Contact Name', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 12, 9, true, 0, '', 'PhoneNum', 'Business Phone', null);
$fieldId = $FP->createIfMissing('Fax', $sectionId, 12, 0, true, 1, 'N/A', 'FaxNum', 'Business Fax', null);
$fieldId = $FP->createIfMissing('Website',$sectionId, 12, 17, true, 0, '', 'WebAddress', 'Website Address', null);
$fieldId = $FP->createIfMissing('Customer Service Phone',$sectionId, 12, 9, true, 0, '', 'Customer Service Phone', 'Customer Service Phone Number', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('General Underwriting Profile', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Products/Services Sold', $sectionId, 6, 0, true, 1, 'Property Management Services and Rentals', 'Products Services Sold', 'Type of Goods/Services Sold', null);
$fieldId = $FP->createIfMissing('Business Type - Retail', $sectionId, 6, 0, true, 1, 'true', 'Retail', 'Type of Business', null);
$fieldId = $FP->createIfMissing('Business Open Date', $sectionId, 12, 1, true, 0, '', 'OpenDate', 'Date Business Opened', null);
$fieldId = $FP->createIfMissing('Length of Current Ownership',$sectionId, 12, 0, true, 0, '', 'Ownership Length', 'Length of Current Ownership', null);

$fieldId = $FP->createIfMissing('Monthly Volume', $sectionId, 4, 10, true, 0, '', 'MonthlyVol', 'Current Check Volume', null);
$fieldId = $FP->createIfMissing('Average Ticket', $sectionId, 4, 10, true, 0, '', 'AvgTicket', 'Average Check Amount', null);
$fieldId = $FP->createIfMissing('Highest Ticket', $sectionId, 4, 10, true, 0, '', 'MaxSalesAmt', 'Estimated Maximum Transaction Amount', null);
$fieldId = $FP->createIfMissing('Current Processor', $sectionId, 12, 0, true, 1, 'N/A', 'Previous Processor', 'Current ACH Provider', null);

$fieldId = $FP->createIfMissing('Method of Sales - Written Pre-Authorized - Company', $sectionId, 12, 11, true, 1, 100, 'Card Not Present (Internet)', 'Written Pre-Authorized - Company', null);
$fieldId = $FP->createIfMissing('% of Product Sold Merchant Initiated', $sectionId, 12, 11, true, 1, 50, 'Merchant Initiated', '', null);
$fieldId = $FP->createIfMissing('% of Product Sold Consumer Initiated', $sectionId, 12, 11, true, 1, 50, 'Consumer Initiated', '', null);

// create a new field
$fieldId = $FP->createIfMissing('Ownership Type', $sectionId, 12, 0, true, 0, '', 'Ownership Type', 'Ownership Type', null);
$fieldId = $FP->createIfMissing('Corporate Status', $sectionId, 12, 0, false, 1, '', 'CorpStatus', '', null, true);
$fieldId = $FP->createIfMissing('Federal Tax ID',$sectionId, 12, 0, true, 0, '', 'TaxID', 'Federal Tax ID #', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('OWNER / OFFICER (1)', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Full Name', $sectionId, 12, 0, true, 0, '', 'Principal', 'Owner 1/Partner/Officer Name', null);
$fieldId = $FP->createIfMissing('Title', $sectionId, 12, 0, true, 0, 'Owner', 'Owner1Title', 'Business Title', null);
$fieldId = $FP->createIfMissing('Percentage Ownership', $sectionId, 12, 11, true, 0, '', 'OwnerEquity', 'Equity %', null);
$fieldId = $FP->createIfMissing('SSN', $sectionId, 12, 12, true, 0, '', 'OwnerSSN', 'Social Security #', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, true, 0, '', 'Owner1Address', 'Home Address', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 0, '', 'Owner1City', 'City', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, true, 0, $FP::STATES, 'Owner1State', 'State', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, true, 0, '', 'Owner1Zip', 'Zip', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 12, 9, true, 0, '', 'Owner1Phone', 'Phone Number', null);
//$fieldId = $FP->createIfMissing('Fax', $sectionId, 6, 9, true, 1, '', 'Owner1Fax', '', null);
//$fieldId = $FP->createIfMissing('Email', $sectionId, 6, 14, true, 1, '', 'Owner1Email', '', null);
$fieldId = $FP->createIfMissing('Date of Birth', $sectionId, 12,  1, true, 1, '', 'Owner1DOB', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('OWNER / OFFICER (2)', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields

$fieldId = $FP->createIfMissing('Full Name', $sectionId, 12, 0, false, 0, '', 'Owner2Name', 'Owner 2/Partner/Officer Name', null);
$fieldId = $FP->createIfMissing('Title', $sectionId, 12, 0, false, 0, 'Owner', 'Owner2Title', 'Business Title', null);
$fieldId = $FP->createIfMissing('Percentage Ownership', $sectionId, 12, 11, false, 0, '', 'Owner2Equity', 'Equity %', null);
$fieldId = $FP->createIfMissing('SSN', $sectionId, 12, 12, false, 0, '', 'Owner2SSN', 'Social Security #', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, false, 0, '', 'Owner2Address', 'Home Address', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, false, 0, '', 'Owner2City', 'City', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, false, 0, $FP::STATES, 'Owner2State', 'State', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, false, 0, '', 'Owner2Zip', 'Zip', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 12, 9, false, 0, '', 'Owner2Phone', 'Phone Number', null);
//$fieldId = $FP->createIfMissing('Fax', $sectionId, 6, 9, false, 1, '', 'Owner2Fax', '', null);
//$fieldId = $FP->createIfMissing('Email', $sectionId, 6, 14, false, 1, '', 'Owner2Email', '', null);
$fieldId = $FP->createIfMissing('Date of Birth', $sectionId, 12,  1, false, 1, '', 'Owner2DOB', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Bank Information', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Bank Name', $sectionId, 4, 0, true, 0, '', 'BankName', 'Bank Reference Name', null);
$fieldId = $FP->createIfMissing('Contact Name', $sectionId, 4, 0, false, 0, 'Anyone', 'BankContact', 'Contact', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 4, 9, false, 0, '', 'BankPhone', 'Phone Number', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Trade Reference 1', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Business Name', $sectionId, 4, 0, true, 1, 'AppFolio, Inc.', 'TradeRefName', 'Trade Reference Name 1', null);
// TODO: find out if these next two fields are needed?
$fieldId = $FP->createIfMissing('Contact Person', $sectionId, 4, 0, true, 0, '', 'TradeRefContact', 'Contact', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 4, 9, true, 0, '', 'TradeRefPhone1', 'Phone Number', null);
//$fieldId = $FP->createIfMissing('Acct #', $sectionId, 6, 0, true, 1, '', 'TradeRefAccount1', '', null);
//$fieldId = $FP->createIfMissing('City', $sectionId, 6, 0, true, 1, '', 'TradeRefCity1', '', null);
//$fieldId = $FP->createIfMissing('State', $sectionId, 6, 20, true, 1, $FP::STATES, 'TradeRefSt1', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Trade Reference 2', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Business Name', $sectionId, 4, 0, false, 0, '', 'TradeRef2', 'Trade Reference Name 2', null);
$fieldId = $FP->createIfMissing('Contact Person', $sectionId, 4, 0, false, 0, '', 'TradeRefContact2', 'Contact', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 4, 9, false, 0, '', 'TradeRefPhone2', 'Phone Number', null);

//$fieldId = $FP->createIfMissing('Acct #', $sectionId, 6, 0, false, 1, '', 'TradeRefAccount2', '', null);
//$fieldId = $FP->createIfMissing('City', $sectionId, 6, 0, false, 1, '', 'TradeRefCity2', '', null);
//$fieldId = $FP->createIfMissing('State', $sectionId, 6, 20, false, 1, $FP::STATES, 'TradeRefSt2', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Disbursements Account', $pageId, 12);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Routing Number', $sectionId, 12, 0, true, 0, '', 'RoutingNum', 'Routing #', null);
$fieldId = $FP->createIfMissing('Account Number', $sectionId, 12, 0, true, 0, '', 'AccountNum', 'Account #', null);
$fieldId = $FP->createIfMissing('BankName', $sectionId, 12, 0, true, 0, '', 'DisbursementsBankName1', 'Bank Name', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, true, 0, '', 'DisbursementsBankAddress1', 'Address', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 0, '', 'DisbursementsBankCity1', 'City', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, true, 0, $FP::STATES, 'DisbursementsBankState1', 'State', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, true, 0, '', 'DisbursementsBankZip1', 'Zip', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Fees Account', $pageId, 12);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Routing Number', $sectionId, 12, 0, true, 1, 'AppFolio | xxxxx2882', 'FeesRoutingNum', 'Routing #', null);
$fieldId = $FP->createIfMissing('Account Number', $sectionId, 12, 0, true, 1, 'AppFolio | xxxxx2882', 'FeesAccountNum', 'Account #', null);
$fieldId = $FP->createIfMissing('BankName', $sectionId, 12, 0, true, 1, 'Wells Fargo', 'FeesBankName1', 'Bank Name', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, true, 1, '195 Fairview Avenue', 'FeesBankAddress1', 'Address', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 1, 'Geoleta', 'FeesBankCity1', 'City', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 0, true, 1, 'CA', 'FeesBankState1', 'State', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, true, 1, '93117', 'FeesBankZip1', 'Zip', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Rejects Account', $pageId, 12);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Routing Number', $sectionId, 12, 0, true, 0, '', 'RejectsRoutingNum', 'Routing #', null);
$fieldId = $FP->createIfMissing('Account Number', $sectionId, 12, 0, true, 0, '', 'RejectsAccountNum', 'Account #', null);
$fieldId = $FP->createIfMissing('BankName', $sectionId, 12, 0, true, 0, '', 'RejectsBankName1', 'Bank Name', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, true, 0, '', 'RejectsBankAddress1', 'Address', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 0, '', 'RejectsBankCity1', 'City', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 20, true, 0, $FP::STATES, 'RejectsBankState1', 'State', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 13, true, 0, '', 'RejectsBankZip1', 'Zip', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Additional ACH Accounts', $pageId, 12);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');
$fieldId = $FP->createIfMissing('Additional ACH Accounts', $sectionId, 12, 21, false, 0, '', 'AdditionalAchAccounts', 'Comma separated value consisting of additional ACH bank information', null);
