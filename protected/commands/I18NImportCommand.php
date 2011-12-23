<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

class I18NImportCommand extends CConsoleCommand
{
	
	/**
	 * @see		CConsoleCommand::run()
	 */
	public function run($args)
	{
		// Get all languages
		$languages = array_merge(
			glob('../../translation/protected/messages/_translation/??.po'),
			glob('../../translation/protected/messages/_translation/??_??.po')
		);
		
		// Run for every language
		foreach($languages as $lang)
		{
			$pathinfo = pathinfo($lang);
			$lang = $pathinfo['filename'];
			
			$this->runLanguage($lang);
		}
	}
	
	protected function runLanguage($lang)
	{
		// Find all source files (english)
		$files = array(
			'core',
			'dataTypes',
		);
		
		// Create an empty array for all texts
		$stringKeys = array();
				
		// Go through all files
		foreach($files as $file)
		{
			// Load XML
			$xml = new SimpleXMLElement(file_get_contents('messages/en/' . $file . '.xml'));
			
			foreach($xml->entry as $entry)
			{
				$stringKeys[] = (string)$entry;
			}
		}
		//
		// This is a quick dirty hack because Gettext_PDO does can't handle escaped field delimiters.
		// So i replace all espaced delimiters with -!!- and reverse it afterwards.
		//
		$old = file_get_contents('../../translation/protected/messages/_translation/' . $lang . '.po');
		$replaced = str_replace('\"', '-!!-', $old);
		file_put_contents('../../translation/protected/messages/_translation/' . $lang . '.po', $replaced);
		
		// Now try to load translations
		require_once('File/Gettext/PO.php');
		$test = new File_Gettext_PO('../../translation/protected/messages/_translation/' . $lang . '.po');
		$test->load();
		$data = $test->toArray();
		$strings = array();
		
		file_put_contents('../../translation/protected/messages/_translation/' . $lang . '.po', $old);
		
		foreach($data['strings'] as $key => $value)
		{
			$key = str_replace('-!!-', '"', $key);
			$value = str_replace('-!!-', '"', $value);

			$pos = array_search($key, $stringKeys);
			
			if($pos > -1)
			{
				array_splice($stringKeys, $pos, 1);
				$strings[$key] = $value;
			}
		}
		
		if(count($stringKeys) > 0)
		{
			echo $lang . ' skipped, missing ' . count($stringKeys) . ' terms' . "\n";
			foreach($stringKeys as $key)
			{
				echo "     " . $key . "\n";
			}
		}
		else
		{
			echo $lang . ' imported' . "\n";
		}
			
		if(strlen($lang) == 2)
		{
			$targetLang = $lang . '_' . $lang;
		}
		else
		{
			$targetLang = $lang;
		}

		$finishedTranslated = array(
			"pt_br", "en_gb", "fr_fr", "it_it", "ru_ru", "uk_uk", "pl_pl", "es_es", "hu_hu", "ro_ro", "nl_nl", "sw_sw"
		);

		if(!in_array($targetLang, $finishedTranslated))
		{
			return;
		}

		@mkdir('messages/' . strtolower($targetLang));
		
		// Translate
		foreach($files as $file)
		{
			$xml = simplexml_load_file('messages/en/' . $file . '.xml');
			$xmlNew = new SimpleXMLElement('<category id="' . $xml['id'] . '" />');

			$dom = new DOMDocument("1.0", "utf-8");
			$dom->formatOutput = true;

			$category = $dom->createElement("category", null);
			$category->setAttribute("id", $xml['id']);
			$dom->appendChild($category);
			
			foreach($xml->entry as $entry)
			{
				if(isset($strings[(string)$entry]))
				{
					$child = $dom->createElement("entry", $strings[(string)$entry]);
					$child->setAttribute("id", $entry['id']);
					$category->appendChild($child);
				}
			}

			/**
			 * Reload into new DOM because otherwise formatOutput does not work (whyever ? ..)
			 */
			$content = $dom->saveXml();
			$dom = new DOMDocument("1.0", "utf-8");
			$dom->loadXML($content);
			$dom->formatOutput = true;

			var_dump(file_put_contents('messages/' . strtolower($targetLang) . '/' . $file . '.xml', $dom->saveXML()));
		}
	}
}