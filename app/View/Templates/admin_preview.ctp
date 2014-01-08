<br/><br/><br/>
<div>
  <div>
    <h1>
    <?php
    echo String::insert(
      "Preview of ':template_name' Template for ':partner_name'",
      array(
        'template_name' => $template['Template']['name'],
        'partner_name' => $template['Cobrand']['partner_name']
      )
    ); ?></h1>
  </div>

  <div class="container">
    <?php
    // TODO: use the Template->logo_position and include_axia_logo
    // to display this VVVV
    $partner_logo = $this->Html->image($template['Cobrand']['logo_url']);
    if ($template['Template']['include_axia_logo'] == true) {
      // only one way to display this combination
      echo String::insert(
        '<div class="row">' .
          '<div class="col-md-6">:partner_logo</div>' .
          '<div class="col-md-6">:axia_logo</div>' .
        '</div>',
        array(
          'partner_logo' => $partner_logo,
          'axia_logo' => $this->Html->image('/img/axia_logo.png', array('class' => 'pull-right'))
        )
      );
    } else {
      // position the logo left,center or right
      $logo_position = $template['Template']['logo_position'];
      if ($logo_position < 3) {
        echo String::insert(
          '<div class="row">' .
            '<div class="col-md-12 text-:position">:partner_logo</div>' .
          '</div>',
          array(
            'partner_logo' => $partner_logo,
            'position' => $logo_position_types[$logo_position]
          )
        );
      } // else don't show the logo
    }
    ?>
  </div>

  <br/>

  <div class="accordion">
    <div class="panel-group" id="page_accordion">
    <?php
      $is_admin = $this->Session->read('Auth.User.id') > 0;
      foreach ($template['TemplatePages'] as $page):
        $page_id = str_replace($bad_characters, '', $page['name']);
      ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#page_accordion" href="#<?php echo $page_id ?>">
              <?php echo $page['name'] ?>
            </a>
          </h4>
        </div>
        <div class="panel-body panel-collapse collapse" id="<?php echo $page_id ?>">
          <div class="accordion">
            <div class="panel-group" id="section_accordion">
              <div class="row">
                <?php
                $form_html = $this->Form->create($page['name']);
                echo preg_replace('/(id="[^"]*)"/', '\1" class="onlineapp_preview_page"', $form_html);
                foreach ($page['TemplateSections'] as $section):

                  if ($is_admin || $section['rep_only'] !== true) {

                    $section_id = str_replace($bad_characters, '', $section['name']);
                ?>

                  <div class="col-md-<?php echo $section['width']; ?>">

                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#section_accordion" href="#<?php echo $section_id ?>">
                            <?php echo $section['name']; ?>
                          </a>
                        </h4>
                      </div>
                      <div class="panel-body panel-collapse collapse" id="<?php echo $section_id ?>">
                        <div class="row">
                        <?php echo $this->Element('Templates/Pages/Sections/Fields/genericField',
                          array("fields" => $section['TemplateFields'], "bad_characters" => $bad_characters)); ?>
                        </div>
                      </div>
                    </div>

                  </div>
                <?php
                  }
                endforeach;
                ?><!-- end sections -->
                <?php echo $this->Form->end(array('label' => 'Update', 'class' => 'hidden')); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?><!-- end pages -->
    </div>
  </div>

  <script type="text/javascript">
    /* TODO: move this into a javascript file */
    $(document).on("ready", function() {
      // remove the cake css
      // TODO: use a different template that does not use the cake.generic.css file
      $.map(
        $('head link'),
        function(link, index) {
          if ($(link).attr('href') == '/css/cake.generic.css') {
            $(link).remove();
          }
        }
      );

      $(document).on("percentOptionBlur", handlePercentOptionBlur);
      function handlePercentOptionBlur(event) {
        var totalField = $(event.totalFieldId);
        var startingTotalValue = parseInt(totalField.val());
        var originatingField = $(event.origin);
        if (totalField.val() == "") {
          // stuff the new value value
          totalField.val(originatingField.val());
        } else {
          var newTotal = parseInt(startingTotalValue) + parseInt(originatingField.val());
          if (newTotal <= 100) {
            totalField.val(newTotal);
          } else {
            // start from the top of the fieldset and all sum the inputs
            // except for the originatingField
            var percentSum = 0;
            $("#"+event.fieldset_id).find("input").map(function(index, input) {
              var inputObj = $(input);
              if (!inputObj.is(':disabled') &&
                  inputObj.attr("id") != originatingField.attr("id")) {
                if (inputObj.val() != '') {
                  percentSum += parseInt(inputObj.val());
                }
              }
            });

            var newTotal = percentSum + parseInt(originatingField.val());
            if (newTotal <= 100) {
              // set it
              parseInt(originatingField.val());
              totalField.val(newTotal);
            } else {
              var maxOriginatingValue = 100 - percentSum;
              originatingField.val(maxOriginatingValue < 0 ? 0 : maxOriginatingValue);
              totalField.val(100);
            }
          }
        }
      }
    });
  </script>
</div>
