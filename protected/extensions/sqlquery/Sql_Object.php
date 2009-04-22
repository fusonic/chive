<?php
/**
 *
 * Sql_Object
 * @package Sql
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc Singleton
 */

/**
 *
 * Sql_Object
 * @package Sql
 * @author Thomas Schäfer
 * @since 30.11.2008 07:49:30
 * @desc Singleton
 */

final class Sql_Object {

    private static $properties = array();
    private static $log = array();

    private function __construct(){}
	    
    public static function set($name, $value){
    	self::$properties[$name] = $value;	
    }
    
    private static function setLog($type, $message, $file, $line) {
    	$logString = '<li>';
    	$logString .= '<div class="log-msg">'.$message."</div>";
    	$logString .= '<div class="log-pos">thrown at '.$file." (". $line.")</div>";
    	$logString .= '</li>';
    	self::$log[$type] = $logString;
    }
    
    public static function setWarning($message=null, $file="", $line=""){
    	self::setLog("Warning", $message, $file, $line);	
    }
    
    public static function setError($message=null, $file="", $line=""){
    	self::setLog("Error", $message, $file, $line);
    }
    
    public static function getLog(){
    	return '<div class="log"><ul>'.self::$log.'</ul></div>';
    }
    
    public static function has($name){
    	if(strstr($name,".")){
    		$path = explode(".", $name);
    		$key = array_shift($path);
			$path = implode(".",$path);
    		return self::hasElement($path,self::$properties[$key]);
    	} else {
    		return (isset(self::$properties[$name])) ? true : false;
    	}
    }

    public static function count($name){
    	if(strstr($name,".")){
    		$path = explode(".", $name);
    		$key = array_shift($path);
			$path = implode(".",$path);
    		return count(self::getElement($path,self::$properties[$key]));
    	} else {
    		return (isset(self::$properties[$name])) ? count(self::$properties[$name]) : false;
    	}
    }
    
    public static function length($name){
    	if(strstr($name,".")){
    		$path = explode(".", $name);
    		$key = array_shift($path);
			$path = implode(".",$path);
    		return strlen(self::getElement($path,self::$properties[$key]));
    	} else {
    		return (isset(self::$properties[$name])) ? strlen(self::$properties[$name]) : false;
    	}
    }
    
    public static function get($name){
    	if(self::has($name)) {
			if(strstr($name,".")){
	    		$path = explode(".", $name);
	    		$key = array_shift($path);
				$path = implode(".",$path);
	    		return self::getElement($path,self::$properties[$key]);
			} 		
    		return self::$properties[$name];
    	}
    	return false;
    }
    
    public static function token(){
    	return self::$properties["token"];
    }
    
    public static function lexer(){
    	return self::$properties["lexer"];
    }

	protected static function getElement($path, $data) {
		if(!is_array($path)and strstr($path,".")){$path = explode(".", $path);}
		if(is_array($path)) {
			$key = array_shift($path);
			$path = implode(".",$path);
			return self::getElement($path, $data[$key]);
		} else {
			if(isset($data[$path])) {
				return $data[$path];
			} else {
				return $data;
			}			
		}
	}	
    
	protected static function hasElement($path, $data) {
		if(!is_array($path)and strstr($path,".")){$path = explode(".", $path);}
		if(is_array($path)) {
			$key = array_shift($path);
			$path = implode(".",$path);
			$dat = self::hasElement($path, $data[$key]);
			if(is_array($dat)){
				return $dat;
			} elseif(!empty($dat)) {
				return true;
			} else {
				return false;
			}
		} else {
			return (isset($data[$path]))?true:false;			
		}
	}	

    public static function getAll(){
    	return self::$properties;
    }
    
    public static function clear(){
    	self :: $properties["lexer"] = null;
    	self :: $properties["token"] = null;
    }
    
}

