<script type="text/javascript">
    <?php
        echo "var map = {};";
        foreach ($templates as $key => $val) {
            echo "map['".$key."'] = '".$val."';";
        }
    ?>

    $(document).ready(function(){
        $("#TemplateBuilderBaseCobrand").prepend("<option value=''>Select Base Cobrand</option>").val('');
        $('#TemplateBuilderBaseTemplate')[0].options.length = 0;
        $("#TemplateBuilderBaseTemplate").prepend("<option value=''>Select Base Template</option>").val('');

        $("#TemplateBuilderBaseCobrand").on('change', function() {
            $('#TemplateBuilderBaseTemplate')[0].options.length = 0;
            $("#TemplateBuilderBaseTemplate").prepend("<option value=''>Select Base Template</option>").val('');

            value = $("#TemplateBuilderBaseCobrand").val();

            $.ajax({
                url: "/cobrands/get_template_ids/"+value,
                data: value,
                success: function(response){
                    if (response.length != 0) {
                        response = $.parseJSON(response)
                        $.each(response, function(key, val) {
                            name = map[val];
                            $('#TemplateBuilderBaseTemplate').append('<option value="'+val+'">'+name+'</option>');
                        });
                    }
                },
                cache: false
            });
        });
    });

    var checkCheckbox = function(arg) {
        $('#'+arg).prop('checked', true);
    };

    var checkAll = function() {   
        var checked = $("#check_all").is(":checked");
        var pattern = /^template_page.+/;

        $('input[type=checkbox]').each(function () {
            var id = $(this).attr('id');
            if (pattern.test(id)) {
                if (checked) {
                    $(this).prop('checked', true);
                }
                else {
                    $(this).prop('checked', false);
                }
            }
        });
    };

</script>

<?php

