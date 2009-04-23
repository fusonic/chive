<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

define('URL_MATCH', '([^\/]*)');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Dublin - database management',
	'theme'=>'standard',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.helpers.*',
		'application.components.helpers.utils.*',
		'application.controllers.*',
		'application.db.*',
		'application.extensions.*',
	),

	// application components
	'components'=>array(

		// Log database
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info, trace',
				),
				array(
					'class'=>'CProfileLogRoute',
					'levels'=>'error, warning, info, trace',
					'showInFireBug'=>true,
				),
				array(
					'class'=>'CWebLogRoute',
					'levels'=>'error, warning, info, trace', //, warning, info, trace',
					'showInFireBug'=>true,
				),
			),
		),

		// User settings
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		// Database settings
		'db'=>array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=web;dbname=information_schema',
			'charset' => 'utf8',
			'autoConnect' => false,
			'schemaCachingDuration'=>3600,
		),

		'messages'=>array(
		    'class'=>'application.components.messages.CXmlMessageSource',
			'cachingDuration' => 0,
		),

		// URL - Manager (for SEO-friendly URLs)
		'urlManager'=>array(
            'urlFormat'=>'path',
			'showScriptName' => false,
            'rules'=>array(
				// Login
                'login'=>'site/login',

				// Site
                'site/changeLanguage/<id:(.*)>'=>'site/changeLanguage',
                'site/changeTheme/<id:(.*)>'=>'site/changeTheme',

				// schemas
				'schemata'=>'schema/list',
				'schemata/create'=>'schema/create',
				'schemata/update'=>'schema/update',
				'schemata/drop'=>'schema/drop',

				// schema
               	'schema'=>'schema/list',
                'schema/<schema:'.URL_MATCH.'>'=>'schema/index',
				'schema/<schema:'.URL_MATCH.'>/tables'=>'schema/show',
				'schema/<schema:'.URL_MATCH.'>/sql'=>'schema/sql',

				//Bookmarks
				'schema/<schema:'.URL_MATCH.'>/bookmark/show/<id:(.+)>'=>'schema/showBookmark',

					// Table
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/browse'=>'table/browse',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/structure'=>'table/structure',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/sql'=>'table/sql',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/search'=>'table/search',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/insert'=>'table/insert',

					// Table actions
					'schema/<schema:'.URL_MATCH.'>/tableAction/truncate'=>'table/truncate',
					'schema/<schema:'.URL_MATCH.'>/tableAction/drop'=>'table/drop',

					// Row
					#'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/row/load'=>'row/load',
					#'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/row/update'=>'row/update',
					#'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/row/delete'=>'row/delete',

					// Column
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/columns/<column:'.URL_MATCH.'>/move'=>'column/move',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/columns/<column:'.URL_MATCH.'>/update'=>'column/update',

					// ColumnActions
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/columnAction/create'=>'column/create',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/columnAction/drop'=>'column/drop',

					// Index
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/indices/<index:'.URL_MATCH.'>/update'=>'index/update',

					// IndexActions
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/indexAction/create'=>'index/create',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/indexAction/createSimple'=>'index/createSimple',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/indexAction/drop'=>'index/drop',
            ),
        ),

        /*
        // Cache
        'cache' => array(
        	'class' => 'system.caching.CMemCache',
        	'servers'=>array(
                array(
                    'host'=>'127.0.0.1',
                    'port'=>11211,
                    'weight'=>100,
                ),
            ),
        ),
        */

        // View Renderer (template engine)
        'viewRenderer'=>array(
            'class'=>'CPradoViewRenderer',
        ),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'iconpack'=>'images/icons/fugue',
	),

	'sourceLanguage'=>'xxx',
);
