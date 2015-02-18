<script type="text/javascript">

    $(document).ready(function(){

        var cobrandPattern = /Cobrand/;
        var templatePattern = /Template/;

        $('input[type=checkbox]').each(function () {
            var id = $(this).attr('id');
            if (templatePattern.test(id)) {
                $('label[for="'+id+'"]').hide();
            }
        });

        $('input[type=checkbox]').each(function() {
            value = $(this).attr('value');
            id = $(this).attr('id');
            checked = $(this).is(":checked");

            if (cobrandPattern.test(id)) {
                if (checked) {
                    $.ajax({
                        url: "/cobrands/get_template_ids/"+value,
                        data: value,
                        success: function(response){
                            if (response.length != 0) {
                                response = $.parseJSON(response)
                                $.each(response, function(key, val) {
                                    $('label[for="TemplateTemplate'+val+'"]').show();
                                });
                            }
                        },
                        cache: false
                    });
                }
            }
        });

        $(document).on("click", "input[type='checkbox']", function(){
            value = $(this).attr('value');
            id = $(this).attr('id');
            checked = $(this).is(":checked");

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
                                    $('#TemplateTemplate'+val).prop('checked', false);
                                    $('label[for="TemplateTemplate'+val+'"]').hide();
                                    $('#UserTemplateId option[value="'+val+'"]').remove();
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

<h1>Edit User</h1>
<div class="users form">
    <table cellpadding="0" cellspacing="0">
        <tr>
<?php
echo $this->Form->create('User', array('action' => 'edit', 'novalidate' => true));
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('email');
echo $this->Form->input('pwd', array('label'=> 'Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
echo $this->Form->input('password_confirm', array('type' => 'password', 'value'=>'', 'autocomplete'=>'off'));
echo $this->Form->input('firstname');
echo $this->Form->input('lastname');
echo $this->Form->input('group_id');
echo $this->Form->input('extension');
echo $this->Form->input('api_enabled', array('label'=> 'Enable API','type'=>'checkbox'));        
echo $this->Form->input('api_password', array('label'=> 'Api Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
echo $this->Form->input('active', array('type' => 'checkbox'));
echo $this->Form->input('Manager', array('label' => 'Select Manager(s)', 'multiple' => 'checkbox'));
if ($this->request->data['User']['group_id'] == User::MANAGER_GROUP_ID){?>
    <br/>Select Rep(s)<br/>
 
            
<?php
echo $this->Form->input('AssignedRepresentative',array('label' => false,'multiple' => 'checkbox'));
}?>
        
<?php
if ($this->request->data['User']['api_enabled']) {
echo $this->Form->input('token');
echo $this->Form->input('token_used');
echo $this->Form->input('token_uses');
}

echo "<br>";
echo $this->Form->input('Cobrand', array('label' => 'Select Cobrand(s)', 'multiple' => 'checkbox'));
echo "<br>";
echo $this->Form->input('Template', array('label' => 'Select Template(s)', 'multiple' => 'checkbox'));
echo "<br>";
echo $this->Form->input(
    'User.template_id',
    array(
        'options' => $userTemplates,
        'label' => 'Select Default Template',
        'type' => 'select'
    )
);
echo "<br>";

echo $this->Form->end('Save User');
?>
            </tr>
    </table>
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link('List Users', array('action' => 'index', 'admin' => true)); ?></li>
        <li><?php echo $this->Html->link('List Applications', array('controller' => 'applications', 'action' => 'index', 'admin' => true)); ?></li>
        <li><?php echo $this->Html->link('List Settings', array('controller' => 'settings', 'action' => 'index', 'admin' => true)); ?></li>
        <li><?php echo $this->Html->link('List IP Restrictions', array('controller' => 'apips', 'action' => 'index', 'admin' => true)); ?></li>
        <li><?php echo $this->Html->link('List Groups', array('controller' => 'groups', 'action' => 'index', 'admin' => true)); ?></li>
        <? echo $this->Element('users/search'); ?>
	</ul>
</div>
