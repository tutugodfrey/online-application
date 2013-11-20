
<?php
    echo $this->Html->script('prototype');  
    echo $this->Html->script('scriptaculous/src/scriptaculous.js?load=effects'); 
    if ($data['Merchant']['merchant_id'] == ""){
        echo '<h4>This Application Has not been boarded into the Database!<br/>Please contact <a href="mailto:support@axiapayments.com?subject= Onlineapp ' . $data['Application']['id'] . ' not boarded">support@axiapayments.com</a></h4>';
    } else {
    echo '<h4>Merchant Install Sheet - VAR</h4>';

    echo '<p>Contact Info</p>';
    if (is_array($errors)) {
        echo $this->Html->scriptBlock("var errors = " . json_encode($errors) . ";", array('inline' => false));
    }
    //if ($error) echo $this->Html->div('error', $error);
    echo $this->Form->create('Application', array('url' => '/applications/install_sheet_var/' . $data['Application']['id']));
    //echo $this->Form->create('Application', array('controller' => 'applications', 'action' => 'sent_var_install'));
    //echo $this->Form->create('Application', array('action' => 'install_sheet_var'));
    echo '<table width="800">';
    echo '<tr>';
    echo '<td><strong>Rep:</strong> ' . $data['Application']['rep_contractor_name'] . '</td>';
    echo '<td><strong>Merchant:</strong> ' . $data['Application']['dba_business_name'] . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><strong>Phone:</strong> ' . '877.875.6114 x' .  $data['User']['extension'] . '</td>';
    echo '<td><strong>Contact:</strong> ' . $data['Application']['corp_contact_name']. '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><strong>Fax:</strong> ' . '877.875.5135' . '</td>';
    echo '<td><strong>Phone/Fax:</strong> ' . $data['Application']['location_phone'] . ' / '. $data['Application']['location_fax'] . '</td>';
    echo '</tr>';
    echo '</table>';
    foreach($data['Merchant']['EquipmentProgramming'] as $programming) {
        echo 'Terminal Type: ' . $programming['terminal_type'];
    }
    echo '<p>Installation Information</p>';
    echo '<strong>Address: </strong>' . $data['Application']['location_address'] . '<br/>';
    echo '<strong>City: </strong>' . $data['Application']['location_city'] . '<br/>';
    echo '<strong>State: </strong>' . $data['Application']['location_state'] . '<br/>';
    echo '<strong>Zip: </strong>' . $data['Application']['location_zip'] . '<br/>';
    echo '<strong>Merchant ID: </strong>' . $data['Merchant']['merchant_id'] . '<br/><br/>';
    foreach($data['Application'] as $application) {
        if (preg_match("/@/",$application)) {
        $applications .= $application . ',';
        }
    }
    $applications = explode(",",$applications, -1);
    $applications = array_unique($applications);

    foreach ($applications as $key => $value) {
        $app_array[$value] = $value;
    }
    
    if ($data['Application']['var_status'] == 'sent') {
        $div_string = 'show.hide';
        echo '<strong>This Installation sheet was already sent for Signing.</strong><br/>';
        echo $this->Ajax->link('Click to Send Again.', '', array('update' => $div_string, 'loading' => 'Effect.Appear(\''.$div_string.'\')',)) . '<br/>';
        echo $this->Ajax->div($div_string, array('style' => 'display:none'));
    }
    if ($data['Application']['var_status'] == 'signed') {
        $div_string = 'show.hide';
        echo '<strong>This Installation sheet has already been signed.</strong><br/>';
        echo $this->Ajax->link('Click to Send Again.', '', array('update' => $div_string, 'loading' => 'Effect.Appear(\''.$div_string.'\')',)) . '<br/>';
        echo $this->Ajax->div($div_string, array('style' => 'display:none'));
    }
    echo 'Please select or enter an email address for the Install Sheet Recipient <br/>';
    echo '<table><tr class="select_email_address">';
    echo '<td>' . $this->Html->div('select_email_address', $this->Form->label('select_email_address', 'Select Email Address:') . '</td><td>' . $this->Form->select('select_email_address',
            $app_array, null) . '</td></tr>');
    echo '<tr class="enter_email_address"><td>' . $this->Html->div('enter_email_address', $this->Form->label('enter_email_address', 'Enter Email Address: ') . '</td><td>' . $this->Form->text('enter_email_address') . '</td>');
    //echo '<tr class="installer"><td>' . $this->Html->div('installer', $this->Form->label('installer', 'Installer/Trainer: ') . '</td><td>' . $this->Form->text('installer') . '</td>');
    //echo $this->Form->input('installer');
    echo '</tr></table>';
    echo $this->Form->end('Submit');
        echo $this->Ajax->divEnd($div_string);
    }
?>
<?php
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
                        if ($('.' + field + ' input').filter(':first').length) {
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
