<?php

use Codeception\Util\Debug;

class FieldPage
{
	// include url of current page
	static $URL = '/admin/templatesections/\d*/templatefields';
	const STATES = 'AL::Alabama,AK::Alaska,AZ::Arizona,AR::Arkansas,CA::California,CO::Colorado,CT::Connecticut,DE::Delaware,DC::District Of Columbia,FL::Florida,GA::Georgia,HI::Hawaii,ID::Idaho,IL::Illinois,IN::Indiana,IA::Iowa,KS::Kansas,KY::Kentucky,LA::Louisiana,ME::Maine,MD::Maryland,MA::Massachusetts,MI::Michigan,MN::Minnesota,MS::Mississippi,MO::Missouri,MT::Montana,NE::Nebraska,NV::Nevada,NH::New Hampshire,NJ::New Jersey,NM::New Mexico,NY::New York,NC::North Carolina,ND::North Dakota,OH::Ohio,OK::Oklahoma,OR::Oregon,PA::Pennsylvania,RI::Rhode Island,SC::South Carolina,SD::South Dakota,TN::Tennessee,TX::Texas,UT::Utah,VT::Vermont,VA::Virginia,WA::Washington,WV::West Virginia,WI::Wisconsin,WY::Wyoming';

	/**
	 * Declare UI map for this page here. CSS or XPath allowed.
	 * public static $usernameField = '#username';
	 * public static $formSubmitButton = "#mainForm input[type=submit]";
	 */

	// form fields and labels
	public static $templateFieldOrderField = 'TemplateFieldOrder';
	public static $templateFieldOrderLabel = 'Order';

	public static $templateFieldNameField = 'TemplateFieldName';
	public static $templateFieldNameLabel = 'Name';

	public static $templateFieldWidthField = 'TemplateFieldWidth';
	public static $templateFieldWidthLabel = 'Width';

	public static $templateFieldTypeField = 'TemplateFieldType';
	public static $templateFieldTypeLabel = 'Type';

	public static $templateFieldRequiredField = 'TemplateFieldRequired';
	public static $templateFieldRequiredLabel = 'Required';

	public static $templateFieldRepOnlyField = 'TemplateFieldRepOnly';
	public static $templateFieldRepOnlyLabel = 'Rep Only';

	public static $templateFieldSourceField = 'TemplateFieldSource';
	public static $templateFieldSourceLabel = 'Source';

	public static $templateFieldDefaultValueField = 'TemplateFieldDefaultValue';
	public static $templateFieldDefaultValueLabel = 'Default Value';

	public static $templateFieldMergeFieldNameField = 'TemplateFieldMergeFieldName';
	public static $templateFieldMergeFieldNameLabel = 'Merge Field Name';

	public static $descriptionField = 'TemplateFieldDescription';
	public static $descriptionLabel = 'Description';

	public static $templateFieldEncryptField = 'TemplateFieldEncrypt';
	public static $templateFieldEncryptLabel = 'Encrypt';

	// buttons
	public static $newButtonLabel = 'New Template Field';
	public static $editButtonLabel = 'Edit';
	public static $cancelButtonLabel = 'Cancel';
	public static $submitButtonLabel = 'Submit';
	public static $deleteButtonLabel = 'Delete';
	public static $listChildrenButtonLabel = 'List Fields';

	// action titles
	public static $addActionTitle = 'Add Template Field';
	public static $editActionTitle = 'Edit Template Field';

	// action messages
	public static $savedMsg = 'Template Field Saved!';
	public static $deletedMsg = 'Template Field Deleted!';

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
	 * @return FieldPage
	 */
	public static function of(WebGuy2 $I)
	{
		return new static($I);
	}

