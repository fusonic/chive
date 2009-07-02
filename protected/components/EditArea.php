<?php

class EditArea extends CInputWidget
{

	private $_editAreaPath;
	public $id;
	public $syntax = "sql";  // language that should be highlighted
	public $width = "100%";    //width of the editor
	public $height = "200px";   // height of the editor
	public $autogrow = false;    // allow autogrow
	public $toolbar = " undo, redo";  //comma seperated string for the config of the toolbar
	public $wordWrap = "true";  // allow word wrap at the end of line
	public $allowToggle = "true";  // allows toggle the editor
	public $allowResize = "false";  // allows to resize the editor
	public $minHeight = "100px";  // min-height of the editor
	public $minWidth = "200px";  // min-width of the editor
	public $maxHeight = "300px"; //max-height of the editor

	public function init()
	{
		list($name, $this->id) = $this->resolveNameID();

		//$this->id .= StringUtil::getRandom(5);


		$autogrow = ($this->autogrow  ? 'setupEditAreaAutoGrow' : 'setoverflow');



		// Publish CodePress
		/*
		if($autogrow == true)
		{
		$cs->registerScriptFile($this->_editAreaPath . DIRECTORY_SEPARATOR . "fusonic_extensions/editarea_autogrow.js");
		}
		*/

		$cs = Yii::app()->getClientScript();

		$jsInit = '$("#' . $this->id . '").closest("form").submit(function() {
				var content = editAreaLoader.getValue("'.$this->id.'");
				$("#' . $this->id . '").val(content);
			});

			';

		$jsInit2 = '
	     editAreaLoader.init({
			 id : "'.$this->id.'"		// textarea id
			,syntax: "'.$this->syntax.'"			// syntax to be uses for highlighting
			,start_highlight: true		// to display with highlight mode on start-up
			,show_line_colors: true
			,toolbar: " '.$this->toolbar.'"
			,word_wrap: "'.$this->wordWrap.'"
			,allow_toggle: "'.$this->allowToggle.'"
			,EA_load_callback: "'.$autogrow.'"
			,min_height:"'.$this->minHeight.'"
			,allow_resize: "'.$this->allowResize.'"
			,min_width:"'.$this->minWidth.'"
				
			});
			';

		$cs->registerScript('Yii.EditArea.' . $this->id, $jsInit, CClientScript::POS_BEGIN);

		$cs->registerScript('Yii.EditArea.' . $this->id.'_2', $jsInit2, CClientScript::POS_END);

		parent::init();
	}




	/**
	 * Executes the widget.
	 * This method registers all needed client scripts and renders
	 * the text field.
	 */
	public function run()
	{


		list($name, $asdf) = $this->resolveNameID();
		$this->htmlOptions['id'] = $this->id;
		$this->htmlOptions['style'] = "width: " . $this->width . "; min-width:" . $this->minWidth . "; height: " . $this->height . "; min-height:". $this->minHeight."; max-height:".$this->maxHeight.";";

		echo CHtml::textArea($name, $this->value, $this->htmlOptions);

	}

}