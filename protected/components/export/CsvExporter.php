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
		'fieldTerminator' => ';',
		'fieldEncloseString' => '"',
		'fieldEscapeString' => '\\',
		'fieldsFirstRow' => true,
		'rowsPerInsert' => 1000,		// Specifies the number of rows per INSERT statement
		'hexBlobs' => true				// Use HEX for blob fields
	);
	private $stepCount;

	private $table;
	private $rows = array();
	private $result;

	/**
	 * @see		IExporter::__construct()
	 */
	public function __construct($mode)
	{
		$this->mode = $mode;

		// Reload settings from request
		if($r = @$_REQUEST['Export']['settings']['CsvExporter'])
		{
			foreach($this->settings AS $key => $value)
			{
				if(is_bool($this->settings[$key]))
				{
					$this->settings[$key] = isset($r[$key]);
				}
				elseif(isset($r[$key]))
				{
					$this->settings[$key] = $r[$key];
				}
			}
		}
	}

	/**
	 * @see		IExporter::getSettingsView()
	 */
	public function getSettingsView()
	{
		$r = '';

		// Structure
		$r .= '<fieldset>';
		$r .= CHtml::label(Yii::t('core', 'separateFieldsBy'), 'Export_settings_CsvExporter_fieldTerminator'). ' ';
		$r .= " " . CHtml::textField("Export[settings][CsvExporter][fieldTerminator]", $this->settings["fieldTerminator"]) . '<br />';
		
		$r .= CHtml::label(Yii::t('core', 'encloseFieldsBy'), 'Export_settings_CsvExporter_fieldEncloseString'). ' ';
		$r .= " " . CHtml::textField("Export[settings][CsvExporter][fieldEncloseString]", $this->settings["fieldEncloseString"]) . '<br />';
		
		$r .= CHtml::label(Yii::t('core', 'escapeFieldTextDelimiterWith'), 'Export_settings_CsvExporter_fieldEscapeString'). ' ';
		$r .= " " . CHtml::textField("Export[settings][CsvExporter][fieldEscapeString]", $this->settings["fieldEscapeString"]) . '<br />';
		
		$r .= CHtml::checkBox('Export[settings][CsvExporter][fieldsFirstRow]', $this->settings['fieldsFirstRow']) . ' ';
		$r .= CHtml::label(Yii::t('core', 'fieldNamesInFirstRow'), 'Export_settings_CsvExporter_fieldsFirstRow') . '<br />';
		
		$r .= CHtml::checkBox('Export[settings][CsvExporter][hexBlobs]', $this->settings['hexBlobs']) . ' ';
		$r .= CHtml::label(Yii::t('core', 'useHexForBlob'), 'Export_settings_SqlExporter_hexBlobs') . '<br />';
		
		$r .= '</fieldset>';

		return $r;
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
	 * @see		IExporter::setItems()
	 */
	public function setRows(array $rows, $table = null, $schema = null)
	{
		$this->rows = $rows;
		$this->table = $table;
		$this->schema = $schema;
	}

	/**
	 * @see		IExporter::runStep()
	 */
	public function runStep($i, $collect = false)
	{
		if($collect)
		{
			ob_start();
		}

		switch($this->mode)
		{
			case 'objects':
				$this->exportObjects($i);
				break;
				
			case 'rows':
				$this->exportRows($i);
		}

		if($collect)
		{
			$this->result = ob_get_contents();
			ob_end_clean();
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
		return array('objects', 'rows');
	}

	/**
	 * @see		IExporter::getTitle()
	 */
	public static function getTitle()
	{
		return 'CSV';
	}


	/**
	 * Exports all specified database objects (tables, views, routines, ...).
	 *
	 * @return	boolean
	 */
	private function exportObjects()
	{
		// Find elements
		$tables = $views = $routines = array();
		if(count($this->items) > 0)
		{
			foreach($this->items AS $item)
			{
				switch($item{0})
				{
					case 't':
						$tables[] = substr($item, 2);
						break;
				}
			}
		}

		// Export everything
		if(count($tables) > 0)
		{
			$this->exportTables($tables);
		}
	}
	
	/**
	 * Exports (selected) rows
	 *
	 * @return	boolean
	 */
	private function exportRows()
	{		
		$this->exportRowData();
	}
	
	/**
	 * Exports all tables of the given array and writes the dump to the output buffer.
	 *
	 * @param	array					list of tables
	 */
	private function exportTables($tables)
	{
		// Get DbConnection object
		$db = Yii::app()->db;

		// Escape all table names
		$tableNames = array();
		foreach($tables AS $table)
		{
			$tableNames[] = Yii::app()->db->quoteValue($table);
		}

		// Find all tables
		$tables = Table::model()->findAll('TABLE_NAME IN (' . implode(',', $tableNames) . ') '
			. 'AND TABLE_SCHEMA = ' . Yii::app()->db->quoteValue($this->schema));

		foreach($tables AS $table)
		{
			$this->exportTableData($table);
		}
	}

	/**
	 * Exports data of the specified table and writes the sql dump to the output buffer.
	 *
	 * @param	string					name of table
	 */
	private function exportTableData($table)
	{
		$db = Yii::app()->db;
		$pdo = $db->getPdoInstance();

		// Columns
		$cols = Column::model()->findAllByAttributes(array(
			'TABLE_NAME' => $table->TABLE_NAME,
			'TABLE_SCHEMA' => $table->TABLE_SCHEMA,
		));
		$blobCols = array();

		$columns = array();
		$i = 0;
		foreach($cols AS $col)
		{
			$columns[] = $this->settings['fieldEncloseString'] . $col->COLUMN_NAME . $this->settings['fieldEncloseString'];
			if(in_array(DataType::getBaseType($col->DATA_TYPE), array('smallblob', 'blob', 'mediumblob', 'longblob')))
			{
				$blobCols[] = $i;
			}
			$i++;
		}
		$columns = implode($this->settings['fieldTerminator'], $columns);

		// Find all rows
		$sql = 'SELECT * FROM ' . Yii::app()->db->quoteTableName($this->schema) . '.' . Yii::app()->db->quoteTableName($table->TABLE_NAME);
		$statement = $pdo->query($sql);
		$statement->setFetchMode(PDO::FETCH_NUM);
		$rowCount = $statement->rowCount();

		// Settings
		$hexBlobs = $this->settings['hexBlobs'];

		// Cycle rows
		$i = 0;
		$k = 1;
		while($row = $statement->fetch())
		{
			if($i == 0 && $this->settings['fieldsFirstRow'])
			{
				echo $columns;
			}
			
			SqlUtil::FixRow($row);

			// Escape all contents
			foreach($row AS $key => $value)
			{
				if($value === null)
				{
					$row[$key] = 'NULL';
				}
				elseif($hexBlobs && in_array($key, $blobCols) && $value)
				{
					$row[$key] = '0x' . bin2hex($value);
				}
				else
				{
					$row[$key] = $this->settings['fieldEncloseString'] . addcslashes($value, $this->settings['fieldEncloseString']) . $this->settings['fieldEncloseString'];
				}
			}
			
			echo "\n", implode($this->settings['fieldTerminator'], $row);
			if($i == $rowCount - 1)
			{
				echo "\n\n";
			}
			$i++;
			$k++;
		}
	}
	
	/**
	 * Exports all rows of the given array and writes the dump to the output buffer.
	 *
	 * @param	array					array with identifiers of rows
	 */
	private function exportRowData()
	{
		$db = Yii::app()->db;
		$pdo = $db->getPdoInstance();

		// Columns
		$cols = Column::model()->findAllByAttributes(array(
			'TABLE_NAME' => $this->table,
			'TABLE_SCHEMA' => $this->schema,
		));
		$blobCols = array();

		$columns = array();
		$i = 0;
		foreach($cols AS $col)
		{
			$columns[] = $db->quoteColumnName($col->COLUMN_NAME);
			if(in_array(DataType::getBaseType($col->DATA_TYPE), array('smallblob', 'blob', 'mediumblob', 'longblob')))
			{
				$blobCols[] = $i;
			}
			$i++;
		}
		$columns = implode($this->settings['fieldTerminator'], $columns);
		
		$insert = "";

		// Find all rows
		$rowCount = count($this->rows);

		// Settings
		$hexBlobs = $this->settings['hexBlobs'];
		$rowsPerInsert = (int)$this->settings['rowsPerInsert'];

		// Cycle rows
		$i = 0;
		$k = 1;
		
		foreach($this->rows AS $row)
		{
			if($i == 0 && $this->settings['fieldsFirstRow'])
			{
				echo $columns;
			}
			
			$attributes = $row->getAttributes();
			SqlUtil::FixRow($attributes);

			// Escape all contents
			foreach($attributes AS $key => $value)
			{
				if($value === null)
				{
					$attributes[$key] = 'NULL';
				}
				elseif($hexBlobs && in_array($key, $blobCols) && $value)
				{
					$attributes[$key] = '0x' . bin2hex($value);
				}
				else
				{
					$attributes[$key] = $this->settings['fieldEncloseString'] . addcslashes($value, $this->settings['fieldEncloseString']) . $this->settings['fieldEncloseString'];
				}
			}

			echo "\n", implode($this->settings['fieldTerminator'], $attributes);
			if($i == $rowCount - 1)
			{
				echo "\n\n";
			}
			$i++;
			$k++;
		}
		
	}

	/**
	 * Writes a sql comment to the output buffer.
	 *
	 * @param	array					lines of comment
	 */
	private function comment($items)
	{
		$items = (array)$items;
		echo "-- \n";
		foreach($items AS $item)
		{
			echo '-- ', $item, "\n";
		}
		echo '-- ';
	}

}