	// utility functions
	public function checkForm() {
		$this->webGuy->see(static::$templateFieldOrderLabel);
		$this->webGuy->see(static::$templateFieldNameLabel);
		$this->webGuy->see(static::$templateFieldWidthLabel);
		$this->webGuy->see(static::$templateFieldTypeLabel);
		$this->webGuy->see(static::$templateFieldRequiredLabel);
		$this->webGuy->see(static::$templateFieldRepOnlyLabel);
		$this->webGuy->see(static::$templateFieldSourceLabel);
		$this->webGuy->see(static::$templateFieldDefaultValueLabel);
		$this->webGuy->see(static::$templateFieldMergeFieldNameLabel);
		$this->webGuy->see(static::$descriptionLabel);
		$this->webGuy->see(static::$submitButtonLabel);
		$this->webGuy->see(static::$templateFieldEncryptLabel);
	}

	public function fillForm($fieldName, $fieldWidth, $fieldType, $fieldRequired, $fieldSource, $fieldDefaultValue, $fieldMergeFieldName, $fieldDescription, $fieldOrder = null, $fieldRepOnly = false, $fieldEncrypt = false) {
		if (!is_null($fieldOrder)) {
			$this->webGuy->fillField(static::$templateFieldOrderField, $fieldOrder);
		}
		$this->webGuy->fillField(static::$templateFieldNameField, $fieldName);
		$this->webGuy->fillField(static::$templateFieldWidthField, $fieldWidth);
		$this->webGuy->selectOption(static::$templateFieldTypeField, $fieldType);
		if ($fieldRequired == true) {
			$this->webGuy->checkOption(static::$templateFieldRequiredField, $fieldRequired);
		} else {
			$this->webGuy->uncheckOption(static::$templateFieldRequiredField, $fieldRequired);
		}
		if ($fieldRepOnly == true) {
			$this->webGuy->checkOption(static::$templateFieldRepOnlyField, $fieldRepOnly);
		} else {
			$this->webGuy->uncheckOption(static::$templateFieldRepOnlyField, $fieldRepOnly);
		}
		if ($fieldEncrypt == true) {
			$this->webGuy->checkOption(static::$templateFieldEncryptField, $fieldEncrypt);
		} else {
			$this->webGuy->uncheckOption(static::$templateFieldEncryptField, $fieldEncrypt);
		}
		$this->webGuy->selectOption(static::$templateFieldSourceField, $fieldSource);
		$this->webGuy->fillField(static::$templateFieldDefaultValueField, $fieldDefaultValue);
		$this->webGuy->fillField(static::$templateFieldMergeFieldNameField , $fieldMergeFieldName);
		$this->webGuy->fillField(static::$descriptionField, $fieldDescription);
	}

	public function createIfMissing($fieldName, $parentId, $fieldWidth, $fieldType, $fieldRequired, $fieldSource, $fieldDefaultValue, $fieldMergeFieldName, $fieldDescription, $fieldOrder, $fieldRepOnly = false, $fieldEncrypt = false) {
		$fieldId = $this->getSectionId($fieldName, $parentId);
		if (strlen($fieldId) == 0) {
			// create it
			$this->webGuy->seeCurrentUrlMatches(static::likeRoute('~^', '$~', '', ''));
			$this->webGuy->click(static::$newButtonLabel);
			//$this->checkForm();
			$this->fillForm($fieldName, $fieldWidth, $fieldType, $fieldRequired, $fieldSource, $fieldDefaultValue, $fieldMergeFieldName, $fieldDescription, $fieldOrder, $fieldRepOnly, $fieldEncrypt);
			$this->webGuy->click(static::$submitButtonLabel);
			$this->webGuy->seeCurrentUrlMatches(static::likeRoute('~^', '$~', '', ''));
			$fieldId = $this->getSectionId($fieldName, $parentId);
		} else {
			Debug::debug('NOOP - field already exists.');
		}

		return $fieldId;
	}

	public function getSectionId($fieldName, $sectionId) {
		$Id =  $this->webGuy->grabFromDatabase(
			'onlineapp_template_fields',
			'id',
			array(
				'name' => $fieldName,
				'section_id' => $sectionId
			)
		);
		return $Id;
	}
}
