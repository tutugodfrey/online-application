<?php
$I = new WebGuy($scenario);
$I->wantTo('ensure that login page works');
$I->amOnPage('/users/login'); 
$I->see('Login');
$I->see('Email');
$I->see('Password');
$I->fillField('UserEmail','dev@axiapayments.com');
$I->fillField('UserPassword','123456');
$I->click('Login');
$I->see('Axia Admin Home');
$I->see('Logout');
?>
