<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * ImportPage model
 * With this page you can implement imports
 */
class ImportPage extends CModel
{

	private $view = 'upload';

	private $file = '';
	private $fileSize = 0;
	private $mimeType;
	
	private $finished = false;
	private $chunkSize = 301215;
	
	private $timeLimit = 5;
	private $position = 0;
	private $totalExecutedQueries = 0;
	
	private $ignoreErrorNumbers = array(
		1231,
	);
	

	public $schema;
	public $table;
	public $db;
	public $formTarget;
	
	public $partialImport = true;
	public $fromCharacterSet = 'utf-8';
	
	private $characterSets;
	
	
	/**
	 * Constructor
	 *
	 * @param	string					mode (objects/schemata)
	 * @param	string					selected schema (when mode == objects)
	 */
	public function __construct()
	{
		$characterSets = CharacterSet::model()->findAll();
		foreach($characterSets AS $characterSet)
		{
			$this->characterSets[] = array(
				'name' => $characterSet->CHARACTER_SET_NAME,
				'title' => Yii::t('collation', $characterSet->CHARACTER_SET_NAME),
			);
		} 
	}

	/**
	 * @see		CModel::attributeNames()
	 */
	public function attributeNames()
	{
		return array(
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'partialImport' => Yii::t('core', 'partialImportDescription'), 
			'fromCharacterSet' => Yii::t('core', 'fromCharacterSetDescription'),
		);
	}

	/**
	 * @see		CModel::safeAttributes()
	 */
	public function safeAttributes()
	{
		return array();
	}

	/**
	 * Runs the ExportPage decides wether to show form or do export.
	 */
	public function run()
	{
		
		$response = array();
		
		if(isset($_POST['Import']))
		{
			$this->view = 'submit';
			
			$this->file = 'protected/runtime/' . $_FILES['file']['name'] . "_" . time();
			$this->fileSize = $_FILES['file']['size'];
			$this->mimeType = $_FILES['type'];
			
			move_uploaded_file($_FILES['file']['tmp_name'], $this->file);

		}
		elseif(isset($_GET['position']))
		{
			$this->view = 'postprocessing';
			$this->file = $_GET['file'];
			$this->fileSize = $_GET['fileSize'];
			$this->mimeType = $_GET['mimeType'];
			$this->position = $_GET['position'];
			$this->totalExecutedQueries = $_GET['totalExecutedQueries'];

			$this->runPostProcessing();
		}
		else
		{
			$this->view = 'form';
			$this->runForm();
		}
	}
	
