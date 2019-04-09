<div class="panel panel-primary">
	<div class="panel-heading"><u><strong><?php echo __('Export Application Data')?></strong></u></div>
	<div class="media">
		<div class="media-left">
			<img class="media-object" src="/img/connect_cloud.jpg" style="width: 74px; height: 74px;">
		</div>
		<div class="media-body">
			<h4 class="media-heading text-info">Export Directly to Axia Database </h4>
			Connect to Axia's database and export.
			<p>The database will create the merchant account automatically.</p>
			<p><strong>Enter the MID that will be assigned to this merchant:</strong></p>
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
					$this->Form->button('<span class="glyphicon glyphicon-cloud-upload"></span> Export', array('escape' => false, 'type' => 'submit', 'class' => 'btn btn-success'))
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