<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Feedback model
 *
 * @package   CMS/Frontend
 * @category  Model
 * @author    WinterSilence
 */ 
class Model_Feedback extends Model
{
	
	protected $_data = array();

	
	protected $_data_valid = FALSE;

	
	protected $_validation = NULL;

	
	protected function __construct(array $data = NULL)
	{
		$this->_data =  Arr::extract($data, array('csrf', 'captcha', 'email', 'title', 'message'));
	}

	public function check()
	{
		$this->_validation = Validation_CSRF_Captcha::factory($this->_data)
			->rules('email', array(
				array('not_empty'),
				array('email'),
			))
			->rules('title', array(
				array('not_empty'),
				array('min_length', array(':value', 5)),
				array('max_length', array(':value', 200)),
			))
			->rules('message', array(
				array('not_empty'),
				array('min_length', array(':value', 50)),
				array('max_length', array(':value', 3000)),
			))
			->labels(array(
				'email'   => 'Email',
				'title'   => 'Title',
				'message' => 'Message',
			));
		
		return $this->_data_valid = $this->_validation->check();
	}

	// Returns the error messages. 
	public function errors($file = NULL, $translate = TRUE)
	{
		return $this->_validation->errors($file, $translate);
	}

	// Send feedback mail to admin
	public function send_mail($admin_mail)
	{
		if ($this->_data_valid)
		{
			$body = SView::factory('email/feedback', $this->_data);
			
			Email::instance()->from($this->_data['email'])
							 ->to($admin_mail)
							 ->subject('Feedback message')
							 ->body($body, TRUE)
							 ->send();
		}
		return $this;
	}

} // End Feedback