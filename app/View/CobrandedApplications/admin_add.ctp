
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
		jQuery('input[type=submit]').click(function() {
			jQuery('#CobrandedApplicationAdminAddForm').append('<input type="hidden" name="'+this.name+'" value="'+this.name+'" />');
			jQuery(this).attr('disabled', 'disabled');
			jQuery('#CobrandedApplicationAdminAddForm').submit();
		})
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
		if ($applicationId) {
			echo $this->Form->hidden('applicationId');
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		<div class="panel-body">
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
			</div>
	</div>
</div>
