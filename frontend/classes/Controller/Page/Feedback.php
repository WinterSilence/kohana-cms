<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Feedback page controller
 *
 * @package   CMS/Frontend
 * @category  Controller
 * @author    WinterSilence
 */ 
class Controller_Page_Feedback extends Controller_Page
{
	/**
	 * Content template
	 * @var mixed(string|View)
	 */
	public $content = 'page/feedback';

	public function action_index()
	{
		if ($this->post())
		{
			// Create model
			$form = Model::factory('Feedback', $this->post());
			if ($form->check())
			{
				// Send mail to admin
				$form->send_mail($this->config['contacts']['email']);
				// Send success message
				Message::success('Feedback message sent', TRUE);
				// Redirect for clear post data
				// $this->redirect($this->request->url());
				$this->redirect();
			}
			else
			{
				// Send error message
				Message::error($form->errors('model'));
			}
		}
	}
}