<?php

class Formatter {

	/*
	 * @param $_size 		Size in bytes
	 */
	public static function fileSize($_size) {

		$s = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
		$e = floor(log($_size)/log(1024));

		if(!$_size)
			return 0 . ' ' . $s[0];

		$output = sprintf('%.2f '.$s[$e], ($_size/pow(1024, floor($e))));

		return $output;

	}

}

?>