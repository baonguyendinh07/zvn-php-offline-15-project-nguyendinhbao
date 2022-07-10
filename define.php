<?php
	
	// ====================== PATHS ===========================
	define 	('DS'				, '/');
	define 	('ROOT_PATH'			, dirname(__FILE__));						// Định nghĩa đường dẫn đến thư mục gốc
	define 	('LOCALHOST'			, 'localhost');
	define	('LIBRARY_PATH'		, ROOT_PATH . DS . 'libs' . DS);			// Định nghĩa đường dẫn đến thư mục thư viện
	define 	('PUBLIC_PATH'		, ROOT_PATH . DS . 'public' . DS);			// Định nghĩa đường dẫn đến thư mục public							
	define 	('APPLICATION_PATH'	, ROOT_PATH . DS . 'application' . DS);		// Định nghĩa đường dẫn đến thư mục public							
	define 	('TEMPLATE_PATH'		, PUBLIC_PATH . 'template' . DS);							
	define	('FILES_PATH'		, PUBLIC_PATH . 'files' . DS);
	define	('BLOCK_PATH'		, APPLICATION_PATH . 'block' . DS);


	
	define	('ROOT_URL'			, DS . 'zvn-php-offline-15-project-nguyendinhbao' . DS);
	define	('APPLICATION_URL'	, ROOT_URL . 'application' . DS);
	define	('PUBLIC_URL'		, ROOT_URL . 'public' . DS);
	define	('TEMPLATE_URL'		, PUBLIC_URL . 'template' . DS);
	define	('FILES_URL'		, PUBLIC_URL . 'files' . DS);


	define	('DEFAULT_MODULE'		, 'default');
	define	('DEFAULT_CONTROLLER'	, 'index');
	define	('DEFAULT_ACTION'		, 'index');

	// ====================== DATABASE ===========================
	define ('DB_HOST'			, 'us-cdbr-east-06.cleardb.net');
	define ('DB_USER'			, 'bf69243b301d28');						
	define ('DB_PASS'			, 'f5d01478');						
	define ('DB_NAME'			, 'heroku_933e030ef249a56');						
	define ('DB_TABLE'			, 'group');
	define ('LOGIN_TIME'		, '3600');								


	//mysql://bf69243b301d28:f5d01478@us-cdbr-east-06.cleardb.net/heroku_933e030ef249a56?reconnect=true