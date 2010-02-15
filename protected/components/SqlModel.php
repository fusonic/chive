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


abstract class SqlModel extends CModel
{

	private static $db;
	private static $models = array();

	private $_attributes = array();

	public function __get($name)
	{
		if(isset($this->_attributes[$name]))
		{
			return $this->_attributes[$name];
		}
		elseif(in_array($name, $this->attributeNames()))
		{
			return null;
		}
		else
		{
			return parent::__get($name);
		}
	}

	public function __set($name,$value)
	{
		if(in_array($name, $this->attributeNames()))
		{
			$this->_attributes[$name] = $value;
		}
		else
		{
			parent::__set($name, $value);
		}
	}

	public function __isset($name)
	{
		if(isset($this->_attributes[$name]))
		{
			return true;
		}
		elseif(in_array($name, $this->attributeNames()))
		{
			return false;
		}
		else
		{
			return parent::__isset($name);
		}
	}

	public function __unset($name)
	{
		if(in_array($name, $this->attributeNames()))
		{
			unset($this->_attributes[$name]);
		}
		else
		{
			parent::__unset($name);
		}
	}

	public static function model($class = __CLASS__)
	{
		if(!isset(self::$models[$class]))
		{
			self::$models[$class] = new $class();
		}
		return self::$models[$class];
	}

	public function findAll()
	{
		return $this->populateRecords($this->queryAll());
	}

	public function findAllByAttributes(array $attributes = array())
	{
		return $this->populateRecords($this->queryAll($attributes));
	}

	public function findByAttributes(array $attributes = array())
	{
		$results = $this->queryAll($attributes);
		if(count($results) > 0)
		{
			return $this->populateRecord(current($results));
		}
		else
		{
			return null;
		}
	}

	public function safeAttributes()
	{
		return $this->attributeNames();
	}

	public function getDbConnection()
	{
		if(self::$db !== null)
		{
			return self::$db;
		}
		else
		{
			self::$db = Yii::app()->getDb();
			if(self::$db instanceof CDbConnection)
			{
				self::$db->setActive(true);
				return self::$db;
			}
			else
			{
				throw new CDbException(Yii::t('core','Active Record requires a "db" CDbConnection application component.'));
			}
		}
	}

	public function populateRecord($attributes)
	{
		$record = $this->instantiate($attributes);
		$availableAttributes = $this->attributeNames();
		foreach($attributes as $name => $value)
		{
			if(property_exists($record, $name))
			{
				$record->$name = $value;
			}
			elseif(array_search($name, $availableAttributes) !== false)
			{
				$record->_attributes[$name] = $value;
			}
		}
		$record->attachBehaviors($record->behaviors());
		return $record;
	}

	public function populateRecords($data)
	{
		$records=array();
		foreach($data as $attributes)
		{
			$records[] = $this->populateRecord($attributes);
		}
		return $records;
	}

	protected function instantiate($attributes)
	{
		$class=get_class($this);
		return new $class(null);
	}

	protected function queryAll(array $attributes = array())
	{
		$cmd = $this->getDbConnection()->createCommand($this->getSql());
		$results = $cmd->queryAll();
		foreach($results AS $key => $result)
		{
			$isValid = true;
			foreach($attributes AS $key2 => $value)
			{
				if(is_array($value))
				{
					$isValid2 = false;
					foreach($value AS $value2)
					{
						if($result[$key2] == $value2)
						{
							$isValid2 = true;
						}
					}
					if(!$isValid2)
					{
						$isValid = false;
						break;
					}
				}
				elseif($result[$key2] != $value)
				{
					$isValid = false;
					break;
				}
			}
			if(!$isValid){
				unset($results[$key]);
			}
		}
		return $results;
	}

	protected abstract function getSql();

}

?>