<?php

class BookmarkController extends Controller
{

	public $schema;

	private $_db;

	public function __construct() {

		$this->layout = false;

		$request = Yii::app()->getRequest();
		$this->schema = $request->getParam('schema');

		$this->_db = new CDbConnection('mysql:host='.Yii::app()->user->host.';dbname=' . $this->schema, Yii::app()->user->name, Yii::app()->user->password);
		$this->_db->charset='utf8';
		$this->_db->active = true;

		parent::__construct($id, $module);


	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('deny',
					'users'=>array('?'),
			),
		);
	}

	/**
	 * Add a bookmark
	 */
	public function actionAdd()
	{

		$response = new AjaxResponse();

		$name = $_POST['name'];
		$schema = $_POST['schema'];
		$query = $_POST['query'];

		$oldValue = Yii::app()->user->settings->get('bookmarks', 'database', $schema);


		$exists = (bool)Yii::app()->user->settings->get('bookmarks', 'database', $schema, 'name', $name);

		if($exists)
		{
			$response->addNotification('error', Yii::t('message', 'errorBookmarkWithThisNameAlreadyExists'));
			$response->send();
		}

		if($oldValue && !is_array($oldValue))
		{
			$oldValue = (array)$oldValue;
		}

		$id = substr(md5(microtime(true)),0, 10);

		$oldValue[] = array(
			'id' => $id,
			'name' => $name,
			'query' => $query,
		);

		Yii::app()->user->settings->set('bookmarks', $oldValue, 'database', $schema);
		Yii::app()->user->settings->saveSettings();

		$response->addNotification('success', Yii::t('message', 'successAddBookmark', array('{name}'=>$name)));

		$response->addData(null, array(
			'id' => $id,
			'schema' => $this->schema,
			'name' => $name,
			'query' => $query,
		));

		$response->send();

	}

	/*
	 * Delete a bookmark
	 */
	public function actionDelete()
	{

		$response = new AjaxResponse();

		$id = Yii::app()->getRequest()->getParam('id');
		$schema = Yii::app()->getRequest()->getParam('schema');

		$bookmarks = Yii::app()->user->settings->get('bookmarks', 'database', $schema);

		foreach($bookmarks AS $key=>$bookmark)
		{
			if($bookmark['id'] == $id) {
				$name = $bookmark['name'];
				unset($bookmarks[$key]);
			}
		}

		Yii::app()->user->settings->set('bookmarks', $bookmarks, 'database', $schema);
		Yii::app()->user->settings->saveSettings();

		$response->addNotification('success', Yii::t('message', 'successDeleteBookmark', array('{name}'=>$name)));
		$response->send();

	}

	/*
	 * Execute a bookmark
	 */
	public function actionExecute()
	{

		$id = Yii::app()->getRequest()->getParam('id');

		$response = new AjaxResponse();
		$response->reload = true;

		$bookmark = Yii::app()->user->settings->get('bookmarks', 'database', $this->schema, 'id', $id);

		try
		{
			$cmd = new CDbCommand($this->_db, $bookmark['query']);
			$cmd->execute();
			$response->addNotification('success', Yii::t('message', 'successExecuteBookmark', array('{name}'=>$bookmark['name'])), null, $bookmark['query']);
		}
		catch (Exception $ex)
		{
			$response->addNotification('error', $ex->getMessage(), $bookmark['query'], array('isSticky'=>true));
			Yii::app()->end($response);
		}

		Yii::app()->end($response);

	}

}