<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/***   API documentation anotations for swagger-php ***/
/**
 *	@OA\Info(
 *		title="Axia Applications REST API",
 *		version="",
 *		@OA\Contact(
 *			email="webmaster@axiamed.com"
 *		),
 *	 	description="Our REST API allows access to the resources detailed herein. Access to this API and these resources must be requested and approved by a system administrator.
 * 		Users are given access to Axia's online application system and also access to the API will be enabled for their account.
 *		Access token is auto-generated when the user account access to the API is enabled.
 *		Users will receive an email with important steps to complete enabling their API access.
 *		Authentication method: Basic base64encode api_usertoken:api_password",
 *	)
 *
 */
class DocumentationsController extends AppController {

    public $permissions = array(
		'apidoc' => array(User::ADMIN, User::REP, User::MANAGER, User::API),
    );


/**
 * apidoc method
 * Display API documentation
 *
 * @return void
 */
    public function apidoc($value='') {
    	$this->layout = false;
    	require(APP . "Vendor/autoload.php");
		//Including paths/files known to have Doctrine annotations to avoid too many uncessesary scans
		$includePaths = [
			APP.'Model/CobrandedApplication.php',
			APP.'Controller',
		];

		$openapi = \OpenApi\scan($includePaths);
		$jsonData = $openapi->toJson();

		$path = WWW_ROOT . 'js/swagger-ui' . DS;
		$fp = @fopen($path . 'openapi_axia.json', 'w');
		if ($fp === false) {
			throw new Exception("Internal Error: Unable to generate JSON definition for swagger --cannot open file openapi_axia.json");
		}
		fwrite($fp, $jsonData);
		fclose($fp);
    }
}
?>