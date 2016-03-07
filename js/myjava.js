$(document).ready(pagination(1));
$(function(){
	$('#bt-buscar').on('click', function(){
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		var bodega = $('#cb-bodega').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/busca_producto_fecha.php';				
		$.ajax({
		type:'GET',
		url:url,
		data:'desde='+desde+'&hasta='+hasta+'&bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});

	$('#bt-ventadiaria').on('click', function(){
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		var bodega = $('#cb-bodega').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/busca_ventadiaria.php';				
		$.ajax({
		type:'GET',
		url:url,
		data:'desde='+desde+'&hasta='+hasta+'&bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});
        
        $('#bt-reposicion').on('click', function(){
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		var bodega = $('#cb-bodega').val();                
                var tipo = $('#cb-tipo').val(); 
                var stock = $('#txt-stock').val();                 
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/Busca_reposicion.php';				
		$.ajax({
		type:'GET',
		url:url,
		data:'desde='+desde+'&hasta='+hasta+'&bodega='+bodega+'&tipo='+tipo+'&stock='+stock,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});

	$('#bt-ticket').on('click',function(){
		var ticket = $('#txt-ticket').val();	
		var estado = $('#cb-estado').val();
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/busca_ticket.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'ticket='+ticket+'&desde='+desde+'&hasta='+hasta+'&estado='+estado,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});	
	

	$('#bt-busca-facturas').on('click',function(){
		var factura = $('#txt-fac').val();	
		var bodega = $('#cb-bodega').val();
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/busca_facturas.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'factura='+factura+'&desde='+desde+'&hasta='+hasta+'&bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});	

	$('#bt-busti').on('click',function(){		
		var bodega = $('#cb-bodega').val();
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/busca_tincompletas.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'desde='+desde+'&hasta='+hasta+'&bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});	



	$('#nuevo-producto').on('click',function(){
		$('#formulario')[0].reset();
		$('#pro').val('Registro');
		$('#edi').hide();
		$('#reg').show();
		$('#registra-producto').modal({
			show:true,
			backdrop:'static'
		});
	});

	$('#nuevo-correo').on('click',function(){
		$('#formulario')[0].reset();
		$('#pro').val('Registro');
		$('#edi').hide();
		$('#reg').show();
		$('#registra-correo').modal({
			show:true,
			backdrop:'static'
		});
	});




	$('#bt-buscaranulados').on('click', function(){
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		var bodega = $('#cb-bodega').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/productosanulados.php';				
		$.ajax({
		type:'GET',
		url:url,
		data:'desde='+desde+'&hasta='+hasta+'&bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});


	////////////////////////////////////////////////////////////

	$('#bt-rentabilidad').on('click', function(){
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		var bodega = $('#cb-bodega').val();
		var tipo = $('#cb-tipo').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/rentabilidadxtransacion.php';				
		$.ajax({
		type:'GET',
		url:url,
		data:'desde='+desde+'&hasta='+hasta+'&bodega='+bodega+'&tipo='+tipo,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});

		
	$('#bt-buscar2').on('click',function(){
		var dato = $('#bs-prod').val();	
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		var bodega = $('#cb-bodega').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/busca_producto2.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'dato='+dato+'&desde='+desde+'&hasta='+hasta+'&bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});	


////////////////////////////////////////////////////////////////////////////////////////

$('#bt-reporteFAC_ANULADAS').on('click',function(){		
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		var bodega = $('#cb-bodega').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/facturasanuladas.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'desde='+desde+'&hasta='+hasta+'&bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});	

///////////////////////////////////////////////////////////////////////////////////////////////
	$('#bt-buscar-smx').on('click',function(){		
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();	
		var bodega = $('#cb-bodega').val();	
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/loading.gif" width="100" /><div></h2>');
		var url = '../php/busca_facturas_smx.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'desde='+desde+'&hasta='+hasta+'&bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});
        
        $('#bt-buscarproductossn').on('click',function(){				
		var bodega = $('#cb-bodega').val();			
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/actualizacioncc.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});
        
        
        $('#bt-buscarproductossn').on('click',function(){				
		var bodega = $('#cb-bodega').val();			
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/actualizacioncc.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});


	$('#bt-correos').on('click',function(){				
		var programa = $('#cb-programa').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/busca_correos.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'programa='+programa,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});

	$('#bt-buscarproductonovedad').on('click',function(){				
		var bodega = $('#cb-bodega').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/actualizacionnovedades.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});


	$('#bt-creditosemp').on('click',function(){				
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();			
		var categoria = $('#cb-categoria').val();
		var tipo = $('#cb-tipo').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/cargando2.gif" width="100" /><div></h2>');
		var url = '../php/creditosempresariales.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'desde='+desde+'&hasta='+hasta+'&categoria='+categoria+'&tipo='+tipo,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});


	$('#bt-enviofanul').on('click',function(){		
		var desde = $('#bd-desde').val();
		var hasta = $('#bd-hasta').val();
		var idpro = $('#idpro').val();
		var bodega = $('#cb-bodega').val();
		$('#agrega-registros').html('<h2><div align="center"><img src="../recursos/email.gif" width="100" /><div></h2>');
		var url = '../php/envio_facturasanuladas.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'desde='+desde+'&hasta='+hasta+'&idpro='+idpro+'&bodega='+bodega,
		success: function(datos){
			$('#agrega-registros').html(datos);
		}
	});
	return false;
	});


	$(".text").linker({
   target: '', //blank,self,parent,top
   className : '',
   rel : ''
}); 

	

});


function agregaRegistro(){
	var url = '../php/agrega_producto.php';
	$.ajax({
		type:'GET',
		url:url,
		data:$('#formulario').serialize(),
		success: function(registro){
			if ($('#pro').val() == 'Registro'){
			$('#formulario')[0].reset();
			$('#mensaje').addClass('bien').html('Registro completado con exito').show(200).delay(2500).hide(200);
			$('#agrega-registros').html(registro);
			return false;
			}else{
			$('#mensaje').addClass('bien').html('Edicion completada con exito').show(200).delay(2500).hide(200);
			$('#agrega-registros').html(registro);
			return false;
			}
		}
	});
	return false;
}

function eliminarProducto(id,es){
	var url = '../php/elimina_producto.php';
	var pregunta = confirm('¿Esta seguro de eliminar este Ticket?');
	if(pregunta==true){
		$.ajax({
		type:'GET',
		url:url,
		data:'id='+id+'&es='+es,
		success: function(registro){
			$('#agrega-registros').html(registro);
			return false;
		}
	});
	return false;
	}else{
		return false;
	}
}

function editarProducto(id){
	$('#formulario')[0].reset();
	var url = '../php/edita_producto.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'id='+id,
		success: function(valores){
				var datos = eval(valores);
				$('#reg').hide();
				$('#edi').show();
				$('#pro').val('Edicion');
				$('#id').val(id);
				$('#detalle').val(datos[0]);				
				$('#area').val(datos[1]);
				$('#usuario').val(datos[2]);
				$('#estado').val(datos[3]);
				$('#tecnico').val(datos[4]);
				$('#grupo').val(datos[5]);
				$('#registra-producto').modal({
					show:true,
					backdrop:'static'
				});
			return false;
		}
	});
	return false;
}

function reporteEXCEL(){
	var desde = $('#bd-desde').val();
	var hasta = $('#bd-hasta').val();
	var dato = $('#bs-prod').val();	
	var bodega = $('#cb-bodega').val();
	window.location.href = '../php/excelventas.php?desde='+desde+'&hasta='+hasta+'&dato='+dato+'&bodega='+bodega;	
}

function reporteSINACT(){	
	var bodega = $('#cb-bodega').val();
	window.location.href = '../php/excelpsinuentas.php?bodega='+bodega;	
}

function reportePNOVEDAD(){	
	var bodega = $('#cb-bodega').val();
	window.location.href = '../php/excelnovedades.php?bodega='+bodega;	
}

function reporteCREDITOSEMP(){
	var desde = $('#bd-desde').val();
	var hasta = $('#bd-hasta').val();
	var tipo = $('#cb-tipo').val();
	var categoria = $('#cb-categoria').val();
	window.location.href = '../php/excelcreditosempresariales.php?categoria='+categoria+'&tipo='+tipo+'&desde='+desde+'&hasta='+hasta;	
}


function reporteFACTURAPDF(){
		var desde = $('#bd-desde').val();
	var hasta = $('#bd-hasta').val();
	window.location.href('../php/excelcreditosempresariales.php?desde='+desde+'&hasta='+hasta);
}

function reporteFACANU(){
	var desde = $('#bd-desde').val();
	var hasta = $('#bd-hasta').val();
	var bodega = $('#cb-bodega').val();
	window.location.href = '../php/excelfacturasanuladas.php?desde='+desde+'&hasta='+hasta+'&bodega='+bodega;	
}


function pagination(partida){
	var url = '../php/paginarTickets.php';
	$.ajax({
		type:'POST',
		url:url,
		data:'partida='+partida,
		success:function(data){
			var array = eval(data);
			$('#agrega-registros').html(array[0]);
			$('#pagination').html(array[1]);
		}
	});
	return false;
}

function editarcorreos(id){
	$('#formulario')[0].reset();
	var url = '../php/editar_correo.php';
		$.ajax({
		type:'GET',
		url:url,
		data:'id='+id,
		success: function(valores){
				var datos = eval(valores);
				$('#reg').hide();
				$('#edi').show();
				$('#pro').val('Edicion');
				$('#id').val(id);
				$('#nombre').val(datos[0]);				
				$('#correo').val(datos[1]);
				$('#programa').val(datos[2]);
				$('#registra-correo').modal({
					show:true,
					backdrop:'static'
				});
			return false;
		}
	});
	return false;
}

function agregacorreos(){
	var url = '../php/agrega_correos.php';
	$.ajax({
		type:'GET',
		url:url,
		data:$('#formulario').serialize(),
		success: function(registro){
			if ($('#pro').val() == 'Registro'){
			$('#formulario')[0].reset();
			$('#mensaje').addClass('bien').html('Registro completado con exito').show(200).delay(2500).hide(200);
			$('#agrega-registros').html(registro);
			return false;
			}else{
			$('#mensaje').addClass('bien').html('Edicion completada con exito').show(200).delay(2500).hide(200);
			$('#agrega-registros').html(registro);
			return false;
			}
		}
	});
	return false;
}

function eliminacorreos(id,idpro){
	var url = '../php/elimina_correo.php';
	var pregunta = confirm('¿Esta seguro de eliminar este Correo?');
	if(pregunta==true){
		$.ajax({
		type:'GET',
		url:url,
		data:'id='+id+'&idpro='+idpro,
		success: function(registro){
			$('#agrega-registros').html(registro);
			return false;
		}
	});
	return false;
	}else{
		return false;
	}
}