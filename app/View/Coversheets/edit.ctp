<?php echo $this->Html->css('coversheet', null, array('inline' => false)); ?>
<?php if ($data['Coversheet']['status'] != 'saved') { ?>
<script type="text/javascript">                                        
   $(document).ready(function () {
   $('#CoversheetEditForm :input').attr('disabled', true);
   });
</script>
<?php } else{
    echo $this->Html->script('prototype');  
    echo $this->Html->script('scriptaculous/src/scriptaculous.js?load=effects');
}
?>
<div class="Coversheets form">
<?php echo $this->Form->create('Coversheet', array('novalidate' => true));?>
	<fieldset>
		<legend><?php echo __('Add %s', __('Coversheet')); ?></legend>
	<?php echo $this->Form->hidden('id'); ?>
                <table style="border: 1px solid black; background-color:  #999A8F; width: 100%">
                    <tr>
                        <td style="text-align: center;"><strong>Setup Information</strong></td>
                    </tr>
                    
                </table>
                <table style="border: 1px solid black; width: 100%">
		<tr>
                
                <td ><?php echo 'Rep Name: '. $data['User']['firstname'] . $data['User']['lastname']; ?></td>
                
                <td ><?php echo 'Merchant: ' . $data['CobrandedApplication']['DBA']; ?></td>
                
                </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                        <td><?php echo "Attached:"?></td>
                        <td><?php echo $this->Form->input('setup_banking', array('label' => "Banking (no starter checks)")); ?></td>
                        <td><?php echo $this->Form->input('setup_statements', array('label' => "3 Mo. Processing Stmts")); ?></td>
                        <td><?php echo $this->Form->input('setup_drivers_license', array('label' => "Owner's Driver's License")); ?></td>
                    </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                        <td><?php echo "For New Businesses:"?></td>
                        <td><?php echo $this->Form->input('setup_business_license', array('label' => "Business License or Utility Bill")); ?></td>
                        <td><?php echo $this->Form->input('setup_other', array('label' => "Other:")); ?></td>
                        <td style="width: 25%;"><?php echo $this->Form->input('setup_field_other', array('div' => false, 'label' => false, 'style' => 'width: 120px;','size' => '20')); ?></td>
                    </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios <?php if ($tier == 'tier1') echo 'bg-highlight'; ?>">
                    <tr>
                    <td style="width: 20%;">
                    <?php 
                    $options = array('1' => 'Tier 1:');
                    echo $this->Form->radio('setup_tier_select', $options); 
                    if ($this->Form->isFieldError('setup_tier_select')){
                    echo $this->Form->error('setup_tier_select');
                    }
                    ?>
                    
                    </td>
                    <td style="width: 80%;">Retail: Volume $0-$250,000/month, Average Ticket Less Than $1,000</td>
                    </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios <?php if ($tier == 'tier2') echo 'bg-highlight'; ?>">
                    <tr>
                    <td style="width: 20%;">
                    <?php 
                    $options = array('2' => 'Tier 2:');
                    $attributes=array('hiddenField' => false);
                    echo $this->Form->radio('setup_tier_select', $options, $attributes); ?>
                    </td>
                    <td style="width: 80%;">MOTO: Volume $0-$150,000, Average Ticket Less Than $1,000</td>
                    </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios <?php if ($tier == 'tier3') echo 'bg-highlight'; ?>">
                    <tr>
                    <td style="width: 20%;">
                    <?php 
                    $options = array('3' => 'Tier 3:');
                    $attributes=array('hiddenField' => false);
                    echo $this->Form->radio('setup_tier_select', $options, $attributes); ?>
                    </td>
                    <td style="width: 80%;">Retail: Volume $250,000 or Greater/month, Average Ticket Less Than $1,000</td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">Required:</td>
                    <td style="width: 80%;"><?php echo $this->Form->input('setup_tier3',array('label' => '2 years business financials or 2 years tax returns')); ?></td>
                    </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios <?php if ($tier == 'tier4') echo 'bg-highlight'; ?>">
                    <tr>
                    <td style="width: 20%;">
                        <?php 
                        $options = array('4' => 'Tier 4:');
                        $attributes=array('hiddenField' => false);
                        echo $this->Form->radio('setup_tier_select', $options, $attributes); ?>
                    </td>
                    <td style="width: 80%;">Retail: Volume $0-$250,000/month, Average Ticket Greater Than $1,000</td>
                    </tr>
                    <tr>
                    <td style="width: 20%;"></td>
                    <td style="width: 80%;">MOTO/Internet: Volume $0-$150,000/month, Average Ticket Greater than $1,000</td>
                    </tr>
                    <tr>
                    <td style="width: 20%;">Required:</td>
                    <td style="width: 80%;"><?php echo $this->Form->input('setup_tier4',array('label' => '2 years business financials or 2 years tax returns')); ?></td>
                    </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios <?php if ($tier == 'tier5') echo 'bg-highlight'; ?>">
                    <tr>
                    <td style="width: 20%;">
                        <?php 
                        $options = array('5' => 'Tier 5:');
                        $attributes=array('hiddenField' => false);
                        echo $this->Form->radio('setup_tier_select', $options, $attributes); ?>                        
                    </td>
                    <td style="width: 80%;">Retail: Volume $250,000 or Greater/month, Average Ticket Greater Than $1,000</td>
                    </tr>
                    <tr>
                    <td style="width: 20%;"></td>
                    <td style="width: 80%;">MOTO/Internet: Volume $150,000 or Greater/month</td>
                    </tr>
                    <tr>
                    <td style="width: 20%;"></td>
                    <td style="width: 80%;"><?php echo $this->Form->input('setup_tier5_financials',array('label' => '2 years audited business financials or 2 years tax returns')); ?></td>
                    </tr>
                    <tr>
                    <td style="width: 20%;"></td>
                    <td style="width: 80%;"><?php echo $this->Form->input('setup_tier5_processing_statements',array('label' => '6 months processing statements')); ?></td>
                    </tr>
                    <tr>
                    <td style="width: 20%;"></td>
                    <td style="width: 80%;"><?php echo $this->Form->input('setup_tier5_bank_statements',array('label' => '3 months bank statements')); ?></td>
                    </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                        <td style="width: 30%;">
                            <?php 
                            if ($this->Form->isFieldError('setup_equipment_terminal') && $this->Form->isFieldError('setup_equipment_gateway')){
                            echo $this->Form->error('setup_equipment_terminal');
                            } else echo 'Equipment Type:'; ?></td>
                        <td style="width: 30%;"><?php echo $this->Form->input('setup_equipment_terminal', array('label' => 'Terminal', 'error' => false));  ?></td>
                        <td style="width: 40%;"><?php echo $this->Form->input('setup_equipment_gateway', array('label' => 'POS/Gateway', 'error' => false)); ?></td>
                             
                   </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                        <td><?php 
                            if ($this->Form->isFieldError('setup_install')){
                            echo 'Install to be done by: ' . $this->Form->error('setup_install');
                            } else 
                            echo 'Install to be done by:'; ?> </td>
                        <td>
                        <?php 
                        $options = array('rep' => 'Rep');
                        echo $this->Form->radio('setup_install', $options);

                            ?>
                        </td>
                        <td>
                        <?php 
                        $options = array('axia' => 'Axia');
                        $attributes=array('hiddenField' => false);                        
                        echo $this->Form->radio('setup_install', $options, $attributes); ?>
                        </td>    
                        <td>
                        <?php 
                        $options = array('pos' => 'POS/Gateway Provider');
                        $attributes=array('hiddenField' => false);                        
                        echo $this->Form->radio('setup_install', $options, $attributes); ?>
                        </td>
                   </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                        <td style="width: 50%;">
                            <?php if ($this->Form->isFieldError('setup_starterkit')){
                            echo 'Starter kits supplies provided by (terminal only): ' . $this->Form->error('setup_starterkit');
                            } else echo 'Starter kits supplies provided by (terminal only):'; 
                                ?>
                        </td>
                        <td style="width: 10%;">
                            <?php 
                        $options = array('rep' => 'Rep');
                        echo $this->Form->radio('setup_starterkit', $options);

                            ?>
                        </td>
                        <td style="width: 40%;">
                            <?php 
                        $options = array('axia' => 'Axia (ship or drop off)');
                        $attributes = array('hiddenField' => false);                        
                        echo $this->Form->radio('setup_starterkit', $options, $attributes); ?>
                        </td>    

                   </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                        <td>
                            <?php
                            if ($this->Form->isFieldError('setup_equipment_payment')) {
                                echo 'Equipment Payment: ' . $this->Form->error('setup_equipment_payment');
                            } else echo 'Equipment Payment:';
                            ?>
                        </td>
                        <td>
                            <?php 
                            $options = array('ach' => 'ACH');
                            echo $this->Form->radio('setup_equipment_payment',$options);
                            ?>
                        </td>
                        <td>
                            <?php
                            $options = array('lease' => 'Lease - Terms: ');
                            $attributes = array('hiddenField' => false);
                            echo $this->Form->radio('setup_equipment_payment',$options,$attributes); ?>
                        </td>

                        <td><?php echo $this->Form->input('setup_lease_price',array('label' => '$ per month','style' => 'width:40px','size' => '5')); ?></td>
                        <td><?php echo $this->Form->input('setup_lease_months',array('label' => '# of months','style' => 'width:40px','size' => '5')); ?></td>
                    </tr>
                </table>
                <table style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                        <td>Debit Volume: <?php echo $this->Form->input('setup_debit_volume',array('div' => false, 'label' => false,'style' => 'width:100px','size' => '20'));?></td>
                        <td>Monthly Item Count: <?php echo $this->Form->input('setup_item_count',array('div' => false, 'label' => false,'style' => 'width:100px','size' => '20'));?></td>
                    </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr class="radios">
                        <td>Referrer: <?php echo $this->Form->input('setup_referrer',array('div' => false, 'label' => false, 'style' => 'width:150px', 'size' => '25')); ?></td>
                        <td><?php 
                        $options=array('gp' => 'GP','bp' => 'BP');
                        $attributes=array('legend' => false);
                        echo $this->Form->radio('setup_referrer_type',$options,$attributes); ?></td>
                        <td><?php echo $this->Form->input('setup_referrer_pct',array('div' => false, 'label' => '%','style' => 'width:50px','size' => '2')); ?></td>
                        
                        <td>Reseller<?php echo $this->Form->input('setup_reseller',array('div' => false, 'label' => false,'style' => 'width:150px','size' => '25')); ?></td>
                        <td><?php 
                        $options=array('gp' => 'GP','bp' => 'BP');
                        $attributes=array('legend' => false);
                        echo $this->Form->radio('setup_reseller_type',$options,$attributes); ?></td>
                        <td><?php echo $this->Form->input('setup_reseller_pct',array('div' => false, 'label' => '%','style' => 'width:50px', 'size' => '2')); ?></td>
                        
                    </tr>
                </table>
                <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                        <td>Setup Notes: <?php echo $this->Form->input('setup_notes', array('rows' => '2', 'div' => false, 'label' => false,'style' => 'width:100%', 'size' => '255')); ?></td>
                    </tr>
                </table>
                <table style="border: 1px solid black; width: 100%; background-color: #999A8F;">
                    <tr>
                    <td style="text-align: center"><strong>Merchant Info Questionnaire</strong></td>
                    
                    </tr>
                </table>
                <?php  if ($data['Coversheet']['status'] == 'saved'){
                if ($cp != true): ?>
                <table>
                    <tr>
                     <td>
                     <?php
                     
                     
                     $div_show = 'ShowCpLink';
                     $div_hide = 'HideCpLink';
                     $div_string = 'Cp';
                     echo '<div id="'.$div_show.'">';
                     //echo $this->Js->link('Click for Card Present Merchant', '', array('update' => array($div_string,$div_hide,$div_show), 'loading' => 'Effect.Appear(\''.$div_string.'\'),Effect.Appear(\''.$div_hide.'\'),Effect.Fade(\''.$div_show.'\')')); 
                     echo $this->Html->link('Click for Card Present Merchant', 'javascript:void(0)', array('onclick' => 'Effect.Appear(\''.$div_string.'\'),Effect.Appear(\''.$div_hide.'\'),Effect.Fade(\''.$div_show.'\')'));
                     echo '</div>'; 
                     echo '<div id="'.$div_hide.'" style=display:none;">';
                     echo $this->Html->link('Hide Card Present Merchant', 'javascript:void(0)', array('onclick' => 'Effect.Fade(\''.$div_string.'\'),Effect.Fade(\''.$div_hide.'\'),Effect.Appear(\''.$div_show.'\')'));
                     echo '</div>'; 
                     
                     ?>                         
                     </td>
                    </tr>
                </table>
                     <?php 
                     echo '<div id="'.$div_string.'" style=display:none;">';
                     endif;
                }
                     if(($data['Coversheet']['status'] != 'saved' && $cp != false)  || $data['Coversheet']['status'] == 'saved') {
                     ?>
                  

                 <table  style="border: 1px solid black; width: 100%;" class="radios">

                     <tr>
                                                
                         <td colspan="4"><strong>Card Present Merchant:</strong></td>
                         
                    </tr>                  
                     <tr >
                          
                            <td style="width: 30%;">
                            <?php if ($this->Form->isFieldError('cp_encrypted_sn') || $this->Form->isFieldError('cp_pinpad_ra_attached')){
                            echo 'Does the Merchant have Debit?' . $this->Form->error('cp_encrypted_sn');
                            } else echo 'Does the Merchant have Debit?'; 
                                ?>                                
                            
                            </td>
                            <td ><div div class="textborder">
                                    <?php echo $data['CobrandedApplication']['TermAcceptDebit-Yes'] == 'true' ? 'Yes' : 'No'; ?>
                                </div>
                            </td>
                            <td style="width: 21%;">Pin Pad Type? <div class="textborder">
                             <?php echo $data['CobrandedApplication']['PinPad1'];?></div>
                     </td>
                     <td >
                         <?php echo $this->Form->input('cp_encrypted_sn', array('label' => 'JR\'s encrypted-S/N ', 'style' => 'width:100px','size' => '20', 'error' => false)); ?></td>
                     </tr>
                     <tr >
                         <td style="width: 30%;">
                             Does the Merchant do autoclose?
                         </td>
                         <td style="width: 12%;">
                             <div class="textborder">
                                <?php echo $data['CobrandedApplication']['DoYouUseAutoclose-Autoclose'] == 'true' ? 'Yes' : 'No'; ?>
                             </div>
                         </td>
                         <td>
                             <?php echo 'Time: <div class="textborder">' . $data['CobrandedApplication']['Autoclose Time 1'] . '</div>';?>
                         </td>
                         <td >
                             <?php echo $this->Form->input('cp_pinpad_ra_attached', array('label' => 'Pin Pad Encryption RA Attached?', 'error' => false));?>
                         </td>
                     </tr>
                     <tr >
                         <td style="width: 32%;">
                             Does the merchant do gift cards?
                         </td>
                         <td >
                         <?php
                                $options=array('yes' => 'Yes','no' => 'No');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('cp_giftcards',$options,$attributes); ?>
                         </td>
                         <td>
                         </td>
                         <td>
                         </td>
                     </tr>
                    <tr >
                        <td colspan="2">
                            Does the merchant have check guarantee?
                         </td>   
                         <td style="width: 12%;">
                         <?php
                                $options=array('yes' => 'Yes','no' => 'No');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('cp_check_guarantee',$options,$attributes); ?>
                         </td>
                         <td>
                             <?php echo $this->Form->input('cp_check_guarantee_info', array('label' => 'Info: ', 'style' => 'width:80%','size' => '15')); ?>
                         </td>
                     </tr> 
                     <tr>
                         <td>Does the merchant accept Amex?</td>
                         <td>
                             <div class="textborder">
                                <?php
                                    if ($data['CobrandedApplication']['DoYouAcceptAE-Exist'] == 'true' ||
                                        $data['CobrandedApplication']['DoYouWantToAcceptAE-New'] == 'true') {
                                        echo 'Yes';
                                    }
                                    else {
                                        echo 'No';
                                    }
                                ?>  
                             </div>
                         </td>
                         <td >
                            
                         <?php echo ($data['CobrandedApplication']['AmexNum'] == '' && $data['CobrandedApplication']['DoYouWantToAcceptAE-New'] == 'true') ? 'Request New Amex' : 'Amex# <div class="textborder"> ' . $data['CobrandedApplication']['AmexNum'] . '</div>';?>    
                         </td>
                         <td>
                         </td>
                         
                     </tr>
                     <tr>
                         <td>Does the merchant accept Discover?</td>
                         <td>
                             <div class="textborder">
                                <?php echo $data['CobrandedApplication']['DoYouWantToAcceptDisc-New'] == 'true' ? 'Yes' : 'No'; ?> 
                             </div>
                         </td>
                         <td>
                             
                            <?php echo $data['CobrandedApplication']['DoYouWantToAcceptDisc-New'] == 'true' ? 'Axia Request New' : ''; ?>   
                             
                         </td>
                         <td>
                         </td>
                     </tr>
                     <tr>
                         <td>Are they using POS?</td>
                         <td>
                         <?php
                         
                                $options=array('yes' => 'Yes','no' => 'No');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('cp_pos',$options,$attributes); ?>    
                         </td>
                         <td colspan="2">
                         <?php echo $this->Form->input('cp_pos_contact', array('label' => 'Contact Info: ', 'style' => 'width:75%','size' => '15'));?>    
                         </td>
                     </tr>
                    
                 </table>
                 <?php 
                 }
                 if ($data['Coversheet']['status'] == 'saved'){
                    if ($cp != true) { echo '</div>'; }
                 }
                 if($data['Coversheet']['status'] == 'saved') {
                     if ($micros != true) {
                     ?>
                <table>
                    <tr>
                     <td>
                     <?php
                     $div_show = 'ShowMicrosLink';
                     $div_hide = 'HideMicrosLink';
                     $div_string = 'Micros';
                     echo '<div id="'.$div_show.'">';
                     echo $this->Html->link('Click for Micros Merchant', 'javascript:void(0)', array('onclick' => 'Effect.Appear(\''.$div_string.'\'),Effect.Appear(\''.$div_hide.'\'),Effect.Fade(\''.$div_show.'\')'));
                     echo '</div>'; 
                     echo '<div id="'.$div_hide.'" style=display:none;">';
                     echo $this->Html->link('Hide Micros Merchant', 'javascript:void(0)', array('onclick' => 'Effect.Fade(\''.$div_string.'\'),Effect.Fade(\''.$div_hide.'\'),Effect.Appear(\''.$div_show.'\')'));
                     echo '</div>'; 
                     ?>
                     </td>
                    </tr>
                </table>
                     <?php 
                     echo '<div id="'.$div_string.'" style=display:none;">';
                     }
                 }
                 if(($data['Coversheet']['status'] != 'saved' && $data['Coversheet']['micros'] != '')  || $data['Coversheet']['status'] == 'saved') {
                     ?>
                 <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                         <td colspan="2"><strong>For MICROS Merchant:</strong></td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td >What Type of Micros system does the merchant have?</td>
                        <td style="width: 50%;">
                            <?php
                         
                                $options=array('ip' => 'IP','dial' => 'Dial-Up');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('micros',$options,$attributes); ?>
                            <?php //echo $this->Form->input('micros_ip', array('div'=>false, 'label' => 'IP', 'error' => false));?><?php //echo $this->Form->input('micros_dial', array('div'=>false, 'label' => 'Dial'));?></td>
                       
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php
                            if ($this->Form->isFieldError('micros')) {
                                echo $this->Form->error('micros');
                            } else {echo 'How will the additional per item fee be handled?';}
                            ?>
                        </td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td colspan="2">
                        <?php
                         
                                $options=array('statement' => 'Included in Pricing(BILLED ON STATEMENT)','merchant' => 'Bill Merchant (AUTHORIZATION FORM ATTACHED)', 'rep' => 'Bill Rep');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('micros_billing',$options,$attributes); ?>    
                        <?php //echo $this->Form->input('micros_bill_statement', array('div'=>false, 'label' => 'Included in Pricing(BILLED ON STATEMENT)'));?>
                        <?php //echo $this->Form->input('micros_bill_merchant', array('div'=>false, 'label' => 'Bill Merchant (AUTHORIZATION FORM ATTACHED)'));?>
                        <?php //echo $this->Form->input('micros_bill_rep', array('div'=>false, 'label' => 'Bill Rep'));?>
                        </td>

                    </tr>
                 </table>
                <?php 
                }
                if($data['Coversheet']['status'] == 'saved') {
                if ($micros != true) {echo '</div>';}
                }?>
                <?php 
                if($data['Coversheet']['status'] == 'saved') {



                     if ($gateway != true) {
                echo '<table>';
                    echo '<tr>';
                     echo '<td>';
                     $div_show = 'ShowGatewayLink';
                     $div_hide = 'HideGatewayLink';
                     $div_string = 'Gateway';
                     echo '<div id="'.$div_show.'">';
                     echo $this->Html->link('Click for Gateway Merchant', 'javascript:void(0)', array('onclick' => 'Effect.Appear(\''.$div_string.'\'),Effect.Appear(\''.$div_hide.'\'),Effect.Fade(\''.$div_show.'\')',   )); 
                     echo '</div>'; 
                     echo '<div id="'.$div_hide.'" style=display:none;">';
                     echo $this->Html->link('Hide Gateway Merchant', 'javascript:void(0)', array('onclick' => 'Effect.Fade(\''.$div_string.'\'),Effect.Fade(\''.$div_hide.'\'),Effect.Appear(\''.$div_show.'\')',));
                     echo '</div>';
                     echo '</td>';
                    echo '</tr>';
                echo '</table>';
                     echo '<div id="'.$div_string.'" style=display:none;">';
                     }
                }
                if(($data['Coversheet']['status'] != 'saved' && $data['Coversheet']['gateway_option'] != '') || $data['Coversheet']['status'] == 'saved') {
                     ?>                
                <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                        <td><strong>For Axia Gateway Merchant:</strong></td>
                    </tr>
                    <tr>
                        <td>What setup option?</td>
                    </tr>
                    <tr>
                        <td>
                           <?php
                                $options=array('option1' => 'Option 1 ($75 setup, $10/month, $0.05 per item)','option2' => 'Option 2 ($29.95 setup, $15/month, $0.05 per item)');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('gateway_option',$options,$attributes);
                                //echo $this->Form->input('gateway_option', array('div'=>false, 'label' => 'Option 1 ($75 setup, $10/month, $0.05 per item)'));
                                //echo $this->Form->input('gateway_option_2', array('div'=>false, 'label' => 'Option 2 ($29.95 setup, $15/month, $0.05 per item)'));
                           ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php if ($this->Form->isFieldError('gateway_package')) {
                                 echo $this->Form->error('gateway_package');
                             } else if($this->Form->isFieldError('gateway_gold_subpackage')) {
                                 echo $this->Form->error('gateway_gold_subpackage');
                             } else echo 'What Package';
  
                                $options=array('silver' => 'Silver', 'gold' => 'Gold* (Please select:');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('gateway_package',$options,$attributes);
                                //echo $this->Form->input('gateway_package_silver', array('div'=>false, 'label' => 'Silver'));
                                //echo $this->Form->input('gateway_package_gold', array('div'=>false, 'label' => 'Gold* (Please select:'));
                                $options=array('cust_db' => 'Customer Database or', 'fraud' => 'Fraud Package)');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('gateway_gold_subpackage',$options,$attributes);
                                //echo $this->Form->input('gateway_package_gold_cust_db', array('div'=>false, 'label' => 'Customer Database or'));
                                //echo $this->Form->input('gateway_package_gold_fraud', array('div'=>false, 'label' => 'Fraud Package)'));
                                $options=array('platinum' => 'Platinum*');
                                $attributes=array('legend' => false, 'hiddenField' => false);
                                echo $this->Form->radio('gateway_package',$options,$attributes);
                                //echo $this->Form->input('gateway_package_platinum', array('div'=>false, 'label' => 'Platinum*'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">*Additional $5 monthly fee for Gold. Additional $10 monthly fee for Platinum</td>
                    </tr>
                    <tr>
                        

                        <td>
                            
                                <?php
                                if($this->Form->isFieldError('gateway_epay')) {
                                echo $this->Form->error('gateway_epay');
                                } else echo 'ePay Charge Software';
                                $options=array('yes' => 'Yes','no' => 'No');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('gateway_epay',$options,$attributes); 
                                ?>    

                            (ePay Charge Software used for swiped transactions on USAePay)
                        </td>         
                    </tr>
                    <tr>
                        <td>
                            <?php
                                if($this->Form->isFieldError('gateway_billing')) {
                                echo $this->Form->error('gateway_billing');
                                } else echo 'How will the billing of gateway fees be handled?';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                                
                                $options=array('statement' => 'Included in Pricing(BILLED ON STATEMENT)','merchant' => 'Bill Merchant (AUTHORIZATION FORM ATTACHED)', 'rep' => 'Bill Rep');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('gateway_billing',$options,$attributes); 
                                ?> 
                        </td>
                    </tr>
                </table>
                <?php
                }
                if($data['Coversheet']['status'] == 'saved') {
                if ($gateway != 1) {echo '</div>';}
                }
                if($data['Coversheet']['status'] == 'saved' && $data['Coversheet']['moto_online_chd'] == '') {
                  if ($moto != true) {
                    ?>
                <table>
                    <tr>
                     <td>
                     <?php

                     $div_show = 'ShowMotoLink';
                     $div_hide = 'HideMotoLink';
                     $div_string = 'Moto';
                     echo '<div id="'.$div_show.'">';
                     echo $this->Html->link('Click for Moto Merchant', 'javascript:void(0)', array('onclick' => 'Effect.Appear(\''.$div_string.'\'),Effect.Appear(\''.$div_hide.'\'),Effect.Fade(\''.$div_show.'\')',   )); 
                     echo '</div>'; 
                     echo '<div id="'.$div_hide.'" style=display:none;">';
                     echo $this->Html->link('Hide Moto Merchant', 'javascript:void(0)', array('onclick' => 'Effect.Fade(\''.$div_string.'\'),Effect.Fade(\''.$div_hide.'\'),Effect.Appear(\''.$div_show.'\')',));
                     echo '</div>'; 
                     ?>                         
                     </td>
                    </tr>
                </table>
                     <?php 
                     echo '<div id="'.$div_string.'" style=display:none;">';
                     }
                     }
                     if(($data['Coversheet']['status'] != 'saved' && $data['Coversheet']['moto_online_chd'] != '') || $data['Coversheet']['status'] == 'saved') {
                     ?>                
                <table  style="border: 1px solid black; width: 100%;" class="radios">
                    <tr>
                        <td colspan="3"><strong>For MOTO/Internet Merchant:</strong></td>
                    </tr>
                    <tr>
                         <td>Does the merchant accept Amex?</td>
                         <td>
                             <div class="textborder">
                                <?php
                                    if ($data['CobrandedApplication']['DoYouAcceptAE-Exist'] == 'true' ||
                                        $data['CobrandedApplication']['DoYouWantToAcceptAE-New'] == 'true') {
                                        echo 'Yes';
                                    }
                                    else {
                                        echo 'No';
                                    }
                                ?> 
                            </div>
                         </td>
                         <td>
                            <?php echo ($data['CobrandedApplication']['AmexNum'] == '' && $data['CobrandedApplication']['DoYouWantToAcceptAE-New'] == 'true') ? 'Request New Amex' : 'Amex# <div class="textborder"> ' . $data['CobrandedApplication']['AmexNum'] . '</div>';?>
                         </td>
   
                     </tr>
                     <tr>
                         <td>Does the merchant accept Discover?</td>
                         <td>
                            <div class="textborder">
                                <?php echo $data['CobrandedApplication']['DoYouWantToAcceptDisc-New'] == 'true' ? 'Axia Request New' : ''; ?>   
                            </div>
                         </td>
                         <td>
                             
                            <?php echo $data['CobrandedApplication']['DoYouWantToAcceptDisc-New'] == 'true' ? 'Axia Request New' : ''; ?>   
                                 
                         </td>
                     </tr>
                     <tr>
                         <td colspan="3">
                             <?php if ($this->Form->isFieldError('moto_online_chd')) {
                                 echo $this->Form->error('moto_online_chd');
                             } else 
                             echo 'Internet Merchants: Does the merchant store credit card numbers online?';
                             ?>
                         <?php 
                                $options=array('yes' => 'Yes', 'no' => 'No');
                                $attributes = array('legend' => false);
                                echo $this->Form->radio('moto_online_chd', $options,$attributes);
                         ?>   
                         </td>
                     </tr>
                     <tr>
                        
                        <td colspan="3">
                        <?php
                echo $this->Form->input('moto_developer', array('style' => 'width:45%', 'size' => '20', 'label' => 'If applicable, please fill in name of web developer & Gateway:'));
                ?>
                        </td>
                     </tr>
                     <tr>
                         <td>
                             <?php
                             echo $this->Form->input('moto_company', array('style' => 'width:70%','size' => '20','div'=>false, 'label' => 'Company '));
                             ?>
                         </td>
                          <td>
                             <?php
                             echo $this->Form->input('moto_gateway', array('style' => 'width:70%','size' => '20','div'=>false, 'label' => 'Gateway '));
                             ?>
                         </td>
                         <td>
                         </td>
                     </tr>
                     <tr>
                         <td >
                             <?php
                                echo $this->Form->input('moto_contact', array('style' => 'width:75%', 'size' => '20', 'div'=>false, 'label' => 'Contact '));
                             ?>
                         </td>
                         <td >
                             <?php
                                echo $this->Form->input('moto_phone', array('style' => 'width:75%', 'size' => '20', 'div'=>false, 'label' => 'Phone '));
                             ?>
                         </td>
                         <td >
                             <?php
                                echo $this->Form->input('moto_email', array('style' => 'width:75%', 'size' => '20', 'div'=>false, 'label' => 'Email '));
                             ?>
                         </td>
                     </tr>
                </table>
                <?php
                     }
                if($data['Coversheet']['status'] == 'saved' && $data['Coversheet']['moto_online_chd'] != '') {
                    if ($moto != true) {echo '</div>';}
                }?>
        </fieldset>
    
    <?php 
    $this->Html->css(array('coversheet'), 'stylesheet', array('media' => 'print'));
    if ($data['Coversheet']['status'] == 'saved') {
        echo $this->Form->submit('Save',array('name'=>'save'));
        echo $this->Form->submit('Submit to UW',array('name'=>'uw'));
        echo $this->Form->end();
    }
        ?>
</div>

<?php 
if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
        echo $this->Html->link(
            'Return to Applications Admin',
            '/admin/cobranded_applications/',
            array('style' => 'display: block; float: right;')
        );
    }
    ?>
