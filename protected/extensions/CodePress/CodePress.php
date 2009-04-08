<?php

class CodePress extends CInputWidget
{

	private $_codePressPath;
	public $width = "100%";
	public $height = "160px";
	public $autogrow = false;
	public $language = "text";



	public function init()
	{

		list($name, $id) = $this->resolveNameID();

		// Publish CodePress
		$this->_codePressPath = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'codepress', true, -1);

		// Register client script
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile($this->_codePressPath . DIRECTORY_SEPARATOR . "codepress.js");
		$jsSubmit = 'jQuery(jQuery("#' . $id . '").get(0).form).submit(function() { jQuery("#' . $id . '_cp").val(' . $id . '.getCode()); jQuery("#' . $id . '_cp").attr("disabled", false); });';
		$cs->registerScript('Yii.SqlEditor#' . $id, $jsSubmit);
		$cs->registerScript('Yii.SqlEditor.Path', 'var codePressPath = "' . $this->_codePressPath .  '/"; CodePress.run();');

		parent::init();

	}

	/**
	 * Executes the widget.
	 * This method registers all needed client scripts and renders
	 * the text field.
	 */
	public function run()
	{

		list($name, $id) = $this->resolveNameID();
		$this->htmlOptions['id'] = $id;
		$this->htmlOptions['class'] = "codepress " . $this->language . ($this->autogrow ? " autogrow" : "");
		$this->htmlOptions['style'] = "width: " . $this->width . "; height: " . $this->height;

		echo CHtml::textArea($name, $this->value, $this->htmlOptions);

	}

}