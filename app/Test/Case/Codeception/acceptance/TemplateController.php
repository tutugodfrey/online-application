<?php
class TemplateController
{
	protected $template;

	public function __construct(WebGuy $I) {
		$this->template = $I;
	}

	public function checkForm() {
		$this->template->see(TemplatePage::$templateNameLabel);
		$this->template->see(TemplatePage::$templateLogoPositionLabel);
		$this->template->see(TemplatePage::$templateIncludeAxiaLogoLabel);
		$this->template->see(TemplatePage::$descriptionLabel);
		$this->template->see(TemplatePage::$templateRightsignatureTemplateGuidLabel);
		$this->template->see(TemplatePage::$templateRightsignatureInstallTemplateGuidLabel);
		$this->template->see(TemplatePage::$templateOwnerEquityThresholdLabel);
		$this->template->see(TemplatePage::$submitButtonLabel);
	}

	public function fillForm($templateName, $templateLogoPosition, $templateIncludeAxiaLogo, $description,
		$templateRightsignatureTemplateGuid, $templateRightsignatureInstallTemplateGuid, $templateOwnerEquityThreshold) {
		$this->template->fillField(TemplatePage::$templateNameField, $templateName);
		$this->template->selectOption(TemplatePage::$templateLogoPositionField, $templateLogoPosition);
		if ($templateIncludeAxiaLogo == true) {
			$this->template->checkOption(TemplatePage::$templateIncludeAxiaLogoField);
		} else {
			$this->template->uncheckOption(TemplatePage::$templateIncludeAxiaLogoField);
		}
		$this->template->fillField(TemplatePage::$descriptionField, $description);
		$this->template->fillField(TemplatePage::$templateRightsignatureTemplateGuidField, $templateRightsignatureTemplateGuid);
		$this->template->fillField(TemplatePage::$templateRightsignatureInstallTemplateGuidField, $templateRightsignatureInstallTemplateGuid);
		$this->template->fillField(TemplatePage::$templateOwnerEquityThresholdField, $templateOwnerEquityThreshold);
	}
}
