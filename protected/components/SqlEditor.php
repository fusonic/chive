<?php

// @todo (rponudic) check this...
Yii::import('application.extensions.EditArea.*');

/**
 * An editor to edit sql statements.
 *
 * This can be either a normal textarae
 * or an editor with syntax highlighting etc. (We are still looking for a
 * good solution on this problem).
 */
class SqlEditor extends EditArea
{

	public $width = "100%";
	public $height = "100px";
	public $autogrow = false;

	/**
	 * Executes the widget.
	 * This method registers all needed client scripts and renders
	 * the text field.
	 */
	public function run()
	{

		if(false)
		{
			list($name, $id) = $this->resolveNameID();
			$this->htmlOptions['id'] = $id;
			$this->htmlOptions['style'] = "width: " . $this->width . "; height: " . $this->height;
	
			echo CHtml::textArea($name, $this->value, $this->htmlOptions);
			
		}
		else
		{
			
			parent::run();
			
		}

	}

}