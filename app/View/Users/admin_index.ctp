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


<div class="users index">
<h2>Users</h2>
<table cellpadding="0" cellspacing="0">
  <tr>
    <th><?php echo $this->Paginator->sort('active', 'Status'); ?></th>
    <th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
    <th><?php echo $this->Paginator->sort('firstname', 'Firstname'); ?></th>
    <th><?php echo $this->Paginator->sort('lastname', 'Lastname'); ?></th>
    <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
    <th><?php echo $this->Paginator->sort('extension', 'Ext'); ?></th>
    <th><?php echo $this->Paginator->sort('group_id', 'Group'); ?></th>
    <th><?php echo $this->Paginator->sort('cobrand_id', 'Cobrand'); ?></th>
    <th><?php echo $this->Paginator->sort('template_id', 'Template'); ?></th>
    <th><?php echo __('Actions');?></th>
  </tr>
<?php
$i = 0;
foreach ($users as $user):
  // Skip items for reps when it doesn't belong to them
  $class = null;
  if ($i++ % 2 == 0) {
    $class = ' class="altrow"';
  }
echo "\n";
  echo "\t<tr{$class}>\n";
    foreach ($scaffoldFields as $_field) {
      $isKey = false;
      if (!empty($user['Group'])) {
        foreach ($user['Group'] as $_alias => $_details) {
          if ($_field === 'group_id') {
            $isKey = true;
            echo "\t\t<td>\n\t\t\t" . ($user['User']['active'] === true ? $this->Html->image('green_orb.gif',array('alt' => 'Active')) : $this->Html->image('red_orb.png',array('alt' => 'Inactive'))) . " \n\t\t</td>\n";
            echo "\t\t<td>\n\t\t\t" . $user['User']['id'] . " \n\t\t</td>\n";
            echo "\t\t<td>\n\t\t\t" . $user['User']['firstname'] . " \n\t\t</td>\n";
            echo "\t\t<td>\n\t\t\t" . $user['User']['lastname'] . " \n\t\t</td>\n";
            echo "\t\t<td>\n\t\t\t" . $user['User']['email'] . " \n\t\t</td>\n";
            echo "\t\t<td>\n\t\t\t" . $user['User']['extension'] . " \n\t\t</td>\n";
            echo "\t\t<td>\n\t\t\t" . $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id'])) . "\n\t\t</td>\n";
            echo "\t\t<td>\n\t\t\t" . $user['Cobrand']['partner_name'] . "\n\t\t</td>\n";
            echo "\t\t<td>\n\t\t\t" . ($user['Template']['name'] == null ? '' : $user['Template']['name']). "\n\t\t</td>\n";
            break;
          }
        }
      }
    }
      
    echo "\t\t<td class=\"actions\">\n";
      echo "\t\t\t" . $this->Html->link(__('View'), array('action' => 'view', 'admin' => true, $user['User']['id'])) . "\n";
    echo "\t\t\t" . $this->Html->link(__('Edit'), array('action' => 'edit', 'admin' => true, $user['User']['id'])) . "\n";
      echo "\t\t\t" . $this->Html->link(__('Create Token'), array('action' => 'token', 'admin' => true, $user['User']['id'])) . "\n";
    echo "\t\t\t" . $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete').' #' . $user['User']['id']) . "\n";
    echo "\t\t</td>\n";
  echo "\t</tr>\n";

endforeach;
echo "\n";
?>
</table>

<?php echo $this->Element('paginatorBottomNav'); ?>

</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link('New User', array('action' => 'add', 'admin' => true)); ?></li>
    <li><?php echo ($this->request->action == 'admin_index' ? $this->Html->link('Show All Users', array('action' => 'all', 'admin' => true)) : 
                    $this->Html->link('Show Active Users', array('action' => 'index', 'admin' => true))); ?></li>
    <li><?php echo $this->Html->link('Bulk Edit Users', array('action' => 'bulk_edit', 'admin' => true)); ?></li>                
    <li><?php echo $this->Html->link('List Applications', array('controller' => 'applications', 'action' => 'index', 'admin' => true)); ?></li>
    <li><?php echo $this->Html->link('List Settings', array('controller' => 'settings', 'action' => 'index', 'admin' => true)); ?></li>
    <li><?php echo $this->Html->link('List IP Restrictions', array('controller' => 'apips', 'action' => 'index', 'admin' => true)); ?></li>
    <li><?php echo $this->Html->link('List Groups', array('controller' => 'groups', 'action' => 'index', 'admin' => true)); ?></li>
    <? echo $this->Element('users/search'); ?>
  </ul>
</div>

