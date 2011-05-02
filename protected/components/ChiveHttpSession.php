<?php

class ChiveHttpSession extends CHttpSession
{
	/**
	 * @see yii/CHttpSession::setSavePath()
	 */
	public function setSavePath($value)
	{
		if(($value=realpath($value))===false || !is_dir($value) || !is_writable($value))
		{
			throw new CException(Yii::t('yii','Application runtime path "{path}" is not valid. Please make sure it is a directory writable by the Web server process.',
				array('{path}' => $value)));
		}
		
		parent::setSavePath($value);	
	}
}