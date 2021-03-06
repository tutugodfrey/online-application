<?php
class AdminController extends AppController {

	public $uses = array();

	public $permissions = array(
		'index' => array('admin', 'rep', 'manager', 'api'),
	);

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index() {
		$links = array();
		if ($this->Session->read('Auth.User.Group.name') == 'admin') {
			$links = array(
				array('System Integrations Configurations', '/admin/ApiConfigurations/index'),
				array('Settings', '/admin/settings/'),
				array('Users', '/admin/users/'),
				array('Groups', '/admin/groups/'),
				array('Cobrands', '/admin/cobrands'),
				array('API IP restrictions', '/admin/apips/'),
				array('API Logs', '/admin/apiLogs/'),
				array('System Event Log', '/admin/SysEventLogs/index/'),
				array('Email Timeline Subjects', '/admin/emailTimelineSubjects/'),
				array('Template Builder', '/admin/template_builder/build'),
			);
		}

		// everyone gets to see these links
		$links[count($links)] = array('API Documentation', '/Documentations/apidoc/');
		$links[count($links)] = array('Applications', '/admin/cobranded_applications/');
		$links[count($links)] = array('Coversheets', '/admin/coversheets/');

		$this->set('links', $links);
	}
}
//End of File
