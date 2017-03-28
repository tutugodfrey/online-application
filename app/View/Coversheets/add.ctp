<?php echo $this->Html->css('coversheet', null, array('inline' => false)); ?>
<div class="Coversheets form">
<?php echo $this->Form->create('Coversheet');?>
	<fieldset>
		<legend><?php echo __('Add %s', __('Coversheet')); ?></legend>
	
                <table width="100%" bgcolor="999A8F" style="border: 1px solid black;">
                    <tr>
                        <td align="center"><strong>Setup Information</strong></td>
                    </tr>
                    
                </table>
                <table width="100%" style="border: 1px solid black;">
		<tr>
                
                <td ><?php echo 'Rep Name: '. $users['User']['firstname'] . $users['User']['lastname']; ?></td>
                
                <td ><?php echo 'Merchant: ' . $applications['CobrandedApplication']['DBA']; ?></td>
                
                </tr>
                </table>
                <table width="100%" style="border: 1px solid black;" class="radios">
                <tr>
                    <!--<td><?php //echo $this->Form->input('setup_existing_merchant'); ?></td>-->
                    <td><?php echo "Attached: " . $this->Form->input('setup_banking', array('label' => "Banking (no starter checks)")); ?></td>
                    <td><?php echo $this->Form->input('setup_statements', array('label' => "3 Mo. Processing Stmts")); ?></td>
                    <td><?php echo $this->Form->input('setup_drivers_license', array('label' => "Owner's Driver's License")); ?></td>
                </tr>
                </table>
                <table width="100%" style="border: 1px solid black;">
                    <tr>
		<td><?php echo "For New Businesses:"//$this->Form->input('setup_new_merchant', array('div' => false, 'label' => false)); ?></td>
		<td><?php echo $this->Form->input('setup_business_license', array('div' => false, 'label' => false)) . "Business License or Utility Bill"; ?></td>
		<td><?php echo $this->Form->input('setup_other', array('div' => false, 'label' => false)); ?></td>
		<td width="25%"><?php echo "Other:" . $this->Form->input('setup_field_other', array('div' => false, 'label' => false, 'style' => 'width:120px')); ?></td>
                </tr>
                </table>
                <table width="100%" style="border: 1px solid black;">
                    <tr>
                    <td width="20%">Tier 1:</td>
                    <td>Retail: Volume $0-$250,000/month, Average Ticket Less Than $1,000</td>
                    </tr>
                </table>
                <table width="100%" style="border: 1px solid black;">
                    <tr>
                    <td width="20%">Tier 2:</td>
                    <td>MOTO: Volume $0-$150,000, Average Ticket Less Than $1,000</td>
                    </tr>
                </table>
                <table width="100%" style="border: 1px solid black;">
                    <tr>
                    <td width="20%">Tier 3:</td>
                    <td>Retail: Volume $250,000 or Greater/month, Average Ticket Less Than $1,000</td>
                    </tr>
                    <tr>
                        <td width="20%">Required: <span style="float:right;"><?php echo $this->Form->input('setup_tier3',array('div' => false, 'label' => false)); ?></span></td>
                    <td>2 years business financials or 2 years tax returns</td>
                    </tr>
                </table>
                </table>
                <table width="100%" style="border: 1px solid black;">
                    <tr>
                    <td width="20%">Tier 4:</td>
                    <td>Retail: Volume $0-$250,000/month, Average Ticket Greater Than $1,000</td>
                    </tr>
                    <tr>
                    <td width="20%"></td>
                    <td>MOTO/Internet: Volume $0-$150,000/month, Average Ticket Greater than $1,000</td>
                    </tr>
                    <tr>
                    <td width="20%">Required: <span style="float:right;"><?php echo $this->Form->input('setup_tier4',array('div' => false, 'label' => false)); ?></span></td>
                    <td>2 years business financials or 2 years tax returns</td>
                    </tr>
                </table>
                </table>
                <table width="100%" style="border: 1px solid black;">
                    <tr>
                    <td width="20%">Tier 5:</td>
                    <td>Retail: Volume $250,000 or Greater/month, Average Ticket Greater Than $1,000</td>
                    </tr>
                    <tr>
                    <td width="20%"></td>
                    <td>MOTO/Internet: Volume $150,000 or Greater/month</td>
                    </tr>
                    <tr>
                    <td width="20%"><span style="float:right;"><?php echo $this->Form->input('setup_tier5_financials',array('div' => false, 'label' => false)); ?></span></td>
                    <td>2 years audited business financials or 2 years tax returns</td>
                    </tr>
                    <tr>
                    <td width="20%"><span style="float:right;"><?php echo $this->Form->input('setup_tier5_processing_statements',array('div' => false, 'label' => false)); ?></span></td>
                    <td>6 months processing statements</td>
                    </tr>
                    <tr>
                    <td width="20%"><span style="float:right;"><?php echo $this->Form->input('setup_tier5_bank_statements',array('div' => false, 'label' => false)); ?></span></td>
                    <td>3 months bank statements</td>
                    </tr>
                </table>
                <table width="100%" style="border: 1px solid black;" class="radios">
                    <tr>
                        <td width="25%">Equipment Type: <span style="float:right;"><?php echo $this->Form->input('setup_equipment_terminal',array('label' => 'Terminal')); ?></span></td>
                        <td width="40%"><span style="float:left;"><?php echo $this->Form->input('setup_equipment_gateway',array('label' => 'POS/Gateway')); ?></span></td>     
                   </tr>
                </table>
                <table width="100%" style="border: 1px solid black;">
                    <tr>
                        <td width="40%">Install to be done by: <span style="float:right;"><?php echo $this->Form->input('setup_install_rep',array('div' => false, 'label' => false)); ?></span></td>
                        <td width="10%">Rep</td>
                        <td width="10"><span style="float:left;"><?php echo $this->Form->input('setup_install_axia',array('div' => false, 'label' => false)); ?>Axia</span></td>    
                        <td width="40%"><span style="float:left;"><?php echo $this->Form->input('setup_install_pos',array('div' => false, 'label' => false)); ?>POS/Gateway Provider</span></td>
                   </tr>
                </table>
                <table width="100%" style="border: 1px solid black;">
                    <tr>
                        <td width="50%">Starter kits supplies provided by (terminal only): </td>
                        <td witdh="10%"><span style="float:left;"><?php echo $this->Form->input('setup_starterkit_rep',array('div' => false, 'label' => false)); ?></span>Rep</td>
                        <td width="40%"><span style="float:left;"><?php echo $this->Form->input('setup_starterkit_axia',array('div' => false, 'label' => false)); ?>Axia (ship or drop off)</span></td>    

                   </tr>
                </table>
                <table width="100%" style="border: 1px solid black;">
                    <tr>
                        <td>Equipment Payment</td>
                        <td><span style="float:left;"><?php echo $this->Form->input('setup_equipment_payment_ach',array('div' => false, 'label' => false)); ?></span>ACH</td>
                        <td border="1">
                            <span style="float:left;">
                            <?php echo $this->Form->input('setup_equipment_payment_lease',array('div' => false, 'label' => false)); ?>
                            </span>
                            Lease - Terms:
                            <span>
                            <?php echo $this->Form->input('setup_lease_price',array('div' => false, 'label' => false,'style' => 'width:40px')); ?>
                            </span>
                            per month
                            <span>
                            <?php echo $this->Form->input('setup_lease_months',array('div' => false, 'label' => false,'style' => 'width:40px')); ?>
                            </span>
                            #of months
                        </td>
                    </tr>
                </table>
                <table width="100%" style="border: 1px solid black;">
                    <tr>
                        <td>Existing Amex #: <?php echo $this->Form->input('OnlineCobrandedApplication.existing_se_num', array('div' => false, 'label' => false, 'style' => 'width:100px', 'disabled' => true));?> </td>
                        <td>Debit Volume: <?php echo $this->Form->input('setup_debit_volume',array('div' => false, 'label' => false,'style' => 'width:100px'));?></td>
                        <td>Monthly Item Count: <?php echo $this->Form->input('setup_item_count',array('div' => false, 'label' => false,'style' => 'width:100px'));?></td>
                    </tr>
                </table>
                <table width="100%" style="border: 1px solid black;" class="radios">
                    <tr class="radios">
                        <td>Referrer: <?php echo $this->Form->input('setup_referrer',array('div' => false, 'label' => false, 'style' => 'width:150px')); ?>
                        <?php 
                        $options=array('gp' => 'GP','bp' => 'BP');
                        $attributes=array('legend' => false);
                        echo $this->Form->radio('setup_referrer_type',$options,$attributes); ?>
                        <?php echo $this->Form->input('setup_referrer_pct',array('div' => false, 'label' => false,'style' => 'width:20px')); ?>%
                         Reseller<?php echo $this->Form->input('setup_reseller',array('div' => false, 'label' => false,'style' => 'width:150px')); ?>
                         <?php 
                        $options=array('gp' => 'GP','bp' => 'BP');
                        $attributes=array('legend' => false);
                        echo $this->Form->radio('setup_reseller_type',$options,$attributes); ?>
                        <?php echo $this->Form->input('setup_reseller_pct',array('div' => false, 'label' => false,'style' => 'width:20px')); ?>%
                        </td>
                    </tr>
                </table>
                <table width="100%" style="border: 1px solid black;" class="radios">
                    <tr>
                        <td>Setup Notes: <?php echo $this->Form->input('setup_notes', array('rows' => '2', 'div' => false, 'label' => false,'style' => 'width:100%')); ?></td>
                    </tr>
                </table>
                <table width="100%" bgcolor="999A8F" style="border: 1px solid black;">
                    <td align="center"><strong>Merchant Info Questionnaire</strong></td>
                </table>
                 <table width="100%" style="border: 1px solid black;" class="radios" class="text">
                     <tr>
                         <td><strong>For Card Present Merchant:</strong><td>
                    </tr>
                                         
                     <tr >
                          
                            <td width="30%">
                            Does the Merchant have Debit?
                            </td>
                            <td >
                             <?php 
                                $options=array('yes' => 'Yes','no' => 'No');
                                $attributes=array('legend' => false, 'disabled' => true);
                                echo $this->Form->radio('CobrandedApplication.term1_accept_debit',$options,$attributes); ?>
                            </td>
                            <td width ="21%"align="right">Pin Pad Type?
                             <?php echo $this->Form->input('CobrandedApplication.term1_pin_pad_type', array('div' => false, 'label' => false, 'style' => 'width:50px', 'disabled' => true));?>
                     </td>
                     <td >
                         <?php echo $this->Form->input('cp_encrypted_sn', array('label' => 'JR\'s encrypted-S/N ', 'style' => 'width:100px')); ?></td>
                     </tr>
                     <tr >
                         <td width="30%">
                             Does the Merchant do autoclose?
                         </td>
                         <td width="12%">
                         <?php 
                                $options=array('yes' => 'Yes','no' => 'No');
                                $attributes=array('legend' => false, 'disabled' => true);
                                echo $this->Form->radio('CobrandedApplication.term1_use_autoclose',$options,$attributes); ?>
                         </td>
                         <td align="right">
                             <?php echo $this->Form->input('CobrandedApplication.term1_what_time', array('label' => 'Time', 'style' => 'width:50px', 'disabled' => true));?>
                         </td>
                         <td >
                             <?php echo $this->Form->input('cp_pinpad_ra_attached', array('label' => 'Pin Pad Encryption RA Attached?'));?>
                         </td>
                     </tr>
                     <tr >
                         <td width="32%">
                             Does the merchant do gift cards?
                         </td>
                         <td >
                         <?php
                                $options=array('t' => 'Yes','f' => 'No');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('cp_giftcards',$options,$attributes); ?>
                         </td>
                     </tr>
                    <tr >
                        <td colspan="2">
                            Does the merchant have check guarantee?
                         </td>   
                         <td width="12%">
                         <?php
                                $options=array('t' => 'Yes','f' => 'No');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('cp_check_guarantee',$options,$attributes); ?>
                         </td>
                         <td>
                             <?php echo $this->Form->input('cp_check_guarantee_info', array('label' => 'Info: ', 'style' => 'width:80%')); ?>
                         </td>
                     </tr> 
                     <tr>
                         <td>Does the merchant accept Amex?</td>
                         <td>
                            <?php
                                if ($applications['CobrandedApplication']['DoYouAcceptAE-Exist'] == 'true' ||
                                    $applications['CobrandedApplication']['DoYouWantToAcceptAE-New'] == 'true') {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                            ?>   
                         </td>
                         <td>
                         <?php echo $applications['CobrandedApplication']['AmexNum'] == '' ? 'Request New Amex' : 'Amex # ' . $applications['CobrandedApplication']['AmexNum'];?>    
                         </td>
                     </tr>
                     <tr>
                         <td>Does the merchant accept Discover?</td>
                         <td>
                         <?php
                                echo $applications['CobrandedApplication']['DoYouWantToAcceptDisc-New'] == 'true' ? 'Yes' : 'No' 
                         ?>     
                         </td>
                         <td>
                         <?php echo $applications['CobrandedApplication']['DoYouWantToAcceptDisc-New'] == 'true' ? 'Axia Request New' : '' ?>    
                         </td>
                     </tr>
                     <tr>
                         <td>Are they using POS?</td>
                         <td>
                         <?php
                                $options=array('t' => 'Yes','f' => 'No');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('cp_pos',$options,$attributes); ?>    
                         </td>
                         <td colspan="2">
                         <?php echo $this->Form->input('cp_pos_contact', array('label' => 'Contact Info: ', 'style' => 'width:75%'));?>    
                         </td>
                     </tr>
                 </table>
                 <table width="100%" style="border: 1px solid black;" class="radios" class="text">
                    <tr>
                         <td colspan="2"><strong>For MICROS Merchant:</strong><td>
                    </tr>
                    <tr border="1">
                        <td >What Type of Micros system does the merchant have?</td>
                        <td width="50%"><?php echo $this->Form->input('micros_ip', array('div'=>false, 'label' => 'IP'));?><?php echo $this->Form->input('micros_dial', array('div'=>false, 'label' => 'Dial'));?></td>
                       
                    </tr>
                    <tr>
                        <td colspan="2">How will the additional per item fee be handled?</td>
                    </tr>
                    <tr border="1">
                        <td colspan="2"><?php echo $this->Form->input('micros_bill_statement', array('div'=>false, 'label' => 'Included in Pricing(BILLED ON STATEMENT)'));?>
                        <?php echo $this->Form->input('micros_bill_merchant', array('div'=>false, 'label' => 'Bill Merchant (AUTHORIZATION FORM ATTACHED)'));?>
                        <?php echo $this->Form->input('micros_bill_rep', array('div'=>false, 'label' => 'Bill Rep'));?>
                        </td>

                    </tr>
                 </table>
                <table width="100%" style="border: 1px solid black;" class="radios" class="text">
                    <tr>
                        <td><strong>For Axia Gateway Merchant:</strong></td>
                    </tr>
                    <tr>
                        <td>What setup option?</td>
                    </tr>
                    <tr>
                        <td>
                           <?php
                                echo $this->Form->input('gateway_option_1', array('div'=>false, 'label' => 'Option 1 ($75 setup, $10/month, $0.05 per item)'));
                                echo $this->Form->input('gateway_option_2', array('div'=>false, 'label' => 'Option 2 ($29.95 setup, $15/month, $0.05 per item)'));
                           ?>
                        </td>
                    </tr>
                    <tr>
                        <td>What package?
                            <?php
                                echo $this->Form->input('gateway_package_silver', array('div'=>false, 'label' => 'Silver'));
                                echo $this->Form->input('gateway_package_gold', array('div'=>false, 'label' => 'Gold* (Please select:'));
                                echo $this->Form->input('gateway_package_gold_cust_db', array('div'=>false, 'label' => 'Customer Database or'));
                                echo $this->Form->input('gateway_package_gold_fraud', array('div'=>false, 'label' => 'Fraud Package)'));
                                echo $this->Form->input('gateway_package_platinum', array('div'=>false, 'label' => 'Platinum*'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">*Additional $5 monthly fee for Gold. Additional $10 monthly fee for Platinum</td>
                    </tr>
                    <tr>
                        

                        <td>ePay Charge Software
                            
                                <?php
                                $options=array('t' => 'Yes','f' => 'No');
                                $attributes=array('legend' => false);
                                echo $this->Form->radio('gateway_epay',$options,$attributes); 
                                ?>    

                            (ePay Charge Software used for swiped transactions on USAePay)
                        </td>         
                    </tr>
                    <tr>
                        <td>How will the billing of gateway fees be handled?</td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                                echo $this->Form->input('gateway_bill_statement', array('div'=>false, 'label' => 'Included In Pricing (BILLED ON STATEMENT)'));
                                echo $this->Form->input('gateway_bill_merchant', array('div'=>false, 'label' => 'Bill Merchant (AUTHORIZATION FORM ATTACHED)'));
                                echo $this->Form->input('gateway_bill_rep', array('div'=>false, 'label' => 'Bill Rep'));
                                ?>
                        </td>
                    </tr>
                        
                        <?php


		
		                        
                        ?>
                        
                    </tr>
                </table>
                <table width="100%" style="border: 1px solid black;" class="radios" class="text">
                    <tr>
                        <td colspan="3"><strong>For MOTO/Internet Merchant:</strong></td>
                    </tr>
                    <tr>
                         <td>Does the merchant accept Amex?</td>
                         <td>
                            <?php
                                if ($applications['CobrandedApplication']['DoYouAcceptAE-Exist'] == 'true' ||
                                    $applications['CobrandedApplication']['DoYouWantToAcceptAE-New'] == 'true') {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                            ?> 
                         </td>
                         <td>
                         <?php echo $applications['CobrandedApplication']['AmexNum'] == '' ? 'Request New Amex' : 'Amex # ' . $applications['CobrandedApplication']['AmexNum'];?>    
                         </td>
   
                     </tr>
                     <tr>
                         <td>Does the merchant accept Discover?</td>
                         <td>
                         <?php
                                echo $applications['CobrandedApplication']['DoYouWantToAcceptDisc-New'] == 'true' ? 'Yes' : 'No' 
                         ?>    
                         </td>
                         <td>
                         <?php echo $applications['CobrandedApplication']['DoYouWantToAcceptDisc-New'] == 'true' ? 'Axia Request New' : '' ?>    
                         </td>
                     </tr>
                     <tr>
                         <td colspan="3">Internet Merchants: Does the merchant store credit card numbers online?
                         <?php 
                                $options=array('t' => 'Yes', 'f' => 'No');
                                $attributes = array('legend' => false);
                                echo $this->Form->radio('moto_online_chd', $options,$attributes);
                         ?>   
                         </td>
                     </tr>
                     <tr>
                        
                        <td colspan="3">
                        <?php
                echo $this->Form->input('moto_developer', array('style' => 'width:45%','label' => 'If applicable, please fill in name of web developer & Gateway:'));
                ?>
                        </td>
                     </tr>
                     <tr>
                         <td>
                             <?php
                             echo $this->Form->input('moto_company', array('style' => 'width:70%','div'=>false, 'label' => 'Company '));
                             ?>
                         </td>
                          <td>
                             <?php
                             echo $this->Form->input('moto_gateway', array('style' => 'width:70%','div'=>false, 'label' => 'Gateway '));
                             ?>
                         </td>
                     </tr>
                     <tr>
                         <td >
                             <?php
                                echo $this->Form->input('moto_contact', array('style' => 'width:75%', 'div'=>false, 'label' => 'Contact '));
                             ?>
                         </td>
                         <td >
                             <?php
                                echo $this->Form->input('moto_phone', array('style' => 'width:75%', 'div'=>false, 'label' => 'Phone '));
                             ?>
                         </td>
                         <td >
                             <?php
                                echo $this->Form->input('moto_email', array('style' => 'width:75%', 'div'=>false, 'label' => 'Email '));
                             ?>
                         </td>
                     </tr>
                </table>
        </fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<div class="panel panel-info">
        <div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
         <div class="panel-body">
        	<ul>

        		<li><?php echo $this->Html->link(__('List %s', __('Coversheets')), array('action' => 'index'));?></li>
        		<li><?php echo $this->Html->link(__('List %s', __('Applications')), array('controller' => 'cobranded_applications', 'action' => 'index')); ?> </li>
        		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'cobranded_applications', 'action' => 'add')); ?> </li>
        		<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?> </li>
        		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
        	</ul>
        </div>
    </div>
</div>