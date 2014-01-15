<?php
$I = new WebGuy($scenario);
$UL = new UsersLoginController($I);
$CC = new CobrandController($I);
$TC = new TemplateController($I);
$PC = new PageController($I);
$SC = new SectionController($I);

// authenticate
$UL->login();

$I->wantTo('Ensure that the Cobrand UI exists');
$I->click('Axia Admin Home');
$I->see('Cobrands');

$I->click('Cobrands');
$I->seeCurrentUrlEquals(CobrandPage::$url);

// add a new cobrand
$I->wantTo('Ensure that I can add a Cobrand');
$I->see(CobrandPage::$newButtonLabel);
$I->click(CobrandPage::$newButtonLabel);
$I->seeCurrentUrlEquals(CobrandPage::$urlAdd);
$I->see(CobrandPage::$addActionTitle);
$CC->checkCobrandForm();

// I don't add any values and click submit
$I->click(CobrandPage::$submitButtonLabel);

// I see errors
$I->seeCurrentUrlEquals(CobrandPage::$urlAdd);
$I->see('Partner name cannot be empty');
$I->see('Short partner name cannot be empty');

// add required fields and click submit
$partner_name = 'cobrand_1';
$partner_name_short = 'CB1';
$logo_url = '/img/cobrand_1.png';
$partner_description = 'Cobrand_1 description goes here';
$CC->fillCobrandForm($partner_name, $partner_name_short, $logo_url, $partner_description);
$I->click(CobrandPage::$submitButtonLabel);

// should be redirected to the /admin/cobrands page and see Cobrand Saved!
$I->seeCurrentUrlEquals(CobrandPage::$url);
$I->see(CobrandPage::$savedMsg);
$I->see($partner_name);
$I->see($partner_name_short);
$I->see($logo_url);
$I->see($partner_description);

// this time click cancel on the add form
$I->wantTo('Ensure that clicking cancel goes back to the lists page');
$I->see(CobrandPage::$newButtonLabel);
$I->click(CobrandPage::$newButtonLabel);

// I see add Cobrand form
$I->seeCurrentUrlEquals(CobrandPage::$urlAdd);
$I->see(CobrandPage::$addActionTitle); // title
$CC->checkCobrandForm();

// click cancel
$I->click(CobrandPage::$cancelButtonLabel);
$I->seeCurrentUrlEquals(CobrandPage::$url);

// edit - click on the first edit and see the edit form
$I->wantTo('Ensure that I can edit a cobrand');
$I->click(CobrandPage::$editButtonLabel);
$I->see(CobrandPage::$editActionTitle); // title
$I->seeCurrentUrlMatches(CobrandPage::$urlEdit);
$CC->checkCobrandForm();

$I->click(CobrandPage::$submitButtonLabel);

// Submit the edit form
$I->seeCurrentUrlEquals(CobrandPage::$url);
$I->see(CobrandPage::$savedMsg);

$I->wantTo('Ensure that I can manipulate cobrand templates');
$I->see(CobrandPage::$listChildrenButtonLabel);
$I->click(CobrandPage::$listChildrenButtonLabel);
$I->seeCurrentUrlMatches(TemplatePage::$url);

/***************** Template ****************/
// add a new template
$I->wantTo('Ensure that I can add a Template');
$I->see(TemplatePage::$newButtonLabel);
$I->click(TemplatePage::$newButtonLabel);
$I->seeCurrentUrlMatches(TemplatePage::$urlAdd);
$I->see(TemplatePage::$addActionTitle);

$TC->checkForm();

// I don't add any values and click submit
$I->click(TemplatePage::$submitButtonLabel);

// I see errors
$I->seeCurrentUrlMatches(TemplatePage::$urlAdd);
$I->see('Template name cannot be empty');
$I->see('Logo position value not selected');

// add required fields and click submit
$templateName = 'template_1';
$logoPosition = 'left';
$includeAxiaLogo = true;
$description = 'Template_1 description goes here';
$TC->fillForm($templateName, $logoPosition, $includeAxiaLogo, $description);
$I->click(TemplatePage::$submitButtonLabel);

