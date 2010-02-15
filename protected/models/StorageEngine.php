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


class StorageEngine extends SqlModel
{

	/**
	 * @see		SqlModel::model()
	 */
	public static function model($class = __CLASS__)
	{
		return parent::model($class);
	}

	/**
	 * @see		SqlModel::getSql()
	 */
	protected function getSql()
	{
		return 'SHOW ENGINES';
	}

	/**
	 * @see		SqlModel::attributeNames()
	 */
	public function attributeNames()
	{
		return array(
			'Engine',
			'Support',
			'Comment',
		);
	}

	/**
	 * Returns wether DELAY_KEY_WRITE option is supported by storage engine.
	 *
	 * @return	bool				True, if supported. Else, if not.
	 */
	public function getSupportsDelayKeyWrite()
	{
		return self::check($this->Engine, self::SUPPORTS_DELAY_KEY_WRITE);
	}

	/**
	 * Returns wether CHECKSUM option is supported by storage engine.
	 *
	 * @return	bool				True, if supported. Else, if not.
	 */
	public function getSupportsChecksum()
	{
		return self::check($this->Engine, self::SUPPORTS_CHECKSUM);
	}

	/**
	 * Returns wether PACK_KEYS option is supported by storage engine.
	 *
	 * @return	bool				True, if supported. Else, if not.
	 */
	public function getSupportsPackKeys()
	{
		return self::check($this->Engine, self::SUPPORTS_PACK_KEYS);
	}

	/**
	 * Returns all variable keys for this storage engine.
	 *
	 * @return	array				List of variable keys
	 */
	public function getVariables()
	{
		switch(strtolower($this->Engine))
		{
			case 'myisam':
				return array(
					'myisam_data_pointer_size',
					'myisam_recover_options',
					'myisam_max_sort_file_size',
					'myisam_max_extra_sort_file_size',
					'myisam_repair_threads',
					'myisam_sort_buffer_size',
					'myisam_stats_method',
					'delay_key_write',
					'bulk_insert_buffer_size',
					'skip_external_locking',
				);
			case 'innodb':
				return array(
					'innodb_data_home_dir',
					'innodb_data_file_path',
					'innodb_autoextend_increment',
					'innodb_buffer_pool_size',
					'innodb_additional_mem_pool_size',
					'innodb_buffer_pool_awe_mem_mb',
					'innodb_checksums',
					'innodb_commit_concurrency',
					'innodb_concurrency_tickets',
					'innodb_doublewrite',
					'innodb_fast_shutdown',
					'innodb_file_io_threads',
					'innodb_file_per_table',
					'innodb_flush_log_at_trx_commit',
					'innodb_flush_method',
					'innodb_force_recovery',
					'innodb_lock_wait_timeout',
					'innodb_locks_unsafe_for_binlog',
					'innodb_log_arch_dir',
					'innodb_log_archive',
					'innodb_log_buffer_size',
					'innodb_log_file_size',
					'innodb_log_files_in_group',
					'innodb_log_group_home_dir',
					'innodb_max_dirty_pages_pct',
					'innodb_max_purge_lag',
					'innodb_mirrored_log_groups',
					'innodb_open_files',
					'innodb_support_xa',
					'innodb_sync_spin_loops',
					'innodb_table_locks',
					'innodb_thread_concurrency',
					'innodb_thread_sleep_delay',
				);
			case 'memory':
				return array(
					'max_heap_table_size',
				);
			case 'ndbcluster':
				return array(
					'ndb_connectstring',
				);
			default:
				return array();
		}
	}

	/**
	 * Returns all variables for this storage engine with values.
	 *
	 * @return	array				List of variables with values
	 */
	public function getVariablesWithValues()
	{
		$keys = $this->getVariables();
		$values = array();

		foreach($keys AS $key)
		{
			$values[$key] = '-';
		}

		if(count($keys) > 0)
		{
			$cmd = Yii::app()->getDb()->createCommand('SHOW GLOBAL VARIABLES');
			$data = $cmd->queryAll();

			$variables = array();
			foreach($data AS $entry)
			{
				if(in_array($entry['Variable_name'], $keys))
				{
					$values[$entry['Variable_name']] = $entry['Value'];
				}
			}
		}

		return $values;
	}




	/*
	 * static things ...
	 */

	const SUPPORTS_DELAY_KEY_WRITE = 0;
	const SUPPORTS_CHECKSUM = 1;
	const SUPPORTS_PACK_KEYS = 2;
	const SUPPORTS_FOREIGN_KEYS = 3;

	public static $engines = array(

	//							< OPTIONS             >
	// Engine					delkwr	chksum	pckkeys	fkeys

		'MyISAM'		=> array(	true,	true,	true,	false),
		'MEMORY'		=> array(	false,	false,	false,	false),
		'InnoDB'		=> array(	false,	false,	false,	true),
		'BerkeleyDB'	=> array(	false,	false,	false,	false),
		'BLACKHOLE'		=> array(	false,	false,	false,	false),
		'EXAMPLE'		=> array(	false,	false,	false,	false),
		'ARCHIVE'		=> array(	false,	false,	false,	false),
		'CSV'			=> array(	false,	false,	false,	false),
		'ndbcluster'	=> array(	false,	false,	false,	false),
		'FEDERATED'		=> array(	false,	false,	false,	false),
		'MRG_MYISAM'	=> array(	false,	false,	false,	false),
		'ISAM'			=> array(	false,	false,	false,	false),

	);

	/**
	 * Checks a specific property for a specific storage engine.
	 *
	 * @param	string				Storage engine
	 * @param	int					Property Index (use constants to get them)
	 * @return	bool				True, if supported. Else, if not.
	 */
	public static function check($engine, $property)
	{
		return self::$engines[self::getFormattedName($engine)][$property];
	}

	/**
	 * Returns the formatted name of a storage engine.
	 *
	 * @param	string				Storage engine name
	 * @return	string				Formatted name
	 */
	public static function getFormattedName($engine)
	{
		switch(strtolower($engine))
		{
			case 'myisam':
				return 'MyISAM';
			case 'innodb':
				return 'InnoDB';
			case 'berkeleydb':
				return 'BerkeleyDB';
			case 'ndbcluster':
				return 'ndbcluster';
			default:
				return strtoupper($engine);
		}
	}

	/**
	 * Returns all storage engines which are supported by the current server.
	 *
	 * @return	array				Array of supported StorageEngine objects
	 */
	public static function getSupportedEngines()
	{
		return StorageEngine::model()->findAllByAttributes(array(
			'Support' => array('YES', 'DEFAULT'),
		));
	}

	/**
	 * Returns the available settings for the PACK_KEY option to use in selects.
	 *
	 * @return	array				All settings for the PACK_KEY option
	 */
	public static function getPackKeyOptions()
	{
		return array(
			'DEFAULT' => Yii::t('core', 'default'),
			'1' => Yii::t('core', 'yes'),
			'0' => Yii::t('core', 'no'),
		);
	}
	
}