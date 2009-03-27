<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
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
					'class'=>'CWebLogRoute',
					'levels'=>'error', //, warning, info, trace',
					'categories'=>'system.db.*'
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
			#'username' => !Yii::app()->user->isGuest ? Yii::app()->user->getName() : false,
			#'password' => !Yii::app()->user->isGuest ? Yii::app()->user->password : false,
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
                'login'=>'site/login',
                'database'=>'database/list',
                'database/show/<id:(.*)>'=>'database/show',
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
	),

	'sourceLanguage'=>'asdf',
	#'language' => substr(Yii::app()->request->getPreferredLanguage(), 0, 2),
);
