<?php
/**
 * 
 * Clase para generar listados
 * @author scumbagblues
 *
 */
class Weezer_Catalog_List{
	
	protected $_table;
	protected $_view;
	protected $_pagination;
	
	
	public function __construct($table = null,$view = null,$options = NULL){
		$this->_table = $table;
		$this->_view  = $view;
		
		if (isset($options['pagination'])) {
			$this->_pagination = $options['pagination'];
		}
	}
	
	/**
	 * 
	 * M�todo para generar un listado a partir de una tabla (modelo)
	 */
	public function createList(){
		$table_name			= Weezer_Catalog_Form_Abstract::getTableData($this->_table);
		//FIXME No esta obteniendo todas las configuraciones
		$catalog_config 	= Weezer_Catalog_Form_Abstract::getCatalogConfig($table_name->name);
		$labels 			= $catalog_config->labels->toArray();
		$list_header_fields = $catalog_config->show_list_fields;
		$actions			= $catalog_config->actions;
		if (is_array($actions)){
			if (array_key_exists('add', $actions)){
				$url_action = $this->getUrlAction();
				$add_action = new stdClass();
				
				$add_action->list = TRUE;
				if (is_array($catalog_config->name->toArray())){
					$catalog_name = $catalog_config->name->toArray();
					$catalog_name = $catalog_name['plural'];
				}else{
					$catalog_name = $table_name->name;
				}
				$add_action->name = ucfirst($catalog_name);
				$add_action->url  = $url_action . DIRECTORY_SEPARATOR . 'add';
				$this->_view->add = $add_action;
			}
		}
		
		
		$header 	= $this->_getHeaders($labels, $list_header_fields,$actions);
		$content	= $this->_getContent($this->_table,$list_header_fields,$actions);
		
		$pagination_list = $this->_pagination ? TRUE: FALSE;
		
		$options    = array('header' => $header
							,'content' => $content
							,'pagination' => $pagination_list
							);
		
							
							
		//Se le mandan los parametros requeridos al helper gridform
		$list = $this->_view->gridform('list',null,null,$options);
		if (is_array($catalog_config->name->toArray())){
			$catalog_name = $catalog_config->name->toArray();
			$catalog_name = $catalog_name['plural'];
		}else{
			$catalog_name = $table_name->name;
		}
		$this->_view->catalog_name = ucfirst($catalog_name);
		$this->_view->list = $list;
	}
	
	/**
	 * 
	 * M�todo para obtener las cabeceras del listado
	 * @param array $labels
	 * @param string $list_show_fields
	 */
	protected function _getHeaders($labels,$list_show_fields,$actions = NULL){
		$headers = array();
		if (!is_null($actions)){
			if (array_key_exists('edit', $actions) || array_key_exists('delete', $actions)){
				$labels = array_merge($labels,array('actions' => ''));//Se agrega el <th> de las acciones
			}
		}
		foreach ($labels as $key => $value){
			if (in_array($key, $list_show_fields) || $key == 'actions'){
				$headers[$key] = $value;
			}
		}
		
		return $headers;
	}
	
	/**
	 * 
	 * M�todo para obtener el contenido del listado
	 * @param string $table
	 * @param string $show_list_fields
	 */
	protected function _getContent($table,$show_list_fields,$actions = NULL){
		
		$list_table 	= new $table();
		$table_prefix	= Weezer_Catalog_Form_Abstract::getTableData($table);
		$where			= "{$table_prefix->prefix}_activo = '1'";
		$data 			= $list_table->getAll($where);
		
		$content_grid 	= array();
		$cont 			= 0;
		//$html_actions   = $this->_processActions($actions,$data);
		
		foreach ($data as $key => $content){
			if (!is_null($actions)){
				$content = array_merge($content,array('actions' => $this->_processActions($actions, $content)));
			}
			foreach ($content as $header => $val){
				if (in_array($header, $show_list_fields) || $header == 'actions'){
					$content_grid[$cont][$header] = $val;
				}
			}$cont++;
		}
	
		return $content_grid;
	}
	
	/**
	 * M�todo para procesar las acciones enviadas y generar su html correspondiente
	 * para las acciones edit & delete
	 */
	
	protected function _processActions($actions,$data){
		
		$table_prefix	= Weezer_Catalog_Form_Abstract::getTableData($this->_table);
		$url_action		= $this->getUrlAction();
		//TODO modificar cuando se agreguen los permisos
		$html_actions = '<div class="btn-toolbar">
  							<div class="btn-group">';
		$row_id = $data[$table_prefix->prefix . '_id'];
		
		if ($actions['edit']){
			//HTML de edit
			$html_actions .= "<a title='Editar' class='btn btn-inverse' href='{$url_action}/edit/id/{$row_id}'><i class='icon-pencil icon-white'></i></a>";
		}
		if ($actions['delete']){
			//HTML de delete
			$html_actions .= "<a title='Eliminar' class='btn btn-inverse' href='javascript: weezer.deleteaction(\"{$url_action}/delete/id/{$row_id}\")'><i class='icon-trash icon-white'></i></a>";
		}
	
		$html_actions .= "</div></div>";
		
		return $html_actions;
	}
	
	/**
	 * 
	 * Metodo para obtener la URL de las acciones de los listados
	 */
	public function getUrlAction(){
		//Se obtiene el front controller
		$front 			= Zend_Controller_Front::getInstance();
		$baseUrl 		= $front->getBaseUrl();//url base
		$module			= $front->getRouter()->getFrontController()->getRequest()->getModuleName();
		$controller 	= $front->getRouter()->getFrontController()->getRequest()->getControllerName();
		$url_action 	= $baseUrl . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $controller;
		
		return $url_action;
	}
}