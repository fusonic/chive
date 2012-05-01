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


class SchemaController extends Controller
{
	public static $defaultPageSize = 10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction = 'list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	public $_schema;
	public $schema;

	public $isSent = false;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = 'schema';

	public function __construct($id, $module=null)
	{
		$request = Yii::app()->getRequest();
		$this->schema = $request->getParam('schema');

		if($request->isAjaxRequest && $this->schema && $request->pathInfo != 'schemata/update')
		{
			$this->layout = '_schema';
		}
		elseif($request->isAjaxRequest)
		{
			$this->layout = false;
		}

		parent::__construct($id, $module);
		$this->connectDb($this->schema ? $this->schema : 'information_schema');
	}

	/**
	 * Shows schema index.
	 *
	 * Currently redirects to table list (via Ajax).
	 */
	public function actionIndex()
	{
		$this->loadSchema();

		// Tables
		$this->_schema->tables = Table::model()->findAll('TABLE_SCHEMA = :schema AND TABLE_TYPE IN (\'BASE TABLE\', \'SYSTEM VIEW\')', array(
			':schema' => $this->schema,
		));

		// Views
		$this->_schema->views = View::model()->findAllByAttributes(array(
			'TABLE_SCHEMA' => $this->schema,
		));

		$this->render('index');
	}

	/**
	 * Lists all tables.
	 */
	public function actionTables()
	{
		$schema = $this->loadSchema();

		// Criteria
		$criteria = new CDbCriteria();
		$criteria->condition = 'TABLE_SCHEMA = :schema AND TABLE_TYPE IN (\'BASE TABLE\', \'SYSTEM VIEW\')';
		$criteria->params = array(
			':schema' => $this->schema,
		);

		if($this->request->getParam('sideBar'))
		{
			$tables = array();

			foreach(Table::model()->findAll($criteria) AS $table)
			{
				$tables[] = array(
					'tableName' => $table->TABLE_NAME,
					'rowCount' => $table->getRowCount(),
					'rowCountText' => Yii::t('core', 'Xrows', array($table->getRowCount(), '{amount}' => $table->getRowCount())),
				);
			}

			$this->sendJSON($tables);
		}
		else
		{

			// Pagination
			$pages = new Pagination(Table::model()->count($criteria));
			$pages->setupPageSize('pageSize', 'schema.tables');
			$pages->applyLimit($criteria);
			$pages->route = '#tables';

			// Sort
			$sort = new CSort('Table');
			$sort->attributes = array(
				'name' => 'TABLE_NAME',
				'rows' => 'TABLE_ROWS',
				'collation' => 'TABLE_COLLATION',
				'engine' => 'ENGINE',
				'datalength' => 'DATA_LENGTH',
				'datafree' => 'DATA_FREE',
			);
			$sort->route = '#tables';
			$sort->applyOrder($criteria);

			// Load data
			$schema->tables = Table::model()->findAll($criteria);

			// Render
			$this->render('tables', array(
				'schema' => $schema,
				'tableCount' => count($schema->tables),
				'pages' => $pages,
				'sort' => $sort,
			));
		}
	}

	/**
	 * Lists all views.
	 */
	public function actionViews()
	{
		$schema = $this->loadSchema();

		// Criteria
		$criteria = new CDbCriteria;
		$criteria->condition = 'TABLE_SCHEMA = :schema';
		$criteria->params = array(
			':schema' => $this->schema,
		);
		
		if($this->request->getParam('sideBar'))
		{
			$views = array();

			foreach(View::model()->findAll($criteria) AS $view)
			{
				$views[] = array(
					'viewName' => $view->TABLE_NAME,
				);
			}

			$this->sendJSON($views);
		}
		else
		{
			// Pagination
			$pages = new Pagination(View::model()->count($criteria));
			$pages->setupPageSize('pageSize', 'schema.views');
			$pages->applyLimit($criteria);
			$pages->route = '#views';
	
			// Sort
			$sort = new CSort('View');
			$sort->attributes = array(
				'name' => 'TABLE_NAME',
				'updatable' => 'IS_UPDATABLE',
			);
			$sort->route = '#views';
			$sort->applyOrder($criteria);
	
			// Load data
			$schema->views = View::model()->findAll($criteria);
	
			// Render
			$this->render('views', array(
				'schema' => $schema,
				'viewCount' => count($schema->views),
				'pages' => $pages,
				'sort' => $sort,
			));
		}
	}

