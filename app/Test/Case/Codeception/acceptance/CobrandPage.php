<?php
class CobrandPage
{
	public static $url = '/admin/cobrands';
	public static $urlAdd = '/admin/cobrands/add';
	public static $urlEdit = '~^/admin/cobrands/edit/\d*$~';

	// form fields and labels
	public static $partnerNameField = 'CobrandPartnerName';
	public static $partnerNameLabel = 'Partner Name';

	public static $partnerNameShortField = 'CobrandPartnerNameShort';
	public static $partnerNameShortLabel = 'Partner Name Short';

	public static $logoUrlField = 'CobrandLogoUrl';
	public static $logoUrlLabel = 'Logo Url';

	public static $descriptionField = 'CobrandDescription';
	public static $descriptionLabel = 'Description';

	// buttons
	public static $newButtonLabel = 'New Cobrand';
	public static $editButtonLabel = 'Edit';
	public static $cancelButtonLabel = 'Cancel';
	public static $submitButtonLabel = 'Submit';
	public static $deleteButtonLabel = 'Delete';
	public static $listChildrenButtonLabel = 'List Templates';

	// action titles
	public static $addActionTitle = 'Add Cobrand';
	public static $editActionTitle = 'Edit Cobrand';

	// action messages
	public static $savedMsg = 'Cobrand Saved!';
	public static $deletedMsg = 'Cobrand Deleted!';
}