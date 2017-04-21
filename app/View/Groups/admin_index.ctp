
<div class="container-fluid">
  <div class="row">
    <?php
    	$navItems = array(
    		'Add Group' => '/admin/groups/edit',
    		);
        echo $this->Element('actionsNav', array('navLinks' => $navItems));
    ?>
    <div class="col-sm-9 col-lg-10">
    <!-- view page content -->
        <div class="groups panel panel-default  index">
			<div class="panel-heading"><u><strong>Groups</strong></u></div>
			<table class="table talbe-condensed table-hover">
			<thead>
			<tr>
					<th><?php echo $this->Paginator->sort('id'); ?></th>
					<th><?php echo $this->Paginator->sort('name'); ?></th>
					<th><?php echo $this->Paginator->sort('created'); ?></th>
					<th><?php echo $this->Paginator->sort('modified'); ?></th>
					<th class="actions"><?php echo __('Actions'); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($groups as $group): ?>
			<tr>
				<td><?php echo h($group['Group']['id']); ?>&nbsp;</td>
				<td><?php echo h($group['Group']['name']); ?>&nbsp;</td>
				<td><?php echo h($group['Group']['created']); ?>&nbsp;</td>
				<td><?php echo h($group['Group']['modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(
						$this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-pencil')),
						array('action' => 'edit', $group['Group']['id']),
						array('escape' => false)
						); ?>
					<?php echo $this->Form->postLink(
						$this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-remove text-danger')),
						array('action' => 'delete', $group['Group']['id']), array('escape' => false, 'confirm' => __('Are you sure you want to delete # %s?', $group['Group']['id']))); ?>
				</td>
			</tr>
		<?php endforeach; ?>
			</tbody>
			</table>
			<div class="paging">
			<?php echo $this->Element('paginatorBottomNav'); ?>
			</div>
		</div>
    </div>
  </div>
</div>