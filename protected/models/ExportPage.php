<?php

/**
 * ExportPage model
 * With this page you can implement exports for either schemata, objects or data.
 */
class ExportPage extends CModel
{

	private $exporters;
	private $mode;
	private $objects;
	private $result;
	private $schema;
	private $view = 'form';

	public function __construct($mode, $schema = null)
	{
		$this->mode = $mode;
		$this->schema = $schema;
	}

	public function attributeNames()
	{
		return array();
	}

	public function safeAttributes()
	{
		return array();
	}

	public function run()
	{
		if(isset($_POST['Export']))
		{
			$this->view = 'result';
			$this->runSubmit();
		}
		else
		{
			$this->view = 'form';
			$this->runForm();
		}
	}

	public function runSubmit()
	{
		// Initialize exporter
		$exporterName = $_POST['Export']['exporter'];
		$exporter = new $exporterName($this->mode);

		// Load items and assign to exporter
		$items = (array)$_POST['Export']['objects'];
		$exporter->setItems($items, $this->schema);

		// Calculate step count
		$exporter->calculateStepCount();

		// If it was not an ajax request, we have to serve the file for download
		if(!Yii::app()->getRequest()->isAjaxRequest)
		{
			header('Content-type: text/plain');
			header('Content-disposition: attachment; filename="Dump.sql"');
			ini_set('output_buffering', false);
			ini_set('implicit_flush', true);
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
			die();
		}

		// Save result
		$this->result = $exporter->getResult();
	}

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
				$this->objects[Yii::t('database', 'tables')] = $data;
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
				$this->objects[Yii::t('database', 'views')] = $data;
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
				$this->objects[Yii::t('database', 'routines')] = $data;
			}
		}
	}

	public function getObjects()
	{
		return $this->objects;
	}

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

	public function getExporters()
	{
		$data = array();
		foreach($this->exporters AS $exporter)
		{
			$data[get_class($exporter)] = call_user_func(array(get_class($exporter), 'getTitle'));
		}
		return $data;
	}

	public function getExporterInstances()
	{
		return $this->exporters;
	}

	public function getView()
	{
		return $this->view;
	}

	public function getResult()
	{
		return $this->result;
	}

}