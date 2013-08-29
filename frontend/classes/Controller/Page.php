<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Frontend Page controller
 *
 * @package   CMS/Common
 * @category  Controller
 * @author    WinterSilence
 */ 
class Controller_Page extends Controller_CMS_Page
{
	/**
	 * Automatically executed before the controller action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return void
	 */
	public function before()
	{
		parent::before();
		
		// Send user agent info in View
		SView::set_global('user_agent', $this->request->user_agent(array('browser', 'robot', 'mobile')));
	}
}
