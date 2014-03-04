<?php
	echo $this->Html->css('step_4', null, array('inline' => false));

	if (isset($errors) && is_array($errors)) {
		echo $this->Html->scriptBlock("var errors = " . json_encode($errors) . ";", array('inline' => false));
	}
?>

<?php if (in_array($this->request->data['Application']['status'], array('completed', 'signed'))): ?><h3 class="center">Note: This application has been marked as completed and is read-only.</h3><?php endif; ?>

<p class="steps_blocks">
	<?php
		if (in_array($this->request->data['Application']['status'], array('pending', 'completed', 'signed')) || in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
			for ($i=0; $i<6; $i++) {
				echo $this->Html->link($this->Html->image('steps_block.png', array('alt'=> __('Step ' . ($i + 1)), 'border' => '0')), '/applications/add/' . ($i + 1) . '/' . $id . '/' . $hash, array('title' => 'Step '. ($i + 1), 'escape' => false)) . ' &nbsp; ';
			}
		}
		else {
			for ($i=0; $i<$step; $i++) {
				echo $this->Html->link($this->Html->image('steps_block.png', array('alt'=> __('*'), 'border' => '0')), '/applications/add/' . ($i + 1) . '/' . $id . '/' . $hash, array('title' => 'Step '. ($i + 1), 'escape' => false)) . ' &nbsp; ';
			}
		}
	?>
</p>

<h4>Step 4 of 6 | Set Up Information</h4>

<?php
	if (isset($errors) && is_array($errors)) {
		echo '<h4 class="error">Please correct the specified errors (labeled in red)</h4>';
	}
	
	echo $this->Form->create('Application', array('url' => '/applications/add/' . $step . '/' . $id . '/' . $hash, 'novalidate' => true));
	echo $this->Form->hidden('id', array('value' => $id));
?>

<table>
	<tr><th colspan="2">American Express Information</th></tr>
	<tr><td colspan="2" class="currently_accept_amex">Do you currently accept American Express? *</td></tr>
	<tr>
		<td class="radio" style="width: 65px;"><input type="radio" name="data[Application][currently_accept_amex]" id="ApplicationCurrentlyAcceptAmexYes" value="yes" <?php echo ($this->request->data['Application']['currently_accept_amex'] == 'yes' ? 'checked="checked"' : ''); ?>><label for="ApplicationCurrentlyAcceptAmexYes">Yes</label></td>
		<td>
			<?php echo $this->Html->div(
				'existing_se_num',
				$this->Form->label('existing_se_num', 'Please Provide Existing SE# ', array('style' => 'display: inline;')) .
				$this->Form->text('existing_se_num')
			); ?>
		</td>
	<tr>
	<tr>
		<td class="radio" style="width: 65px;"><input type="radio" name="data[Application][currently_accept_amex]" id="ApplicationCurrentlyAcceptAmexNo" value="no" <?php echo ($this->request->data['Application']['currently_accept_amex'] == 'no' ? 'checked="checked"' : ''); ?>><label for="ApplicationCurrentlyAcceptAmexNo">No</label></td>
		<td>
			<?php echo $this->Html->div(
				'want_to_accept_amex radio2',
				'<span>Do you want to accept American Express?</span> ' .
				$this->Form->radio('want_to_accept_amex', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false))
			); ?>
		</td>
	<tr>
</table>

<h4 style="margin-bottom: 0px;">Discover Information</h4>
<?php echo $this->Html->div(
	'want_to_accept_discover radio2',
	'<span>Do you want to accept Discover? *</span> ' .
	$this->Form->radio('want_to_accept_discover', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false))
); ?>

<p><hr /></p>

<table class="nowrap">
	<tr><th colspan="2">Terminal/Software Type (1)</th></tr>
	<tr>
		<td style="width: 300px;">
			<?php echo $this->Html->div(
				'term1_quantity',
				$this->Form->label('term1_quantity', 'Quantity * ', array('style' => 'display: inline;')) .
				$this->Form->text('term1_quantity'),
				array('style' => 'display: inline-block')
			); ?> &nbsp;
			<?php echo $this->Html->div(
				'term1_type',
				$this->Form->label('term1_type', 'Type * ', array('style' => 'display: inline;')) .
				$this->Form->text('term1_type'),
				array('style' => 'display: inline-block')
			); ?>
		</td>
		<td class="term1_use_autoclose">
			<strong>Do You Use Autoclose? *</strong>
		</td>
	</tr>
	<tr>
		<td style="width: 300px;">
			<?php echo $this->Html->div(
				'term1_provider radio2',
				'<span>Provider: *</span> ' .
				$this->Form->radio('term1_provider', array('axia' => 'Axia', 'merchant' => 'Merchant'), array('legend' => false))
			); ?>
		</td>
		<td>
			<?php echo $this->Html->div(
				'term1_use_autoclose radio2',
				$this->Form->radio('term1_use_autoclose', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false)) .
				' &nbsp; &nbsp; ' . $this->Form->input('term1_what_time', array('type' => 'time', 'interval' => 15,'label' => 'If Yes, What Time? ', 'style' => 'display: inline;', 'div' => false, 'timeFormat' => 24, 'default' => '00:00' ))
			);
			
