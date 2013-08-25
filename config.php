<?php defined('SYSPATH') OR die('No direct script access.');

Kohana::$environment = Kohana::DEVELOPMENT; // Kohana::PRODUCTION

$charset  = 'utf-8';

// Server
$server = array(
	'address'  => '127.0.0.1',
	'name'     => 'kohana3',
	'base_url' => '/',
);

// Bootstrap config
return array(
	// Date and language
	'timezone'    => 'Europe/Moscow',
	'locale'      => 'ru_RU.'.$charset,
	'charset'     => $charset,
	'language'    => 'ru',
	// Server
	'server' => $server,
	// Secure
	'encrypt' => 'blowfish',
	/**
	 * Cookie configuration
	 * 
	 * The following options are available:
	 * 
	 * - string		salt		Magic salt to add to a cookie							"12345"
	 * - integer	expiration	Number of seconds before a cookie expires				0
	 * - string		domain		Restrict the domain that the cookie is available to		NULL
	 * - string		path		Restrict the path that the cookie is available to		"/"
	 * - boolean	httponly	Only transmit cookies over HTTP, disabling JS access	FALSE
	 * - boolean	secure		Only transmit cookies over secure connections			FALSE
	 */
	'cookie' => array(
		'salt'       => 'i_6f05.Bl1i!sS3xaM0z',
		'expiration' => Date::WEEK,
		'domain'     => $server['name'],
		'path'       => $server['base_url'],
		'httponly'   => FALSE,
		'secure'     => FALSE,
	),
	/**
	 * Initialize Kohana, setting the default options.
	 *
	 * The following options are available:
	 *
	 * - string   base_url		path, and optionally domain, of your application	NULL
	 * - string   index_file	name of your index file, usually "index.php"		index.php
	 * - string   charset		internal character set used for input and output	utf-8
	 * - string   cache_dir		set the internal cache directory					APPPATH/cache
	 * - integer  cache_life	lifetime, in seconds, of items cached				60
	 * - boolean  errors		enable or disable error handling					TRUE
	 * - boolean  profile		enable or disable internal profiling				TRUE
	 * - boolean  caching		enable or disable internal caching					FALSE
	 * - boolean  expose		set the X-Powered-By header							FALSE
	 */
	'init' => array(
		'base_url'   => $server['base_url'],
		'index_file' => FALSE,
		'charset'    => $charset,
		'cache_dir'  => APPPATH.'cache',
		'cache_life' => Date::MINUTE*10,
		'errors'     => Kohana::$environment !== Kohana::PRODUCTION,
		'profile'    => Kohana::$environment !== Kohana::PRODUCTION,
		'caching'    => Kohana::$environment === Kohana::PRODUCTION,
		'expose'     => FALSE,
	),
);