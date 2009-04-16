<?php

class AjaxResponse
{

	/*
	 * Public members
	 */
	public $redirectUrl;		// Redirects the user to the given url

	public $refresh;			// Refresh content in center area
	public $reload;				// Reload the complete site

	/*
	 * Private members
	 */
	private $data;
	private $notifications;

	/*
	 * Notifications
	 */
	public function addNotification($type, $message, $code = false, $options = false)
	{
		$this->notifications[] = array(
			'type' => $type,
			'message' => $message,
			'code' => $code,
			'header' => ($header ? $header : Yii::t('core', $type)),
			'options' => $options,
		);

	}

	/*
	 * Data
	 */

	public function addData($name, $value)
	{

		if($name != null)
			$this->data[$name] = $value;
		else
			$this->data = $value;

	}

	public function send()
	{
		Yii::app()->end($this);
	}

	public function __toString() {

		$data = array(
			'redirectUrl'=>$this->redirectUrl,
			'reload'=>$this->reload,
			'notifications'=>$this->notifications,
			'data' => $this->data,
		);

		return json_encode($data);

	}

}