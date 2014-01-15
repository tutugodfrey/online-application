<?php
class PageController
{
	protected $template;

	public function __construct(WebGuy $I) {
		$this->template = $I;
	}

	public function checkForm() {
		$this->template->see(PagePage::$templatePageNameLabel);
		$this->template->see(PagePage::$descriptionLabel);
		$this->template->see(PagePage::$submitButtonLabel);
	}

	public function fillForm($templateName, $description) {
		$this->template->fillField(PagePage::$templatePageNameField, $templateName);
		$this->template->fillField(PagePage::$descriptionField, $description);
	}
}