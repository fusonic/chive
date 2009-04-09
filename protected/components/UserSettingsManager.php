<?php

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

	public function getJsObject()
	{
		$jsSettings = 'var userSettings = {};' . "\n";
		foreach($this->defaultSettings AS $key => $value) {
			if(isset($this->userSettings[$key]))
			{
				$value = $this->userSettings[$key];
			}
			$jsSettings .= 'userSettings.' . $key . ' = "' . str_replace('"', '\"', $value) . '";' . "\n";
		}
		return $jsSettings;
	}

	public function get($name, $scope = null, $object = null)
	{
		$id = $this->getSettingId($name, $scope);
		if(isset($this->userSettings[$id]))
		{
			if(isset($this->userSettings[$id][$object]))
			{
				return $this->userSettings[$id][$object];
			}
			elseif(isset($this->userSettings[$id][null]))
			{
				return $this->userSettings[$id][null];
			}
		}
		elseif(isset($this->defaultSettings[$id]))
		{
			return $this->defaultSettings[$id][null];
		}
		else
		{
			throw new CException(Yii::t('yii','The setting {setting} does not exist.',
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
			throw new CException(Yii::t('yii','The setting {setting} does not exist.',
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
			$value = (string)$setting;
			$scope = (isset($setting['scope']) ? $setting['scope'] : null);
			$object = (isset($settings['object']) ? $settings['object'] : null);

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
					$settingXml = $xml->addChild($name, $value);
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
		$return = explode('_', $id);
		if(is_array($return))
		{
			$return[1] = str_replace("__", ".", $return[1]);
			return $return;
		}
		else
		{
			return array($return, null);
		}
	}

}