<?php
/*
	Optional Element variables:
	array $settings postlink settings to add to default settings

	Required Element variables:
	string $id the id of the user to reset the password for
*/
if (isset($id)) {
	$resetPwSettings = ['escape' => false, 'confirm' => __("A random password will be assigned and an email will be sent to this user to update it.\nContinue?")];
	if (isset($settings)) {
		$resetPwSettings = array_merge($resetPwSettings, $settings);
	}
	$resetPwUrl = ['controller' => 'Users', 'action' => 'request_pw_reset', 'admin' => false, true, $id, true];
	echo $this->Form->postLink(__("Reset Password"), $resetPwUrl, $resetPwSettings);
}