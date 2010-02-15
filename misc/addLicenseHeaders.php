#!/usr/bin/php5
<?php

function rglob($pattern='*', $flags = 0, $path='')
{
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
    return $files;
}

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
 */";

echo "<pre>";

foreach($files AS $key=>$file)
{

	$content = file_get_contents($file);

	preg_match("/\/\*(.*)Chive(.*)\n\40\*\//is", $content, $res);

	if(isset($res[0]) && $res[0])
	{
		$content = str_replace($res[0], $license, $content);
		file_put_contents($file, $content);

		echo $file . "<br/>";

	}

	continue;

	if(strpos($content, "MIT") || strpos($content, "GPL"))
		continue;

	echo $file . "<br/>";

	continue;

	if(substr($file, -3) == "php")
	{
		file_put_contents($file, "<?php\n\n" . $license . $content);
	}
	else
	{
		file_put_contents($file, $license . "\n" . $content);
	}

}


?>