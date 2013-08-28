<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'driver'       => 'ORM',
	'hash_method'  => 'sha256',
	'hash_key'     => '12ef941sf0!4u57852Hc65b374b,9dDd',
	'lifetime'     => Date::DAY,
	'session_type' => Session::$default,
	'session_key'  => 'cms_auth_user',
	// Authentification(login) page
	'url_login'   => CMS::url('controller:user,action:login'),
	// Registration confirm page
	'url_confirm' => CMS::url('controller:user,action:confirm'),
	// User account page
	'url_success' => CMS::url('controller:account,action:index'),
);