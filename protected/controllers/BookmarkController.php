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


class BookmarkController extends Controller
{

	public $schema;

	public function __construct($id, $module = null)
	{
		$this->layout = false;

		$request = Yii::app()->getRequest();
		$this->schema = $request->getParam('schema');

		parent::__construct($id, $module);
		$this->connectDb($this->schema);
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
			$response->addNotification('error', Yii::t('core', 'errorBookmarkWithThisNameAlreadyExists', array("{name}" => $name)));
			$this->sendJSON($response);
		}

		if($oldValue && !is_array($oldValue))
		{
			$oldValue = array();
		}

		$id = substr(md5(microtime(true)),0, 10);

		$oldValue[] = array(
			'id' => $id,
			'name' => $name,
			'query' => $query,
		);

		Yii::app()->user->settings->set('bookmarks', $oldValue, 'database', $schema);
		Yii::app()->user->settings->saveSettings();

		$response->addNotification('success', Yii::t('core', 'successAddBookmark', array('{name}'=>$name)), null, $query);

		$response->addData(null, array(
			'id' => $id,
			'schema' => $this->schema,
			'name' => $name,
			'query' => $query,
		));

		$this->sendJSON($response);
	}

	/**
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

		$response->addNotification('success', Yii::t('core', 'successDeleteBookmark', array('{name}'=>$name)));
		$this->sendJSON($response);

	}

	/**
	 * Execute a bookmark
	 */
	public function actionExecute()
	{
		$id = Yii::app()->getRequest()->getParam('id');

		$response = new AjaxResponse();
		$response->refresh = true;

		$bookmark = Yii::app()->user->settings->get('bookmarks', 'database', $this->schema, 'id', $id);

		try
		{
			$cmd = new CDbCommand($this->db, $bookmark['query']);
			$cmd->execute();
			$response->addNotification('success', Yii::t('core', 'successExecuteBookmark', array('{name}'=>$bookmark['name'])), null, $bookmark['query']);
		}
		catch (Exception $ex)
		{
			$response->addNotification('error', $ex->getMessage(), $bookmark['query'], array('isSticky'=>true));
		}

		$this->sendJSON($response);
	}

}