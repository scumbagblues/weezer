<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initJquery() {

		$view = new Zend_View($this->getOptions());
		$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");

		$server = $_SERVER['PHP_SELF'];
		$base_url = substr($server, 0, -(strlen($server) - strpos($server, 'index.php')));
		$view->jQuery()->enable()
			->setLocalPath($base_url .'js/jquery/jquery-1.7.1.min.js')
			->setUiLocalPath($base_url .'js/jquery/jquery-ui-1.8.13.custom.min.js')
			->uiEnable();//enable ui
		//Se instancia autoloader para libreria PHPExcel
		$excel_autoloader = Zend_Loader_Autoloader::getInstance();
    	$excel_autoloader->pushAutoloader(new Weezer_ExcelAutoloader());
			
	}

}

