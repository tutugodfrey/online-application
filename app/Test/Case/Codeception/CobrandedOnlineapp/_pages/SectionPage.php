<?php

use Codeception\Util\Debug;

class SectionPage
{
	// include url of current page
	static $URL = '/admin/templatepages/\d*/templatesections';

	/**
	 * Declare UI map for this page here. CSS or XPath allowed.
	 * public static $usernameField = '#username';
	 * public static $formSubmitButton = "#mainForm input[type=submit]";
	 */

	// form fields and labels
	public static $templateSectionOrderField = 'TemplateSectionOrder';
	public static $templateSectionOrderLabel = 'Order';

	public static $templateSectionNameField = 'TemplateSectionName';
	public static $templateSectionNameLabel = 'Name';

	public static $templateSectionWidthField = 'TemplateSectionWidth';
	public static $templateSectionWidthLabel = 'Width';

	public static $descriptionField = 'TemplateSectionDescription';
	public static $descriptionLabel = 'Description';

	// buttons
	public static $newButtonLabel = 'New Template Section';
	public static $editButtonLabel = 'Edit';
	public static $cancelButtonLabel = 'Cancel';
	public static $submitButtonLabel = 'Submit';
	public static $deleteButtonLabel = 'Delete';
	public static $listChildrenButtonLabel = 'List Fields';

	// action titles
	public static $addActionTitle = 'Add Template Section';
	public static $editActionTitle = 'Edit Template Section';

	// action messages
	public static $savedMsg = 'Template Section Saved!';
	public static $deletedMsg = 'Template Section Deleted!';

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

	/**
	 * @var WebGuy;
	 */
	protected $webGuy;

	public function __construct(WebGuy2 $I)
	{
		$this->webGuy = $I;
	}

	/**
	 * @return SectionPage
	 */
	public static function of(WebGuy2 $I)
	{
		return new static($I);
	}

	// utility funcitons
	public function checkForm() {
		$this->webGuy->see(static::$templateSectionNameLabel);
		$this->webGuy->see(static::$templateSectionWidthLabel);
		$this->webGuy->see(static::$descriptionLabel);
		$this->webGuy->see(static::$submitButtonLabel);
	}

	public function fillForm($sectionName, $width, $description) {
		$this->webGuy->fillField(static::$templateSectionNameField, $sectionName);
		$this->webGuy->fillField(static::$templateSectionWidthField, $width);
		$this->webGuy->fillField(static::$descriptionField, $description);
	}

	public function createIfMissing($sectionName, $parentId, $width = 12, $description = '') {
		Debug::debug("Create a section with name [$sectionName] for page with id [$parentId]");
		$sectionId = $this->getSectionId($sectionName, $parentId);
		if (strlen($sectionId) == 0) {
			// create it
			$this->webGuy->seeCurrentUrlMatches(static::likeRoute('~^', '$~', '', ''));
			$this->webGuy->click(static::$newButtonLabel);
			//$this->checkForm();
			$this->fillForm($sectionName, $width, $description);
			$this->webGuy->click(static::$submitButtonLabel);
			$this->webGuy->seeCurrentUrlMatches(static::likeRoute('~^', '$~', '', ''));
			$sectionId = $this->getSectionId($sectionName, $parentId);
		} else {
			Debug::debug('NOOP - section already exists.');
		}

		return $sectionId;
	}

	public function getSectionId($sectionName, $pageId) {
		$Id =  $this->webGuy->grabFromDatabase(
			'onlineapp_template_sections',
			'id',
			array(
				'name' => str_replace("&", "&amp;", $sectionName),
				'page_id' => $pageId
			)
		);
		return $Id;
	}

}