// should be redirected to the /admin/Templates page and see Template Saved!
$I->seeCurrentUrlMatches(TemplatePage::$url);
$I->see(TemplatePage::$savedMsg);
$I->see($templateName);
$I->see($logoPosition);
//$I->see($includeAxiaLogo);
$I->see($description);

// this time click cancel on the add form
$I->wantTo('Ensure that clicking cancel goes back to the lists page');
$I->see(TemplatePage::$newButtonLabel);
$I->click(TemplatePage::$newButtonLabel);

// I see add Template form
$I->seeCurrentUrlMatches(TemplatePage::$urlAdd);
$I->see(TemplatePage::$addActionTitle); // title
$TC->checkForm();

// click cancel
$I->click(TemplatePage::$cancelButtonLabel);
$I->seeCurrentUrlMatches(TemplatePage::$url);

// edit - click on the first edit and see the edit form
$I->wantTo('Ensure that I can edit a Template');
$I->click(TemplatePage::$editButtonLabel);
$I->see(TemplatePage::$editActionTitle);
$TC->checkForm();

$I->click(TemplatePage::$submitButtonLabel);

// Submit the edit form
$I->seeCurrentUrlMatches(TemplatePage::$url);
$I->see(TemplatePage::$savedMsg);

$I->wantTo('Ensure that I can manipulate template pages');
$I->see(TemplatePage::$listChildrenButtonLabel);
$I->click(TemplatePage::$listChildrenButtonLabel);
$I->seeCurrentUrlMatches(PagePage::$url); // horrible name :(
/*************** End Template **************/

/***************** Page ****************/
// add a new page
$I->wantTo('Ensure that I can add a Page');
$I->see(PagePage::$newButtonLabel);
$I->click(PagePage::$newButtonLabel);
$I->seeCurrentUrlMatches(PagePage::$urlAdd);
$I->see(PagePage::$addActionTitle);

$PC->checkForm();

// I don't add any values and click submit
$I->click(PagePage::$submitButtonLabel);

// I see errors
$I->seeCurrentUrlMatches(PagePage::$urlAdd);
$I->see('Page name cannot be empty');


// add required fields and click submit
$pageName = 'page_1';
$description = 'Page_1 description goes here';
$PC->fillForm($pageName, $description);
$I->click(PagePage::$submitButtonLabel);

// should be redirected to the /admin/Pages page and see Page Saved!
$I->seeCurrentUrlMatches(PagePage::$url);
$I->see(PagePage::$savedMsg);
$I->see($pageName);
$I->see($description);

// this time click cancel on the add form
$I->wantTo('Ensure that clicking cancel goes back to the lists page');
$I->see(PagePage::$newButtonLabel);
$I->click(PagePage::$newButtonLabel);

// I see add Page form
$I->seeCurrentUrlMatches(PagePage::$urlAdd);
$I->see(PagePage::$addActionTitle); // title
$PC->checkForm();

// click cancel
$I->click(PagePage::$cancelButtonLabel);
$I->seeCurrentUrlMatches(PagePage::$url);

// edit - click on the first edit and see the edit form
$I->wantTo('Ensure that I can edit a Page');
$I->click(PagePage::$editButtonLabel);
$I->see(PagePage::$editActionTitle); // title
$PC->checkForm();

$I->click(PagePage::$submitButtonLabel);

// Submit the edit form
$I->seeCurrentUrlMatches(PagePage::$url);
$I->see(PagePage::$savedMsg);

$I->wantTo('Ensure that I can manipulate template pages');
$I->see(PagePage::$listChildrenButtonLabel);
$I->click(PagePage::$listChildrenButtonLabel);
$I->seeCurrentUrlMatches(SectionPage::$url);
/*************** End Page **************/

/***************** Section ****************/
// add a new section
$I->wantTo('Ensure that I can add a Section');
$I->see(SectionPage::$newButtonLabel);
$I->click(SectionPage::$newButtonLabel);
$I->seeCurrentUrlMatches(SectionPage::$urlAdd);
$I->see(SectionPage::$addActionTitle);

