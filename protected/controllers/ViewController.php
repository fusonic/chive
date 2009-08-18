<?php

class ViewController extends TableController
{
	const PAGE_SIZE = 10;
	private static $delimiter = '//';

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_view;

	public $view;
	public $table;
	public $schema;

	public $isSent = false;

	/**
	 * @var Default layout for this controller
	 */
	public $layout = '_view';

	public function __construct($id, $module=null) {

		$request = Yii::app()->getRequest();

		$this->view = $request->getParam('view');
		$this->schema = $request->getParam('schema');
		
		parent::__construct($id, $module);
		
		$this->table = $this->view;
		$this->connectDb($this->schema);
	}

	/**
	 * Shows the table structure
	 */
	public function actionStructure()
	{
		$view = $this->loadView();

		$this->render('structure',array(
			'view' => $view,
		));
	}

	/**
	 * Creates a view.
	 */
	public function actionCreate()
	{
		$this->layout = false;

		$view = new View();

		if(isset($_POST['query']))
		{
			$query = $_POST['query'];

			try
			{
				$cmd = $this->db->createCommand($query);
				$cmd->prepare();
				$cmd->execute();

				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('message', 'successAddView'),
					null,
					$query);
				$response->refresh = true;
				$response->send();
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$view->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			}
		}
		else
		{
			$query = 'CREATE VIEW ' . $this->db->quoteTableName('name_of_view') . ' AS' . "\n"
				. '-- Definition start' . "\n\n"
				. '-- Definition end';
		}

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$this->render('form', array(
			'view' => $view,
			'query' => $query,
		));
	}

	/**
	 * Drops views.
	 */
	public function actionDrop()
	{
		$response = new AjaxResponse();
		$response->refresh = true;
		$views = (array)$_POST['views'];
		$droppedViews = $droppedSqls = array();

		foreach($views AS $view)
		{
			$viewObj = View::model()->findByPk(array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $view,
			));
			try
			{
				$sql = $viewObj->delete();
				$droppedViews[] = $view;
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('message', 'errorDropView', array('{view}' => $view)),
					$ex->getText(),
					$ex->getSql());
			}
		}

		$count = count($droppedViews);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('message', 'successDropView', array($count, '{view}' => $droppedViews[0], '{viewCount}' => $count)),
				($count > 1 ? implode(', ', $droppedViews) : null),
				implode("\n", $droppedSqls));
		}

		$response->send();
	}

	/**
	 * Updates a view.
	 */
	public function actionUpdate()
	{
		$this->layout = false;

		$view = View::model()->findByPk(array(
			'TABLE_SCHEMA' => $this->schema,
			'TABLE_NAME' => $this->view,
		));

		if(isset($_POST['query']))
		{
			$query = $_POST['query'];
			$cmd = $this->db->createCommand($query);
			try
			{
				$cmd->prepare();
				$cmd->execute();
				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('message', 'successAlterView', array('{view}' => $view->TABLE_NAME)),
					null,
					$query);
				$response->refresh = true;
				$response->send();
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$view->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			}
		}
		else
		{
			$query = $view->getAlterView();
		}

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$this->render('form', array(
			'view' => $view,
			'query' => $query,
		));
	}

	/**
	 * Loads the current view.
	 *
	 * @return	View
	 */
	public function loadView()
	{
		if(is_null($this->_view))
		{
			$pk = array(
				'TABLE_SCHEMA' => $this->schema,
				'TABLE_NAME' => $this->view,
			);
			$this->_view = View::model()->findByPk($pk);
			$this->_view->columns = Column::model()->findAllByAttributes($pk);

			if(is_null($this->_view))
			{
				throw new CHttpException(500, 'The requested view does not exist.');
			}
		}
		return $this->_view;
	}

}