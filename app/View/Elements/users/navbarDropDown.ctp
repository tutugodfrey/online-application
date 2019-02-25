 <?php
 $logoutLink = $this->Html->link(__('Logout') .
 	$this->Html->tag('span', null, array('class' => 'glyphicon glyphicon-log-out pull-right')) .
 	$this->Html->tag('/span'),
		array(
			'controller' => 'users',
			'action' => 'logout',
			'admin' => false,
		),
		array('escape' => false)
	);
	$resetPwLink = $this->element('users/resetPwPostLink', array('id' => $this->Session->read('Auth.User.id')));
	$apiManageLink = null;
	if ($this->Session->read('Auth.User.api_enabled')) {
		$apiManageLink = '<li>';
		$apiManageLink .= $this->element('users/apiCredentials', ['userId' => $this->Session->read('Auth.User.id')]);
		$apiManageLink .= '</li>';
	}
	echo '<span class="btn btn-default dropdown">
	    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<span class="glyphicon glyphicon-user"></span><span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
	        <li>' . $logoutLink . '</li>
			<li>' . $resetPwLink . '</li>
			' . $apiManageLink . '
	    </ul>
	</span>';