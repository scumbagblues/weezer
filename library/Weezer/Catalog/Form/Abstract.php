<?php

/**
 * Clase abstracta para las formas
 * @author ricardo
 *
 */

abstract class Weezer_Catalog_Form_Abstract extends Zend_Form{
	
	public static function getCatalogConfig($table_config,$config_file = 'config.ini'){
		
		$config_table	= new stdClass();
		$catalog_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/catalogs/' . $config_file, $table_config);
		
		//$config_table->hidden_fields	= explode(',', $catalog_config->hidden_form_fields);
		$config_table->show_fields      = explode(',', $catalog_config->show_form_fields);
		$config_table->name 			= $catalog_config->catalog_name;
		$config_table->labels 			= $catalog_config->label;
		$config_table->required_fields 	= explode(',',$catalog_config->required_fields);
		$config_table->columns          = explode(',', $catalog_config->column_fields);
		//$config_table->actions          = $catalog_config->actions;
		
		return $config_table;
	}
	
}