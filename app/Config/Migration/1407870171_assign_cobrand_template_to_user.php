<?php
class AssignCobrandTemplateToUser extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {

		$this->User = $this->generateModel('OnlineappUser');
		$this->Cobrand = $this->generateModel('OnlineappCobrand');
		$this->Template = $this->generateModel('OnlineappTemplate');
		$this->User->recursive = -1;
		$this->Cobrand->recursive = -1;
		$this->Template->recursive = -1;
		$partners = array(
			'acfc' => array(
				'name' => 'A Charity For Charities',
				'email' => 'julie.david@acharityforcharities.org'
			),
			'fs1' => array(
				'name' => 'Fire Spring',
				'email' => 'jason.wilkinson@firespring.com'
			),
			'fs2' => array(
				'name' => 'Fire Spring',
				'email' => 'brendan.mcdaniel@firespring.com'
			),
			'inspire' => array(
				'name' => 'Inspire Commerce',
				'email' => 'support@inspirecommerce.com'
			),
			'short' => array(
				'name' => 'Shortcuts',
				'email' => 'garry.robertson@shortcuts.net'
			),
			'axia' => array(
				'name' => 'Axia'
			),
			'appfolio' => array(
				'name' => 'Appfolio',
				'email' => 'appfolio@axiapayments.com'
			)
		);
		if($direction == 'up') {
			foreach($partners as $partner) {

				$cobrand = $this->Cobrand->find('all', array(
					'conditions' => array('OnlineappCobrand.partner_name' => $partner['name']),
					'fields' => array('id')
				));
				$template = $this->Template->find('all', array(
					'conditions' => array('OnlineappTemplate.cobrand_id' => $cobrand['0']['OnlineappCobrand']['id']),
					'fields' => array('id')
				));
				if(array_key_exists('email', $partner)) {
					$user = $this->User->find('all', array(
						'conditions' => array('email' => $partner['email']),
						'fields' => array('id')
					));
				
					$this->User->id = $user['0']['OnlineappUser']['id'];
					$this->User->set(array(
						'template_id' => $template['0']['OnlineappTemplate']['id'],
						'cobrand_id' => $cobrand['0']['OnlineappCobrand']['id']
					));
					$this->User->save();
				} else {
					$this->User->updateAll(array(
						'OnlineappUser.template_id' => $template['0']['OnlineappTemplate']['id'],
						'OnlineappUser.cobrand_id' => $cobrand['0']['OnlineappCobrand']['id']
					),
						array('OnlineappUser.template_id' => null, 'OnlineappUser.cobrand_id' => null)
					);
				}
			}
		} else {
			$this->User->updateAll(array(
				'OnlineappUser.template_id' => null,
				'OnlineappUser.cobrand_id' => null 
			));
		}
		return true;
	}
}
