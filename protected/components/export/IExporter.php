<?php

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
	 * Sets the items to export (e.g. tables, schemata, rows).
	 *
	 * @param	array				items to export
	 */
	public function setItems(array $items);

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