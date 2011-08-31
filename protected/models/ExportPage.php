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
class ExportPage extends CModel
{

	private $exporters;
	private $mode;
	private $objects;
	private $rows;
	private $selectedObjects = null;
	private $selectedRows = null;
	private $result;
	private $schema;
	private $table;
	private $view = 'form';
	private $compressionChunkSize = 8192;
	private $compression = null;

	/**
	 * Constructor
	 *
	 * @param	string					mode (objects/schemata)
	 * @param	string					selected schema (when mode == objects)
	 */
	public function __construct($mode, $schema = null, $table = null)
	{
		$this->mode = $mode;
		$this->schema = $schema;
		$this->table = $table;
	}

	/**
	 * @see		CModel::attributeNames()
	 */
	public function attributeNames()
	{
		return array();
	}

	/**
	 * Runs the ExportPage decides wether to show form or do export.
	 */
	public function run()
	{
		if(isset($_POST['Export']))
		{
			$this->view = 'result';

			// Check for compression
			if(isset($_POST['Export']['compression']) && $_POST['Export']['compression'])
			{
				$this->compression = $_POST['Export']['compression'];
			}

			$this->runSubmit();
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
	public function runSubmit()
	{
		// Initialize exporter
		$exporterName = $_POST['Export']['exporter'];
		$exporter = new $exporterName($this->mode);
		
		$extension = strtolower($exporter->getTitle());

		if(isset($_POST['Export']['objects']))
		{
			// Load items and assign to exporter
			$items = (array)$_POST['Export']['objects'];
			$exporter->setItems($items, $this->schema);
		}
		elseif(isset($_POST['Export']['rows']))
		{
			// Load rows and assign to exporter
			$rowAttributes = (array)CJSON::decode($_POST['Export']['rows'], true);
			$rows = array();

			foreach($rowAttributes AS $row)
			{
				$rows[] = Row::model()->findByAttributes($row);
			}

			$exporter->setRows($rows, $this->table, $this->schema);
		}

		// Calculate step count
		$exporter->calculateStepCount();

		// If it was not an ajax request, we have to serve the file for download
		if(!Yii::app()->getRequest()->isAjaxRequest)
		{
			if($this->compression == 'gzip' && function_exists('gzencode'))
			{
				$mimeType = 'application/x-gzip';
				$filenameSuffix = '.gz';
			}
			elseif($this->compression == 'bzip2' && function_exists('bzcompress'))
			{
				$mimeType = 'application/x-bzip2';
				$filenameSuffix = '.bz2';
			}
			else
			{
				$mimeType = 'text/plain';
				$filenameSuffix = '';
			}
			
			$filename = $this->schema . "_" . date("Y_m_d");

			// Send headers
			header('Content-type: ' . $mimeType);
			header('Content-disposition: attachment; filename="' . $filename . "." . $extension . $filenameSuffix . '"');

			// Set handlers
			if($this->compression == 'gzip' && function_exists('gzencode'))
			{
				ob_start(array('ExportPage', 'gzEncode'), $this->compressionChunkSize);
			}
			elseif($this->compression == 'bzip2' && function_exists('bzcompress'))
			{
				ob_start(array('ExportPage', 'bz2Encode'), $this->compressionChunkSize);
			}

			$collect = false;
		}
		else
		{
			$collect = true;
		}

		// Disable XDebug
		if(function_exists('xdebug_disable'))
		{
			@xdebug_disable();
		}
		// Time limit 0
		@set_time_limit(0);

		// Run step 0, we only support 1-step-expots by now
		$exporter->runStep(0, $collect);

		// Die after file output when downloading ...
		if(!$collect)
		{
			ob_end_flush();
			die();
		}

		// Save result
		$this->result = $exporter->getResult();
	}

	/**
	 * Runs the form.
	 */
	private function runForm()
	{
		// @todo: Load all exporters
		$exporterNames = array('SqlExporter', 'CsvExporter');

		// Instantiate supported exporters
		$this->exporters = array();
		foreach($exporterNames AS $exporter)
		{
			$supported = call_user_func(array($exporter, 'getSupportedModes'));
			if(in_array($this->mode, $supported))
			{
				$this->exporters[] = new $exporter($this->mode);
			}
		}

		// Create the object list
		$this->objects = array();
		if($this->mode == 'objects')
		{
			// Load schema
			$schema = Schema::model()->findByPk($this->schema);

			// Tables
			$tables = $schema->tables;
			if(count($tables) > 0)
			{
				$data = array();
				foreach($tables AS $table)
				{
					$data['t:' . $table->TABLE_NAME] = $table->TABLE_NAME;
				}
				$this->objects[Yii::t('core', 'tables')] = $data;
			}

			// Views
			$views = $schema->views;
			if(count($views) > 0)
			{
				$data = array();
				foreach($views AS $view)
				{
					$data['v:' . $view->TABLE_NAME] = $view->TABLE_NAME;
				}
				$this->objects[Yii::t('core', 'views')] = $data;
			}

			// Routines
			$routines = $schema->routines;
			if(count($routines) > 0)
			{
				$data = array();
				foreach($routines AS $routine)
				{
					$data['r:' . $routine->ROUTINE_NAME] = $routine->ROUTINE_NAME;
				}
				$this->objects[Yii::t('core', 'routines')] = $data;
			}
		}

	}

	/**
	 * Returns all selectable objects.
	 *
	 * @return	array
	 */
	public function getObjects()
	{
		return $this->objects;
	}

	/**
	 * Returns all keys of selectable objects.
	 *
	 * @return	array
	 */
	public function getObjectKeys()
	{
		$keys = array();
		foreach($this->objects AS $key => $value)
		{
			if(is_array($value))
			{
				foreach($value AS $key2 => $value2)
				{
					$keys[] = $key2;
				}
			}
			else
			{
				$keys[] = $key;
			}
		}
		return $keys;
	}

	/**
	 * Returns keys of all selected objects.
	 *
	 * @return	array
	 */
	public function getSelectedObjects()
	{
		if($this->selectedObjects)
		{
			return $this->selectedObjects;
		}
		else
		{
			return $this->getObjectKeys();
		}
	}

	/**
	 * Sets the selected object keys.
	 *
	 * @param	mixed					selected objects
	 */
	public function setSelectedObjects($objects)
	{
		if($objects)
		{
			$this->selectedObjects = (array)$objects;
		}
		else
		{
			$this->selectedObjects = null;
		}
	}

	/**
	 * Sets the chosen row attributes.
	 *
	 * @param	mixed					row attributes
	 */
	public function setRows($rows)
	{
		$this->rows = (array)$rows;
	}

	/**
	 * Gets the chosen row attributes.
	 *
	 */
	public function getRows()
	{
		return $this->rows;
	}

	/**
	 * Returns all exporter names.
	 *
	 * @return	array
	 */
	public function getExporters()
	{
		$data = array();
		foreach($this->exporters AS $exporter)
		{
			$data[get_class($exporter)] = call_user_func(array(get_class($exporter), 'getTitle'));
		}
		return $data;
	}

	/**
	 * Returns all exporter instances.
	 *
	 * @return	array
	 */
	public function getExporterInstances()
	{
		return $this->exporters;
	}

	/**
	 * Returns the current view type.
	 *
	 * @return	string
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * Returns the export result.
	 *
	 * @return	string
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * Callback for output handler to "gzencode".
	 *
	 * @param	string					content to encode
	 * @return	string					encoded content
	 */
	public static function gzEncode($content)
	{
		return gzencode($content, 1);
	}

	/**
	 * Callback for output handler to "bzcompress".
	 *
	 * @param	string					content to encode
	 * @return	string					encoded content
	 */
	public static function bz2Encode($content)
	{
		return bzcompress($content);
	}

}