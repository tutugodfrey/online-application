<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
  public $actsAs = array(
    'Containable',
    'Utils.CsvImport' => array(
      'delimiter'  => ',',
      'hasHeader' => true,
    )
  );
 

/**
 * getById
 * Returns a single record by its id. By Default no associated Model data is contained unless specified otherwise in settings param.
 *
 * @param integer $id a User.id
 * @param array $settings to use for search
 * @return array
 */
	public function getById($id, $settings = array()) {
		$default = array(
			'contain' => false
		);
		$settings = array_merge($default, $settings);
		$settings['conditions']["{$this->alias}.id"] = $id;

		return $this->find('first', $settings);
	}
}
