<?php

require_once('yii.php');

class ColumnTest extends TestCase
{

	public function setUp()
	{
		#$this->executeSqlFile(dirname(__FILE__) . '/Column.sql');
		parent::setUp();
	}

	public function testTest()
	{

		//$col = Column::model()->findByPk(array('TABLE_SCHEMA' => 'testing_ptd1', 'TABLE_NAME' => 'ctd1_sys_user', 'COLUMN_NAME' => 'username'));

		$this->assertEquals(true, true);

	}

}

?>