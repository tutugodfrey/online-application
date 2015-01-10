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
</script>

<?php
if ($template) {
    echo "<div class='template builder form'>";
            echo $this->Form->create('TemplateBuilder', array('url' => '/template_builder/add'));
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
                echo $this->Form->input('name');
                echo $this->Form->input(
                    'logo_position',
                    array(
                        'options' => $logoPositionTypes,
                        'empty' => __('(choose one)')
                    )
                );
                echo $this->Form->input('include_axia_logo',
                    array(
                        'label' => 'rep only',
                        'type' => 'checkbox'
                    )
                );
                echo $this->Form->input('description');
                echo $this->Form->input('rightsignature_template_guid');
                echo $this->Form->input('rightsignature_install_template_guid');
                echo $this->Form->input('owner_equity_threshold');
                echo "<br><br>";

                foreach ($template['TemplatePages'] as $page) {
                    echo $this->Form->input(
                        "template_page_id_".$page['id'],
                        array(
                            'label' => $page['name'],
                            'type' => 'checkbox'
                        )
                    );
                   // echo $this->Form->input(
                    //    "template_page_rep_only_".$page['id'],
                   //     array(
                   //         'label' => 'rep only',
                   //         'type' => 'checkbox'
                   //     )
                    //);
                    echo "<br>";

                    foreach ($page['TemplateSections'] as $section) {
                        echo $this->Form->input(
                            "template_page_id_".$page['id']."_section_id_".$section['id'],
                            array(
                                'label' => $section['name'],
                                'type' => 'checkbox'
                            )
                        );
                        echo "<br>";

                        foreach ($section['TemplateFields'] as $field) {
                            echo $this->Form->input(
                                "template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
                                array(
                                    'label' => $field['name'],
                                    'type' => 'checkbox'
                                )
                            );
                            echo "<br>";
                        }
                    }
                }
        
            echo $this->Form->end('Submit');
    echo "</div>";
}
else {
    echo "<div class='template builder form'>";
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