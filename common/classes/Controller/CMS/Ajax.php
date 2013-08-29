<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Basic AJAX controller
 *
 * @package   CMS/Common
 * @category  Controller
 * @author    WinterSilence
 */ 
abstract class Controller_CMS_Ajax extends Controller_Basic
{
	/**
	 * View auto render? Not used for AJAX requests.
	 * @var  boolean
	 */
	public $auto_render = FALSE;

	/**
	 * Response data type(html|json|xml). Not convert data, if empty.
	 * @var mixed(string|NULL)
	 */
	public $data_type = 'json';

	/**
	 * Automatically executed before the controller action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		// Check request type
		if ( ! $this->request->is_ajax())
		{
			throw HTTP_Exception::factory(501, 'AJAX request not detected');
			// throw new Request_Exception('AJAX request not detected', 501);
		}
		
		// Set response data type
		$this->data_type = $this->param('data_type', $this->data_type);
		if ( ! empty($this->data_type) AND ! in_array($this->data_type, array('html', 'json', 'xml')))
		{
			throw HTTP_Exception::factory(500, 'Wrong response data type');
			// throw new Request_Exception('Wrong response data type', 500);
		}
		
		parent::before();
	}

	/**
	 * Sets request response options
	 *
	 * @return  void
	 */
	public function set_response()
	{
		// Add special header fields for AJAX requests
		$this->headers = array_merge(
			$this->headers, 
			array(
				'Content-Type'  => 'text/'.$this->data_type,
				'Pragma'        => 'no-cache', 
				'Cache-Control' => 'no-store, no-cache, must-revalidate',
			)
		);
		
		parent::set_response();
	}

	/**
	 * Automatically executed after the controller action. Can be used to apply
	 * transformation to the response, add extra output, and execute other custom code.
	 * 
	 * @return  void
	 */
	public function after()
	{
		// Convert response data
		if ( ! empty($this->data_type) AND gettype($this->content) != $this->data_type)
		{
			$this->content = CMS::convert($this->content, $this->data_type);
		}
		
		parent::after();
	}

} // End Controller_Ajax