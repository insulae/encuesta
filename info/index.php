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
}
.divtabla1{
	width: 100%;
	height: 100%;
}
.ico-profes{
	margin-right:20px;
}
</style>



</head>
<body>
	<div class="alert alert-info" role="alert">Datos Globales</div>
	<div class="divtabla1"><table id="tabla1"></table></div>
</body>
</html>


<script>
window.eventosAcciones = {
	    'click .ico-profes': function (e, value, row, index) {
	        datosProfes(row);
	    },
	    'click .ico-alumnos': function (e, value, row, index) {
	        datosAlumnos(row);
	    },	    
	};

$('#tabla1').bootstrapTable({
    columns: [
		{
        	field: 'id_encuesta_generada',
            title: 'ID Encuesta',
            visible:false
		},	              
    	{
        	field: 'carrera',
        	title: 'Carrera',
        	sortable: true
    	}, 
    	{
        	field: 'descrip',
        	title: 'Materia',
            sortable: true,
            width: 500
        },
    	{
        	field: 'cantidad',
        	title: 'Cantidad QR',
        	width: "1%",
            sortable: true
        },   
    	{
        	field: 'abiertos',
        	title: 'Abiertos',
        	width: "1%",
            sortable: true
        },   
    	{
        	field: 'respondidos',
        	title: 'Respondidos',
        	width: "1%",
            sortable: true
        },   
    	{
        	title: 'Acciones',
        	width: "1%",
            events: eventosAcciones,
            formatter: formatoAcciones
        },           
    ],
    pagination: true,
    pageSize: 50,
    search: true,
    sortName: "Carrera",
	sortOrder: "asc"
});

var $table = $('#tabla1');

function formatoAcciones(value, row, index) {
    return [
        '<a class="ico-profes" href="javascript:void(0)" title="ver datos Profesores">',
        '<i class="glyphicon glyphicon-user"></i>',
        '</a>  ',
        '<a class="ico-alumnos" href="javascript:void(0)" title="ver datos Alumnos">',
        '<i class="glyphicon glyphicon-education"></i>',
        '</a>'
    ].join('');
}

function traerDatos(){
	$.ajax({		
		url:   'funciones.php?accion=encuesta_materias'
		,type:  'post'
		,success: function (resp) {
			$('#tabla1').bootstrapTable('load', JSON.parse(resp));
		}
	});

}
traerDatos();


function datosProfes(row){
	$(location).attr('href','encuesta_profes.php?id='+row.id_encuesta_generada+'&carrera='+row.carrera+'&materia='+row.descrip);
}
function datosAlumnos(row){
	$(location).attr('href','encuesta_alumnos.php?id='+row.id_encuesta_generada+'&carrera='+row.carrera+'&materia='+row.descrip);
}

//$("#mostrar").click(function () {
//	console.log($('#tabla1').bootstrapTable('getSelections'));   
//});


//$('#tabla1').on('click-row.bs.table', function (row, $element, field) {
//	console.log($element.id_arbol);
//});


/* $(function () {
 $('#tabla1').bootstrapTable({
 }).on('click-row.bs.table', function (e, row) {
 	alert(row.id_arbol);
 });
}); */
</script>