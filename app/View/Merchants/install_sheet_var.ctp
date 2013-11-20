
<?php
    if ($this->request->data['Merchant']['merchant_id'] == ""){
        echo '<h4>This Application Has not been boarded into the Database!<br/>Please contact <a href="mailto:support@axiapayments.com?Subject=>support@axiapayments.com</a></h4>';
    } else {
    echo '<h4>Merchant Install Sheet - VAR</h4>';

    echo '<p>Contact Info</p>';

    if ($error) echo $this->Html->div('error', $error);
    echo $this->Form->create('Application', array('controller' => 'applications', 'action' => 'retrieve'));
    echo '<table width="800">';
    echo '<tr>';
    echo '<td><strong>Rep:</strong> ' . $this->request->data['User']['firstname'] . ' ' . $this->request->data['User']['lastname'] . '</td>';
    echo '<td><strong>Merchant:</strong> ' . $this->request->data['Application']['dba_business_name'] . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><strong>Phone:</strong> ' . '877.875.6114 x' .  $this->request->data['User']['extension'] . '</td>';
    echo '<td><strong>Contact:</strong> ' . $this->request->data['Application']['corp_contact_name']. '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><strong>Fax:</strong> ' . '877.875.5135' . '</td>';
    echo '<td><strong>Phone/Fax:</strong> ' . $this->request->data['Application']['location_phone'] . ' / '. $this->request->data['Application']['location_fax'] . '</td>';
    echo '</tr>';
    echo '</table>';
    echo $this->Html->div(
                        'firstname',
                        $this->Form->label('firstname', 'Rep First Name') .
                        $this->Form->text('User.firstname')
                    );
    echo $this->Html->div(
                        'lastname',
                        $this->Form->label('lastname', 'Rep Last Name') .
                        $this->Form->text('User.lastname')
                    );
    echo '<p>Installation Information</p>';
    
    echo $this->Html->div(
                        'location_address',
                        $this->Form->label('location_address', 'Business Address') .
                        $this->Form->text('location_address')
                    ); 
    echo $this->Html->div(
                        'location_city',
                        $this->Form->label('location_city', 'Business City') .
                        $this->Form->text('location_city')
                    ); 
    echo $this->Html->div(
                        'location_state',
                        $this->Form->label('location_state', 'Business State') .
                        $this->Form->text('location_state')
                    );
    echo $this->Html->div(
                        'location_zip',
                        $this->Form->label('location_zip', 'Business Zip') .
                        $this->Form->text('location_zip')
                    );
    echo $this->Html->div(
                        'merchant_id',
                        $this->Form->label('merchant_id', 'Merchant ID') .
                        $this->Form->text('Merchant.merchant_id')
                    );
    //System Type
    echo $this->Html->div(
                        'corp_contact_name',
                        $this->Form->label('corp_contact_name', 'Installation Contact') .
                        $this->Form->text('corp_contact_name')
                    );
    echo $this->Html->div(
                        'email',
                        $this->Form->label('email', 'Email') .
                        $this->Form->text('email')
                    );
    echo $this->Html->div(
                        'phone',
                        $this->Form->label('phone', 'Phone') .
                        $this->Form->text('phone')
                    );
    echo $this->Html->div(
                        'fax',
                        $this->Form->label('fax', 'Fax') .
                        $this->Form->text('fax')
                    );
    echo $this->Html->div(
                        'customer_id',
                        $this->Form->label('customer_id', 'Customer ID') .
                        $this->Form->text('customer_id')
                    );
    echo $this->Html->div(
                        'support_desk_number',
                        $this->Form->label('support_desk_number', 'Support Desk Number') .
                        $this->Form->text('support_desk_number')
                    );
    echo $this->Html->div(
                        'notes',
                        $this->Form->label('notes', 'Notes') .
                        $this->Form->text('notes')
                    );
    echo $this->Form->end('Submit');
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
?>
