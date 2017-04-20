<?php
App::uses('Cobrand', 'Model');
App::uses('Template', 'Model');
App::uses('TemplatePage', 'Model');

/**
 * TemplatePage Test Case
 *
 */
class TemplatePageTest extends CakeTestCase {

	public $autoFixtures = false;

	public $fixtures = array(
		'app.onlineappCobrand',
		'app.onlineappUser',
		'app.onlineappUsersTemplate',
		'app.onlineappTemplate',
		'app.onlineappTemplatePage',
		'app.onlineappTemplateSection',
		'app.onlineappTemplateField',
		'app.onlineappCobrandedApplication',
		'app.onlineappCobrandedApplicationValue',
	);

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
	}

	public static function tearDownAfterClass() {
		parent::tearDownAfterClass();
	}

	public function setUp() {
		parent::setUp();
		$this->Cobrand = ClassRegistry::init('Cobrand');
		$this->User = ClassRegistry::init('User');
		$this->UsersTemplate = ClassRegistry::init('UsersTemplate');
		$this->Template = ClassRegistry::init('Template');
		$this->TemplatePage = ClassRegistry::init('TemplatePage');
		$this->TemplateSection = ClassRegistry::init('TemplateSection');
		$this->TemplateField = ClassRegistry::init('TemplateField');
		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
		$this->CobrandedApplicationValue = ClassRegistry::init('CobrandedApplicationValue');

		// load data
		$this->loadFixtures('OnlineappCobrand');
		$this->loadFixtures('OnlineappUser');
		$this->loadFixtures('OnlineappUsersTemplate');
		$this->loadFixtures('OnlineappTemplate');
		$this->loadFixtures('OnlineappTemplatePage');
		$this->loadFixtures('OnlineappTemplateSection');
		$this->loadFixtures('OnlineappTemplateField');
		$this->loadFixtures('OnlineappCobrandedApplication');
		$this->loadFixtures('OnlineappCobrandedApplicationValue');
	}

	public function tearDown() {
		$this->CobrandedApplicationValue->deleteAll(true, false);
		$this->CobrandedApplication->deleteAll(true, false);
		$this->TemplateField->deleteAll(true, false);
		$this->TemplateSection->deleteAll(true, false);
		$this->TemplatePage->deleteAll(true, false);
		$this->Template->deleteAll(true, false);
		$this->Cobrand->deleteAll(true, false);

		unset($this->CobrandedApplicationValue);
		unset($this->CobrandedApplication);
		unset($this->TemplateField);
		unset($this->TemplateSection);
		unset($this->TemplatePage);
		unset($this->Template);
		unset($this->Cobrand);

		parent::tearDown();
	}

	public function testGetCobrand() {
		$templateId = 1;
		$expectedCobrand = array(
			'id' => 1,
			'partner_name' => 'Partner Name 1',
			'partner_name_short' => 'PN1',
			'description' => 'Cobrand "Partner Name 1" description goes here.',
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'cobrand_logo_url' => 'PN1 logo_url',
			'response_url_type' => null,
			'brand_logo_url' => 'PN1 logo_url',
		);
		$returnedCobrand = $this->TemplatePage->getCobrand($templateId);
		$this->assertEquals($expectedCobrand, $returnedCobrand);

		$templateId = 3;
		$expectedCobrand = array(
			'id' => 2,
			'partner_name' => 'Partner Name 2',
			'partner_name_short' => 'PN2',
			'description' => 'Cobrand "Partner Name 2" description goes here.',
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'cobrand_logo_url' => 'PN2 logo_url',
			'response_url_type' => null,
			'brand_logo_url' => 'PN2 logo_url',
		);
		$returnedCobrand = $this->TemplatePage->getCobrand($templateId);
		$this->assertEquals($expectedCobrand, $returnedCobrand);

		//Test method by setting data to the model
		$this->TemplatePage->set(array('TemplatePage' => array('template_id' => $templateId)));
		$returnedCobrand = $this->TemplatePage->getCobrand($templateId);
		$this->assertEquals($expectedCobrand, $returnedCobrand);
	}

	public function testGetTemplate() {
		$templateId = 1;
		$expectedtemplate = array(
			'id' => 1,
			'name' => 'Template 1 for PN1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'cobrand_id' => 1,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'logo_position' => 0,
			'include_brand_logo' => true,
			'rightsignature_template_guid' => null,
			'rightsignature_install_template_guid' => null,
			'owner_equity_threshold' => 50,
			'requires_coversheet' => false
		);
		$returnedtemplate = $this->TemplatePage->getTemplate($templateId);
		$this->assertEquals($expectedtemplate, $returnedtemplate);

		$templateId = 2;
		$expectedtemplate = array(
			'id' => 2,
			'name' => 'Template 2 for PN1',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'cobrand_id' => 1,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'logo_position' => 0,
			'include_brand_logo' => true,
			'rightsignature_template_guid' => null,
			'rightsignature_install_template_guid' => null,
			'owner_equity_threshold' => 50,
			'requires_coversheet' => null
		);
		$returnedtemplate = $this->TemplatePage->getTemplate($templateId);
		$this->assertEquals($expectedtemplate, $returnedtemplate);

		$templateId = 3;
		$expectedtemplate = array(
			'id' => 3,
			'name' => 'Template 1 for PN2',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'cobrand_id' => 2,
			'created' => '2007-03-18 10:41:31',
			'modified' => '2007-03-18 10:41:31',
			'logo_position' => 0,
			'include_brand_logo' => true,
			'rightsignature_template_guid' => null,
			'rightsignature_install_template_guid' => null,
			'owner_equity_threshold' => 50,
			'requires_coversheet' => null
		);
		$returnedtemplate = $this->TemplatePage->getTemplate($templateId);
		$this->assertEquals($expectedtemplate, $returnedtemplate);
	}

	public function testValidation() {
		$expectedValidationErrors = array(
			'name' => array('Template page name cannot be empty'),
			'template_id' => array('Invalid cobrand_id value used'),
			'order' => array('Invalid order value used'),
		);

		$this->TemplatePage->create(array('name' => '', 'template_id' => '', 'order' => ''));
		$this->assertFalse($this->TemplatePage->validates());
		$this->assertEquals($expectedValidationErrors, $this->TemplatePage->validationErrors);

		// go right path
		$expectedValidationErrors = array();

		$this->TemplatePage->create(array('name' => 'name', 'template_id' => 1, 'order' => 0));
		$this->assertTrue($this->TemplatePage->validates());
		$this->assertEquals($expectedValidationErrors, $this->TemplatePage->validationErrors);
	}

	public function testSaveNew() {
		// save new will get the existing count of Template pages and use that for the order property
		// initially there should be no templatePages for any template
		// create a new template page for template_id = 1
		$templateData = array(
			'Template' => array(
				'name' => 'testSaveNew_templateName',
				'logo_position' => 0,
				'include_brand_logo' => false,
				'cobrand_id' => 1
			)
		);
		$this->Template->create();
		$this->Template->save($templateData);
		$template = $this->Template->find('first', array('conditions' => array('Template.id' => $this->Template->id)));

		// add another page
		$pageData = array(
			'TemplatePage' => array(
				'name' => 'testSaveNew_order',
				'description' => '',
				'template_id' => $template['Template']['id'],
				'rep_only' => false
			)
		);
		$this->TemplatePage->create();
		if ($this->TemplatePage->save($pageData, array('validate' => false))) {
			// should have 2 pages now but the new page should be first; order == 0.
			$expectedOrderValue = 0;
			// re-read the template object add associations
			$template = $this->Template->find('first', array('conditions' => array('Template.id' => $this->Template->id)));
			$templatePages = $template['TemplatePages'];
			$this->assertEquals($expectedOrderValue, $templatePages[0]['order'], 'New pages get inserted before the "Validate Application" page.');
		}
	}

	public function testReorderingLastToFirst() {
		// make sure the order values are what we expect
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(0, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(1, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(2, $thirdPage['TemplatePage']['order']);

		// move the third field to the front of the list
		$thirdPage['TemplatePage']['order'] = 0;
		$this->TemplatePage->save($thirdPage);

		// check the order values
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(0, $thirdPage['TemplatePage']['order']);
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(1, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(2, $secondPage['TemplatePage']['order']);
	}

	public function testReorderingFirstToLast() {
		// make sure the order values are what we expect
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(0, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(1, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(2, $thirdPage['TemplatePage']['order']);

		// move the third back to the end now
		$firstPage = $this->TemplatePage->findById(1);
		$firstPage['TemplatePage']['order'] = 2;
		$this->TemplatePage->save($firstPage);

		// make sure the order values are what we expect
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(2, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(0, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(1, $thirdPage['TemplatePage']['order']);
	}

	public function testReorderingMiddleToFirst() {
		// make sure the order values are what we expect
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(0, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(1, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(2, $thirdPage['TemplatePage']['order']);

		// move the third back to the end now
		$secondPage = $this->TemplatePage->findById(2);
		$secondPage['TemplatePage']['order'] = 0;
		$this->TemplatePage->save($secondPage);

		// make sure the order values are what we expect
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(1, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(0, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(2, $thirdPage['TemplatePage']['order']);
	}

	public function testReorderingMiddleToLast() {
		// make sure the order values are what we expect
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(0, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(1, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(2, $thirdPage['TemplatePage']['order']);

		// move the third back to the end now
		$secondPage = $this->TemplatePage->findById(2);
		$secondPage['TemplatePage']['order'] = 2;
		$this->TemplatePage->save($secondPage);

		// make sure the order values are what we expect
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(0, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(2, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(1, $thirdPage['TemplatePage']['order']);
	}

	public function testDeleteFirstPage() {
		// make sure the order values are what we expect
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(0, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(1, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(2, $thirdPage['TemplatePage']['order']);

		// delete the first one
		$this->TemplatePage->delete(1);
		// make sure 1 is gone
		$this->assertEquals(array(), $this->TemplatePage->findById(1), 'Page with id == [1] has not been deleted.');

		// re-check the order
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(0, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(1, $thirdPage['TemplatePage']['order']);
		// make sure 1 is gone
	}

	public function testDeleteMiddlePage() {
		// make sure the order values are what we expect
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(0, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(1, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(2, $thirdPage['TemplatePage']['order']);

		// delete one in the middle; id = 2
		$this->TemplatePage->delete(2);
		// make sure 2 is gone
		$this->assertEquals(array(), $this->TemplatePage->findById(2), 'Page with id == [2] has not been deleted.');

		// recheck the order
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(0, $firstPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(1, $thirdPage['TemplatePage']['order']);
	}

	public function testDeleteLastPage() {
		// make sure the order values are what we expect
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(0, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(1, $secondPage['TemplatePage']['order']);
		$thirdPage = $this->TemplatePage->findById(3);
		$this->assertEquals(2, $thirdPage['TemplatePage']['order']);

		// delete the last page
		$this->TemplatePage->delete(3);
		// make sure 3 is gone
		$this->assertEquals(array(), $this->TemplatePage->findById(3), 'Page with id == [3] has not been deleted.');

		// recheck the order
		$firstPage = $this->TemplatePage->findById(1);
		$this->assertEquals(0, $firstPage['TemplatePage']['order']);
		$secondPage = $this->TemplatePage->findById(2);
		$this->assertEquals(1, $secondPage['TemplatePage']['order']);
	}

	public function testNameEditable() {
		$this->assertFalse($this->TemplatePage->nameEditable('Validate Application'));
		$this->assertTrue($this->TemplatePage->nameEditable('anything else'));
	}

	public function testOrderEditable() {
		$this->assertFalse($this->TemplatePage->orderEditable('Validate Application'));
	}
/**
 * testAfterSave
 *
 * @covers TemplatePage:afterSave()
 * @return void
 */
	public function testAfterSave() {
		//Set data to the model
		$templateId = 1;
		$this->TemplatePage->set(array('TemplatePage' => array('template_id' => $templateId)));
		$this->TemplatePage->afterSave(false);
		$result = $this->Template->find('first', array('conditions' => array('Template.id' => $templateId)));

		//check that the template was saved in the proper order with Validate Application page at the end
		$nameOfLastTemplate = Hash::get($result, 'TemplatePages.' . (count($result['TemplatePages']) - 1) . '.name');
		$this->assertSame($nameOfLastTemplate, 'Validate Application');
	}
}
