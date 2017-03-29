<?php 
/** 
*
*This navigation element should only be used for existing controller actions and corresponding views.
*/
 ?>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
				<li><?php echo $this->Html->link(__('List ' . $this->name), array('action' => 'index')); ?></li>
				<li><?php echo $this->Html->link(__('Add ' . $this->name), array('action' => 'edit')); ?></li>
				<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
			</ul>

		</div>
	</div>
</div>