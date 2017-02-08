<html lang=es>
<head>
	<link rel="stylesheet" href="../engine/bootstrap-table.min.css">
	<link rel="stylesheet" href="../engine/bootstrap.min.css">

	<script src="../engine/jquery.min.js"></script>
	<script src="../engine/bootstrap-table.min.js"></script>
	<script src="../engine/bootstrap-table-es-AR.min.js"></script>
	<script src="../engine/bootstrap.min.js"></script>
	
	<meta charset="utf8">
<style>
body{
	margin: 20px;
	background: #efefef;
}
.divtabla1{
	width: 900px;
	height: 300px;
	background: white;
}
.titulo{
	width: 900px;
	font-weight: bold;
}
.estilo{
	background: lime;
}
.divcomentarios{
	width: 900px;
}
.divcomentarios hr{
    height: 12px;
    border: 0;
    box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);
}
</style>



</head>
<body>
<?php 
	echo "<script>
		var id_encuesta_generada ="32";
		var carrera ='pepe';
		var materia ='popo';
	</script>";
?>
	<div id="cuerpo"></div>
	<div class="divcomentarios">
		<b>Comentarios:</b>
		<div id="divcomentarios"></div>
	</div>
</body>
</html>

<script>

$("#cuerpo").append('<div class="alert alert-warning titulo" role="alert" id=titulo>'+carrera+" / "+materia+'</div>');
for(i=1; i <13; i++){

	$("#cuerpo").append('<div class="alert alert-info titulo" role="alert" id=respuesta'+i+'></div>');
	$("#cuerpo").append('<div class="divtabla1"><table id="tabla'+i+'"></table></div><br><br>');

	$('#tabla'+i).bootstrapTable({
	    columns: [
	    	{
	        	field: 'id_obj',
	        	title: 'ID',
	        	visible:false
	        }, 
	    	{
	        	field: 'obj_descrip',
	        	title: 'Profesor',
	        	sortable: true
	    	}, 
	    	{
	        	field: 'no_resp',
	        	title: 'No Resp.',
	        	cellStyle: estilo
	        },
	    	{
	        	field: 'malo',
	        	title: 'Malo',
	        	cellStyle: estilo
	        },
	    	{
	        	field: 'regular',
	        	title: 'Regular',
	        	cellStyle: estilo
	        },
	    	{
	        	field: 'bueno',
	        	title: 'Bueno',
	        	cellStyle: estilo
	        },   
	    	{
	        	field: 'muy_bueno',
	        	title: 'Muy Bueno',
	        	cellStyle: estilo
	        },   
	    	{
	        	field: 'excelente',
	        	title: 'Excelente',
	        	cellStyle: estilo
	        },                   
	    ],
	    sortName: "Carrera",
		sortOrder: "asc"
	});
	traerDatos(i);
}

function traerDatos(preg_nro){
	$.ajax({		
		url:   'funciones.php?accion=encuesta_profes',
		type:  'post',
		data:{
			id_encuesta_generada: id_encuesta_generada,
			preg_nro: preg_nro
		},
		success: function (resp) {
			resp = JSON.parse(resp);
			var obj = resp.obj_estudios;
			$('#tabla'+preg_nro).bootstrapTable('load', obj);
			$('#respuesta'+preg_nro).html(preg_nro +" "+ resp.materia);
			if(preg_nro == 12){
				$('#divcomentarios').html(resp.comentarios);
			}
		}
	});

}
function estilo(value, row, index, field) {
	valores = [];
	valores.push(row.no_resp.split(/\W+/)[1]);
	valores.push(row.malo.split(/\W+/)[1]);
	valores.push(row.regular.split(/\W+/)[1]);
	valores.push(row.bueno.split(/\W+/)[1]);
	valores.push(row.muy_bueno.split(/\W+/)[1]);
	valores.push(row.excelente.split(/\W+/)[1]);
	max = Math.max.apply(Math,valores);
	
	if(valores[0] == max && field == "no_resp"){
  		return {
    		classes: 'estilo'
    		//css: {"color": "blue", "font-size": "50px"}
  		};
	}else if(valores[1] == max && field == "malo"){
  		return {
    		classes: 'estilo'
  		};
	}else if(valores[2] == max && field == "regular"){
  		return {
    		classes: 'estilo'
  		};
	}else if(valores[3] == max && field == "bueno"){
  		return {
    		classes: 'estilo'
  		};
	}else if(valores[4] == max && field == "muy_bueno"){
  		return {
    		classes: 'estilo'
  		};
	}else if(valores[5] == max && field == "excelente"){
  		return {
    		classes: 'estilo'
  		};  		  		  		
	}else{
		return{
			classes: ''
		}
	}
}
</script>
