<?php
/**
 * 
 * Clase abstracta que contiene los elementos comunes para la creacion de decoradores
 * @author scumbagblues
 *
 */
class Weezer_Form_Decorator_CompositeAbstract extends Zend_Form_Decorator_Abstract{
	
	public function buildInput() {
		$element 	= $this->getElement();//Se obtiene el Zend_Form_Element_Text
		$helper 	= $element->helper;//Se obtiene el helper que crea el input "formText", "formSelect" ..etc

		switch ($helper){
			case 'formText':
				$html_element = $element->getView()->$helper( $element->getName (), $element->getValue (), $element->field_attribs );
				break;
			case 'formTextarea':
				if (!empty($element->field_attribs)){
					$element->field_attribs = array_merge($element->field_attribs,array ('rows' => '10' ));
				}else{
					$element->field_attribs = array ('rows' => '10' );
				}
				
				$html_element = $element->getView()->$helper( $element->getName (), $element->getValue (), $element->field_attribs );
			break;
			case 'formSelect':
				$html_element = $element->getView()->$helper($element->getName(), $element->getValue(), $element->field_attribs, $element->options);
			break;
			case 'formCheckbox':
				$options = $element->getAttribs();
				$element->checked_options = $options['options'];
				$html_element = $element->getView()->$helper($element->getName(), $element->getValue(), $element->field_attribs, $element->checked_options);
			break;
			case 'formButton':
				$html_element = $element->getView()->$helper( $element->getName(), $element->getValue(), $element->field_attribs );
			break;
			case 'formFile':
				$html_element = $element->getView()->$helper( $element->getName(), $element->field_attribs );
			break;	
			case 'formRadio':
				$html_element = $element->getView()->$helper($element->getName(), $element->getValue(), $element->field_attribs);
			break;
			default:
				$html_element = $element->getView()->$helper( $element->getName(), $element->getValue(),  $element->field_attribs );
		}
		
		return $html_element;
	}
}