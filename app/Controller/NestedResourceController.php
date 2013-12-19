<?php
App::uses('AppController', 'Controller');
/**
 * NestedResource Controller
 */
class NestedResourceController extends AppController {
  /*-----------------------------------------------------*/
  /*   helper functions for parent child relationships   */
  /*-----------------------------------------------------*/

  protected $_restrict_to_admin = true;
  protected $_parent_controller_name;
  protected $_controller_name;

  protected function _getParentListUrl() {
    return strtolower(String::insert(
      ':admin/:parent_controller_name',
      array(
        "admin" => ($this->_restrict_to_admin === true ? '/admin' : ''),
        "parent_controller_name" => $this->_parent_controller_name)
    ));
  }

  protected function _getListUrl() {
    return strtolower(String::insert(
      ':admin/:parent_controller_name/:parent_controller_id/:controller_name',
      array(
        "admin" => ($this->_restrict_to_admin === true ? "/admin" : ""),
        "parent_controller_name" => $this->_parent_controller_name,
        "parent_controller_id" => $this->_getParentControllerId(),
        "controller_name" => $this->_controller_name)
    ));
  }

  private $__parent_controller_id;
  protected function _getParentControllerId() {
    if ($this->__parent_controller_id == null) {
      // makes an assumption about the routes :(
      $this->__parent_controller_id = $this->request->params['parent_controller_id'];
    }
    return $this->__parent_controller_id;
  }
}
