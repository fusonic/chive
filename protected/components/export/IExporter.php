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


interface IExporter
{

	/**
	 * Constructs the new IExport object.
	 *
	 * @param	string				used mode (schemata/tables/rows)
	 * @return	IExport
	 */
	public function __construct($mode);

	/**
	 * Returns the settings form for the current mode.
	 *
	 * @return	string
	 */
	public function getSettingsView();

	/**
	 * Calculates the number of needed steps to export.
	 *
	 * @return	int
	 */
	public function calculateStepCount();

	/**
	 * Returns the number of steps the export will need.
	 *
	 * @return	int
	 */
	public function getStepCount();

	/**
	 * Sets the items to export (e.g. tables, schemata).
	 *
	 * @param	array				items to export
	 */
	public function setItems(array $items);
	
	/**
	 * 
	 * Sets the rows to export.
	 * @param array $rows
	 * @param string $table
	 * @param string $schema
	 */
	public function setRows(array $rows, $table = null, $schema = null);

	/**
	 * Runs the specified exporting step.
	 *
	 * @param	int					step number
	 * @param	boolean				collect output or flush directly
	 * @return	boolean
	 */
	public function runStep($i, $collect = false);

	/**
	 * Returns the export result.
	 *
	 * @return	string
	 */
	public function getResult();

	/**
	 * Returns the supported export modes (schemata/tables/rows)
	 *
	 * @return	array
	 */
	public static function getSupportedModes();

	/**
	 * Returns the title for display purposes.
	 *
	 * @return	string
	 */
	public static function getTitle();

}