<div class='container'>
<br />
    <div class='panel panel-primary'>
    <div class=" panel-heading"><h4 class="text-center panel-title"><?php echo __(ucfirst($this->action) . ' Coversheet'); ?></h4></div>
    <div class='row'>
        <div class='col-xs-12'>
            <?php echo $this->Html->css('coversheet', null, array('inline' => false)); ?>

            <script type="text/javascript" src="/js/jquery-validate.1.11.11.js"></script>
            <script type="text/javascript" src="/js/jquery-validate-additional-methods.js"></script>
            <script type="text/javascript" src="/js/jquery.inputmask/dist/jquery.inputmask.bundle.js"></script>

            <script type="text/javascript">
                jQuery.noConflict();  

                jQuery(document).ready(function () {
                    jQuery('.text-danger').addClass('error-message');
                    jQuery(":input").inputmask();

                    jQuery('input[type=submit]').click(function(e) {

                        var focusField = null;
                        var submitFlag = true;

                        /*if (jQuery('#CoversheetSetupPartner').val() || jQuery('#CoversheetSetupPartnerPctProfit').val() ||
                            jQuery('#CoversheetSetupPartnerPctVolume').val() || jQuery('#CoversheetSetupPartnerPctGross').val()) {

                            if (!jQuery('#CoversheetSetupPartner').val()) {
                                jQuery('#CoversheetSetupPartner').css('background','#FFFF00');
                                focusField = 'CoversheetSetupPartner';
                                submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupPartner').css('background','#FFFFFF');
                            }

                            if (!jQuery('#CoversheetSetupPartnerPctProfit').val() && !jQuery('#CoversheetSetupPartnerPctVolume').val()) {
                                    jQuery('#CoversheetSetupPartnerPctProfit').css('background','#FFFF00');
                                    jQuery('#CoversheetSetupPartnerPctVolume').css('background','#FFFF00');
                                    if (focusField == null) {
                                        focusField = 'CoversheetSetupPartnerPctProfit';
                                    }
                                    submitFlag = false;
                            }
                            else {
                                if ((jQuery('#CoversheetSetupPartnerPctProfit').val() && (jQuery('#CoversheetSetupPartnerPctProfit').val() > 100 || jQuery('#CoversheetSetupPartnerPctProfit').val() < 0)) ||
                                    (jQuery('#CoversheetSetupPartnerPctVolume').val() && (jQuery('#CoversheetSetupPartnerPctVolume').val() > 5 || jQuery('#CoversheetSetupPartnerPctVolume').val() < 0))) {
                                        jQuery('#CoversheetSetupPartnerPctProfit').css('background','#FFFF00');
                                        jQuery('#CoversheetSetupPartnerPctVolume').css('background','#FFFF00');
                                        if (focusField == null) {
                                            focusField = 'CoversheetSetupPartnerPctProfit';
                                        }
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
                                    if (focusField == null) {
                                        focusField = 'CoversheetSetupPartnerPctGross';
                                    }
                                    submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupPartnerPctGross').css('background','#ffffff');
                            }
                        }*/

                        if (jQuery('#CoversheetSetupReferrer').val() || jQuery('#CoversheetSetupReferrerPctProfit').val() ||
                            jQuery('#CoversheetSetupReferrerPctVolume').val() || jQuery('#CoversheetSetupReferrerPctGross').val()) {

                            if (!jQuery('#CoversheetSetupReferrer').val()) {
                                jQuery('#CoversheetSetupReferrer').css('background','#FFFF00');
                                if (focusField == null) {
                                    focusField = 'CoversheetSetupReferrer';
                                }
                                submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupReferrer').css('background','#FFFFFF');
                            }

                            if (!jQuery('#CoversheetSetupReferrerPctProfit').val() && !jQuery('#CoversheetSetupReferrerPctVolume').val()) {
                                    jQuery('#CoversheetSetupReferrerPctProfit').css('background','#FFFF00');
                                    jQuery('#CoversheetSetupReferrerPctVolume').css('background','#FFFF00');
                                    if (focusField == null) {
                                        focusField = 'CoversheetSetupReferrerPctProfit';
                                    }
                                    submitFlag = false;
                            }
                            else {
                                if ((jQuery('#CoversheetSetupReferrerPctProfit').val() && (jQuery('#CoversheetSetupReferrerPctProfit').val() > 100 || jQuery('#CoversheetSetupReferrerPctProfit').val() < 0)) ||
                                    (jQuery('#CoversheetSetupReferrerPctVolume').val() && (jQuery('#CoversheetSetupReferrerPctVolume').val() > 5 || jQuery('#CoversheetSetupReferrerPctVolume').val() < 0))) {
                                        jQuery('#CoversheetSetupReferrerPctProfit').css('background','#FFFF00');
                                        jQuery('#CoversheetSetupReferrerPctVolume').css('background','#FFFF00');
                                        if (focusField == null) {
                                            focusField = 'CoversheetSetupReferrerPctProfit';
                                        }
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
                                    if (focusField == null) {
                                        focusField = 'CoversheetSetupReferrerPctGross';
                                    }
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
                                if (focusField == null) {
                                    focusField = 'CoversheetSetupReseller';
                                }
                                submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupReseller').css('background','#FFFFFF');
                            }

                            if (!jQuery('#CoversheetSetupResellerPctProfit').val() && !jQuery('#CoversheetSetupResellerPctVolume').val()) {
                                    jQuery('#CoversheetSetupResellerPctProfit').css('background','#FFFF00');
                                    jQuery('#CoversheetSetupResellerPctVolume').css('background','#FFFF00');
                                    if (focusField == null) {
                                        focusField = 'CoversheetSetupResellerPctProfit';
                                    }
                                    submitFlag = false;
                            }
                            else {
                                if ((jQuery('#CoversheetSetupResellerPctProfit').val() && (jQuery('#CoversheetSetupResellerPctProfit').val() > 100 || jQuery('#CoversheetSetupResellerPctProfit').val() < 0)) ||
                                    (jQuery('#CoversheetSetupResellerPctVolume').val() && (jQuery('#CoversheetSetupResellerPctVolume').val() > 5 || jQuery('#CoversheetSetupResellerPctVolume').val() < 0))) {
                                        jQuery('#CoversheetSetupResellerPctProfit').css('background','#FFFF00');
                                        jQuery('#CoversheetSetupResellerPctVolume').css('background','#FFFF00');
                                        if (focusField == null) {
                                            focusField = 'CoversheetSetupResellerPctProfit';
                                        }
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
                                    if (focusField == null) {
                                        focusField = 'CoversheetSetupResellerPctGross';
                                    }
                                    submitFlag = false;
                            }
                            else {
                                jQuery('#CoversheetSetupResellerPctGross').css('background','#ffffff');
                            }
                        }

                        if (!submitFlag) {
                            e.preventDefault();
                            jQuery("#"+focusField).focus();
                            return false;
                        }

                        jQuery('#CoversheetEditForm').append('<input type="hidden" name="'+this.name+'" value="'+this.name+'" />');
                        jQuery(this).attr('disabled', 'disabled');
                        document.getElementById('CoversheetEditForm').submit();
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

            <?php if (Hash::get($data, 'Coversheet.status') != 'saved') { ?>
            <script type="text/javascript">                                        
               jQuery(document).ready(function () {
               jQuery('#CoversheetEditForm :input').attr('disabled', true);
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
            	       <?php echo $this->Form->hidden('id'); ?>
                            <table class="table table-condensed">
                                <tr>
                                    <td colspan="3"><?php echo 'Rep Name: '. Hash::get($data, 'User.firstname', '--') .' '. Hash::get($data, 'User.lastname', ''); ?></td>
                                    <td colspan="3"><?php echo 'Merchant: ' . Hash::get($data, 'CobrandedApplication.DBA'); ?></td>
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
                                        $tierLabelsAttr = array('class' => false);
                                        $options = array('1' => 'Tier 1:');
                                        echo $this->Form->radio('setup_tier_select', $options, $tierLabelsAttr);
                                    
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
                                            echo $this->Form->radio('setup_tier_select', $options, array_merge($attributes, $tierLabelsAttr));
                                        ?>
                                    </td>
                                    <td colspan="5">MOTO: Volume $0-$150,000, Average Ticket Less Than $1,000</td>
                                </tr>

                                <tr <?php if ($tier == 'tier3') echo 'class="warning"'; ?>>
                                    <td>
                                        <?php 
                                            $options = array('3' => 'Tier 3:');
                                            echo $this->Form->radio('setup_tier_select', $options, array_merge($attributes, $tierLabelsAttr));
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
                                            echo $this->Form->radio('setup_tier_select', $options, array_merge($attributes, $tierLabelsAttr));
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
                                            echo $this->Form->radio('setup_tier_select', $options, array_merge($attributes, $tierLabelsAttr));
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
                                                echo 'Go-Live to be done by: ' . $this->Form->error('setup_install');
                                            } else 
                                                echo 'Go-Live to be done by:';
                                        ?>
                                    </td>
                                    <td colspan='2'>
                                        <?php 
                                            $options = array('rep' => 'Rep');
                                            echo $this->Form->radio('setup_install', $options, ['class'=> 'col-md-6 col-lg-6 col-sm-6']);

                                             $options = array('axia' => 'Axia');                       
                                            echo $this->Form->radio('setup_install', $options, array_merge($attributes, ['class'=> 'col-md-6 col-lg-6 col-sm-6']));
                                        ?>
                                    </td>
                                    <td >
                                        <?php 
                                            $options = array('pos' => 'POS/Gateway Provider');
                                            echo $this->Form->radio('setup_install', $options, array_merge($attributes, ['class'=> false]));
                                        ?>
                                    </td>    
                                    <td>
                                        <?php 
                                        $expInsDateSettings = array(
                                                'label' => 'Expected to Go-Live',
                                                'type' => 'date',
                                                'dateFormat' => 'MDY',
                                                'minYear' => date('Y'),
                                                'maxYear' => date('Y') + 10,
                                                'orderYear' => 'asc',
                                                'empty' => '--',
                                            );
                                        if (empty($this->request->data('Coversheet.expected_install_date'))) {
                                                $expInsDateSettings['value'] = array('day' => '--', 'month' => '--', 'year' => '--');
                                        }
                                        echo $this->Form->input('Coversheet.expected_install_date', $expInsDateSettings);
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
                                    <td>
                                        Org. Name
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('org_name',
                                            array(
                                                'div' => false,
                                                'label' => false,
                                                'after' => '<span class="small text-primary"><strong>Please pick Organization from list<br>if it\'s shown as you type.</strong></span>'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        Region Name
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('region_name',
                                            array(
                                                'div' => false,
                                                'label' => false,
                                                'after' => '<span class="small text-primary"><strong>Please pick Region from list if<br>it\'s shown as you type.</strong></span>'
                                            )
                                        ); ?>
                                    </td>
                                    <td>
                                        Sub-Region
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('subregion_name',
                                            array(
                                                'div' => false,
                                                'label' => false,
                                                'after' => '<span class="small text-primary"><strong>Please pick Sub-Region from list<br>if it\'s shown as you type.</strong></span>'
                                            )
                                        ); ?>
                                    </td>

                                </tr>
                            </table>
                            <script>
                                enableAutocomplete('#CoversheetOrgName', '/Coversheets/get_orgs_suggestions');
                                enableAutocomplete('#CoversheetRegionName', '/Coversheets/get_regions_suggestions');
                                enableAutocomplete('#CoversheetSubregionName', '/Coversheets/get_subregions_suggestions');
                            </script>
                            <table class="table table-condensed">
                                <tr>
                                    <td style="width: 50px">Partner:</td>
                                    <td>
										<?php echo $this->Form->input('setup_partner',
											array(
												'type' => 'text',
												'div' => false,
												'label' => false,
                                                'after' => '<span class="small text-primary"><strong>Please pick Partner from list<br>if it\'s shown as you type.</strong></span>'
											)
										); 
                                        ?>
                                    </td>
                                    <td>
                                        Pmt Info:
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('setup_partner_pct_profit',
                                            array(
                                                'type' => 'text',
                                                'data-vtype' => 'number',
                                                'min' => '0',
                                                'max' => '100',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "autoGroup": true,
                                                    "digits": 3,
                                                    "digitsOptional": true'
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
                                                'min' => '0',
                                                'max' => '5',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": true'
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
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": true'
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
                                                'min' => '0',
                                                'max' => '100',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "autoGroup": true,
                                                    "digits": 3,
                                                    "digitsOptional": true'
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
                                                'min' => '0',
                                                'max' => '5',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": true'
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
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": true'
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
                                                'min' => '0',
                                                'max' => '100',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "autoGroup": true,
                                                    "digits": 3,
                                                    "digitsOptional": true'
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
                                                'min' => '0',
                                                'max' => '5',
                                                'div' => false,
                                                'label' => false,
                                                'data-inputmask' => '
                                                    "alias": "numeric",
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": true'
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
                                                    "autoGroup": true,
                                                    "digits": 2,
                                                    "digitsOptional": true'
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

                            <?php if (Hash::get($data, 'Coversheet.status') == 'saved'){
                                if ($cp != true): ?>
                                    <table class="table table-condensed">
                                        <tr>
                                            <td>
                                                <?php
                                                    $div_show = 'ShowCpLink';
                                                    $div_hide = 'HideCpLink';
                                                    $div_string = 'Cp';
                                                    echo '<div id="'.$div_show.'">';
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
                            if((Hash::get($data, 'Coversheet.status') != 'saved' && $cp != false)  || Hash::get($data, 'Coversheet.status') == 'saved') {
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
                                            <?php echo Hash::get($data, 'CobrandedApplication.TermAcceptDebit-Yes') == 'true' ? 'Yes' : 'No'; ?>
                                        </div>
                                    </td>
                                    <td style="width: 21%;">Pin Pad Type?
                                        <div <?php echo (Hash::get($data, 'CobrandedApplication.PinPad1')) ? 'class="label label-default"' : ''; ?> >
                                            <?php echo Hash::get($data, 'CobrandedApplication.PinPad1');?>
                                        </div>
                                    </td>
                                    <td >
                                        <?php echo $this->Form->input('cp_encrypted_sn',
                                            array(
                                                'label' => 'JR\'s encrypted-S/N ',
                                                'style' => 'width:100px',
                                                'size' => '20',
                                                'required' => false,
                                                'error' => false
                                            )
                                        ); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 30%;">
                                        Does the Merchant do autoclose?
                                    </td>
                                    <td style="width: 12%;">
                                        <div class="label label-default">
                                            <?php echo Hash::get($data, 'CobrandedApplication.DoYouUseAutoclose-Autoclose') == 'true' ? 'Yes' : 'No'; ?>
                                        </div>
                                    </td>
                                    <td>
                                        Time: <div <?php echo (Hash::get($data, 'CobrandedApplication.Autoclose Time 1')) ? 'class="label label-default"' : ''; ?> ><?php echo Hash::get($data, 'CobrandedApplication.Autoclose Time 1'); ?></div>
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
                                        <?php echo $this->Form->input('cp_check_guarantee_info',
                                            array(
                                                'label' => 'Info: ',
                                                'style' => 'width:80%',
                                                'size' => '15',
                                                'required' => false
                                            )
                                        ); ?>
                                    </td>
                                 </tr>

                                 <tr>
                                    <td>Does the merchant accept Amex?</td>
                                    <td>
                                        <div class="label label-default">
                                            <?php
                                                if (Hash::get($data, 'CobrandedApplication.DoYouAcceptAE-Exist') == 'true' ||
                                                    Hash::get($data, 'CobrandedApplication.DoYouWantToAcceptAE-New') == 'true') {
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
                                            if (empty($data['CobrandedApplication']['AmexNum']) && Hash::get($data, 'CobrandedApplication.DoYouWantToAcceptAE-New') == 'true') {
                                                echo 'Request New Amex';
                                            }
                                            else {
                                                echo 'Amex#';
                                                if (!empty($data['CobrandedApplication']['AmexNum'])) {
                                                    echo '<div class="label label-default">';
                                                }
                                                else {
                                                    echo '<div>';
                                                }
                                                echo Hash::get($data, 'CobrandedApplication.AmexNum', '').'</div>';
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
                                            <?php echo Hash::get($data, 'CobrandedApplication.DoYouWantToAcceptDisc-New') == 'true' ? 'Yes' : 'No'; ?> 
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo Hash::get($data, 'CobrandedApplication.DoYouWantToAcceptDisc-New') == 'true' ? 'Axia Request New' : ''; ?>
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
                                            <?php echo Hash::get($data, 'CobrandedApplication.Tips') == 'true' ? 'Yes' : 'No'; ?>
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
                                            <?php echo Hash::get($data, 'CobrandedApplication.Server') == 'true' ? 'Yes' : 'No'; ?>
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
                             if (Hash::get($data, 'Coversheet.status') == 'saved'){
                                if ($cp != true) { echo '</div>'; }
                             }
                             if(Hash::get($data, 'Coversheet.status') == 'saved') {
                                 if (isset($micros) && $micros != true) {
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
                             if((Hash::get($data, 'Coversheet.status') != 'saved' && !empty(Hash::get($data, 'Coversheet.micros')))  || Hash::get($data, 'Coversheet.status') == 'saved') {
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
                            if(Hash::get($data, 'Coversheet.status') == 'saved') {
                            if (isset($micros) && $micros != true) {echo '</div>';}
                            }?>
                            <?php 
                            if(Hash::get($data, 'Coversheet.status') == 'saved') {
                                if (isset($gateway) && $gateway != true) {
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
                            if((Hash::get($data, 'Coversheet.status') != 'saved' && !empty(Hash::get($data, 'Coversheet.gateway_option'))) || Hash::get($data, 'Coversheet.status') == 'saved') {
                                 ?>                
                            <table class="table table-condensed">
                                <tr>
                                    <td colspan="5"><strong>For Axia Gateway Merchant:</strong></td>
                                </tr>

                                <tr>
                                    <td><?php if ($this->Form->isFieldError('gateway_package')) {
                                             echo $this->Form->error('gateway_package');
                                         } else if($this->Form->isFieldError('gateway_gold_subpackage')) {
                                             echo $this->Form->error('gateway_gold_subpackage');
                                         } else echo 'What Package'; ?>
                                    </td>

                                    <td colspan="2">
                                        <table width="50%">
                                            <tr>
                                                <td>
                                                    <?php
                                                        $options=array('silver' => 'Silver', 'gold' => 'Gold* (Please select:');
                                                        $attributes=array('legend' => false);
                                                        echo $this->Form->radio('gateway_package',$options,$attributes);
                                                    ?>
                                                <td>
                                            </tr>
                                            <tr>
                                                <td style="text-indent:25px;">
                                                    <?php
                                                        $options=array('cust_db' => 'Customer Database or', 'fraud' => 'Fraud Package)');
                                                        $attributes=array('legend' => false);
                                                        echo $this->Form->radio('gateway_gold_subpackage',$options,$attributes);
                                                    ?>
                                                <td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php
                                                        $options=array('platinum' => 'Platinum*');
                                                        $attributes=array('legend' => false, 'hiddenField' => false);
                                                        echo $this->Form->radio('gateway_package',$options,$attributes);
                                                    ?>
                                                <td>
                                            </tr>
                                        </table>
                                    </td>
                                    
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="text-align: center;">*Additional fees may apply for Gold and Platinum packages</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
                            if(Hash::get($data, 'Coversheet.status') == 'saved') {
                            if (isset($gateway) && $gateway != 1) {echo '</div>';}
                            }
                            if(Hash::get($data, 'Coversheet.status') == 'saved' && empty(Hash::get($data, 'Coversheet.moto_online_chd'))) {
                              if (isset($moto) && $moto != true) {
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
                                 if((Hash::get($data, 'Coversheet.status') != 'saved' && !empty(Hash::get($data, 'Coversheet.moto_online_chd'))) || Hash::get($data, 'Coversheet.status') == 'saved') {
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
                                                if (Hash::get($data, 'CobrandedApplication.DoYouAcceptAE-Exist') == 'true' ||
                                                    Hash::get($data, 'CobrandedApplication.DoYouWantToAcceptAE-New') == 'true') {
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
                                            if (empty($data['CobrandedApplication']['AmexNum']) && Hash::get($data, 'CobrandedApplication.DoYouWantToAcceptAE-New') == 'true') {
                                                echo 'Request New Amex';
                                            }
                                            else {
                                                echo 'Amex#';
                                                if (!empty($data['CobrandedApplication']['AmexNum'])) {
                                                    echo '<div class="label label-default">';
                                                }
                                                else {
                                                    echo '<div>';
                                                }
                                                echo Hash::get($data, 'CobrandedApplication.AmexNum', '').'</div>';
                                            }
                                        ?>
                                     </td>
                                 </tr>
                                 <tr>
                                    <td>Does the merchant accept Discover?</td>
                                    <td>
                                        <div class="label label-default">
                                            <?php echo Hash::get($data, 'CobrandedApplication.DoYouWantToAcceptDisc-New') == 'true' ? 'Yes' : 'No'; ?> 
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo Hash::get($data, 'CobrandedApplication.DoYouWantToAcceptDisc-New') == 'true' ? 'Axia Request New' : ''; ?>
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
                            if(Hash::get($data, 'Coversheet.status') == 'saved' && !empty(Hash::get($data, 'Coversheet.moto_online_chd'))) {
                                if (isset($moto) && $moto != true) {echo '</div>';}
                            }?>
                    </fieldset>

                <?php if (!empty($errors)) { ?>
                    <script type="text/javascript">

                        Effect.Appear('Cp');
                        Effect.Appear('HideCpLink');
                        Effect.Fade('ShowCpLink');

                        Effect.Appear('Micros');
                        Effect.Appear('HideMicrosLink');
                        Effect.Fade('ShowMicrosLink');

                        Effect.Appear('Gateway');
                        Effect.Appear('HideGatewayLink');
                        Effect.Fade('ShowGatewayLink');

                        Effect.Appear('Moto');
                        Effect.Appear('HideMotoLink');
                        Effect.Fade('ShowMotoLink');
                    </script>
                <?php } ?>

                <?php 
                    $this->Html->css(array('coversheet'), 'stylesheet', array('media' => 'print'));
                    if (Hash::get($data, 'Coversheet.status') == 'saved') {
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
</div>    
<script>
<?php
    echo "var assocPartners = ". $dbUserAssocParters . ";\n";
?>
jQuery(function() {
    jQuery( "#CoversheetSetupPartner" ).autocomplete({
        source: assocPartners
    });

    jQuery('[id^="CoversheetExpectedInstallDate"').on('change', function() {
        jQuery('#expInsDateErr').remove();
        selMonth = jQuery('#CoversheetExpectedInstallDateMonth').val()*1;
        selDay = jQuery('#CoversheetExpectedInstallDateDay').val()*1;
        selYr = jQuery('#CoversheetExpectedInstallDateYear').val()*1;
        if (selMonth >0 && selDay >0 && selYr >0) {
            maxDaysInMonth = new Date(selYr, selMonth, 0).getDate();
            if (selDay > maxDaysInMonth) {
                selMoName = jQuery('#CoversheetExpectedInstallDateMonth option:selected').text();
                jQuery('#CoversheetExpectedInstallDateYear').parent().prepend('<div class="text-danger bg-danger" id="expInsDateErr"><strong>' + selMoName +' only has '+ maxDaysInMonth + '  days!</strong></div>');
                jQuery('#CoversheetExpectedInstallDateYear').parent().addClass('error');
            } else {
                jQuery('#expInsDateErr').remove();
                jQuery('#CoversheetExpectedInstallDateYear').parent().removeClass('error');
            }
        }
    });

  });
</script>