<?php

class Default_IndexController extends Weezer_Controller_Base
{

	protected $_table_user = 'Default_Model_Users'; 
	
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        
       $this->view->hola = 'hola';
       //$this->createForm('add', $this->_table_user);
    }
    
	public function catalogformAction(){
		//Accion requerida para mandar llamar el
		//partial de la forma desde cualquier
		//modulo - controlador - accion
	}
    
	/**
	 * TODO
	 * Acciones de ejemplo -- eliminar despues
	 */
    public function addAction(){
    	$this->createForm('add', $this->_table_user);
    	$this->_forward('catalogform','index','default');//Se invoca a la accion - controlador - modulo para obtener el view de listado
    } 
    
    public function editAction(){
    	$this->createForm('edit', $this->_table_user);
    	$this->_forward('catalogform','index','default');
    }


}

