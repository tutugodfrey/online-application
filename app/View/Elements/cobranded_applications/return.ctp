<?php 
	if (in_array($this->Session->read('Auth.User.group'), array(USER::ADMIN, USER::REP, USER::MANAGER))) {
		echo $this->Html->link('Return to Applications Admin',
			array(
				'controller' => 'cobranded_applications',
				'action' => 'index',
				'admin' => 'true'
			),
			array(
				'style' => 'display: block; float: right;'
			)
		);
	}
// Last Line
