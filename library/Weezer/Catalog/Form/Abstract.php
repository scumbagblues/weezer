<?php

/**
 * Clase abstracta para las formas
 * @author ricardo
 *
 */

abstract class Weezer_Catalog_Form_Abstract extends Zend_Form{
	
	public function getCatalogConfig($table_config_name,$config_file = 'config.ini'){
		
		$config_table	= new stdClass();
		$catalog_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/catalogs/' . $config_file, $table_config_name);
		
		//$config_table->hidden_fields	= explode(',', $catalog_config->hidden_form_fields);
		$config_table->show_fields      = explode(',', $catalog_config->show_form_fields);
		$config_table->name 			= $catalog_config->catalog_name;
		$config_table->labels 			= $catalog_config->label;
		$config_table->required_fields 	= explode(',',$catalog_config->required_fields);
		$config_table->columns          = explode(',', $catalog_config->column_fields);
		$config_table->show_list_fields = explode(',', $catalog_config->show_list_fields);
		$acciones          				= $catalog_config->action;
		
		if (!is_null($acciones)){
			$config_table->actions = $catalog_config->action->toArray();
		}

		return $config_table;
	}
	
	/**
	 * 
	 * Método que obtiene el nombre del modelo dado
	 * Tambien su prefijo
	 * @string unknown_type $table
	 * @throws Exception
	 */
	public static function getTableData($table){
		
		
		$model = new $table();
		$table_class_data = new stdClass();
		
		if ($model instanceof Zend_Db_Table_Abstract){
			$table_class_data->name =$model->info(Zend_Db_Table::NAME);
			$table_class_data->prefix = $model->_table_prefix;
		}else{
			throw new Exception('No se pudo obtener el nombre de la tabla');
		}
	
		return $table_class_data;
	}
	
}