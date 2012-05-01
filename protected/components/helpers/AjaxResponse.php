<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


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
	private $jsCode = array();

	/**
	 * Adds a notification to the response which will be shown on the client's UI.
	 *
	 * @param	string					Type
	 * @param	string					Title
	 * @param	string					Message
	 * @param	string					Code
	 * @param	string					Options
	 */
	public function addNotification($type, $title, $message = false, $code = false, $options = false)
	{
		$this->notifications[] = array(
			'type' => $type,
			'title' => ($title ? $title : Yii::t('core', $type)),
			'message' => $message,
			'code' => htmlspecialchars($code),
			'options' => $options,
		);
	}

	/**
	 * Adds data to the response.
	 *
	 * @param	string					Key
	 * @param	string					Value
	 */
	public function addData($name, $value)
	{
		if($name !== null)
		{
			$this->data[$name] = $value;
		}
		else
		{
			if($this->data)
			{
				$this->data += $value;
			}
			else
			{
				$this->data = $value;
			}
		}
	}

	/**
	 * Adds JavaScript code to be executed on the client side.
	 *
	 * @param	string					JavaScript code to execute
	 */
	public function executeJavaScript($code)
	{
		$this->jsCode[] = $code;
	}

	/**
	 * Returns the JSON representation of the response.
	 *
	 * @return	string
	 */
	public function __toString()
	{
		$data = array(
			'redirectUrl' => $this->redirectUrl,
			'reload' => $this->reload,
			'refresh' => $this->refresh,
			'notifications' => $this->notifications,
			'data' => $this->data,
			'js' => $this->jsCode,
		);
		
		return CJSON::encode($data);

	}
}