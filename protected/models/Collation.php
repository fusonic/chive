<?php

class Collation extends CActiveRecord
{
	const DEFAULT_CHARACTER_SET = 'utf8';
	const DEFAULT_COLLATION = 'utf8_general_ci';

	public $collationGroup;

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
		return 'COLLATIONS';
	}

	/**
	 * @see		CActiveRecord::primaryKey()
	 */
	public function primaryKey()
	{
		return 'COLLATION_NAME';
	}

	/**
	 * @see		CActiveRecord::relations()
	 */
	public function relations()
	{
		return array(
			'schema' => array(self::HAS_MANY, 'Schema', 'DEFAULT_COLLATION_NAME'),
			'characterSet' => array(self::BELONGS_TO, 'CharacterSet', 'CHARACTER_SET_NAME'),
		);
	}

	/**
	 * Returns the definition of the given collation.
	 *
	 * The definition contains charset, collation and language like this:
	 * cp1252 West European, Swedish (Case-Insensitive)
	 *
	 * @param	string				Collation name (e.g. utf8_general_ci)
	 * @return	string				Definition including charset, collation and language
	 */
	public static function getDefinition($collation)
	{
		$data = explode('_', $collation);
		$text = Yii::t('collation', $data[0]) . ', ' . Yii::t('collation', $data[1]);
		if(count($data) == 3)
		{
			$text .= ' (' . Yii::t('collation', $data[2]) . ')';
		}
		return $text;
	}

	/**
	 * Returns the character set of a collation.
	 *
	 * This is the content before the first underscore.
	 *
	 * @param	string				Collation name (e.g. utf8_general_ci)
	 * @return	string				Charset (e.g. utf8)
	 */
	public static function getCharacterSet($collation)
	{
		$data = explode('_', $collation);
		return $data[0];
	}

}