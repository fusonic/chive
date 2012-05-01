<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
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
class ImportPage extends CModel
{

	protected $view = 'upload';

	protected $file = '';
	protected $fileSize = 0;
	protected $mimeType;
	
	protected $finished = false;
	protected $chunkSize = 1048576;
	
	protected $timeLimit = 30;
	protected $position = 0;
	protected $totalExecutedQueries = 0;
	
	protected $ignoreErrorNumbers = array(
		1231,
	);

	public $schema;
	public $table;
	public $db;
	public $formTarget;
	public $fileUploadError = false;
	
	public $partialImport = false;
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
		@set_time_limit(0);
		
		if($timeLimit = ini_get('max_execution_time'))
		{
			$this->timeLimit = (int)$timeLimit;	
		}
		
		$this->partialImport = isset($_POST['ImportPage']['partialImport']) && $_POST['ImportPage']['partialImport'];
		
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
	 * Runs the ExportPage decides wether to show form or do export.
	 */
	public function run()
	{
		// Form got submitted
		if(isset($_POST['Import']))
		{
			// Check if file was valid
			if($_FILES['file']['error'] == UPLOAD_ERR_OK)
			{
				$this->view = 'form';
				$this->file = 'protected/runtime/' . $_FILES['file']['name'] . "_" . time();
				$this->fileSize = $_FILES['file']['size'];
				
				$this->mimeType = $_FILES['file']['type'];
				
				move_uploaded_file($_FILES['file']['tmp_name'], $this->file);
	
				if($this->partialImport)
				{
					// Redirect to postprocessing
					$this->view = 'submit';
				}
				else
				{
					// Run import in one step
					return $this->runImport();
				}
			}
			else
			{
				$this->view = 'form';
				$this->fileUploadError = true;
			}
		}
		
		// Import file via postprocessing
		elseif($this->partialImport || isset($_GET['position']))
		{
			$this->view = 'postprocessing';
			$this->file = $_GET['file'];
			$this->fileSize = $_GET['fileSize'];
			$this->mimeType = $_GET['type'];
			$this->position = $_GET['position'];
			$this->totalExecutedQueries = $_GET['totalExecutedQueries'];

			return $this->runPostProcessing();
			
		}
		
		// Display default form
		else
		{
			$this->view = 'form';
		}
		
	}
	
	/**
	 * Performs the actual export functions.
	 */
	private function runPostProcessing()
	{
		$response = new AjaxResponse();
		
		$this->mimeType = CFileHelper::getMimeType($this->file);
		$this->mimeType = substr($this->mimeType, 0, strpos($this->mimeType, ";"));
		
		$sqlSplitter = new SqlSplitter();
		$sqlSplitter->ignoreLastQuery = true;
		
		$readingBuffer = '';
		$queryCount = 0;
		
		$chunkSize = $this->chunkSize;
		
		
		// Open file and set position to last position
		switch($this->mimeType)
		{
			// GZip - Files
			case 'application/x-gzip':
				$handle = gzopen($this->file, 'r');
				gzseek($handle, $this->position, SEEK_SET);
				
				while(!gzeof($handle))
				{
					$readingBuffer .= gzread($handle, $chunkSize);
					$queryCount = count($sqlSplitter->getQueries($readingBuffer));
					
					$chunkSize *= 2;
					
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
					
					$temp = bzread($handle, $chunkSize);
					
					if($temp !== false)
					{
						$readingBuffer .= $temp;
					}
					
					$chunkSize *= 2;
						
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
					
					$temp = fread($handle, $chunkSize);
					#$encoding = mb_detect_encoding($temp);
					
					$readingBuffer .= $temp;
					$queryCount = count($sqlSplitter->getQueries($readingBuffer));
					
					$chunkSize *= 2;
		
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
		
		// No more queries could be found 
		if($queryCount > 0)
		{
			$newPosition = $this->position + $sqlSplitter->getPosition();
			
			// Calculate end time
			$end = microtime(true) + $this->timeLimit;
			
			$executedQueries = 0;
			while($executedQueries < $queryCount)
			{
				try 
				{
					$cmd = $this->db->createCommand($queries[$executedQueries]);
					$cmd->execute();
					
					if($executedQueries === 0)
					{
						$response->executeJavaScript('sideBar.loadTables("' . $this->schema . '")');
					}
					
				}
				catch(CDbException $ex)
				{
					$dbException = new DbException($cmd);

					$response->addData('error', true);
					$response->addNotification('error', $dbException->getText(), '',  $queries[$executedQueries]);
					
				}
				
				/*
				if(YII_DEBUG)
				{
					$response->addData('error', true);
					$response->addNotification('success', Yii::t('core', 'successExecuteQuery'), '', $queries[$executedQueries]);
				}*/
				
				
				$executedQueries++;
				$this->totalExecutedQueries++;
				
				// If partial import is activated, break current transaction
				if(microtime(true) > $end )
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
					$notExecutedCharCount += $sqlSplitter->getQueryLength($i+1);
				}
				
				$newPosition -= $notExecutedCharCount;
	
			}
		} 
		else
		{
			$response->refresh = true;
			$this->finished = true;	
			$response->addNotification('success', Yii::t('core','successImportFile'), Yii::t('core', 'executedQueries') . ": " . $this->totalExecutedQueries);
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
			'totalExecutedQueries' => $this->totalExecutedQueries
		);
		
		$response->addData(null, $data);

		return $response;
	}
	
	public function runImport()
	{
		$response = new AjaxResponse();
		$response->refresh = true;
		$response->executeJavaScript('sideBar.loadTables("' . $this->schema . '")');
		
		$this->mimeType = CFileHelper::getMimeType($this->file);
		$filesize = filesize($this->file);

		// Open file and set position to last position
		switch($this->mimeType)
		{
			// GZip - Files
			case 'application/x-gzip':
				$handle = gzopen($this->file, 'r');
				$content = gzread($handle, $filesize);
				gzclose($handle);
				break;
				
			// BZip - Files	
			case 'application/x-bzip2':
				$handle = bzopen($this->file, 'r');
				$content = bzread($handle, $filesize);
				bzclose($handle);
				break;
			
			// All other files (plain text)	
			default:
				$content = file_get_contents($this->file);
				break;
				
		}

		$sqlSplitter = new SqlSplitter($content);
		$queries = $sqlSplitter->getQueries();
		
		foreach($queries AS $query)
		{
			try 
			{
				$cmd = $this->db->createCommand($query);
				# Do NOT prepare the statement, because of double quoting
				$cmd->execute();
				
			}
			catch(CDbException $ex)
			{
				
				$dbException = new DbException($cmd);
				
				if(!in_array(@$dbException->getNumber(), $this->ignoreErrorNumbers))
				{
					$dbException = new DbException($cmd);
					$response->addNotification('error', Yii::t('core', 'errorExecuteQuery'), $dbException->getText() . '  ' . $dbException->getNumber(), StringUtil::cutText($dbException->getSql(), 100));
					$response->addData('error', true);
					$response->refresh = true;
					@unlink($this->file);
					return $response;
				}
				
			}
			
		}
		
		$response->addNotification('success', Yii::t('core','successImportFile'), Yii::t('core', 'executedQueries') . ":" . count($queries));
		
		// We cannot output json here, see: http://jquery.malsup.com/form/#file-upload
		Yii::app()->end($response);		
	}
	
	public function getPosition()
	{
		return $this->position;
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