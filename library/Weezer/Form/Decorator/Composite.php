<?php
/**
 * Decorador generico para input,select,textarea,checkbox,button
 * @author scumbagblues
 *
 */

class Weezer_Form_Decorator_Composite extends Zend_Form_Decorator_Abstract{
	
protected $_class_label = 'control-label';
	
	//Crea el html completo de etiqueta, input y errores
	public function buildLabelInput() {
		$label = '';
		$element = $this->getElement ();
		$html_label_input = "<div class='control-group {$element->class}'>";
		
		//Si el elemento es obligatorio antepone un asterisco
		if ($element->isRequired ()) {
			$label .= '*';
		}
		//Se obtiene la etiqueta
		$label .= $element->getLabel ();

		//Si se tiene traducción se aplica
		if ($translator = $element->getTranslator ()) {
			$label .= $translator->translate ( $label );
		}
		
		$label .= ':';
		//en caso de que no se mande una etiqueta esta quedara vacia
		if (is_null($element->getLabel())){
			$label = '';
		}
		
		//Se obtiene toda la etiqueta label
		$html_label = $element->getView ()->formLabel ( $element->getName (), ($label), array ('class' => $this->_class_label ) );
		//Se agrega la etiqueta label que se acaba de generar
		$html_label_input .= $html_label;
		$html_label_input .= "<div class='controls'>";
		//Para agregar una clase al input se hace desde la forma ex: Default_Form_Login
		//Se construye el input
		$input = $this->buildInput ();
		//Se construye el msj de error
		$error_message = $this->buildErrors ();
		if (!is_null($element->leyenda)){
			$legend = $element->leyenda;
		}else{
			$legend = NULL;
		}

		$html_label_input .= $input . $legend .$error_message;
		$html_label_input .= '</div>';
		$html_label_input .= '</div>';
		
		return $html_label_input;
	}
	
	//Crea el html del input
	public function buildInput() {
		$element 	= $this->getElement();//Se obtiene el Zend_Form_Element_Text
		$helper 	= $element->helper;//Se obtiene el helper que crea el input "formText", "formSelect" ..etc

		switch ($helper){
			case 'formText':
				$html_element = $element->getView()->$helper( $element->getName (), $element->getValue (), $element->field_attribs );
				break;
			case 'formTextarea':
				$element->field_attribs = array ('rows' => '10' );
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
	
	//Crea el html de los mensajes de error
	public function buildErrors() {
		$element = $this->getElement();
		$messages = $element->getMessages();
		
		if (empty ( $messages )) {
			return '';
		}
		//Se settea los elementos html de inicio y fin para el html de errores
		$form_errors_helper = $element->getView()->getHelper('formErrors');
		$form_errors_helper	->setElementStart('<div class="alert alert-error">') 
               				->setElementSeparator('<br />') 
               				->setElementEnd('</div>'); 

		return $element->getView()->formErrors( $messages );
	}
	
	public function buildSubmit(){
		$element 	= $this->getElement();
		$messages 	= $element->getMessages();
		
		if (empty($messages)){
			return '';
		}

		return $element->getView()->formSubmit($messages);
	}
	
	public function render($content) {
		$element = $this->getElement ();
		if (! $element instanceof Zend_Form_Element) {
			return $content;
		}
		if (null === $element->getView()) {
			return $content;
		}
		
		$separator = $this->getSeparator();
		$placement = $this->getPlacement();
		$html_input= $this->buildLabelInput();

		$output = '<div class="form element">' . $html_input . '</div>';
		
		switch ($placement) {
			case (self::PREPEND) :
				return $output . $separator . $content;
			case (self::APPEND) :
			default :
				return $content . $separator . $output;
		}
	}
}