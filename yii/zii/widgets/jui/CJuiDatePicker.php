<?php
/**
 * CJuiDatePicker class file.
 *
 * @author Sebastian Thiere <sebathi@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.jui.CJuiInputWidget');

/**
 * CJuiDatePicker displays a datepicker.
 *
 * CJuiDatePicker encapsulates the {@link http://jqueryui.com/demos/datepicker/ JUI
 * datepicker} plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('zii.widgets.jui.CJuiDatePicker', array(
 *     'name'=>'publishDate',
 *     // additional javascript options for the date picker plugin
 *     'options'=>array(
 *         'showAnim'=>'fold',
 *     ),
 *     'htmlOptions'=>array(
 *         'style'=>'height:20px;'
 *     ),
 * ));
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI datepicker plugin. Please refer to
 * the {@link http://jqueryui.com/demos/datepicker/ JUI datepicker} documentation
 * for possible options (name-value pairs).
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id: CJuiDatePicker.php 99 2010-01-07 20:55:13Z qiang.xue $
 * @package zii.widgets.jui
 * @since 1.1
 */
class CJuiDatePicker extends CJuiInputWidget
{
	/**
	 * @var string the locale ID (e.g. 'fr', 'de') for the language to be used by the date picker.
	 * If this property is not set, I18N will not be involved. That is, the date picker will show in English.
	 */
	public $language;

	/**
	 * @var string The i18n Jquery UI script file. It uses scriptUrl property as base url.
	 */
	public $i18nScriptFile = 'jquery-ui-i18n.js';

	/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	public function run()
	{
		list($name,$id)=$this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;

		if($this->hasModel())
			echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::textField($name,$this->value,$this->htmlOptions);

		$options=CJavaScript::encode($this->options);

		$js = "jQuery('#{$id}').datepicker($options);";

		if (isset($this->language)){
			$this->registerScriptFile($this->i18nScriptFile);
			$js .= "\njQuery('#{$id}').datepicker('option', jQuery.datepicker.regional['{$this->language}']);";
		}

		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id, $js);
	}
}