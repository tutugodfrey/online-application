<?php
class CobrandPage
{
	public static $URL = '/admin/cobrands';

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
	public static $newCobrandButtonLabel = 'New Cobrand';
	public static $editButtonLabel = 'Edit';
	public static $cancelButtonLabel = 'Cancel';
	public static $submitButtonLabel = 'Submit';
	public static $deleteButtonLabel = 'Delete';

	// action titles
	public static $addCobrand = 'Add Cobrand';
	public static $editCobrand = 'Edit Cobrand';

	// action messages
	public static $cobrandSavedMsg = 'Cobrand Saved!';
	public static $cobrandDeletedMsg = 'Cobrand Deleted!';
}