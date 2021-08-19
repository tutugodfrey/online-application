<?php
App::uses('AppModel', 'Model');
class ReencryptAllWithOpensslEncryption extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'reencrypt_all_with_openssl_encryption';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
        if ($direction === 'up') {
            $CobrandedAppValue = $this->generateModel('CobrandedApplicationValue');
            $ApiConfiguration = $this->generateModel('ApiConfiguration');

            $data = $CobrandedAppValue->find('all', array(
                'callbacks' => false,
                'recursive' => -1,
                'fields' => array('CobrandedApplicationValue.*'),
                'conditions' => array(
                    //mcrypt encrypted data is exactly 32 characters long
                    'length("CobrandedApplicationValue"."value") = 32'
                ),
                'joins' => array(
                    array(
                        'table' => 'onlineapp_template_fields',
                        'alias' => 'TemplateField',
                        'type' => 'inner',
                        'conditions' => array(
                            '"CobrandedApplicationValue"."template_field_id" = "TemplateField"."id"',
                            '"TemplateField"."encrypt" = true'
                        )
                    )
                )
            ));

            //if this migration is ran more than once, the query above will return no data since no mcrypt
            //encrypted data 32 characters long will exist.
            if (!empty($data)) {
                foreach($data as $idx => &$value) {
                    $value['CobrandedApplicationValue']['value'] = $this->__mcryptToOpenSSLEnc($value['CobrandedApplicationValue']['value']);
                }
                $CobrandedAppValue->saveMany($data, array('validate' => false, 'callbacks' => false));
            }
            $data = null;
            $ApiConfiguration->query('alter table onlineapp_api_configurations alter column client_secret type character varying(500)');
            $ApiConfiguration->query('alter table onlineapp_api_configurations alter column access_token type character varying(500)');
            $ApiConfiguration->query('alter table onlineapp_api_configurations alter column refresh_token type character varying(500)');
            
            $data = $ApiConfiguration->find('all', array(
                'callbacks' => false,
                'recursive' => -1,
            ));
            //check first whether data is already openssl encrypted
            foreach ($data as $idx => &$value) {
                if (!empty($value['ApiConfiguration']['client_secret']) && !$ApiConfiguration->isEncrypted($value['ApiConfiguration']['client_secret'])) {
                    $value['ApiConfiguration']['client_secret'] = $this->__mcryptToOpenSSLEnc($value['ApiConfiguration']['client_secret']);
                }
                if (!empty($value['ApiConfiguration']['access_token']) && !$ApiConfiguration->isEncrypted($value['ApiConfiguration']['access_token'])) {
                    $value['ApiConfiguration']['access_token'] = $this->__mcryptToOpenSSLEnc($value['ApiConfiguration']['access_token']);
                }
                if (!empty($value['ApiConfiguration']['refresh_token']) && !$ApiConfiguration->isEncrypted($value['ApiConfiguration']['refresh_token'])) {
                    $value['ApiConfiguration']['refresh_token'] = $this->__mcryptToOpenSSLEnc($value['ApiConfiguration']['refresh_token']);
                }
            }
            $ApiConfiguration->saveMany($data, array('validate' => false, 'callbacks' => false));
        }
		return true;
	}

/**
 * mcryptToOpenSSLEnc
 * Decrypts a value originally encripted using deprecated MCRYPT methods, and re-encrypts it
 * using OpenSSL.
 *
 * @param string $mcryptEncVal a string encrypted using MCRYPT
 * @return string
 */
    private function __mcryptToOpenSSLEnc($mcryptEncVal) {
        if (empty($mcryptEncVal)) {
            return null;
        }
        $AppModel = ClassRegistry::init('AppModel');

        $unEncrypted = $AppModel->mcryptDencrypt($mcryptEncVal);
        $search = [",", " ", "'"];
        $unEncrypted = str_replace($search, "", $unEncrypted);
        $encVal = $AppModel->encrypt($unEncrypted, Configure::read('Security.OpenSSL.key'));
        ClassRegistry::flush();
        return $encVal;
    }


}
