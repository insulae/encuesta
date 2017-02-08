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
	height: 500px;
	background: white;
}
.titulo{
	width: 900px;
	font-weight: bold;
}
.estilo{
	background: lime;
}
</style>



</head>
<body>
<?php 
	echo "<script>
		var id_encuesta_generada =".$_REQUEST['id'].";
		var carrera ='".$_REQUEST['carrera']."';
		var materia ='".$_REQUEST['materia']."';
	</script>";
?>
	<div id="cuerpo"></div>
</body>
</html>

<script>

$("#cuerpo").append('<div class="alert alert-warning titulo" role="alert" id=titulo>'+carrera+" / "+materia+'</div>');
$("#cuerpo").append('<div class="divtabla1"><table id="tabla"></table></div><br><br>');

$('#tabla').bootstrapTable({
    columns: [
    	{
        	field: 'id_obj',
        	title: 'ID',
        	visible:false
        }, 
    	{
        	field: 'descrip',
        	title: 'Preguntas'
    	}, 
    	{
        	field: 'val0',
        	title: '0',
        	cellStyle: estilo
        },
    	{
        	field: 'val1',
        	title: '1',
        	cellStyle: estilo
        },
    	{
        	field: 'val2',
        	title: '2',
        	cellStyle: estilo
        },
    	{
        	field: 'val3',
        	title: '3',
        	cellStyle: estilo
        },
    	{
        	field: 'val4',
        	title: '4',
        	cellStyle: estilo
        },
    	{
        	field: 'val5',
        	title: '5',
        	cellStyle: estilo
        },
    	{
        	field: 'val6',
        	title: '6',
        	cellStyle: estilo
        },
    	{
        	field: 'val7',
        	title: '7',
        	cellStyle: estilo
        },
    	{
        	field: 'val8',
        	title: '8',
        	cellStyle: estilo
        },
    	{
        	field: 'val9',
        	title: '9',
        	cellStyle: estilo
        },
    	{
        	field: 'val10',
        	title: '10',
        	cellStyle: estilo
        }
                   
    ]
});
traerDatos();

function traerDatos(){
	$.ajax({		
		url:   'funciones.php?accion=encuesta_alumnos',
		type:  'post',
		data:{
			id_encuesta_generada: id_encuesta_generada,
		},
		success: function (result) {
			result = JSON.parse(result);
			//console.log(JSON.stringify(preg));
			$('#tabla').bootstrapTable('load', result.resps);
		}
	});

}
function estilo(value, row, index, field) {
	valores = [];
	valores.push(row.val0.split(/\W+/)[1]);
	valores.push(row.val1.split(/\W+/)[1]);
	valores.push(row.val2.split(/\W+/)[1]);
	valores.push(row.val3.split(/\W+/)[1]);
	valores.push(row.val4.split(/\W+/)[1]);
	valores.push(row.val5.split(/\W+/)[1]);
	valores.push(row.val6.split(/\W+/)[1]);
	valores.push(row.val7.split(/\W+/)[1]);
	valores.push(row.val8.split(/\W+/)[1]);
	valores.push(row.val9.split(/\W+/)[1]);
	valores.push(row.val10.split(/\W+/)[1]);	
	max = Math.max.apply(Math,valores);
	if(valores[0] == max && field == "val0"){
  		return {
    		classes: 'estilo'
    		//css: {"color": "blue", "font-size": "50px"}
  		};
	}else if(valores[1] == max && field == "val1"){
  		return {
    		classes: 'estilo'
  		};
	}else if(valores[2] == max && field == "val2"){
  		return {
    		classes: 'estilo'
  		};
	}else if(valores[3] == max && field == "val3"){
  		return {
    		classes: 'estilo'
  		};
	}else if(valores[4] == max && field == "val4"){
  		return {
    		classes: 'estilo'
  		};
	}else if(valores[5] == max && field == "val5"){
  		return {
    		classes: 'estilo'
  		};
  	}else if(valores[6] == max && field == "val6"){
  		return {
  	   		classes: 'estilo'
  		};
  	}else if(valores[7] == max && field == "val7"){
  	  	return {
  	    	classes: 'estilo'
  	  	};
  	}else if(valores[8] == max && field == "val8"){
  		return {
  	   		classes: 'estilo'
  		};
  	}else if(valores[9] == max && field == "val9"){
  		return {
  	   		classes: 'estilo'
  		};
  	}else if(valores[10] == max && field == "val10"){
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