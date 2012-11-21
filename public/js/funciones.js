/**
 * Funciones generales del sistema
 */

if(typeof(weezer) == 'undefined'){
	weezer = {};
}

weezer.combos = {}

var select_lista_precios = [];


/**
 * Método para selects dependientes
 * id  | es el id para los filtros del select
 * url | cadena concatenada por espacios que contiene Modulo controlador accion
 * select_destino | el select dependiente a modificar
 */
weezer.combos.select = function(id,url,select_destino){
	
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


weezer.deleteaction = function(url){
	labelAccion = 'Eliminar';
	var delete_dialog = "&iquest;Esta seguro de "+labelAccion+" este registro?";
	
	bootbox.dialog(delete_dialog, [{
        "label" : "Salir",
    }, {
        "label" : labelAccion,
        "class" : "btn-danger",
        "callback": function() {
        	$(location).attr('href',url);
        }
    }]);
}


weezer.popupimage = function(path_image){
         bootbox.modal('<img class="size_img_gallery" src="'+path_image+'" alt=""/>');
}

