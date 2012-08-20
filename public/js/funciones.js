/**
 * Funciones generales del sistema
 */

if(typeof(admin) == 'undefined'){
	admin = {};
}

admin.combos = {}

var select_lista_precios = [];


/**
 * Método para selects dependientes
 * id  | es el id para los filtros del select
 * url | cadena concatenada por espacios que contiene Modulo controlador accion
 * select_destino | el select dependiente a modificar
 */
admin.combos.select = function(id,url,select_destino){
	
	var url_mod_con_accion = url.split(' ');
	
	 $.ajax(
		        {
		            async: true,
		            type: 'GET',
		            url: admin.base_url + '/' + url_mod_con_accion[0] + '/' + url_mod_con_accion[1] + '/' + url_mod_con_accion[2],
		            data: "id="+id,
		            dataType: 'json',
		           
		            beforeSend: function(data){
		            	 $("#"+select_destino).html("<select class='span2'><option>Cargando...</option></select>");
		            },
		            success: function(data){
		            	if (data == null){
		            		$("#"+select_destino).html("<select class='span2' name='" + select_destino + "' id='" + select_destino + "'>" +
	    				"						<option value=''>Sin opciones</option></select>");
		            	}else{
		            		$("#"+select_destino).html(data);
		            	}
		            },
		            error: function(requestData, strError, strTipoError){
		               
		            }
		        });
}

admin.generaInputPrecios = function(id,url,append_label){
	
	var url_mod_con_accion = url.split('_');
    var label_lista_precio = $('#lista_precios option:selected').html();//Se obtiene el html de la opcion seleccionada
    var input_prefix = 'lista_precios_';
    
	 $.ajax(
		        {
		            async: true,
		            type: 'GET',
		            url: admin.base_url + '/' + url_mod_con_accion[0] + '/' + url_mod_con_accion[1] + '/' + url_mod_con_accion[2],
		            data: "id="+id,
		            dataType: 'json',
		           
		            beforeSend: function(data){
		            	
		            },
		            success: function(data){
		            	//Si ya existe el elemento en el arreglo es que ya se creo un input text
		            	if(select_lista_precios.indexOf(id) > -1 || id == 0
		            	   || document.getElementById( input_prefix + id) != null){
		            		//Ya existe el input y se le coloca el focus
		            		$("#"+input_prefix + id).focus();
		            	}else{
		            		select_lista_precios.push(id);
		            		
		            		jQuery("<dl><div class='form element'><div class='control-group'><label class='control-label' for='lista_precios'>" +
			            			label_lista_precio + ":</label><div class='controls'>" +
			            			"<input type='text' class='span1' value='' id='lista_precios_" + id + "' name='lista_precios_" + id + "'>" +
			            			"</div></div></div></dl>").appendTo("#fieldset-precios");
		            		//Se le da el focus al elemento creado
		            		$("#lista_precios_"+ id).focus();
			            	  
		            	}
		            	//Reinicio el select
		            	$('#lista_precios').val($('option:first', $('#lista_precios')));
		            },
		            error: function(requestData, strError, strTipoError){
		               
		            }
		        });
	 
}	 

admin.validaUnidadPrincipal = function(){
	
	 var total 	= document.forms.frmProductos.elements.length;
     var type	='';
     var checkboxes = new Array();
     var regex_unidades = 'unidades_';
     
     for (i=0;i<total;i++) {
		type   =document.forms.frmProductos.elements[i].type
		
		if(type = 'checkbox'){
			var id = document.forms.frmProductos.elements[i].id;
			if(id.match(regex_unidades)){
				checkboxes.push(document.forms.frmProductos.elements[i].id);
			}
		}
	}
	 
    var check = this.checkUnidades(checkboxes); 
    if(check){
    	document.forms.frmProductos.submit();
    }else{
    	alert('Debes seleccionar una unidad que hayas marcado previamente');
    }
}


admin.checkUnidades = function(unidades_checkboxes){
	
	total_unidades = unidades_checkboxes.length;
	var select_unidad_principal = 'unidad_principal';
	var unidad_seleccionada 	=  $('#' +select_unidad_principal+ ' :selected').val()
	var return_valor = '';

	for(i=0;i<total_unidades;i++){
		
		if($('#' + unidades_checkboxes[i]).is(":checked")) {
			
			var unidad_check_array = unidades_checkboxes[i].split('_');
			var id_unidad = unidad_check_array[1];
				
			if(unidad_seleccionada == id_unidad ){
				return_valor = true;
				break;
				//FIXME
			}else{
				return_valor = false;
			}
		}else{
			  if(unidad_seleccionada.length == 0){
					return_valor = true;
			  }
		}
	}

	return return_valor;
}

