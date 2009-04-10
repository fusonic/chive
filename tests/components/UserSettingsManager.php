<?php

require_once('yii.php');

class UserSettingsManagerTest extends TestCase
{

	protected static $host = '__testcase';
	protected static $user = '__testcase';

	protected $mgr;

	protected function setUp()
	{
		parent::setUp();
		$this->mgr = new UserSettingsManager(self::$host, self::$user);
	}

	/**
	 * Tests getting and setting of settings.
	 */
	public function testSettings()
	{
		$rand = mt_rand(0, 300);

		$this->assertEquals(true, false);

		// Set setting which has no scope/object
		$this->mgr->set('sidebarWidth', $rand);
		$this->assertEquals($rand, $this->mgr->get('sidebarWidth'));

		// Set setting with scope
		$this->mgr->set('entriesPerPage', $rand, 'databases.tables');
		$this->assertEquals($rand, $this->mgr->get('entriesPerPage', 'databases.tables'));

		// Set setting with scope and object
		$this->mgr->set('entriesPerPage', $rand, 'databases.tables', 'project_com2date');
		$this->assertEquals($rand, $this->mgr->get('entriesPerPage', 'databases.tables', 'project_com2date'));
	}

	/**
	 * Tests UserSettingsManager for exceptions when trying to get settings
	 * which are not defined.
	 *
	 * @expectedException CException
	 */
	public function testInvalidSetting()
	{
		$this->mgr->get(md5(microtime()));
	}

	/**
	 * Tests UserSettingsManager for exceptions when trying to set settings
	 * which are not defined.
	 *
	 * @expectedException CException
	 */
	public function testInvalidSettingSet()
	{
		$this->mgr->set(md5(microtime()), 'testvalue');
	}

	/**
	 * Tests UserSettingsManager for exceptions when trying to get settings
	 * which are not defined for this scope.
	 *
	 * @expectedException CException
	 */
	public function testInvalidSettingScope()
	{
		$this->mgr->get('sidebarState', md5(microtime()));
	}

	/**
	 * Tests UserSettingsManager for exceptions when trying to set settings
	 * which are not defined for this scope.
	 *
	 * @expectedException CException
	 */
	public function testInvalidSettingScopeSet()
	{
		$this->mgr->set('sidebarState', 'testvalue', md5(microtime()));
	}

	/**
	 * Saves user settings, opens them again with another manager object and
	 * tries to read the settings.
	 */
	public function testSaving()
	{
		// Create random value
		$random = md5(microtime());

		// Set value and save settings
		$this->mgr->set('sidebarState', $random);
		$this->mgr->saveSettings();

		// Create another manager instance
		$mgr = new UserSettingsManager(self::$host, self::$user);

		// Compare values
		$this->assertEquals($random, $mgr->get('sidebarState'));
	}

}

?>