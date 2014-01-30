<?php
use Codeception\Util\Debug;

$I = new WebGuy($scenario);
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

// start with the cobrand
$cobrandId = $CP->createIfMissing('Axia');

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
$fieldId = $FP->createIfMissing('Ownership Type', $sectionId, 12, 4, true, 1, 'Corporation::corporation,Sole Prop::sole prop,LLC::llc,Partnership::partnership,Non Profit/Tax Exempt (fed form 501C)::non profit,Other::other', 'Owner Type - ', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('CORPORATE INFORMATION', $pageId, 6);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create a new field
$fieldId = $FP->createIfMissing('Legal Business Name', $sectionId, 12, 0, true, 1, '', 'CorpName', '', null);
$fieldId = $FP->createIfMissing('Mailing Address', $sectionId, 12, 0, true, 1, '', 'CorpAddress', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 1, '', 'CorpCity', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 0, true, 1, '', 'CorpState', '', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 0, true, 1, '', 'CorpZip', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 12, 0, true, 1, '', 'CorpPhone', '', null);
$fieldId = $FP->createIfMissing('Fax', $sectionId, 12, 0, true, 1,'', 'CorpFax', '', null);
$fieldId = $FP->createIfMissing('Corp Contact Name', $sectionId, 12, 0, true, 1, '', 'CorpContact', '', null);
$fieldId = $FP->createIfMissing('Title', $sectionId, 12, 0, true, 1, '', 'CorpTitle', '', null);
$fieldId = $FP->createIfMissing('Email', $sectionId, 12, 0, true, 1, '', 'Email', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('LOCATION INFORMATION', $pageId, 6);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create new fields
$fieldId = $FP->createIfMissing('Same As Corporate Information', $sectionId, 12, 3, false, 1, '', 'SameAsCorpInfo', '', null);
$fieldId = $FP->createIfMissing('Business Name (DBA)', $sectionId, 12, 0, true, 1, '', 'DBA', '', null);
$fieldId = $FP->createIfMissing('Mailing Address', $sectionId, 12, 0, true, 1, '', 'Address', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 1, '', 'City', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 0, true, 1, '', 'State', '', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 0, true, 1, '', 'Zip', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 12, 0, true, 1, '', 'PhoneNumber', '', null);
$fieldId = $FP->createIfMissing('Fax', $sectionId, 12, 0, true, 1,'', 'FaxNumber', '', null);
$fieldId = $FP->createIfMissing('Corp Contact Name', $sectionId, 12, 0, true, 1, '', 'Contact', '', null);
$fieldId = $FP->createIfMissing('Title', $sectionId, 12, 0, true, 1, '', 'Title', '', null);
$fieldId = $FP->createIfMissing('Email', $sectionId, 12, 0, true, 1, '', 'Email', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('ADDITIONAL BUSINESS INFORMATION', $pageId, 6);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create new fields
$fieldId = $FP->createIfMissing('Federal Tax ID',$sectionId, 12, 0, true, 1, '', 'Tax ID', '', null);
$fieldId = $FP->createIfMissing('Website',$sectionId, 12, 0, true, 1, '', 'WebAddress', '', null);
$fieldId = $FP->createIfMissing('Customer Service Phone',$sectionId, 12, 0, true, 1, '', 'Customer Service Phone', '', null);
$fieldId = $FP->createIfMissing('Business Open Date', $sectionId, 12, 1, true, 1, '', 'OpenDate', '', null);
$fieldId = $FP->createIfMissing('Length of Current Ownership',$sectionId, 12, 0, true, 1, '', 'Ownership Length', '', null);
$fieldId = $FP->createIfMissing('Existing Axia Merchant?', $sectionId, 6, 4, true, 1, 'Yes::0,No::1', 'Existing_Axia_Merchant_', '', null);
$fieldId = $FP->createIfMissing('Current MID #',$sectionId, 6, 0, false, 1, '', 'Current MID', '', null);
$fieldId = $FP->createIfMissing('General Comments',$sectionId, 12, 0, true, 1, '', 'General Comments', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('LOCATION TYPE', $pageId);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Location Type', $sectionId, 12, 4, true, 1, 'Retail Store::retail store,Industrial::industrial,Trade::trade,Office::office,Residence::residence,Other::other', 'location_type_', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('MERCHANT', $pageId);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Merchant Ownes/Leases', $sectionId, 12,4, true, 1, 'Owns::0,Leases::1', 'mfn', '', null);
$fieldId = $FP->createIfMissing('Landlord Name', $sectionId, 12, 0, false, 1, '', 'Landlord', '', null);
$fieldId = $FP->createIfMissing('Landlord Phone', $sectionId, 12, 0, false, 1, '', 'Landlord Phone', '', null);


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

$fieldId = $FP->createIfMissing('Business Type', $sectionId, 12, 4, true, 1, 'Retail::retail,Restaurant::restaurant,Lodging::lodging,MOTO::moto,Internet::internet,Grocery::grocery', 'Business Type - ', '', null);

$fieldId = $FP->createIfMissing('Products/Services Sold', $sectionId, 12, 0, true, 1, '', 'Products Services Sold', '', null);
$fieldId = $FP->createIfMissing('Return Policy', $sectionId, 12, 0, true, 1, '', 'Return Policy', '', null);
$fieldId = $FP->createIfMissing('Days Until Product Delivery', $sectionId, 12, 0, true, 1, '', 'Return Policy', '', null);
$fieldId = $FP->createIfMissing('Monthly Volume', $sectionId, 4, 0, true, 1, '', 'Return Policy', '', null);
$fieldId = $FP->createIfMissing('Average Ticket', $sectionId, 4, 0, true, 1, '', 'AvgTicket', '', null);
$fieldId = $FP->createIfMissing('Highest Ticket', $sectionId, 4, 0, true, 1, '', 'MaxSalesAmt', '', null);
$fieldId = $FP->createIfMissing('Current Processor', $sectionId, 12, 0, true, 1, '', 'MaxSalesAmt', '', null);
$fieldId = $FP->createIfMissing('Method of Sales', $sectionId, 6, 5, true, 1, 'Card Present Swiped::Card Present Swiped,Card Present Imprint::Card Present Imprint,Card Not Present (Keyed)::Card Not Present (Keyed),Card Not Present (Internet)::Card Not Present (Internet)', 'Method of Sales', '', null);
$fieldId = $FP->createIfMissing('% of Product Sold', $sectionId, 6, 5, true, 1, 'Direct to Customer::Direct to Customer,Direct to Business::Direct to Business,Direct to Government::Direct to Government', 'Percent of Product Sold', '', null);

// go back to the sections page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('High Volume Months', $pageId);

// go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// create fields
$fieldId = $FP->createIfMissing('Jan', $sectionId, 1, 3, false, 1, '', 'high_volume_january', '', null);
$fieldId = $FP->createIfMissing('Feb', $sectionId, 1, 3, false, 1, '', 'high_volume_february', '', null);
$fieldId = $FP->createIfMissing('Mar', $sectionId, 1, 3, false, 1, '', 'high_volume_march', '', null);
$fieldId = $FP->createIfMissing('Apr', $sectionId, 1, 3, false, 1, '', 'high_volume_april', '', null);
$fieldId = $FP->createIfMissing('May', $sectionId, 1, 3, false, 1, '', 'high_volume_may', '', null);
$fieldId = $FP->createIfMissing('Jun', $sectionId, 1, 3, false, 1, '', 'high_volume_june', '', null);
$fieldId = $FP->createIfMissing('Jul', $sectionId, 1, 3, false, 1, '', 'high_volume_july', '', null);
$fieldId = $FP->createIfMissing('Aug', $sectionId, 1, 3, false, 1, '', 'high_volume_august', '', null);
$fieldId = $FP->createIfMissing('Sep', $sectionId, 1, 3, false, 1, '', 'high_volume_september', '', null);
$fieldId = $FP->createIfMissing('Oct', $sectionId, 1, 3, false, 1, '', 'high_volume_october', '', null);
$fieldId = $FP->createIfMissing('Nov', $sectionId, 1, 3, false, 1, '', 'high_volume_november', '', null);
$fieldId = $FP->createIfMissing('Dec', $sectionId, 1, 3, false, 1, '', 'high_volume_december', '', null);

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

$fieldId = $FP->createIfMissing('Bank Name', $sectionId, 12, 0, true, 1, '', 'BankName', '', null);
$fieldId = $FP->createIfMissing('Contact Name', $sectionId, 12, 0, false, 1, '', 'BankContact', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 12, 0, false, 1, '', 'BankPhone', '', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, false, 1, '', 'BankAddress', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, false, 1, '', 'BankCity', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 0, false, 1, '', 'BankState', '', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 0, false, 1, '', 'BankZip', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Depository Account', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Routing Number', $sectionId, 12, 0, true, 1, '', 'RoutingNum', '', null);
$fieldId = $FP->createIfMissing('Account Number', $sectionId, 12, 0, true, 1, '', 'AccountNum', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Fees Account', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Routing Number', $sectionId, 12, 0, true, 1, '', 'FeesRoutingNum', '', null);
$fieldId = $FP->createIfMissing('Account Number', $sectionId, 12, 0, true, 1, '', 'FeesAccountNum', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Trade Reference 1', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Business Name', $sectionId, 12, 0, true, 1, '', 'TradeRef1', '', null);
$fieldId = $FP->createIfMissing('Contact Person', $sectionId, 12, 0, true, 1, '', 'TradeRefContact', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 6, 0, true, 1, '', 'TradeRefPhone1', '', null);
$fieldId = $FP->createIfMissing('Acct #', $sectionId, 6, 0, true, 1, '', 'TradeRefAccount1', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 6, 0, true, 1, '', 'TradeRefCity1', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 6, 0, true, 1, '', 'TradeRefState1', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Trade Reference 2', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

$fieldId = $FP->createIfMissing('Business Name', $sectionId, 12, 0, true, 1, '', 'TradeRef2', '', null);
$fieldId = $FP->createIfMissing('Contact Person', $sectionId, 12, 0, true, 1, '', 'TradeRefContact2', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 6, 0, true, 1, '', 'TradeRefPhone2', '', null);
$fieldId = $FP->createIfMissing('Acct #', $sectionId, 6, 0, true, 1, '', 'TradeRefAccount2', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 6, 0, true, 1, '', 'TradeRefCity2', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 6, 0, true, 1, '', 'TradeRefState2', '', null);

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

//$fieldId = $FP->createIfMissing('Bank Name', $sectionId, 12, 0, true, 1, '', 'BankName', '', null);
$fieldId = $FP->createIfMissing('Do you currently accept American Express?', $sectionId, 6, 4, true, 1,  'Yes::Exist,No::New', 'AE', '', null);
$fieldId = $FP->createIfMissing('Please Provide Existing SE#', $sectionId, 6, 0, false, 1, '', 'AmexNum', '', null);
$fieldId = $FP->createIfMissing('Do you want to accept American Express', $sectionId, 12, 4, false, 1, 'Yes::0,No::1', 'wants to accept amex', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Discover Information', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Do you want to accept Discover?', $sectionId, 12, 4, true, 1,  'Yes::Exist,No::New', 'DiscNew', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Terminal/Software Type(1)', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Quantity', $sectionId, 2, 0, true, 1, '', 'QTY1', '', null);
$fieldId = $FP->createIfMissing('Type', $sectionId, 3, 0, true, 1, '', 'Terminal1', '', null);
$fieldId = $FP->createIfMissing('Do You Use Autoclose?', $sectionId, 3, 4, true, 1, 'Yes::Yes,No::No', 'AutoClose', '', null);
$fieldId = $FP->createIfMissing('If Yes, What Time?', $sectionId, 4, 2, false, 1, '', 'Autoclose Time', '', null);
$fieldId = $FP->createIfMissing('Provider', $sectionId, 12, 4, true, 1, 'Axia::Axia,Merchant::Merchant', 'Terminal1Provider', '', null);
$fieldId = $FP->createIfMissing('Terminal Programming Information (please select all that apply)', $sectionId, 12, 6, false, 2, '', 'label', '', null);

$fieldId = $FP->createIfMissing('AVS', $sectionId, 2, 3, false, 1, '', 'AVS', '', null);
$fieldId = $FP->createIfMissing('Server #s', $sectionId, 2, 3, false,  1, '', 'Server', '', null);
$fieldId = $FP->createIfMissing('Tips', $sectionId, 2, 3, false, 1, '', 'Tips', '', null);
$fieldId = $FP->createIfMissing('Invoice #', $sectionId, 2, 3, false, 1, '', 'Invoice', '', null);
$fieldId = $FP->createIfMissing('Purchasing Cards', $sectionId, 2, 3, false, 1, '', 'Purchasing Cards', '', null);
$fieldId = $FP->createIfMissing('Do you accept Debit on this terminal?', $sectionId, 4, 4, true, 1, 'Yes::Yes,No::No', 'term_accept_debit', '', null);
$fieldId = $FP->createIfMissing('If Yes, what type of PIN Pad?', $sectionId, 4, 0, false, 1, '', 'PinPad1', '', null);
$fieldId = $FP->createIfMissing('PIN Pad Quantity', $sectionId, 4, 0, false, 1, '', 'QTY - PP1', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('Terminal/Software Type(2)', $pageId);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Quantity', $sectionId, 2, 0, true, 1, '', 'QTY2', '', null);
$fieldId = $FP->createIfMissing('Type', $sectionId, 3, 0, true, 1, '', 'Terminal2', '', null);
$fieldId = $FP->createIfMissing('Do You Use Autoclose?', $sectionId, 3, 4, true, 1, 'Yes::Yes,No::No', 'AutoClose_2', '', null);
$fieldId = $FP->createIfMissing('If Yes, What Time?', $sectionId, 4, 2, false, 1, '', 'Autoclose Time 2', '', null);
$fieldId = $FP->createIfMissing('Provider', $sectionId, 12, 4, true, 1, 'Axia::Axia,Merchant::Merchant', 'Terminal2Provider', '', null);
$fieldId = $FP->createIfMissing('Terminal Programming Information (please select all that apply)', $sectionId, 12, 6, false, 2, '', 'label', '', null);

$fieldId = $FP->createIfMissing('AVS', $sectionId, 2, 3, false, 1, '', 'AVS_2', '', null);
$fieldId = $FP->createIfMissing('Server #s', $sectionId, 2, 3, false,  1, '', 'Server_2', '', null);
$fieldId = $FP->createIfMissing('Tips', $sectionId, 2, 3, false, 1, '', 'Tips_2', '', null);
$fieldId = $FP->createIfMissing('Invoice #', $sectionId, 2, 3, false, 1, '', 'Invoice_2', '', null);
$fieldId = $FP->createIfMissing('Purchasing Cards', $sectionId, 2, 3, false, 1, '', 'Purchasing Cards_2', '', null);
$fieldId = $FP->createIfMissing('Do you accept Debit on this terminal?', $sectionId, 4, 4, true, 1, 'Yes::Yes,No::No', 'term_accept_debit_2', '', null);
$fieldId = $FP->createIfMissing('If Yes, what type of PIN Pad?', $sectionId, 4, 0, false, 1, '', 'PinPad2', '', null);
$fieldId = $FP->createIfMissing('PIN Pad Quantity', $sectionId, 4, 0, false, 1, '', 'QTY - PP2', '', null);


// add another page for this template
$I->amOnPage('/admin/templates/'.$templateId.'/templatepages');

// create a new page for the template
$pageId = $PP->createIfMissing('Ownership Information', $templateId);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('OWNER / OFFICER (1)', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Percentage Ownership', $sectionId, 12, 0, true, 1, '', 'OwnerEquity', '', null);
$fieldId = $FP->createIfMissing('Full Name', $sectionId, 12, 0, true, 1, '', 'Principal', '', null);
$fieldId = $FP->createIfMissing('Title', $sectionId, 12, 0, true, 1, '', 'Owner1Title', '', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, true, 1, '', 'Owner1Address', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 1, '', 'Owner1City', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 0, true, 1, '', 'Owner1State', '', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 0, true, 1, '', 'Owner1Zip', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 6, 0, true, 1, '', 'Owner1Phone', '', null);
$fieldId = $FP->createIfMissing('Fax', $sectionId, 6, 0, true, 1, '', 'Owner1Fax', '', null);
$fieldId = $FP->createIfMissing('Email', $sectionId, 4, 0, true, 1, '', 'Owner1Email', '', null);
$fieldId = $FP->createIfMissing('SSN', $sectionId, 4, 0, true, 1, '', 'Owner1SSN', '', null);
$fieldId = $FP->createIfMissing('Date of Birth', $sectionId, 8,  1, true, 1, '', 'Owner1DOB', '', null);

// next go to the sections for this page
$I->amOnPage('/admin/templatepages/'.$pageId.'/templatesections');

// create a new section for the page
$sectionId = $SP->createIfMissing('OWNER / OFFICER (2)', $pageId, 6);

// next go to the sections for this page
$I->amOnPage('/admin/templatesections/'.$sectionId.'/templatefields');

// fields
$fieldId = $FP->createIfMissing('Percentage Ownership', $sectionId, 12, 0, true, 1, '', 'Owner2Equity', '', null);
$fieldId = $FP->createIfMissing('Full Name', $sectionId, 12, 0, true, 1, '', 'Owner2Name', '', null);
$fieldId = $FP->createIfMissing('Title', $sectionId, 12, 0, true, 1, '', 'Owner2Title', '', null);
$fieldId = $FP->createIfMissing('Address', $sectionId, 12, 0, true, 1, '', 'Owner2Address', '', null);
$fieldId = $FP->createIfMissing('City', $sectionId, 4, 0, true, 1, '', 'Owner2City', '', null);
$fieldId = $FP->createIfMissing('State', $sectionId, 4, 0, true, 1, '', 'Owner2State', '', null);
$fieldId = $FP->createIfMissing('Zip', $sectionId, 4, 0, true, 1, '', 'Owner2Zip', '', null);
$fieldId = $FP->createIfMissing('Phone', $sectionId, 6, 0, true, 1, '', 'Owner2Phone', '', null);
$fieldId = $FP->createIfMissing('Fax', $sectionId, 6, 0, true, 1, '', 'Owner2Fax', '', null);
$fieldId = $FP->createIfMissing('Email', $sectionId, 4, 0, true, 1, '', 'Owner2Email', '', null);
$fieldId = $FP->createIfMissing('SSN', $sectionId, 4, 0, true, 1, '', 'Owner2SSN', '', null);
$fieldId = $FP->createIfMissing('Date of Birth', $sectionId, 8,  1, true, 1, '', 'Owner2DOB', '', null);

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
Debug::debug($sectionId);
// fields
$fieldId = $FP->createIfMissing('Referral Business 1', $sectionId, 4, 0, true, 1, '', 'Referral1Business', '', null);
$fieldId = $FP->createIfMissing('Owner/Officer 1', $sectionId, 4, 0, true, 1, '', 'Referral1Owner/Officer', '', null);
$fieldId = $FP->createIfMissing('Phone # 1', $sectionId, 4, 0, true, 1, '', 'Referral1Phone', '', null);
$fieldId = $FP->createIfMissing('Referral Business 2', $sectionId, 4, 0, true, 1, '', 'Referral2Business', '', null);
$fieldId = $FP->createIfMissing('Owner/Officer 2', $sectionId, 4, 0, true, 1, '', 'Referral2Owner/Officer', '', null);
$fieldId = $FP->createIfMissing('Phone # 2', $sectionId, 4, 0, true, 1, '', 'Referral2Phone', '', null);
$fieldId = $FP->createIfMissing('Referral Business 3', $sectionId, 4, 0, true, 1, '', 'Referral3Business', '', null);
$fieldId = $FP->createIfMissing('Owner/Officer 3', $sectionId, 4, 0, true, 1, '', 'Referral3Owner/Officer', '', null);
$fieldId = $FP->createIfMissing('Phone # 3', $sectionId, 4, 0, true, 1, '', 'Referral3Phone', '', null);
