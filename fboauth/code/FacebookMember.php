<?php

/**
 * Facebook member class to wrap the member functionality of the Facebook
 * members into the member object.
 *
 * An extension to the built in {@link Member} class this adds the fields which
 * may be required as part of the member
 *
 * @package facebookconnect
 */

class FacebookMember extends DataExtension {
	
	/**
	 *
	 * @var array
	 */
	public static $db = array(
		'Email'				=> 'Varchar(255)',
		'FacebookUID' 		=> 'Varchar(200)',
		'FacebookLink'		=> 'Varchar(200)',
		'FacebookTimezone'	=> 'Varchar(200)'
	);
	
	/**
	 *
	 * @param FieldList $fields 
	 */
	public function updateCMSFields(FieldList $fields) {
		$fields->makeFieldReadonly('Email');
		$fields->makeFieldReadonly('FacebookUID');
		$fields->makeFieldReadonly('FacebookLink');
		$fields->makeFieldReadonly('FacebookTimezone');
	}
	
	/**
	 * Takes one of 'square' (50x50), 'small' (50xXX) or 'large' (200xXX)
	 *
	 * @return string
	 */
	public function getAvatar($type = "square") {
		$controller = Controller::curr();

		if($controller && ($member = $controller->getCurrentFacebookMember())) {
			return sprintf(
				"http://graph.facebook.com/%s/picture?type=%s",
				 $member->FacebookUID, $type
			);
		}

		return false;
	}
        
	/**
	 * Sync the new data from a users Facebook profile to the member database.
	 *
	 * @param array
	 */
	public function updateFacebookFields($result) {
		// only Update Email if ist already set to a correct Email,
		// while $result['email'] is still a proxied_email
		if(!Email::validEmailAddress($this->owner->Email) || (!stristr($result['email'], '@facebook.com') && !DataObject::get_one('Member', "\"Email\" = '". Convert::raw2sql($result['email']) ."'"))){
			$this->owner->Email 	= (isset($result['email'])) ? $result['email'] : "";
		}
		$this->owner->FirstName	= (isset($result['first_name'])) ? $result['first_name'] : "";
		$this->owner->Surname	= (isset($result['last_name'])) ? $result['last_name'] : "";
		$this->owner->Link		= (isset($result['link'])) ? $result['link'] : "";
		$this->owner->FacebookUID	= (isset($result['id'])) ? $result['id'] : "";
		$this->owner->FacebookTimezone = (isset($result['timezone'])) ? $result['timezone'] : "";
	}
}