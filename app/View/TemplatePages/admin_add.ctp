<?php
$this->Html->addCrumb(__('Cobrands'), '/admin/cobrands');
$this->Html->addCrumb(
	__('Templates'),
	CakeText::insert(
		'/admin/cobrands/:cobrand_id/templates',
		array('cobrand_id' => $cobrand['id'])
	)
);
?>

<div class="templatePages form panel panel-default">
	<div class="panel-heading">
		<strong>
			<?php echo CakeText::insert(__('Add Template Page for ":template_name" '), array("template_name" => $template['name'])); ?>
		</strong>
	</div>
	<div class="panel-body">
	<?php 
		echo $this->Form->create('TemplatePage'); 
		echo $this->Form->input('name');
		echo $this->Form->input('rep_only');
		echo $this->Form->input('description');
		echo $this->Form->end(array('label' => __('Submit'), 'div' => false, 'class' => 'btn btn-sm btn-success')); 
	?>
</div>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
				<li><?php echo $this->Html->link(__('Cancel'), $list_url); ?> </li>
			</ul>
		</div>
	</div>
</div>
