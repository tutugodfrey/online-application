<?php
class AdminController extends AppController {
  //public $components = array('RequestHandler');
  public $uses = array();
  public $permissions = array(
    'index' => array('admin', 'rep','manager'),
  );

  function beforeFilter() {
    parent::beforeFilter();
  }

  function index() {
    $links = array();
    if ($this->Session->read('Auth.User.Group.name') == 'admin') {
      $links = array(
        array('Settings', '/admin/settings/'),
        array('Users', '/admin/users/'),
        array('Groups', '/admin/groups/'),
        array('Cobrands', '/admin/cobrands'),
        array('Multipass', '/admin/multipasses/'),
        array('API IP restrictions', '/admin/apips/'),
        array('API Logs', '/admin/apiLogs/'),
        array('USAePay Merchants', '/admin/epayments/'),
        array('Email Timelines', '/admin/emailTimelines/'),
        array('Template Builder', '/admin/template_builder/'),
      );
    }

    // everyone gets to see these links
    $links[count($links)] = array('Applications', '/admin/cobranded_applications/');
    $links[count($links)] = array('Coversheets', '/admin/coversheets/');

    $this->set('links', $links);
  }
}
?>
