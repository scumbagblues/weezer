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
	protected $_redirect_after_post;
	protected $_label_submit;

	
	public function getFormFields(){
		
	}
	
	/**
	 * 
	 * Metodo para crear la forma ya sea para agregar o editar
	 * @param string $type   | el tipo de forma agregar = 'add' o editar = 'edit'
	 * @param string $table  | la tabla de la forma
	 * @param array  $params | parametros a enviar a la forma hasta el momento recibe: 'decorators','enctype_flag','redirect'
	 */
	public function createForm($type,$table,$params = array()){
		
		$this->_form_table 	= $table;
		$model 				= new $this->_form_table ();
		$this->setTypeForm($type);
		$form_data = '';
		//Se pasa la bandera para saber si es tiene que cambiar la forma
		//para procesar archivos
		if (isset($params['attribs']['enctype_flag'])){
			$flag_enc_type = $params['attribs']['enctype_flag'];
		}else{
			$flag_enc_type = FALSE;
		}
		if ($this->getRequest()->isPost()){
			$form_data = $this->getRequest()->getPost();
		}else{
			//Si es edición
			if (!$this->_is_new){
				$db_id = $this->_getParam('id');
				$form_data = $model->getData($db_id);
			}
		}
		
		if (isset($params['redirect'])){
			$redirect_array = $params['redirect'];
		}else{
			$redirect_array = array();
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
		//var_dump($form);die;
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()){
			//Si la forma es valida y el preSave devuelve TRUE..
			if ($form->isValid($form_data) &&  $model->preSave($form, $form_data)){
				//Se guardan los datos
				if ($this->_is_new){
					$model->addElements($form_data);
				}else{
					//update
					$id = $this->_getParam('id');
					$model->updateElements($form_data,$id);
				}
				if ($model->postSave($form_data)){
					//Se redirige en caso de asi indicarlo
					$this->redirectAfterPost($redirect_array);
				}else{
					
				}
				
			}else{
				$form->populate($form_data);
			}
		}else{
			//Si es edici�n
			if (!$this->_is_new){
				$form->populate($form_data);
			}
		}
	}
	
	
	public function deleteRow($table){
		$id = $this->_getParam('id');
		$model = new $table();
		$model->deleteElement($id);
		$this->redirectAfterPost();
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
	
	public function redirectAfterPost($params = array()){
		
		if (!empty($params)){
			$module 	= $params['module'];
			$controller = $params['controller'];
			$action 	= $params['action'];
		}else{
			$url_params 		= $this->getRequest()->getParams();
			$module 			= $url_params['module'];
			$controller 		= $url_params['controller'];
		}
		
		$url = "{$module}/{$controller}/$action";

		if ($this->_redirect_after_post){
			$this->_redirect($url);
		}
	}
	
	/**
	 * 
	 * Metodo que invoca al listado
	 * @param string $table
	 */
	public function createList($table,$options = array()){
		$list = new Weezer_Catalog_List($table,$this->view,$options);
		$list->createList();
	}
}