<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('Coversheets'), array('controller' => 'coversheets', 'action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('Multipass Overview'), array('controller' => 'multipasses', 'action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('Multipass Import'), array('controller' => 'multipasses', 'action' => 'import')); ?> </li>
                <li><?php echo $this->Html->link(__('Multipass Add'), array('controller' => 'multipasses', 'action' => 'add')); ?> </li>
	</ul>
        <?php echo $this->Element('multipasses/search'); ?>
</div>
