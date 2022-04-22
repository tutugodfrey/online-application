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
<div class="container-fluid">
  <div class="row">
  	<?php
  	$elVars = array(
  			'navLinks' => array(
  				'Add New Template Page' => "$list_url/add"
			)
  		);
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
		<div class="panel-heading">
			<u>
				<strong>
					<?php echo __('Pages for: ') . CakeText::insert(__(':partner_name > :template_name'),
					array(
						"partner_name" => $cobrand["partner_name"],
						"template_name" => $template["name"],
					)
				); ?>
				</strong>
			</u>
		</div>
			<table class="table table-condensed table-striped table-hover">
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
						<?php echo $this->Html->link($this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-pencil')),
						CakeText::insert($list_url . '/edit/:id', array('id' => $templatePage['TemplatePage']['id'])),
						array('escape' => false)); ?>
						<?php echo $this->Form->postLink($this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-remove text-danger')),
						CakeText::insert($list_url . '/delete/:id', array('id' => $templatePage['TemplatePage']['id'])), array('escape' => false), 
						__('Are you sure you want to delete # %s?', $templatePage['TemplatePage']['id'])); ?>
						<?php echo $this->Html->link(__('List Sections'), CakeText::insert('/admin/templatepages/:template_page_id/templatesections', array('template_page_id' => $templatePage['TemplatePage']['id']))); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php echo $this->Element('paginatorBottomNav'); ?>
		</div>
	</div>
  </div>
</div>