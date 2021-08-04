<p>Click the <strong>Delete</strong> button to delete
the post <?php echo $epayment['Epayment']['merchant_id']; ?></p>
<?php
echo $this->Form->create(array('url'=>array('action'=>'delete',
$epayment['Epayment']['id'])));
echo $this->Form->hidden('Epayment.id', array('value'=>$epayment['Epayment']['id']));
echo $this->Form->end('Delete');
?>
