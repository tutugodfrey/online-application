<div class="panel panel-primary">
	<div class="panel-heading"><u><strong><?php echo __('Export Application Data')?></strong></u></div>
	<div class="media">
		<div class="media-left">
			<img class="media-object" src="/img/connect_cloud.jpg" style="width: 74px; height: 74px;">
		</div>
		<div class="media-body">
			<h4 class="media-heading text-info">Export Directly to Axia Database
				<span class='glyphicon glyphicon-info-sign text-info' data-toggle='tooltip' data-placement='right' data-original-title="Connects to Axia's database, exports the data, and the merchant account will be created automatically."></span>
			</h4>
			<?php echo $this->Session->flash(); ?>

			<?php 
				echo $this->Form->create('CobrandedApplication', array(
					'url' => array('controller' => 'CobrandedApplications', 'action' => 'export', 'admin' => true, $appId),
					'inputDefaults' => array(
						'div' => 'form-group',
						'wrapInput' => false,
						'class' => 'form-control'
					),
					'class' => 'form-inline'
				));
				echo $this->Form->hidden('id', array('value' => $appId));
				echo $this->Html->tag('p', "The Rep and Partner names must exactly match their names in the Axia Database. Correct any miss-matches below, keep in mind some Reps have multiple accounts in the database system. (If names already match you can skip selecting a rep/partner name.)");

				if (empty($repList)) {
					echo '<ul class="list-group">' .
  						 '<li class="list-group-item list-group-item-warning text-center">No similarly named users found in the DB.<br/>You may attempt to export but it might not be successful.</li></ul>';
				} else {
					echo $this->Html->tag('p', '<strong>This is a list of Axia Database Rep names, if the Rep on this app does not exactly match any below, select the correct one:</strong>');
					echo $this->Html->tag('span', "<strong>(The Rep on this app is: " . h($repName) . ") </strong>",
						['class' => 'small center-block text-success']);
					echo $this->Form->input(
						'ContractorID',
						array(
							'label' => false,
							'empty' => 'Select DB Rep',
							'options' => $repList,
							'after' => " <span class='glyphicon glyphicon-info-sign text-info' data-toggle='tooltip' data-placement='top' data-original-title='Rep not listed? The Rep account has probably not yet been created in the Axia DB.'></span>"
						)
					);
					if (!empty($csPartner)) {
						echo $this->Html->tag('p', '<strong>This is a list of Axia Database Partner names, if the Partner on this app does not exactly match any below select the correct one:</strong>');
						echo '<strong>' . $this->Html->tag('span', "The Partner on cover sheet is: " . (h($csPartner))?:'(none)', ['class' => 'small center-block text-success']) . '</strong>';

						echo $this->Form->input(
							'setup_partner',
							array(
								'label' => false,
								'empty' => 'Select DB Partner',
								'options' => [],
								'after' => " <span class='glyphicon glyphicon-info-sign text-info' data-toggle='tooltip' data-placement='top' data-original-title='Partner not listed? The Partner account has probably not yet been created in the Axia DB.'></span>"
							)
						);
					}
				}
				
				echo '<p><strong>Enter the MID that will be assigned to this merchant:</strong></p>';
				echo $this->Form->input(
					'assign_mid',
					array(
						'label' => false,
						'placeholder' => 'Assign MID',
						'type' => 'number',
						'required' => 'required'
					)
				);
				echo $this->Html->tag('div', 
					$this->Form->button('<span class="glyphicon glyphicon-cloud-upload"></span> Export', array('id' => 'submitExportBtn', 'escape' => false, 'type' => 'submit', 'class' => 'btn btn-success'))
					, array('class' => 'form-group')
				);
				echo $this->Form->end();
			?>
		</div>
	</div>
	<hr />
	<div class="media">
		<div class="media-left">
			<img class="media-object" src="/img/csv-download.png" style="width: 64px; height: 64px;">
		</div>
		<div class="media-body">
			<h4 class="media-heading text-info">Export as CSV</h4>
			<span class="text-center center-block">
			<?php 
				echo $this->Html->link('<span class="glyphicon glyphicon-download-alt"></span> Download ',
				 array(
					'action' => 'export',
					$appId
				 ),
				 array(
					'escape' => false,
					'class' => 'btn btn-success',
					'title' => __('Download CSV file')
					)
				);
			?>
			</span>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#CobrandedApplicationExportForm').on('submit', function() {
			$('#submitExportBtn').hide();
			$('#submitExportBtn').parent().append(' <strong class="text-info"> Sending data...<img src="/img/refreshing.gif"></strong>');
		});
		var partnerOptns = <?php echo "'" . $assocPartnerList . "'";?>;
		partnerOptns = JSON.parse(partnerOptns);
		if ($('#CobrandedApplicationContractorID').length > 0 && $('#CobrandedApplicationSetupPartner').length > 0) {
			$('#CobrandedApplicationContractorID').on('change', function() {
				if ($(this).val() != "") {
					if (Object.keys(partnerOptns[$(this).val()]).length > 0) {
						$('#CobrandedApplicationSetupPartner').removeAttr('disabled');
						$('#CobrandedApplicationSetupPartner').find('option').remove().end().append('<option value>Select Partner</option>');
						$.each(partnerOptns[$(this).val()], function(k, v) {
							$("#CobrandedApplicationSetupPartner").append('<option value="' + k + '">' + v + '</option>');
						});
					} else {
						$('#CobrandedApplicationSetupPartner').prop('disabled', 'disabled');
						$('#CobrandedApplicationSetupPartner').find('option').remove().end().append('<option value>Selected Rep has no associated partners</option>');
					}
				} else {
					$('#CobrandedApplicationSetupPartner').prop('disabled', 'disabled');
					$('#CobrandedApplicationSetupPartner').find('option').remove().end().append('<option value>N/A</option>');
				}

			});
		}
		
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>