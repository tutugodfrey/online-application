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
<div class="<?php echo $pluralVar;?> panel panel-default index">
<div class="panel-heading"><u><strong><?php echo "$pluralHumanName:";?></strong></u></div>
<table class="table talbe-condensed table-hover">
<tr>
<?php foreach ($scaffoldFields as $_field):?>
	<th><?php echo $this->Paginator->sort($_field);?></th>
<?php endforeach;?>
	<th><?php echo __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach (${$pluralVar} as ${$singularVar}):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
echo "\n";
	echo "\t<tr{$class}>\n";
		foreach ($scaffoldFields as $_field) {
			$isKey = false;
			if (!empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $_alias => $_details) {
					if ($_field === $_details['foreignKey']) {
						$isKey = true;
						echo "\t\t<td>\n\t\t\t" . $this->Html->link(${$singularVar}[$_alias][$_details['displayField']], array('controller' => $_details['controller'], 'action' => 'view', ${$singularVar}[$_alias][$_details['primaryKey']])) . "\n\t\t</td>\n";
						break;
					}
				}
			}
			if ($isKey !== true) {
				echo "\t\t<td>\n\t\t\t" . ${$singularVar}[$modelClass][$_field] . " \n\t\t</td>\n";
			}
		}

		echo "\t\t<td class=\"actions\">\n";
		echo "\t\t\t" . $this->Html->link(__('Edit'), array('action' => 'edit', ${$singularVar}[$modelClass][$primaryKey])) . "\n";
		echo "\t\t</td>\n";
	echo "\t</tr>\n";

endforeach;
echo "\n";
?>
</table>
	<div class="paging">
		<?php 
		echo $this->Element('paginatorBottomNav');
		?>
	</div>
</div>
<?php if (!empty($associations)) :?>
<div class="actions">
	<div class="panel panel-info">
		<div class="panel-heading"><strong><?php echo __('Actions'); ?></strong></div>
		 <div class="panel-body">
			<ul>
		<?php
				$done = array();
				foreach ($associations as $_type => $_data) {
					foreach ($_data as $_alias => $_details) {
						if ($_details['controller'] != $this->name && !in_array($_details['controller'], $done)) {
							echo "\t\t<li>" . $this->Html->link(__('List %s', Inflector::humanize($_details['controller'])), array('controller' => $_details['controller'], 'action' => 'index')) . "</li>\n";
							$done[] = $_details['controller'];
						}
					}
				}
		?>
			</ul>
		</div>
	</div>
</div>
<?php endif; ?>