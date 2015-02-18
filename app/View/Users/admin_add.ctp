<script type="text/javascript">

    $(document).ready(function(){

        $('input[type=checkbox]').each(function () {
            var id = $(this).attr('id');
            var pattern = /Template/;
            if (pattern.test(id)) {
                $('label[for="'+id+'"]').hide();
            }
        });

        $('#UserTemplateId')[0].options.length = 0;

        $(document).on("click", "input[type='checkbox']", function(){
            value = $(this).attr('value');
            id = $(this).attr('id');
            checked = $(this).is(":checked");
    
            var cobrandPattern = /Cobrand/;
            var templatePattern = /Template/;

            if (cobrandPattern.test(id)) {
                $.ajax({
                    url: "/cobrands/get_template_ids/"+value,
                    data: value,
                    success: function(response){
                        if (response.length != 0) {
                            response = $.parseJSON(response)
                            $.each(response, function(key, val) {
                                if (checked) {
                                    $('label[for="TemplateTemplate'+val+'"]').show();
                                } else {
                                    $('label[for="TemplateTemplate'+val+'"]').hide();
                                }
                            });
                        }
                    },
                    cache: false
                });
            }

            if (templatePattern.test(id)) {
                var labelText = $('label[for="TemplateTemplate'+value+'"]').text();
                if (checked) {
                    $('#UserTemplateId').append('<option value="'+value+'">'+labelText+'</option>');
                } else {
                    $('#UserTemplateId option[value="'+value+'"]').remove();
                }
                
            }
        });
    });

</script>

<div class="users form">
    <?php echo $this->Form->create('User');?>
    <fieldset>
        <legend>Add User</legend>
        <?php
        echo $this->Form->input('firstname', array('type' => 'text', 'required' => true));
        echo $this->Form->input('lastname', array('type' => 'text', 'required' => true));
        echo $this->Form->input('email');
        //echo $this->Form->input('password');
        echo $this->Form->input('pwd', array('label'=> 'Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
        echo $this->Form->input('password_confirm', array('label'=> 'Password Confirmation','type' => 'password', 'value'=>'', 'autocomplete'=>'off'));
        echo $this->Form->input('api', array('label'=> 'Enable API','type'=>'checkbox'));        
        echo $this->Form->input('api_password', array('label'=> 'Api Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
        echo $this->Form->input('group_id');
        echo $this->Form->input('Manager', array('label' => 'Select Manager(s)', 'multiple' => 'checkbox'));

        echo "<br>";
        echo $this->Form->input('Cobrand', array('label' => 'Select Cobrand(s)', 'multiple' => 'checkbox'));
        echo "<br>";
        echo $this->Form->input('Template', array('label' => 'Select Template(s)', 'multiple' => 'checkbox'));
        echo "<br>";
        echo $this->Form->input(
            'User.template_id',
            array(
                'options' => $templates,
                'label' => 'Select Default Template',
                'type' => 'select'
            )
        );
        echo "<br>";

        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>