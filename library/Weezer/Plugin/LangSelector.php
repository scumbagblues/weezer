<?php

class Weezer_Plugin_LangSelector extends Zend_Controller_Plugin_Abstract{
	
	public function preDispatch(Zend_Controller_Request_Abstract $request){
		
		//$lang = $request->getParam('lang','');
		//FIXME corregir que el lenguaje se obtenga a traves del .ini
		//tambien corregir lo routes para poder obtener el lenguaje a traves la de la URL
		$lang = 'es';
		
		switch ($lang){
			case 'es':
				$locale = 'es_MX';
			break;
				 
			case 'en':
				$locale = 'en_CA';
			break;

			default:
				 $request->setParam('lang','es');
				 $locale = 'es_MX';	
			break;
		}
		
		//$lang = $request->getParam('lang','');
		$zl = new Zend_Locale();
		$zl->setLocale($locale);
		Zend_Registry::set('Zend_Locale', $zl);
		
		$frontController = Zend_Controller_Front::getInstance();		
		//Se instancia el traductor
		$translate = new Weezer_Translate();
		$translate->setup($lang);
	}
}