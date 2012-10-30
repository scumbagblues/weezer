<?php
/**
 * Controlador generico para las acciones de un catalogo
 * @author scumbagblues
 *
 */

class Weezer_Controller_Base extends Zend_Controller_Action{
	
	protected $_is_new;
	protected $_action_form;
	protected $_form_table;
	protected $_redirect_after_post = FALSE;
	
	public function getFormFields(){
		
	}
	
	public function createForm($type,$table,$params = array()){
		
		$this->_form_table = $table;
		$model = new $this->_form_table;
		
		if ($this->getRequest()->isPost()){
			$form_data = $this->getRequest()->getPost();
		}else{
			//Si es edición
			if (!$this->_is_new){
				$db_id = $this->_getParam('id');
				$form_data = $model->getData($db_id);
			}
		}
		
		//Se pasa la bandera para saber si es tiene que cambiar la forma
		//para procesar archivos
		if (isset($params['attribs']['enctype_flag'])){
			$flag_enc_type = $params['attribs']['enctype_flag'];
		}else{
			$flag_enc_type = FALSE;
		}
		
		$form_params = array(
							'table' => $this->_form_table,
							'formAttribs' => array(
								'class' => 'form-horizontal'
							),
							'actionForm' 		=> $this->_action_form,
							'formParams' 		=> $params,
							'formData'  		=> $form_data,
							'encTypeMultipart' 	=> $flag_enc_type,
						);
		
		
		$form = new Weezer_Catalog_Form($form_params);
		
		$this->view->form = $form;
	}
	
	/**
	 * 
	 * Determina el tipo de catalogo agregar/editar
	 * @param unknown_type $type
	 */
	public function setTypeForm($type){
		
		if ($type == 'add'){
			$this->_is_new = TRUE;
			$this->_action_form = 'Agregar';
		}else{
			$this->_is_new = FALSE;
			$this->_action_form = 'Editar';
		}
	}
	
	public function redirectAfterPost(){
		
	}
	
}