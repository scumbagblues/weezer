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
		//$excel_autoloader = Zend_Loader_Autoloader::getInstance();
    	//$excel_autoloader->pushAutoloader(new Weezer_ExcelAutoloader());
			
	}
	
	//FIXME corregir lo routes para poder obtener el lenguaje a traves la de la URL
	/*
	protected function _initRoutes(){
		$front_controller = Zend_Controller_Front::getInstance();
		$router = $front_controller->getRouter();
		$router->removeDefaultRoutes();
		
	
		
		
		$router->addRoute(
			'langmodcontrolleraction',
			new Zend_Controller_Router_Route('/:lang/:module/:controller/:action',
	    	  								array('lang' 		=> ':lang',
										           'module' 	=> 'default',
										           'controller' => 'index',
										           'action' 	=> 'index',
	    	  									   'page'			=> '1'
									        ),
	    	  								array('lang' => '[a-z]{2}', 
	    	  								  		 'page' => '\d+'
	    	  								  	    )
	    	)		
		);
		
		$router->addRoute(
	  		'langcontrolleraction',
	  		new Zend_Controller_Router_Route('/:lang/:controller/:action',
									    	  array('lang' => ':lang',
									    	  		 'controller' => 'index',
									    	  		 'action' => 'index',
									    	  		 'page' => '1'
									    	  ),
									    	 array('lang' => '[a-z]{2}', 
	    	  								  		 'page' => '\d+'
	    	  								  	    )
	  		)
		);
	
		/*
		$router->addRoute(
	  		'langindex',
	  		new Zend_Controller_Router_Route('/:lang',
		    	array('lang' 		=> ':lang',
			           'module' 	=> 'default',
			           'controller' => 'index',
			           'action' 	=> 'index',
		    		   	
		        ),
		        
		        array('lang' => '[a-z]{2}')
	  		)
		);
	
		$router->addRoute(
			'mca',
			new Zend_Controller_Router_Route('/:module/:controller/:action',
				array('lang' 		=> 'es',
				  	   'module' 	=> 'default',
				       'controller' => 'index',
				       'action' 	=> 'index'
			    ),
			    array('lang' => '[a-z]{2}')
			)
		);
		
		$router->addRoute(
			'modcontroller',
			new Zend_Controller_Router_Route('/:module/:controller/',
				array('lang' 		=> 'es',
				  	   'module' 	=> 'default',
				       'controller' => 'index',
				       'action' 	=> 'index'
			    ),
			    array('lang' => '[a-z]{2}')
			)
		);
		
		$router->addRoute(
			'mod',
			new Zend_Controller_Router_Route('/:module',
				array('lang' 		=> 'es',
				  	   'module' 	=> 'default',
				       'controller' => 'index',
				       'action' 	=> 'index'
			    ),
			    array('lang' => '[a-z]{2}')
			)
		);*/
	//}
}