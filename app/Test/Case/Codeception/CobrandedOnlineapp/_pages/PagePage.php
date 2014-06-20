<?php

use Codeception\Util\Debug;

class PagePage
{
	// include url of current page
	static $URL = '/admin/templates/\d*/templatepages';

	/**
	 * Declare UI map for this page here. CSS or XPath allowed.
	 * public static $usernameField = '#username';
	 * public static $formSubmitButton = "#mainForm input[type=submit]";
	 */

	// form fields and labels
	public static $templatePageOrderField = 'TemplatePageOrder';
	public static $templatePageOrderLabel = 'Order';

	public static $templatePageNameField = 'TemplatePageName';
	public static $templatePageNameLabel = 'Name';

	public static $descriptionField = 'TemplatePageDescription';
	public static $descriptionLabel = 'Description';

	// buttons
	public static $newButtonLabel = 'New Template Page';
	public static $editButtonLabel = 'Edit';
	public static $cancelButtonLabel = 'Cancel';
	public static $submitButtonLabel = 'Submit';
	public static $deleteButtonLabel = 'Delete';
	public static $listChildrenButtonLabel = 'List Sections';

	// action titles
	public static $addActionTitle = 'Add Template Page';
	public static $editActionTitle = 'Edit Template Page';

	// action messages
	public static $savedMsg = 'Template Page Saved!';
	public static $deletedMsg = 'Template Page Deleted!';

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
	 * @return Page
	 */
	public static function of(WebGuy2 $I)
	{
		return new static($I);
	}

	// utility functions
	public function checkForm() {
		$this->webGuy->see(static::$templatePageNameLabel);
		$this->webGuy->see(static::$descriptionLabel);
		$this->webGuy->see(static::$submitButtonLabel);
	}

	public function fillForm($pageName, $description) {
		$this->webGuy->fillField(static::$templatePageNameField, $pageName);
		$this->webGuy->fillField(static::$descriptionField, $description);
	}

	public function createIfMissing($pageName, $parentId, $description = '') {
		Debug::debug("Create a page with name [$pageName] for template with id [$parentId]");
		$pageId = $this->getPageId($pageName, $parentId);
		if (strlen($pageId) == 0) {
			// create it
			$this->webGuy->seeCurrentUrlMatches(static::likeRoute('~^', '$~', '', ''));
			$this->webGuy->click(static::$newButtonLabel);
			//$this->checkForm();
			$this->fillForm($pageName, $description);
			$this->webGuy->click(static::$submitButtonLabel);
			$this->webGuy->seeCurrentUrlMatches(static::likeRoute('~^', '$~', '', ''));
			$pageId = $this->getPageId($pageName, $parentId);
		} else {
			Debug::debug('NOOP - page already exists.');
		}

		return $pageId;
	}

	public function getPageId($pageName, $pageId) {
		$Id =  $this->webGuy->grabFromDatabase(
			'onlineapp_template_pages',
			'id',
			array(
				'name' => $pageName,
				'template_id' => $pageId
			)
		);
		return $Id;
	}

}