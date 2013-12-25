<div style="padding-top: 25px;">
  <div>
    <h1><?php echo "Preview of '" . $template['Template']['name'] ."' Template"; ?></h1>
  </div>

  <div>
    <?php
    // TODO: use the Template->logo_position and include_axia_logo
    // to display this VVVV
    echo $this->Html->image($template['Cobrand']['logo_url']);
    ?></div>
  <br/>
  <div class="panel-group" id="accordion">
  <?php foreach ($template['TemplatePages'] as $page): ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo str_replace(array(' ', '&'), '', $page['name']) ?>">
            <?php echo $page['name'] ?>
          </a>
        </h4>
      </div>
      <div id="<?php echo str_replace(array(' ', '&', '#'), '', $page['name']) ?>" class="panel-collapse collapse">
        <div class="panel-body">
          <?php
          $form_html = $this->Form->create($page['name']);
          echo preg_replace('/(id="[^"]*)"/', '\1" class="onlineapp_preview_page"', $form_html);
          ?>
          <?php foreach ($page['TemplateSections'] as $section): ?>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><?php echo $section['name']; ?></h3>
              </div>
              <div class="panel-body">
                <?php foreach ($section['TemplateFields'] as $field):
                  switch ($field['type']) {
                    case 0: // text
                      echo $this->Form->input($field['name']);
                      break;

                    case 1: // date
                      echo $this->Form->input($field['name'], array('type' => 'date'));
                      break;

                    case 2: // time
                      echo $this->Form->input($field['name'], array('type' => 'time'));
                      break;

                    case 3: // checkboxes
                      echo $this->Form->input($field['name'], array('type' => 'checkbox'));
                      break;

                    case 4: // radios
                      $radio_options_string = $field['default_value'];
                      $radio_options = array();
                      foreach (split(',', $radio_options_string) as $key_value_pair_str) {
                        $key_value_pair = split(':', $key_value_pair_str);
                        $radio_options[$key_value_pair[1]] = $key_value_pair[0];
                      }
                      $options = array('options' => $radio_options, 'empty' => __('(choose one)'));
                      echo $this->Form->input($field['name'], $options);
                      break;
/*
                    case 5: // percent
                      echo $field['name'] . " percent<br/>";
                      break;
*/
                    default:
                      echo '***** UNRECOGNIZED FIELD TYPE [' . $field['type'] . '] for field [' . $field['merge_field_name'] . ']*****';
                      break;
                  }
                  ?>
                <?php endforeach; ?><!-- end fields -->
              </div>
            </div>
          <?php endforeach; ?><!-- end sections -->
          <?php echo $this->Form->end(array('label' => 'Update', 'class' => 'hidden')); ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?><!-- end pages -->
  </div>
</div>