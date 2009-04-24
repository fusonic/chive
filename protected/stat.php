<?php

$extensions = Array("php", "htm", "js", "css", "tpl");
$aExcludedirs = Array("./runtime/");
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