//            echo $this->Form->input('term1_what_time', array('type' => 'time', 'label' => 'If Yes, What Time? ', 'style' => 'display: inline;'));
//            echo $this->Html->div(
//                'term1_what_time',
//                $this->Form->label('bank_phone', 'If Yes, What Time? ', array('style' => 'display: inline;')) .
//                $this->Form->text('term1_what_time')
//            ); ?>
		</td>
	</tr>
</table>


<div class="terminal_prog_info">
<?php
	echo '<h4>Terminal Programming Information (please select all that apply):</h4>';
	echo $this->Html->div(
		'term1_programming_avs',
		$this->Form->checkbox('term1_programming_avs') .
		$this->Form->label('term1_programming_avs', 'AVS')
	);
	echo $this->Html->div(
		'term1_programming_server_nums',
		$this->Form->checkbox('term1_programming_server_nums') .
		$this->Form->label('term1_programming_server_nums', 'Server #s')
	);
	echo $this->Html->div(
		'term1_programming_tips',
		$this->Form->checkbox('term1_programming_tips') .
		$this->Form->label('term1_programming_tips', 'Tips')
	);
	echo $this->Html->div(
		'term1_programming_invoice_num',
		$this->Form->checkbox('term1_programming_invoice_num') .
		$this->Form->label('term1_programming_invoice_num', 'Invoice #')
	);
	echo $this->Html->div(
		'term1_programming_purchasing_cards',
		$this->Form->checkbox('term1_programming_purchasing_cards') .
		$this->Form->label('term1_programming_purchasing_cards', 'Purchasing Cards')
	);
?>
</div>
<div style="clear: left;"></div>

<?php
	echo '<h4 class="term1_accept_debit">Do you accept Debit on this terminal? *</h4>';
	echo $this->Html->div(
		'term1_accept_debit radio2',
		$this->Form->radio('term1_accept_debit', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false)) .
		' &nbsp; &nbsp; '
	);
	echo $this->Html->div(
		'term1_pin_pad_type',
		$this->Form->label('term1_pin_pad_type', 'If Yes, what type of PIN Pad? ') .
		$this->Form->text('term1_pin_pad_type') .
		' &nbsp; &nbsp; '
	);
	echo $this->Html->div(
		'term1_pin_pad_qty',
		$this->Form->label('term1_pin_pad_qty', 'Quantity ') .
		$this->Form->text('term1_pin_pad_qty')
	);
?>

<p><hr /></p>

<table class="nowrap">
	<tr><th colspan="2">Terminal/Software Type (2)</th></tr>
	<tr>
		<td style="width: 300px;">
			<?php echo $this->Html->div(
				'term2_quantity',
				$this->Form->label('term2_quantity', 'Quantity ', array('style' => 'display: inline;')) .
				$this->Form->text('term2_quantity'),
				array('style' => 'display: inline-block')
			); ?> &nbsp;
			<?php echo $this->Html->div(
				'term2_type',
				$this->Form->label('term2_type', 'Type ', array('style' => 'display: inline;')) .
				$this->Form->text('term2_type'),
				array('style' => 'display: inline-block')
			); ?>
		</td>
		<td class="term2_use_autoclose">
			<strong>Do You Use Autoclose?</strong>
		</td>
	</tr>
	<tr>
		<td style="width: 300px;">
			<?php echo $this->Html->div(
				'term2_provider radio2',
				'<span>Provider:</span> ' .
				$this->Form->radio('term2_provider', array('axia' => 'Axia', 'merchant' => 'Merchant'), array('legend' => false))
			); ?>
		</td>
		<td>
			<?php echo $this->Html->div(
				'term2_use_autoclose radio2',
				$this->Form->radio('term2_use_autoclose', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false)) .
				' &nbsp; &nbsp; ' . $this->Form->input('term2_what_time', array('type' => 'time', 'interval' => 15,'label' => 'If Yes, What Time? ', 'style' => 'display: inline;', 'div' => false, 'timeFormat' => 24, 'default' => '00:00' ))
			);
