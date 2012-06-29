<?php

class RootURLController extends Controller {

	public function index() {
		$this->Title = 'Chasey';
		$this->Content = 'A facebook connected game of tag.';
		return $this->renderWith(array('Page'));
	}
}