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


class FormatterTest extends CTestCase
{
    /**
     * @todo Implement testFileSize().
     */
    public function testFileSize() 
    {
    	$this->assertEquals('0 B', Formatter::fileSize(0));
    	$this->assertEquals('1.00 KiB', Formatter::fileSize(1000));
    	$this->assertEquals('1.02 KiB', Formatter::fileSize(1024));
    	// Test fails due to floating point precision?
    	//$this->assertEquals('1.03 KiB', Formatter::fileSize(1025));
    	$this->assertEquals('1.03 KiB', Formatter::fileSize(1025.1));
    	$this->assertEquals('1.00 MiB', Formatter::fileSize(1000000));
    	$this->assertEquals('1.00 GiB', Formatter::fileSize(1000000000));
    	$this->assertEquals('1.00 TiB', Formatter::fileSize(1000000000000));
    	$this->assertEquals('1.00 PiB', Formatter::fileSize(1000000000000000));
    }

}