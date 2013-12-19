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
?>

<div class="templateSections index">
	<h2><?php echo String::insert(__('Template Sections for ":tempalte_page_name"'), array("tempalte_page_name" => $templatePage["name"])); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('order'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($templateSections as $templateSection): ?>
	<tr>
		<td><?php echo h($templateSection['TemplateSection']['order']); ?>&nbsp;</td>
		<td><?php echo h($templateSection['TemplateSection']['name']); ?>&nbsp;</td>
		<td><?php echo h($templateSection['TemplateSection']['description']); ?>&nbsp;</td>
		<td><?php echo h($templateSection['TemplateSection']['created']); ?>&nbsp;</td>
		<td><?php echo h($templateSection['TemplateSection']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), String::insert($list_url . '/edit/:template_section_id', array('template_section_id' => $templateSection['TemplateSection']['id']))); ?>
			<?php echo $this->Form->postLink(__('Delete'), String::insert($list_url . '/delete/:id', array('id' => $templateSection['TemplateSection']['id'])), null, __('Are you sure you want to delete # %s?', $templateSection['TemplateSection']['id'])); ?>
			<?php echo $this->Html->link(__('List Fields'), String::insert('/admin/templatesections/:template_section_id/templatefields', array('template_section_id' => $templateSection['TemplateSection']['id']))); ?>
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
			<?php echo $this->Html->link(__('New Template Section'), $list_url . '/add/'); ?>
		</li>
	</ul>
</div>
