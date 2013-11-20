<?php
    echo $this->Html->css('step_2', null, array('inline' => false));
    
    if (is_array($errors)) {
        echo $this->Html->scriptBlock("var errors = " . json_encode($errors) . ";", array('inline' => false));
    }
    
    echo $this->Html->scriptBlock("
        function isInt(n) {
            n = parseInt(n);
            return typeof n == 'number' && n % 1 == 0;
        }
        
        function update_method_totals() {
            total = (isInt($('#ApplicationCardPresentSwiped').val()) ? parseInt($('#ApplicationCardPresentSwiped').val()) : 0);
            total += (isInt($('#ApplicationCardPresentImprint').val()) ? parseInt($('#ApplicationCardPresentImprint').val()) : 0);
            total += (isInt($('#ApplicationCardNotPresentKeyed').val()) ? parseInt($('#ApplicationCardNotPresentKeyed').val()) : 0);
            total += (isInt($('#ApplicationCardNotPresentInternet').val()) ? parseInt($('#ApplicationCardNotPresentInternet').val()) : 0);
            $('#ApplicationMethodTotal').val(total);
            
            check_moto();
        }
        
        function update_products_totals() {
            total = (isInt($('#ApplicationDirectToCustomer').val()) ? parseInt($('#ApplicationDirectToCustomer').val()) : 0);
            total += (isInt($('#ApplicationDirectToBusiness').val()) ? parseInt($('#ApplicationDirectToBusiness').val()) : 0);
            total += (isInt($('#ApplicationDirectToGovt').val()) ? parseInt($('#ApplicationDirectToGovt').val()) : 0);
            $('#ApplicationProductsTotal').val(total);
        }
        
        function check_moto() {
            if ($('input[name=\"data[Application][business_type]\"]:checked').val() in {'MOTO': '', 'internet':''}) $('#moto_and_internet').slideDown();
            else if (isInt($('#ApplicationCardNotPresentKeyed').val()) &&
                     isInt($('#ApplicationCardNotPresentInternet').val()) &&
                     ((parseInt($('#ApplicationCardNotPresentKeyed').val()) +
                       parseInt($('#ApplicationCardNotPresentInternet').val()))
                      >= 30)) $('#moto_and_internet').slideDown();
            else {
                $('#moto_and_internet input').each(function(i, v) {
                    if ($(v).attr('type') == 'checkbox' || $(v).attr('type') == 'radio') { $(v).attr('checked', false) }
                    else if ($(v).attr('type') == 'text') { $(v).val(''); }
                });
                $('#moto_and_internet').slideUp();
            }
        }
    ", array('inline' => false));
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

<h4>Step 2 of 6 | Products & Services Information</h4>

<?php
    if (is_array($errors)) {
        echo '<h4 class="error">Please correct the specified errors (labeled in red)</h4>';
    }
    
    echo $this->Form->create('Application', array('url' => '/applications/add/' . $step . '/' . $id . '/' . $hash, 'novalidate' => true));
    echo $this->Form->hidden('id', array('value' => $id));
?>
<?php
    // GENERAL UNDERWRITING PROFILE
    echo '<h4>General Underwriting Profile: (Must Select One)</h4>';
    echo $this->Html->div(
        'business_type radio',
        '<span>Business Type: *</span> ' .
        $this->Form->radio('business_type', array('retail' => 'Retail', 'restaurant' => 'Restaurant', 'lodging' => 'Lodging', 'MOTO' => 'MOTO', 'internet' => 'Internet', 'grocery' => 'Grocery'), array('legend' => false, 'separator' => ' &nbsp; &nbsp; ', 'onclick' => 'check_moto();'))
    );
?>
<br />

<table>
    <tr><td colspan="3">
        <?php echo $this->Html->div(
            'products_services_sold',
            $this->Form->label('products_services_sold', 'Products/Services Sold *') .
            $this->Form->text('products_services_sold')
        ); ?>
    </td></tr>
    <tr><td colspan="3">
        <?php echo $this->Html->div(
            'return_policy',
            $this->Form->label('return_policy', 'Return Policy *') .
            $this->Form->text('return_policy')
        ); ?>
    </td></tr>
    <tr><td colspan="3">
        <?php echo $this->Html->div(
            'days_until_prod_delivery',
            $this->Form->label('days_until_prod_delivery', 'Days Until Product Delivery *') .
            $this->Form->text('days_until_prod_delivery')
        ); ?>
    </td></tr>
    <tr>
        <td>
            <?php echo $this->Html->div(
                'monthly_volume',
                $this->Form->label('monthly_volume', 'Monthly Volume *') .
                $this->Form->text('monthly_volume')//, array('onblur' => '$(this).val($(this).val().replace(/\D/g, \'\'))'))
            ); ?>
        </td>
        <td>
            <?php echo $this->Html->div(
                'average_ticket',
                $this->Form->label('average_ticket', 'Average Ticket *') .
                $this->Form->text('average_ticket')//, array('onblur' => '$(this).val($(this).val().replace(/\D/g, \'\'))'))
            ); ?>
        </td>
        <td>
            <?php echo $this->Html->div(
                'highest_ticket',
                $this->Form->label('highest_ticket', 'Highest Ticket *') .
                $this->Form->text('highest_ticket')//, array('onblur' => '$(this).val($(this).val().replace(/\D/g, \'\'))'))
            ); ?>
        </td>
    </tr>
    <tr><td colspan="3">
        <?php echo $this->Html->div(
            'current_processor',
            $this->Form->label('current_processor', 'Current Processor *') .
            $this->Form->text('current_processor')
        ); ?>
    </td></tr>
</table>

<p><hr /></p>

<table class="percentages">
    <tr><td>
        <table>
            <tr><th colspan="2">Method of Sales (total must equal 100%)</th></tr>
            <tr class="card_present_swiped">
                <td><?php echo $this->Form->label('card_present_swiped', 'Card Present Swiped *'); ?></td>
                <td><?php echo $this->Form->text('card_present_swiped', array('onblur' => 'update_method_totals();')) . ' %'; ?></td>
            </tr>
            <tr class="card_present_imprint">
                <td><?php echo $this->Form->label('card_present_imprint', 'Card Present Imprint *'); ?></td>
                <td><?php echo $this->Form->text('card_present_imprint', array('onblur' => 'update_method_totals();')) . ' %'; ?></td>
            </tr>
            <tr class="card_not_present_keyed">
                <td><?php echo $this->Form->label('card_not_present_keyed', 'Card Not Present (Keyed) *'); ?></td>
                <td><?php echo $this->Form->text('card_not_present_keyed', array('onblur' => 'update_method_totals();')) . ' %'; ?></td>
            </tr>
            <tr class="card_not_present_internet">
                <td><?php echo $this->Form->label('card_not_present_internet', 'Card Not Present (Internet) *'); ?></td>
                <td><?php echo $this->Form->text('card_not_present_internet', array('onblur' => 'update_method_totals();')) . ' %'; ?></td>
            </tr>
            <tr class="method_total">
                <td align="right"><strong><?php echo $this->Form->label('method_total', 'TOTAL *'); ?></strong></td>
                <td><?php echo $this->Form->text('method_total') . ' %'; ?></td>
            </tr>
        </table>
    <td valign="top">
        <table>
            <tr><th colspan="2">% of Products Sold (total must equal 100%)</th></tr>
            <tr class="direct_to_customer">
                <td><?php echo $this->Form->label('direct_to_customer', 'Direct to Customer *'); ?></td>
                <td><?php echo $this->Form->text('direct_to_customer', array('onblur' => 'update_products_totals();')) . ' %'; ?></td>
            </tr>
            <tr class="direct_to_business">
                <td><?php echo $this->Form->label('direct_to_business', 'Direct to Business *'); ?></td>
                <td><?php echo $this->Form->text('direct_to_business', array('onblur' => 'update_products_totals();')) . ' %'; ?></td>
            </tr>
            <tr class="direct_to_govt">
                <td><?php echo $this->Form->label('direct_to_govt', 'Direct to Government *'); ?></td>
                <td><?php echo $this->Form->text('direct_to_govt', array('onblur' => 'update_products_totals();')) . ' %'; ?></td>
            </tr>
            <tr class="products_total">
                <td align="right"><strong><?php echo $this->Form->label('products_total', 'TOTAL *'); ?></strong></td>
                <td><?php echo $this->Form->text('products_total') . ' %'; ?></td>
            </tr>
        </table>
    </td></tr>
</table>

<p><hr /></p>

<h4>High Volume Months</h4>
<div class="high_volume_months">
<?php
    // HIGH VOLUME MONTHS
    echo $this->Html->div(
        'high_volume_january',
        $this->Form->checkbox('high_volume_january') .
        $this->Form->label('high_volume_january', 'Jan')
    );
    echo $this->Html->div(
        'high_volume_february',
        $this->Form->checkbox('high_volume_february') .
        $this->Form->label('high_volume_february', 'Feb')
    );
    echo $this->Html->div(
        'high_volume_march',
        $this->Form->checkbox('high_volume_march') .
        $this->Form->label('high_volume_march', 'Mar')
    );
    echo $this->Html->div(
        'high_volume_april',
        $this->Form->checkbox('high_volume_april') .
        $this->Form->label('business_type', 'Apr')
    );
    echo $this->Html->div(
        'high_volume_may',
        $this->Form->checkbox('high_volume_may') .
        $this->Form->label('high_volume_may', 'May')
    );
    echo $this->Html->div(
        'high_volume_june',
        $this->Form->checkbox('high_volume_june') .
        $this->Form->label('high_volume_june', 'Jun')
    );
    echo $this->Html->div(
        'high_volume_july',
        $this->Form->checkbox('high_volume_july') .
        $this->Form->label('high_volume_july', 'Jul')
    );
    echo $this->Html->div(
        'high_volume_august',
        $this->Form->checkbox('high_volume_august') .
        $this->Form->label('high_volume_august', 'Aug')
    );
    echo $this->Html->div(
        'high_volume_september',
        $this->Form->checkbox('high_volume_september') .
        $this->Form->label('high_volume_september', 'Sep')
    );
    echo $this->Html->div(
        'high_volume_october',
        $this->Form->checkbox('high_volume_october') .
        $this->Form->label('high_volume_october', 'Oct')
    );
    echo $this->Html->div(
        'high_volume_november',
        $this->Form->checkbox('high_volume_november') .
        $this->Form->label('high_volume_november', 'Nov')
    );
    echo $this->Html->div(
        'high_volume_december',
        $this->Form->checkbox('high_volume_december') .
        $this->Form->label('high_volume_december', 'Dec')
    );
?>
</div>
<div style="clear: left;"></div>

<p><hr /></p>

<div id="moto_and_internet" style="display: none;">
    <h4>MOTO/Internet Questionnaire</h4>
    
    <?php echo $this->Html->div(
        'moto_storefront_location radio2',
        '<span>Does your organization have a store front location?</span> ' .
        $this->Form->radio('moto_storefront_location', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false))
    ); ?>
    <?php echo $this->Html->div(
        'moto_orders_at_location radio2',
        '<span>Are orders received and processed at business location?</span> ' .
        $this->Form->radio('moto_orders_at_location', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false))
    ); ?>
    <?php echo $this->Html->div(
        'moto_inventory_housed',
        $this->Form->label('moto_inventory_housed', 'Where is inventory housed? ') .
        $this->Form->text('moto_inventory_housed')
    ); ?>
        <?php echo $this->Html->div(
        'moto_inventory_owned radio2',
        '<span>Do you own the product/inventory?</span> ' .
        $this->Form->radio('moto_inventory_owned', array('yes' => 'Yes', 'no' => 'No'), array('legend' => false))
    ); ?>
    <div class="moto_checkboxes">
    <?php
        echo '<p>Are any of the following aspects of your business outsourced to other companies? (please select all that apply)</p>';
        echo $this->Html->div(
            'moto_outsourced_customer_service',
            $this->Form->checkbox('moto_outsourced_customer_service') .
            $this->Form->label('moto_outsourced_customer_service', 'Customer Service') .
            $this->Form->text('moto_outsourced_customer_service_field')
        );
        echo $this->Html->div(
            'moto_outsourced_shipment',
            $this->Form->checkbox('moto_outsourced_shipment') .
            $this->Form->label('moto_outsourced_shipment', 'Product Shipment').
            $this->Form->text('moto_outsourced_shipment_field')
        );
        echo $this->Html->div(
            'moto_outsourced_returns',
            $this->Form->checkbox('moto_outsourced_returns') .
            $this->Form->label('moto_outsourced_returns', 'Handling of Returns') .
            $this->Form->text('moto_outsourced_returns_field')    
        );/*
        echo $this->Html->div(
            'moto_outsourced_billing',
            $this->Form->checkbox('moto_outsourced_billing') .
            $this->Form->label('moto_outsourced_billing', 'Cardholder Billing')
        );*/
    ?>
    </div>
    <div style="clear: left;">&nbsp;</div>
    <?php echo $this->Html->div(
        'moto_sales_methods',
        $this->Form->label('moto_sales_methods', 'By what methods do sales take place? (i.e. internet, trade shows, etc.) ') .
        $this->Form->text('moto_sales_methods')
    ); ?>
