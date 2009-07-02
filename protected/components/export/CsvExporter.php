<?php

class CsvExporter implements IExporter
{
	private $items = array();
	private $mode;
	private $schema;
	private $settings = array(
		'addDropObject' => true,		// Adds DROP TABLE statement
		'addIfNotExists' => true,		// Adds IF NOT EXISTS to CREATE TABLE statement
		'completeInserts' => true,		// Adds column names to insert statement
		'ignoreInserts' => false,		// Adds IGNORE to insert statement (INSERT IGNORE ...)
		'insertCommand' => 'INSERT',	// Specifies the command for data (INSERT/REPLACE)
		'rowsPerInsert' => 1000,		// Specifies the number of rows per INSERT statement
	);
	private $stepCount;

	private $result;

	public function __construct($mode)
	{
		$this->mode = $mode;
	}

	/**
	 * @see		IExport::getSettingsView()
	 */
	public function getSettingsView()
	{
		return 'CSV export settings go here ...';
	}

	/**
	 * @see		IExport::calculateStepCount()
	 */
	public function calculateStepCount()
	{
		// We're currently only supporting one-step-exports ...
		$this->stepCount = 1;
		return $this->stepCount;
	}

	/**
	 * @see		IExport::getStepCount()
	 */
	public function getStepCount()
	{
		return $this->stepCount;
	}

	/**
	 * @see		IExport::setItems()
	 */
	public function setItems(array $items, $schema = null)
	{
		$this->items = $items;
		$this->schema = $schema;
	}

	/**
	 * @see		IExport::runStep()
	 */
	public function runStep($i, $collect = false)
	{
		switch($this->mode)
		{
			case 'objects':
				return $this->exportObjects($i);
			default:
				return false;
		}
	}

	/**
	 * @see		IExport::getResult()
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * @see		IExport::getSupportedModes()
	 */
	public static function getSupportedModes()
	{
		return array('objects');
	}

	/**
	 * @see		IExport::getTitle()
	 */
	public static function getTitle()
	{
		return 'CSV';
	}

}