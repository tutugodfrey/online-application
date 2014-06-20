<?php
class PagePage
{
	// URL patterns
	public static $url =     '~^/admin/templates/\d*/templatepages$~';
	public static $urlAdd =  '~^/admin/templates/\d*/templatepages/add$~';
	public static $urlEdit = '~^/admin/templates/\d*/templatepages/edit/\d*$~';

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
}