</p>
    <table>
        <tr><td>Are sales done:</td><td>
  
    <?php
        echo $this->Html->div(
            'moto_sales_local',
            $this->Form->checkbox('moto_sales_local') .
            $this->Form->label('moto_sales_local', 'Locally')
        );
        ?>
                </td><td>
                <?php
        echo $this->Html->div(
            'moto_sales_national',
            $this->Form->checkbox('moto_sales_national') .
            $this->Form->label('moto_sales_national', 'Nationally')
        );
        ?>
        </td></tr>
    </table>
    <div class="moto_checkboxes">
    <?php
        echo '<p>If product/service delivery requires recurring billing, please explain available billing options:</p>';
        echo $this->Html->div(
            'moto_billing_monthly',
            $this->Form->checkbox('moto_billing_monthly') .
            $this->Form->label('moto_billing_monthly', 'Monthly')
        );
        echo $this->Html->div(
            'moto_billing_quarterly',
            $this->Form->checkbox('moto_billing_quarterly') .
            $this->Form->label('moto_billing_quarterly', 'Quarterly')
        );
        echo $this->Html->div(
            'moto_billing_semiannually',
            $this->Form->checkbox('moto_billing_semiannually') .
            $this->Form->label('moto_billing_semiannually', 'Semi-Annually')
        );
        echo $this->Html->div(
            'moto_billing_annually',
            $this->Form->checkbox('moto_billing_annually') .
            $this->Form->label('moto_billing_annually', 'Annually')
        );
    ?>
    </div>
    <div style="clear: left;">&nbsp;</div>
    <p>Please explain your billing/delivery policy and to what percentage each applies.  <?php echo $this->Html->div('billing_delivery_policy', $this->Form->hidden('billing_delivery_policy'). $this->Form->label('billing_delivery_policy', 'Total must equal 100%:')); ?> </p>
    <table class="moto_policy">
        <tr><td><?php echo $this->Html->div('moto_policy_full_up_front', $this->Form->text('moto_policy_full_up_front'). $this->Form->label('moto_policy_full_up_front', '% FULL PAYMENT UP FRONT WITH ')) . "</td><td colspan = \"2\">" . $this->Html->div('moto_policy_days_until_delivery', $this->Form->text('moto_policy_days_until_delivery') . $this->Form->label('moto_policy_days_until_delivery', ' DAYS UNTIL PRODUCT/SERVICE DELIVERY')); ?> </td><td></td></tr>
        <tr><td><?php echo $this->Html->div('moto_policy_partial_up_front', $this->Form->text('moto_policy_partial_up_front') . $this->Form->label('moto_policy_partial_up_front', '% PARTIAL PAYMENT REQUIRED UP FRONT WITH ')) ."</td><td>" . $this->Html->div('moto_policy_partial_with', $this->Form->text('moto_policy_partial_with') . $this->Form->label('moto_policy_partial_with', '% AND WITHIN ')) ."</td><td>" . $this->Html->div('moto_policy_days_until_final', $this->Form->text('moto_policy_days_until_final') . $this->Form->label('moto_policy_days_until_final', ' DAYS UNTIL FINAL DELIVERY')); ?> </td></tr>
        <tr><td colspan ="3"><?php echo $this->Html->div('moto_policy_after', $this->Form->text('moto_policy_after') . $this->Form->label('moto_policy_after', '% PAYMENT RECEIVED AFTER PRODUCT/SERVICE IS PROVIDED')); ?></td></tr>
    </table>
    
    <p><hr /></p>
</div>

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
                        if (field == 'business_type') {
                            $('.' + field + ' span').filter(':first').css('color', '#f00');
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
            
            check_moto();
        });
    ", array('inline' => false));
?>
