<?php
echo $this->Form->create();
echo $this->Form->inputs(array(
'pin',
'application_id',
'merchant_id',
'user_id'
));
echo $this->Form->end('Save');
echo json_encode($errMsg);
?>