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


$extensions = Array("php", "htm", "js", "css", "tpl");
$aExcludedirs = Array("./runtime/", "./assets/", "./extensions/");
$aExcludefiles = Array();

function getsize($folder) {
	global $extensions, $aExcludedirs, $aExcludefiles;
	if(!in_array($folder, $aExcludedirs)) {
		$oDir = dir($folder);
		while($sFile = $oDir->read()) {
			if($sFile != "." && $sFile != ".." && $sFile != ".svn" && !in_array($folder.$sFile, $aExcludefiles)) {
				if(is_file($folder.$sFile)) {
					if(in_array(substr($sFile, strrpos($sFile, ".") + 1), $extensions)) {
						$size = $size + filesize($folder.$sFile);
					}
				} elseif(is_dir($folder.$sFile)) {
					$size = $size + getsize($folder.$sFile."/");
				}
			}
		}
	}
	return $size;
}

function getlines($folder) {
	global $extensions, $aExcludedirs, $aExcludefiles;
	if(!in_array($folder, $aExcludedirs)) {
		$oDir = dir($folder);
		while($sFile = $oDir->read()) {
			if($sFile != "." && $sFile != ".." && $sFile != ".svn" && !in_array($folder.$sFile, $aExcludefiles)) {
				if(is_file($folder.$sFile)) {
					if(in_array(substr($sFile, strrpos($sFile, ".") + 1), $extensions)) {
						$size = $size + count(file($folder.$sFile));
					}
				} elseif(is_dir($folder.$sFile)) {
					$size = $size + getlines($folder.$sFile."/");
				}
			}
		}
	}
	return $size;
}

function getfiles($folder) {
	global $extensions, $aExcludedirs, $aExcludefiles;
	if(!in_array($folder, $aExcludedirs)) {
		$oDir = dir($folder);
		$size = 0;
		while($sFile = $oDir->read()) {
			if($sFile != "." && $sFile != ".." && $sFile != ".svn" && !in_array($folder.$sFile, $aExcludefiles)) {
				if(is_file($folder.$sFile)) {
					if(in_array(substr($sFile, strrpos($sFile, ".") + 1), $extensions)) {
						$size++;
					}
				} elseif(is_dir($folder.$sFile)) {
					$size = $size + getfiles($folder.$sFile."/");
				}
			}
		}
	}
	return $size;
}

echo "Dateien: ".getfiles("./")."\n";
echo "Zeichen: ".str_replace(",", ".", number_format(getsize("./")))."\n";
echo "Zeilen: ".str_replace(",", ".", number_format(getlines("./")));

?>