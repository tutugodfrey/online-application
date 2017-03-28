<div class="cobrandedApplications form">
<?php echo $this->Form->create('CobrandedApplication'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Application'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('uuid', array('type' => 'hidden'));
		echo $this->Form->input('template_id', array('type' => 'hidden'));
		echo $this->Form->input('user_id');
		echo $this->Form->input('status',
                	array(
                        	'options' => array(
                                	'saved'=>'saved',
                                	'validate'=>'validate',
                                	'completed'=>'completed',
                                	'pending'=>'pending',
                                	'signed'=>'signed'
				),
                        'empty' => 'Show All'
			)
		);
		echo $this->Form->input('rightsignature_document_guid');
		echo $this->Form->input('rightsignature_install_document_guid');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
	<ul>
		<li>
			<?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?>
		</li>
	</ul>
			</div>
	</div>
</div>
