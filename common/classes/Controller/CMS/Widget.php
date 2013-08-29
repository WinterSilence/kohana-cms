<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Basic widget controller
 *
 * @package     CMS/Common
 * @category    Controller
 * @author      WinterSilence
 */
abstract class Controller_CMS_Widget extends Controller_Layout
{
	/**
	 * Deny external requests?
	 * @var  boolean
	 */
	public $deny_external = TRUE;

	/**
	 * Automatically executed before the controller action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		if ($this->deny_external AND $this->request->is_external())
		{
			throw HTTP_Exception::factory(501, 'External request is denied');
			// throw new Request_Exception('External request is denied', 501);
		}
		
		// Widget have only index action
		$this->request->action('index');
		
		parent::before();
	}

	/**
	 * Default action, action_index() is alias for action() method
	 *
	 * @return  void
	 */
	public function action_index()
	{
		$this->action();
	}

	/**
	 * Abstract action, override in your widget class
	 *
	 * @return  void
	 */
	public function action()
	{
		// ..
	}

} // End Controller_Widget