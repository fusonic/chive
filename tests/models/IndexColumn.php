<?php

class IndexColumnTest extends TestCase
{

	/**
	 * Tests some config functions.
	 */
	public function testConfigFunctions()
	{
		// Create new schema
		$index = new IndexColumn();

		// Check return types
		$this->assertTrue(is_array($index->safeAttributes()));
		$this->assertTrue(is_array($index->attributeLabels()));
		$this->assertTrue(is_array($index->rules()));
		$this->assertTrue(is_array($index->relations()));
		$this->assertTrue(is_array($index->primaryKey()));
	}

}

?>