	/**
	 * Lists all routines (procedures & functions).
	 */
	public function actionRoutines()
	{
		$schema = $this->loadSchema();

		// Criteria
		$criteria = new CDbCriteria;
		$criteria->condition = 'ROUTINE_SCHEMA = :schema';
		$criteria->params = array(
			':schema' => $this->schema,
		);

		// Pagination
		$pages = new Pagination(Routine::model()->count($criteria));
		$pages->setupPageSize('pageSize', 'schema.routines');
		$pages->applyLimit($criteria);
		$pages->route = '#routines';

		// Sort
		$sort = new CSort('View');
		$sort->attributes = array(
			'ROUTINE_NAME' => 'name',
		);
		$sort->route = '#routines';
		$sort->applyOrder($criteria);

		// Load data
		$schema->routines = Routine::model()->findAll($criteria);

		// Render
		$this->render('routines', array(
			'schema' => $schema,
			'routineCount' => count($schema->routines),
			'pages' => $pages,
			'sort' => $sort,
		));
	}

	public function actionSql($_query = false, $_execute = true) {

		$query = Yii::app()->getRequest()->getParam('query');

		$browsePage = new BrowsePage();

		$browsePage->schema = $this->schema;
		$browsePage->db = $this->db;
		$browsePage->query = $query;
		$browsePage->route = 'schema/' . $this->schema . '/sql';
		$browsePage->formTarget = 'schema/' . $this->schema . '/sql';
		$browsePage->execute = (bool)$query;

		$browsePage->run();

		$this->render('../global/browse', array(
			'model' => $browsePage
		));

	}

