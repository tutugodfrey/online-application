<?php

use Codeception\Util\Debug;

class CobrandPage
{
	// include url of current page
	static $URL = '/admin/cobrands';

	/**
	 * Declare UI map for this page here. CSS or XPath allowed.
	 * public static $usernameField = '#username';
	 * public static $formSubmitButton = "#mainForm input[type=submit]";
	 */
	// form fields and labels
	static $partnerNameField = 'CobrandPartnerName';
	static $partnerNameLabel = 'Partner Name';

	static $partnerNameShortField = 'CobrandPartnerNameShort';
	static $partnerNameShortLabel = 'Partner Name Short';

	static $logoUrlField = 'CobrandLogoUrl';
	static $logoUrlLabel = 'Logo Url';

	static $descriptionField = 'CobrandDescription';
	static $descriptionLabel = 'Description';

	// buttons
	static $newButtonLabel = 'New Cobrand';
	static $editButtonLabel = 'Edit';
	static $cancelButtonLabel = 'Cancel';
	static $submitButtonLabel = 'Submit';
	static $deleteButtonLabel = 'Delete';
	static $listChildrenButtonLabel = 'List Templates';

	// action titles
	static $addActionTitle = 'Add Cobrand';
	static $editActionTitle = 'Edit Cobrand';

	// action messages
	static $savedMsg = 'Cobrand Saved!';
	static $deletedMsg = 'Cobrand Deleted!';

	// children
	static $childModelName = 'templates';

	/**
	 * Basic route example for your current URL
	 * You can append any additional parameter to URL
	 * and use it in tests like: EditPage::route('/123-post');
	 */
	public static function route($param)
	{
		// TODO: switch to use 
		return static::$URL.$param;
	}

	/**
	 * Similar to route above but intended to be used with
	 * $I->seeCurrentUrlMatches()
	 * You can append any additional parameter to URL
	 * and use it in tests like: EditPage::route('/123-post');
	 */
	public static function likeRoute($begin, $end, $action, $param)
	{
		if (strlen($action) > 0) {
			$action = '/'.$action;
		}
		return $end.static::$URL.$action.$param.$end;
	}

	/**
	 * @var WebGuy;
	 */
	protected $webGuy;

	public function __construct(WebGuy2 $I)
	{
		$this->webGuy = $I;
	}

	/**
	 * @return CobrandPage
	 */
	public static function of(WebGuy2 $I)
	{
		return new static($I);
	}

	// utility functions
	public function checkForm() {
		$this->webGuy->see(static::$partnerNameLabel);
		$this->webGuy->see(static::$partnerNameShortLabel);
		$this->webGuy->see(static::$logoUrlLabel);
		$this->webGuy->see(static::$descriptionLabel);
		$this->webGuy->see(static::$submitButtonLabel);
	}

	public function fillForm($partner_name, $partner_name_short, $logo_url, $partner_description) {
		$this->webGuy->fillField(static::$partnerNameField, $partner_name);
		$this->webGuy->fillField(static::$partnerNameShortField, $partner_name_short);
		$this->webGuy->fillField(static::$logoUrlField, $logo_url);
		$this->webGuy->fillField(static::$descriptionField, $partner_description);
	}

	public function createIfMissing($partnerName = 'Axia', $partnerNameShort = 'AX', $imgLogoPath = '/img/axia_logo.png', $description = '') {
		$cobrandId = $this->getCobrandId($partnerName);
		if (strlen($cobrandId) == 0) {
			// create it
			$this->webGuy->amOnPage(static::route(''));
			$this->webGuy->click(static::$newButtonLabel);
			$this->checkForm();
			$this->fillForm($partnerName, $partnerNameShort, $imgLogoPath, $description);
			$this->webGuy->click(static::$submitButtonLabel);
			$this->webGuy->seeCurrentUrlEquals(static::route(''));
			// and we should see 'Cobrand Created'
			$cobrandId = $this->getCobrandId($partnerName);
		} else {
			Debug::debug('NOOP - cobrand already exists.');
		}

		return $cobrandId;
	}

	public function getCobrandId($partnerName) {
		$Id =  $this->webGuy->grabFromDatabase('onlineapp_cobrands', 'id', array('partner_name' => $partnerName));
		return $Id;
	}
}