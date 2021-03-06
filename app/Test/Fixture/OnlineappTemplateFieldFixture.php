<?php
class OnlineappTemplateFieldFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'rep_only' => array('type' => 'boolean', 'null' => false, 'default' => false),
		'width' => array('type' => 'integer', 'null' => false, 'default' => '12'),
		'type' => array('type' => 'integer', 'null' => false),
		'required' => array('type' => 'boolean', 'null' => false, 'default' => false),
		'source' => array('type' => 'integer', 'null' => false),
		'default_value' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'merge_field_name' => array('type' => 'string', 'null' => true, 'default' => null),
		'order' => array('type' => 'integer', 'null' => false),
		'section_id' => array('type' => 'integer', 'null' => false),
		'encrypt' => array('type' => 'boolean', 'null' => false, 'default' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'name' => 'field 1',
			'width' => 12,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'type' => 1,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_text_from_user_without_default',
			'order' => 0,
			'section_id' => 1,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field 2',
			'width' => 12,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_text_from_user_without_default',
			'order' => 1,
			'section_id' => 1,
			'rep_only' => false,
			'encrypt' => true,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field 3',
			'width' => 12,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_text_from_user_without_default',
			'order' => 2,
			'section_id' => 1,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field 4',
			'width' => 12,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'type' => 4,
			'required' => 1,
			'source' => 1,
			'default_value' => 'name1::value1,name2::value2,name3::value3',
			'merge_field_name' => 'required_radio_from_user_without_default',
			'order' => 3,
			'section_id' => 1,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type text',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_text_from_user_without_default',
			'order' => 0,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type date',
			'width' => 12,
			'description' => '',
			'type' => 1,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_date_from_user_without_default',
			'order' => 1,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type time',
			'width' => 12,
			'description' => '',
			'type' => 2,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_time_from_user_without_default',
			'order' => 2,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type checkbox',
			'width' => 12,
			'description' => '',
			'type' => 3,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_checkbox_from_user_without_default',
			'order' => 3,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type radio',
			'width' => 12,
			'description' => '',
			'type' => 4,
			'required' => 1,
			'source' => 1,
			'default_value' => 'name1::value1,name2::value2,name3::value3',
			'merge_field_name' => 'required_radio_from_user_without_default',
			'order' => 4,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type percents',
			'width' => 12,
			'description' => '',
			'type' => 5,
			'required' => 1,
			'source' => 1,
			'default_value' => 'name1::value1,name2::value2,name3::value3',
			'merge_field_name' => 'required_percents_from_user_without_default',
			'order' => 5,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type label',
			'width' => 12,
			'description' => '',
			'type' => 6,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'label',
			'order' => 6,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type fees',
			'width' => 12,
			'description' => '',
			'type' => 7,
			'required' => 1,
			'source' => 1,
			'default_value' => 'name1::value1,name2::value2,name3::value3',
			'merge_field_name' => 'required_fees_from_user_without_default',
			'order' => 7,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type hr',
			'width' => 12,
			'description' => '',
			'type' => 8,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'hr',
			'order' => 8,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type phoneUS',
			'width' => 12,
			'description' => '',
			'type' => 9,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_phoneUS_from_user_without_default',
			'order' => 9,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type money',
			'width' => 12,
			'description' => '',
			'type' => 10,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_money_from_user_without_default',
			'order' => 10,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type percent',
			'width' => 12,
			'description' => '',
			'type' => 11,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_percent_from_user_without_default',
			'order' => 11,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type ssn',
			'width' => 12,
			'description' => '',
			'type' => 12,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_ssn_from_user_without_default',
			'order' => 12,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type zip',
			'width' => 12,
			'description' => '',
			'type' => 13,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_zipcodeUS_from_user_without_default',
			'order' => 13,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type email',
			'width' => 12,
			'description' => '',
			'type' => 14,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_email_from_user_without_default',
			'order' => 14,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
/*		array(
			'name' => 'field type lengthoftime',
			'width' => 12,
			'description' => '',
			'type' => 15,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_lengthoftime_from_user_without_default',
			'order' => 15,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type creditcard',
			'width' => 12,
			'description' => '',
			'type' => 16,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_cc_from_user_without_default',
			'order' => 16,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),*/
		array(
			'name' => 'field type url',
			'width' => 12,
			'description' => '',
			'type' => 17,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_url_from_user_without_default',
			'order' => 17,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type number',
			'width' => 12,
			'description' => '',
			'type' => 18,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_number_from_user_without_default',
			'order' => 18,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type digits',
			'width' => 12,
			'description' => '',
			'type' => 19,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_digits_from_user_without_default',
			'order' => 19,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type select',
			'width' => 12,
			'description' => '',
			'type' => 20,
			'required' => 1,
			'source' => 1,
			'default_value' => 'name1::value1,name2::value2,name3::value3',
			'merge_field_name' => 'required_select_from_user_without_default',
			'order' => 20,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'field type textArea',
			'width' => 12,
			'description' => '',
			'type' => 21,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_textArea_from_user_without_default',
			'order' => 21,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Referral1Business',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'Referral1Business',
			'order' => 22,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Referral1Owner/Officer',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'Referral1Owner/Officer',
			'order' => 23,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Referral1Phone',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'Referral1Phone',
			'order' => 24,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Referral2Business',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'Referral2Business',
			'order' => 25,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Referral2Owner/Officer',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'Referral2Owner/Officer',
			'order' => 26,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Referral2Phone',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'Referral2Phone',
			'order' => 27,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Referral3Business',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'Referral3Business',
			'order' => 28,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Referral3Owner/Officer',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'Referral3Owner/Officer',
			'order' => 29,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Referral3Phone',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => 'Referral3',
			'merge_field_name' => 'Referral3Phone',
			'order' => 30,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Owner Type - ',
			'width' => 12,
			'description' => '',
			'type' => 4,
			'required' => 1,
			'source' => 1,
			'default_value' => 'Corporation::Corp,Sole Prop::SoleProp,LLC::LLC,Partnership::Partnership,Non Profit/Tax Exempt (fed form 501C)::NonProfit,Other::Other',
			'merge_field_name' => 'OwnerType-',
			'order' => 31,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Unknown Type for testing',
			'width' => 12,
			'description' => '',
			'type' => 9999,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'Unknown Type for testing',
			'order' => 32,
			'section_id' => 4,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		/*
		Add TemplateFields for 
		*/
		array(
			'name' => 'DBA',
			'width' => 12,
			'description' => 'DBA',
			'type' => 0,
			'required' => 1,
			'source' => 2,
			'default_value' => '',
			'merge_field_name' => 'DBA',
			'order' => 0,
			'section_id' => 5,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'EMail',
			'width' => 12,
			'description' => 'EMail',
			'type' => 14,
			'required' => 1,
			'source' => 2,
			'default_value' => '',
			'merge_field_name' => 'EMail',
			'order' => 0,
			'section_id' => 5,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Text field',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 2,
			'default_value' => '',
			'merge_field_name' => 'required_text_from_api_without_default',
			'order' => 0,
			'section_id' => 5,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Text field',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 2,
			'default_value' => '',
			'merge_field_name' => 'required_text_from_api_without_default_source_2',
			'order' => 1,
			'section_id' => 5,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Text field',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_text_from_user_without_default_repOnly',
			'order' => 2,
			'section_id' => 5,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Text field',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_text_from_user_without_default_textfield',
			'order' => 3,
			'section_id' => 5,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Text field 1',
			'width' => 12,
			'description' => '',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'required_text_from_user_without_default_textfield1',
			'order' => 4,
			'section_id' => 5,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Multirecord field',
			'width' => 12,
			'description' => '',
			'type' => 22,
			'required' => 0,
			'source' => 0,
			'default_value' => 'CobrandedApplicationAch',
			'merge_field_name' => 'multirecord_from_api_with_default',
			'order' => 5,
			'section_id' => 5,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Owner Type - ',
			'width' => 12,
			'description' => '',
			'type' => 4,
			'required' => 0,
			'source' => 0,
			'default_value' => 'Corporation::Corp,Sole Prop::SoleProp,LLC::LLC,Partnership::Partnership,Non Profit/Tax Exempt (fed form 501C)::NonProfit,Other::Other',
			'merge_field_name' => 'OwnerType-',
			'order' => 6,
			'section_id' => 5,
			'rep_only' => false,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
		array(
			'name' => 'Text field 1',
			'width' => 12,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'type' => 0,
			'required' => 1,
			'source' => 1,
			'default_value' => '',
			'merge_field_name' => 'rep_only_true_field_for_testing_rep_only_view_logic',
			'order' => 0,
			'section_id' => 6,
			'rep_only' => true,
			'encrypt' => false,
			'created' => '2013-12-18 14:10:17',
			'modified' => '2013-12-18 14:10:17'
		),
	);
}
