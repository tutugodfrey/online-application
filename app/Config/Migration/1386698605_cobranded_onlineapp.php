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
            'type'    => 'text',
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
      $data['Cobrand'][0]['partner_name'] = 'A Charity for Charities';
      $data['Cobrand'][0]['partner_name_short'] = 'ACFC';
      $data['Cobrand'][0]['logo_url'] = 'TODO: add ACFC logo';
      $data['Cobrand'][1]['partner_name'] = 'Axia';
      $data['Cobrand'][1]['partner_name_short'] = 'AX';
      $data['Cobrand'][1]['logo_url'] = 'TODO: add AX logo';
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
              echo('$hooza_cobrand_id ' . $hooza_cobrand_id);
              break;

            case 'Axia':
              $axia_cobrand_id = $Cobrand->id;
              echo('$axia_cobrand_id ' . $axia_cobrand_id);
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
    } else if ($direction == 'down') {
        //do more work here
    }
    return true;
  }
}
