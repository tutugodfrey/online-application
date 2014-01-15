<?php $this->Html->addCrumb(__('Cobrand'), '/admin/cobrands'); ?>

<div class="templates index">
	<h2><?php echo __('Templates for:'); ?></h2>
	<?php echo String::insert(__(':partner_name'), array("partner_name" => $cobrand['Cobrand']['partner_name'],)); ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('logo_position'); ?></th>
			<th><?php echo $this->Paginator->sort('include_axia_logo'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php foreach ($templates as $template): ?>
		<tr>
			<td><?php echo $this->Html->tag('div', $template['Template']['name'], array('class' => 'help', 'title' => strlen($template['Template']['description']) > 0 ? $template['Template']['description'] : 'No description saved')); ?>&nbsp;</td>
			<td><?php echo h($logoPositionTypes[$template['Template']['logo_position']]); ?>&nbsp;</td>
			<td><?php echo h(($template['Template']['include_axia_logo'] ? 'yes':'no')); ?>&nbsp;</td>
			<td><?php echo h($template['Template']['created']); ?>&nbsp;</td>
			<td><?php echo h($template['Template']['modified']); ?>&nbsp;</td>
			<td class="actions">
				<?php echo $this->Html->link(__('Edit'), String::insert($list_url . '/edit/:id', array('id' => $template['Template']['id']))); ?>
				<?php echo $this->Form->postLink(__('Delete'), String::insert($list_url . '/delete/:template_id', array('template_id' => $template['Template']['id'])), null, __('Are you sure you want to delete the template named "%s"?', $template['Template']['name'])); ?>
				<?php echo $this->Html->link(__('List Pages'), String::insert('/admin/templates/:template_id/templatepages', array('template_id' => $template['Template']['id']))); ?>
				<?php echo $this->Html->link(__('Preview'), String::insert('/admin/templates/preview/:template_id', array('template_id' => $template['Template']['id']))); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $this->Element('paginatorBottomNav'); ?>
</div>
<div class="actions">
	<h3>Actions</h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Template'), String::insert('/admin/cobrands/:cobrand_id/templates/add', array('cobrand_id' => $cobrand['Cobrand']['id']))); ?></li>
	</ul>
</div>
