<?php

class Weezer_Form_Decorator_Submit extends Zend_Form_Decorator_Abstract{
	
	protected $_format = " <div class='form-actions'>
            					<input type='submit' class='btn btn-primary' value=\"%s\"/>
            			   </div>";
 
    public function render($content){
       $element = $this->getElement();
       $label   = ($element->getLabel());
       $value   = htmlentities($element->getValue());
       $markup  = sprintf($this->_format, $label, $value);
  
       return $markup;
    }   
}