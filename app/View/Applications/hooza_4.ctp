<?php
    echo $this->Html->css('step_4', null, array('inline' => false));
    
    if (is_array($errors)) {
        echo $this->Html->scriptBlock("var errors = " . json_encode($errors) . ";", array('inline' => false));
    }
?>

<?php if (in_array($this->request->data['Application']['status'], array('completed', 'signed'))): ?><h3 class="center">Note: This application has been marked as completed and is read-only.</h3><?php endif; ?>

<p class="steps_blocks">
    <?php
        if (in_array($this->request->data['Application']['status'], array('pending', 'completed', 'signed')) || in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
            for ($i=0; $i<6; $i++) {
                echo $this->Html->link($this->Html->image('steps_block.png', array('alt'=> __('Hooza ' . ($i + 1)), 'border' => '0')), '/applications/app/' . ($i + 1) . '/' . $id . '/' . $hash, array('title' => 'Hooza '. ($i + 1), 'escape' => false)) . ' &nbsp; ';
            }
        }
        else {
            for ($i=0; $i<$hooza; $i++) {
                echo $this->Html->link($this->Html->image('steps_block.png', array('alt'=> __('*'), 'border' => '0')), '/applications/app/' . ($i + 1) . '/' . $id . '/' . $hash, array('title' => 'Hooza '. ($i + 1), 'escape' => false)) . ' &nbsp; ';
            }
        }
    ?>
</p>

<h4>Step 4 of 6 | Set Up Information</h4>

<?php
    if (is_array($errors)) {
        echo '<h4 class="error">Please correct the specified errors (labeled in red)</h4>';
    }
    
    echo $this->Form->create('Application', array('url' => '/applications/app/' . $hooza . '/' . $id . '/' . $hash, 'novalidate' => true));
    echo $this->Form->hidden('id', array('value' => $id));
?>

<table>
    <tr><th colspan="2">American Express Information</th></tr>
    <tr>
        <td>
            <?php echo $this->Html->div(
                'want_to_accept_amex radio2',
                '<span>Do you want to accept American Express? *</span> ' .
                $this->Form->radio('want_to_accept_amex', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false))
                    . "<br><br>Note: The American Express Rate for Internet Merchants is 3.50%"
            ); ?>
        </td>
    </tr>
</table>


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
            if (". (is_array($errors) ? '1' : '0') .") {
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
