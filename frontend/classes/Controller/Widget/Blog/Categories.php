<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Widget blog post categories
 *
 * @package   Blog
 * @category  Controller
 * @author    WinterSilence
 */ 
class Controller_Widget_Blog_Categories extends Controller_Widget
{
	public function action()
	{
		$this->content->blog_categories = ORM::factory('Blog_Category')
			->where('active', '=', 1)
			->order_by('sort_order', 'asc')
			->cached(Date::MINUTE)
			->find_all()
			->as_array();
	}
} // End Controller_Widget_Blog_Categories