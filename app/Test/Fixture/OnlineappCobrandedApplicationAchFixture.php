<?php

/**
 * OnlineappCobrandedApplicationAchFixture
 *
 */
class OnlineappCobrandedApplicationAchFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'cobranded_application_id' => array('type' => 'integer', 'null' => false),
		'description' => array('type' => 'string', 'null' => true),
		'auth_type' => array('type' => 'string', 'null' => false),
		'routing_number' => array('type' => 'string', 'null' => false),
		'account_number' => array('type' => 'string', 'null' => false),
		'bank_name' => array('type' => 'string', 'null' => false),
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
			'cobranded_application_id' => 1,
			'description' => 'Lorem ipsum dolor sit amet',
			'auth_type' => 'Ywz6YRLkfNGC3SNpyiFcfZTjCMwBSRSN15RXwQq2lpfsADOPHtp9zoq0k458oBukgLjX+bpUke0ImIZBBF+OUjTFThHeEX3MY8IGlBrDJn4=',
			'routing_number' => 'Ywz6YRLkfNGC3SNpyiFcfc2bhwRPRdb5np5w5NqBFRRrhx4IEG+XVY0Uv1JbMnp157ULnS+tYky8ZEswmcdyBg==',
			'account_number' => 'Ywz6YRLkfNGC3SNpyiFcfdjUgGo4sE8S2OUlk/CTdgtKJtDkmZuCYeWHh43OJqGnKPDlJkyPsGQ5oXhdtlaC8Q==',
			'bank_name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'description' => 'Lorem ipsum dolor sit amet',
			'auth_type' => 'Ywz6YRLkfNGC3SNpyiFcfZTjCMwBSRSN15RXwQq2lpfsADOPHtp9zoq0k458oBukgLjX+bpUke0ImIZBBF+OUjTFThHeEX3MY8IGlBrDJn4=',
			'routing_number' => 'Ywz6YRLkfNGC3SNpyiFcfc2bhwRPRdb5np5w5NqBFRRrhx4IEG+XVY0Uv1JbMnp157ULnS+tYky8ZEswmcdyBg==',
			'account_number' => 'Ywz6YRLkfNGC3SNpyiFcfdjUgGo4sE8S2OUlk/CTdgtKJtDkmZuCYeWHh43OJqGnKPDlJkyPsGQ5oXhdtlaC8Q==',
			'bank_name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'description' => 'Lorem ipsum dolor sit amet',
			'auth_type' => 'Ywz6YRLkfNGC3SNpyiFcfZTjCMwBSRSN15RXwQq2lpfsADOPHtp9zoq0k458oBukgLjX+bpUke0ImIZBBF+OUjTFThHeEX3MY8IGlBrDJn4=',
			'routing_number' => 'Ywz6YRLkfNGC3SNpyiFcfc2bhwRPRdb5np5w5NqBFRRrhx4IEG+XVY0Uv1JbMnp157ULnS+tYky8ZEswmcdyBg==',
			'account_number' => 'Ywz6YRLkfNGC3SNpyiFcfdjUgGo4sE8S2OUlk/CTdgtKJtDkmZuCYeWHh43OJqGnKPDlJkyPsGQ5oXhdtlaC8Q==',
			'bank_name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'description' => 'Lorem ipsum dolor sit amet',
			'auth_type' => 'Ywz6YRLkfNGC3SNpyiFcfZTjCMwBSRSN15RXwQq2lpfsADOPHtp9zoq0k458oBukgLjX+bpUke0ImIZBBF+OUjTFThHeEX3MY8IGlBrDJn4=',
			'routing_number' => 'Ywz6YRLkfNGC3SNpyiFcfc2bhwRPRdb5np5w5NqBFRRrhx4IEG+XVY0Uv1JbMnp157ULnS+tYky8ZEswmcdyBg==',
			'account_number' => 'Ywz6YRLkfNGC3SNpyiFcfdjUgGo4sE8S2OUlk/CTdgtKJtDkmZuCYeWHh43OJqGnKPDlJkyPsGQ5oXhdtlaC8Q==',
			'bank_name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
		array(
			'cobranded_application_id' => 1,
			'description' => 'Lorem ipsum dolor sit amet',
			'auth_type' => 'Ywz6YRLkfNGC3SNpyiFcfZTjCMwBSRSN15RXwQq2lpfsADOPHtp9zoq0k458oBukgLjX+bpUke0ImIZBBF+OUjTFThHeEX3MY8IGlBrDJn4=',
			'routing_number' => 'Ywz6YRLkfNGC3SNpyiFcfc2bhwRPRdb5np5w5NqBFRRrhx4IEG+XVY0Uv1JbMnp157ULnS+tYky8ZEswmcdyBg==',
			'account_number' => 'Ywz6YRLkfNGC3SNpyiFcfdjUgGo4sE8S2OUlk/CTdgtKJtDkmZuCYeWHh43OJqGnKPDlJkyPsGQ5oXhdtlaC8Q==',
			'bank_name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-01-23 17:28:15',
			'modified' => '2014-01-23 17:28:15'
		),
	);
}