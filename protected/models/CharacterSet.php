<?php

/**
 * Represents a character set installed on the MySql server.
 */
class CharacterSet extends CActiveRecord
{
	/**
	 * @see		CActiveRecord::model()
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @see		CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'CHARACTER_SETS';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return 'CHARACTER_SET_NAME';
	}

	/**
	 * @see		CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'collations' => array(self::HAS_MANY, 'Collation', 'CHARACTER_SET_NAME'),
		);

	}
}