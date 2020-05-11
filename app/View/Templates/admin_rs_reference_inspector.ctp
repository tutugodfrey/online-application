<div class="container-fluid">
  <div class="row">
  	<?php
  	$elVars['navLinks']['Fix RightSignature References'] = $this->here;
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
		<div class="panel-heading">
			<strong>
			<?php echo __('RightSignature Template References');?>
			</strong>
		</div>

		<?php 
		if (!empty($orphanedTemplates)) :?>
			<div class="alert alert-danger">
				<strong><img src='/img/test-fail-icon.png'/> The following templates are referencing non-existing RightSignature template IDs. Please update the referenced RightSignature IDs!</strong>
			</div>
			<table class="table table-condensed table-striped table-hover">
				<tr>
					<th><?php echo 'Axia Template Name'; ?></th>
					<th><?php echo 'Outdated RightSignature Reference Ids'; ?></th>
				</tr>
				<?php foreach ($orphanedTemplates as $template): ?>
				<tr>
					<td><?php echo $this->Html->tag('div', 
					$template['Cobrand']['partner_name'] . ' - ' . $template['Template']['name']); ?></td>
					<td class="text-danger nowrap"><?php 
					echo $this->Html->image('/img/test-fail-icon.png') . ' ' . $template['Template']['rightsignature_template_guid']; ?></td>
				</tr>
			<?php endforeach; ?>
			</table>
			<hr/>
				<div class="alert text-info">
					<span class="glyphicon glyphicon-info-sign"></span> Select the outdated rightsignature reference id below from the left, then pair it with the appropriate new Rightsignature Template from the list on the right.<br/>All the templates listed above containing the outdated references, will be automatically updated with the RightSignature template selected.
				</div>
			<div class="center-block" style="width: 50%;">
				<?php 
					echo $this->Form->create('Template', array(
						'inputDefaults' => array(
							'div' => 'form-group',
							'label' => array('class' => 'col-md-2 control-label'),
							'wrapInput' => 'col-md-10',
							'class' => 'form-control input-sm',
						),
						'class' => 'form-horizontal',
					)); 
				?>
				<table class="table table-condensed table-striped table-bordered">
					<tr>
						<th><?php echo "<img src='/img/test-fail-icon.png'/> Outdated References"; ?></th>
						<th><?php echo 'Enter New Rightsignature Template Id'; ?></th>
					</tr>
					<tr>
						<td>
							<?php 
							echo $this->Form->input('old_template_guid',
								array(
									'type' => 'select',
									'label' => false,
									'options' => $outdatedIds,
								)
							);
							?>
						</td>
						<td><?php 
							echo $this->Form->input('rightsignature_template_guid',
								array(
									'type' => 'text',
									'required' => 'required',
									'label' => false,
									'options' => $rsTemplates,
									'after' => $this->Form->button('<span class="glyphicon glyphicon-info-sign"></span>',
											array(
												'type' => 'button',
												'onClick' => "getRsTemplateById();",
												'class' => 'btn btn-info btn-xs',
												'title' => __('View information about selected template')
											)
										) . '<div class="text-center"><strong class="text-danger" id ="selErrMsg1"></strong></div>'
								)
							);
						?></td>
					</tr>
					<tr>
						<td colspan="2" class='text-center'>
							<?php echo $this->Form->end(array('label' => __('Update all References'), 'div' => false, 'class' => 'btn btn-sm btn-success'));  ?>
						</td>
					</tr>
				</table>
			</div>
		<?php else: ?>
			<hr>
			<div class='alert alert-success center-block text-center' style="width: 50%;">
				<h3><span class="glyphicon glyphicon-ok"></span></h3>
				<h4>
				All templates are properly referencing their corresponding RightSignature templates!</h4>
			</div>
			<hr>
		<?php endif; ?>
		</div>
	</div>
  </div>
</div>
<script>
function getRsTemplateById() {
	$('#selErrMsg1').html('');
	rsTemplateId = $("#TemplateRightsignatureTemplateGuid").val();
	rsTemplateId = rsTemplateId.trim();

	if (rsTemplateId == undefined || rsTemplateId == '') {
		$('#selErrMsg1').html('<br/>Enter a valid RigthSignature template Id.');
	} else {
		renderContentAJAX('', '', '', 'dynamicModalBody', '/admin/Templates/preview_rs_template/' + rsTemplateId);
		$("#dynamicModal").modal();
	}
}
</script>