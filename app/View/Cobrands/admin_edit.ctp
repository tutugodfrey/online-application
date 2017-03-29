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
		<legend><?php echo __('Edit Cobrand'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('partner_name');
		echo $this->Form->input('partner_name_short');

		echo "<br>";

		if (!empty($this->request->data['Cobrand']['cobrand_logo_url'])) {
			$label = 'Replace Cobrand Logo';
			echo '<strong>Existing Cobrand Logo</strong><br/>' . $this->Html->image($this->request->data['Cobrand']['cobrand_logo_url'], array('height' => '50px'));
		} else {
			$label = 'Upload Cobrand Logo';
		}

		$counter = 0;
		$selected = null;
		$cobrandLogo = $cobrand['Cobrand']['cobrand_logo_url'];
		$cobrandLogo = preg_replace('/\/img\//', '', $cobrandLogo);

		foreach ($existingLogos as $logo) {
			if ($logo == $cobrandLogo) {
				$selected = $counter;
			}
			$counter++;
		}

		echo $this->Form->input(
			'cobrand_logo_select',
			array(
				'type' => 'select',
				'selected' => $selected,
				'options' => $existingLogos,
				'label' => 'Select Existing Logo',
				'empty' => 'none'
			)
		);

		echo $this->Form->input('cobrand_logo', array('type' => 'file', 'label' => $label, 'novalidate' => true, 'required' => false));
		echo $this->Form->input('delete_cobrand_logo', array('type' => 'checkbox'));
		echo $this->Form->input('cobrand_logo_url');

		echo "<br><br>";

		if (!empty($this->request->data['Cobrand']['brand_logo_url'])) {
			$label = 'Replace Brand Logo';
			echo '<strong>Existing Brand Logo</strong><br/>' . $this->Html->image($this->request->data['Cobrand']['brand_logo_url'], array('height' => '50px'));
		} else {
			$label = 'Upload Brand Logo';
		}

		$counter = 0;
		$selected = null;
		$brandLogo = $cobrand['Cobrand']['brand_logo_url'];
		$brandLogo = preg_replace('/\/img\//', '', $brandLogo);

		foreach ($existingLogos as $logo) {
			if ($logo == $brandLogo) {
				$selected = $counter;
			}
			$counter++;
		}

		echo $this->Form->input(
			'brand_logo_select',
			array(
				'type' => 'select',
				'selected' => $selected,
				'options' => $existingLogos,
				'label' => 'Select Existing Logo',
				'empty' => 'none'
			)
		);

		echo $this->Form->input('brand_logo', array('type' => 'file', 'label' => $label, 'novalidate' => true, 'required' => false));
		echo $this->Form->input('delete_brand_logo', array('type' => 'checkbox'));
		echo $this->Form->input('brand_logo_url');

		echo "<br><br>";

		echo $this->Form->input('description');
		echo $this->Form->input('response_url_type', array('options' => $responseUrlTypes));
	?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>	
</div>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
				<li><?php echo $this->Html->link(__('Cancel'), array('action' => 'index')); ?></li>
			</ul>
		</div>
	</div>
</div>
