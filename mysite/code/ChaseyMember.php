<?php

/**
 * Description of ChaseyMember
 *
 */
class ChaseyMember extends DataExtension {

	
	public static $many_many = array(
		'Friends' => 'Member'
	);
	
	public static $belongs_many_many = array(
		'RelatedFriends' => 'Member'
	);
}
