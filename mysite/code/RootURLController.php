<?php

class RootURLController extends Controller {

	public function index() {
		$this->Title = 'Chasey';
		$this->Content = 'A facebook connected game of tag.';
		return $this->renderWith(array('Page'));
	}
	
	/**
	 * @return array
	 */
	public function getFriends() {
		return Member::currentUser()->Friends();
	}
}