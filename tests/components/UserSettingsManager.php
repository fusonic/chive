<?php

class UserSettingsManagerTest extends TestCase
{

	protected static $host = '__testcase';
	protected static $user = '__testcase';

	protected $mgr;

	protected function setUp()
	{
		$this->mgr = new UserSettingsManager(self::$host, self::$user);
	}

	/**
	 * Tests getting and setting of settings.
	 */
	public function testSettings()
	{
		$rand = mt_rand(0, 300);
		$rand2 = mt_rand(0, 300);

		// Read default setting
		$this->assertEquals(250, $this->mgr->get('sidebarWidth'));

		// Set setting which has no scope/object
		$this->mgr->set('sidebarWidth', $rand);
		$this->assertEquals($rand, $this->mgr->get('sidebarWidth'));

		// Set setting with scope
		$this->mgr->set('entriesPerPage', $rand, 'databases.tables');
		$this->assertEquals($rand, $this->mgr->get('entriesPerPage', 'databases.tables'));
		$this->assertEquals($rand, $this->mgr->get('entriesPerPage', 'databases.tables', $rand));

		// Set setting with scope and object
		$this->mgr->set('entriesPerPage', $rand, 'databases.tables', 'project_com2date');
		$this->assertEquals($rand, $this->mgr->get('entriesPerPage', 'databases.tables', 'project_com2date'));

		// Set array
		$this->mgr->set('entriesPerPage', array($rand, $rand2), 'databases.tables');
		$this->assertEquals('a:2:{i:0;i:' . $rand . ';i:1;i:' . $rand2 . ';}', serialize($this->mgr->get('entriesPerPage', 'databases.tables')));
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
		$rand = mt_rand(0, 300);
		$rand2 = mt_rand(0, 300);

		// Set value and save settings
		$this->mgr->set('sidebarState', $random);
		$this->mgr->set('entriesPerPage', array($rand, $rand2), 'databases.tables');
		$this->mgr->saveSettings();

		// Create another manager instance
		$mgr = new UserSettingsManager(self::$host, self::$user);

		// Compare values
		$this->assertEquals($random, $mgr->get('sidebarState'));
		$this->assertEquals('a:2:{i:0;i:' . $rand . ';i:1;i:' . $rand2 . ';}', serialize($this->mgr->get('entriesPerPage', 'databases.tables')));
	}

}

?>