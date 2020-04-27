<?php
App::uses('User', 'Model');
App::uses('NestedResourceController', 'Controller');
/**
 * Templates Controller
 * 
 * 
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
 *			description="Partner name",
 *			property="partner_name",
 *			type="string"
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
		'api_view' => array(User::API),
		'admin_index' => array('admin', 'rep', 'manager'),
		'admin_add' => array('admin', 'rep', 'manager'),
		'admin_edit' => array('admin', 'rep', 'manager'),
		'admin_delete' => array('admin', 'rep', 'manager'),
		'admin_preview' => array('admin', 'rep', 'manager'),
		'admin_preview_rs_template' => array('admin'),
	);


/**
 * api_index
 *
 * Handles API GET request for a list of Templates assigned to specified sales representative.
 *
 * Each user is given access to specific set of templates, 
 * knowing which template to use is important in order to be able to create an application for a client.
 * This endpoint will return a full list of all templates a specified sales representative has access to.
 *
 * @OA\Get(
 *   path="/api/Templates/index?rep_name={sales rep full name}",
 *	 tags={"Templates"},
 *   summary="list sales rep's user templates",
 *	 @OA\Parameter(
 *		   name="rep_name",
 *		   description="The full name of the sales repesentative (first name last name), value must match their name in their user profile in the online application system.
 *	 			Example 1: Sam Salesman,
 *	 			Example 2: John Doe",
 *         in="query",
 *         @OA\Schema(type="string")
 *   ),
 *	 @OA\Response(
 *     response=200,
 *     @OA\MediaType(
 *         mediaType="application/json",
 *		   example={"id": 50, "cobrand_id": 2, "partner_name": "Payment Fusion", "name": "Payment Fusion Sales Agreement", "description": "Payment Fusion Sales Agreement", "requires_coversheet": false, "created": "2017-05-30 12:28:30", "is_default_user_template": "NO"},
 *         @OA\Schema(
 *	   	   	 ref="#/components/schemas/Templates",
 *         )
 *     ),
 *     description="
 * 			status=success detailed JSON array of user's templates (empty if no templates have been assigned to sales rep user).,
 * 			status=failed if specified sales rep user is not found.",
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
		$response = array('status' => AppModel::API_FAILS, 'messages' => 'HTTP method not allowed');
		if ($this->request->is('get')) {
			$user = $this->User->find('first', array('recursive' => -1, 'conditions' => array(
				'User.fullname' => $this->request->query('rep_name'),
			)));
			if (!empty($user)) {
				$response['status'] = AppModel::API_SUCCESS;
				$response['messages'] = null;
				$response['data'] = $this->Template->Users->allTemplates($user['User']['id']);
			} else {
				$response['messages'] = "A user account does not exist for specified sales rep name.";
			}
		} else {
			$this->response->statusCode(405); //405 Method Not Allowed
		}

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}

/**
 * api_view
 *
 * Handles API GET request to view details about the fields used by specific Template.
 * 
 * API consumers can use this detailed list of fields to create or update an application.
 * The returned JSON data will consist of an array of arrays. The key at the top array level is called the merge_field_name and is the one required to submit application data. For more details see information on adding CobrandedApplications.
 * Request requires a query parameter containig a template id to search for. The id param value cannot be empty.
 *
 * @OA\Get(
 *   path="/api/Templates/view?id={template id}",
 *	 tags={"Templates"},
 *   summary="Returns Template fields details",
 *	 @OA\Parameter(
 *		   name="id",
 *		   description="The id of the template being requested. Empty id parameter value is not allowed.
 *	 			Example 1: 25
 *	 			Example 2: 2",
 *         in="query",
 *         @OA\Schema(type="integer")
 *   ),
 *	 @OA\Response(
 *     response=200,
 *     @OA\MediaType(
 *         mediaType="application/json",
 *		   example={"<merge_field_name>": {"type":"<data type or field type>", "required": "<boolean true/false>", "name": "<field name>","description": "<a description>"}, "CorpName": {"type": "text","required": true,"name": "Legal Business Name","description": ""}},
 *         @OA\Schema(
 *	   	   	 ref="#/components/schemas/TemplateFields",
 *         )
 *     ),
 *     description="
 * 			status=success message JSON list of Template Fields (empty when template id is not found).
 *			status=failed message Missing or invalid Template id parameter",
 *   ),
 *   @OA\Response(
 *     response=405,
 *     description="HTTP method not allowed when request method is not GET"
 *   ),
 *   @OA\Response(
 *     response=400,
 *     description="Missing or invalid Template id parameter"
 *   )
 * )
 *
 * @return void
 */
	public function api_view() {
		$this->autoRender = false;
		$response = array('status' => AppModel::API_FAILS, 'messages' => 'HTTP method not allowed');
		if ($this->request->is('get')) {
			$id = Hash::get($this->request->query, 'id');
			if (!empty($id)) {
				$response['status'] = AppModel::API_SUCCESS;
				$response['messages'] = null;
				$response['data'] = $this->Template->getTemplateFields($id, 0, false);
				if (empty($response['data'])) {
					$response['messages'] = "No template fields found for given template id.";
				}
			} else {
				$response['messages'] = 'Missing or invalid Template id parameter.';
				$this->response->statusCode(400);
			}
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
		if (!empty(Hash::get($results, 'error'))) {
			$this->_failure(__('Unexpected Error: ' . Hash::get($results, 'error') .". Please try again later."));
			return $this->redirect($this->_getListUrl());
		}
		$orderedTemplates = $this->CobrandedApplication->arrayDiffTemplateTypes($results);
		$templates = $orderedTemplates['templates'];
		$installTemplates = $orderedTemplates['install_templates'];

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

			if (!empty(Hash::get($results, 'error'))) {
				$this->_failure(__('Unexpected Error: ' . Hash::get($results, 'error') .". Please try again later."));
				return $this->redirect($this->_getListUrl());
			}
			
			$orderedTemplates = $this->CobrandedApplication->arrayDiffTemplateTypes($results);
			$templates = $orderedTemplates['templates'];
			$installTemplates = $orderedTemplates['install_templates'];

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

/**
 * admin_preview_template
 * Ajax method gets rightsignature template details for user to preview
 *
 * @param string $rsTemplateId string RightSignature external template id
 * @return void
 */
	public function admin_preview_rs_template($rsTemplateId) {
		$this->layout = 'ajax';
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			if ($this->Session->read('Auth.User.id')) {
				if (!empty($rsTemplateId)) {
					$this->CobrandedApplication = ClassRegistry::init('CobrandedApplication');
					$client = $this->CobrandedApplication->createRightSignatureClient();
					$rsTemplate = $this->CobrandedApplication->getRightSignatureTemplate($client, $rsTemplateId);
					$rsTemplate = json_decode($rsTemplate, true);

					if (empty(Hash::get($rsTemplate, 'error'))) {
						//all good
						$templateData['id'] = $rsTemplate['reusable_template']['id'];
						$templateData['name'] = $rsTemplate['reusable_template']['name'];
						$templateData['filename'] = $rsTemplate['reusable_template']['filename'];
						$templateData['signer_sequencing'] = ($rsTemplate['reusable_template']['signer_sequencing'])? "Yes":"No";
						$templateData['roles'] = $rsTemplate['reusable_template']['roles'];
						$templateData['created_at'] = $rsTemplate['reusable_template']['created_at'];
						$templateData['updated_at'] = $rsTemplate['reusable_template']['updated_at'];
						$templateData['thumbnail_url'] = $rsTemplate['reusable_template']['thumbnail_url'];
						$templateData['page_image_urls'] = $rsTemplate['reusable_template']['page_image_urls'];
						$this->set(compact('templateData'));
						$this->render('/Elements/Templates/preview_template', 'ajax');
					} else {
						//Unexpected internal error
						$this->response->statusCode(500);
						return;
					}
				} else {
					//Bad Request
					$this->response->statusCode(400);
				}
			} else {
				//session expired
				$this->response->statusCode(401);
			}
		} else {
			//Bad Request
			$this->response->statusCode(400);
		}
	}

}
