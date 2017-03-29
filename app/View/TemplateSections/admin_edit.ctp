<?php
$this->Html->addCrumb(__('Cobrands'), '/admin/cobrands');
$this->Html->addCrumb(
	__('Templates'),
	CakeText::insert(
		'/admin/cobrands/:cobrand_id/templates',
		array('cobrand_id' => $cobrand['id'])
	)
);
$this->Html->addCrumb(
	__('Pages'),
	CakeText::insert(
		'/admin/templates/:template_id/templatepages',
		array('template_id' => $template['id'])
	)
);
?>
<div class="templateSections form panel panel-default">
	<div class="panel-heading">
		<strong>
			<?php echo CakeText::insert(__('Edit Template Section for ":template_page_name" '), array("template_page_name" => $templatePage['name'])); ?>
		</strong>
	</div>
	<div class="panel-body">
		<?php 
		echo $this->Form->create('TemplateSection');
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('width', array('min' => 1, 'max' => 12));
		echo $this->Form->input('rep_only');
		echo $this->Form->input('order', array('min' => 0));
		echo $this->Form->input('description');
		echo $this->Form->hidden('page_id');
	 	echo $this->Form->end(array('label' => 'Submit', 'div' => false, 'class' => 'btn btn-sm btn-success')); 
	 	?>
	</div>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
				<li><?php echo $this->Html->link(__('Cancel'), $list_url); ?></li>
			</ul>
		</div>
	</div>
</div>
