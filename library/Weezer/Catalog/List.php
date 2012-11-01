<?php
/**
 * 
 * Clase para generar listados
 * @author scumbagblues
 *
 */
class Weezer_List extends Weezer_Catalog_Form_Abstract{
	
	protected $_table;
	
	public function __construct($table = null){
		$this->_table = $table;
	}
	
	/**
	 * 
	 * M�todo para generar un listado a partir de una tabla (modelo)
	 */
	public function createList(){
		
		$this->addPrefixPath('Weezer_Form_Element_', 'Weezer/Form/Element/', 'element');
		
		//FIXME pasar a un m�todo aparte la obtenci�n de los headers del listado
		$catalog_config 	= Weezer_Catalog_Form_Abstract::getCatalogConfig($this->_table);
		$labels 			= $catalog_config->labels->toArray();
		$list_header_fields = $catalog_config->show_list_fields;
		
		//TODO Terminar la obtenci�n del header, luego el content y por ultimo las acciones
		$header = array();
		
		$content = array();
		
		
		$this->addElement('grid','list',array('header' => $header
											  ,'content' => $content
											  ));
	
	}
}