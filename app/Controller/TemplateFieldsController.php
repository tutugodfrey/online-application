<?php
App::uses('NestedResourceController', 'Controller');
/**
 * TemplateFields Controller
 *
 */
 /**
 * @OA\Tag(name="TemplateFields", description="Operation and data about TemplateFields")
 *
 * @OA\Schema(
 *	   schema="TemplateFields",
 *     description="TemplateFields database table schema",
 *     title="TemplateFields",
 *     @OA\Property(
 *			description="TemplateField id",
 *			property="id",
 *			type="integer"
 *     ),
 *	   @OA\Property(
 *			description="This important property is used to uniquely identify the field across platforms and must be used exactly as shown (case sensitive).",
 *			property="merge_field_name",
 *			type="string"
 *     ),
 *     @OA\Property(
 *			description="This property's purpose is to simply provide a human readable name.",
 *			property="name",
 *			type="string"
 *     ),
 *     @OA\Property(
 *			description="The template field description may contain additional details about the field",
 *			property="description",
 *			type="string"
 *     ),
 *     @OA\Property(
 *			description="The Field types include radio and checkbox field types are boolean data types, fees type is a monetary data type, etc. Requests will display with a proper type description rather than an integer in the response.",
 *			property="type",
 *			type="integer"
 *     ),
 *     @OA\Property(
 *			description="Indicates whether the template field is required for an application to pass validation",
 *			property="required",
 *			type="boolean"
 *     )
 * )
 */
class TemplateFieldsController extends NestedResourceController {

	protected $_parentCtrlName = 'TemplateSections';

	protected $_controllerName = 'TemplateFields';

	public $permissions = array(		
		'admin_index' => array('admin', 'rep', 'manager'),
		'admin_add' => array('admin', 'rep', 'manager'),
		'admin_edit' => array('admin', 'rep', 'manager'),
		'admin_delete' => array('admin', 'rep', 'manager'),
	);
	
	public function admin_add() {
		if ($this->request->is('post')) {
			$data = $this->request->data;
			// we know the page_id from the uri
			$data['TemplateField']['section_id'] = $this->_getParentControllerId();
			$this->TemplateField->create();
			if ($this->TemplateField->save($data)) {
				$this->_success(__("Template Field Saved!"));
				return $this->redirect($this->_getListUrl());
			}
			$this->_failure(__('Unable to add your field.'));
		}

		$this->__setCommonViewVariables();
	}

	public function admin_edit($idToEdit) {
		$this->TemplateField->id = $idToEdit;

		if (empty($this->request->data)) {
			$templateField = $this->TemplateField->find(
				'first',
				array(
					'conditions' => array('TemplateField.id' => $idToEdit),
					'recursive' => -1
				)
			);
			$this->request->data = $templateField;
		} else {
			$data = $this->request->data;
			// we know the page_id from the uri
			$data['TemplateField']['section_id'] = $this->_getParentControllerId();
			if ($this->TemplateField->save($data)) {
				$this->_success(__("Template Field Saved!"));
				return $this->redirect($this->_getListUrl());
			}
			$this->_failure(__('Unable to update your field.'));
		}

		$this->__setCommonViewVariables();
	}

	public function admin_index() {
		$this->paginate = array(
			'limit' => 25,
			'recursive' => -1,
			'order' => array('TemplateField.order' => 'ASC'),
			'conditions' => array('TemplateField.section_id' => $this->_getParentControllerId())
		);

		$data = $this->paginate('TemplateField');
		$this->set('templateFields', $data);
		$this->set('scaffoldFields', array_keys($this->TemplateField->schema()));
		$this->set('requiredTypes', $this->TemplateField->requiredTypes);
		$this->__setCommonViewVariables();
	}

	public function admin_delete($idToDelete) {
		$this->TemplateField->delete($idToDelete);
		$this->_success(("Template Field Deleted!"));
		$this->redirect($this->_getListUrl());
	}

	private function __setCommonViewVariables() {
		$this->set('list_url', $this->_getListUrl());

		// set the object(s) the view will need
		$this->set('cobrand', $this->TemplateField->getCobrand($this->_getParentControllerId()));
		$this->set('template', $this->TemplateField->getTemplate($this->_getParentControllerId()));
		$this->set('templatePage', $this->TemplateField->getTemplatePage($this->_getParentControllerId()));
		$this->set('templateSection', $this->TemplateField->getTemplateSection($this->_getParentControllerId()));

		$this->set('fieldTypes', $this->TemplateField->fieldTypes);
		$this->set('sourceTypes', $this->TemplateField->sourceTypes);
	}
}
