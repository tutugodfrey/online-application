<?php
class CobrandedOnlineapp extends CakeMigration {

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
      'create_table' => array(
        'onlineapp_cobrands' => array(
          'id' => array(
            'type'    => 'integer',
            'null'    => false,
            'key'     => 'primary'
          ),
          'partner_name' => array(
            'type'    => 'string',
            'null'    => false,
          ),
          'partner_name_short' => array(
            'type'    => 'string',
            'null'    => false,
          ),
          'logo_url' => array(
            'type'    => 'string',
            'null'    => true,
          ),
          'description' => array(
            'type'    => 'text',
            'null'    => true,
          ),
          'created' => array(
            'type'    => 'datetime',
            'null'    => false,
          ),
          'modified' => array(
            'type'    => 'datetime',
            'null'    => false,
          ),
          'indexes' => array(
            'PRIMARY' => array(
              'column' => 'id',
              'unique' => 1,
            )
          )
        ),
        'onlineapp_templates' => array(
          'id' => array(
            'type'    => 'integer',
            'null'    => false,
            'key'     => 'primary',
          ),
          'name' => array(
            'type'    => 'string',
            'null'    => false,
          ),
          'logo_position' => array(
            'type'    => 'integer',
            'null'    => false,
            'default' => 3
          ),
          'include_axia_logo' => array(
            'type'    => 'boolean',
            'null'    => false,
            'default' => true,
          ),
          'description' => array(
            'type'    => 'text',
            'null'    => true,
          ),
          'cobrand_id' => array(
            'type'    => 'integer',
            'null'    => true,
          ),
          'created' => array(
            'type'    => 'datetime',
            'null'    => false,
          ),
          'modified' => array(
            'type'    => 'datetime',
            'null'    => false,
          ),
          'indexes' => array(
            'PRIMARY' => array(
              'column' => 'id',
              'unique' => 1,
            )
          )
        ),
        'onlineapp_template_pages' => array(
          'id' => array(
            'type'    => 'integer',
            'null'    => false,
            'key'     => 'primary'
          ),
          'name' => array(
            'type' => 'string',
            'null' => false
          ),
          'description' => array(
            'type'    => 'text',
            'null'    => true
          ),
          'template_id' => array(
            'type' => 'integer',
            'null' => false
          ),
          'order' => array(
            'type' => 'integer',
            'null' => false
          ),
          'created' => array(
            'type'    => 'datetime',
            'null'    => false
          ),
          'modified' => array(
            'type'    => 'datetime',
            'null'    => false
          ),
          'indexes' => array(
            'PRIMARY' => array(
              'column' => 'id',
              'unique' => 1
            )
          )
        ),
        'onlineapp_template_sections' => array(
          'id' => array(
            'type'    => 'integer',
            'null'    => false,
            'key'     => 'primary'
          ),
          'name' => array(
            'type' => 'string',
            'null' => false
          ),
          'description' => array(
            'type'    => 'text',
            'null'    => true
          ),
          'page_id' => array(
            'type' => 'integer',
            'null' => false
          ),
          'order' => array(
            'type' => 'integer',
            'null' => false
          ),
          'created' => array(
            'type'    => 'datetime',
            'null'    => false
          ),
          'modified' => array(
            'type'    => 'datetime',
            'null'    => false
          ),
          'indexes' => array(
            'PRIMARY' => array(
              'column' => 'id',
              'unique' => 1
            )
          )
        ),
        'onlineapp_template_fields' => array(
          'id' => array(
            'type'    => 'integer',
            'null'    => false,
            'key'     => 'primary'
          ),
          'name' => array(
            'type' => 'string',
            'null' => false
          ),
          'description' => array(
            'type' => 'text',
            'null' => true
          ),
          'type' => array(
            'type' => 'integer',
            'null' => false
          ),
          'required' => array(
            'type' => 'boolean',
            'null' => false,
            'default' => false
          ),
          'source' => array(
            'type' => 'integer',
            'null' => false
          ),
          'default_value' => array(
            'type' => 'string',
            'null' => true
          ),
          'merge_field_name' => array(
            'type' => 'string',
            'null' => true
          ),
          'order' => array(
            'type' => 'integer',
            'null' => false
          ),
          'section_id' => array(
            'type' => 'integer',
            'null' => false
          ),
          'created' => array(
            'type'    => 'datetime',
            'null'    => false
          ),
          'modified' => array(
            'type'    => 'datetime',
            'null'    => false
          ),
          'indexes' => array(
            'PRIMARY' => array(
              'column' => 'id',
              'unique' => 1
            )
          )
        )
      ),
      'create_field' => array(
        'onlineapp_users' => array(
          'cobrand_id' => array(
            'type' => 'integer'
          ),
          'template_id' => array(
            'type' => 'integer'
          )
        )
      )
    ),
    'down' => array(
      'drop_field' => array(
        'onlineapp_users' => array(
          'cobrand_id',
          'template_id'
        )
      ),
      'drop_table' => array(
        'onlineapp_cobrands',
        'onlineapp_templates',
        'onlineapp_template_pages',
        'onlineapp_template_sections',
        'onlineapp_template_fields'
      ),
    )
  );

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
  public function before($direction) {
    // remove the foreign key constraint if we are 'down'
    if ($direction == 'down') {
      echo "\nDrop the foreign key relationship for cobrand and template on the onlineapp_users table\n";
      $Cobrand = ClassRegistry::init('Cobrand');
      $Cobrand->query("
        ALTER TABLE onlineapp_users
        DROP CONSTRAINT onlineapp_users_cobrand_fk;
        ALTER TABLE onlineapp_users
        DROP CONSTRAINT onlineapp_users_template_fk;
        ALTER TABLE onlineapp_templates
        DROP CONSTRAINT onlineapp_template_cobrand_fk;
        ALTER TABLE onlineapp_template_pages
        DROP CONSTRAINT onlineapp_template_page_template_fk;
        ALTER TABLE onlineapp_template_sections
        DROP CONSTRAINT onlineapp_template_section_page_fk;
        ALTER TABLE onlineapp_template_fields
        DROP CONSTRAINT onlineapp_template_field_section_fk;
      "); 
    }
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
    if ($direction == 'up') {
      $data = $this->__getCobrandData();

      echo "\nINSERT Cobrand\n";
      $Cobrand = ClassRegistry::init('Cobrand');
      $hooza_cobrand_id;
      $axia_cobrand_id;
      foreach ($data['Cobrand'] as $cobrand) {
        echo "-------\n";
        //echo var_dump($cobrand);
        $Cobrand->create();
        if ($Cobrand->save($cobrand)) {
          echo String::insert(
            "Created cobrand partner for [:partner_name]\n",
            array(
              "partner_name" => $cobrand["partner_name"]
            )
          );
          switch ($cobrand['partner_name']) {
            case 'Hooza':
              $hooza_cobrand_id = $Cobrand->id;
              break;

            case 'Axia':
              $axia_cobrand_id = $Cobrand->id;
              break;
            
            default:
              # noop
              break;
          }
        } else {
          echo "Failed to seed cobrand data.";
          return false;
        }
      }

      echo "\nCobrand table has been initialized\n";

      // set cobrand_id in the users table
      // hooza@axiapayments.com ==> gets Hooza
      $User = ClassRegistry::init('OnlineappUser');
      $hooza_user = $User->findByEmail('hooza@axiapayments.com');
      $User->id = $hooza_user['OnlineappUser']['id'];
      $User->saveField('cobrand_id', $hooza_cobrand_id);
      
      // everyone else gets the axia cobrand
      $Cobrand->query(
        String::insert("
          UPDATE onlineapp_users
          SET cobrand_id = :id
          WHERE email != 'hooza@axiapayments.com'
          ", array('id' => $axia_cobrand_id)));

      echo "\nCreate the foreign key relationship for cobrand and template\n";
      $Cobrand->query("
        ALTER TABLE onlineapp_users
        ADD CONSTRAINT onlineapp_users_cobrand_fk FOREIGN KEY (cobrand_id) REFERENCES onlineapp_cobrands (id);
        ALTER TABLE onlineapp_users
        ADD CONSTRAINT onlineapp_users_template_fk FOREIGN KEY (template_id) REFERENCES onlineapp_templates (id);
        ALTER TABLE onlineapp_templates
        ADD CONSTRAINT onlineapp_template_cobrand_fk FOREIGN KEY (cobrand_id) REFERENCES onlineapp_cobrands (id);

        ALTER TABLE onlineapp_template_pages
        ADD CONSTRAINT onlineapp_template_page_template_fk FOREIGN KEY (template_id) REFERENCES onlineapp_templates (id);
        ALTER TABLE onlineapp_template_sections
        ADD CONSTRAINT onlineapp_template_section_page_fk FOREIGN KEY (page_id) REFERENCES onlineapp_template_pages (id);
        ALTER TABLE onlineapp_template_fields
        ADD CONSTRAINT onlineapp_template_field_section_fk FOREIGN KEY (section_id) REFERENCES onlineapp_template_sections (id);
      ");

      // Add the default axia template
      $this->__addDefaultAxiaTemplate($axia_cobrand_id);
    } else if ($direction == 'down') {
        //do more work here
    }
    return true;
  }

  private function __getCobrandData() {
    $data['Cobrand'][0]['partner_name'] = 'A Charity for Charities';
    $data['Cobrand'][0]['partner_name_short'] = 'ACFC';
    $data['Cobrand'][0]['logo_url'] = 'TODO: add ACFC logo';
    $data['Cobrand'][1]['partner_name'] = 'Axia';
    $data['Cobrand'][1]['partner_name_short'] = 'AX';
    $data['Cobrand'][1]['logo_url'] = '/img/axia_logo.png';
    $data['Cobrand'][2]['partner_name'] = 'Hooza';
    $data['Cobrand'][2]['partner_name_short'] = 'HZ';
    $data['Cobrand'][2]['logo_url'] = 'TODO: add HZ logo';
    $data['Cobrand'][3]['partner_name'] = 'Inspire';
    $data['Cobrand'][3]['partner_name_short'] = 'IN';
    $data['Cobrand'][3]['logo_url'] = 'TODO: add IN logo';
    $data['Cobrand'][4]['partner_name'] = 'Passport';
    $data['Cobrand'][4]['partner_name_short'] = 'PP';
    $data['Cobrand'][4]['logo_url'] = 'TODO: add PP logo';
    $data['Cobrand'][5]['partner_name'] = 'PaymentSpring';
    $data['Cobrand'][5]['partner_name_short'] = 'PS';
    $data['Cobrand'][5]['logo_url'] = 'TODO: add PS logo';
    $data['Cobrand'][6]['partner_name'] = 'Shortcut';
    $data['Cobrand'][6]['partner_name_short'] = 'SC';
    $data['Cobrand'][6]['logo_url'] = 'TODO: add SC logo';
    return $data;
  }

  private function __addDefaultAxiaTemplate($axia_cobrand_id) {
    $template = array(
      'Template' => array(
        'name' => 'Default',
        'description' => 'Default Axia application',
        'logo_position' => 1,
        'include_axia_logo' => false,
        'cobrand_id' => $axia_cobrand_id,
      )
    );
    

    echo "add templates\n";
    $Template = ClassRegistry::init('Template');
    $Template->create();
    $Template->save($template['Template']);
    $default_template_id = $Template->id;

    // TODO: add pages and other children
    $TemplatePage = ClassRegistry::init('TemplatePage');
    $TemplateSection = ClassRegistry::init('TemplateSection');
    $TemplateField = ClassRegistry::init('TemplateField');
    $page_order = 0;
    foreach ($this->__pages as $page) {
      $TemplatePage->create();
      $TemplatePage->save(
        array(
          'TemplatePage' => array(
            'name' => $page['name'],
            'order' => $page_order,
            'template_id' => $default_template_id
          )
        )
      );
      $page_order = $page_order + 1;
      $page_id = null;
      if (array_key_exists('sections', $page) && count($page['sections']) > 0) {
        $page_id = $TemplatePage->id;
        // add sections
        $sections = $page['sections'];
        $section_order = 0;
        foreach ($sections as $section) {
          $TemplateSection->create();
          $TemplateSection->save(
            array(
              'TemplateSection' => array(
                'name' => $section['name'],
                'order' => $section_order,
                'page_id' => $page_id
              )
            )
          );
          $section_order = $section_order + 1;
          $section_id = null;
          if (array_key_exists('fields', $section) && count($section['fields']) > 0) {
            $section_id = $TemplateSection->id;
            // add fields
            $fields = $section['fields'];
            $field_order = 0;
            foreach ($fields as $field) {
              $TemplateField->create();
              $TemplateField->save(
                array(
                  'TemplateField' => array(
                    'name' => $field['name'],
                    'order' => $field_order,
                    'section_id' => $section_id,
                    'type' => $field['type'],
                    'required' => $field['required'],
                    'source' => $field['source'],
                    'default_value' => $field['default_value'],
                    'merge_field_name' => $this->__buildMergeFieldName($page['name'], $section['name'], $field['name']),
                  )
                )
              );
              $field_order = $field_order + 1;

              // TODO: fields can have follow on questions

            }
          }
        }
      }
    }
  }

  private function __stripString($str, $remove_spaces = false) {
    $bad_chars = array('&', '#', '(', ')', '?');
    if ($remove_spaces) {
      $bad_chars[count($bad_chars)] = ' ';
    }
    return str_replace($bad_chars, '', $str);
  }

  private function __buildMergeFieldName($page_name, $section_name, $field_name) {
    return String::insert(
      ":page_name_:section_name_:field_name",
      array(
        'page_name' => $this->__getFLOEW($page_name),
        'section_name' => $this->__getFLOEW($section_name),
        'field_name' => $this->__stripString($field_name, true),
      )
    );
  }

  // get the "first letter of each word"
  private function __getFLOEW($words) {
    // replace & and # with ''
    $clean_words = $this->__stripString($words);
    $words_array = split(' ', $clean_words);
    $FLOEW = '';
    foreach ($words_array as $word) {
      $FLOEW = $FLOEW . substr($word, 0, 1);
    }
    return $FLOEW;
  }

  private $__pages = array(
    array(
      'name' => 'General Information',
      'sections' => array(
        array(
          'name' => 'OWNERSHIP TYPE',
          'fields' => array(
            array(
              'name' => 'Ownership Type',
              'type' => 4,
              'required' => true,
              'source' => 1,
              'default_value' => 'Corporation:0,Sole Prop:1,LLC:2,Partnership:3,Non Profit/Tax Exempt (fed form 501C):4,Other:5',
            )
          )
        ),
        array(
          'name' => 'CORPORATE INFORMATION',
          'fields' => array(
            array(
              'name' => 'Legal Business Name',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Mailing Address',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'City',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'State',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Zip',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Phone',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Fax',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Corp Contact Name',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Title',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Email',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Federal Tax ID',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Website',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Customer Service Phone',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Business Open Date',
              'type' => 1,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Length of Current Ownership',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Existing Axia Merchant?',
              'type' => 4,
              'required' => true,
              'source' => 1,
              'default_value' => 'Yes:0,No:1',
            ),
            array(
              'name' => 'Current MID #',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'General Comments',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
          )
        ),
        array(
          'name' => 'LOCATION INFORMATION',
          'fields' => array(
            array(
              'name' => 'Same As Corporate Information',
              'type' => 3,
              'source' => 1,
              'required' => false,
              'default_value' => '',
            ),
            array(
              'name' => 'Business Name (DBA)',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Location Address',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'City',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'State',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Zip',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Phone',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Fax',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Location Contact Name',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Title',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Email',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
          )
        ),
        array(
          'name' => 'LOCATION TYPE',
          'fields' => array(
            array(
              'name' => 'Location Type',
              'type' => 4,
              'required' => true,
              'source' => 1,
              'default_value' => 'Retail Store:0,Industrial:1,Trade:2,Office:3,Residence:4,Other:5',
            ),
          )
        ),
        array(
          'name' => 'MERCHANT',
          'fields' => array(
            array(
              'name' => 'Merchant Ownes/Leases',
              'type' => 4,
              'required' => true,
              'source' => 1,
              'default_value' => 'Owns:0,Leases:1',
            ),
            array(
              'name' => 'Landlord Name',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Landlord Phone',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
          )
        )
      )
    ),
    array(
      'name' => 'Products & Services Information',
      'sections' => array(
        array(
          'name' => 'General Underwriting Profile',
          'fields' => array(
            array(
              'name' => 'Profile',
              'type' => 4,
              'required' => true,
              'source' => 1,
              'default_value' => 'Retail:0,Restaurant:1,Lodging:2,MOTO:3,Internet:4,Grocery:5',
            ),
            array(
              'name' => 'Products/Services Sold',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Return Policy',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Days Until Product Delivery',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Monthly Volume',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Average Ticket',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Highest Ticket',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Current Processor',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            )
          ),
        ),
        array(
          'name' => 'Method of Sales',
          'fields' => array(
            array(
              'name' => 'Method of Sales',
              'type' => 5,
              'required' => true,
              'source' => 1,
              'default_value' => 'Card Present Swiped:0,Card Present Imprint:1,Card Not Present (Keyed):2,Card Not Present (Internet):3',
            )
          )
        ),
        array(
          'name' => '% of Products Sold',
          'fields' => array(
            array(
              'name' => '% of Product Sold',
              'type' => 5,
              'required' => true,
              'source' => 1,
              'default_value' => 'Card Present Swiped:0,Card Present Imprint:1,Card Not Present (Keyed):2,Card Not Present (Internet):3',
            )
          )
        ),
        array(
          'name' => 'High Volume Months',
          'fields' => array(
            array(
              'name' => 'Jan',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Feb',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Mar',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Apr',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'May',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Jun',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Jul',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'aug',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Sep',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Oct',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Nov',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Dec',
              'type' => 3,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
          )
        )
      )
    ),
    array(
      'name' => 'ACH Bank and Trade Reference',
      'sections' => array(
        array(
          'name' => 'Bank Information',
          'fields' => array(
            array(
              'name' => 'Bank Name',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Contact Name',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Phone',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Address',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'City',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Zip',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            )
          )
        ),
        array(
          'name' => 'Depository Account',
          'fields' => array(
            array(
              'name' => 'Routing Number',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Account Number',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            )
          )
        ),
        array(
          'name' => 'Fees Account',
          'fields' => array(
            array(
              'name' => 'Routing Number',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',

            ),
            array(
              'name' => 'Account Number',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
              'merge_field_name' => 'ach_bank_and_trade_reference_fees_account_account_number',
            )
          )
        ),
        array(
          'name' => 'Trade Reference 1',
          'fields' => array(
            array(
              'name' => 'Business Name',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Contact Person',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Phone',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Acct #',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'City',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'State',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
          )
        ),
        array(
          'name' => 'Trade Reference 2',
          'fields' => array(
            array(
              'name' => 'Business Name',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Contact Person',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Phone',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Acct #',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'City',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'State',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
          )
        )
      )
    ),
    array(
      'name' => 'Set Up Information',
      'sections' => array(
        array(
          'name' => 'American Express Information',
          'fields' => array(
            array(
              'name' => 'Do you currently accept American Express?',
              'type' => 4,
              'required' => true,
              'source' => 1,
              'default_value' => 'Yes:0,No:1',
              'merge_field_name' => 'set_up_information_american_express_information_currently_accepted',
            ),
            array(
              'name' => 'Please Provide Existing SE#',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
              'merge_field_name' => 'set_up_information_american_express_information_existing_se',
            ),
            array(
              'name' => 'Do you want to accept American Express',
              'type' => 4,
              'required' => false,
              'source' => 1,
              'default_value' => 'Yes:0,No:1',
              'merge_field_name' => 'set_up_information_american_express_information_wants_to_accept_american_express',
            ),
          )
        ),
        array(
          'name' => 'Discover Information',
          'fields' => array(
            array(
              'name' => 'Do you want to accept Discover?',
              'type' => '4',
              'required' => true,
              'source' => 1,
              'default_value' => 'Yes:0,No:1',
            )
          )
        ),
        array(
          'name' => 'Terminal/Software Type (1)',
          'fields' => array(
            array(
              'name' => 'Quantity',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Type',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Provider',
              'type' => '4',
              'required' => true,
              'source' => 1,
              'default_value' => 'Axia:0,Merchant:1',
            ),
            array(
              'name' => 'Do You Use Autoclose?',
              'type' => '4',
              'required' => true,
              'source' => 1,
              'default_value' => 'Yes:0,No:1',
            ),
            array(
              'name' => 'If Yes, What Time?',
              'type' => '2',
              'required' => true,
              'source' => 1,
              'default_value' => '',
            )
          )
        ),
        array(
          'name' => 'Terminal Programming Information (please select all that apply)',
          'fields' => array(
            array(
              'name' => 'AVS',
              'type' => '3',
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Server #s',
              'type' => '3',
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Tips',
              'type' => '3',
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Invoice #',
              'type' => '3',
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Purchasing Cards',
              'type' => '3',
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Do you accept Debit on this terminal?',
              'type' => 4,
              'required' => true,
              'source' => 1,
              'default_value' => 'Yes:0,No:1',
            ),
            array(
              'name' => 'If Yes, what type of PIN Pad?',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Quantity',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            )
          )
        ),
        array(
          'name' => 'Terminal/Software Type (2)',
          'fields' => array(
            array(
              'name' => 'Quantity',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Type',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Provider',
              'type' => '4',
              'required' => true,
              'source' => 1,
              'default_value' => 'Axia:0,Merchant:1',
            ),
            array(
              'name' => 'Do You Use Autoclose?',
              'type' => '4',
              'required' => true,
              'source' => 1,
              'default_value' => 'Yes:0,No:1',
            ),
            array(
              'name' => 'If Yes, What Time?',
              'type' => '2',
              'required' => true,
              'source' => 1,
              'default_value' => '',
            )
          )
        ),
        array(
          'name' => 'Terminal Programming Information (please select all that apply)',
          'fields' => array(
            array(
              'name' => 'AVS',
              'type' => '3',
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Server #s',
              'type' => '3',
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Tips',
              'type' => '3',
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Invoice #',
              'type' => '3',
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Purchasing Cards',
              'type' => '3',
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Do you accept Debit on this terminal?',
              'type' => 4,
              'required' => true,
              'source' => 1,
              'default_value' => 'Yes:0,No:1',
            ),
            array(
              'name' => 'If Yes, what type of PIN Pad?',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Quantity',
              'type' => 0,
              'required' => false,
              'source' => 1,
              'default_value' => '',
            )
          )
        )
      )
    ),
    array(
      'name' => 'Ownership Information',
      'sections' => array(
        array(
          'name' => 'OWNER / OFFICER (1) Percentage Ownership',
          'fields' => array(
            array(
              'name' => 'Full Name',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Title',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Address',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'City',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'State',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Zip',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Phone',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Fax',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Email',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'SSN',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Date of Birth',
              'type' => 1,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
          )
        ),
        array(
          'name' => 'OWNER / OFFICER (2) Percentage Ownership',
          'fields' => array(
            array(
              'name' => 'Full Name',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Title',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Address',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'City',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'State',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Zip',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Phone',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Fax',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Email',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'SSN',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Date of Birth',
              'type' => 1,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
          )
        )
      )
    ),
    array(
      'name' => 'Merchant Referral Program',
      'sections' => array(
        array(
          'name' => "Any successful referrals will result in $100 credit to Merchant's bank account provided. Visit our referral program page for details.",
          'fields' => array(
            array(
              'name' => 'Referral Business #1',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Owner/Officer',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Phone #',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Referral Business #2',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Owner/Officer',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Phone #',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Referral Business #3',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Owner/Officer',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
            array(
              'name' => 'Phone #',
              'type' => 0,
              'required' => true,
              'source' => 1,
              'default_value' => '',
            ),
          )
        ),
        // TODO: add Validate Application info
      )
    ),
  );
}
