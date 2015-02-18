<div class="cobrands form">
<?php echo $this->Form->create('Cobrand', array('enctype' => 'multipart/form-data')); ?>
	<fieldset>
		<legend><?php echo __('Edit Cobrand'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('partner_name');
		echo $this->Form->input('partner_name_short');
		if (!empty($this->request->data['Cobrand']['logo_url'])) {
			$label = 'Replace Logo';
			echo '<strong>Existing Logo</strong><br/>' . $this->Html->image($this->request->data['Cobrand']['logo_url'], array('height' => '50px'));
		} else {
			$label = 'Upload Logo';
		}
		echo $this->Form->input('logo', array('type' => 'file', 'label' => $label, 'novalidate' => true, 'required' => false));
		echo $this->Form->input('delete_logo', array('type' => 'checkbox'));
		echo $this->Form->input('logo_url');
		echo $this->Form->input('description');
		echo $this->Form->input('response_url_type', array('options' => $responseUrlTypes));
	?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>	
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
	</ul>
</div>
