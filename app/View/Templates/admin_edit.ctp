<?php $this->Html->addCrumb(__('Cobrand'), '/admin/cobrands'); ?>

<div class="templates form">
<?php echo $this->Form->create('Template'); ?>
	<fieldset>
		<legend><?php echo String::insert(__('Edit Template for ":cobrand_name"'), array('cobrand_name' => $cobrand['Cobrand']['partner_name'])); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');

		echo "<br>";

		$cobrandImage = $this->Html->image($cobrand['Cobrand']['cobrand_logo_url'], array('height' => '50px'));
		echo $this->Form->input(
			'logo_position',
			array(
				'options' => $logoPositionTypes,
				'empty' => __('(choose one)'),
				'between' => $cobrandImage
			)
		);
		
		echo "<br>";

		$brandImage = $this->Html->image($cobrand['Cobrand']['brand_logo_url'], array('height' => '50px'));
		echo $this->Form->input(
			'include_brand_logo',
			array(
				'before' => $brandImage
			)
		);

		echo $this->Form->input('description');
		echo $this->Form->input('rightsignature_template_guid');
		echo $this->Form->input('rightsignature_install_template_guid');
		echo $this->Form->input('owner_equity_threshold');
		echo $this->Form->hidden('cobrand_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), String::insert('/admin/cobrands/:cobrand_id/templates', array('cobrand_id' => $cobrand['Cobrand']['id']))); ?></li>
	</ul>
</div>
