<?php

class RootURLController extends Controller {

	public function index() {
		$this->Title = 'Chasey';
		return $this->renderWith(array('Page'));
	}
	
	public function Tag(SS_HTTPRequest $request) {
		$currentlyTagged = ChaseyMember::get_tagged_player();
		if($currentlyTagged) {
			foreach($currentlyTagged as $tagged) {
				$tagged->untag();
			}
		}
		
		$id = Convert::raw2sql($request->Param('ID'));
		$member = Member::get()->byId($id);
		if($member) {
			$member->tag();
		}
		$this->redirectBack();
	}
	
	/**
	 * @return array
	 */
	public function getFriends() {
		if(!Member::currentUser()) {
			return null;
		}
		return Member::currentUser()->Friends();
	}
	
	public function getPlayers() {
		$allFBMembers = Member::get()->where('FacebookUID IS NOT NULL');
		
		#if(Member::currentUserID()) {
		#	$allFBMembers->exclude('ID', Member::currentUserID());
		#}
		return $allFBMembers;
		
	}
	
	public function IsTagged() {
		if(!Member::currentUser()) {
			return null;
		}
		return Member::currentUser()->Tagged;
	}
}