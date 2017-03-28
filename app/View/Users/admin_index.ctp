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


<div class="users  panel panel-default index">
<div class="panel-heading"><u><strong>Users</strong></u></div>
<table class="table talbe-condensed table-hover">
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
			'action' => 'view',
			$user['User']['group_id']
		)
	) . "\n\t\t</td>\n";
	echo "\t\t<td>\n\t\t\t" . ($user['Template']['name'] == null ? '' : $user['Template']['name']) . "\n\t\t</td>\n";

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
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			  <ul>
				<li>
				<?php echo $this->Element('users/search'); ?>
				</li>
				<li>
					<?php
						echo $this->Html->link('New User',
							array(
								'action' => 'add',
								'admin' => true
							)
						);
					?>
				</li>
				<li>
					<?php
						echo ($queryString == '1' ?
							$this->Html->link('Show Active Users',
								array(
									'action' => 'index',
									'admin' => true
								)
							) :
							$this->Html->link('Show All Users',
								array(
									'action' => '?all=1',
									'admin' => true
								)
							)
						);
					?>
				</li>
				<li>
					<?php
						echo $this->Html->link('Bulk Edit Users',
							array(
								'action' => 'bulk_edit',
								'admin' => true
							)
						);
					?>
				</li>
				<li>
					<?php
						echo $this->Html->link('List Applications',
							array(
								'controller' => 'applications',
								'action' => 'index',
								'admin' => true
							)
						);
					?>
				</li>
				<li>
					<?php
						echo $this->Html->link('List Settings',
							array(
								'controller' => 'settings',
								'action' => 'index',
								'admin' => true
							)
						);
					?>
				</li>
				<li>
					<?php
						echo $this->Html->link('List IP Restrictions',
							array(
								'controller' => 'apips',
								'action' => 'index',
								'admin' => true
							)
						);
					?>
				</li>
				<li>
					<?php
						echo $this->Html->link('List Groups',
							array(
								'controller' => 'groups',
								'action' => 'index',
								'admin' => true
							)
						);
					?>
				</li>
			  </ul>
		</div>	
	</div>	
</div>

