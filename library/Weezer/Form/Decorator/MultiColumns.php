<?php
/**
 * 
 * Decorador para formularios de multiples columnas
 * @author scumbagblues
 *
 */
class Weezer_Form_Decorator_MultiColumns extends Weezer_Form_Decorator_CompositeAbstract
	implements Weezer_Form_Decorator_Interface
{
	
	public function buildLabelInput(){
		$label = '';
		$element = $this->getElement ();
		$html_label_input = "<div class='span3'>";
		
		//Si el elemento es obligatorio antepone un asterisco
		if ($element->isRequired ()) {
			$label .= '*';
		}
		
		//Si se tiene traducción se aplica
		if ($translator = $element->getTranslator ()) {
			$label .= $translator->translate ( $element->getLabel ());
		}

		$label .= ':';
		//en caso de que no se mande una etiqueta esta quedara vacia
		if (is_null($element->getLabel())){
			$label = '';
		}
		//$label .= "</label>";
		//Se obtiene toda la etiqueta label
		$html_label = $element->getView ()->formLabel ( $element->getName (), ($label));
		//Se agrega la etiqueta label que se acaba de generar
		$html_label_input .= $html_label;
		//$html_label_input .= "<div class='controls'>";
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
		//$html_label_input .= '</div>';
		$html_label_input .= '</div>';
		
		return $html_label_input;
	}
	public function buildErrors(){
		$element = $this->getElement();
		$messages = $element->getMessages();
		
		if (empty ( $messages )) {
			return '';
		}
		//Se settea los elementos html de inicio y fin para el html de errores
		$form_errors_helper = $element->getView()->getHelper('formErrors');
		$form_errors_helper	->setElementStart('<div class="alert alert-error alert-font-size">') 
               				->setElementSeparator('<br />') 
               				->setElementEnd('</div>'); 

		return $element->getView()->formErrors( $messages );
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

		$output = $html_input;
		
		switch ($placement) {
			case (self::PREPEND) :
				return $output . $separator . $content;
			case (self::APPEND) :
			default :
				return $content . $separator . $output;
		}
	}
	
}