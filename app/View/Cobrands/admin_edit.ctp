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


<div class="container-fluid">
  <div class="row">
	<?php
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
		<div class="panel-heading"><u><strong><?php echo __('Edit Cobrand')?></strong></u></div>
			<?php echo $this->Form->create('Cobrand', array(
				'enctype' => 'multipart/form-data',
				'inputDefaults' => array(
						'div' => 'form-group col-md-12',
						'label' => array('class' => 'col-md-2 control-label'),
						'wrapInput' => 'col-md-4',
						'class' => 'form-control input-sm',
					),
					'class' => 'form-horizontal',
				)); 
				echo $this->Form->input('id');
				echo $this->Form->input('partner_name');
				echo $this->Form->input('partner_name_short');
				echo "<br>";

				if (!empty($this->request->data['Cobrand']['cobrand_logo_url'])) {
					$label = 'Replace Cobrand Logo';

					echo '<div class="row">
							  <div class="col-sm-offset-2 col-sm-6 col-md-3">
								<div class="thumbnail">
									<div class="caption text-center">
									<strong>Existing Cobrand Logo</strong>
									</div>
								' . $this->Html->image($this->request->data['Cobrand']['cobrand_logo_url'], array(
									'onError' => "this.onerror=null; this.src='/img/no-image.png';",
									'class' => 'thumbnail col-md-offset-3', 
									'height' => '75px')) . '
								</div>
							  </div>
							</div>';
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

				echo $this->Form->input('cobrand_logo', array('type' => 'file', 'class' => null, 'label' => $label, 'novalidate' => true, 'required' => false));
				echo $this->Form->input('delete_cobrand_logo',  array('label'=> array('class' => 'col-md-11 control-label text-warning'),'type'=>'checkbox', 'class' => null));
				echo $this->Form->input('cobrand_logo_url');

				echo "<br><br>";

				if (!empty($this->request->data['Cobrand']['brand_logo_url'])) {
					$label = 'Replace Brand Logo';
					echo '<div class="row">
							  <div class="col-sm-offset-2 col-sm-6 col-md-3">
								<div class="thumbnail">
									<div class="caption text-center">
									<strong>Existing Brand Logo</strong>
									</div>
								' . $this->Html->image($this->request->data['Cobrand']['brand_logo_url'], array(
									'onError' => "this.onerror=null; this.src='/img/no-image.png';",
									'class' => 'thumbnail col-md-offset-3', 
									'height' => '75px')) . '
								</div>
							  </div>
							</div>';
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

				echo $this->Form->input('brand_logo', array('type' => 'file', 'class' => null, 'label' => $label, 'novalidate' => true, 'required' => false));
				echo $this->Form->input('delete_brand_logo', array('label'=> array('class' => 'col-md-11 control-label text-warning'),'type'=>'checkbox', 'class' => null));
				echo $this->Form->input('brand_logo_url');

				echo "<br><br>";

				echo $this->Form->input('description');
				echo $this->Form->input('response_url_type', array('options' => $responseUrlTypes));
				echo $this->Form->end(array('label' => __('Submit'), 'div' => false, 'class' => 'btn btn-sm btn-success')); ?>
		</div>
	</div>
  </div>
</div>