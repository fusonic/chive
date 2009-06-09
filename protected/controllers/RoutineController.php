<?php

class RoutineController extends Controller
{
	private static $delimiter = '//';

	public $routine;
	public $schema;

	public $layout = false;

	public function __construct($id, $module=null)
	{
		$request = Yii::app()->getRequest();

		$this->routine = $request->getParam('routine');
		$this->schema = $request->getParam('schema');

		parent::__construct($id, $module);
		$this->connectDb($this->schema);
	}

	/**
	 * Creates a routine (procedure/function).
	 */
	public function actionCreate()
	{
		$routine = new Routine();
		$type = $_REQUEST['type'];

		if(isset($_POST['query']))
		{
			$query = $_POST['query'];
			$cmd = null;

			try
			{

				$cmd = $this->db->createCommand($query);
				$cmd->prepare();
				$cmd->execute();

				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('message', 'successAdd' . ucfirst($type)),
					null,
					$query);
				$response->refresh = true;
				$response->send();
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$routine->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			}
		}
		else
		{
			$query = 'CREATE ' . strtoupper($type) . ' ' . $this->db->quoteTableName('name_of_' . strtolower($type)) . "()\n"
				. ($type == 'function' ? 'RETURNS VARCHAR(50)' . "\n" : '')
				. 'BEGIN' . "\n"
				. '-- Definition start' . "\n\n"
				. '-- Definition end' . "\n"
				. 'END';
		}

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$this->render('form', array(
			'routine' => $routine,
			'query' => $query,
			'type' => $type,
		));
	}

	/**
	 * Drops routines.
	 */
	public function actionDrop()
	{
		$response = new AjaxResponse();
		$response->refresh = true;
		$routines = (array)$_POST['routines'];
		$droppedRoutines = $droppedSqls = array();

		foreach($routines AS $routine)
		{
			$routineObj = Routine::model()->findByPk(array(
				'ROUTINE_SCHEMA' => $this->schema,
				'ROUTINE_NAME' => $routine,
			));
			try
			{
				$sql = $routineObj->delete();
				$droppedRoutines[] = $routine;
				$droppedSqls[] = $sql;
			}
			catch(DbException $ex)
			{
				$response->addNotification('error',
					Yii::t('message', 'errorDropRoutine', array('{routine}' => $routineObj)),
					$ex->getText(),
					$ex->getSql());
			}
		}

		$count = count($droppedRoutines);
		if($count > 0)
		{
			$response->addNotification('success',
				Yii::t('message', 'successDropRoutine', array($count, '{routine}' => $droppedRoutines[0], '{routineCount}' => $count)),
				($count > 1 ? implode(', ', $droppedRoutines) : null),
				implode("\n", $droppedSqls));
		}

		$response->send();
	}

	/**
	 * Updates a routine.
	 */
	public function actionUpdate()
	{
		$routine = Routine::model()->findByPk(array(
			'ROUTINE_SCHEMA' => $this->schema,
			'ROUTINE_NAME' => $this->routine,
		));
		if(is_null($routine))
		{
			$routine = new Routine();
			$routine->ROUTINE_TYPE = $_POST['type'];
		}
		$type = strtolower($routine->ROUTINE_TYPE);

		if(isset($_POST['query']))
		{
			$query = $_POST['query'];
			try
			{
				// Split queries
				$splitter = new SqlSplitter($query);
				$splitter->delimiter = self::$delimiter;
				$queries = $splitter->getQueries();

				foreach($queries AS $query2)
				{
					$cmd = $this->db->createCommand($query2);
					$cmd->prepare();
					$cmd->execute();
				}

				$response = new AjaxResponse();
				$response->addNotification('success',
					Yii::t('message', 'successAlterRoutine', array('{routine}' => $routine->ROUTINE_NAME)),
					null,
					$query);
				$response->refresh = true;
				$response->send();
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$routine->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			}
		}
		else
		{
			$query = 'DROP ' . strtoupper($routine->ROUTINE_TYPE) . ' ' . $this->db->quoteTableName($routine->ROUTINE_NAME) . self::$delimiter . "\n"
				. $routine->getCreateRoutine();
		}

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$this->render('form', array(
			'routine' => $routine,
			'type' => $type,
			'query' => $query,
		));
	}

}