<?php

class InputField extends CWidget
{

	public $column;
	public $row;
	public $htmlOptions;
	public $value;
	public $id;
	
	private $fixedHtmlOptions = array(
		'number' =>				array(),
		'select' =>				array(),
		'file' =>				array(),
		'select' =>				array(),
		'date' =>				array(),
		'datetime' =>			array(),
		'select-multiple' => 	array('multiple'=>'multiple'),
		'text' => 				array('style'=>'min-width: 500px; min-height: 100px;'),
		'single' => 			array('class'=>'text'),
	);
	
	public function run()
	{

		$type = DataType::getInputType($this->column->dbType);		
		$this->htmlOptions += $this->fixedHtmlOptions[$type];
		
		switch($type) {
	
			case 'number':
				echo CHtml::activeTextField($this->row, $this->column->name, $this->htmlOptions);
				break;
		
			case 'select':
				echo CHtml::activeDropDownList($this->row, $this->column->name, $this->getEnumValues(), $this->htmlOptions);
				break;
				
			case 'select-multiple':
				echo CHtml::activeListBox($this->row, $this->column->name, $this->getSetValues(), $this->htmlOptions);
				break;
		
			case 'text':
				echo CHtml::activeTextArea($this->row, $this->column->name, $this->htmlOptions);
				break;
		
			case 'file':
				echo '<script type="text/javascript">
					$(document).ready(function() {
						$("#<?php echo CHtml::$idPrefix; ?>").submit(function() {
							alert("ok1");
							
						});
					});
					</script>';
				echo CHtml::activeFileField($this->row, $this->column->name, $this->htmlOptions);
				break;	
				
			case 'date':
				echo CHtml::activeTextField($this->row, $this->column->name, $this->htmlOptions);
				echo '<script type="text/javascript">
						$(document).ready(function() {
							$("#' . $this->htmlOptions['id'] . '").datepicker({showOn: "button", dateFormat: "yy-mm-dd", buttonImage: "' . ICONPATH . '/16/calendar.png' . '", buttonImageOnly: true, buttonText: "' . Yii::t('core', 'showCalendar') . '"});
						});
						</script>';
				break;
				
			case 'datetime':
				echo CHtml::activeTextField($this->row, $this->column->name, $this->htmlOptions);
				echo '<script type="text/javascript">
						$(document).ready(function() {
							now = new Date();
							$("#' . $this->htmlOptions['id'] . '").datepicker({showOn: "button", dateFormat: "yy-mm-dd " + now.getHours() + ":" + now.getMinutes() + ":00", buttonImage: "' . ICONPATH . '/16/calendar.png' . '", buttonImageOnly: true, buttonText: "' . Yii::t('core', 'showCalendar') . '"});
						});
						</script>';
				break;
				
			default:
				echo CHtml::activeTextField($this->row, $this->column->name, array_merge($this->htmlOptions, array('maxlength'=>$this->column->size)));
				break;
		}
		
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
		$type = preg_match('/\(\'(.+)\'\)/i', $this->column->dbType, $res);
		$values = explode('\',\'', $res[1]);

		$return = array();
		foreach($values AS $value)
		{
			$return[$value] = $value;
		}

		return $return;
	}


}