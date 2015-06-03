<script type="text/javascript">

	$(document).ready(function(){

		var nonePattern = /none/;
		var cobrandPattern = /CobrandCobrandLogoSelect/;
		var brandPattern = /CobrandBrandLogoSelect/;

		$('select').on('change', function() {
			var id = $(this).attr('id');
			var selected = $(this).find("option:selected").text();
			var filename = '/img/'+selected;

			if (nonePattern.test(selected)) {
				filename = '';
			}

			if (cobrandPattern.test(id)) {
				$('#CobrandCobrandLogoUrl').val(filename);
			}
			else if (brandPattern.test(id)) {
				$('#CobrandBrandLogoUrl').val(filename);
			}
		});
	});

</script>

<div class="cobrands form">
<?php echo $this->Form->create('Cobrand', array('enctype' => 'multipart/form-data')); ?>
	<fieldset>
		<legend><?php echo __('Add Cobrand'); ?></legend>
	<?php
		echo $this->Form->input('partner_name');
		echo $this->Form->input('partner_name_short');

		echo "<br>";

		echo $this->Form->input(
			'cobrand_logo_select',
			array(
				'type' => 'select',
				'options' => $existingLogos,
				'label' => 'Select Existing Logo',
				'empty' => 'none'
			)
		);


		echo $this->Form->input('cobrand_logo', array('type' => 'file'));
		echo $this->Form->input('cobrand_logo_url');

		echo "<br><br>";

		echo $this->Form->input(
			'brand_logo_select',
			array(
				'type' => 'select',
				'options' => $existingLogos,
				'label' => 'Select Existing Logo',
				'empty' => 'none'
			)
		);

		echo $this->Form->input('brand_logo', array('type' => 'file'));
		echo $this->Form->input('brand_logo_url');

		echo "<br><br>";

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
