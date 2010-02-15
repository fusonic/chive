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

	/**
	 * @see		IExporter::__construct()
	 */
	public function __construct($mode)
	{
		$this->mode = $mode;
	}

	/**
	 * @see		IExporter::getSettingsView()
	 */
	public function getSettingsView()
	{
		return 'CSV export settings go here ...';
	}

	/**
	 * @see		IExporter::calculateStepCount()
	 */
	public function calculateStepCount()
	{
		// We're currently only supporting one-step-exports ...
		$this->stepCount = 1;
		return $this->stepCount;
	}

	/**
	 * @see		IExporter::getStepCount()
	 */
	public function getStepCount()
	{
		return $this->stepCount;
	}

	/**
	 * @see		IExporter::setItems()
	 */
	public function setItems(array $items, $schema = null)
	{
		$this->items = $items;
		$this->schema = $schema;
	}

	/**
	 * @see		IExporter::runStep()
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
	 * @see		IExporter::getResult()
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * @see		IExporter::getSupportedModes()
	 */
	public static function getSupportedModes()
	{
		return array('objects');
	}

	/**
	 * @see		IExporter::getTitle()
	 */
	public static function getTitle()
	{
		return 'CSV';
	}

}