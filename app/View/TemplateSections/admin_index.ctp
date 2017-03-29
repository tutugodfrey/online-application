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

<div class="templateSections panel panel-default index">
	<div class="panel-heading">
		<strong>
			<?php 
			echo __('Sections for: ') . CakeText::insert(__(
				':partner_name > :template_name > :template_page_name'),
				array(
					"partner_name" => $cobrand["partner_name"],
					"template_name" => $template["name"],
					"template_page_name" => $templatePage["name"],
				)
			);
			?>
		</strong>
	</div>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('order'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('width'); ?></th>
			<th><?php echo $this->Paginator->sort('rep_only'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php foreach ($templateSections as $templateSection): ?>
		<tr>
			<td><?php echo h($templateSection['TemplateSection']['order']); ?>&nbsp;</td>
			<td><?php echo $this->Html->tag('div', $templateSection['TemplateSection']['name'], array('class' => 'help', 'title' => strlen($templateSection['TemplateSection']['description']) > 0 ? $templateSection['TemplateSection']['description'] : 'No description saved')); ?>&nbsp;</td>
			<td><?php echo h($templateSection['TemplateSection']['width']); ?>&nbsp;</td>

			<td><?php echo $templateSection['TemplateSection']['rep_only'] === true ? $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-ok', 'title' => 'Only the rep sees this section')) : ''; ?>&nbsp;</td>
			<td><?php echo h($templateSection['TemplateSection']['created']); ?>&nbsp;</td>
			<td><?php echo h($templateSection['TemplateSection']['modified']); ?>&nbsp;</td>
			<td class="actions">
				<?php echo $this->Html->link(__('Edit'), CakeText::insert($list_url . '/edit/:template_section_id', array('template_section_id' => $templateSection['TemplateSection']['id']))); ?>
				<?php echo $this->Form->postLink(__('Delete'), CakeText::insert($list_url . '/delete/:id', array('id' => $templateSection['TemplateSection']['id'])), null, __('Are you sure you want to delete # %s?', $templateSection['TemplateSection']['id'])); ?>
				<?php echo $this->Html->link(__('List Fields'), CakeText::insert('/admin/templatesections/:template_section_id/templatefields', array('template_section_id' => $templateSection['TemplateSection']['id']))); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this->Element('paginatorBottomNav'); ?>
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
				<li>
					<?php echo $this->Html->link(__('New Template Section'), $list_url . '/add/'); ?>
				</li>
			</ul>
		</div>
	</div>
</div>
