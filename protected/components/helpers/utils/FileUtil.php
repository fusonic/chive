<?php

class FileUtil {

	public static final function readDirectory($_path, $_recursively = false, $_type = false, $_readFilesize = false) {

		$dir = dir($_path);
		$data = array();

		while($file = $dir->read()) {

			if($file == "." || $file == ".." || $file == ".svn")
				continue;

			if($_type != "dir" && is_file($_path . "/" . $file)) {
				if(!$_readFilesize)
					$data[] = $_path . "/" . $file;
				else {
					$data[] = array (
						"name" => $_path . "/" . $file,
						"filesize" => filesize($_path . "/" . $file));
				}
			}

			elseif(is_dir($_path . "/" . $file)) {

				if($_type != "file")
					$data[] = $_path . "/" . $file;

				if($_recursively)
					$data = array_merge(self::readDirectory($_path . "/" . $file, $_recursively, $_type, $_readFilesize), $data);
			}
		}

		return $data;
	}

}

?>