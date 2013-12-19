<?php
$this->Html->addCrumb(__('Cobrands'), '/admin/cobrands');
$this->Html->addCrumb(
	__('Templates'),
	String::insert(
		'/admin/cobrands/:cobrand_id/templates',
		array('cobrand_id' => $cobrand['id'])
	)
);
$this->Html->addCrumb(
	__('Pages'),
	String::insert(
		'/admin/templates/:template_id/templatepages',
		array('template_id' => $template['id'])
	)
);
$this->Html->addCrumb(
	__('Sections'),
	String::insert(
		'/admin/templatepages/:template_page_id/templatesections',
		array('template_page_id' => $templatePage['id'])
	)
);
?>

<div class="TemplateFields index">
	<h2><?php echo String::insert(__('Template Fields for ":tempalte_section_name"'), array("tempalte_section_name" => $templateSection["name"])); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('order'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('type'); ?></th>
			<th><?php echo $this->Paginator->sort('required'); ?></th>
			<th><?php echo $this->Paginator->sort('source'); ?></th>
			<th><?php echo $this->Paginator->sort('default_value'); ?></th>
			<th><?php echo $this->Paginator->sort('merge_field_name'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($templateFields as $templateField): ?>
	<tr>
		<td><?php echo h($templateField['TemplateField']['order']); ?>&nbsp;</td>
		<td><?php echo h($templateField['TemplateField']['name']); ?>&nbsp;</td>
		<td><?php echo h($templateField['TemplateField']['description']); ?>&nbsp;</td>
		<td><?php echo h($field_types[$templateField['TemplateField']['type']]); ?>&nbsp;</td>
		<td><?php echo h($templateField['TemplateField']['required']); ?>&nbsp;</td>
		<td><?php echo h($source_types[$templateField['TemplateField']['source']]); ?>&nbsp;</td>
		<td><?php echo h($templateField['TemplateField']['default_value']); ?>&nbsp;</td>
		<td><?php echo h($templateField['TemplateField']['merge_field_name']); ?>&nbsp;</td>
		<td><?php echo h($templateField['TemplateField']['created']); ?>&nbsp;</td>
		<td><?php echo h($templateField['TemplateField']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), String::insert($list_url . '/edit/:template_section_id', array('template_section_id' => $templateField['TemplateField']['id']))); ?>
			<?php echo $this->Form->postLink(__('Delete'), String::insert($list_url . '/delete/:id', array('id' => $templateField['TemplateField']['id'])), null, __('Are you sure you want to delete # %s?', $templateField['TemplateField']['id'])); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li>
			<?php echo $this->Html->link(__('New Template Field'), $list_url . '/add/'); ?>
		</li>
	</ul>
</div>
