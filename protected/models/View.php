<?php

class View extends CActiveRecord
{

	public static $db;

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'VIEWS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('TABLE_CATALOG','length','max'=>512),
			array('TABLE_SCHEMA','length','max'=>64),
			array('TABLE_NAME','length','max'=>64),
			array('CHECK_OPTION','length','max'=>8),
			array('IS_UPDATABLE','length','max'=>3),
			array('DEFINER','length','max'=>77),
			array('SECURITY_TYPE','length','max'=>7),
			array('VIEW_DEFINITION', 'required'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'columns' => array(self::HAS_MANY, 'Column', 'TABLE_SCHEMA, TABLE_NAME'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'IS_UPDATABLE' => Yii::t('database', 'updatable'),
		);
	}

	public function primaryKey()
	{
		return array(
			'TABLE_SCHEMA',
			'TABLE_NAME',
		);
	}

	/**
	 * Drop view
	 *
	 * @return	string
	 */
	public function delete()
	{
		$sql = 'DROP VIEW ' . self::$db->quoteTableName($this->TABLE_NAME) . ';';
		$cmd = self::$db->createCommand($sql);

		// Execute
		try
		{
			$cmd->prepare();
			$cmd->execute();
			return $sql;
		}
		catch(CDbException $ex)
		{
			throw new DbException($cmd);
		}
	}

	/**
	 * Returns the CREATE VIEW statement for this view.
	 *
	 * @return	string
	 */
	public function getCreateView()
	{
		$cmd = self::$db->createCommand('SHOW CREATE VIEW ' . self::$db->quoteTableName($this->TABLE_NAME));
		$res = $cmd->queryRow(false);
		return $res[1];
	}

	/**
	 * Returns the ALTER VIEW statement for this view.
	 *
	 * @return	string
	 */
	public function getAlterView()
	{
		return 'ALTER' . substr($this->getCreateView(), 6);
	}

}