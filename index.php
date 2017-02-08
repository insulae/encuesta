<html lang="es">
<head>
	<!-- estilos -->
	<link rel="stylesheet" type="text/css" href="engine/bootstrap.min.css" media="screen">
	<link rel="stylesheet" type="text/css" href="engine/bootstrap-theme.min.css" media="screen">
	<link rel="stylesheet" type="text/css" href="engine/encuesta.css" media="screen">
	<link rel="stylesheet" type="text/css" href="engine/jquery-confirm.css" media="screen">
	<link rel="stylesheet" type="text/css" href="engine/fonts/flaticon.css">
	
	<!-- scripts -->
	<script src="engine/jquery.min.js"></script>
	<script src="engine/jquery-confirm.js"></script>
	<script src="engine/bootstrap.min.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf8">
</head>

<body style="background-color: #d0d1d5">

<div id="_objetos" style="display: none">
	<?php include("pregSliderEx.html")?>
	<?php include("pregSelectObj.php")?>
	<?php include("pregText.html")?>
	<?php include("pregCombo.php")?>
	<?php include("pagMsj.html")?>
	<?php include("pregPtos.html")?>
	<?php include("pagEnviar.html")?>
	<?php include("pagFinal.html")?>
</div>
	
<script>
var pagTot = 2; //1 de inicio, 2 para que empiece desde 1
var pagCon = 1;
var pregCon = 1;
var id_encuesta = "";
var recolector = [];
var pregCreadas = 0;
var codigo = "";
</script>

<div id="encuesta" style="width: 600px; min-height: 600px; background: #d0d1d5">
<?php include("pagInicio.php")?>

<script>
//----------------------------------------------------CREAR ENCUESTA ------------------------------------------------------------
function crearPreguntas(jsonEnc){
		var objAUsar = jsonEnc.encuesta_items[0].obj_estudio;
			crearPregSelectObj("obj0","Por favor selecciona de la siguiente lista los profesores con los que hayas cursado para luego calificarlos respecto de las cuestiones que se te preguntarán",objAUsar);	
		$("#btns_1").click(function(){
			
			var objEstSel = getPregSelectObj(objAUsar);
			//alert(JSON.stringify(jsonEnc.encuesta_items[0].obj_estudio));
			var objListos = [];
			for (var cos =0; cos<objEstSel.length; cos++) {
				for (var cosj =0; cosj<objAUsar.length; cosj++) {
					if(objEstSel[cos].objEst == objAUsar[cosj].id_obj_estudio && objEstSel[cos].value == 1){
						objListos.push(objAUsar[cosj]);
					}
				}
			}
			jsonEnc.encuesta_items[0].obj_estudio = objListos;
			
			if(objListos.length > 0){
				
				if(pregCreadas != 1){
					crearPreguntas2(jsonEnc);
					$("#pag_1").css("display","none");
					$("#pag_2").css("display","block");
					$(window).scrollTop(0);	
					$("#pag_2 .preg-contenedor").children().slideUp(0).fadeIn(700);
				}
				else{
					$.confirm({
					    title: 'Precaución',
					    content: 'Uds ya comenzó la encuesta con otra selección, para cambiar los profesores deberá reiniciar la encuesta.',
					    buttons: {
					        confirm: {
					        	text:'Reiniciar Encuesta',
								btnClass: 'btn-danger',
								action: function(){
									$(location).attr('href','index.php?codigo='+codigo);
									return;
								}
					        },
					        cancel: {
								text:'continuar',
								btnClass: 'btn-primary'
							}     
					    }
					
					});		
				}
			}
			else{
				$.alert({
				    title: 'Error!',
				    content: 'Debe seleccionar al menos un profesor.',
				    buttons: {
				        confirm: {
				        	text:'Aceptar',
							btnClass: 'btn-danger'
				         }
				    }
						    
				});
			}
		});
		$("#btna_1").click(function(){
			$(location).attr('href','index.php?codigo='+codigo);
		});
}
function crearPreguntas2(jsonEnc){
	//saco sub encuestas
	var subEnc = jsonEnc.encuesta_items;
	//cuento cant preguntas
	for (var ce =0; ce<subEnc.length; ce++) {
		pagTot += (subEnc[ce].preguntas.length);
	}
	for (var ce =0; ce<subEnc.length; ce++) {
		
		//Creo pag de encuesta
    	if(ce >1){
    		crearPagMsj(subEnc[ce].titulo,subEnc[ce].descrip);
    	}
		//saco preguntas
		var preguntas = subEnc[ce].preguntas;
		for (var cp =0; cp<preguntas.length; cp++) {
			//veo su tipo
			var tipo = preguntas[cp].tipo_respuesta.tipo_objeto;
			switch(tipo) {
				//creo slider
			    case "sliderEx":
				    if(preguntas[cp].obj_estudio == "S"){
					    obj_estudio = subEnc[ce].obj_estudio;
				    }
				    else{
				    	obj_estudio = [{"descrip":" ","id":1}];
				    }
			    	crearPregSliderEx(preguntas[cp].id,preguntas[cp].descrip,obj_estudio);
			        break;
			    //creo text
			    case "text":
				    if(preguntas[cp].obj_estudio == "S"){
					    alert("ERROR: NO CONTEMPLADO, FIXME");
				    }
				    else{
			    		crearPregText(preguntas[cp].id,preguntas[cp].descrip);
				    }
			        break;
				//creo text
			    case "combo":
				    if(preguntas[cp].obj_estudio == "S"){
					    alert("ERROR: NO CONTEMPLADO, FIXME");
				    }
				    else{
			    		crearPregCombo(preguntas[cp].id,preguntas[cp].descrip,preguntas[cp].tipo_respuesta.json.opciones);
				    }
			        break;			        
				//creo puntos
			    case "puntos":
				    if(preguntas[cp].obj_estudio == "S"){
					    alert("ERROR: NO CONTEMPLADO, FIXME");
				    }
				    else{
						crearPregPtos(preguntas[cp].id,preguntas[cp].descrip);
				    }
			        break;						        				        		        
			}
			
		}
		pregCreadas = 1;
   		//activo tooltips para sliderEx y otros
   	  	$('[data-toggle="tooltip"]').tooltip();
	}
	//termino recorrido json, creo enviar
	crearPagEnviar(pagCon,"Encuesta Completada","Muchas gracias por su tiempo, solo queda enviar la encuesta");
	crearPagFinal(pagCon,"MUCHAS GRACIAS POR REALIZAR LA ENCUESTA","Recordá guardar tu <b>código de encuesta</b> para que una vez publicado los resultados puedas corroborar los tuyos");
	crearEventos();
}

/* $.alert({
    title: 'Confirm!',
    content: 'Simple confirm!',
    buttons: {
        confirm:  {
            btnClass: 'btn-warning',
         action: function(){
            $.alert('Confirmed!');
         }

    }
    }
}); */
     
</script>

<?php include("funciones.html")?>


</div>
</body>
</html>

<!--
agregar jquery confirm si no eligen profes y el error al final si no puede guardar

//generar relacion id_obj_estudio y obj_estudio_subencuesta SOLO UNA VEZ SINO GENERA DOBLE
insert into obj_estudio_subencuesta(
select null AS id_subencuesta, 
obj_estudio.id_obj_estudio, "1" AS id_subencuesta,
null as json
from obj_estudio
)
 
 insert into obj_estudio set descrip = trim("ZOTTOLA, MONICA LIA");
 UPDATE table_name SET `column_name` = UPPER( `column_name` )
 
 -->
