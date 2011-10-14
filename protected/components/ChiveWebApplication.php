<?php

/**
 * @property ChiveHttpRequest request
 */
class ChiveWebApplication extends CWebApplication
{
	/**
	 * Sends json output headers, outputs $json and ends the application.
	 * @param string $json
	 */
	public function endJson($json)
	{
		header("Content-type: application/json");
		self::end($json);
	}
}