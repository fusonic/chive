<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


define('URL_MATCH', '([^\/]*)');

return array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'Chive',
	'theme' => 'standard',

	// preloading 'log' component
	'preload' => array('log'),

	// autoloading model and component classes
	'import' => array(
		'application.models.*',
		'application.models.Index',
		'application.models.privileges.*',
		'application.components.*',
		'application.components.helpers.*',
		'application.components.helpers.utils.*',
		'application.components.export.*',
		'application.controllers.*',
		'application.db.*',
		'application.extensions.*',
	),

	// application components
	'components' => array(
	
		'session' => array(
			'class' => 'ChiveHttpSession',
			'sessionName' => 'chiveSession',
			'savePath' => 'protected/runtime/sessions',
			'cookieParams' => array(
				 'path' => ''
			),
		),

		'request' => array(
			'class' => 'ChiveHttpRequest',
			'enableCookieValidation' => true,
		),

		'locale' => array(
			'dateFormat' => 'middle',
			'dateTimeFormat' => 'middle'
		),

		// Log database
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning, info, trace',
				),
				array(
					'class' => 'CProfileLogRoute',
					'levels' => 'error, warning, info, trace',
					'showInFireBug' => false,
				),
			),
		),

		// User settings
		'user' => array(
			// Enable cookie-based authentication
			'allowAutoLogin' => true,
		),

		// Database settings
		'db' => array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=information_schema',
			'charset' => 'utf8',
			'autoConnect' => false,
			'schemaCachingDuration' => 3600,
			'emulatePrepare' => true,
		),

		'messages' => array(
		    'class' => 'application.components.messages.CXmlMessageSource',
			'cachingDuration' => 24 * 60 * 60, // 24h
		),
		
		'cache' => array(
			'class' => 'CFileCache',
		),

		// URL-Manager
		'urlManager' => array(
            'urlFormat' => 'path',
			'showScriptName' => !isset($_GET['__chive_rewrite_on']),
            'rules' => array(

				// Site
                'site/changeLanguage/<id:(.*)>' => 'site/changeLanguage',
                'site/changeTheme/<id:(.*)>' => 'site/changeTheme',

				// schemas
				'schemata' => 'schema/list',
				'schemata/create' => 'schema/create',
				'schemata/update' => 'schema/update',
				'schemata/drop' => 'schema/drop',

				// Information
				'information/status' => 'information/status',
				'information/variables' => 'information/variables',
				'information/characterSets' => 'information/characterSets',
				'information/storageEngines' => 'information/storageEngines',
				'information/processes' => 'information/processes',
				'information/processes/kill' => 'information/killProcess',

				// Privileges
				'privileges/users' => 'privileges/users',
				'privileges/users/<user:' . URL_MATCH . '>/update' => 'privileges/updateUser',
				'privileges/userActions/create' => 'privileges/createUser',
				'privileges/userActions/drop' => 'privileges/dropUsers',

				'privileges/users/<user:' . URL_MATCH . '>/schemata' => 'privileges/schemata',
				'privileges/users/<user:' . URL_MATCH . '>/schemata/<schema:' . URL_MATCH . '>/update' => 'privileges/updateSchema',
				'privileges/users/<user:' . URL_MATCH . '>/schemaActions/create' => 'privileges/createSchema',
				'privileges/users/<user:' . URL_MATCH . '>/schemaActions/drop' => 'privileges/dropSchema',

				// schema
               	'schema'=>'schema/list',
                'schema/<schema:'.URL_MATCH.'>'=>'schema/index',
				'schema/<schema:'.URL_MATCH.'>/tables'=>'schema/tables',
				'schema/<schema:'.URL_MATCH.'>/views'=>'schema/views',
				'schema/<schema:'.URL_MATCH.'>/routines'=>'schema/routines',
				'schema/<schema:'.URL_MATCH.'>/sql'=>'schema/sql',
				'schema/<schema:'.URL_MATCH.'>/import'=>'schema/import',
				'schema/<schema:'.URL_MATCH.'>/import/upload'=>'schema/upload',
				'schema/<schema:'.URL_MATCH.'>/export'=>'schema/export',

				//Bookmarks
				'schema/<schema:'.URL_MATCH.'>/bookmark/show/<id:(.+)>'=>'schema/showBookmark',

					// Table actions
					'schema/<schema:'.URL_MATCH.'>/tableAction/create'=>'table/create',
					'schema/<schema:'.URL_MATCH.'>/tableAction/truncate'=>'table/truncate',
					'schema/<schema:'.URL_MATCH.'>/tableAction/drop'=>'table/drop',

					// Table
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/browse'=>'table/browse',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/structure'=>'table/structure',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/sql'=>'table/sql',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/search'=>'table/search',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/insert'=>'row/insert',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/update'=>'table/update',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/export'=>'table/export',
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/import'=>'table/import',

						// ColumnActions
						'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/columnAction/create'=>'column/create',
						'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/columnAction/drop'=>'column/drop',

						// Column
						'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/columns/<column:'.URL_MATCH.'>/move'=>'column/move',
						'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/columns/<column:'.URL_MATCH.'>/update'=>'column/update',

						// Relation
						'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/foreignKeys/<column:'.URL_MATCH.'>/update'=>'foreignKey/update',

						// IndexActions
						'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/indexAction/create'=>'index/create',
						'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/indexAction/createSimple'=>'index/createSimple',
						'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/indexAction/drop'=>'index/drop',

						// Index
						'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/indices/<index:'.URL_MATCH.'>/update'=>'index/update',

						// TriggerActions
						'schema/<schema:' . URL_MATCH . '>/tables/<table:' . URL_MATCH . '>/triggerAction/create' => 'trigger/create',
						'schema/<schema:' . URL_MATCH . '>/tables/<table:' . URL_MATCH . '>/triggerAction/drop' => 'trigger/drop',

						// Trigger
						'schema/<schema:' . URL_MATCH . '>/tables/<table:' . URL_MATCH . '>/triggers/<trigger:' . URL_MATCH . '>/update' => 'trigger/update',

					// View actions
					'schema/<schema:' . URL_MATCH . '>/viewAction/create' => 'view/create',
					'schema/<schema:' . URL_MATCH . '>/viewAction/drop' => 'view/drop',

					// Views
					'schema/<schema:' . URL_MATCH . '>/views/<view:' . URL_MATCH . '>/browse' => 'view/browse',
					'schema/<schema:' . URL_MATCH . '>/views/<view:' . URL_MATCH . '>/structure' => 'view/structure',
					'schema/<schema:' . URL_MATCH . '>/views/<view:' . URL_MATCH . '>/sql' => 'view/sql',
					'schema/<schema:' . URL_MATCH . '>/views/<view:' . URL_MATCH . '>/search' => 'view/search',
					'schema/<schema:' . URL_MATCH . '>/views/<table:' . URL_MATCH . '>/insert' => 'row/insert',
					'schema/<schema:' . URL_MATCH . '>/views/<view:' . URL_MATCH . '>/update' => 'view/update',

					// Routine actions
					'schema/<schema:' . URL_MATCH . '>/routineAction/create' => 'routine/create',
					'schema/<schema:' . URL_MATCH . '>/routineAction/drop' => 'routine/drop',

					// Routines
					'schema/<schema:' . URL_MATCH . '>/routines/<routine:' . URL_MATCH . '>/update' => 'routine/update',

					// Row
					'schema/<schema:'.URL_MATCH.'>/tables/<table:'.URL_MATCH.'>/row/export'=>'row/export',
            ),
        ),

        // View Renderer (template engine)
        'viewRenderer' => array(
            'class' => 'CPradoViewRenderer',
        ),

	),

	// application-level parameters
	'params' => array(
		'iconPack' => 'fugue',
		'version' => '1.3',
	),

	// There is no source language ...
	'sourceLanguage' => 'xxx',
);
