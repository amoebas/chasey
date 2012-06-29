<?php

/**
 * Description of ChaseyMember
 *
 */
class ChaseyMember extends DataExtension {

	public static $db = array(
		'Tagged' => 'Boolean',
		'TaggedSince' => 'SS_DateTime',
		'TotalTagTime' => 'int',
		'TagCount' => 'int'
	);
	
	public static $many_many = array(
		'Friends' => 'Member'
	);
	
	public static $belongs_many_many = array(
		'RelatedFriends' => 'Member'
	);
	
	/**
	 *
	 * @var Member
	 */
	protected static $currently_tagged = null;
	
	/**
	 *
	 * @return DataList
	 */
	public static function get_tagged_player() {
		if(self::$currently_tagged !== null) {
			return self::$currently_tagged;
		}
		$tagged =  Member::get()->where('Tagged = 1');
		
		if($tagged->count()) {
			self::$currently_tagged = $tagged;
		} else {
			self::$currently_tagged = false;
		}
		return self::$currently_tagged;
	}
	
	
	public function canBeTagged() {
		if(!Member::currentUserID()) {
			return false;
		}
		
		if(!$this->isAnyOneTagged()) {
			return true;
		}
		
		if(self::get_tagged_player()->count()) {
			foreach(self::get_tagged_player() as $player) {
				if($player->ID == $this->owner->ID) {
					return false;
				}
			}
		}
		
		if(Member::currentUserID()) {
			if($this->owner->ID == Member::currentUserID()) {
				return false;
			}
		}
		
		if($this->owner->Tagged) {
			return false;
		}
		
		return true;
	}
	
	public function tag() {
		$this->owner->Tagged = 1;
		$this->owner->TagCount = $this->owner->TagCount+1; 
		$this->owner->TaggedSince = SS_Datetime::now();
		$this->owner->write();
	}
	
	public function untag() {
		$this->owner->Tagged = 0;
		$this->owner->TotalTagTime = time() - $this->owner->obj('TaggedSince')->format('u');
		$this->owner->TaggedSince = null;
		$this->owner->write();
	}
	
	protected function isAnyOneTagged() {
		if(self::get_tagged_player()) {
			return true;
		}
		return false;
	}
	
	
	
	
}
