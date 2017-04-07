 <script type='text/javascript' src='/js/multiselect/multiselect.js'></script>
 <?php 
$this->request->data['Managers'] = [];
$managers = [];
echo $this->Form->create('User', array(
   'inputDefaults' => array(
		'div' => 'form-group col-md-12',
		'label' => array('class' => 'col-md-4 control-label'),
		'wrapInput' => 'col-md-7',
		'class' => 'form-control input-sm',
	),
	'class' => 'form-horizontal',
	'novalidate' => true));
echo $this->Html->tag('div', null, array('class' => 'col-md-6'));
	echo $this->Form->input('active', array('label'=> array('class' => 'col-md-10 control-label text-success'), 'type' => 'checkbox', 'class' => null));
	if (!empty($this->request->data['User']['id'])) {
		echo $this->Form->hidden('id');
	}
	echo $this->Form->input('firstname');
	echo $this->Form->input('lastname');
	echo $this->Form->input('email');
	echo $this->Form->input('pwd', array('label'=> 'Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
	echo $this->Form->input('password_confirm', array('type' => 'password', 'value'=>'', 'autocomplete'=>'off'));
//-------------END FIRST INPUT COLUMN
echo $this->Html->tag('/div');
//-------------START SECOND INPUT COLUMN
echo $this->Html->tag('div', null, array('class' => 'col-md-6'));
	echo $this->Form->input('api_enabled', array('label'=> array('text' => 'Enable API', 'class' => 'col-md-11 control-label'),'type'=>'checkbox', 'class' => null));        
	echo $this->Form->input('api_password', array('label'=> 'Api Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
	echo $this->Form->input('group_id');
	echo $this->Form->input('extension');
	if ($this->request->data('User.api_enabled')) {
		echo $this->Form->input('token');
		echo $this->Html->tag('div', null, array('class' => 'form-group col-md-12'));
		echo $this->Form->label('User.token_used', 'Token Used On', array('class' => 'col-md-4 control-label'));
		echo $this->Form->dateTime('token_used', 'MDY', '12', array(
			'wrapInput' => 'col-md-7',
			'class' => null,
			'maxYear' => date('Y') + 2,
			'empty' => true));
		echo $this->Html->tag('/div');
		echo $this->Form->input('token_uses');
	}
echo $this->Html->tag('/div');
//Begin Multiselect
?>
<div class="clearfix"></div>
<div class="well well-sm"><strong>Select Manager(s)</strong>
	<div class="row">
		<?php
			echo $this->Form->input('AllManagersMulti', array(
			'id' => 'all_managers',
			'options' => $allManagers,
			'div' => false,
			'wrapInput' => 'col-xs-5',
			'label' => false,
			'multiple' => true, 
			'size' => '7'));
		?>    
		<div class="col-xs-2">
			<button type="button" id="all_managers_rightAll" class="btn btn-block btn-primary"><i class="glyphicon glyphicon-forward"></i></button>
			<button type="button" id="all_managers_rightSelected" class="btn btn-block btn-info"><i class="glyphicon glyphicon-chevron-right"></i></button>
			<button type="button" id="all_managers_leftSelected" class="btn btn-block btn-warning"><i class="glyphicon glyphicon-chevron-left"></i></button>
			<button type="button" id="all_managers_leftAll" class="btn btn-block btn-danger"><i class="glyphicon glyphicon-backward"></i></button>
		</div>
		<?php
		echo $this->Form->input('Manager', array(
			'id' => 'all_managers_to', //chosen options element id must end in *_to for the multiselect to work
			'div' => false,
			'wrapInput' => 'col-xs-5',
			'class' => 'form-control input-sm',
			'label' => false,
			'multiple' => true, 
			'size' => '7'));
		?>
	</div>
</div>
<?php
	if ($this->request->data('User.group_id') == User::MANAGER_GROUP_ID) :?>
		<div class="well well-sm"><strong>Select Rep(s)</strong>
			<div class="row">
				<?php
					echo $this->Form->input('allRepresentativesMulti', array(
					'id' => 'all_assn_reps',
					'options' => $allRepresentatives,
					'div' => false,
					'wrapInput' => 'col-xs-5',
					'label' => false,
					'multiple' => true, 
					'size' => '7'));
				?>    
				<div class="col-xs-2">
					<button type="button" id="all_assn_reps_rightAll" class="btn btn-block btn-primary"><i class="glyphicon glyphicon-forward"></i></button>
					<button type="button" id="all_assn_reps_rightSelected" class="btn btn-block btn-info"><i class="glyphicon glyphicon-chevron-right"></i></button>
					<button type="button" id="all_assn_reps_leftSelected" class="btn btn-block btn-warning"><i class="glyphicon glyphicon-chevron-left"></i></button>
					<button type="button" id="all_assn_reps_leftAll" class="btn btn-block btn-danger"><i class="glyphicon glyphicon-backward"></i></button>
				</div>
				<?php
				echo $this->Form->input('AssignedRepresentative', array(
					'id' => 'all_assn_reps_to', //chosen options element id must end in *_to for the multiselect to work
					'div' => false,
					'wrapInput' => 'col-xs-5',
					'class' => 'form-control input-sm',
					'label' => false,
					'multiple' => true, 
					'size' => '7'));
				?>
			</div>
		</div>
<?php endif; ?>

<div class="well well-sm"><strong>Select Cobrands(s)</strong>
	<div class="row">
		<?php
		    echo $this->Form->input('AllCoBrandsMulti', array(
	    	'id' => 'all_cobrands',
	    	'options' => $allCobrands,
		    'div' => false,
		    'wrapInput' => 'col-xs-5',
		    'label' => false,
		    'multiple' => true, 
		    'size' => '18'));
	    ?>    
	    <div class="col-xs-2">
	        <button type="button" id="all_cobrands_rightAll" class="btn btn-block btn-primary"><i class="glyphicon glyphicon-forward"></i></button>
	        <button type="button" id="all_cobrands_rightSelected" class="btn btn-block btn-info"><i class="glyphicon glyphicon-chevron-right"></i></button>
	        <button type="button" id="all_cobrands_leftSelected" class="btn btn-block btn-warning"><i class="glyphicon glyphicon-chevron-left"></i></button>
	        <button type="button" id="all_cobrands_leftAll" class="btn btn-block btn-danger"><i class="glyphicon glyphicon-backward"></i></button>
	    </div>
	    <?php
	    echo $this->Form->input('Cobrand', array(
	    	'id' => 'all_cobrands_to', //chosen options element id must end in *_to for the multiselect to work
		    'div' => false,
		    'wrapInput' => 'col-xs-5',
		    'class' => 'form-control input-sm',
		    'label' => false,
		    'multiple' => true, 
		    'size' => '18'));
	    ?>
	</div>
</div>
<div class="well well-sm"><strong>Select Templates(s)</strong> <small>(must be associated with the cobrand above)</small>
	<?php 
	$invalidTmpltErr = Hash::get($this->validationErrors, 'User.Template.0');
	if (!empty($invalidTmpltErr))
		echo "<strong class='text-danger pull-right'>***$invalidTmpltErr***</strong>"
		?>
	<div class="row" id="multiSelectionArea">
		<?php
		    echo $this->Form->input('AllTemplatesMulti', array(
	    	'id' => 'all_templates',
	    	'options' => $allTemplates,
		    'div' => false,
		    'wrapInput' => 'col-xs-5',
		    'label' => false,
		    'multiple' => true, 
		    'size' => '18'));
	    ?>    
	    <div class="col-xs-2">
	        <button type="button" id="all_templates_rightAll" class="btn btn-block btn-primary"><i class="glyphicon glyphicon-forward"></i></button>
	        <button type="button" id="all_templates_rightSelected" class="btn btn-block btn-info"><i class="glyphicon glyphicon-chevron-right"></i></button>
	        <button type="button" id="all_templates_leftSelected" class="btn btn-block btn-warning"><i class="glyphicon glyphicon-chevron-left"></i></button>
	        <button type="button" id="all_templates_leftAll" class="btn btn-block btn-danger"><i class="glyphicon glyphicon-backward"></i></button>
	    </div>
	    <?php
	    echo $this->Form->input('Template', array(
	    	'id' => 'all_templates_to', //chosen options element id must end in *_to for the multiselect to work
		    'div' => false,
		    'wrapInput' => 'col-xs-5',
		    'class' => 'form-control input-sm',
		    'label' => false,
		    'multiple' => true, 
		    'size' => '18'));
	    ?>
	</div>
</div>

<?php
	//set variable for add action
	$userDefaultTemplates = (!isset($userDefaultTemplates))? array() : $userDefaultTemplates;
	echo $this->Form->input(
		'User.template_id',
		array(
			'empty' => 'Select a default Template...',
			'options' => $userDefaultTemplates,
			'label' => 'Select Default Template',
			'type' => 'select'
		)
	);
?>
<script type='text/javascript'>
$(document).ready(function() {
	//Enable multiselect functionality
	$('#all_managers').multiselect({
		settings: {
			submitAllLeft: false
		},
		search: {
			left: '<input type="text" name="mgr_optns_search" class="form-control" placeholder="Search All Managers..." />',
			right: '<input type="text" name="mgr_optns_search" class="form-control" placeholder="Search Selected..." />',
		},
		fireSearch: function(value) {
			return value.length > 1;
		}
	});

	$('#all_assn_reps').multiselect({
		settings: {
			submitAllLeft: false
		},
		search: {
			left: '<input type="text" name="assn_reps_optns_search" class="form-control" placeholder="Search All Reps..." />',
			right: '<input type="text" name="assn_reps_optns_search" class="form-control" placeholder="Search Selected..." />',
		},
		fireSearch: function(value) {
			return value.length > 1;
		}
	});

	$('#all_cobrands').multiselect({
		settings: {
			submitAllLeft: false
		},
		search: {
			left: '<input type="text" name="all_cobrands_optns_search" class="form-control" placeholder="Search All Cobrands..." />',
			right: '<input type="text" name="all_cobrands_optns_search" class="form-control" placeholder="Search Selected..." />',
		},
		fireSearch: function(value) {
			return value.length > 1;
		}
	});

	$('#all_templates').multiselect({
		settings: {
			submitAllLeft: false
		},
		search: {
			left: '<input type="text" name="all_templates_optns_search" class="form-control" placeholder="Search All Templates..." />',
			right: '<input type="text" name="all_templates_optns_search" class="form-control" placeholder="Search Selected..." />',
		},
		fireSearch: function(value) {
			return value.length > 1;
		}
	});
	$('#multiSelectionArea').on('click mouseenter mouseleave keypress', function() {
		if ($('#UserTemplateId option').length -1 !== $('#all_templates_to option').length) {
			//remember initial selection
			curSelVal = $("#UserTemplateId option:selected").val();
			//Get all new updated options from multiselect selected menu
			var newOptions = $('#all_templates_to option');
			
			var $el = $("#UserTemplateId");
			// remove old options except the top 
			$('#UserTemplateId option:gt(0)').remove(); 
			//Update default template dropdown
			$.each(newOptions, function(key,value) {
				$el.append($("<option></option>")
					.attr("value", value.value).text(value.label));
			});

			//Set initial value
			$('#UserTemplateId option[value=' + curSelVal + ']').attr('selected','selected');
		}
	});

});
</script>
<?php
echo $this->Form->end(array('label' => __('Save user'), 'div' => array('class' => 'col-md-12'), 'class' => 'btn btn-sm btn-success'));
?>