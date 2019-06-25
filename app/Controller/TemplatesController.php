<?php
App::uses('User', 'Model');
App::uses('NestedResourceController', 'Controller');
/**
 * Templates Controller
 *
 */
 /**
 * @OA\Tag(name="Templates", description="Operation and data about Templates")
 *
 * @OA\Schema(
 *	   schema="Templates",
 *     description="Templates database table schema",
 *     title="Templates",
 *     @OA\Property(
 *			description="Template id",
 *			property="id",
 *			type="integer"
 *     ),
 *     @OA\Property(
 *			description="The cobrand associated with the template",
 *			property="cobrand_id",
 *			type="integer"
 *     ),
 *     @OA\Property(
 *			description="Template name",
 *			property="name",
 *			type="string"
 *     ),
 *     @OA\Property(
 *			description="Template description",
 *			property="description",
 *			type="string"
 *     ),
 *     @OA\Property(
 *			description="Template requires coversheet? true/false",
 *			property="requires_coversheet",
 *			type="boolean"
 *     ),
 *     @OA\Property(
 *			description="String representation of Template creation date and time in unix format yyyy-mm-dd hh:mm:ss",
 *			property="created",
 *			type="string"
 *     ),
 *     @OA\Property(
 *			description="Whether this is the user's default template (YES/NO). ",
 *			property="is_default_user_template",
 *			type="string"
 *     )
 * )
 */
class TemplatesController extends NestedResourceController {

	protected $_parentCtrlName = "Cobrands";

	protected $_controllerName = "Templates";

	public $permissions = array(
		'api_index' => array(User::API),
		'admin_index' => array('admin', 'rep', 'manager'),
		'admin_add' => array('admin', 'rep', 'manager'),
		'admin_edit' => array('admin', 'rep', 'manager'),
		'admin_delete' => array('admin', 'rep', 'manager'),
		'admin_preview' => array('admin', 'rep', 'manager'),
	);


/**
 *
 * Handles API GET request for a list of Templates assigned to the API consumer performing the request.
 * Request requires no parameters, any parameters in request query will be ignored.
 * A full list of all templates assigned to authenticated user will be returned
 *
 * @OA\Get(
 *   path="/api/Templates/index",
 *	 tags={"Templates"},
 *   summary="list autheticated user templates",
 *	 @OA\Response(
 *     response=200,
 *     @OA\MediaType(
 *         mediaType="application/json",
 *		   example={"id": 50, "cobrand_id": 2, "name": "Payment Fusion Sales Agreement", "description": "Payment Fusion Sales Agreement", "requires_coversheet": false, "created": "2017-05-30 12:28:30", "is_default_user_template": "NO"},
 *         @OA\Schema(
 *	   	   	 ref="#/components/schemas/Templates",
 *         )
 *     ),
 *     description="
 * 			status=success detailed JSON array of user's templates (empty if no templates have been assigned to the authenticated user).",
 *   ),
 *   @OA\Response(
 *     response=405,
 *     description="HTTP method not allowed when request method is not GET"
 *   )
 * )
 *
 * @return void
 */
	public function api_index() {
		$this->autoRender = false;
		$response = array('status' => 'failed', 'messages' => 'HTTP method not allowed');
		if ($this->request->is('get')) {
			$response['status'] = 'success';
			$response['messages'] = null;
			$response['data'] = $this->Template->Users->allTemplates($this->Auth->user('id'));
		} else {
			$this->response->statusCode(405); //405 Method Not Allowed
		}

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}

	public function admin_add() {
		if ($this->request->is('post')) {
			$data = $this->request->data;
			// set the cobrand_id
			$data['Template']['cobrand_id'] = $this->_getParentControllerId();
			$this->Template->create();
			if ($this->Template->save($data)) {
				$this->_success("Template Saved!");
				return $this->redirect($this->_getListUrl());
			}
			$this->_failure(__('Unable to add your template'));
		}

		$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
			
		$client = $this->CobrandedApplication->createRightSignatureClient();
		$results = $this->CobrandedApplication->getRightSignatureTemplates($client);

		$templates = array();
		$installTemplates = array();

		foreach ($results as $guid => $filename) {
			if (preg_match('/install/i', $filename)) {
				$installTemplates[$guid] = $filename;
			} else {
				$templates[$guid] = $filename;
			}
		}
		$this->set('templateList', $templates);
		$this->set('installTemplateList', $installTemplates);

		$this->__setCommonViewVariables();
	}

	public function admin_edit($idToEdit) {
		$this->Template->id = $idToEdit;
		if (empty($this->request->data)) {
			$this->request->data = $this->Template->getById($idToEdit);

			$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
			
			$client = $this->CobrandedApplication->createRightSignatureClient();
			$results = $this->CobrandedApplication->getRightSignatureTemplates($client);

			$templates = array();
			$installTemplates = array();

			foreach ($results as $guid => $filename) {
				if (preg_match('/install/i', $filename)) {
					$installTemplates[$guid] = $filename;
				} else {
					$templates[$guid] = $filename;
				}
			}

			$this->set('templateList', $templates);
			$this->set('installTemplateList', $installTemplates);
		} else {
			$data = $this->request->data;
			// we know the cobrand_id from the uri
			$data['Template']['cobrand_id'] = $this->_getParentControllerId();
			if ($this->Template->saveAll($data)) {
				$this->_success("Template Saved!");
				return $this->redirect($this->_getListUrl());
			}
			$this->_failure(__('Unable to update your template'));
		}

		$this->__setCommonViewVariables();
	}

	public function admin_index() {
		$this->paginate = array(
			'limit' => 25,
			'order' => array('Template.name' => 'asc'),
			'conditions' => array('Template.cobrand_id' => $this->_getParentControllerId())
		);

		$data = $this->paginate('Template');
		$this->set('templates', $data);
		$this->set('scaffoldFields', array_keys($this->Template->schema()));
		$this->_setViewNavData();
		$this->__setCommonViewVariables();
	}

	public function admin_delete($idToDelete) {
		$this->Template->delete($idToDelete);
		$this->_success("Template Deleted!");
		$this->redirect($this->_getListUrl());
	}

	private function __setCommonViewVariables() {
		$this->_setViewNavData();
		$this->set('list_url', $this->_getListUrl());
		$this->set('cobrand', $this->Template->getCobrand($this->_getParentControllerId()));
		$this->set('logoPositionTypes', $this->Template->logoPositionTypes);
	}

	public function admin_preview($idToPreview) {
		$this->Template->id = $idToPreview;
		$template = $this->Template->find(
			'first', array(
				'contain' => array(
					'Cobrand',
					'TemplatePages' => array(
						'TemplateSections' => array(
							'TemplateFields' => array(
								'CobrandedApplicationValues'
							)
						)
					)
				),
				'conditions' => array('Template.id' => $idToPreview)
			)
		);

		$this->set('template', $template);
		$this->set('logoPositionTypes', $this->Template->logoPositionTypes);
		$this->set('bad_characters', array(' ', '&', '#', '$', '(', ')', '/', '%', '\.', '.', '\''));
		$this->set('requireRequiredFields', true);
	}


/**
 * _setViewNavContent
 * Utility method sets an array of urls to use as left navigation items on views
 *
 * @param string $showActive string representation of boolean value
 * @return array
 */
	protected function _setViewNavData() {
		$elVars = array(
			'navLinks' => array(
				'Templates Index' => $this->_getListUrl(),
				'New Template' =>$this->_getListUrl() . '/add',
			)
		);

		$this->set(compact('elVars'));
	}

}
