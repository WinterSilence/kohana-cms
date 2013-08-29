<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Frontend home page
 *
 * @package   CMS/Frontend
 * @category  Controller
 * @author    WinterSilence
 */ 
class Controller_Page_Home extends Controller_Page
{
	/**
	 * Content template
	 * @var mixed(string|View)
	 */
	public $content = 'page/home';
	
	public function action_index()
	{
		//var_export(Route::get('widget')->matches(Request::factory('widget/blog/tags')));
	}
} // End Controller_Page_Home