<?php $this->Html->addCrumb(__('Cobrand'), '/admin/cobrands'); ?>

<div class="container-fluid">
  <div class="row">
  	<?php
  	//Overwrite current url default for proper left nav functionality
  	$this->here = $list_url;
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
		<div class="panel-heading">
			<strong>
			<?php echo __('Templates for: ') . 
			CakeText::insert(__(':partner_name'), array("partner_name" => $cobrand['Cobrand']['partner_name'],));?>
			</strong>
		</div>
			<table class="table table-condensed table-striped table-hover">
				<tr>
					<th><?php echo $this->Paginator->sort('name'); ?></th>
					<th><?php echo $this->Paginator->sort('logo_position'); ?></th>
					<th><?php echo $this->Paginator->sort('include_brand_logo'); ?></th>
					<th><?php echo "Owner Equity Threshold" ?></th>
					<th><?php echo $this->Paginator->sort('created'); ?></th>
					<th><?php echo $this->Paginator->sort('modified'); ?></th>
					<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				<?php foreach ($templates as $template): ?>
				<tr>
					<td><?php echo $this->Html->tag('div', $template['Template']['name'], array('class' => 'help', 'title' => strlen($template['Template']['description']) > 0 ? $template['Template']['description'] : 'No description saved')); ?>&nbsp;</td>
					<td><?php echo h($logoPositionTypes[$template['Template']['logo_position']]); ?>&nbsp;</td>
					<td><?php echo h(($template['Template']['include_brand_logo'] ? 'yes':'no')); ?>&nbsp;</td>
					<td><?php echo h($template['Template']['owner_equity_threshold']); ?>&nbsp;</td>
					<td><?php echo h($template['Template']['created']); ?>&nbsp;</td>
					<td><?php echo h($template['Template']['modified']); ?>&nbsp;</td>
					<td class="actions">
						<?php echo $this->Html->link(__('Edit'), CakeText::insert($list_url . '/edit/:id', array('id' => $template['Template']['id']))); ?>
						<?php echo $this->Form->postLink(__('Delete'), CakeText::insert($list_url . '/delete/:template_id', array('template_id' => $template['Template']['id'])), null, __('Are you sure you want to delete the template named "%s"?', $template['Template']['name'])); ?>
						<?php echo $this->Html->link(__('List Pages'), CakeText::insert('/admin/templates/:template_id/templatepages', array('template_id' => $template['Template']['id']))); ?>
						<?php echo $this->Html->link(__('Preview'), CakeText::insert('/admin/templates/preview/:template_id', array('template_id' => $template['Template']['id'])), array('target' => '_blank')); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php echo $this->Element('paginatorBottomNav'); ?>
		</div>
	</div>
  </div>
</div>