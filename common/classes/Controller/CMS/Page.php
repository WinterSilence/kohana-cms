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
	 * Page theme layout
	 * @var mixed
	 */
	public $layout = 'layout/basic';

	/**
	 * Load controller configuration from config parts
	 * 
	 * @return  void
	 */
	public function set_config()
	{
		// Add theme layout config part
		$this->config[] = $this->layout;
		
		parent::set_config();
	}

	/**
	 *  Render content and wrap in layout 
	 * 
	 * @return  string
	 */
	public function render_content()
	{
		// Wrap page content in theme layout
		$this->layout = SView::factory($this->layout);
		$this->layout->content = $this->content->render();
		$this->content = $this->layout;
		
		return parent::render_content();
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
		// Bind config in View
		SView::bind_global('config', $this->config);
		
		SView::set_global(array(
			// 'controller' => $this->request->controller(),
			// 'action'     => $this->request->action(),
			'user'       => $this->user(),
			'post_data'  => $this->post(),
		));
		
		parent::after();
	}

} // End Controller_Page