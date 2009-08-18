<?php 

function rglob($pattern='*', $flags = 0, $path='')
{
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
    return $files;
}

/* example usage: */
chdir('../');

$dirs = array(
	"js",
	"misc",
	"protected",
	"tests",
	"themes",
);

$fileTypes = array(
	"css",
	"js",
	"php",
);

$exclude = array(
	"EditArea",
	"CodePress",
	"/sqlquery/",
	"runtime",
	"ajaxfileupload",
	"hotkey",
	"jquery.js",
	"json.js",
);

$files = array();


foreach($dirs AS $dir)
{
	foreach($fileTypes AS $fileType)
	{
		$files = array_merge($files, rglob("*." . $fileType, 0, $dir));
	}
	
}


foreach($files AS $key=>$file)
{
	
	foreach($exclude AS $excl)
	{
		if(strpos($file, $excl))
		{
			unset($files[$key]);
		}
		
	}
	
	
}

$license = "/*
 * Chive - web based MySQL database management
 * Copyright (C) 2009 Fusonic GmbH
 * 
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */
";


foreach($files AS $key=>$file)
{
	$content = str_replace(array("<?php"), array(""), file_get_contents($file));

	if(strpos($content, "MIT") || strpos($content, "GPL"))
		continue;
	
	echo $file . "<br/>";
	
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