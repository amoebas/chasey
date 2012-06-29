<?php

/**
 * Description of FacebookExtension
 *
 */
class FacebookExtension extends Extension {

	/**
	 *
	 * @var Facebook
	 */
	protected $facebook = null;
	
	/**
	 * 
	 */
	public function onAfterInit() {
		require_once(BASE_PATH.'/fboauth/thirdparty/facebook-php-sdk/src/facebook.php');
		$this->facebook = new Facebook(array(
			'appId'  => FacebookOAuthController::get_app_id(),
			'secret' => FacebookOAuthController::get_api_secret(),
		));
	}
	
	/**
	 *
	 * @return Facebook
	 */
	public function getFacebook() {
		return $this->facebook;
	}
	
	/**
	 *
	 * @return Member
	 */
	public function getCurrentFacebookMember() {
		if($this->getFacebook()->getUser()) {
			return Member::currentUser();
		};
		return null;
	}
	
	/**
	 *
	 * @return string 
	 */
	public function getFacebookLogoutLink() {
		return 'fb/logout';
	}
	
	/**
	 *
	 * @return string 
	 */
	public function getFacebookLoginLink() {
		return 'fb/login';
	}
	
	/**
	 *
	 * @param type $query
	 * @return array 
	 */
	public function api($query) {
		return $this->getFacebook()->api($query);
	}
}