$SC->checkForm();

// I don't add any values and click submit
$I->click(SectionPage::$submitButtonLabel);

// I see errors
$I->seeCurrentUrlMatches(SectionPage::$urlAdd);
$I->see('Section name cannot be empty');
$I->see('Invalid width value used, please select a number between 1 and 12');

// add required fields and click submit
$sectionName = 'section_1';
$width = 12;
$description = 'Section_1 description goes here';
$SC->fillForm($sectionName, $width, $description);
$I->click(SectionPage::$submitButtonLabel);

// should be redirected to the /admin/Sections section and see Section Saved!
$I->seeCurrentUrlMatches(SectionPage::$url);
$I->see(SectionPage::$savedMsg);
$I->see($sectionName);
$I->see($description);

// this time click cancel on the add form
$I->wantTo('Ensure that clicking cancel goes back to the lists section');
$I->see(SectionPage::$newButtonLabel);
$I->click(SectionPage::$newButtonLabel);

// I see add Section form
$I->seeCurrentUrlMatches(SectionPage::$urlAdd);
$I->see(SectionPage::$addActionTitle); // title
$SC->checkForm();

// click cancel
$I->click(SectionPage::$cancelButtonLabel);
$I->seeCurrentUrlMatches(SectionPage::$url);

// edit - click on the first edit and see the edit form
$I->wantTo('Ensure that I can edit a Section');
$I->click(SectionPage::$editButtonLabel);
$I->see(SectionPage::$editActionTitle); // title
$SC->checkForm();

$I->click(SectionPage::$submitButtonLabel);

// Submit the edit form
$I->seeCurrentUrlMatches(SectionPage::$url);
$I->see(SectionPage::$savedMsg);
/*
$I->wantTo('Ensure that I can manipulate template sections');
$I->see(SectionPage::$listChildrenButtonLabel);
$I->click(SectionPage::$listChildrenButtonLabel);
$I->seeCurrentUrlMatches(FieldPage::$url);
*/
/*************** End Section **************/


/***************** Field ****************/
/*************** End Field **************/

/*-------------- CLEAN UP!!!------------*/
// get he object id's that we need
$newCobrandId =  $I->grabFromDatabase('onlineapp_cobrands', 'id', array('partner_name_short' => $partner_name_short));
$newTemplateId = $I->grabFromDatabase('onlineapp_templates', 'id', array('name' => $templateName));
$newPageId =     $I->grabFromDatabase('onlineapp_template_pages', 'id', array('name' => $pageName));
$newSectionId =  $I->grabFromDatabase('onlineapp_template_sections', 'id', array('name' => $sectionName));

// delete field

// delete section
$I->wantTo('Ensure that I can delete a template section');
$I->sendAjaxPostRequest('/admin/templatepages/'.$newPageId.'/templatesections/delete/'.$newSectionId, array('_method' => 'POST', 'notifications' => true));

// delete page
$I->wantTo('Ensure that I can delete a template page');
$I->sendAjaxPostRequest('/admin/templates/'.$newTemplateId.'/templatepages/delete/'.$newPageId, array('_method' => 'POST', 'notifications' => true));

// delete template
$I->wantTo('Ensure that I can delete a template');
$I->sendAjaxPostRequest('/admin/cobrands/'.$newCobrandId.'/templates/delete/'.$newTemplateId, array('_method' => 'POST', 'notifications' => true));

// manually refresh the page
$I->amOnPage('/admin/cobrands/'.$newCobrandId.'/templates');
$I->see(TemplatePage::$deletedMsg);

// delete cobrand
$I->wantTo('Ensure that I can delete a cobrand');
$I->sendAjaxPostRequest('/admin/cobrands/delete/'.$newCobrandId, array('_method' => 'POST', 'notifications' => true));

// manually refresh the page
$I->amOnPage(CobrandPage::$url);
$I->see(CobrandPage::$deletedMsg);
