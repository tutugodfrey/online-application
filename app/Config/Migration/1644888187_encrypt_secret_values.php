<?php
class EncryptSecretValues extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'encrypt_secret_values';

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
            $CobrandedApplicationValue = $this->generateModel('CobrandedApplicationValue');
            $values = $CobrandedApplicationValue->find('all', array(
                    'callbacks' => false,
                    'recursive' => -1,
                    'conditions' => array(
                        'template_field_id in (select id from onlineapp_template_fields where encrypt = true)',
                        'char_length(value) < 80',
                         "value not ilike '%XXX%'",
                        "value !=''",
                    )
                )
            );

            foreach($values as $value) {
                $value['CobrandedApplicationValue']['value'] = $CobrandedApplicationValue->encrypt($value['CobrandedApplicationValue']['value'], Configure::read('Security.OpenSSL.key'));
                $CobrandedApplicationValue->clear();
                $CobrandedApplicationValue->save($value['CobrandedApplicationValue'], array('validate' => false, 'callbacks' => false));
            }
	   }
		return true;
    }
}
