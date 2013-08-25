<?php defined('SYSPATH') OR die('No direct script access.');

class ORM extends Kohana_ORM
{
	/**
	 * Constructs a new model and loads a record if given
	 *
	 * @param  mixed  $id  Parameter for find or object to load
	 * @return void
	 */
	public function __construct($id = NULL)
	{
		// Basic loading
		if ( ! empty($this->_table_columns) OR ! Kohana::$caching)
		{
			// TODO: 
			//return parent::__construct($id);
			parent::__construct($id);
			// Saving columns in config file for add in model
			if (Kohana::$environment != Kohana::PRODUCTION)
			{
				$path = APPPATH.'config'.DS.CMS::path($this).EXT;
				File::var_export($this->_table_columns, $path, TRUE);
			}
			return ;
		}
		
		// Loading table columns from cache
		$tag = 'table_columns:'.get_class($this);
		
		if ( ! $this->_table_columns = Kohana::cache($tag))
		{
			parent::__construct($id);
			
			// Saving columns in config file for add in model
			if (Kohana::$environment != Kohana::PRODUCTION)
			{
				$path = APPPATH.'config'.DS.CMS::path($this).EXT;
				File::var_export($this->_table_columns, $path, TRUE);
			}
			
			// Save columns in cache
			Kohana::cache($tag, $this->_table_columns, Date::DAY);
		}
	}
	
	/**
	 * Count the number of records in the table.
	 *
	 * @return integer
	 */
	public function count_all($reset = TRUE)
	{
		$selects = array();
		foreach ($this->_db_pending as $key => $method)
		{
			if ($method['name'] == 'select')
			{
				// Ignore any selected columns for now
				$selects[] = $method;
				unset($this->_db_pending[$key]);
			}
		}
		if ( ! empty($this->_load_with))
		{
			foreach ($this->_load_with as $alias)
			{
				// Bind relationship
				$this->with($alias);
			}
		}
		$this->_build(Database::SELECT);
		$records = $this->_db_builder
						->from(array($this->_table_name, $this->_object_name))
						->select(array(DB::expr('COUNT(*)'), 'records_found'))
						->execute($this->_db)
						->get('records_found');
		
		// Add back in selected columns
		$this->_db_pending += $selects;
		
		$this->reset($reset);
		
		// Return the total number of records in a table
		return $records;
	}
	
	/**
	 * Filters a value for a specific column
	 *
	 * @param  string  $field   The column name
	 * @param  string  $value   The value to filter
	 * @param  boolean $filter  Filter method
	 * @return string
	 */
	protected function run_filter($field, $value, $filter = 'filters')
	{
		$filters = $this->{$filter}();

		// Get the filters for this column
		$wildcards = empty($filters[TRUE]) ? array() : $filters[TRUE];

		// Merge in the wildcards
		$filters = empty($filters[$field]) ? $wildcards : array_merge($wildcards, $filters[$field]);

		// Bind the field name and model so they can be used in the filter method
		$_bound = array(
			':field' => $field,
			':model' => $this,
		);

		foreach ($filters as $array)
		{
			// Value needs to be bound inside the loop so we are always using the
			// version that was modified by the filters that already ran
			$_bound[':value'] = $value;

			// Filters are defined as array($filter, $params)
			$filter = $array[0];
			$params = Arr::get($array, 1, array(':value'));

			foreach ($params as $key => $param)
			{
				if (is_string($param) AND array_key_exists($param, $_bound))
				{
					// Replace with bound value
					$params[$key] = $_bound[$param];
				}
			}

			if (is_array($filter) OR ! is_string($filter))
			{
				// This is either a callback as an array or a lambda
				$value = call_user_func_array($filter, $params);
			}
			elseif (strpos($filter, '::') === FALSE)
			{
				// Use a function call
				$function = new ReflectionFunction($filter);

				// Call $function($this[$field], $param, ...) with Reflection
				$value = $function->invokeArgs($params);
			}
			else
			{
				// Split the class and method of the rule
				list($class, $method) = explode('::', $filter, 2);

				// Use a static method call
				$method = new ReflectionMethod($class, $method);

				// Call $Class::$method($this[$field], $param, ...) with Reflection
				$value = $method->invokeArgs(NULL, $params);
			}
		}
		return $value;
	}
	
	/**
	 * Filter definitions for validation uses after select rows
	 *
	 * @return array
	 */
	public function output_filters()
	{
		return array();
	}

	/**
	 * Get items per page
	 */
	public function find_items_per_page($offset = 0, $limit = 10, $order = NULL, $direction = 'asc')
	{
		return $this->order_by(($order ? $order : $this->pk()), $direction)
					->offset($offset)
					->limit($limit)
					->find_all()
					->as_array();
	}

} // End ORM