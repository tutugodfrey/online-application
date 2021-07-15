<?php 
	if (in_array($this->Session->read('Auth.User.group'), array(USER::ADMIN, USER::REP, USER::MANAGER))) {
		echo '<div class="text-center">' . $this->Html->link('<span class="glyphicon glyphicon-backward"></span> Return to Applications List',
			array(
				'controller' => 'cobranded_applications',
				'action' => 'index',
				'admin' => 'true'
			),
			array('escape' => false)
		) . '</div>';
	}
// Last Line
