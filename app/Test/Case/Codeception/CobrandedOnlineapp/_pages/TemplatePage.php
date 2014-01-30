<?php

use Codeception\Util\Debug;

class TemplatePage
{
	// include url of current page
	static $URL = '/admin/cobrands/\d*/templates';

	/**
	 * Declare UI map for this page here. CSS or XPath allowed.
	 * public static $usernameField = '#username';
	 * public static $formSubmitButton = "#mainForm input[type=submit]";
	 */

	/**
	 * Basic route example for your current URL
	 * You can append any additional parameter to URL
	 * and use it in tests like: EditPage::route('/123-post');
	 */
	 public static function route($param)
	 {
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
		return $begin.static::$URL.$action.$param.$end;
	}

	// form fields and labels
	static $templateNameField = 'TemplateName';
	static $templateNameLabel = 'Name';

	static $templateLogoPositionField = 'TemplateLogoPosition';
	static $templateLogoPositionLabel = 'Logo Position';

	static $templateIncludeAxiaLogoField = 'TemplateIncludeAxiaLogo';
	static $templateIncludeAxiaLogoLabel = 'Include Axia Logo';

	static $descriptionField = 'TemplateDescription';
	static $descriptionLabel = 'Description';

	// buttons
	static $newButtonLabel = 'New Template';
	static $editButtonLabel = 'Edit';
	static $cancelButtonLabel = 'Cancel';
	static $submitButtonLabel = 'Submit';
	static $deleteButtonLabel = 'Delete';
	static $listChildrenButtonLabel = 'List Pages';

	// action titles
	static $addActionTitle = 'Add Template';
	static $editActionTitle = 'Edit Template';

	// action messages
	static $savedMsg = 'Template Saved!';
	static $deletedMsg = 'Template Deleted!';

	// children
	static $childModelName = 'pages';

	/**
	 * @var WebGuy;
	 */
	protected $webGuy;

	public function __construct(WebGuy $I)
	{
		$this->webGuy = $I;
	}

	/**
	 * @return TemplatePage
	 */
	public static function of(WebGuy $I)
	{
		return new static($I);
	}

	// utility functions
	public function checkForm() {
		$this->webGuy->see(static::$templateNameLabel);
		$this->webGuy->see(static::$templateLogoPositionLabel);
		$this->webGuy->see(static::$templateIncludeAxiaLogoLabel);
		$this->webGuy->see(static::$descriptionLabel);
		$this->webGuy->see(static::$submitButtonLabel);
	}

	public function fillForm($templateName, $templateLogoPosition, $templateIncludeAxiaLogo, $description) {
		$this->webGuy->fillField(static::$templateNameField, $templateName);
		$this->webGuy->selectOption(static::$templateLogoPositionField, $templateLogoPosition);
		if ($templateIncludeAxiaLogo == true) {
			$this->webGuy->checkOption(static::$templateIncludeAxiaLogoField);
		} else {
			$this->webGuy->uncheckOption(static::$templateIncludeAxiaLogoField);
		}
		$this->webGuy->fillField(static::$descriptionField, $description);
	}

	public function createIfMissing($templateName, $parentId, $logoPosition = '3', $includeAxiaLogo = true, $description = '') {
		$templateId = $this->getTemplateId($templateName, $parentId);
		if (strlen($templateId) == 0) {
			// create it
			$this->webGuy->seeCurrentUrlMatches(static::likeRoute('~^', '$~', '', ''));
			$this->webGuy->click(static::$newButtonLabel);
			//$this->checkForm();
			$this->fillForm($templateName, $logoPosition, $includeAxiaLogo, $description);
			$this->webGuy->click(static::$submitButtonLabel);
			$this->webGuy->seeCurrentUrlMatches(static::likeRoute('~^', '$~', '', ''));
			$templateId = $this->getTemplateId($templateName, $parentId);
		} else {
			Debug::debug('NOOP - tempate already exists.');
		}

		return $templateId;
	}

	public function getTemplateId($templateName, $parentId) {
		$Id =  $this->webGuy->grabFromDatabase('onlineapp_templates', 'id', array('name' => $templateName, 'cobrand_id' => $parentId));
		return $Id;
	}

}