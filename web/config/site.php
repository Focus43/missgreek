<?php
	
	/**
	 * PAGODABOX PRODUCTION SETTINGS
	 */
	if( isset($_SERVER['PAGODA_PRODUCTION']) && ((bool) $_SERVER['PAGODA_PRODUCTION'] === true) ) {
		
		// enable all url rewriting
		define('URL_REWRITING_ALL', true);
		
		// needed for successful installation on Pagodabox. see issue
		// http://www.concrete5.org/developers/bugs/5-6-0-2/install-fails-with-mysql-auto-increment-offset-set/
		// define('REGISTERED_GROUP_ID', '5');
		// define('ADMIN_GROUP_ID', '9');
		
		// connect to Redis cache
		define('REDIS_CONNECTION_HANDLE', sprintf("%s:%s", $_SERVER['CACHE1_HOST'], $_SERVER['CACHE1_PORT']));

		// the following depend on the constant REDIS_CONNECTION_HANDLE being defined
		if( defined('REDIS_CONNECTION_HANDLE') ){
			// use Redis as the page cache library
			define('PAGE_CACHE_LIBRARY', 'Redis');
		
			// if using the FluidDNS package
			define('PAGE_TITLE_FORMAT', '%2$s');
		}

        // disable marketplace support b/c of Pagodabox read-only file system
        define('ENABLE_MARKETPLACE_SUPPORT', false);

        // thumbnail compression defaults
        define('AL_THUMBNAIL_JPEG_COMPRESSION', 90);

        // AUTHORIZE.NET STUFF (CLINICA SPECIFIC)
        define('AUTHORIZENET_API_LOGIN_ID', $_SERVER['AUTHNET_API_LOGIN']);
        define('AUTHORIZENET_TRANSACTION_KEY', $_SERVER['AUTHNET_API_TRXN_KEY']);
        define('AUTHORIZENET_SANDBOX', false);
	
	/**
	 * STAGING, LOCAL MACHINE, OR VAGRANT?
	 * 
	 * Is the site running locally? Then create a site.local.php file in the /config folder,
	 * and DO NOT TRACK IT IN THE REPO (default settings in .gitignore). Any team members, 
	 * or other environments (eg. dev or staging) you want to run the site on should have 
	 * their own site.local.php file.
	 */
	}else{

        if( (isset($_SERVER['VAGRANT_VM']) && ((bool) $_SERVER['VAGRANT_VM'] === true)) || in_array('VAGRANT_VM', (array) $argv) ){

            // This only applies when auto-installing through Vagrant provisioner; basically
            // if the 3rd argument exists, we know we're receiving host, user, pass, and db_name
            // in that order, and should set the $_SERVER variable here (then apache takes over
            // for setting server variables when accessed via http
            if( $argv[2] !== null ){
                $_SERVER['DB1_HOST'] = $argv[2];
                $_SERVER['DB1_USER'] = $argv[3];
                $_SERVER['DB1_PASS'] = $argv[4];
                $_SERVER['DB1_NAME'] = $argv[5];
            }

            // enable all url rewriting
            define('URL_REWRITING_ALL', true);
            // connect to Redis cache
            define('REDIS_CONNECTION_HANDLE', '127.0.0.1:6379');
            // the following depend on the constant REDIS_CONNECTION_HANDLE being defined
            if( defined('REDIS_CONNECTION_HANDLE') ){
                // use Redis as the page cache library
                define('PAGE_CACHE_LIBRARY', 'Redis');
                // if using the FluidDNS package
                define('PAGE_TITLE_FORMAT', '%2$s');
            }

            // AUTHORIZE.NET STUFF (TEST ACCOUNT CREDENTIALS)
            define('AUTHORIZENET_API_LOGIN_ID', '7ep7L4U4');
            define('AUTHORIZENET_TRANSACTION_KEY', '4y4G4436kMYJg749');
            define('AUTHORIZENET_SANDBOX', true);

        }else{

            require __DIR__ . '/site.local.php';

            /**************************** SAMPLE *****************************
            $_SERVER['DB1_HOST'] = 'localhost';
            $_SERVER['DB1_USER'] = 'root';
            $_SERVER['DB1_PASS'] = '';
            $_SERVER['DB1_NAME'] = '';

            // enable url rewriting. use locally if you have an Apache VirtualHost setup
            define('URL_REWRITING_ALL', true);

            // if you have Redis installed on your local machine...
            define('REDIS_CONNECTION_HANDLE', '127.0.0.1:6379');
            *****************************************************************/

        }

	}

    // server variables are set by Pagoda, or by you in site.local.php
    define('DB_SERVER',     $_SERVER['DATABASE1_HOST']);
    define('DB_USERNAME',   $_SERVER['DATABASE1_USER']);
    define('DB_PASSWORD',   $_SERVER['DATABASE1_PASS']);
    define('DB_DATABASE',   $_SERVER['DATABASE1_NAME']);
    define('PASSWORD_SALT', '6NVukfgwAgqaOi3SMlsWwEqURSe4Xh8pBApvhOauP7blC2kx1FKsHxcjGSXMqP3N');
	
	// sitemap.xml file
	define('SITEMAPXML_FILE', 'files/sitemap.xml');

    // issue emails from address
    define('OUTGOING_MAIL_ISSUER_ADDRESS', 'webreceipt@clinica.org');
    define('EMAIL_DEFAULT_FROM_ADDRESS', OUTGOING_MAIL_ISSUER_ADDRESS);
    define('EMAIL_ADDRESS_FORGOT_PASSWORD', OUTGOING_MAIL_ISSUER_ADDRESS);
    define('EMAIL_DEFAULT_FROM_NAME', 'Clinica.org Website');