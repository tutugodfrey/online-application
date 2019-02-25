<?php
//API user credentials menu
echo $this->Html->link('API user credentials', '#', [
		'data-toggle' => 'modal',
		'data-target' => '#dynamicModal',
		'onClick' => "renderContentAJAX('', '', '', 'dynamicModalBody', '/Users/reset_api_info/" . $userId . "')",
	]);
