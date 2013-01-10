<?php

/**
 * 
 * Clase que inicia el traductor
 * @author scumbagblues
 *
 */
class Weezer_Translate{
	
	public function setup($lang){
		$translate 		= new Zend_Translate('csv'
											 ,'../resources/languages/' . $lang . '.csv' 
											 ,$lang
											 ,array('disableNotices'=>true));
											 
		$translate_form = new Zend_Translate('array'
										     ,APPLICATION_PATH . '/../resources/languages/'
										     ,$lang
										     ,array('scan' => Zend_Translate::LOCALE_DIRECTORY,'disableNotices'=>true));
										     
		//Se agrega el traductor por default de zend
		$translate->addTranslation($translate_form);
		//Save translator for later
		Zend_Registry::set('Zend_Translate', $translate);
	}
	
	
}