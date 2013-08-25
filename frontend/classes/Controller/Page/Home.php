<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Page_Home extends Controller_Page
{
	/**
	 * Content template
	 * @var mixed(string|View)
	 */
	public $content = 'page/home';
	
	public function action_index()
	{
		//$this->response->body('hello, world!');
	}
} // End Controller_Page_Home