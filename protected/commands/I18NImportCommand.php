<?php

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
		
		// Now try to load translations
		require_once('PEAR/File_Gettext/Gettext/PO.php');
		$test = new File_Gettext_PO('../../translation/protected/messages/_translation/' . $lang . '.po');
		$test->load();
		$data = $test->toArray();
		$strings = array();
		foreach($data['strings'] as $key => $value)
		{
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
		
		@mkdir('messages/' . strtolower($targetLang));
		
		// Translate
		foreach($files as $file)
		{
			$xml = simplexml_load_file('messages/en/' . $file . '.xml');
			$xmlNew = new SimpleXMLElement('<category id="' . $xml['id'] . '" />');
			
			foreach($xml->entry as $entry)
			{
				$entry2 = $xmlNew->addChild('entry', $strings[(string)$entry]);
				$entry2->addAttribute('id', $entry['id']);
			}
			
			$dom = dom_import_simplexml($xmlNew)->ownerDocument;
			$dom->formatOutput = true;
			
			file_put_contents('messages/' . strtolower($targetLang) . '/' . $file . '.xml', $dom->saveXML());
		}
	}
	
}