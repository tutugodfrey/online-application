<?php
$I = new WebGuy($scenario);
$UL = new UsersLoginController($I);
$CB = new CobrandController($I);

$I->haveInDatabase('onlineapp_groups',
	array(
		'name' => 'admin', 
		'created' => '2014-01-10 13:50:53',
		'modified' => '2014-01-10 13:50:53',
	)
);
$email = $I->grabFromDatabase('onlineapp_users', 'email', array('email' => 'dev@axiapayments.com'));
if (is_null($email)) {
	$group_id = $I->grabFromDatabase('onlineapp_groups', 'id', array('name' => 'admin'));
	$I->haveInDatabase('onlineapp_users',
		array(
			'email' => 'dev@axiapayments.com',
			'password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
			'group_id' => $group_id,
			'created' => '2014-01-10 13:50:53',
			'modified' => '2014-01-10 13:50:53',
			'token_uses' => 0,
			'firstname' => 'code',
			'lastname' => 'cept',
			'active' => 't',
			'api_password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
			'api' => 'f'
		)
	);
}

$UL->login();

$I->wantTo('Ensure that the Cobrand UI exists');
$I->click('Axia Admin Home');
$I->see('Cobrands');

$I->click('Cobrands');
$I->seeCurrentUrlEquals(CobrandPage::$URL);

// add a new cobrand
$I->wantTo('Ensure that I can add a new Cobrand');
$I->see(CobrandPage::$newCobrandButtonLabel);
$I->click(CobrandPage::$newCobrandButtonLabel);
$I->seeCurrentUrlEquals(CobrandPage::$URL . '/add');

// I see add Cobrand form
$I->see('Add Cobrand'); // title
$CB->checkCobrandForm();

// I don't add any values and click submit
$I->click(CobrandPage::$submitButtonLabel);

// I see errors
$I->seeCurrentUrlEquals(CobrandPage::$URL . '/add');
$I->see('Partner name cannot be empty');
$I->see('Short partner name cannot be empty');

// add required fields and click submit
$partner_name = 'cobrand_1';
$partner_name_short = 'CB1';
$logo_url = '/img/cobrand_1.png';
$partner_description = 'Cobrand_1 description goes here';
$CB->fillCobrandForm($partner_name, $partner_name_short, $logo_url, $partner_description);
$I->click(CobrandPage::$submitButtonLabel);

// should be redirected to the /admin/cobrands page and see Cobrand Saved!
$I->seeCurrentUrlEquals(CobrandPage::$URL);
$I->see(CobrandPage::$cobrandSavedMsg);
$I->see($partner_name);
$I->see($partner_name_short);
$I->see($logo_url);
$I->see($partner_description);

// this time click cancel on the add form
$I->wantTo('Ensure that clicking cancel goes back to the lists page');
$I->see(CobrandPage::$newCobrandButtonLabel);
$I->click(CobrandPage::$newCobrandButtonLabel);

// I see add Cobrand form
$I->seeCurrentUrlEquals(CobrandPage::$URL . '/add');
$I->see('Add Cobrand'); // title
$CB->checkCobrandForm();

// click cancel
$I->click(CobrandPage::$cancelButtonLabel);
$I->seeCurrentUrlEquals(CobrandPage::$URL);

// edit - click on the first edit and see the edit form
$I->wantTo('Ensure that I can edit a cobrand');
$I->click(CobrandPage::$editButtonLabel);
$I->seeCurrentUrlEquals(CobrandPage::$URL . '/edit/1');
$I->see('Edit Cobrand'); // title
$CB->checkCobrandForm();

$I->click(CobrandPage::$submitButtonLabel);

// Submit the edit form
$I->seeCurrentUrlEquals(CobrandPage::$URL);
$I->see(CobrandPage::$cobrandSavedMsg);

// delete
$I->wantTo('Ensure that I can delete a cobrand');
$newCobrandId = $I->grabFromDatabase('onlineapp_cobrands', 'id', array('partner_name_short' => 'CB1'));
$I->sendAjaxPostRequest('/admin/cobrands/delete/'.$newCobrandId, array('_method' => 'POST', 'notifications' => true)); // POST

// manually refresh the page
$I->amOnPage(CobrandPage::$URL);
$I->see(CobrandPage::$cobrandDeletedMsg);
