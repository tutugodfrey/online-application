<h1>Applications</h1>
<?php if(!empty($applications)) { ?>
<ul>
    <?php foreach($applications as $application) { ?>
<li>
    <?php echo $this->Html->link($application['Application']['id'], array(
        'action' =>'view',
        $application['Application']['id']
        )); ?>
    
    <?php echo $this->Html->link('Edit', array(
        'action' =>'edit',
        $application['Application']['id']
    )); ?>
    
    <?php echo $this->Html->link('Delete', array(
        'action' =>'Delete',
        $application['Application']['id']
    )); ?>    
</li>
<?php } ?>
</ul>
<?php } ?>
<?php echo $this->Html->link('Create new Post', array('action'=>'add')); ?>
