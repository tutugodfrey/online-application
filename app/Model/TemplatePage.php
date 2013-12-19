<?php
App::uses('AppModel', 'Model');
/**
 * OnlineappTemplatePage Model
 *
 * @property Template $Template
 */
class TemplatePage extends AppModel {

  public $displayField = 'name';
	public $useTable = 'onlineapp_template_pages';

	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
        'required' => true,
        'message' => array('Template page name cannot be empty')
			),
		),
		'template_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
        'required' => true,
        'message' => array('Invalid cobrand_id value used')
			),
		),
    'order' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => array('Invalid order value used')
      ),
      'notempty' => array(
        'rule' => array('notempty'),
      ),
    ),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
		'Template' => array(
			'className' => 'Template',
			'foreignKey' => 'template_id',
		)
	);

	public $hasMany = array(
		'TemplateSections' => array(
			'className' => 'TemplateSection',
			'foreignKey' => 'page_id',
			'order' => 'TemplateSections.order',
      'dependent' => true,
		)
	);

  public function beforeSave($options = array()) {
    // if the order field is not set, figure it out
    if (empty($this->data['TemplatePage']['order'])) {
      $template = $this->Template->find(
        'first',
        array('conditions' => 'Template.id = "' . $this->data['TemplatePage']['template_id'] . '"' ),
        array('contain' => array('TemplatePage'))
      );
      $count = count($template['TemplatePages']);

      $this->data['TemplatePage']['order'] = $count;
    }

    // TODO: if the order is dirty we may have to change some siblings
  }

	public function getCobrand($template_id) {
    // is this the way to access another model?
    $this->Template->id = $template_id;
    $parentTemplate = $this->Template->read();
    return $parentTemplate['Cobrand'];
	}

	public function getTemplate($template_id) {
    $this->Template->id = $template_id;
    $template = $this->Template->read();
    return $template['Template'];
	}
}
