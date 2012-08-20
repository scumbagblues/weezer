<?php

class Weezer_ExcelAutoloader implements Zend_Loader_Autoloader_Interface{
	
	public function autoload($class)
    {
    	if ('PHPExcel' != $class){
            return false;
        }
        require_once 'PHPExcel.php';
        return $class;
    }
	
}