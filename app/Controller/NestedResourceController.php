<?php
App::uses('AppController', 'Controller');
/**
 * NestedResource Controller
 */
class NestedResourceController extends AppController {
	/*-----------------------------------------------------*/
	/*   helper functions for parent-child relationships   */
	/*-----------------------------------------------------*/

	protected $_restrictToAdmin = true;

	protected $_parentCtrlName;

	protected $_controllerName;

	protected function _getParentListUrl() {
		return strtolower(String::insert(
			':admin/:parentControllerName',
			array(
				"admin" => ($this->_restrictToAdmin === true ? '/admin' : ''),
				"parentControllerName" => $this->_parentCtrlName)
		));
	}

	protected function _getListUrl() {
		return strtolower(String::insert(
			':admin/:parentControllerName/:parent_controller_id/:controller_name',
			array(
				"admin" => ($this->_restrictToAdmin === true ? "/admin" : ""),
				"parentControllerName" => $this->_parentCtrlName,
				"parent_controller_id" => $this->_getParentControllerId(),
				"controller_name" => $this->_controllerName)
		));
	}

	private $__parentControllerId;

	protected function _getParentControllerId() {
		if ($this->__parentControllerId == null) {
			// makes an assumption about the routes :(
			$this->__parentControllerId = $this->request->params['parent_controller_id'];
		}
		return $this->__parentControllerId;
	}
}
