<?php defined('SYSPATH') OR die('No direct script access.');

class Request extends Kohana_Request
{
	/**
	 * Get request general data
	 * 
	 * @param   mixed  $key  Key or key value pairs to get
	 * @return  array
	 * @uses    Arr::extract
	 */
	public function data($key = NULL)
	{
		$reult = array(
			'directory'  => $this->directory(),
			'controller' => $this->controller(),
			'action'     => $this->action(),
		) + $this->param();
		
		return ($key ? Arr::extract($reult, (array) $key) : $reult);
	}
	
	/**
	 * Gets or sets HTTP POST parameters to the request.
	 *
	 * @param   mixed  $key    Key or key value pairs to set
	 * @param   string $value  Value to set to a key
	 * @return  mixed
	 * @uses    Arr::path
	 * @uses    Arr::set_path
	 */
	public function post($key = NULL, $value = NULL)
	{
		if (is_array($key))
		{
			// Act as a setter, replace all fields
			$this->_post = $key;
			// $this->_post = Arr::merge($this->_post, $key);
		}
		elseif ($key === NULL)
		{
			// Act as a getter, all fields
			return $this->_post;
		}
		elseif ($value === NULL)
		{
			// Act as a getter, single field
			return Arr::path($this->_post, $key);
		}
		else
		{
			// Set a value on an array by path.
			Arr::set_path($this->_post, $key, $value);
		}
		return $this;
	}

} // End Request