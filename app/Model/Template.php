<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplate Model
 *
 */
class Template extends AppModel {

	public $logoPositionTypes = array('left', 'center', 'right', 'hide');

	public $displayField = 'name';

	public $useTable = 'onlineapp_templates';

	public $actsAs = array(
		'Search.Searchable',
		'Containable',
	);

	public $validate = array(
		'name' => array(
			'rule' => array('notempty'),
			'message' => array('Template name cannot be empty'),
		),
		'cobrand_id' => array(
			'rule' => array('numeric'),
			'message' => array('Invalid cobrand_id value used'),
		),
		'logo_position' => array(
			'rule' => array('notempty'),
			'message' => array('Logo position value not selected'),
		),
	);

	public $hasMany = array(
		'Users' => array(
			'className' => 'User',
			'foreignKey' => 'template_id',
			'dependent' => false,
		),
		'TemplatePages' => array(
			'className' => 'TemplatePage',
			'foreignKey' => 'template_id',
			'order' => 'TemplatePages.order',
			'dependent' => true,
		)
	);

	public $belongsTo = array(
		'Cobrand' => array(
			'className' => 'Cobrand',
			'foreignKey' => 'cobrand_id'
		)
	);

	public function getList() {
		return $this->find('list',
			array('order' => array('Template.name' => 'asc')));
	}

	public function getCobrand($cobrandId) {
		// is this the way to access another model?
		$this->Cobrand->id = $cobrandId;
		$this->Cobrand->recursive = -1;
		$this->Cobrand->find('first');
		return $this->Cobrand->read();
	}

	public function beforeDelete() {
		$templateToDelete = $this->read();
		$pages = $templateToDelete['TemplatePages'];
		if (count($pages) > 0) {
			// delete the children
			$templatePage = ClassRegistry::init('TemplatePage');
			debug('delete the template pages');
			foreach ($pages as $page) {
				$templatePage->delete($page['id']);
			}
		}
	}

	public function afterSave($created, $options) {
		if ($created == true) {
			$page = $this->__getValidateApplicationPageMetaData();
			// add 'Validate Application' page
			$TemplatePage = $TemplatePage = ClassRegistry::init('TemplatePage');
			$TemplatePage->create();
			$TemplatePage->save(
				array(
					'TemplatePage' => array(
						'name' => $page['name'],
						'rep_only' => true,
						'template_id' => $this->data['Template']['id']
					)
				)
			);

			$TemplateSection = ClassRegistry::init('TemplateSection');
			$TemplateField = ClassRegistry::init('TemplateField');

			if (array_key_exists('sections', $page) && count($page['sections']) > 0) {
				$pageId = $TemplatePage->id;
				// add sections
				$sections = $page['sections'];
				$sectionOrder = 0;
				foreach ($sections as $section) {
					$TemplateSection->create();
					$TemplateSection->save(
						array(
							'TemplateSection' => array(
								'name' => $section['name'],
								'width' => (array_key_exists('width', $section) ? $section['width'] : 12),
								'rep_only' => (array_key_exists('rep_only', $section) ? $section['rep_only'] : false),
								'order' => $sectionOrder,
								'page_id' => $pageId
							)
						)
					);
					$sectionOrder = $sectionOrder + 1;
					$sectionId = null;
					if (array_key_exists('fields', $section) && count($section['fields']) > 0) {
						$sectionId = $TemplateSection->id;
						// add fields
						$fields = $section['fields'];
						$fieldOrder = 0;
						foreach ($fields as $field) {
							$merge_field_name;
							switch ($field['type']) {
								case 6:
									$merge_field_name = 'label';
									break;

								case 8:
									$merge_field_name = 'hr';
									break;

								default:
									$merge_field_name = $this->__buildMergeFieldName($page['name'], $section['name'], $field['name']);
									break;
							}

							$TemplateField->create();
							$newField = array(
								'TemplateField' => array(
									'name' => $field['name'],
									'width' => (array_key_exists('width', $field) ? $field['width'] : 12),
									'order' => $fieldOrder,
									'section_id' => $sectionId,
									'type' => $field['type'],
									'required' => (array_key_exists('required', $field) ? $field['required'] : false),
									'source' => (array_key_exists('source', $field) ? $field['source'] : 2), // 2 == n/a
									'default_value' => (array_key_exists('default_value', $field) ? $field['default_value'] : ''),
									'merge_field_name' => $merge_field_name,
									'rep_only' => (array_key_exists('rep_only', $field) ? $field['rep_only'] : false),
								)
							);

							if (!$TemplateField->save($newField)) {
								debug('Failed to save field.');
								debug($newField);
							}
							$fieldOrder = $fieldOrder + 1;
						}
					}
				}
			}
		}
	}

