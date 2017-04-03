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

<div class="container-fluid">
  <div class="row">
  	<?php
	$elVars['htmlContent'] = array(
		$this->Element('users/search')
	);

	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="users panel panel-default">
		<div class="panel-heading"><u><strong><?php echo __('Users') ?></strong></u></div>
		<table class="table table-condensed table-striped table-hover">
		  <tr>
			<th><?php echo $this->Paginator->sort('active', 'Status'); ?></th>
			<th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
			<th><?php echo $this->Paginator->sort('firstname', 'Firstname'); ?></th>
			<th><?php echo $this->Paginator->sort('lastname', 'Lastname'); ?></th>
			<th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
			<th><?php echo $this->Paginator->sort('extension', 'Ext'); ?></th>
			<th><?php echo $this->Paginator->sort('group_id', 'Group'); ?></th>
			<th><?php echo $this->Paginator->sort('template_id', 'Template'); ?></th>
			<th><?php echo __('Actions');?></th>
		  </tr>
		<?php
		$i = 0;
		foreach ($users as $user):
			// Skip items for reps when it doesn't belong to them
			echo "\t\t<td>\n\t\t\t" . ($user['User']['active'] === true ?
				$this->Html->image('green_orb.gif',
					array('alt' => 'Active')
				) :
				$this->Html->image('red_orb.png',
					array(
						'alt' => 'Inactive')
					)
				) . " \n\t\t</td>\n";
			echo "\t\t<td>\n\t\t\t" . $user['User']['id'] . " \n\t\t</td>\n";
			echo "\t\t<td>\n\t\t\t" . $user['User']['firstname'] . " \n\t\t</td>\n";
			echo "\t\t<td>\n\t\t\t" . $user['User']['lastname'] . " \n\t\t</td>\n";
			echo "\t\t<td>\n\t\t\t" . $user['User']['email'] . " \n\t\t</td>\n";
			echo "\t\t<td>\n\t\t\t" . $user['User']['extension'] . " \n\t\t</td>\n";
			echo "\t\t<td>\n\t\t\t" . $this->Html->link($user['Group']['name'],
				array(
					'controller' => 'groups',
					'action' => 'index',
				)
			) . "\n\t\t</td>\n";
			echo "\t\t<td>\n\t\t\t" . (empty($user['Template']['name']) ? '' : $user['Template']['name']) . "\n\t\t</td>\n";

			echo "\t\t<td class=\"actions\">\n";
			echo $this->BoostCakeHtml->link(' ',
				array(
					'action' => 'edit',
					$user['User']['id']
				),
				array(
					'role' => 'button',
					'class' => 'glyphicon glyphicon-edit',
					'title' => __('Edit User')
				)
			) . ' ';
			echo "\t\t\t" . $this->Html->link(__('Create Token'),
				array(
					'action' => 'token',
					'admin' => true,
					$user['User']['id']
				)
			) . "\n";
			echo "\t\t\t" . $this->BoostCakeForm->postLink(__(' '),
				array(
					'action' => 'delete',
					$user['User']['id']
				),
				array(
					'role' => 'button',
					'class' => 'glyphicon glyphicon-trash',
					'title' => __('Delete User')
				),
				__('Are you sure you want to delete') . ' #' . $user['User']['id']
			) . "\n";
			echo "\t\t</td>\n";
			echo "\t</tr>\n";

		endforeach;
		echo "\n";
		?>
		</table>

		<?php echo $this->Element('paginatorBottomNav'); ?>

		</div>
	</div>
  </div>
</div>
