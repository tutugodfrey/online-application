<?php $this->Html->addCrumb(__('Cobrand'), '/admin/cobrands'); ?>

<div class="templates form  panel panel-default">
	<div class="panel-heading">
		<strong>
			<?php echo CakeText::insert(__('Edit Template for ":cobrand_name"'), array('cobrand_name' => $cobrand['Cobrand']['partner_name'])); ?>
		</strong>
	</div>
	<div class="panel-body">
<?php echo $this->Form->create('Template', array('class' => 'form-inline')); ?>
	<?php
		echo $this->element('Templates/templateFields');
		// the cobrand_id is injected if we cannot tell what it(cobrand_id) is from the route
		if ($this->request->params['parent_controller_id'] == null) {
			echo $this->Form->input('cobrand_id');
		}
	?>
<?php echo $this->Form->end(array('label' => __('Submit'), 'div' => false, 'class' => 'btn btn-sm btn-success')); ?>
</div>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
				<li><?php echo $this->Html->link(__('Cancel'), CakeText::insert('/admin/cobrands/:cobrand_id/templates', array('cobrand_id' => $cobrand['Cobrand']['id']))); ?></li>
			</ul>
		</div>
	</div>
</div>
