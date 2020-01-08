<div class="container-fluid">
  <div class="row">

	<div class="col-md-12">
	  <!-- view page content -->
		<div class="panel panel-default">
			<div class="panel-heading"><u><strong><?php echo __('Admin Add Application')?></strong></u></div>
			<div class="panel-body">
				<?php 
					if (empty($this->request->params['pass'])) : ?>
						<div class="alert alert-info small">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							If data from this needs to sync between SalesForce and the DB, please create from SF.
						</div>
				<?php endif; ?>
				<?php
					echo $this->Form->create('CobrandedApplication', array(
							'inputDefaults' => array(
								'div' => 'form-group col-md-12',
								'label' => array('class' => 'col-md-3 control-label'),
								'wrapInput' => 'col-md-5',
								'class' => 'form-control input-sm',
							),
							'class' => 'form-horizontal',
						)); 
					echo $this->Form->input('user_id', array('id' => 'appCopyUserIdDropDown'));
					echo $this->Form->input('template_id', array('default' => $defaultTemplateId));
					echo $this->Form->hidden('uuid');
					if (isset($applicationId)) {
						echo $this->Form->hidden('applicationId');
					}
					echo $this->Html->link('Cancel', array('controller' => 'CobrandedApplications', 'action' => 'index'), array('class' => 'btn btn-sm btn-danger col-md-offset-4 col-sm-offset-4'));
					echo $this->Form->end(array('label' => __('Submit'), 'div' => false, 'class' => 'btn btn-sm btn-success', 'id' => 'appCopySubmitBtn'));
				?>
			</div>
		</div>
	</div>
  </div>
</div>
<script type="text/javascript">
	jQuery("#appCopyUserIdDropDown").change(function(){
    	userId=jQuery(this).val();
		jQuery.ajax({
			url: "/users/get_user_templates/"+userId,
			data: userId,
			success: function(result){
				jQuery("#CobrandedApplicationTemplateId").html(result);
			},
			cache: false
		});
	});
	jQuery('#appCopySubmitBtn').click(function() {
		jQuery('#CobrandedApplicationAdminAddForm').append('<input type="hidden" name="'+this.name+'" value="'+this.name+'" />');
		jQuery(this).attr('disabled', 'disabled');
		jQuery('#CobrandedApplicationAdminAddForm').submit();
	});
</script>