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


class Formatter {

	/*
	 * @param $_size 		Size in bytes
	 */
	public static function fileSize($_size) {

		$s = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
		$e = floor(log((float)$_size)/log(1000));

		if(!$_size || !pow(1000, $e))
			return 0 . ' ' . $s[0];

		$output = sprintf('%.2f '.$s[$e], round($_size/pow(1000, $e),2));

		return $output;

	}
	
}