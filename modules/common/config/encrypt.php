<?php defined('SYSPATH') OR die('No direct script access.');
 
return array(
	'default' => array(
		'key'    => '2r3QwVXX96TIJoKxyByB9AJkwAOHixuV1ENZmIWyanI0j1zNgSVvqywy014Agaj',
		'cipher' => MCRYPT_RIJNDAEL_128,
		'mode'   => MCRYPT_MODE_NOFB,
	),
	'blowfish' => array(
		'key'    => '7bZJJkmNr1lj5NaKoY6h6rMSRSmeUlJu6eOd5HHka5Xkn2MX4uGSfeVolTz4IYy',
		'cipher' => MCRYPT_BLOWFISH,
		'mode'   => MCRYPT_MODE_ECB,
	),
	'tripledes' => array(
		'key'    => 'anhcSLRvA3LkFc7EJgxXIKcuz1ec91J7P6WNq1IaxMZp4CTj5m39gZLARLxI1j8',
		'cipher' => MCRYPT_3DES,
		'mode'   => MCRYPT_MODE_CBC,
	),
);