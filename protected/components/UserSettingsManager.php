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


class UserSettingsManager
{

	private $configPath;
	private $host, $user;
	private $defaultSettings = array();
	private $userSettings = array();

	public function __construct($host, $user)
	{

		$this->host = $host;
		$this->user = $user;

		// Get config path
		$this->configPath = Yii::app()->getRuntimePath() . DIRECTORY_SEPARATOR . 'user-config' . DIRECTORY_SEPARATOR;

		// Load settings
		$this->loadSettings();

	}

	/**
	 * Creates JavaScript representation of settings.
	 * @todo(mburtscher): Support arrays.
	 * @return	string
	 */
	public function getJsObject()
	{
		$jsSettings = 'var userSettings = {};' . "\n";
		foreach($this->defaultSettings AS $key => $value) {

			$value = $value[null];
			if(is_array($value))
			{
				continue;
			}
			if(isset($this->userSettings[$key]))
			{
				foreach($this->userSettings[$key] AS $key2 => $value2)
				{
					if(is_array($value2))
					{
						continue;
					}
					if(!$key2)
					{
						$value = $value2;
					}
					else
					{
						$jsSettings .= 'userSettings.' . $key . '__' . str_replace('.', '_', $key2) . ' = "' . str_replace('"', '\"', $value2) . '";' . "\n";
					}
				}
			}
			$jsSettings .= 'userSettings.' . $key . ' = "' . str_replace('"', '\"', $value) . '";' . "\n";
		}
		return $jsSettings;
	}

	public function get($name, $scope = null, $object = null, $attribute = null, $value = null)
	{
		$id = $this->getSettingId($name, $scope);

		if(isset($this->userSettings[$id]))
		{
			if(isset($this->userSettings[$id][$object]))
			{
				if($attribute && $value)
				{
					return self::findByAttributeValue($this->userSettings[$id][$object], $attribute, $value);
				}
				else
				{
					return $this->userSettings[$id][$object];
				}
			}
			elseif(isset($this->userSettings[$id][null]))
			{
				if($attribute && $value)
				{
					return self::findByAttributeValue($this->userSettings[$id][null], $attribute, $value);
				}
				else
				{
					return $this->userSettings[$id][null];
				}

			}
		}
		elseif(isset($this->defaultSettings[$id]))
		{
			if($attribute && $value)
			{
				return self::findByAttributeValue($this->defaultSettings[$id][null], $attribute, $value);
			}
			else
			{
				return $this->defaultSettings[$id][null];
			}
		}
		else
		{
			throw new CException(Yii::t('core','The setting {setting} does not exist.',
				array('{setting}' => $id)));
		}
	}

	public function set($name, $value, $scope = null, $object = null)
	{
		$id = $this->getSettingId($name, $scope);
		if(isset($this->defaultSettings[$id]))
		{
			$this->userSettings[$id][$object] = $value;
		}
		else
		{
			throw new CException(Yii::t('core','The setting {setting} does not exist.',
				array('{setting}'=>$id)));
		}
	}

	private function loadSettings()
	{
		// Load settings
		$this->defaultSettings = $this->loadSettingsFile($this->configPath . 'default.xml');
		if(is_file($this->configPath . $this->host . '.' . $this->user . '.xml'))
		{
			$this->userSettings = $this->loadSettingsFile($this->configPath . $this->host . '.' . $this->user . '.xml');
		}
	}

	private function loadSettingsFile($filename)
	{
		$defaultXml = new SimpleXMLElement(file_get_contents($filename));
		$settings = array();
		foreach($defaultXml->children() AS $setting)
		{
			$name = $setting->getName();
			if(isset($setting['serialized']))
			{
				$value = unserialize((string)$setting);
			}
			else
			{
				$value = (string)$setting;
			}
			$scope = (isset($setting['scope']) ? (string)$setting['scope'] : null);
			$object = (isset($setting['object']) ? (string)$setting['object'] : null);

			$id = $this->getSettingId($name, $scope);

			$settings[$id][$object] = $value;
		}
		return $settings;
	}

	public function saveSettings()
	{
		if(count($this->userSettings) > 0)
		{
			$xml = new SimpleXmlElement('<settings host="' . $this->host . '" user="' . $this->user . '" />');
			foreach($this->userSettings AS $key => $values)
			{
				list($name, $scope) = $this->getSettingNameScope($key);
				foreach($values AS $object => $value)
				{
					if(is_array($value))
					{
						$value = serialize($value);
						$setSerialized = true;
					}
					else
					{
						$setSerialized = false;
					}
					$settingXml = $xml->addChild($name, $value);
					if($setSerialized)
					{
						$settingXml['serialized'] = true;
					}
					if($scope)
					{
						$settingXml['scope'] = $scope;
					}
					if($object)
					{
						$settingXml['object'] = $object;
					}
				}
			}
		}
		elseif(is_file($this->configPath . $this->host . '.' . $this->user . '.xml'))
		{
			unlink($this->configPath . $this->host . '.' . $this->user . '.xml');
		}
		$xml->asXML($this->configPath . $this->host . '.' . $this->user . '.xml');
	}

	private function getSettingId($name, $scope)
	{
		return $name . ($scope ? '__' . str_replace(".", "_", $scope) : '');
	}

	private function getSettingNameScope($id)
	{
		$return = explode('__', $id);
		if(is_array($return))
		{
			if(isset($return[1]))
			{
				$return[1] = str_replace("_", ".", $return[1]);
			}
			else
			{
				$return[1] = null;
			}
			return $return;
		}
		else
		{
			return array($return, null);
		}
	}

	private function findByAttributeValue($array, $attribute, $value)
	{
		$array = CPropertyValue::ensureArray($array);

		foreach($array AS $key=>$entry)
		{
			if($entry[$attribute] == $value)
				return $entry;
		}

		return false;
	}

}