	/**
	 * Create a new schema.
	 */
	public function actionCreate()
	{
		$schema = new Schema;
		if(isset($_POST['Schema']))
		{
			$schema->attributes = $_POST['Schema'];
			if($sql = $schema->save())
			{
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('core', 'successAddSchema', array('{schema}' => $schema->SCHEMA_NAME)),
					null,
					$sql);
				$response->refresh = true;
				$response->executeJavaScript('sideBar.loadSchemata()');
				$this->sendJSON($response);
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select'=> 'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		$this->render('form', array(
			'schema' => $schema,
			'collations' => $collations,
		));
	}

	/**
	 * Update a schema.
	 *
	 * @todo(mburtscher): Renaming. Requires copying the whole schema.
	 */
	public function actionUpdate()
	{
		$isSubmitted = false;
		$sql = null;
		$schema = $this->loadSchema();
		if(isset($_POST['Schema']))
		{
			$schema->attributes = $_POST['Schema'];
			if($sql = $schema->save())
			{
				$isSubmitted = true;
			}
		}

		$collations = Collation::model()->findAll(array(
			'order' => 'COLLATION_NAME',
			'select'=>'COLLATION_NAME, CHARACTER_SET_NAME AS collationGroup'
		));

		$this->render('form', array(
			'schema' => $schema,
			'collations' => $collations,
			'isSubmitted' => $isSubmitted,
			'sql' => $sql,
		));
	}

	/**
	 * Drop a schema.
	 */
	public function actionDrop()
	{
		$response = new AjaxResponse();
		$response->refresh = true;
		$response->executeJavaScript('sideBar.loadSchemata()');
		$schemata = (array)$_POST['schemata'];
		$droppedSchemata = $droppedSqls = array();

		Schema::$db = Yii::app()->getDb();

		foreach($schemata AS $schema)
		{
			$schemaObj = Schema::model()->findByPk($schema);
			$schemaObj->throwExceptions = true;
			try
			{
				$sql = $schemaObj->delete();
				$droppedSchemata[] = $schema;
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('core', 'errorDropSchema', array('{schema}' => $schema)),
					$ex->getText(),
					$ex->getSql());
			}
		}

		$count = count($droppedSchemata);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('core', 'successDropSchema', array($count, '{schema}' => $droppedSchemata[0], '{schemaCount}' => $count)),
				($count > 1 ? implode(', ', $droppedSchemata) : null),
				implode("\n", $droppedSqls));
		}

		$this->sendJSON($response);
	}

	/**
	 * Lists all schemata.
	 */
	public function actionList()
	{
		// Create list for sideBar usage
		if($this->request->getParam('sideBar'))
		{
			$schemata = array();

			foreach(Schema::model()->findAll() AS $schema)
			{
				$schemata[] = $schema->SCHEMA_NAME;
			}

			$this->sendJSON($schemata);
		}
		// Show the page
		else
		{
			$criteria = new CDbCriteria();

			// Pagination
			$pages = new Pagination(Schema::model()->count($criteria));
			$pages->setupPageSize('pageSize', 'schemata');
			$pages->applyLimit($criteria);
			$pages->route = '#schemata';

			// Sort
			$sort = new CSort('Schema');
			$sort->attributes = array(
				'name' => 'SCHEMA_NAME',
				'tableCount' => 'tableCount',
				'collation' => 'DEFAULT_COLLATION_NAME',
			);
			$sort->defaultOrder = 'SCHEMA_NAME ASC';
			$sort->route = '#schemata';
			$sort->applyOrder($criteria);

			$criteria->group = 'SCHEMA_NAME';
			$criteria->select = 'SCHEMA_NAME, DEFAULT_COLLATION_NAME, COUNT(*) AS tableCount';

			$schemaList = Schema::model()->with(array(
				'tables' => array('select' => 'COUNT(tables.TABLE_NAME) AS tableCount'),
			))->together()->findAll($criteria);

			$this->render('list',array(
				'schemaList' => $schemaList,
				'schemaCount' => $pages->getItemCount(),
				'schemaCountThisPage' => min($pages->getPageSize(), $pages->getItemCount() - $pages->getCurrentPage() * $pages->getPageSize()),
				'pages' => $pages,
				'sort' => $sort,
			));
		}
	}

	/**
	 * Loads the current schema.
	 *
	 * @return	Schema
	 */
	public function loadSchema()
	{
		if(is_null($this->_schema))
		{
			$this->_schema = Schema::model()->findByPk($this->schema);

			if(is_null($this->_schema))
			{
				throw new CHttpException(500, 'The requested schema does not exist.');
			}
		}
		return $this->_schema;
	}


	/**
	 * Bookmark actions
	 */
	public function actionShowBookmark()
	{

		$id = Yii::app()->getRequest()->getParam('id');
		$bookmark = Yii::app()->user->settings->get('bookmarks', 'database', $this->schema, 'id', $id);
		$query = Yii::app()->getRequest()->getParam('query');

		$browsePage = new BrowsePage();

		$browsePage->schema = $this->schema;
		$browsePage->db = $this->db;
		$browsePage->query = !$query ? $bookmark['query'] : $query;
		$browsePage->route = 'schema/' . $this->schema . '/bookmark/show/' . $id;
		$browsePage->formTarget = 'schema/' . $this->schema . '/bookmark/show/' . $id;
		$browsePage->execute = true;

		$browsePage->run();

		$this->render('../global/browse', array(
			'model' => $browsePage
		));

	}

	/**
	 * Shows the export page for this schema.
	 */
	public function actionExport()
	{
		$exportPage = new ExportPage('objects', $this->schema);
		$exportPage->run();

		$this->render('../global/export', array(
			'model' => $exportPage,
		));
	}

	public function actionImport()
	{

		$importPage = new ImportPage();
		$importPage->db = $this->db;
		$importPage->schema = $this->schema;
		$importPage->formTarget = Yii::app()->urlManager->baseUrl . '/schema/' . $this->schema . '/import';

		$res = $importPage->run();

		if($res instanceof AjaxResponse)
		{
			$this->sendJSON($res);
		}
		else
		{
			$this->layout = '_schema';

			$this->render('../global/import', array(
				'model' => $importPage,
			));
		}
	}

}