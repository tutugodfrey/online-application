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
<div class="container-fluid">
  <div class="row">
  	<?php
  	$elVars = array(
  			'navLinks' => array(
  				'Add New Template Section' => "$list_url/add"
			)
  		);
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
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
			<table class="table table-condensed table-striped table-hover">
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
						<?php echo $this->Html->link($this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-pencil')),
						CakeText::insert($list_url . '/edit/:template_section_id', array('template_section_id' => $templateSection['TemplateSection']['id'])), array('escape' => false)); ?>
						<?php echo $this->Form->postLink($this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-remove text-danger')),
							CakeText::insert($list_url . '/delete/:id', array('id' => $templateSection['TemplateSection']['id'])), array('escape' => false), __('Are you sure you want to delete # %s?', $templateSection['TemplateSection']['id'])); ?>
						<?php echo $this->Html->link(__('List Fields'), CakeText::insert('/admin/templatesections/:template_section_id/templatefields', array('template_section_id' => $templateSection['TemplateSection']['id']))); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php echo $this->Element('paginatorBottomNav'); ?>
		</div>
	</div>
  </div>
</div>