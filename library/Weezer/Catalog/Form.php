<?php

class Weezer_Catalog_Form extends Weezer_Catalog_Form_Abstract{
	
	//Clases para elementos HTML
	const TEXT_CLASS	= 'input-medium';
	const INT_CLASS 	= 'input-small';
	const ENUM_CLASS    = 'span2';
	const TEXTAREA_CLASS = 'span5';

	
	protected $_max_lenght;
	protected $_table_form_name;
	protected $_edit_form_data;
	protected $_action_form;
	protected $_fields_decorators;
	protected $_field_attribs;
	protected $_decorators_default 	= array('MultiColumns');
	protected $_filters_default 		= array('StringTrim','StripTags');
	protected $_label_submit;
	
	
	
	/**
	 *
	 * Metodo para setear una tabla (modelo) a la forma
	 * @param unknown_type $table_form
	 */
	public function setTable($table_form){
		$this->_table_form_name = $table_form;
	}
	
	/**
	 * 
	 * Método para pasarle la acción Ej: "Agregar usuario"
	 * @param unknown_type $action_form
	 */
	public function setActionForm($action_form){
		$this->_action_form = $action_form;
	}
	
	/**
	 *
	 * Metodo que settea la informacion de la forma
	 * cuando es edicion
	 * @param unknown_type $form_data
	 */
	public function setFormData($form_data){
		$this->_edit_form_data = $form_data;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Form::render()
	 */
	public function render($view = NULL){
		return parent::render($view);	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Form::init()
	 */
	public function init(){
		
		$this->setName( 'frm' . $this->_table_form_name );
		$this->setAction('#');
		/*
		$this->setDecorators(array(
				'FormElements',
    			array('HtmlTag', array('tag' => 'div')),
			));*/
		
		//Path hacia los decoradores
		$this->addElementPrefixPaths(array(
			'decorator' => array(
				'Weezer_Form_Decorator' => 'Weezer/Form/Decorator/'
				)
			)
		);
		
		$form = new $this->_table_form_name();
		$config_table = Weezer_Catalog_Form_Abstract::getCatalogConfig($form->info(Zend_Db_Table::NAME));
        $data_elements = $form->info ( Zend_Db_Table_Abstract::METADATA );
		
        if (is_object($config_table->labels)){
			$label_fields = $config_table->labels->toArray();
		}else{
			$label_fields = array();
		}
		$required_fields = $config_table->required_fields;
		
		//Se asigna el nombre del catalogo al view
		$view = $this->getView();
		$view->catalog_name = $this->_action_form . ' ' . ucfirst($config_table->name->singular);
		
		if($config_table->show_fields){
    		foreach ($config_table->show_fields as $campo){
    			$info_field = $data_elements[$campo];
    			
    			if($info_field){
    				$element_object = $this->_createFormField ( $info_field );
    				//Validación de campos obligatorios
    				if (in_array($element_object->name, $required_fields)){
    					$options['required'] = TRUE;
    				}else{
    					$options['required'] = FALSE;
    				}
    				
    				//Se toman las etiquetas de catalogs.ini
    				if (array_key_exists($campo, $label_fields)){
    					$options['label'] = $label_fields[$element_object->name];
    				}else{
    					//Se toma el nombre por default del campo (su nombre en la BD)
    					$options['label'] = $element_object->name;
    				}
    				
    				if ($element_object->html_type == 'select') {
    					$options['multiOptions'] = $element_object->multioptions;
    					$options['registerInArrayValidator'] = false;
    				}
    				//Se agregan validadores
    				if (!is_null($element_object->validator)){
    					$options['validators'] = $element_object->validator;
    				}else{
    					$options['validators'] = array();
    				}
    				
    				//Se agrega el nombre de la tabla por si se necesita saber en algun otro lado
    				//como un decorador o helper
    				$options['table_name'] = $this->_table_form_name;
    				
				//En este arreglo se pasa la informacion de la form
				//cuando es edicion
				
				$options['form_data'] = $this->_edit_form_data;
				
    				if (!is_null($this->_field_attribs)){
    					if (array_key_exists($campo, $this->_field_attribs)){
    						$options['field_attribs'] = $this->_field_attribs[$campo];
    					}else{
    						$options['field_attribs'] = array('class' => $element_object->class );
    					}
    				}
    				else
    				{
    				    $options['field_attribs'] = array('class' => $element_object->class );
    				}
    				//Se agrega el maxlength para todos los campos varchar
    				if ($this->_max_lenght != ''){
    					$options['field_attribs'] = array_merge($options['field_attribs']
    														   ,array('maxlength' => $this->_max_lenght));
    				}
    				
    				$options ['decorators'] = $this->_decorators_default;
    				if (!is_null($this->_fields_decorators)){
    					if (array_key_exists($campo, $this->_fields_decorators)){
    						$decorador = array($this->_fields_decorators[$campo]);
    						$options['decorators'] = $decorador;
    					}else{
    						$options['decorators'] = $this->_decorators_default;
    					}
    				}
    				
    				$options['disableLoadDefaultDecorators'] = true;
    				//Zend_Debug::dump($options);
    				$element = $this->addElement($element_object->html_type, $element_object->name, $options );
    			}
    		}
    		//FIXME Agregar etiqueta de "guardar" en catalogs/config.ini
    		$this->addElement ( 'submit', $this->_label_submit, array ('class' => 'btn btn-primary btn-large', 'decorators' => array ('Submit' ) ) );
		}
		//$this->addDisplayGroup($config_table->show_fields, 'form_fields');
		
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
		    $resultado[utf8_encode($valor)] = utf8_encode($valor);
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
	
	/**
	 *
	 * Metodo para determinar que tipo de elemento HTML
	 * sera el campo del formulario
	 * @param array $info_field
	 */
	protected function _createFormField($info_field){
		$field_type = $info_field['DATA_TYPE'];
		$element = new stdClass();
		
		if(preg_match('/enum/', $field_type)){
			$field_type_switch = 'enum';
		}else{
			$field_type_switch = $field_type;
		}
		
		switch ($field_type_switch){
			case 'int':
			case 'float':
			case 'decimal':	
				$element->html_type = 'text';
				//$element->class = self::INT_CLASS;
				$element->validator = array('Float');
			break;
			case 'varchar':
				$element->html_type = 'text';
				//$element->class = self::TEXT_CLASS;
				$this->_max_lenght = $info_field['LENGTH'];
				
			break;
			case 'enum':
				$element->html_type = 'select';
				//$element->class = self::ENUM_CLASS;
				$element->multioptions = $this->_getArrayTypeEnum($field_type);
			break;
			case 'text':
				$element->html_type = 'textarea';
				$element->class = self::TEXTAREA_CLASS;
				$this->_max_lenght = '';
			break;
			case 'datetime':
				$element->html_type = 'text';
				//$element->class = self::TEXT_CLASS;
				$this->_max_lenght = '10';
			break;	
			default: $element->html_type = 'text';
					// $element->class = self::TEXT_CLASS;
			break;
		}
		
		if (!$info_field['NULLABLE']){
			$null = TRUE;
		}else{
			$null = FALSE;
		}
		
		$element->nullable = $null;
		$element->name = $info_field['COLUMN_NAME'];
		return $element;
	}
	
	/**
	 * 
	 * Settea los decoradores, atributos o info de la forma
	 * enviada desde el controlador
	 * @param unknown_type $params
	 */
	public function setFormParams($params){
		$num_args = func_num_args();
		$form_params = func_get_arg(0);
		$attribs = $form_params['attribs'];

		if (!empty($params) && is_array($attribs)){
			if ($num_args > 0){
				$form_params = func_get_arg(0);
				$attribs = $form_params['attribs'];
				if (array_key_exists('decorators', $attribs)){
					$form_decorators = $attribs['decorators'];
				}
			
				if (array_key_exists('field_attribs', $attribs)){
					$form_field_attribs = $attribs['field_attribs'];
				}
		    }
		    
		    if(isset($params['labelSubmit'])){
		    	$this->_label_submit = $params['labelSubmit'];
		    }else{
		    	$this->_label_submit = 'Guardar';
		    }
			//die;
			$this->_fields_decorators 	= $form_decorators;
			$this->_field_attribs 		= $form_field_attribs;
		}else{
			if(isset($params['labelSubmit'])){
		    	$this->_label_submit = $params['labelSubmit'];
		    }else{
		    	$this->_label_submit = 'Guardar';
		    }
		}
		
	}
	
	/**
	 *
	 * Se le indica si se enviaran archivos por el formulario
	 * @param bool $flag
	 */
	public function setEncTypeMultipart($flag = false){
		if ($flag){
			$this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
		}
	}
	
	
}