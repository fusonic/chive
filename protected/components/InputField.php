<?php

class InputField extends CWidget
{

	public $column;
	public $row;
	public $htmlOptions;
	public $value;
	public $id;
	
	public function run()
	{
		$data = array();

		$this->render('inputField', array(
			'column'=>$this->column,
			'row'=>$this->row,
			'htmlOptions'=>$this->htmlOptions,
			'type'=>DataType::getInputType($this->column->dbType),
		));
		
	}

	public function getEnumValues()
	{
		$type = preg_match('/\(\'(.+)\'\)/i', $this->column->dbType, $res);
		$values = explode('\',\'', $res[1]);

		$return = array();
		foreach($values AS $value)
		{
			$return[$value] = $value;
		}

		return $return;
	}
	
	public function getSetValues() 
	{
		return true;
	}


}