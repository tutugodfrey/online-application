<?php
class CobrandController
{
	protected $cobrand;

	public function __construct(WebGuy $I) {
		$this->cobrand = $I;
	}

	public function checkCobrandForm() {
		$this->cobrand->see(CobrandPage::$partnerNameLabel);
		$this->cobrand->see(CobrandPage::$partnerNameShortLabel);
		$this->cobrand->see(CobrandPage::$cobrandLogoUrlLabel);
		$this->cobrand->see(CobrandPage::$brandLogoUrlLabel);
		$this->cobrand->see(CobrandPage::$descriptionLabel);
		$this->cobrand->see(CobrandPage::$submitButtonLabel);
	}

	public function fillCobrandForm($partner_name, $partner_name_short, $cobrand_logo_url, $brand_logo_url, $partner_description) {
		$this->cobrand->fillField(CobrandPage::$partnerNameField, $partner_name);
		$this->cobrand->fillField(CobrandPage::$partnerNameShortField, $partner_name_short);
		$this->cobrand->fillField(CobrandPage::$cobrandLogoUrlField, $cobrand_logo_url);
		$this->cobrand->fillField(CobrandPage::$brandLogoUrlField, $brand_logo_url);
		$this->cobrand->fillField(CobrandPage::$descriptionField, $partner_description);
	}
}