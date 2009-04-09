<?php

class Formatter {

	/*
	 * @param $_size 		Size in bytes
	 */
	public static function fileSize($_size) {

		$s = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
		$e = floor(log($_size)/log(1024));

		if(!$_size || !pow(1000, floor($e)))
			return 0 . ' ' . $s[0];

		$output = sprintf('%.2f '.$s[$e], ($_size/pow(1000, floor($e))));

		return $output;

	}

}

?>