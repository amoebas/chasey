<?php


class FacebookOAuthController extends Controller {

	/**
	 *
	 * @var string
	 */
	protected static $id = '';
	
	/**
	 *
	 * @var string
	 */
	protected static $secret = '';
	
	/**
	 *
	 * @param string $id 
	 */
	public static function set_app_id($id) {
		self::$id = $id;
	}
	
	/**
	 *
	 * @return string
	 */
	public static function get_app_id() {
		return self::$id;
	}
	
	/**
	 *
	 * @param string $secret 
	 */
	public static function set_api_secret($secret) {
		self::$secret = $secret;
	}
	
	/**
	 *
	 * @return string
	 */
	public static function get_api_secret() {
		return self::$secret;
	}

	/**
	 *
	 * @param SS_HTTPRequest $request 
	 */
	public function login(SS_HTTPRequest $request) {
		$facebook = $this->getFacebook();
		$url = $facebook->getLoginUrl(array('redirect_uri' => $this->authLink()));
		$this->redirect($url);
	}
	
	/**
	 *
	 * @param SS_HTTPRequest $request
	 * @return boolean 
	 */
	public function auth(SS_HTTPRequest $request) {
		
		$facebook = $this->getFacebook();
		$user = $facebook->getUser();
		
		$result = $facebook->api('me');

		
		
		// if email is empty and proxied_email is set instead
		// write down proxied_email to email
		if(!isset($result['proxied_email']) && isset($result['email']) && !stristr($result['email'], '@')){
			$result['email'] = $result['proxied_email'];
		}
		
		if($this->loginWithFBID($result)) {
			$this->redirect(Director::absoluteBaseURLWithAuth());
			return true;
		}
		
		if($this->loginWithEmail($result)) {
			$this->redirect(Director::absoluteBaseURLWithAuth());
			return true ;
		}
		
		$this->createAndLogin($result);
		$this->redirect(Director::absoluteBaseURLWithAuth());
		return true;
	}
	
	/**
	 *
	 * @param SS_HTTPRequest $request 
	 */
	public function logout(SS_HTTPRequest $request) {
		$facebook = $this->getFacebook();
		$url = $facebook->getLoginUrl(array('redirect_uri' => $this->logoutRedirectLink()));
		$this->redirect($url);
	}
	
	/**
	 *
	 * @param SS_HTTPRequest $request 
	 */
	public function destroy(SS_HTTPRequest $request) {
		$facebook = $this->getFacebook();
		$facebook->destroySession();
		Member::currentUser()->logOut();
		$this->redirect(Director::absoluteBaseURLWithAuth());
		return;
	}
	
	/**
	 *
	 * @return string
	 */
	protected function authLink() {
		return $this->join_links(Director::absoluteBaseURLWithAuth(),'fb/auth');
	}
	
	/**
	 *
	 * @return string
	 */
	protected function logoutRedirectLink() {
		return $this->join_links(Director::absoluteBaseURLWithAuth(),'fb/destroy');
	}
	
	/**
	 *
	 * @param array $result
	 * @return boolean 
	 */
	protected function loginWithFBID($result) {
		$member = DataObject::get_one('Member', "\"FacebookUID\" = '".Convert::raw2sql($result['id'])."'");
		if($member) {
			$this->updateFacebookFields($member, $result);
			$this->addFriends($member);
			$member->logIn();
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * @param array $result
	 * @return boolean 
	 */
	protected function loginWithEmail($result) {
		if(!isset($result['email'])) {
			return false;
		}
		$member = DataObject::get_one('Member', "\"Email\" = '". Convert::raw2sql($result['email']) ."'");
		
		if($member) {
			$this->updateFacebookFields($member, $result);
			$this->addFriends($member);
			$member->logIn();
		}
		return false;
	}
	
	 /**
	 * create a new User based on the Facebook Member.
	 *
	 * @param array $result
	 */
	function createAndLogin($result){
		$member = new Member();
		$member->write();
		$this->updateFacebookFields($member, $result);
		$member->login();
	}
	
	/**
	 *
	 * @param type $member
	 * @param type $result 
	 */
	protected function updateFacebookFields($member, $result) {
		$member->updateFacebookFields($result, $member);
		$member->write();
	}
	
	/**
	 *
	 * @param Member $member 
	 */
	protected function addFriends($member) {
		$friends = $this->getFacebook()->api('me/friends');
		
		$ids = array();
		foreach($friends['data'] as $friend) {
			$ids[] = $friend['id'];
		}
		
		$friends = DataList::create('Member')->filter(array('FacebookUID' => $ids));
		
		foreach($friends as $friend) {
			$member->Friends()->add($friend);
		}
	}
}