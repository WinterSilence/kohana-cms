<?php defined('SYSPATH') OR die('No direct access allowed.');return array(	'meta_tags' => array(		'title'       => array('Categories'),		'description' => 'Blog Categories description',		'keywords'    => 'Blog Categories keywords',	),	'assets' => array(		'css' => array('page/blog/category.css'),	),	'breadcrumbs' => array('controller:blog,action:categories' => 'Categories'),);