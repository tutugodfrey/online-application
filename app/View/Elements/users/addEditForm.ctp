 <script type='text/javascript' src='/js/multiselect/multiselect.js'></script>
 <script type='text/javascript'>
 	//Set global variable
	var allTemplates = <?php echo $allTemplates;?>;
 </script>
 <?php 
$this->request->data['Managers'] = array();
$managers = array();
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
	echo $this->Form->hidden('User.has_okta_user_account');
	echo $this->Form->input('group_id');
	echo $this->Form->input('firstname');
	echo $this->Form->input('lastname');
	echo $this->Form->input('email');
	if ($this->request->data('User.has_okta_user_account')) {
		echo $this->Form->hidden('User.okta_user_current_email', ['value' => $this->request->data('User.email')]);
	}
	
	echo $this->Form->input('extension');
	//only the owner of this user profile can change password
	if ($this->Session->read('Auth.User.id') == $this->request->data('User.id')) {
		echo $this->Form->input('pwd', array('label'=> 'Password','type'=>'password', 'value'=>'', 'autocomplete'=>'off'));
		echo $this->Form->input('password_confirm', array('type' => 'password', 'value'=>'', 'autocomplete'=>'off'));
	} elseif (!empty($this->request->data('User.id'))) {
		echo $this->element('users/resetPwPostLink', array(
			'id' => $this->request->data('User.id'),
			'settings' => array('inline' => false, 'class' => 'btn btn-sm btn-primary col-md-offset-4'),
		));
		if ($oktaMfaEnrolled) {
			echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span> ' . __("Disable Okta MFA"),
				['action' => 'reset_okta_mfa', $this->request->data('User.id'), 'admin' => false],
				['class' => 'btn btn-sm btn-danger','inline' => false, 'escape' => false, 'confirm' => __("Disable this user's Okta Multifactor Authentication?\n(A notification will be sent to the user by Okta).")]);
		}
		echo '<br />';
		echo '<br />';
	}
//-------------END FIRST INPUT COLUMN
echo $this->Html->tag('/div');
//-------------START SECOND INPUT COLUMN
echo $this->Html->tag('div', null, array('class' => 'col-md-5'));
echo '<br />';
	echo $this->Html->tag('div', null, array('class' => 'panel panel-success'));
	$apiBtnCss = 'btn btn-xs pull-right ';
	$apiBtnCss .= ($this->request->data('User.api_enabled') || empty($this->request->data('User.id')))? 'btn-default disabled' : 'btn-success';
	echo $this->Html->tag('div', '<strong><span class="glyphicon glyphicon-lock pull-left"></span>API Access Information</strong>' . 
			$this->Html->link('<span class="glyphicon glyphicon-check"></span>' . __(' Enable API'),
				array(
					'action' => 'token',
					'admin' => true,
					$this->request->data('User.id')
				), 
				array('escape' => false, 'class' => $apiBtnCss	)
			),
	array('class' => 'panel-heading text-center'));
		echo $this->Html->tag('div', null, array('class' => 'panel-body'));
			if ($this->request->data('User.api_enabled')) {
				echo $this->Form->input('api_enabled', array('label'=> array('text' => 'API Enabled', 'class' => 'col-md-11 control-label'),'type'=>'checkbox', 'class' => null));
				echo $this->Html->tag('div',
					$this->Form->label('User.token', 'Token', array('class' => 'col-md-4 control-label')) .
					$this->Html->tag('pre', $this->request->data('User.token')),
					array('class' => 'form-group col-md-12'));
				echo $this->Html->tag('div', null, array('class' => 'form-group col-md-12'));
					echo $this->Form->label('User.token_used', 'Token Used On', array('class' => 'col-md-4 control-label'));
					echo $this->Html->tag('pre', $this->Time->format('F jS, Y h:i A', $this->request->data('User.token_used')). '&nbsp;');
				echo $this->Html->tag('/div');
				echo $this->Form->input('token_uses');
			} else {
				$noApiMsg = (empty($this->request->data('User.id')))? ' Cannot enable API while creating a new user. New user must be saved first.': ' API Access not enabled';
				echo $this->Html->tag('pre', 
					$this->Html->tag('span', null, ['class' => 'glyphicon glyphicon-ban-circle']) . $this->Html->tag('/span') . $noApiMsg,
					['class' => 'text-center text-muted']);
			}
	echo $this->Html->tag('/div');
	echo $this->Html->tag('/div');
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
	        <button type="button" name='cobrands_btns' id="all_cobrands_rightAll" class="btn btn-block btn-primary"><i class="glyphicon glyphicon-forward"></i></button>
	        <button type="button" name='cobrands_btns' id="all_cobrands_rightSelected" class="btn btn-block btn-info"><i class="glyphicon glyphicon-chevron-right"></i></button>
	        <button type="button" name='cobrands_btns' id="all_cobrands_leftSelected" class="btn btn-block btn-warning"><i class="glyphicon glyphicon-chevron-left"></i></button>
	        <button type="button" name='cobrands_btns' id="all_cobrands_leftAll" class="btn btn-block btn-danger"><i class="glyphicon glyphicon-backward"></i></button>
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
	    	'options' => array(),
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
<script type='text/javascript' src='/js/users/users-templates.js'></script>
<?php
if ($this->action === 'admin_add') {
	echo $this->Form->hidden('pw_expiry_date');
	echo $this->Form->hidden('password');
	echo $this->Form->hidden('pw_reset_hash');
}
echo $this->Form->end(array('label' => __('Save user'), 'div' => array('class' => 'col-md-12'), 'class' => 'btn btn-sm btn-success'));
echo $this->fetch('postLink'); // output the post link form(s) outside of the main form
?>