	private function __buildMergeFieldName($pageName, $sectionName, $fieldName) {
		return String::insert(
			":pageName_:sectionName_:fieldName",
			array(
				'pageName' => $this->__getFLOEW($pageName),
				'sectionName' => $this->__getFLOEW($sectionName),
				'fieldName' => $this->__stripString($fieldName, true),
			)
		);
	}

	private function __getFLOEW($words) {
		// get the "first letter of each word" ==>> FLOEW
		// replace & and # with ''
		$cleanWords = $this->__stripString($words);
		$wordsArray = explode(' ', $cleanWords);
		$FLOEW = '';
		foreach ($wordsArray as $word) {
			$FLOEW = $FLOEW . substr($word, 0, 1);
		}
		return $FLOEW;
	}

	private function __stripString($str, $removeSpaces = false) {
		$badChars = array('&', '#', '(', ')', '?');
		if ($removeSpaces) {
			$badChars[count($badChars)] = ' ';
		}
		return str_replace($badChars, '', $str);
	}

	private function __getValidateApplicationPageMetaData() {
		return array(
			'name' => 'Validate Application',
			'rep_only' => true,
			'sections' => array(
				array(
					'name' => 'Rep only',
					'fields' => array(
						array(
							'name' => 'Contractor Name',
							'type' => 0,
							'required' => true,
							'source' => 1,
							'width' => 12,
						)
					)
				),
				array(
					'name' => 'Schedule of Fees Part I',
					'fields' => array(
						array(
							'name' => 'Rate Discount %',
							'type' => 0,
							'required' => true,
							'source' => 1,
							'width' => 4,
						),
						array(
							'name' => 'Rate Structure',
							'type' => 4,
							'required' => true,
							'source' => 1,
							'default_value' => 'Interchange Pass Thru::Interchange Pass Thru,Downgrades At Cost::Downgrades At Cost,Cost Plus::Cost Plus,Bucketed::Bucketed,Bucketed (Rewards)::Bucketed (Rewards),Simply Swipe It Rates::Simply Swipe It Rates',
							'width' => 4,
						),
						array(
							'name' => 'Qualification Exemptions',
							'type' => 4,
							'required' => true,
							'source' => 1,
							'default_value' => 'Visa/MC Interchange at Pass Thru::Visa/MC Interchange at Pass Thru,Non-Qualified Transactions at Additional Visa/MC Cost Based on Regulated Check Cards::Non-Qualified Transactions at Additional Visa/MC Cost Based on Regulated Check Cards,Non-Qualified Transactions at Additional Visa/MC Cost Based on Qualified Consumer Cards::Non-Qualified Transactions at Additional Visa/MC Cost Based on Qualified Consumer Cards,Non-Qualified Transactions at Additional Visa/MC Cost Based on Non-Regulated Qualified Check Cards::Non-Qualified Transactions at Additional Visa/MC Cost Based on Non-Regulated Qualified Check Cards,Visa/MC Cost Plus 0.05%::Visa/MC Cost Plus 0.05%,Visa/MC Cost Plus 0.10%::Visa/MC Cost Plus 0.10%,Visa/MC Cost Plus 0.15%::Visa/MC Cost Plus 0.15%,Visa/MC Cost Plus 0.20%::Visa/MC Cost Plus 0.20%,Visa/MC Cost Plus 0.25%::Visa/MC Cost Plus 0.25%,Visa/MC Cost Plus 0.30%::Visa/MC Cost Plus 0.30%,Visa/MC Cost Plus 0.35%::Visa/MC Cost Plus 0.35%,Visa/MC Cost Plus 0.40%::Visa/MC Cost Plus 0.40%,Visa/MC Cost Plus 0.45%::Visa/MC Cost Plus 0.45%,Visa/MC Cost Plus 0.50%::Visa/MC Cost Plus 0.50%,Visa/MC Cost Plus 0.55%::Visa/MC Cost Plus 0.55%,Visa/MC Cost Plus 0.60%::Visa/MC Cost Plus 0.60%,Visa/MC Cost Plus 0.65%::Visa/MC Cost Plus 0.65%,Visa/MC Cost Plus 0.70%::Visa/MC Cost Plus 0.70%,Visa/MC Cost Plus 0.75%::Visa/MC Cost Plus 0.75%,(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%::(SSI) RATE 2: Keyed: 0.40% Keyed Rewards: 0.75% Mid-Qual: 0.95% Bus: 1.15% Non-Qual: 1.90%,RATE 2:  0.45%            RATE 3:  1.15% + $0.10             BUS 1:  1.05% + $0.10            BUS 2:  1.95% + $0.10::RATE 2:  0.45%            RATE 3:  1.15% + $0.10             BUS 1:  1.05% + $0.10            BUS 2:  1.95% + $0.10,RATE 2:  0.85%            RATE 3:  1.79% + $0.10             BUS 1:  1.15% + $0.10            BUS 2:  1.75% + $0.10::RATE 2:  0.85%            RATE 3:  1.79% + $0.10             BUS 1:  1.15% + $0.10            BUS 2:  1.75% + $0.10,REWARDS:  0.36%            MID:  0.85%             BUS 1:  1.15% + $0.10               NON:  1.79% + $0.10         ::REWARDS:  0.36%            MID:  0.85%             BUS 1:  1.15% + $0.10               NON:  1.79% + $0.10         ',
							'width' => 12,
						),
					),
				),
				array(
					'name' => 'Schedule of Fees Part II',
					'fields' => array(
						array(
							'name' => 'Start Up Fees',
							'type' => 7,
							'required' => false,
							'source' => 1,
							'width' => 3,
							'default_value' => 'Application::25.00,Equipment::0.00,Expedite::0.00,Reprogramming::0.00,Training::0.00,Wireless Activation::0.00',
						),
						array(
							'name' => 'Authorization Fees',
							'type' => 7,
							'required' => false,
							'source' => 1,
							'width' => 3,
							'default_value' => "Visa/MC/JCB/DISC & Batch::0.10,American Express::0.00,ARU & Voice Authorization::0.65,Wireless::0.00",
						),
						array(
							'name' => 'Monthly Fees',
							'type' => 7,
							'required' => false,
							'source' => 1,
							'width' => 3,
							'default_value' => 'Statement::4.75,Monthly Minimum::25.00,Debit Access::0.00,EBT Access::0.00,Gateway Access::0.00,Wireless Access::0.00',
						),
						array(
							'name' => 'Miscellaneous Fees',
							'type' => 7,
							'required' => false,
							'source' => 1,
							'width' => 3,
							'default_value' => 'Annual File Fee::95.00,Chargeback::15.00',
						),
						array(
							'name' => 'PIN Debit Fees',
							'type' => 6,
							'width' => 6
						),
						array(
							'name' => 'PIN Debit Authorization',
							'type' => 6,
							'width' => 6
						),
						array(
							'name' => 'PIN Debit Discount',
							'type' => 0,
							'width' => 6,
							'required' => false,
							'source' => 1,
						),
						array(
							'name' => 'EBT Fees',
							'type' => 0,
							'width' => 6,
							'required' => false,
							'source' => 1,
						),
						array(
							'name' => 'EBT Authorization',
							'type' => 0,
							'width' => 6,
							'required' => false,
							'source' => 1,
						),
						array(
							'name' => 'EBT Discount',
							'type' => 0,
							'width' => 6,
							'required' => false,
							'source' => 1,
						),
						array(
							'name' => 'Discount Paid',
							'type' => 4,
							'width' => 12,
							'source' => 1,
							'default_value' => 'Monthly::0,Daily::1'
						),
						array(
							'name' => 'Amex Discount Rate',
							'type' => 0,
							'width' => 12,
							'source' => 1,
							'default_value' => '2.89',
						),
					),
				),
				array(
					'name' => 'Site Inspection Information',
					'fields' => array(
						array(
							'name' => 'Does business appear legitimate?',
							'type' => 4,
							'required' => true,
							'source' => 1,
							'default_value' => 'Yes::0,No::1',
							'width' => 12,
						),
						array(
							'name' => 'Is site photo included with this application?',
							'type' => 4,
							'required' => true,
							'source' => 1,
							'default_value' => 'Yes::0,No::1',
							'width' => 12,
						),
						array(
							'name' => 'Is inventory sufficient for Business Type?',
							'type' => 4,
							'required' => true,
							'source' => 1,
							'default_value' => 'Yes::0,No::1',
							'width' => 12,
						),
						array(
							'name' => 'Are goods and services delivered at time of sale?',
							'type' => 4,
							'required' => true,
							'source' => 1,
							'default_value' => 'Yes::0,No::1',
							'width' => 12,
						),
						array(
							'name' => 'Is business open and operating?',
							'type' => 4,
							'required' => true,
							'source' => 1,
							'default_value' => 'Yes::0,No::1',
							'width' => 12,
						),
						array(
							'name' => 'Are Visa and MasterCard decals visible?',
							'type' => 4,
							'required' => true,
							'source' => 1,
							'default_value' => 'Yes::0,No::1',
							'width' => 12,
						),
						array(
							'name' => 'Any mail/telephone order sales activity?',
							'type' => 4,
							'required' => true,
							'source' => 1,
							'default_value' => 'Yes::0,No::1',
							'width' => 12,
						),
						array(
							'name' => 'Please type name to confirm if you visted the site',
							'type' => 0,
							'width' => 12,
							'required' => false,
							'source' => 1,
						),
					),
				),
			),
		);
	}
}
