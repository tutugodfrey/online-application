<div class="templateSections form">
<?php echo $this->Form->create('TemplateSection'); ?>
	<fieldset>
		<legend><?php echo String::insert(__('Edit Template Section for ":template_page_name" '), array("template_page_name" => $templatePage['name'])); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), $list_url); ?></li>
	</ul>
</div>
