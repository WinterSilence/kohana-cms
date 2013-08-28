<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Basic page controller
 *
 * @package   CMS/Common
 * @category  Controller
 * @author    WinterSilence
 */ 
abstract class Controller_CMS_Page extends Controller_Layout
{
	/**
	 * Page layout template
	 * @var mixed(string|View)
	 */
	public $layout = 'layout/basic';
	
	/**
	 * Path to controller files (view, config and etc.)
	 * @var mixed(string|NULL)
	 */
	protected $_path = NULL;

	/**
	 * Automatically executed before the controller action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return void
	 */
	public function before()
	{
		// Set controller path
		$this->_path = CMS::path($this, $this->request->action());
		
		// Add config parts for layout, controller and action
		$this->config[] = $this->layout;
		$this->config[] = dirname($this->_path);
		$this->config[] = $this->_path;
		
		parent::before();
		
		// Send user agent info in View
		// SView::set_global('user_agent', $this->request->user_agent(array('browser', 'robot', 'mobile')));
		
		// Bind config in View
		SView::bind_global('config', $this->config);
	}

	/**
	 * Automatically executed after the controller action. Can be used to apply
	 * transformation to the response, add extra output, and execute
	 * other custom code.
	 * 
	 * @return  void
	 */
	public function after()
	{
		SView::set_global($this->request->data(array('controller', 'action')));
		SView::set_global(array(
			'user'      => $this->user(),
			'post_data' => $this->post(),
		));
		
		if ($this->auto_render)
		{
			if ( ! $this->content->file())
			{
				// Set page template
				$this->content->file($this->_path);
			}
			// Wrap content in layout
			$this->layout = SView::factory($this->layout);
			$this->layout->content = $this->content->render();
			$this->content = clone $this->layout;
			unset($this->layout);
		}
		
		parent::after();
	}

} // End Controller_Page