<?php
class Epayment extends AppModel {
/**
 * API KEY
 */
	const API_KEY = '72063deb919178dd02646d543bb58da3';
/**
 * API PIN
 */
	const API_PIN = 'f4c31e4ed207c0ce';

/**
 * Api start date
 */
	const API_START_DATE = '2012-05-07';

/**
 * Api request url 
 */
	const API_REQ_URL = 'https://secure.axiaepay.com/interface/vendorapi/sourcekeys?output=xml&apikey=';

	public $useTable = 'onlineapp_epayments';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}