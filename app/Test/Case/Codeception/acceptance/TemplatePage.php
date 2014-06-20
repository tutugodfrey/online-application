<?php
class TemplatePage
{
	// URL patterns
	public static $url =     '~^/admin/cobrands/\d*/templates$~';
	public static $urlAdd =  '~^/admin/cobrands/\d*/templates/add$~';
	public static $urlEdit = '~^/admin/cobrands/\d*/templates/edit/\d*$~';

	// form fields and labels
	public static $templateNameField = 'TemplateName';
	public static $templateNameLabel = 'Name';

	public static $templateLogoPositionField = 'TemplateLogoPosition';
	public static $templateLogoPositionLabel = 'Logo Position';

	public static $templateIncludeAxiaLogoField = 'TemplateIncludeAxiaLogo';
	public static $templateIncludeAxiaLogoLabel = 'Include Axia Logo';

	public static $descriptionField = 'TemplateDescription';
	public static $descriptionLabel = 'Description';

	// buttons
	public static $newButtonLabel = 'New Template';
	public static $editButtonLabel = 'Edit';
	public static $cancelButtonLabel = 'Cancel';
	public static $submitButtonLabel = 'Submit';
	public static $deleteButtonLabel = 'Delete';
	public static $listChildrenButtonLabel = 'List Pages';

	// action titles
	public static $addActionTitle = 'Add Template';
	public static $editActionTitle = 'Edit Template';

	// action messages
	public static $savedMsg = 'Template Saved!';
	public static $deletedMsg = 'Template Deleted!';
}