	/**
	 * Performs the actual export functions.
	 */
	private function runPostProcessing()
	{
		$response = new AjaxResponse();
		
		$this->mimeType = CFileHelper::getMimeType($this->file);

		$sqlSplitter = new SqlSplitter();
		$sqlSplitter->ignoreLastQuery = true;
		
		$readingBuffer = '';
		$queryCount = 0;
		
		// Open file and set position to last position
		switch($this->mimeType)
		{
			// GZip - Files
			case 'application/x-gzip':
				$handle = gzopen($this->file, 'r');
				gzseek($handle, $this->position, SEEK_SET);
				
				while(!gzeof($handle))
				{
					$readingBuffer .= gzread($handle, $this->chunkSize);
					$queryCount = count($sqlSplitter->getQueries($readingBuffer));
					
					if($queryCount > 0)
					{
						$queries = $sqlSplitter->getQueries();
						break;
					}
				}
				
				gzclose($handle);
				break;
				
			// BZip - Files	
			case 'application/x-bzip2':
				$handle = bzopen($this->file, 'r');
				bzread($handle, $this->position);
				do {
					$temp = bzread($handle, $this->chunkSize);
					
					if($temp !== false)
						$readingBuffer .= $temp;
						
					$queryCount = count($sqlSplitter->getQueries($readingBuffer));
					if($queryCount > 0) 
					{
						$queries = $sqlSplitter->getQueries();
						break;
					}
					
				}
				while($temp);
				
				bzclose($handle);
				break;
			
			// All other files (plain text)	
			default:
				$handle = fopen($this->file, 'r');
				fseek($handle, $this->position, SEEK_SET);
				
				while(!feof($handle))
				{
					$temp = fread($handle, $this->chunkSize);
					#$encoding = mb_detect_encoding($temp);
					
					$readingBuffer .= $temp;
					
					$queryCount = count($sqlSplitter->getQueries($readingBuffer));
		
					// Skip loop when a complete query was found
					if($queryCount > 0) 
					{
						$queries = $sqlSplitter->getQueries();
						break;
					}
					
				}
				
				fclose($handle);
				break;
				
		}
		
		mysql_connect("localhost", "root", "");
		mysql_select_db("testing_ox");
		
		// No more queries could be found 
		if($queryCount > 0)
		{
			$names = $this->db->createCommand('SET NAMES utf8');
			$names->prepare();
			$names->execute();
			
			$newPosition = $this->position + $sqlSplitter->getPosition();
			
			// Calculate end time
			$end = microtime(true) + $this->timeLimit;
			
			$executedQueries = 0;
			while($executedQueries < $queryCount)
			{
				try 
				{
					#mysql_query($queries[$executedQueries]);
					
					$cmd = $this->db->createCommand($queries[$executedQueries]);
					$cmd->prepare(); 
					$cmd->execute();
				}
				catch(CDbException $ex)
				{
					
					if(!in_array($dbException->getNumber(), $this->ignoreErrorNumbers))
					{
						$dbException = new DbException($cmd);
						$response->addNotification('error', Yii::t('core', 'errorExecuteQuery'), $dbException->getText() . '  ' . $dbException->getNumber(), $dbException->getSql());
						$response->addData('error', true);
						$response->refresh = true;
						$response->executeJavaScript('sideBar.loadTables("' . $this->schema . '")');
					}
					
				}
				
				$executedQueries++;
				$this->totalExecutedQueries++;
				
				// If partial import is activated, break current transaction
				if($this->partialImport && microtime(true) > $end )
				{
					break;
				}
			}
			
			// Not all queries could be executed, rewind to last executed query position 
			if($queryCount > $executedQueries)
			{
				$notExecutedCharCount = 0;
				
				for($i = $executedQueries; $i < $queryCount; $i++)
				{
					$notExecutedCharCount += strlen($queries[$i]);
				}
	
				$newPosition -= $notExecutedCharCount;
			}
		} 
		else
		{
			$response->refresh = true;
			$this->finished = true;	
			$response->addNotification('success', Yii::t('core','successImportFile'), Yii::t('core', 'executedQueries') . ":" . $this->totalExecutedQueries);
			$response->executeJavaScript('sideBar.loadTables("' . $this->schema . '")');
			@unlink($this->file);
		}
		

		// Skip delimiter
		$this->position = $newPosition+1;
		
		$data = array(
			'position' => $this->position,
			'schema' => $this->schema,
			'file' => $this->file,
			'filesize' => $this->fileSize,
			'mimetype' => $this->mimeType,
			'finished' => $this->finished,
			'totalExecutedQueries' => $this->totalExecutedQueries,
			'queries' => $queries,
		);
		
		$response->addData(null,$data);
		$response->send();
		
	}
	
	public function getPosition()
	{
		return $this->position;
	}
	

	/**
	 * Runs the form.
	 */
	private function runForm()
	{


	}
	
	public function getView()
	{
		return $this->view;		
	}
	
	public function getCharacterSets()
	{
		return $this->characterSets;		
	}
	
	public function getFileSize()
	{
		return $this->fileSize;		
	}
	
	public function getFile()
	{
		return $this->file;		
	}
	
	public function getFinished()
	{
		return $this->finished;		
	}
	
	public function getTotalExecutedQueries()
	{
		return $this->totalExecutedQueries;		
	}
	
	public function getMaxFileSize()
	{
		return ConfigUtil::getMaxUploadSize(true);
	}
	

}