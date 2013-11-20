<?php
    echo $this->Html->css('hooza_6', null, array('inline' => false));
    
    if (is_array($errors)) {
        echo $this->Html->scriptBlock("var errors = " . json_encode($errors) . ";", array('inline' => false));
    }
?>

<?php if (in_array($this->request->data['Application']['status'], array('completed', 'signed'))): ?><h3 class="center" style="color: #4D4D4D;">Note: This application has been marked as completed and is read-only.</h3><?php endif; ?>

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

<h4>Step 6 of 6 | Application Validation</h4>

<?php
    if (is_array($errors)) {
        echo '<h4 class="error">Please correct the specified errors (labeled in red)</h4>';
    }
    
    echo $this->Form->create('Application', array('url' => '/applications/app/' . $hooza . '/' . $id . '/' . $hash, 'novalidate' => true));
    echo $this->Form->hidden('id', array('value' => $id));
?>


<?php

                //if ($this->request->data['Application']['want_to_accept_amex'] == 'Yes') {
                //$this->request->data['Application']['rep_amex_discount_rate'] = '3.50%';
           // }
        /*
        echo $this->Form->checkbox('owner');
        echo 'Are you the Owner? </br>';
        echo $this->Form->checkbox('not_owner');
        echo 'Is someone else the owner? </br>';
        */




    if ($this->request->data['Application']['api'] == '1' && ($this->request->data['Application']['status'] != 'pending')) echo $this->Form->end('Validate Application');
    /*else {
        switch ($this->request->data['Application']['status']) {
            //case 'pending':
            case 'completed':
               echo $this->Form->end('Validate Application');
                break;
            default:
                /*
       $repNotify = Router::url(array('controller' => 'applications', 'action' => 'rep_notify', $this->request->data['Application']['id']));
        echo $this->Html->scriptBlock("            
            function repNotify() {
                window.location = '{$repNotify}';
            }
        ");
                 //echo "<input type=\"button\" onclick=\"repNotify();\" value=\"Submit for Review\">";
                echo $this->Form->end('Submit for Review');
        }
    }*/
    //echo 'API = ' . $this->request->data['Application']['api'] . ', STATUS =  '. $this->request->data['Application']['status'];
    




    if ($this->request->data['Application']['api'] == '1' && $this->request->data['Application']['status'] == 'pending') {
        $url = Router::url(array('controller' => 'applications', 'action' => 'send_document', $this->request->data['Application']['id'], False));
        //echo $url;
        //echo $this->request->data['Application']['rs_document_guid'];
        echo $this->Html->scriptBlock("
            function submit_for_signature() {
                
                if (". (is_array($errors) ? '1' : '0') .") {
                    alert('The application must be saved with all required fields completed before submitting for signature.');
                    return null;
                }
                else if (" . ($this->request->data['Application']['status'] == 'signed' ? '1' : '0') . ") {
                    answer = confirm('This application has aleady been signed. Do you really want to resend?');
                    if (!answer) return null
                }
                else if (" . ($this->request->data['Application']['rs_document_guid'] ? '1' : '0') . ") {
                    answer = confirm('This application has aleady been sent for signature. Do you really want to send it again?');
                    if (!answer) return null
                }
                window.location = '{$url}';
            }
        ");
         
       // $signNowURL = Router::url(array('controller' => 'applications', 'action' => 'in_person', $this->request->data['Application']['id']));
        $signNowURL = Router::url(array('controller' => 'applications', 'action' => 'send_document', $this->request->data['Application']['id'], True));
        
        //echo $signNowURL; 
        echo $this->Html->scriptBlock(" 
            
            function signDocNow() {
               
               if (". (is_array($errors) ? '1' : '0') .") {
                    alert('The application must be saved with all required fields completed before submitting for signature.');
                    return null;
                }
                else if (" . ($this->request->data['Application']['status'] == 'signed' ? '1' : '0') . ") {
                    answer = confirm('This application has aleady been signed.');
                    return null;
                }
                
                /*else if (" . ($this->request->data['Application']['rs_document_guid'] ? '1' : '0') . ") {
                    answer = confirm('This application has aleady been sent for signature. Do you really want to send it again?');
                    if (!answer) return null
                }*/

                if(document.getElementById('ApplicationOwnerOwner').checked)
                    window.location = '{$signNowURL}/1';
                if(document.getElementById('ApplicationOwnerNotOwner').checked)
                    window.location = '{$signNowURL}/0';

            }
             
            /*This function calculates the total Application Start up fees and is designed to work 
            * even if more start up fee input fields are added onto the page as long as the string in the
            * id attribue is consistent.
            * patt1: A RegEx pattern used to locate the startup fees input element based its id attribute    
            */
           function addStartUpFees(){
            var patt1=/Startup/gi; 
            var total = 0.00;
            var inputElements = document.getElementsByTagName('input')
                   for(x=0; x < inputElements.length; x++){
                       if(inputElements[x].id.match(patt1) && inputElements[x].id !='ApplicationFeesStartupTotal' && inputElements[x].id !='ApplicationFeesStartupTax'){                       
                            //If not a number set current element's value to zero
                            if (isNaN(Number(inputElements[x].value)))
                            inputElements[x].value = 0;
                            if (!isNaN(inputElements[x].value))
                            var numVal = Number(inputElements[x].value);                            
                            total = total + numVal;
                        }                
                    }
            document.getElementById('ApplicationFeesStartupTotal').value = total.toFixed(2);
          }     
                
        ");

        echo "<br/>Congratulations Your Application Has Been Validated. <br/>Please Click the Button Below to Review and Sign Your Application <br/><br/>";
        //echo "<input type=\"button\" onclick=\"submit_for_signature();\" value=\"Submit for Signature\">" . "<br/>";   
        
        $options=array('owner'=>'I certify that I am ' . $this->request->data['Application']['owner1_fullname'] . '.',
            'not_owner'=>'I am not ' . $this->request->data['Application']['owner1_fullname'] . ', please send the application to ' . $this->request->data['Application']['owner1_email'] . ' for signature.');
        $attributes=array('legend'=>false, onclick=>"document.getElementById('signOrSendBtn').style.display='block'");
        echo $this->Form->radio('owner',$options,$attributes) . '</br>';
        echo "<input type=\"button\" id=\"signOrSendBtn\" style=\"display:none\" onclick=\"signDocNow();\" value=\"Sign Application\">";
        
        
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
    
    echo $this->Html->scriptBlock("
        //This code must always be at the bottom of this page. This controls the GUI 
            for(x=0;x<document.getElementsByName('data[Application][owner]').length;x++){            
                if(document.getElementsByName('data[Application][owner]')[x].checked){     
                    document.getElementById('signOrSendBtn').style.display='block';
                    break;
                    }
            }
")
?>

