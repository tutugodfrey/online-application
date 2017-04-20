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
		<div class="panel-heading"><u><strong><?php echo __('Add Cobrand')?></strong></u></div>
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
						echo $this->Form->input('partner_name');
						echo $this->Form->input('partner_name_short');
						echo $this->Form->input(
							'cobrand_logo_select',
							array(
								'type' => 'select',
								'options' => $existingLogos,
								'label' => 'Select Existing Logo',
								'empty' => 'none'
							)
						);
						echo $this->Form->input('cobrand_logo', array('class' => null, 'type' => 'file'));
						echo $this->Form->input('cobrand_logo_url');
						echo $this->Form->input(
							'brand_logo_select',
							array(
								'type' => 'select',
								'options' => $existingLogos,
								'label' => 'Select Existing Logo',
								'empty' => 'none'
							)
						);

						echo $this->Form->input('brand_logo', array('class' => null, 'type' => 'file'));
						echo $this->Form->input('brand_logo_url');
						echo $this->Form->input('description');
						echo $this->Form->input('response_url_type', array('options' => $responseUrlTypes));
					?>
				<?php echo $this->Form->end(array('label' => __('Submit'), 'div' => false, 'class' => 'btn btn-sm btn-success')); ?>
		</div>
	</div>
  </div>
</div>