admin.popupimage = function(path_image){
         bootbox.modal('<img class="size_img_gallery" src="'+path_image+'" alt=""/>');
}

function toggle(){
	guardarOpcion();
	$('a.toggles i').toggleClass('icon-chevron-left icon-chevron-right');
    $('#sidebar').animate({
        width: 'toggle'
    }, 0);
    $('#content').toggleClass('span12 span10');
    $('#content').toggleClass('no-sidebar');				
}

function guardarOpcion(){
    $.ajax({
    	async: true,
		type: 'GET',
		url: admin.base_url + '/Default/index/ocultarmenu',
		data: "ocultar=" + ($('#sidebar').is(":visible") ? 1 : 0),
		dataType: 'json',

		beforeSend: function(data){},
		success: function(data){},
        error: function(requestData, strError, strTipoError){}
	});
}

function print(obj)
{
	mensaje = '';
	for (var x in obj)
	{
		mensaje += x+' - '+obj[x]+"\n";
	}
	alert(mensaje);
}

//version anterior
/*function appendParams(params)
{
	var campos = params.split(',');
	var parametros = "";
	
	for(i=0; i<campos.length; i++)
	{
		parametros += campos[i]+"="+$("#"+campos[i]).val()+"&";
	}	
	
	return parametros;
}*/

function appendParams(params)
{
	var campos = params.split(',');
	var parametros = "";
	
	for(i=0; i<campos.length; i++)
	{
		valor = '';
		elemento = $("#"+campos[i]);
		if(elemento.attr('type') == 'checkbox' || elemento.attr('type') == 'radio')  //(elemento.is(':checkbox'))
		{
			if(elemento.is(':checked'))
				valor = elemento.val();
		}
		else
		{
			valor = elemento.val();
		}
		parametros += campos[i]+"="+valor+"&";
	}	
	
	return parametros;
}

function cleanForm(form)
{
	form.find('input:text, input:password, input:file, select, textarea').val('');
    form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
}

function setFieldErrors(errores)
{
	for (var x in errores)
	{
		$("#DIVERROR"+x).html('<div class="alert alert-error">'+errores[x].join("<br>")+'</div>');
	}
}

function setElementForm(field, element)
{	
	divError = '<div id="DIVERROR'+field+'"></div>';
	
	$("#DIV"+field).html(element + divError);	
}

function getFieldsForm(form)
{
	var elementos = document.getElementById(form).elements;
	var parametros = new Array();
	
	for(var i = 0; i < elementos.length; i++)
	{
		if(elementos[i].id != '')
			parametros.push(elementos[i].id);
	}
	return parametros.join(',');
}

function cleanFieldErrors(form)
{
	var campos = getFieldsForm(form).split(',');

	for(i=0; i<=campos.length; i++)
	{
		//limpiar mensaje de error
		$("#DIVERROR"+campos[i]).html('');
	}
}

function setErrorsTop(errores)
{
	if(errores.length)
	{
		var htmlError = "<div class='alert alert-error'><ul class='unstyled'>";
		for (var x in errores)
		{
			htmlError += "<li>"+errores[x]+"</li>";
		}
		htmlError += "</ul></div>";
		$("#DIVerrores").html(htmlError);
	}		
}

function putSomething(div, url, parametros)
{
	$.ajax(
	        {
	            async: true,
	            type: 'GET',
	            url: url,
	            data: parametros,
	            dataType: 'json',
	           
	            beforeSend: function(data)
	            {
	            	$("#"+div).html('');
	            },
	            success: function(data)
	            {	            	
	            	$("#"+div).html(data);
	            },
	            error: function(requestData, strError, strTipoError){
	             
	            }
	        });
}


function onlyNumbers(event, element)
{
	element.value = element.value.replace(/[^0-9\.]/g,'');

	char = !event.charCode ? event.which : event.charCode;
	if(char >= 32)
	{
    	var regex = new RegExp("^[0-9\.]+$");
														
    	var key = String.fromCharCode(char);
    	if (!regex.test(key)) {
    	   event.preventDefault();
    	   return false;
    	}
	}

}

function confirmAccion(url)
{
	labelAccion = 'Eliminar';
	bootbox.dialog("¿Está seguro de "+labelAccion+" el logo para las facturas?", [{
	            "label" : "Salir",
	        	},
	        	{
	            "label" : labelAccion,
	            "class" : "btn-danger",
	            "callback": function() {
	            	window.location = url;
	             }
	        	}]);

}