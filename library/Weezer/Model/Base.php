<?php
/**
 * 
 * Modelo base para los catalogos
 * @author scumbagblues
 *
 */

class Weezer_Model_Base extends Zend_Db_Table_Abstract{
	
	public $_table_prefix = null;
	
	public function init(){
		
	}
	
	public function preSave(& $form, & $form_data){
		return TRUE;
	}
	
	public function postSave($values){
		return TRUE;
	}
	
	/**
	 * 
	 * Método que obtiene la información de un registro
	 * por su ID
	 * @param unknown_type $param
	 */
	public function getData($param = null){
		if (!is_null($param)){
			$campo_id = "{$this->_table_prefix}_id =  {$param}";
		}else{
			$campo_id = null;
		}
		$row = $this->fetchRow($campo_id);
        if (!$row) {
            throw new Exception("No se encontro el registro con ID: {$param}");
        }
        return $row->toArray();
	}
	
	/**
	 * 
	 * Método para obtener un row de acuerdo a su clausula "where"
	 * @param unknown_type $where
	 * @param unknown_type $order
	 */
	public function getRow($where, $order=null){
        $row = $this->fetchRow($where, $order);
        if(! $row)
        {
            return array();
        }
        return $row->toArray();
    }
    
    /**
     * 
     * Método para obtener un array de registros
     * @param unknown_type $where
     * @param unknown_type $order
     */
    public function getAll($where=null, $order=null){
        //devuelve todos los registros de la tabla filtrado y ordenado según los parámetros enviados
        return $this->fetchAll($where, $order)->toArray();
    }
    
    public function getPrefix(){
        return $this->_table_prefix;
    }
    
	public function getDefaultData()
    {
	    $data["{$this->_table_prefix}_uid"] = Zend_Auth::getInstance()->getIdentity()->usu_id;
	    $data["{$this->_table_prefix}_udt"] = date('Y-m-d H:i:s');
	    
	    return $data;
    }
    
	public function addElements($data){
	    //TODO insertar datos
	    //TODO crear log
	    $data = array_merge($data, $this->getDefaultData());
	    $this->insert($data);
	}
	
	public function updateElements($data,$id = null,$where = null){
		
		if (!is_null($id)){
			 $where_update = "{$this->_table_prefix}_id = '{$id}'";
		}else if (!is_null($where)){
			$where_update = $where;
		}
		$this->update($data, $where_update);
	}
	
}