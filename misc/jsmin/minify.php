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

include 'jsmin-1.1.1.php';


$dir = '/var/www/dublin/trunk/js/jquery';
$handler = opendir($dir);

$mergedJs = '';

while($file = readdir($handler))
{
	if($file == '.' || $file == '..' || $file == 'all.js' || substr($file, 0, 1) == ".")
		continue;

	echo 'reading ' . $dir . '/' . $file . '<br/>';

	$mergedJs .= "\n\n\n\n" . '/* ' . $file . ' */' . "\n\n";
	$mergedJs .= JSMin::minify(file_get_contents($dir. '/' . $file));
}

closedir($handler);

// Write to file
file_put_contents($dir . '/all.js', $mergedJs);

?>