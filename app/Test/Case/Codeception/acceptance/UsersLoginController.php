<?php
class UsersLoginController
{
	protected $usersLogin;

	public function __construct(WebGuy $I) {
		$this->usersLogin = $I;
	}

	public function login($username = 'dev@axiapayments.com', $password = '123456') {
		$this->usersLogin->wantTo('Ensure that users can login');
		$this->usersLogin->amOnPage(UsersLoginPage::$URL);
		$this->usersLogin->see(UsersLoginPage::$loginButton);
		$this->usersLogin->see(UsersLoginPage::$userEmailLabel);
		$this->usersLogin->see(UsersLoginPage::$userPasswordLabel);
		$this->usersLogin->fillField(UsersLoginPage::$userEmailField, $username);
		$this->usersLogin->fillField(UsersLoginPage::$userPasswordField, $password);
		$this->usersLogin->click(UsersLoginPage::$loginButton);
		$this->usersLogin->see('Axia Admin Home');
	}

	public function logout() {
		$this->usersLogin->click('Logout');
	}

}
