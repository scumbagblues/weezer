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
		
		$output  = "<table class='table table-striped table-bordered table-condensed'>";
		$output .= $this->_getGridHeader($header);
		$output .= $this->_getGridContent($content);
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
	 */
	protected function _getGridContent($content_array){
		$html_content = '';
		
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