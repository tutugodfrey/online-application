<div class="Multipasses form">
<?php
echo $this->Form->create('Multipass');
echo $this->Form->hidden('id');
echo $this->Form->input('merchant_id', array('type' => 'text'));
echo $this->Form->input('device_number');
echo $this->Form->input('username');
echo $this->Form->input('pass');
echo $this->Form->input('in_use');
echo $this->Form->input('application_id', array('type' => 'text'));
echo $this->Form->end('Save');
?>
</div>