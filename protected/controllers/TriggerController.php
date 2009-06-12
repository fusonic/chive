<?php

class TriggerController extends Controller
{
	private static $delimiter = '//';

	public $trigger;
	public $table;
	public $schema;

	public $layout = false;

	public function __construct($id, $module=null)
	{
		$request = Yii::app()->getRequest();

		$this->trigger = $request->getParam('trigger');
		$this->table = $request->getParam('table');
		$this->schema = $request->getParam('schema');

		parent::__construct($id, $module);
		$this->connectDb($this->schema);
	}

	/**
	 * Creates a trigger.
	 */
	public function actionCreate()
	{
		$trigger = new Trigger();

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
					Yii::t('message', 'successAddTrigger'),
					null,
					$query);
				$response->refresh = true;
				$response->send();
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$trigger->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			}
		}
		else
		{
			$query = 'CREATE TRIGGER ' . $this->db->quoteTableName('name_of_trigger') . "\n"
				. '[AFTER|BEFORE] [INSERT|UPDATE|DELETE] ' . "\n"
				. 'ON ' . $this->db->quoteTableName($this->table) . ' FOR EACH ROW' . "\n"
				. 'BEGIN' . "\n"
				. '-- Definition start' . "\n\n"
				. '-- Definition end' . "\n"
				. 'END';
		}

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$this->render('form', array(
			'trigger' => $trigger,
			'query' => $query,
		));
	}

	/**
	 * Drops trigger.
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
	 * Updates a trigger.
	 */
	public function actionUpdate()
	{
		$trigger = Trigger::model()->findByPk(array(
			'TRIGGER_SCHEMA' => $this->schema,
			'TRIGGER_NAME' => $this->trigger,
		));
		if(is_null($trigger))
		{
			$trigger = new Trigger();
		}

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
					Yii::t('message', 'successAlterTrigger'),
					null,
					$query);
				$response->refresh = true;
				$response->send();
			}
			catch(CDbException $ex)
			{
				$errorInfo = $cmd->getPdoStatement()->errorInfo();
				$trigger->addError(null, Yii::t('message', 'sqlErrorOccured', array('{errno}' => $errorInfo[1], '{errmsg}' => $errorInfo[2])));
			}
		}
		else
		{
			$query = 'DROP TRIGGER ' . $this->db->quoteTableName($trigger->TRIGGER_NAME) . self::$delimiter . "\n"
				. $trigger->getCreateTrigger();
		}

		CHtml::$idPrefix = 'r' . substr(md5(microtime()), 0, 3);
		$this->render('form', array(
			'trigger' => $trigger,
			'query' => $query,
		));
	}

}