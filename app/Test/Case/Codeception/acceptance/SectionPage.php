<?php
class SectionPage
{
	// URL patterns
	public static $url =     '~^/admin/templatepages/\d*/templatesections$~';
	public static $urlAdd =  '~^/admin/templatepages/\d*/templatesections/add/$~';
	public static $urlEdit = '~^/admin/templatepages/\d*/templatesections/edit/\d*$~';

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
}