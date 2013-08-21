<?php
/**
 * 
 * Helper para el elemento Grid y crear una tabla generica con su
 * header y su contenido de forma dinamica
 * @author scumbagblues
 *
 */
class Weezer_View_Helper_Gridform extends Zend_View_Helper_Abstract{
	
	public function gridform($name, $value=null, $attribs=null, $options=null){
		
		$header 	= $options['header'];
		$content	= $options['content'];
		if (isset($options['content'])){
			$pagination = $options['pagination'];
		}else{
			$pagination =FALSE;
		} 
		
		$output  = "<table class='table table-striped table-bordered table-condensed'>";
		$output .= $this->_getGridHeader($header);
		$output .= $this->_getGridContent($content,$pagination);
		$output .= '</table>';
	
		return $output;
	}
	
	/**
	 * 
	 * Método para obtener los headers de la tabla
	 * @param array $header_array
	 */
	protected function _getGridHeader($header_array){
		$html_header = '<thead>';
		foreach ($header_array as $key => $header){
			$html_header .= "<th>{$header}</th>";
		}
		$html_header .= '</thead>';
		
		return $html_header;
	}
	
	/**
	 * 
	 * Método para obtener el contenido de la tabla
	 * @param array $content_array
	 * @param bool $pagination
	 */
	protected function _getGridContent($content_array,$pagination = FALSE){
		$html_content = '';
		
		if ($pagination){
			$paginator = Zend_Paginator::factory($content_array);
        	$paginator->setItemCountPerPage('10');
        	//Se obtiene el parametro page para saber en que pagina se encuentra
        	$page_param = Zend_Controller_Front::getInstance()->getRequest()->getParam('page');
        	$paginator->setCurrentPageNumber($page_param);
        	$content_array = $paginator;
        	//Se envia el paginador a la vista
	        $this->view->pagination = $paginator;
	        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
		}else{
			//No se mostrara el paginator
		}
		

		foreach ($content_array as $key => $content){
			$html_content .= '<tr>';
			foreach ($content as $k => $value){
				$html_content .= "<td>{$value}</td>";
			}
			$html_content .= '</tr>';
		}

				
		return $html_content;
	}
}