<?php
class FieldPage
{
	// URL patterns
	public static $url =     '~^/admin/templatesections/\d*/templatefields$~';
	public static $urlAdd =  '~^/admin/templatesections/\d*/templatefields/add/$~';
	public static $urlEdit = '~^/admin/templatesections/\d*/templatefields/edit/\d*$~';

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

	public static $templateFieldSourceField = 'TemplateFieldSource';
	public static $templateFieldSourceLabel = 'Source';

	public static $templateFieldDefaultValueField = 'TemplateFieldDefaultValue';
	public static $templateFieldDefaultValueLabel = 'Default Value';

	public static $templateFieldMergeFieldNameField = 'TemplateFieldMergeFieldName';
	public static $templateFieldMergeFieldNameLabel = 'Merge Field Name';

	public static $descriptionField = 'TemplateFieldDescription';
	public static $descriptionLabel = 'Description';

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
}