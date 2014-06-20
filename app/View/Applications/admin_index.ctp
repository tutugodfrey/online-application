<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.views
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<script type="text/javascript">
	/*$(document).ready(function() {
                doTimer();
                
		public $dialog = $('<div></div>')
			.html('This dialog will show every time!')
                        .css("borderColor","red")
			.dialog({
				autoOpen: false,
				title: 'Basic Dialog'
			});

		$('#opener').click(function() {
			$dialog.dialog('open');
                        $(".ui-dialog").css( 'backgroundColor', 'red' )
                        var d = $(".ui-dialog").position();
                         window.scrollTo( d.left , d.top);
			// prevent the default action, e.g., following a link
			return false;
		});

	});*/
	</script>
<!--button id="opener">JQuery dialog</button-->


<div class="applications index">
<h2>Applications</h2>
<table cellpadding="0" cellspacing="0">
<tr>
<?php foreach ($scaffoldFields as $_field):?>
    <?php if (in_array($_field, array('id', 'user_id', 'legal_business_name', 'dba_business_name', 'corp_contact_name', 'business_type', 'status', 'modified'))): ?>
	<th><?php echo $this->Paginator->sort($_field); ?></th>
	<?php endif; ?>
<?php endforeach;?>
	<th><?php echo __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($applications as $application):
	// Skip items for reps when it doesn't belong to them
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
echo "\n";
	echo "\t<tr{$class}>\n";
		foreach ($scaffoldFields as $_field) {
			$isKey = false;
			if (!empty($application['User'])) {
				foreach ($application['User'] as $_alias => $_details) {
					if ($_field === 'user_id') {
						$isKey = true;
						echo "\t\t<td>\n\t\t\t" . $this->Html->link($application['User']['email'], array('controller' => 'users', 'action' => 'view', $application['User']['id'])) . "\n\t\t</td>\n";                                                
						break;
					}
				}
			}
			if ($isKey !== true && in_array($_field, array('id', 'user_id', 'legal_business_name', 'dba_business_name', 'corp_contact_name', 'business_type','status', 'modified'))) {
                                if ($_field == 'status' && ($application['Application']['status'] == 'completed' || $application['Application']['status'] == 'signed')) {
                                    echo "\t\t<td>\n\t\t\t" . $this->Html->link($application['Application']['status'], array('controller' => 'applications', 'action' => 'app_status', $application['Application']['id'], 'admin' => false)) . "\n\t\t</td>\n";
                                } else {
				echo "\t\t<td>\n\t\t\t" . $application['Application'][$_field] . " \n\t\t</td>\n";
                                }
			} 
		}

		echo "\t\t<td class=\"actions\">\n";
		echo "\t\t\t" . "<a href=\"javascript:null(0)\" onClick=\"showAndHideCurrentMenu('actionsMenuSlider" . $application['Application']['id'] . "')\">Show Actions</a> ";
                echo "\t\t </td>\n";
                echo "\t\t\t</tr >\n";
                echo "\t<tr>\n";
                echo "<td class=\"actions\" style=\"vertical-align: baseline; text-align: left;\" colspan=\"9\">\n";
                
                echo  "\t\t" . "<div id=\"actionsMenuSlider". $application['Application']['id'] . "\" style=\"DISPLAY: none; vertical-align: baseline; OVERFLOW: hidden; HEIGHT: 18px; padding: 2px 0;\">" . $this->Html->link(__('Copy'), array('action' => 'copy_document', $application['Application']['id'], $application['Application']['hash'])) . "\n";
		//Display the export button only for admins                
                if (in_array($this->Session->read('Auth.User.group'), array('admin'))) {
                  echo "\t\t\t" . $this->Html->link(__('Export'), array('action' => 'export_document', $application['Application']['id'], $application['Application']['hash'])) . "\n";
                }   
                if ($application['User']['id'] == User::HOOZA) {
                echo "\t\t\t" . $this->Html->link(__('Edit'), array('controller' => 'applications', 'action' => 'app', 'admin' => false, 1, $application['Application']['id'], $application['Application']['hash'])) . "\n";
                } else {
                        echo "\t\t\t" . $this->Html->link(__('Edit'), array('controller' => 'applications', 'action' => 'add', 'admin' => false, 1, $application['Application']['id'], $application['Application']['hash'])) . "\n";

                }
		echo "\t\t\t" . $this->Form->postLink(__('Delete'), array('action' => 'delete', $application['Application']['id']), null, __('Are you sure you want to delete').' #' . $application['Application']['id']) . "\n";
                if (in_array($this->Session->read('Auth.User.group'), array('admin'))) {
                echo "\t\t\t" . $this->Html->link(__('Override'), array('action' => 'override', $application['Application']['id'])) . "\n";
                }
                echo "\t\t\t" . $this->Html->link(__('Timeline for Emails'), array('controller' => 'applications', 'action' => 'email_timeline', 'admin' => true, $application['Application']['id'])) . "\n";
                if ($application['Application']['status'] != 'signed' && $application['Application']['status'] != 'completed') {
                echo "\t\t\t" . $this->Html->link(__('Email App For Field Completion'), array('controller' => 'applications', 'action' => 'complete_fields', 'admin' => false, $application['Application']['id']), null, __('Send for completion to: ') . $application['Application']['owner1_email']) . "\n";
                }
                if ($application['Application']['status'] == 'completed') {
                    if ($application['User']['id'] == User::HOOZA) {
                    echo "\t\t\t" . $this->Html->link(__('Resend for signing'), array('controller' => 'applications', 'action' => 'hooza_email_app', 'admin' => false, $application['Application']['id']), null, __('Send for signature to:') . $application['Application']['owner1_email'] . ", " .$application['Application']['owner2_email']) .  "\n";    
                    } else {
                echo "\t\t\t" . $this->Html->link(__('Resend for signing'), array('controller' => 'applications', 'action' => 'email_app', 'admin' => false, $application['Application']['id']), null, __('Send for signature to:') . $application['Application']['owner1_email'] . ", " .$application['Application']['owner2_email']) .  "\n";
                    }
                }
                if (in_array($this->Session->read('Auth.User.group'), array('admin')) && $application['Application']['status'] == 'signed') {
                echo "\t\t\t" . $this->Html->link(__('Install Sheet'), array('controller' => 'applications', 'action' => 'install_sheet_var', 'admin' => false, $application['Application']['id'])) . "\n";
                }
                
                if ($application['Coversheet']['id'] == '') {
                echo "\t\t\t" . $this->Html->link(__('Cover Sheet'), array('controller' => 'coversheets', 'action' => 'add', 'admin' => false, $application['Application']['id'],$application['Application']['user_id'])) . "\n";
                } else  {
                    echo "\t\t\t" . $this->Html->link(__('Cover Sheet'), array('controller' => 'coversheets', 'action' => 'edit', 'admin' => false, $application['Coversheet']['id'])) . "\n";
                }
                echo "\t\t </div> </td>";
                echo "\t\t\t</tr >";
        

endforeach;
echo "\n";
?>
</table>
	<p><?php
	echo $this->Paginator->counter(array(
		'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
	));
	?></p>
	<div class="paging">
	<?php echo "\t" . $this->Paginator->prev('<< ' . __('previous'), array(), null, array('class' => 'disabled')) . "\n";?>
	 | <?php echo $this->Paginator->numbers() . "\n"?>
	<?php echo "\t ". $this->Paginator->next(__('next') .' >>', array(), null, array('class' => 'disabled')) . "\n";?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link('New Application', array('action' => 'add', 'admin' => false)); ?></li>
                <li><?php echo $this->Html->link('List Coversheets', array('controller' => 'coversheets', 'action' => 'index', 'admin' => true)); 
                ?></li>
               <? echo $this->Element('applications/search'); ?>
                
                <?php
                        echo $this->Form->end();
                ?>
	</ul>
</div>