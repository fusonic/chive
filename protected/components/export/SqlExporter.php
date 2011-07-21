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


class SqlExporter implements IExporter
{
	
	private $items = array();
	private $rows = array();
	private $mode;
	private $schema;
	private $table;
	private $settings = array(
		'addDropObject' => true,		// Adds DROP TABLE statement
		'addIfNotExists' => true,		// Adds IF NOT EXISTS to CREATE TABLE statement
		'completeInserts' => true,		// Adds column names to insert statement
		'exportStructure' => true,		// Export structure
		'exportTriggers' => true,		// Exporter triggers
		'exportData' => true,			// Export data
		'ignoreInserts' => false,		// Adds IGNORE to insert statement (INSERT IGNORE ...)
		'delayedInserts' => false,		// Adds DELAYED to insert statement (INSERT DELAYED ...)
		'insertCommand' => 'INSERT',	// Specifies the command for data (INSERT/REPLACE)
		'rowsPerInsert' => 1000,		// Specifies the number of rows per INSERT statement
		'hexBlobs' => true,				// Use HEX for blob fields
	);
	private $stepCount;

	private $result;

	/**
	 * @see		IExporter::__construct()
	 */
	public function __construct($mode)
	{
		$this->mode = $mode;

		// Reload settings from request
		if($r = @$_REQUEST['Export']['settings']['SqlExporter'])
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

		$r .= '<legend>'
			. CHtml::checkBox('Export[settings][SqlExporter][exportStructure]', $this->settings['exportStructure']) . ' '
			. CHtml::label(Yii::t('core', 'exportStructure'), 'Export_settings_SqlExporter_exportStructure')
			. '</legend>';

		$r .= CHtml::checkBox('Export[settings][SqlExporter][addDropObject]', $this->settings['addDropObject']) . ' '
			. CHtml::label(Yii::t('core', 'addDropObject'), 'Export_settings_SqlExporter_addDropObject') . '<br />'
			. CHtml::checkBox('Export[settings][SqlExporter][addIfNotExists]', $this->settings['addIfNotExists']) . ' '
			. CHtml::label(Yii::t('core', 'addIfNotExists'), 'Export_settings_SqlExporter_addIfNotExists');

		$r .= '</fieldset>';

		// Data
		$r .= '<fieldset>';

		$r .= '<legend>'
			. CHtml::checkBox('Export[settings][SqlExporter][exportData]', $this->settings['exportData']) . ' '
			. CHtml::label(Yii::t('core', 'exportData'), 'Export_settings_SqlExporter_exportData')
			. '</legend>';

		$r .= CHtml::label(Yii::t('core', 'command'), 'Export_settings_SqlExporter_insertCommand') . ': '
			. CHtml::radioButtonList('Export[settings][SqlExporter][insertCommand]', $this->settings['insertCommand'], array(
					'INSERT' => 'INSERT',
					'REPLACE' => 'REPLACE',
				), array('separator' => ' &nbsp; ')) . '<br />'
			. CHtml::label(Yii::t('core', 'rowsPerInsert'), 'Export_settings_SqlExporter_rowsPerInsert') . ': '
			. CHtml::textField('Export[settings][SqlExporter][rowsPerInsert]', $this->settings['rowsPerInsert']) . '<br />'
			. CHtml::checkBox('Export[settings][SqlExporter][completeInserts]', $this->settings['completeInserts']) . ' '
			. CHtml::label(Yii::t('core', 'useCompleteInserts'), 'Export_settings_SqlExporter_completeInserts') . '<br />'
			. CHtml::checkBox('Export[settings][SqlExporter][ignoreInserts]', $this->settings['ignoreInserts']) . ' '
			. CHtml::label(Yii::t('core', 'useInsertIgnore'), 'Export_settings_SqlExporter_ignoreInserts') . '<br />'
			. CHtml::checkBox('Export[settings][SqlExporter][delayedInserts]', $this->settings['delayedInserts']) . ' '
			. CHtml::label(Yii::t('core', 'useDelayedInserts'), 'Export_settings_SqlExporter_delayedInserts') . '<br />'
			. CHtml::checkBox('Export[settings][SqlExporter][hexBlobs]', $this->settings['hexBlobs']) . ' '
			. CHtml::label(Yii::t('core', 'useHexForBlob'), 'Export_settings_SqlExporter_hexBlobs') . '<br />';

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
		return 'SQL';
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
					case 'v':
						$views[] = substr($item, 2);
						break;
					case 'r':
						$routines[] = substr($item, 2);
						break;
						
				}
			}
		}

		// Export everything
		if(count($tables) > 0)
		{
			$this->exportTables($tables);
		}
		if(count($views) > 0 && $this->settings['exportStructure'])
		{
			$this->exportViews($views);
		}
		if(count($routines) > 0 && $this->settings['exportStructure'])
		{
			$this->exportRoutines($routines);
		}
	}
	
	/**
	 * Exports (selected) rows
	 *
	 * @return	boolean
	 */
	private function exportRows()
	{
		if($this->settings['exportStructure'])
		{
			$table = Table::model()->findByPk(array('TABLE_SCHEMA' => $this->schema, 'TABLE_NAME' => $this->table));
			$this->exportTableStructure($table);
		}
		
		$this->exportRowData();
	}
	
	/**
	 * Exports all tables of the given array and writes the dump to the output buffer.
	 * @todo	constraints
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
		$allTables = Table::model()->findAll('TABLE_SCHEMA = ' . Yii::app()->db->quoteValue($this->schema));

		if(count($tables) > 0)
		{
			$filteredTables = array();
			foreach($allTables as $table)
			{
				if(in_array($table->TABLE_NAME, $tables))
				{
					$filteredTables[] = $table;
				}
			}
		}
		else
		{
			$filteredTables = $allTables;	
		}
		
		foreach($filteredTables AS $table)
		{
			
			if($this->settings['exportStructure'])
			{
				$this->exportTableStructure($table);
			}

			if($this->settings['exportData'])
			{
				// Data
				$this->exportTableData($table);
			}
		}
	}
	
	private function exportTableStructure($table)
	{

		// Get DbConnection object
		$db = Yii::app()->db;

		$this->comment('Structure for table ' . $db->quoteTableName($table->TABLE_NAME));
		echo "\n\n";

		// Structure
		if($this->settings['addDropObject'])
		{
			echo 'DROP TABLE IF EXISTS ', $db->quoteTableName($table->TABLE_NAME), ";\n";
		}

		$tableStructure = $table->getShowCreateTable();
		if($this->settings['addIfNotExists'])
		{
			$tableStructure = 'CREATE TABLE IF NOT EXISTS' . substr($tableStructure, 12);
		}
		echo $tableStructure, ";\n\n";

		// Triggers
		if($this->settings['exportTriggers'])
		{
			$triggers = Trigger::model()->findAllByAttributes(array(
				'EVENT_OBJECT_SCHEMA' => $table->TABLE_SCHEMA,
				'EVENT_OBJECT_TABLE' => $table->TABLE_NAME,
			));
			foreach($triggers AS $trigger)
			{
				$this->comment('Trigger ' . $db->quoteTableName($trigger->TRIGGER_NAME) . ' on table ' . $db->quoteTablename($table->TABLE_NAME));
				echo "\n\n";

				if($this->settings['addDropObject'])
				{
					echo 'DROP TRIGGER IF EXISTS ', $db->quoteTableName($trigger->TRIGGER_NAME), ";\n";
				}

				echo $trigger->getCreateTrigger(), ";\n\n";
			}
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

		// Create insert statement
		if($this->settings['completeInserts'])
		{
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
			$columns = ' (' . implode(', ', $columns) . ')';
		}
		else
		{
			$columns = '';
		}
		$insert = $this->settings['insertCommand']
			. ($this->settings['delayedInserts'] ? ' DELAYED' : '')
			. ($this->settings['ignoreInserts'] ? ' IGNORE' : '')
			. ' INTO '
			. $db->quoteTableName($table->TABLE_NAME)
			. $columns
			. ' VALUES';

		// Find all rows
		$sql = 'SELECT * FROM ' . Yii::app()->db->quoteTableName($this->schema) . '.' . Yii::app()->db->quoteTableName($table->TABLE_NAME);
		$statement = $pdo->query($sql);
		$statement->setFetchMode(PDO::FETCH_NUM);
		$rowCount = $statement->rowCount();

		// Settings
		$hexBlobs = $this->settings['hexBlobs'];
		$rowsPerInsert = (int)$this->settings['rowsPerInsert'];

		// Cycle rows
		$i = 0;
		$k = 1;
		while($row = $statement->fetch())
		{
			// Add comment
			if($i == 0)
			{
				$this->comment('Data for table ' . $db->quoteTableName($table->TABLE_NAME));
				echo "\n\n";
				echo $insert;
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
					$row[$key] = $pdo->quote($value);
				}
			}

			// Add this row
			echo "\n  (", implode(', ', $row), ')';

			if($i == $rowCount - 1)
			{
				echo ";\n\n";
			}
			elseif($k == $rowsPerInsert)
			{
				echo ";\n\n", $insert;
				$k = 0;
			}
			else
			{
				echo ',';
			}
			$i++;
			$k++;
		}
	}
	
	/**
	 * Exports all views of the given array and writes the dump to the output buffer.
	 *
	 * @param	array					list of views
	 */
	private function exportViews($views)
	{
		// Get DbConnection object
		$db = Yii::app()->db;

		// Escape all view names
		$viewNames = array();
		foreach($views AS $view)
		{
			$viewNames[] = Yii::app()->db->quoteValue($view);
		}

		// Find all views
		$views = View::model()->findAll('TABLE_NAME IN (' . implode(',', $viewNames) . ') '
			. 'AND TABLE_SCHEMA = ' . Yii::app()->db->quoteValue($this->schema));

		foreach($views AS $view)
		{
			$this->comment('View ' . $db->quoteTableName($view->TABLE_NAME));
			echo "\n\n";

			// Structure
			if($this->settings['addDropObject'])
			{
				echo 'DROP VIEW IF EXISTS ', $db->quoteTableName($view->TABLE_NAME), ";\n";
			}
			echo $view->getCreateView(), ";\n\n";
		}
	}
	
	/**
	 * Exports all routines of the given array and writes the dump to the output buffer.
	 *
	 * @param	array					list of routines
	 */
	private function exportRoutines($routines)
	{
		// Get DbConnection object
		$db = Yii::app()->db;

		// Escape all routine names
		$routineNames = array();
		foreach($routines AS $routine)
		{
			$routineNames[] = Yii::app()->db->quoteValue($routine);
		}

		// Find all routines
		$routines = Routine::model()->findAll('ROUTINE_NAME IN (' . implode(',', $routineNames) . ') '
			. 'AND ROUTINE_SCHEMA = ' . $db->quoteValue($this->schema));

		foreach($routines AS $routine)
		{
			$this->comment(ucfirst(strtolower($routine->ROUTINE_TYPE)) . ' ' . $db->quoteTableName($routine->ROUTINE_NAME));
			echo "\n\n";

			if($this->settings['addDropObject'])
			{
				echo 'DROP ', strtoupper($routine->ROUTINE_TYPE), ' IF EXISTS ', $db->quoteTableName($routine->ROUTINE_NAME), ";\n";
			}

			echo $routine->getCreateRoutine(), ";\n\n";
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

		// Create insert statement
		if($this->settings['completeInserts'])
		{
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
			$columns = ' (' . implode(', ', $columns) . ')';
		}
		else
		{
			$columns = '';
		}
		$insert = $this->settings['insertCommand']
			. ($this->settings['delayedInserts'] ? ' DELAYED' : '')
			. ($this->settings['ignoreInserts'] ? ' IGNORE' : '')
			. ' INTO '
			. $db->quoteTableName($this->table)
			. $columns
			. ' VALUES';

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
			// Add comment
			if($i == 0)
			{
				$this->comment('Data for table ' . $db->quoteTableName($this->table));
				echo "\n\n";
				echo $insert;
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
					$attributes[$key] = $pdo->quote($value);
				}
			}

			// Add this row
			echo "\n  (", implode(', ', $attributes), ')';

			if($i == $rowCount - 1)
			{
				echo ";\n\n";
			}
			elseif($k == $rowsPerInsert)
			{
				echo ";\n\n", $insert;
				$k = 0;
			}
			else
			{
				echo ',';
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