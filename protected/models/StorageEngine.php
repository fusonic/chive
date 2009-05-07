<?php

class StorageEngine extends SqlModel
{

	public static function model($class = __CLASS__)
	{
		return parent::model($class);
	}

	protected function getSql()
	{
		return 'SHOW ENGINES';
	}

	public function attributeNames()
	{
		return array(
			'Engine',
			'Support',
			'Comment',
		);
	}

	public function getSupportsDelayKeyWrite()
	{
		return self::check($this->Engine, self::SUPPORTS_DELAY_KEY_WRITE);
	}

	public function getSupportsChecksum()
	{
		return self::check($this->Engine, self::SUPPORTS_CHECKSUM);
	}

	public function getSupportsPackKeys()
	{
		return self::check($this->Engine, self::SUPPORTS_PACK_KEYS);
	}




	/*
	 * static things ...
	 */

	const SUPPORTS_DELAY_KEY_WRITE = 0;
	const SUPPORTS_CHECKSUM = 1;
	const SUPPORTS_PACK_KEYS = 2;

	public static $engines = array(

		//							< OPTIONS             >
		// Engine					delkwr	chksum	pckkeys

		'MyISAM'		=> array(	true,	true,	true),
		'MEMORY'		=> array(	false,	false,	false),
		'InnoDB'		=> array(	false,	false,	false),
		'BerkeleyDB'	=> array(	false,	false,	false),
		'BLACKHOLE'		=> array(	false,	false,	false),
		'EXAMPLE'		=> array(	false,	false,	false),
		'ARCHIVE'		=> array(	false,	false,	false),
		'CSV'			=> array(	false,	false,	false),
		'ndbcluster'	=> array(	false,	false,	false),
		'FEDERATED'		=> array(	false,	false,	false),
		'MRG_MYISAM'	=> array(	false,	false,	false),
		'ISAM'			=> array(	false,	false,	false),

	);

	public static function check($engine, $property)
	{
		return self::$types[self::getFormattedName($engine)][$property];
	}

	public static function getFormattedName($engine)
	{
		switch(strtolower($engine))
		{
			case 'myisam':
				return 'MyISAM';
			case 'innodb':
				return 'InnoDB';
			default:
				return strtoupper($engine);
		}
	}

	public static function getSupportedEngines()
	{
		return StorageEngine::model()->findAllByAttributes(array(
			'Support' => array('YES', 'DEFAULT'),
		));
	}

	public static function getPackKeyOptions()
	{
		return array(
			'DEFAULT' => Yii::t('core', 'default'),
			'1' => Yii::t('core', 'yes'),
			'0' => Yii::t('core', 'no'),
		);
	}

}

?>