<?php
class FieldController
{
	protected $field;

	public function __construct(WebGuy $I) {
		$this->field = $I;
	}

	public function checkForm() {
		$this->field->see(FieldPage::$templateFieldOrderLabel);
		$this->field->see(FieldPage::$templateFieldNameLabel);
		$this->field->see(FieldPage::$templateFieldWidthLabel);
		$this->field->see(FieldPage::$templateFieldTypeLabel);
		$this->field->see(FieldPage::$templateFieldRequiredLabel);
		$this->field->see(FieldPage::$templateFieldSourceLabel);
		$this->field->see(FieldPage::$templateFieldDefaultValueLabel);
		$this->field->see(FieldPage::$templateFieldMergeFieldNameLabel);
		$this->field->see(FieldPage::$descriptionLabel);
		$this->field->see(PagePage::$submitButtonLabel);
	}

	public function fillForm($fieldName, $fieldWidth, $fieldType, $fieldRequired, $fieldSource, $fieldDefaultValue, $fieldMergeFieldName, $fieldDescription, $fieldOrder = null) {

		if (!is_null($fieldOrder)) {
			$this->field->fillField(FieldPage::$templateFieldOrderField, $fieldOrder);
		}
		$this->field->fillField(FieldPage::$templateFieldNameField, $fieldName);
		$this->field->fillField(FieldPage::$templateFieldWidthField, $fieldWidth);
		$this->field->selectOption(FieldPage::$templateFieldTypeField, $fieldType);
		if ($fieldRequired == true) {
			$this->field->checkOption(FieldPage::$templateFieldRequiredField, $fieldRequired);
		} else {
			$this->field->uncheckOption(FieldPage::$templateFieldRequiredField, $fieldRequired);
		}
		$this->field->selectOption(FieldPage::$templateFieldSourceField, $fieldSource);
		$this->field->fillField(FieldPage::$templateFieldDefaultValueField, $fieldDefaultValue);
		$this->field->fillField(FieldPage::$templateFieldMergeFieldNameField , $fieldMergeFieldName);
		$this->field->fillField(FieldPage::$descriptionField, $fieldDescription);
	}
}