//            echo $this->Html->div(
//                'term2_what_time',
//                $this->Form->label('bank_phone', 'If Yes, What Time? ', array('style' => 'display: inline;')) .
//                $this->Form->text('term2_what_time')
//            ); ?>
		</td>
	</tr>
</table>


<div class="terminal_prog_info">
<?php
	echo '<h4>Terminal Programming Information (please select all that apply):</h4>';
	echo $this->Html->div(
		'term2_programming_avs',
		$this->Form->checkbox('term2_programming_avs') .
		$this->Form->label('term2_programming_avs', 'AVS')
	);
	echo $this->Html->div(
		'term2_programming_server_nums',
		$this->Form->checkbox('term2_programming_server_nums') .
		$this->Form->label('term2_programming_server_nums', 'Server #s')
	);
	echo $this->Html->div(
		'term2_programming_tips',
		$this->Form->checkbox('term2_programming_tips') .
		$this->Form->label('term2_programming_tips', 'Tips')
	);
	echo $this->Html->div(
		'term2_programming_invoice_num',
		$this->Form->checkbox('term2_programming_invoice_num') .
		$this->Form->label('term2_programming_invoice_num', 'Invoice #')
	);
	echo $this->Html->div(
		'term2_programming_purchasing_cards',
		$this->Form->checkbox('term2_programming_purchasing_cards') .
		$this->Form->label('term2_programming_purchasing_cards', 'Purchasing Cards')
	);
?>
</div>
<div style="clear: left;"></div>

<?php
	echo '<h4 class="term2_accept_debit">Do you accept Debit on this terminal?</h4>';
	echo $this->Html->div(
		'term2_accept_debit radio2',
		$this->Form->radio('term2_accept_debit', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false)) .
		' &nbsp; &nbsp; '
	);
	echo $this->Html->div(
		'term2_pin_pad_type',
		$this->Form->label('term2_pin_pad_type', 'If Yes, what type of PIN Pad? ') .
		$this->Form->text('term2_pin_pad_type') .
		' &nbsp; &nbsp; '
	);
	echo $this->Html->div(
		'term2_pin_pad_qty',
		$this->Form->label('term2_pin_pad_qty', 'Quantity ') .
		$this->Form->text('term2_pin_pad_qty')
	);
?>

<p><hr /></p>

<p>Fields marked with * are required.</p>

<?php
	echo $this->Form->end('Save & Continue to Next Step ->');
	
	if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
		echo $this->Html->link(
			'Return to Applications Admin',
			'/admin/applications/',
			array('style' => 'display: block; float: right;')
		);
	}
	
	echo $this->Html->scriptBlock("
		$(document).ready(function() {
			if (". (isset($errors) && is_array($errors) ? '1' : '0') .") {
				for (field in errors) {
					if ($('.' + field).length > 0) {
						if (field == 'currently_accept_amex') {
							$('td.' + field).filter(':first').css('color', '#f00');
						}
						else if (field == 'want_to_accept_amex') {
							$('.' + field + ' span').filter(':first').css('color', '#f00');
						}
						else if (field == 'want_to_accept_discover') {
							$('.' + field + ' span').filter(':first').css('color', '#f00');
						}
						else if (field == 'term1_provider') {
							$('.' + field + ' span').filter(':first').css('color', '#f00');
						}
						else if (field == 'term1_use_autoclose') {
							$('td.' + field).filter(':first').css('color', '#f00');
						}
						else if (field == 'term1_accept_debit') {
							$('h4.' + field).filter(':first').css('color', '#f00');
						}
						else if (field == 'term2_provider') {
							$('.' + field + ' span').filter(':first').css('color', '#f00');
						}
						else if (field == 'term2_use_autoclose') {
							$('td.' + field).filter(':first').css('color', '#f00');
						}
						else if (field == 'term2_accept_debit') {
							$('h4.' + field).filter(':first').css('color', '#f00');
						}
						else if ($('.' + field + ' input').filter(':first').length) {
							$('.' + field + ' label').filter(':first').css('color', '#f00');
						}
						else if ($('.' + field + ' select').filter(':first').length) {
							$('.' + field + ' label').filter(':first').css('color', '#f00');
						}
					}
				}
			}
		});
	", array('inline' => false));
?>