if (!empty($template) && $template) {
    echo "<div>";
        echo "<br><br>";
                echo $this->Form->create('TemplateBuilder',
                    array(
                        'url' => '/admin/template_builder/add',
                        'class' => 'form-inline'
                    )
                );

                echo $this->Form->input(
                    'new_template_cobrand_id',
                    array(
                        'options' => $cobrands,
                        'label' => 'Cobrand new template belongs to:',
                        'type' => 'select'
                    )
                );
                echo "<br><br>";

                echo "New Template:<br><br>";
                echo $this->Form->input('name', array('style' => 'width:500px; height:30px;'));
                echo $this->Form->input(
                    'logo_position',
                    array(
                        'options' => $logoPositionTypes,
                        'empty' => __('(choose one)')
                    )
                );
                echo $this->Form->input('include_brand_logo',
                    array(
                        'label' => 'Include Brand Logo',
                        'type' => 'checkbox'
                    )
                );
                echo $this->Form->input('description', array('style' => 'width:500px; height:30px;'));

                echo $this->Form->input('rightsignature_template_guid',
                    array(
                        'type' => 'select',
                        'label' => 'Rightsignature Template Guid',
                        'options' => $templateList,
                    )
                );

                echo $this->Form->input('rightsignature_install_template_guid',
                    array(
                        'type' => 'select',
                        'label' => 'Rightsignature Install Template Guid',
                        'options' => $installTemplateList,
                    )
                );

                echo $this->Form->input('owner_equity_threshold', array('style' => 'width:500px; height:30px;'));     

                echo "<table cellpadding='0' cellspacing='0' border='1'>";

                echo "<tr>";
                echo "<td colspan='4'>";
                echo $this->Form->input('check_all',
                    array(
                        'label' => 'CHECK ALL',
                        'type' => 'checkbox',
                        'id' => 'check_all',
                        'onclick' => 'checkAll();'
                    )
                );
                echo "</td>";
                echo "</tr>";

                echo "<th style='text-align:center'>Page/Section/Field</th>";
                echo "<th style='text-align:center'>Rep Only</th>";
                echo "<th style='text-align:center'>Required</th>";
                echo "<th style='text-align:center'>Default Value(s)</th>";

                foreach ($template['TemplatePages'] as $page) {
                    if ($page['name'] == 'Validate Application') {
                        continue;
                    }

                    echo "<tr>";
                        echo "<td>";
                            echo $this->Form->input(
                                "template_page_id_".$page['id'],
                                array(
                                    'label' => $page['name'],
                                    'type' => 'checkbox',
                                    'id' => 'template_page_id_'.$page['id']
                                )
                            );
                        echo "</td>";

                        $repOnly = $page['rep_only'] ? 'true' : 'false';

                        echo "<td>";
                            echo $this->Form->input(
                                "rep_only_template_page_id_".$page['id'],
                                array(
                                    'type' => 'radio',
                                    'legend' => false,
                                    'options' => array('true' => 'Yes ', 'false' => 'No'),
                                    'default' => $repOnly
                                )
                            );
                        echo "</td>";
                        echo "<td></td>";
                        echo "<td></td>";
                    echo "</tr>";

                    foreach ($page['TemplateSections'] as $section) {
                        echo "<tr>";
                            echo "<td style='padding-left: 3em;'>";
                                echo $this->Form->input(
                                    "template_page_id_".$page['id']."_section_id_".$section['id'],
                                    array(
                                        'label' => $section['name'],
                                        'type' => 'checkbox',
                                        'id' => 'template_page_id_'.$page['id'].'_section_id_'.$section['id'],
                                        'onclick' => 'checkCheckbox("template_page_id_'.$page['id'].'");'
                                    )
                                );
                            echo "</td>";

                            $repOnly = $section['rep_only'] ? 'true' : 'false';

                            echo "<td>";
                                echo $this->Form->input(
                                    "rep_only_template_page_id_".$page['id']."_section_id_".$section['id'],
                                    array(
                                        'type' => 'radio',
                                        'legend' => false,
                                        'options' => array('true' => 'Yes ', 'false' => 'No'),
                                        'default' => $repOnly
                                    )
                                );
                            echo "</td>";
                            echo "<td></td>";
                            echo "<td></td>";
                        echo "</tr>";

                        foreach ($section['TemplateFields'] as $field) {
                            echo "<tr>";
                                echo "<td style='padding-left: 6em;'>";
                                    echo $this->Form->input(
                                        "template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
                                        array(
                                            'label' => $field['name'],
                                            'type' => 'checkbox',
                                            'id' => 'template_page_id_'.$page['id'].'_section_id_'.$section['id'].'_field_id_'.$field['id'],
                                            'onclick' => 'checkCheckbox("template_page_id_'.$page['id'].'");checkCheckbox("template_page_id_'.$page['id'].'_section_id_'.$section['id'].'");'
                                        )
                                    );
                                echo "</td>";

                                $repOnly = $field['rep_only'] ? 'true' : 'false';

                                echo "<td>";
                                    echo $this->Form->input(
                                        "rep_only_template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
                                        array(
                                            'type' => 'radio',
                                            'legend' => false,
                                            'options' => array('true' => 'Yes ', 'false' => 'No'),
                                            'default' => $repOnly
                                        )
                                    );
                                echo "</td>";

                                $required = $field['required'] ? 'true' : 'false';

                                echo "<td>";
                                    echo $this->Form->input(
                                        "required_template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
                                        array(
                                            'type' => 'radio',
                                            'legend' => false,
                                            'options' => array('true' => 'Yes ', 'false' => 'No'),
                                            'default' => $required
                                        )
                                    );
                                echo "</td>";

                                echo "<td>";
                                    echo $this->Form->input(
                                        "default_template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
                                        array(
                                            'type' => 'textarea',
                                            'label' => false,
                                            'default' => $field['default_value']
                                        )
                                    );
                                echo "</td>";

                            echo "</tr>";
                        }
                    }

                    echo "<br>";
                }

                echo "</table>";
                echo "<br>";
        
            echo $this->Form->end('Submit');
    echo "</div>";
}
else {
    echo "<div class='cobrandedApplications form'>";
    echo $this->Form->create('TemplateBuilder');
        echo "<fieldset>";
            echo "<legend>Choose Base Template</legend>";
            echo $this->Form->input(
                'base_cobrand',
                array(
                    'options' => $cobrands,
                    'label' => false,
                    'type' => 'select'
                )
            );
            echo "<br>";

            echo $this->Form->input(
                'base_template',
                array(
                    'options' => $templates,
                    'label' => false,
                    'type' => 'select'
                )
            );
            echo "<br>";
        echo "</fieldset>";
        echo $this->Form->end('Submit');
    echo "</div>";
}
?>