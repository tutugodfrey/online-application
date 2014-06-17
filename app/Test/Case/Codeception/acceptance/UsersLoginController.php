<?php
class UsersLoginController
{
	protected $usersLogin;

	public function __construct(WebGuy $I) {
		$this->usersLogin = $I;
	}

	public function login($username = 'dev@axiapayments.com', $password = '123456') {

		$this->usersLogin->haveInDatabase('onlineapp_groups',
			array(
				'name' => 'admin', 
				'created' => '2014-01-10 13:50:53',
				'modified' => '2014-01-10 13:50:53',
			)
		);

		$email = $this->usersLogin->grabFromDatabase('onlineapp_users', 'email', array('email' => 'dev@axiapayments.com'));
		if (is_null($email)) {
			$group_id = $this->usersLogin->grabFromDatabase('onlineapp_groups', 'id', array('name' => 'admin'));
			$this->usersLogin->haveInDatabase('onlineapp_users',
				array(
					'email' => 'dev@axiapayments.com',
					'password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
					'group_id' => $group_id,
					'created' => '2014-01-10 13:50:53',
					'modified' => '2014-01-10 13:50:53',
					'token_uses' => 0,
					'firstname' => 'code',
					'lastname' => 'cept',
					'active' => 't',
					'api_password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
					'api_enabled' => 't'
				)
			);
		}

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
