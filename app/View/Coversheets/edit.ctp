<div class='container'>
    <div class='row'>
        <div class='col-xs-12'>
            <?php echo $this->Html->css('coversheet', null, array('inline' => false)); ?>

            <script type="text/javascript" src="/js/jquery-validate.1.11.11.js"></script>
            <script type="text/javascript" src="/js/jquery-validate-additional-methods.js"></script>
            <script type="text/javascript" src="/js/jquery.inputmask/dist/jquery.inputmask.bundle.js"></script>

            <script type="text/javascript">
                jQuery.noConflict();  

                jQuery(document).ready(function () {
                    jQuery(":input").inputmask();

                    jQuery('input[type=submit]').click(function(e) {

                        var submitFlag = true;

                        if (jQuery('#CoversheetSetupPartner').val() || jQuery('#CoversheetSetupPartnerPctProfit').val() ||
                            jQuery('#CoversheetSetupPartnerPctVolume').val() || jQuery('#CoversheetSetupPartnerPctGross').val()) {

                            if (!jQuery('#CoversheetSetupPartner').val()) {
                                jQuery('#CoversheetSetupPartner').css('background','#FFFF00');
                                submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupPartner').css('background','#FFFFFF');
                            }

                            if (!jQuery('#CoversheetSetupPartnerPctProfit').val() && !jQuery('#CoversheetSetupPartnerPctVolume').val()) {
                                    jQuery('#CoversheetSetupPartnerPctProfit').css('background','#FFFF00');
                                    jQuery('#CoversheetSetupPartnerPctVolume').css('background','#FFFF00');
                                    submitFlag = false;
                            }
                            else {
                                if ((jQuery('#CoversheetSetupPartnerPctProfit').val() && (jQuery('#CoversheetSetupPartnerPctProfit').val() > 100 || jQuery('#CoversheetSetupPartnerPctProfit').val() < 1)) ||
                                    (jQuery('#CoversheetSetupPartnerPctVolume').val() && (jQuery('#CoversheetSetupPartnerPctVolume').val() > 5 || jQuery('#CoversheetSetupPartnerPctVolume').val() < .01))) {
                                        jQuery('#CoversheetSetupPartnerPctProfit').css('background','#FFFF00');
                                        jQuery('#CoversheetSetupPartnerPctVolume').css('background','#FFFF00');
                                        submitFlag = false;
                                }
                                else {
                                    jQuery('#CoversheetSetupPartnerPctProfit').css('background','#ffffff');
                                    jQuery('#CoversheetSetupPartnerPctVolume').css('background','#ffffff');
                                }
                            }

                            if (!jQuery('#CoversheetSetupPartnerPctGross').val() ||
                                jQuery('#CoversheetSetupPartnerPctGross').val() > 100 || jQuery('#CoversheetSetupPartnerPctGross').val() < 1) {
                                    jQuery('#CoversheetSetupPartnerPctGross').css('background','#FFFF00');
                                    submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupPartnerPctGross').css('background','#ffffff');
                            }
                        }

                        if (jQuery('#CoversheetSetupReferrer').val() || jQuery('#CoversheetSetupReferrerPctProfit').val() ||
                            jQuery('#CoversheetSetupReferrerPctVolume').val() || jQuery('#CoversheetSetupReferrerPctGross').val()) {

                            if (!jQuery('#CoversheetSetupReferrer').val()) {
                                jQuery('#CoversheetSetupReferrer').css('background','#FFFF00');
                                submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupReferrer').css('background','#FFFFFF');
                            }

                            if (!jQuery('#CoversheetSetupReferrerPctProfit').val() && !jQuery('#CoversheetSetupReferrerPctVolume').val()) {
                                    jQuery('#CoversheetSetupReferrerPctProfit').css('background','#FFFF00');
                                    jQuery('#CoversheetSetupReferrerPctVolume').css('background','#FFFF00');
                                    submitFlag = false;
                            }
                            else {
                                if ((jQuery('#CoversheetSetupReferrerPctProfit').val() && (jQuery('#CoversheetSetupReferrerPctProfit').val() > 100 || jQuery('#CoversheetSetupReferrerPctProfit').val() < 1)) ||
                                    (jQuery('#CoversheetSetupReferrerPctVolume').val() && (jQuery('#CoversheetSetupReferrerPctVolume').val() > 5 || jQuery('#CoversheetSetupReferrerPctVolume').val() < .01))) {
                                        jQuery('#CoversheetSetupReferrerPctProfit').css('background','#FFFF00');
                                        jQuery('#CoversheetSetupReferrerPctVolume').css('background','#FFFF00');
                                        submitFlag = false;
                                }
                                else {
                                    jQuery('#CoversheetSetupReferrerPctProfit').css('background','#ffffff');
                                    jQuery('#CoversheetSetupReferrerPctVolume').css('background','#ffffff');
                                }
                            }
                            
                            if (!jQuery('#CoversheetSetupReferrerPctGross').val() ||
                                jQuery('#CoversheetSetupReferrerPctGross').val() > 100 || jQuery('#CoversheetSetupReferrerPctGross').val() < 1) {
                                    jQuery('#CoversheetSetupReferrerPctGross').css('background','#FFFF00');
                                    submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupReferrerPctGross').css('background','#ffffff');
                            }
                        }

                        if (jQuery('#CoversheetSetupReseller').val() || jQuery('#CoversheetSetupResellerPctProfit').val() ||
                            jQuery('#CoversheetSetupResellerPctVolume').val() || jQuery('#CoversheetSetupResellerPctGross').val()) {

                            if (!jQuery('#CoversheetSetupReseller').val()) {
                                jQuery('#CoversheetSetupReseller').css('background','#FFFF00');
                                submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupReseller').css('background','#FFFFFF');
                            }

                            if (!jQuery('#CoversheetSetupResellerPctProfit').val() && !jQuery('#CoversheetSetupResellerPctVolume').val()) {
                                    jQuery('#CoversheetSetupResellerPctProfit').css('background','#FFFF00');
                                    jQuery('#CoversheetSetupResellerPctVolume').css('background','#FFFF00');
                                    submitFlag = false;
                            }
                            else {
                                if ((jQuery('#CoversheetSetupResellerPctProfit').val() && (jQuery('#CoversheetSetupResellerPctProfit').val() > 100 || jQuery('#CoversheetSetupResellerPctProfit').val() < 1)) ||
                                    (jQuery('#CoversheetSetupResellerPctVolume').val() && (jQuery('#CoversheetSetupResellerPctVolume').val() > 5 || jQuery('#CoversheetSetupResellerPctVolume').val() < .01))) {
                                        jQuery('#CoversheetSetupResellerPctProfit').css('background','#FFFF00');
                                        jQuery('#CoversheetSetupResellerPctVolume').css('background','#FFFF00');
                                        submitFlag = false;
                                }
                                else {
                                    jQuery('#CoversheetSetupResellerPctProfit').css('background','#ffffff');
                                    jQuery('#CoversheetSetupResellerPctVolume').css('background','#ffffff');
                                }
                            }

                            if (!jQuery('#CoversheetSetupResellerPctGross').val() ||
                                jQuery('#CoversheetSetupResellerPctGross').val() > 100 || jQuery('#CoversheetSetupResellerPctGross').val() < 1) {
                                    jQuery('#CoversheetSetupResellerPctGross').css('background','#FFFF00');
                                    submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupResellerPctGross').css('background','#ffffff');
                            }
                        }

                        if (!submitFlag) {
                            e.preventDefault()
                            return false;
                        }

                        jQuery('#CoversheetEditForm').append('<input type="hidden" name="'+this.name+'" value="'+this.name+'" />');
                        jQuery(this).attr('disabled', 'disabled');
                        jQuery('#CoversheetEditForm').submit();
                    })

                    var validationRules = {};

                    jQuery("#CoversheetEditForm input[data-vtype]").map(function(index, input) {
                        var currentInput = jQuery(input);
                
                        if (typeof(validationRules[currentInput.attr('id')]) == 'undefined') {
                            var rule = new Object();
                            rule[currentInput.attr('data-vtype')] = true;
                            validationRules[currentInput.attr('id')] = rule;
                        }
                    });

                    $validator = jQuery("#CoversheetEditForm").validate({ rules: validationRules });
                });
            </script>

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

            <div class="form-group">
            <?php echo $this->Form->create('Coversheet', array('novalidate' => true));?>
		      <?php $attributes = array('hiddenField' => false); ?>
            	<fieldset>
            		<legend><?php echo __('Add %s', __('Coversheet')); ?></legend>
            	       <?php echo $this->Form->hidden('id'); ?>
                            <table class="table table-condensed">
                                <tr>
                                    <td colspan="3"><?php echo 'Rep Name: '. $data['User']['firstname'] . $data['User']['lastname']; ?></td>
                                    <td colspan="3"><?php echo 'Merchant: ' . $data['CobrandedApplication']['DBA']; ?></td>
                                </tr>

                                <tr>
                                    <td style="text-align: center;" colspan="6" class="active"><strong>Setup Information</strong></td>
                                </tr>
                                
                                <tr>
                                    <td><?php echo "Attached:"?></td>
                                    <td>
					<?php 
						echo $this->Form->input('setup_banking', 
							array('label' => "Banking (no starter checks)")
						); 
					?>
				    </td>
                                    <td>
					<?php 
						echo $this->Form->input('setup_statements',
							array('label' => "3 Mo. Processing Stmts")
						); 
					?>
				    </td>
                                    <td colspan="3">
					<?php 
						echo $this->Form->input('setup_drivers_license',
							array('label' => "Owner's Driver's License")
						);
					?>
				    </td>
                                </tr>
                                <tr>
                                    <td><?php echo "For New Businesses:"?></td>
                                    <td>
					<?php
						echo $this->Form->input('setup_business_license',
							array(
								'label' => "Business License or Utility Bill"
							)
						); 
					?>
				    </td>
                                    <td colspan="4">
					<?php
						echo $this->Form->input('setup_other',
							array(
								'label' => "Other:"
							)
						);
						echo $this->Form->input('setup_field_other',
							array(
								'div' => false,
								'label' => false,
								'style' => 'width: 120px;',
								'size' => '20'
							)
						);
					?>
				    </td>
                                </tr>
                            
                                <tr <?php if ($tier == 'tier1') echo 'class="warning"'; ?>>
                                    <td>
                                    <?php 
                                        $options = array('1' => 'Tier 1:');
                                        echo $this->Form->radio('setup_tier_select', $options);
                                    
                                        if ($this->Form->isFieldError('setup_tier_select')){
                                            echo $this->Form->error('setup_tier_select');
                                        }
                                    ?>
                                    </td>
                                    <td colspan="5">Retail: Volume $0-$250,000/month, Average Ticket Less Than $1,000</td>
                                </tr>

                                <tr <?php if ($tier == 'tier2') echo 'class="warning"'; ?>>
                                    <td>
                                        <?php 
                                            $options = array('2' => 'Tier 2:');
                                            echo $this->Form->radio('setup_tier_select', $options, $attributes);
                                        ?>
                                    </td>
                                    <td colspan="5">MOTO: Volume $0-$150,000, Average Ticket Less Than $1,000</td>
                                </tr>

                                <tr <?php if ($tier == 'tier3') echo 'class="warning"'; ?>>
                                    <td>
                                        <?php 
                                            $options = array('3' => 'Tier 3:');
                                            echo $this->Form->radio('setup_tier_select', $options, $attributes);
                                        ?>
                                    </td>
                                    <td colspan="5">
                                        <p>Retail: Volume $250,000 or Greater/month, Average Ticket Less Than $1,000</p>
                                        <?php
						echo $this->Form->input('setup_tier3',
                                            		array(
								'label' => '2 years business financials or 2 years tax returns'
							)
						);
                                        ?>
                                    </td>
                                </tr>

                                <tr <?php if ($tier == 'tier4') echo 'class="warning"'; ?>>
                                    <td>
                                        <?php 
                                            $options = array('4' => 'Tier 4:');
                                            echo $this->Form->radio('setup_tier_select', $options, $attributes);
                                        ?>
                                    </td>
                                    <td colspan="5">
                                        <p>Retail: Volume $0-$250,000/month, Average Ticket Greater Than $1,000</p>
                                        <p>MOTO/Internet: Volume $0-$150,000/month, Average Ticket Greater than $1,000</p>
                                        <?php
						echo $this->Form->input('setup_tier4',
							array(
								'label' => '2 years business financials or 2 years tax returns'
							)
						);
					?>
                                    </td>
                                </tr>

                                <tr <?php if ($tier == 'tier5') echo 'class="warning"'; ?>>
                                    <td>
                                        <?php 
                                            $options = array('5' => 'Tier 5:');
                                            echo $this->Form->radio('setup_tier_select', $options, $attributes);
                                        ?>                        
                                    </td>
                                    <td colspan="5"><p>Retail: Volume $250,000 or Greater/month, Average Ticket Greater Than $1,000</p>
                                        <p>MOTO/Internet: Volume $150,000 or Greater/month</p>
                                        <?php
						echo $this->Form->input('setup_tier5_financials',
							array(
								'label' => '2 years audited business financials or 2 years tax returns'
							)
						);
                                        	echo $this->Form->input('setup_tier5_processing_statements',
							array(
								'label' => '6 months processing statements'
							)
						);
                                        	echo $this->Form->input('setup_tier5_bank_statements',
							array(
								'label' => '3 months bank statements'
							)
						);
					?>
                                    </td>
                                </tr>
                            
                                <tr>
                                    <td>
                                        <?php 
                                        if ($this->Form->isFieldError('setup_equipment_terminal') && $this->Form->isFieldError('setup_equipment_gateway')){
                                        echo $this->Form->error('setup_equipment_terminal');
                                        } else echo 'Equipment Type:'; ?>
                                    </td>
                                    <td>
					<?php
						echo $this->Form->input('setup_equipment_terminal',
							array(
								'label' => 'Terminal',
								'error' => false
							)
						);
					?>
				    </td>
                                    <td colspan="4">
					<?php
						echo $this->Form->input('setup_equipment_gateway',
							array(
								'label' => 'POS/Gateway',
								'error' => false
							)
						);
					?>
				    </td>
                                         
                               </tr>
                           
                                <tr>
                                    <td>
                                        <?php 
                                            if ($this->Form->isFieldError('setup_install')){
                                                echo 'Install to be done by: ' . $this->Form->error('setup_install');
                                            } else 
                                                echo 'Install to be done by:';
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $options = array('rep' => 'Rep');
                                            echo $this->Form->radio('setup_install', $options);
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $options = array('axia' => 'Axia');                       
                                            echo $this->Form->radio('setup_install', $options, $attributes);
                                        ?>
                                    </td>    
                                    <td colspan="3">
                                        <?php 
                                            $options = array('pos' => 'POS/Gateway Provider');
                                            echo $this->Form->radio('setup_install', $options, $attributes);
                                        ?>
                                    </td>
                               </tr>
 
                                <tr>
                                    <td>
                                        <?php
                                            if ($this->Form->isFieldError('setup_starterkit')){
                                                echo 'Starter kits supplies provided by (terminal only): ' . $this->Form->error('setup_starterkit');
                                            } else
                                                echo 'Starter kits supplies provided by (terminal only):'; 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $options = array('rep' => 'Rep');
                                            echo $this->Form->radio('setup_starterkit', $options);
                                        ?>
                                    </td>
                                    <td colspan="4">
                                        <?php 
                                            $options = array('axia' => 'Axia (ship or drop off)');
                                            echo $this->Form->radio('setup_starterkit', $options, $attributes);
                                        ?>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-condensed">
                                <tr>
                                    <td style="width: 50px">Partner:</td>
                                    <td>
                                        <?php echo $this->Form->input('setup_partner',array('div' => false, 'label' => false)); ?>
                                    </td>
                                    <td>
                                        Pmt Info:
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('setup_partner_pct_profit',
                                            array(
                                                'type' => 'text',
                                                'data-vtype' => 'number',
                                                'min' => '1',
                                                'max' => '100',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "placeholder": "0",
                                                    "autoGroup": true,
                                                    "digits": 3,
                                                    "digitsOptional": false'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        % Profit&nbsp;&nbsp;&nbsp;&nbsp;or
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('setup_partner_pct_volume',
                                            array(
                                                'type' => 'text',
                                                'data-vtype' => 'number',
                                                'min' => '.01',
                                                'max' => '5',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "placeholder": "0",
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": false'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        % Vol. (Basis Pts).
                                    </td>
                                    <td>
                                        % of Gross:
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('setup_partner_pct_gross',
                                            array(
                                                'type' => 'text',
                                                'data-vtype' => 'number',
                                                'min' => '1',
                                                'max' => '100',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "placeholder": "0",
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": false'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        %
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50px">Referrer:</td>
                                    <td>
                                        <?php echo $this->Form->input('setup_referrer',array('div' => false, 'label' => false)); ?>
                                    </td>
                                    <td>
                                        Pmt Info:
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('setup_referrer_pct_profit',
                                            array(
                                                'type' => 'text',
                                                'data-vtype' => 'number',
                                                'min' => '1',
                                                'max' => '100',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "placeholder": "0",
                                                    "autoGroup": true,
                                                    "digits": 3,
                                                    "digitsOptional": false'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        % Profit&nbsp;&nbsp;&nbsp;&nbsp;or
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('setup_referrer_pct_volume',
                                            array(
                                                'type' => 'text',
                                                'data-vtype' => 'number',
                                                'min' => '.01',
                                                'max' => '5',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "placeholder": "0",
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": false'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        % Vol. (Basis Pts).
                                    </td>
                                    <td>
                                        % of Gross:
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('setup_referrer_pct_gross',
                                            array(
                                                'type' => 'text',
                                                'data-vtype' => 'number',
                                                'min' => '1',
                                                'max' => '100',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "placeholder": "0",
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": false'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        %
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50px">Reseller:</td>
                                    <td>
                                        <?php echo $this->Form->input('setup_reseller',array('div' => false, 'label' => false)); ?>
                                    </td>
                                    <td>
                                        Pmt Info:
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('setup_reseller_pct_profit',
                                            array(
                                                'type' => 'text',
                                                'data-vtype' => 'number',
                                                'min' => '1',
                                                'max' => '100',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "placeholder": "0",
                                                    "autoGroup": true,
                                                    "digits": 3,
                                                    "digitsOptional": false'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        % Profit&nbsp;&nbsp;&nbsp;&nbsp;or
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('setup_reseller_pct_volume',
                                            array(
                                                'type' => 'text',
                                                'data-vtype' => 'number',
                                                'min' => '.01',
                                                'max' => '5',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "placeholder": "0",
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": false'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        % Vol. (Basis Pts).
                                    </td>
                                    <td>
                                        % of Gross:
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('setup_reseller_pct_gross',
                                            array(
                                                'type' => 'text',
                                                'data-vtype' => 'number',
                                                'min' => '1',
                                                'max' => '100',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "placeholder": "0",
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": false'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        %
                                    </td>
                                </tr>
                            
                                <tr>
                                    <td colspan="10">
                                        Setup Notes: <?php echo $this->Form->input('setup_notes',
                                            array(
                                                'rows' => '2',
                                                'div' => false,
                                                'label' => false,
                                                'style' => 'width:100%',
                                                'size' => '255',
                                                'hiddenField' => false
                                            )
                                        ); ?>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-condensed">
                                <tr>
                                    <td style="text-align: center" class="active"><strong>Merchant Info Questionnaire</strong></td>
                                </tr>
                            </table>

                            <?php if ($data['Coversheet']['status'] == 'saved'){
                                if ($cp != true): ?>
                                    <table class="table table-condensed">
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

                            <table class="table table-condensed">

                                <tr>                    
                                    <td colspan="4"><strong>Card Present Merchant:</strong></td>
                                </tr>                  
                                
                                <tr>      
                                    <td style="width: 30%;">
                                        <?php if ($this->Form->isFieldError('cp_encrypted_sn') || $this->Form->isFieldError('cp_pinpad_ra_attached')){
                                            echo 'Does the Merchant have Debit?' . $this->Form->error('cp_encrypted_sn');
                                        } else echo 'Does the Merchant have Debit?'; 
                                        ?>                                
                                        
                                    </td>
                                    <td >
                                        <div class="label label-default">
                                            <?php echo $data['CobrandedApplication']['TermAcceptDebit-Yes'] == 'true' ? 'Yes' : 'No'; ?>
                                        </div>
                                    </td>
                                    <td style="width: 21%;">Pin Pad Type?
                                        <div <?php echo ($data['CobrandedApplication']['PinPad1']) ? 'class="label label-default"' : ''; ?> >
                                            <?php echo $data['CobrandedApplication']['PinPad1'];?>
                                        </div>
                                    </td>
                                    <td >
                                        <?php echo $this->Form->input('cp_encrypted_sn', array('label' => 'JR\'s encrypted-S/N ', 'style' => 'width:100px','size' => '20', 'error' => false)); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 30%;">
                                        Does the Merchant do autoclose?
                                    </td>
                                    <td style="width: 12%;">
                                        <div class="label label-default">
                                            <?php echo $data['CobrandedApplication']['DoYouUseAutoclose-Autoclose'] == 'true' ? 'Yes' : 'No'; ?>
                                        </div>
                                    </td>
                                    <td>
                                        Time: <div <?php echo ($data['CobrandedApplication']['Autoclose Time 1']) ? 'class="label label-default"' : ''; ?> ><?php echo $data['CobrandedApplication']['Autoclose Time 1']; ?></div>
                                    </td>
                                    <td >
                                        <?php
						echo $this->Form->input('cp_pinpad_ra_attached',
							array(
								'label' => 'Pin Pad Encryption RA Attached?',
								'error' => false
							)
						);
					?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Does the merchant do gift cards?
                                    </td>
                                    <td >
                                        <?php
                                            $options=array('yes' => 'Yes');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('cp_giftcards',$options,$attributes);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('no' => 'No');
                                            $attributes=array('legend' => false, 'hiddenField' => false);
                                            echo $this->Form->radio('cp_giftcards',$options,$attributes);
                                        ?>
                                    </td>
                                    <td>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Does the merchant have check guarantee?
                                    </td>   
                                    <td>
                                        <?php
                                            $options=array('yes' => 'Yes');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('cp_check_guarantee',$options,$attributes);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('no' => 'No');
                                            $attributes=array('legend' => false, 'hiddenField' => false);
                                            echo $this->Form->radio('cp_check_guarantee',$options,$attributes);
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('cp_check_guarantee_info', array('label' => 'Info: ', 'style' => 'width:80%','size' => '15')); ?>
                                    </td>
                                 </tr>

                                 <tr>
                                    <td>Does the merchant accept Amex?</td>
                                    <td>
                                        <div class="label label-default">
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
                                        <?php
                                            if ($data['CobrandedApplication']['AmexNum'] == '' && $data['CobrandedApplication']['DoYouWantToAcceptAE-New'] == 'true') {
                                                echo 'Request New Amex';
                                            }
                                            else {
                                                echo 'Amex#';
                                                if ($data['CobrandedApplication']['AmexNum'] != '') {
                                                    echo '<div class="label label-default">';
                                                }
                                                else {
                                                    echo '<div>';
                                                }
                                                echo $data['CobrandedApplication']['AmexNum'].'</div>';
                                            }
                                        ?>    
                                    </td>
                                    <td>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Does the merchant accept Discover?</td>
                                    <td>
                                        <div class="label label-default">
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
                                            $options=array('yes' => 'Yes');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('cp_pos',$options,$attributes);
                                        ?>    
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('no' => 'No');
                                            $attributes=array('legend' => false, 'hiddenField' => false);
                                            echo $this->Form->radio('cp_pos',$options,$attributes);
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('cp_pos_contact', array('label' => 'Contact Info: ', 'style' => 'width:75%','size' => '15'));?>
                                    </td>
                                </tr>

                                <tr>                    
                                    <td colspan="4"><strong>For Restaurant Merchant:</strong></td>
                                </tr>

                                <tr>
                                    <td>
                                        Does the merchant do tips?
                                    </td>
                                    <td>
                                        <div class="label label-default">
                                            <?php echo $data['CobrandedApplication']['Tips'] == 'true' ? 'Yes' : 'No'; ?>
                                        </div>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Does the merchant do server #s?
                                    </td>
                                    <td>
                                        <div class="label label-default">
                                            <?php echo $data['CobrandedApplication']['Server'] == 'true' ? 'Yes' : 'No'; ?>
                                        </div>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
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
                            <table class="table table-condensed">
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
                             <table class="table table-condensed">
                                <tr>
                                     <td colspan="3"><strong>For MICROS Merchant:</strong></td>
                                </tr>

                                <tr>
                                    <td>What Type of Micros system does the merchant have?</td>
                                    <td>
                                        <?php
                                            $options=array('ip' => 'IP');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('micros',$options,$attributes);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('dial' => 'Dial-Up');
                                            $attributes=array('legend' => false, 'hiddenField' => false);
                                            echo $this->Form->radio('micros',$options,$attributes);
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php
                                        if ($this->Form->isFieldError('micros')) {
                                            echo $this->Form->error('micros');
                                        } else {echo 'How will the additional per item fee be handled?';}
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('rep' => 'Included in Pricing/Billed to Rep');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('micros_billing',$options,$attributes);
                                        ?>    
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('merchant' => 'Bill Merchant (AUTHORIZATION FORM ATTACHED)');
                                            $attributes=array('legend' => false, 'hiddenField' => false);
                                            echo $this->Form->radio('micros_billing',$options,$attributes);
                                        ?>    
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
                                    echo '<table class="table table-condensed">';
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
                            <table class="table table-condensed">
                                <tr>
                                    <td colspan="5"><strong>For Axia Gateway Merchant:</strong></td>
                                </tr>

                                <tr>
                                    <td>What setup option?</td>
                                    <td>
                                       <?php
                                            $options=array('option1' => 'Option 1 ($75 setup, $10/month, $0.05 per item)');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('gateway_option',$options,$attributes);
                                       ?>
                                    </td>
                                    <td>
                                       <?php
                                            $options=array('option2' => 'Option 2 ($29.95 setup, $15/month, $0.05 per item)');
                                            $attributes=array('legend' => false, 'hiddenField' => false);
                                            echo $this->Form->radio('gateway_option',$options,$attributes);
                                       ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td><?php if ($this->Form->isFieldError('gateway_package')) {
                                             echo $this->Form->error('gateway_package');
                                         } else if($this->Form->isFieldError('gateway_gold_subpackage')) {
                                             echo $this->Form->error('gateway_gold_subpackage');
                                         } else echo 'What Package'; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('silver' => 'Silver', 'gold' => 'Gold* (Please select:');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('gateway_package',$options,$attributes);
                                            $options=array('cust_db' => 'Customer Database or', 'fraud' => 'Fraud Package)');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('gateway_gold_subpackage',$options,$attributes);
                                            $options=array('platinum' => 'Platinum*');
                                            $attributes=array('legend' => false, 'hiddenField' => false);
                                            echo $this->Form->radio('gateway_package',$options,$attributes);
                                        ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="text-align: center;">*Additional fees may apply for Gold and Platinum packages</td>
                                </tr>

                                <tr>
                                    <td>
                                        Retail swipe enabled?
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('yes' => 'Yes');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('gateway_retail_swipe',$options,$attributes);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('no' => 'No');
                                            $attributes=array('legend' => false, 'hiddenField' => false);
                                            echo $this->Form->radio('gateway_retail_swipe',$options,$attributes);
                                        ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php
                                            if($this->Form->isFieldError('gateway_epay')) {
                                            echo $this->Form->error('gateway_epay');
                                            } else echo 'ePay Charge Software'; ?>

                                            (ePay Charge Software used for swiped transactions on USAePay)
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('yes' => 'Yes');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('gateway_epay',$options,$attributes); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('no' => 'No');
                                            $attributes=array('legend' => false, 'hiddenField' => false);
                                            echo $this->Form->radio('gateway_epay',$options,$attributes); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo "Number of ePay Charge Licenses:"; ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('gateway_epay_charge_licenses',
                                            array(
                                                'type' => 'text',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "placeholder": "0",
                                                    "autoGroup": true,
                                                    "digits": 0,
                                                    "digitsOptional": false,
                                                    "clearMaskOnLostFocus": false'
                                            )
                                        ); ?>
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
                                    <td>
                                        <?php
                                            $options=array('rep' => 'Included in Pricing/Billed to Rep');
                                            $attributes=array('legend' => false);
                                            echo $this->Form->radio('gateway_billing',$options,$attributes); 
                                        ?> 
                                    </td>
                                    <td>
                                        <?php
                                            $options=array('merchant' => 'Bill Merchant (AUTHORIZATION FORM ATTACHED)');
                                            $attributes=array('legend' => false, 'hiddenField' => false);
                                            echo $this->Form->radio('gateway_billing',$options,$attributes); 
                                        ?> 
                                    </td>
                                    <td></td>
                                    <td></td>
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
                            <table class="table table-condensed">
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
                            <table class="table table-condensed">
                                <tr>
                                    <td colspan="3"><strong>For MOTO/Internet Merchant:</strong></td>
                                </tr>
                                <tr>
                                     <td>Does the merchant accept Amex?</td>
                                     <td>
                                         <div class="label label-default">
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
                                        <?php
                                            if ($data['CobrandedApplication']['AmexNum'] == '' && $data['CobrandedApplication']['DoYouWantToAcceptAE-New'] == 'true') {
                                                echo 'Request New Amex';
                                            }
                                            else {
                                                echo 'Amex#';
                                                if ($data['CobrandedApplication']['AmexNum'] != '') {
                                                    echo '<div class="label label-default">';
                                                }
                                                else {
                                                    echo '<div>';
                                                }
                                                echo $data['CobrandedApplication']['AmexNum'].'</div>';
                                            }
                                        ?>
                                     </td>
                                 </tr>
                                 <tr>
                                    <td>Does the merchant accept Discover?</td>
                                    <td>
                                        <div class="label label-default">
                                            <?php echo $data['CobrandedApplication']['DoYouWantToAcceptDisc-New'] == 'true' ? 'Yes' : 'No'; ?> 
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo $data['CobrandedApplication']['DoYouWantToAcceptDisc-New'] == 'true' ? 'Axia Request New' : ''; ?>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                         <?php if ($this->Form->isFieldError('moto_online_chd')) {
                                             echo $this->Form->error('moto_online_chd');
                                         } else 
                                         echo 'Internet Merchants: Does the merchant store credit card numbers online?';
                                         ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $options=array('yes' => 'Yes');
                                            $attributes = array('legend' => false);
                                            echo $this->Form->radio('moto_online_chd', $options,$attributes);
                                        ?>   
                                    </td>
                                    <td>
                                        <?php 
                                            $options=array('no' => 'No');
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
            	echo $this->Element('cobranded_applications/return');
            ?>
        </div>
    </div>
</div>    
