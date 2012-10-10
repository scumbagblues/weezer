<?php

class Weezer_Form extends Zend_Form{
	
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Form::render()
	 */
	public function render($view = NULL){
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Form::init()
	 */
	public function init(){
		
	}
	
	/**
	 *
	 * Metodo para obtener un array si el valor de un formulario
	 * es del tipo enum
	 * @param unknown_type $type
	 */
	protected function _getArrayTypeEnum($type){
		$return_type = $type;
		$parts = array("'", '(',')', 'enum');//Se eliminan las comillas simples,parentesis y la palabra enum
	
		foreach ($parts as $key_part){
			$return_type = str_replace($key_part, '', $return_type);
		}

		$return_type = explode(',', $return_type);
		//agregar valor vacio default
		array_unshift($return_type, '');
		
		//arreglo llave=valor
		foreach($return_type as $valor)
		{
		    $resultado[$valor] = $valor;
		}
		
		return $resultado;
	}
	
	/**
	 *
	 * Metodo para agregar atributos a una forma
	 * Ej: array('class' => 'form-horizontal')
	 * Se pasara este arreglo desde el controlador
	 * @param array $attribs
	 */
	public function setFormAttribs($attribs){
		if (is_array($attribs) && !is_null($attribs)){
			$this->_attribs = $attribs; //Atributos para la forma
		}
	}
	
	
}