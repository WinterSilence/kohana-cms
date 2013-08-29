<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Widget blog post tags
 *
 * @package   Blog
 * @category  Controller
 * @author    WinterSilence
 */ 
class Controller_Widget_Blog_Tags extends Controller_Widget
{
	public function action()
	{
		$tags = array(
			array('weight' => 1, 'slug' => 'html5', 'name' => 'html5'),
			array('weight' => 2, 'slug' => 'cms', 'name' => 'cms'),
			array('weight' => 0, 'slug' => 'getoffset', 'name' => 'getoffset'),
			array('weight' => 7, 'slug' => 'slider', 'name' => 'slider'),
			array('weight' => 0, 'slug' => 'html', 'name' => 'html'),
			array('weight' => 2, 'slug' => 'php', 'name' => 'php'),
			array('weight' => 9, 'slug' => 'jquery', 'name' => 'jquery'),
			array('weight' => 1, 'slug' => 'time', 'name' => 'time'),
			array('weight' => 3, 'slug' => 'wysiwyg', 'name' => 'wysiwyg'),
			array('weight' => 2, 'slug' => 'news', 'name' => 'новости'),
			array('weight' => 4, 'slug' => 'plugin', 'name' => 'plugin'),
			array('weight' => 0, 'slug' => 'ondomready', 'name' => 'ondomready'),
			array('weight' => 1, 'slug' => 'event', 'name' => 'event'),
			array('weight' => 2, 'slug' => 'unixtime', 'name' => 'unixtime'),
			array('weight' => 0, 'slug' => 'glyuk', 'name' => 'глюк'),
			array('weight' => 9, 'slug' => 'ajax', 'name' => 'ajax'),
			array('weight' => 1, 'slug' => 'danneo', 'name' => 'danneo'),
			array('weight' => 5, 'slug' => 'polezniy-skript', 'name' => 'скрипт'),
			array('weight' => 1, 'slug' => 'gallery', 'name' => 'галерея'),
			array('weight' => 3, 'slug' => 'ckeditor', 'name' => 'ckeditor'),
		);
		shuffle($tags);
		$this->content->tags = $tags;
	}
} // End Controller_Widget_Blog_Tags