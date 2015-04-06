<script type="text/javascript">

	$(document).ready(function(){

		var cobrandPattern = /CobrandCobrandLogoSelect/;
		var brandPattern = /CobrandBrandLogoSelect/;

		$('select').on('change', function() {
			var id = $(this).attr('id');
			var selected = $(this).find("option:selected").text();

			if (cobrandPattern.test(id)) {
				$('#CobrandCobrandLogoUrl').val('/img/'+selected);
			}
			else if (brandPattern.test(id)) {
				$('#CobrandBrandLogoUrl').val('/img/'+selected);
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
				'options' => $existingLogos,
				'label' => 'Select Existing Logo',
				'type' => 'select'
			)
		);


		echo $this->Form->input('cobrand_logo', array('type' => 'file'));
		echo $this->Form->input('cobrand_logo_url');

		echo "<br><br>";

		echo $this->Form->input(
			'brand_logo_select',
			array(
				'options' => $existingLogos,
				'label' => 'Select Existing Logo',
				'type' => 'select'
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
