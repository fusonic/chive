<?php

class I18NExportCommand extends CConsoleCommand
{
	
	/**
	 * @see		CConsoleCommand::run()
	 */
	public function run($args)
	{
		// Find all source files (english)
		$files = array(
			'core',
			'dataTypes',
		);
		
		// Create an empty array for all texts
		$strings = array();
		
		// Go through all files
		foreach($files as $file)
		{
			// Load XML
			$xml = new SimpleXMLElement(file_get_contents('messages/en/' . $file . '.xml'));
			
			foreach($xml->entry as $entry)
			{
				$strings[] = (string)$entry;
			}
		}
		
		// Open target file to write
		$fp = fopen("messages/_translation/chive.pot", "w");
		
		// Write headers
		fputs($fp, 'msgid ""' . "\n");
		fputs($fp, 'msgstr ""' . "\n");
		fputs($fp, '"Project-Id-Version: chive 0.2.0\n"' . "\n");
		fputs($fp, '"PO-Revision-Date:\n"' . "\n");
		fputs($fp, '"Last-Translator: Fusonic\n"' . "\n");
		fputs($fp, '"Language-Team: Fusonic\n"' . "\n");
		fputs($fp, '"MIME-Version: 1.0\n"' . "\n");
		fputs($fp, '"Content-Type: text/plain; charset=utf-8\n"' . "\n");
		fputs($fp, '"Content-Transfer-Encoding: 8bit\n"' . "\n");
		fputs($fp, "\n");
		
		// Write all strings
		foreach(array_unique($strings) as $string)
		{
			fputs($fp, 'msgid "' . addcslashes($string, '"') . '"' . "\n");
			fputs($fp, 'msgstr ""' . "\n");
			fputs($fp, "\n");
		}
		
		fclose($fp);
	}
	
}