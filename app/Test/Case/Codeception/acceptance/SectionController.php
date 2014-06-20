<?php
class SectionController
{
	protected $section;

	public function __construct(WebGuy $I) {
		$this->section = $I;
	}

	public function checkForm() {
		$this->section->see(SectionPage::$templateSectionNameLabel);
		$this->section->see(SectionPage::$templateSectionWidthLabel);
		$this->section->see(SectionPage::$descriptionLabel);
		$this->section->see(PagePage::$submitButtonLabel);
	}

	public function fillForm($sectionName, $width, $description) {
		$this->section->fillField(SectionPage::$templateSectionNameField, $sectionName);
		$this->section->fillField(SectionPage::$templateSectionWidthField, $width);
		$this->section->fillField(SectionPage::$descriptionField, $description);
	}
}
