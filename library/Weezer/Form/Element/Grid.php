<?php
/**
 * 
 * Elemento que forma un grid HTML
 * @author ricardo
 *
 */
class Weezer_Form_Element_Grid extends Zend_Form_Element{
	
	public $helper = 'gridform';
	public $options = array();
	
	public function setHeader($header){
		$this->options['header'] = $header;
	}
	
	public function setContent($content){
		$this->options['content'] = $content;
	}
	
	public function setPagination($pagination){
		$this->options['pagination'] = $pagination;
	}
	/*
	public function setForm(Zend_Form $form){
		$this->options['form'] = $form;
	}
	
	public function setFormValues($form_data){
		$this->options['form_values'] = $form_data;
	}*/
}