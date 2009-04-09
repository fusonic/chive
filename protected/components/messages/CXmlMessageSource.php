<?php

class CXmlMessageSource extends CMessageSource {

	const CACHE_KEY_PREFIX='Yii.CXmlMessageSource.';

	/**
	 * @var integer the time in seconds that the messages can remain valid in cache.
	 * Defaults to 0, meaning the caching is disabled.
	 */
	public $cachingDuration=0;
	/**
	 * @var string the base path for all translated messages. Defaults to null, meaning
	 * the "messages" subdirectory of the application directory (e.g. "protected/messages").
	 */
	public $basePath;

	/**
	 * Initializes the application component.
	 * This method overrides the parent implementation by preprocessing
	 * the user request data.
	 */
	public function init()
	{
		parent::init();
		if($this->basePath===null)
			$this->basePath=Yii::getPathOfAlias('application.messages');

	}

	public function loadMessages($category, $language) {

		$parentMessages = array();

		if(strlen($language) == 5) {
			$parentMessages = self::loadMessages($category, substr($language,0,2));
		}

		$messageFile=$this->basePath.DIRECTORY_SEPARATOR.$language.DIRECTORY_SEPARATOR.$category.'.xml';

		$key=self::CACHE_KEY_PREFIX . $messageFile;

		if($this->cachingDuration>0 && ($cache=Yii::app()->getCache())!==null)
		{
			$key=self::CACHE_KEY_PREFIX . $messageFile;
			if(($data=$cache->get($key))!==false)
				return unserialize($data);
		}

		if(is_file($messageFile))
		{
			$xml = simplexml_load_file($messageFile);

			$messages = array();
			foreach($xml AS $entry) {
				$messages[(string)$entry->attributes()->id] = (string)$entry;
			}

			$messages = array_merge($parentMessages, $messages);

			if(isset($cache))
			{
				$dependency=new CFileCacheDependency($messageFile);
				$cache->set($key,serialize($messages),$this->cachingDuration);
			}

			return $messages;
		}
		else
			return array();

	}

}

?>