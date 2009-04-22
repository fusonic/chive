<?php

/**
 *
 * Sql_InterfaceCompiler
 * @package Sql
 * @subpackage Sql_Compiler
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc interface for sql compiler
 */
interface Sql_InterfaceCompiler {
	/**
	 * compile
	 * @desc common sql compile call method
	 */
    public static function compile($tree);
    
}

