<?php

use Codeception\Util\Debug;

class UsersLoginPage
{
	// include url of current page
	static $URL = '/users/login';

	/**
	 * Declare UI map for this page here. CSS or XPath allowed.
	 * public static $usernameField = '#username';
	 * public static $formSubmitButton = "#mainForm input[type=submit]";
	 */

	// form fields and labels
	static $userEmailField = 'UserEmail';
	static $userEmailLabel = 'Email';

	static $userPasswordField = 'UserPassword';
	static $userPasswordLabel = 'Password';

	// buttons
	static $loginButton = 'Login';

	/**
	 * Basic route example for your current URL
	 * You can append any additional parameter to URL
	 * and use it in tests like: EditPage::route('/123-post');
	 */
	public static function route($param)
	{
		// TODO: switch to use 
		return static::$URL.$param;
	}

	/**
	 * Similar to route above but intended to be used with
	 * $I->seeCurrentUrlMatches()
	 * You can append any additional parameter to URL
	 * and use it in tests like: EditPage::route('/123-post');
	 */
	public static function likeRoute($begin, $end, $action, $param)
	{
		if (strlen($action) > 0) {
			$action = '/'.$action;
		}
		return $end.static::$URL.$action.$param.$end;
	}

	/**
	 * @var WebGuy;
	 */
	protected $webGuy;

	public function __construct(WebGuy2 $I)
	{
		$this->webGuy = $I;
	}

	/**
	 * @return UsersLoginPage
	 */
	public static function of(WebGuy2 $I)
	{
		return new static($I);
	}

	// utility functions
	public function login($username = 'dev@axiapayments.com', $password = '123456') {
		$email = $this->webGuy->grabFromDatabase('onlineapp_users', 'email', array('email' => $username));
		if ($email != $username) {
			// add a user with $username and $password...
			// TODO: figure out how to hash the password
			// currently this method only works with a password of 123456
			$this->webGuy->haveInDatabase('onlineapp_groups',
				array(
					'name' => 'admin', 
					'created' => '2014-01-10 13:50:53',
					'modified' => '2014-01-10 13:50:53',
				)
			);
			$group_id = $this->webGuy->grabFromDatabase('onlineapp_groups', 'id', array('name' => 'admin'));
			$date = new DateTime();
			$this->webGuy->haveInDatabase('onlineapp_users',
				array(
					'email' => $username,
					'password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
					'group_id' => $group_id,
					'token_uses' => 0,
					'firstname' => 'code',
					'lastname' => 'cept',
					'active' => 't',
					'api_password' => '0e41ea572d9a80c784935f2fc898ac34649079a9',
					'api_enabled' => 't',
					'created' => $date->format('Y-m-d H:i:s'),
					'modified' => $date->format('Y-m-d H:i:s'),
				)
			);
		}

		$this->webGuy->wantTo('Ensure that users can login');
		$this->webGuy->amOnPage(static::$URL);
		$this->webGuy->see(static::$loginButton);
		$this->webGuy->see(static::$userEmailLabel);
		$this->webGuy->see(static::$userPasswordLabel);
		$this->webGuy->fillField(static::$userEmailField, $username);
		$this->webGuy->fillField(static::$userPasswordField, $password);
		$this->webGuy->click(static::$loginButton);
		$this->webGuy->see('Axia Admin Home');
	}

	public function logout() {
		$this->webGuy->click('Logout');
	}
}
