<?php

	function fetchEnv($key) {
		return $_ENV[$key] ? $_ENV[$key] : $_SERVER[$key];
	}

	$DB_SERVER;
	$DB_USERNAME;
	$DB_PASSWORD;
	$DB_DATABASE;
	$AUTHORIZENET_SANDBOX;
	$AUTHORIZENET_API_LOGIN_ID;
	$AUTHORIZENET_TRANSACTION_KEY;
	$REDIS_CONNECTION_HANDLE;

	/**
	 * PAGODABOX PRODUCTION SETTINGS
	 */
	if( isset($_SERVER['PAGODA_PRODUCTION']) && ((bool) $_SERVER['PAGODA_PRODUCTION'] === true) ) {

		$DB_SERVER 		= fetchEnv('DATABASE1_HOST');
		$DB_USERNAME	= fetchEnv('DATABASE1_USER');
		$DB_PASSWORD	= fetchEnv('DATABASE1_PASS');
		$DB_DATABASE 	= fetchEnv('DATABASE1_NAME');

		$AUTHORIZENET_SANDBOX = false;
		$AUTHORIZENET_API_LOGIN_ID = fetchEnv('AUTHNET_API_LOGIN');
		$AUTHORIZENET_TRANSACTION_KEY = fetchEnv('AUTHNET_API_TRXN_KEY');

		$REDIS_CONNECTION_HANDLE = sprintf("%s:%s", $_SERVER['CACHE1_HOST'], $_SERVER['CACHE1_PORT']);

 	} else { // running locally

		$DB_SERVER 		= fetchEnv('MYSQL_HOST');
		$DB_USERNAME	= fetchEnv('MYSQL_USER');
		$DB_PASSWORD	= fetchEnv('MYSQL_PASSWORD');
		$DB_DATABASE 	= fetchEnv('MYSQL_DATABASE');

		// visa test card #: 4007000000027
		$AUTHORIZENET_SANDBOX = true;
		$AUTHORIZENET_API_LOGIN_ID = '7ep7L4U4'; // test account: 7ep7L4U4
		$AUTHORIZENET_TRANSACTION_KEY = '223B67k6fGxJ57q8'; // test acct: 223B67k6fGxJ57q8

		$REDIS_CONNECTION_HANDLE = 'redis:6379';
	}

	define('REDIS_CONNECTION_HANDLE', $REDIS_CONNECTION_HANDLE);
	// define('PAGE_CACHE_LIBRARY', 'Redis');

	// Authorize.net settings
	define('AUTHORIZENET_SANDBOX', $AUTHORIZENET_SANDBOX);
	define('AUTHORIZENET_API_LOGIN_ID', $AUTHORIZENET_API_LOGIN_ID);
	define('AUTHORIZENET_TRANSACTION_KEY', $AUTHORIZENET_TRANSACTION_KEY);

	// Generic configs
	define('URL_REWRITING_ALL', true);
	define('PAGE_TITLE_FORMAT', '%2$s');
	define('AL_THUMBNAIL_JPEG_COMPRESSION', 90);
	define('ENABLE_MARKETPLACE_SUPPORT', false);
	define('SITEMAPXML_FILE', 'files/sitemap.xml');

  // server variables are set by Pagoda, or by you in site.local.php
  define('DB_SERVER',		$DB_SERVER);
  define('DB_USERNAME',	$DB_USERNAME);
  define('DB_PASSWORD',	$DB_PASSWORD);
  define('DB_DATABASE',	$DB_DATABASE);
  define('PASSWORD_SALT', '6NVukfgwAgqaOi3SMlsWwEqURSe4Xh8pBApvhOauP7blC2kx1FKsHxcjGSXMqP3N');

  // issue emails from address
  define('OUTGOING_MAIL_ISSUER_ADDRESS', 'webreceipt@clinica.org');
  define('EMAIL_DEFAULT_FROM_ADDRESS', OUTGOING_MAIL_ISSUER_ADDRESS);
  define('EMAIL_ADDRESS_FORGOT_PASSWORD', OUTGOING_MAIL_ISSUER_ADDRESS);
  define('EMAIL_DEFAULT_FROM_NAME', 'Clinica.org Website');
