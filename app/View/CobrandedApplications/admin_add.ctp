
<script type="text/javascript">
	$(document).ready(function(){
		$("#CobrandedApplicationUserId").change(function(){
        	userId=$(this).val();
			$.ajax({
				url: "/users/get_user_templates/"+userId,
				data: userId,
				success: function(result){
					$("#CobrandedApplicationTemplateId").html(result);
				},
				cache: false
			});
    	});
	});
</script>

<div class="cobrandedApplications form">
<?php echo $this->Form->create('CobrandedApplication'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Application'); ?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('template_id', array('default' => $defaultTemplateId));
		echo $this->Form->hidden('uuid');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
</div>
