<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Info page model
 * 
 * @package   CMS/Common
 * @category  Model
 * @author    WinterSilence
 */
class Model_Info_Page extends Model_CMS_Info_Page
{
	/**
	 * Finds and loads a single database row into the object.
	 *
	 * @chainable
	 * @throws Kohana_Exception
	 * @return ORM
	 */
	public function find()
	{
		// Find active pages
		$this->where('active', '=', 1);
		return parent::find();
	}

	/**
	 * Finds multiple database rows and returns an iterator of the rows found.
	 *
	 * @throws Kohana_Exception
	 * @return Database_Result
	 */
	public function find_all()
	{
		// Find active pages
		$this->where('active', '=', 1);
		return parent::find_all();
	}

} // End Model_Info_Page