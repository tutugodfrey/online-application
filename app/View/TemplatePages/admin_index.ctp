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

<div class="templatePages index">
	<h2><?php echo __('Pages for:'); ?></h2>
	<?php
		echo CakeText::insert(__(
			':partner_name > :template_name'),
			array(
				"partner_name" => $cobrand["partner_name"],
				"template_name" => $template["name"],
			)
		);
	?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('order'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('rep_only'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions">Actions</th>
		</tr>
		<?php foreach ($templatePages as $templatePage): ?>
		<tr>
			<td><?php echo h($templatePage['TemplatePage']['order']); ?>&nbsp;</td>
			<td><?php echo $this->Html->tag('div', $templatePage['TemplatePage']['name'], array('class' => 'help', 'title' => strlen($templatePage['TemplatePage']['description']) > 0 ? $templatePage['TemplatePage']['description'] : 'No description saved')); ?>&nbsp;</td>
			<td><?php echo $templatePage['TemplatePage']['rep_only'] === true ? $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-ok', 'title' => 'Only the rep sees this page')) : ''; ?>&nbsp;</td>
			<td><?php echo h($templatePage['TemplatePage']['created']); ?>&nbsp;</td>
			<td><?php echo h($templatePage['TemplatePage']['modified']); ?>&nbsp;</td>
			<td class="actions">
				<?php echo $this->Html->link(__('Edit'), CakeText::insert($list_url . '/edit/:id', array('id' => $templatePage['TemplatePage']['id']))); ?>
				<?php echo $this->Form->postLink(__('Delete'), CakeText::insert($list_url . '/delete/:id', array('id' => $templatePage['TemplatePage']['id'])), null, __('Are you sure you want to delete # %s?', $templatePage['TemplatePage']['id'])); ?>
				<?php echo $this->Html->link(__('List Sections'), CakeText::insert('/admin/templatepages/:template_page_id/templatesections', array('template_page_id' => $templatePage['TemplatePage']['id']))); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this->Element('paginatorBottomNav'); ?>
</div>
<div class="actions">
	<h3>Actions</h3>
	<ul>
		<li>
			<?php echo $this->Html->link(__('New Template Page'), $list_url . "/add"); ?>
		</li>
	</ul>
</div>
