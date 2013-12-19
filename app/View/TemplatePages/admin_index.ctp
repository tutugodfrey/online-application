<?php
$this->Html->addCrumb(__('Cobrands'), '/admin/cobrands');
$this->Html->addCrumb(
	__('Templates'),
	String::insert(
		'/admin/cobrands/:cobrand_id/templates',
		array('cobrand_id' => $cobrand['id'])
	)
);
?>

<div class="templatePages index">
	<h2><?php echo String::insert(__("Template Pages for \":template_name\""), array("template_name" => $template['name'])); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('order'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions">Actions</th>
	</tr>
	<?php foreach ($templatePages as $templatePage): ?>
	<tr>
		<td><?php echo h($templatePage['TemplatePage']['order']); ?>&nbsp;</td>
		<td><?php echo h($templatePage['TemplatePage']['name']); ?>&nbsp;</td>
		<td><?php echo h($templatePage['TemplatePage']['description']); ?>&nbsp;</td>
		<td><?php echo h($templatePage['TemplatePage']['created']); ?>&nbsp;</td>
		<td><?php echo h($templatePage['TemplatePage']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), String::insert($list_url . '/edit/:id', array('id' => $templatePage['TemplatePage']['id']))); ?>
			<?php echo $this->Form->postLink(__('Delete'), String::insert($list_url . '/delete/:id', array('id' => $templatePage['TemplatePage']['id'])), null, __('Are you sure you want to delete # %s?', $templatePage['TemplatePage']['id'])); ?>
			<?php echo $this->Html->link(__('List Sections'), String::insert('/admin/templatepages/:template_page_id/templatesections', array('template_page_id' => $templatePage['TemplatePage']['id']))); ?>
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
	<h3>Actions</h3>
	<ul>
		<li>
			<?php echo $this->Html->link(__('New Template Page'), $list_url . "/add"); ?>
		</li>
	</ul>
</div>
