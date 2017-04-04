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
$this->Html->addCrumb(
	__('Sections'),
	CakeText::insert(
		'/admin/templatepages/:template_page_id/templatesections',
		array('template_page_id' => $templatePage['id'])
	)
);
?>
<div class="container-fluid">
  <div class="row">
  	<?php
  	$elVars = array(
  			'navLinks' => array(
  				'Add New Field' => "$list_url/add"
			)
  		);
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>
					<?php echo __('Fields for: ') . CakeText::insert(__(
						':partner_name > :template_name > :template_page_name > :template_section_name'),
						array(
							"partner_name" => $cobrand["partner_name"],
							"template_name" => $template["name"],
							"template_page_name" => $templatePage["name"],
							"template_section_name" => $templateSection["name"],
						)
					); ?>
				</strong>
			</div>
			<table class="table table-condensed table-striped table-hover">
				<tr>
					<th><?php echo $this->Paginator->sort('order'); ?></th>
					<th><?php echo $this->Paginator->sort('name'); ?></th>
					<th><?php echo $this->Paginator->sort('width'); ?></th>
					<th><?php echo $this->Paginator->sort('rep_only'); ?></th>
					<th><?php echo $this->Paginator->sort('type'); ?></th>
					<th><?php echo $this->Paginator->sort('required'); ?></th>
					<th><?php echo $this->Paginator->sort('source'); ?></th>
					<th><?php echo $this->Paginator->sort('default_value'); ?></th>
					<th><?php echo $this->Paginator->sort('merge_field_name'); ?></th>
					<th><?php echo $this->Paginator->sort('encrypt'); ?></th>
					<th><?php echo $this->Paginator->sort('created'); ?></th>
					<th><?php echo $this->Paginator->sort('modified'); ?></th>
					<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				<?php foreach ($templateFields as $templateField): ?>
				<tr>
					<td><?php echo h($templateField['TemplateField']['order']); ?>&nbsp;</td>
					<td><?php echo $this->Html->tag('div', $templateField['TemplateField']['name'], array('class' => 'help', 'title' => strlen($templateField['TemplateField']['description']) > 0 ? $templateField['TemplateField']['description'] : 'No description saved')); ?>&nbsp;</td>
					<td><?php echo h($templateField['TemplateField']['width']); ?>&nbsp;</td>
					<td><?php echo $templateField['TemplateField']['rep_only'] === true ? $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-ok', 'title' => 'Only the rep sees this field')) : ''; ?>&nbsp;</td>
					<td><?php echo h($fieldTypes[$templateField['TemplateField']['type']]); ?>&nbsp;</td>
					<td><?php echo $templateField['TemplateField']['required'] == 1 ? $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-ok', 'title' => 'required')) : ''; ?>&nbsp;</td>
					<td><?php echo h($sourceTypes[$templateField['TemplateField']['source']]); ?>&nbsp;</td>
					<td title="<?php echo $templateField['TemplateField']['default_value']; ?>"><?php echo CakeText::truncate($templateField['TemplateField']['default_value'], 10); ?>&nbsp;</td>
					<td><?php echo h($templateField['TemplateField']['merge_field_name']); ?>&nbsp;</td>
					<td><?php echo $templateField['TemplateField']['encrypt'] == 1 ? $this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-ok', 'title' => 'encrypted')) : ''; ?>&nbsp;</td>
					<td><?php echo h($templateField['TemplateField']['created']); ?>&nbsp;</td>
					<td><?php echo h($templateField['TemplateField']['modified']); ?>&nbsp;</td>
					<td class="actions">
						<?php echo $this->Html->link(__('Edit'), CakeText::insert($list_url . '/edit/:template_section_id', array('template_section_id' => $templateField['TemplateField']['id']))); ?>
						<?php echo $this->Form->postLink(__('Delete'), CakeText::insert($list_url . '/delete/:id', array('id' => $templateField['TemplateField']['id'])), null, __('Are you sure you want to delete # %s?', $templateField['TemplateField']['id'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php echo $this->Element('paginatorBottomNav'); ?>
		</div>
	</div>
  </div>
</div>