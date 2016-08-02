<?php
namespace Spring;

use Spring\SpUser;
use Spring\SpSession;

class SpAuth
{
	private $_siteKey;
	
	public function __construct() 
	{
		$this->_siteKey = 'XVyaPIYs8QFptjkTlV0Z0PyTIy64NAZB8TcyIOaJ8BbQHuMXfz';
	}
	
	private function randomString($length = 50)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string = '';
		
		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters)-1)];
		}
		
		return $string;
	}
	
	protected function hashData($data)
	{
		return hash_hmac('sha512', $data, $this->_siteKey);
	}
	
	// Check if the user object has the is_admin property set to TRUE
	public function isAdmin(SpUser $user)
	{
		return $user->is_admin == TRUE ? TRUE : FALSE;
	}
	
	public function createUser(SpUser $user)
	{
		// generate user salt
		$user_salt = $this->randomString();
		
		// salt and hash the password
		$password = $user_salt.$user->password;
		$password = $this->hashData($password);
		
		// Create verification code
		$code = $this->randomString();
		
		// @TODO Commit values to database
		// return commit result
		$user->password = $password;
		$user->user_salt = $user_salt;
		$user->is_verified = 0;
		$user->is_active = 1;
		$user->is_admin = 0;
		$user->verification_code = $code;
		return $user->save();
		
		// @TODO If you use the verification_code then call sendVerification() 
		// to email a users verification code to them
	}
	
	/**
	 * Tries to login and set session data
	 * @param string $email
	 * @param string $password
	 * @return
	 * 0 = mail not found
	 * 1 = User and password match
	 * 2 = User is not active
	 * 3 = User is not verified
	 * 4 = Wrong password
	 */
	public function login($email, $password)
	{
		// Select user row from database
		$user = new SpUser();
		$user->findByEmail($email);
		
		// Simple validations
		if ($user->id == null) return 0;
		if ($user->is_active == false) return 2;
		if ($user->is_verified == false) return 3;
		
		// Salt and hash password for validations
		// Prepend unique user salt to provided password
		$password = $user->user_salt . $password;
		$password = $this->hashData($password);
		
		// Compare with db salted and hashed password
		if (strcmp($password, $user->password) !== 0) return 4;
		
		// All verifications are OK, set session data
		$random = $this->randomString();
		$token = $_SERVER['HTTP_USER_AGENT'] . $random;
		$token = $this->hashData($token);
		
		// Set sessions vars
		$_SESSION['token'] = $token;
		$_SESSION['user_id'] = $user->id;
		$_SESSION['user_name'] = $user->name;
		
		// @TODO Delete login data for current user
		
		// @TODO Insert login data for current user
		return 1;
	}
	
	/**
	 * Check session information on DB
	 */
	public function checkSession() {
		// Retrieve session data from DB
		$session = new SpSession();
		
		if ($selection) {
			// Check session ID and token
			if (session_id() == $session->session_id && $_SESSION['token'] == $session->token) {
				// Session ID and token match, refresh session for next request
				$this->refreshSession();
				return true;
			}
		}
		return false;
	}
	
	private function refreshSession()
	{
		// Regenerate ID
		$random = $this->randomString();
		
		// Build the token
		$token = $_SERVER['HTTP_USER_AGENT'] . $random;
		$token = $this->hashData($token);
		
		// Store in session
		$_SESSION['token'] = $token;
	}
	
	public function logout()
	{
		session_destroy();
		
		// @TODO Delete from DB
	}
	
	public function sendVerificationCode($email)
	{
		//@TODO Get verification code from email
		$user = new SpUser();
		$user->findByEmail();
		
		// Prepare email
		$subject = 'Your verification code';
		$header = 'Sent by Team Hub';
		$message = 'Your verification code is ' . $user->verification_code;
		
		// Send email
		@mail($email, $subject, $message, $header);
	}
}