<!---------------------------------------------- HTML ----------------------------------------------->
<div id="_pregSelectObj">
	<div id="@pag_nro" class="container-fluid contenedor pregSelectObj">
	<div class="alert alert-danger preg-contenedor">
		<span class="preg-nro">PRIMERA PARTE: DOCENTES</span>
	</div>
		<div class="alert alert-info preg-contenedor">
			<span class="glyph-icon flaticon-persona"></span>
			<span class="preg-nro"></span>
	  		<span class="preg">@preg_desc</span>
		</div>
	
		<div id="@objEsts" class="objEsts"></div>
		
		<div class="botonera">
			<button id="@btna_nro" type="button" class="btn btn-warning btn-atras"><span class="glyph-icon flaticon-atras"></span></button>
			<span class="faltantes">@pgc/@pgt</span>
			<button id="@btns_nro" type="button" class="btn btn-primary btn-sig"><span class="glyph-icon flaticon-siguiente"></span></button>
		</div>
	</div> 
</div>

<div id="_objEstSel">
	<div id="objEstSel" class="objEst">
		<div><span class="glyph-icon flaticon-persona icono"></span><span class="objest-descripcion">@objDesc</span></div>
		<div class="regla">
			<div class="radios">
				<span style="margin-right: 100px"></span>
				<label class="label-selObj">NO</label><input type="radio" name="@objEstSelSI" value="0" data-toggle="tooltip" title="NO" checked>
				<label class="label-selObj">SI</label><input type="radio" name="@objEstSelNO" value="1" data-toggle="tooltip" title="SI">
			</div>
		</div>
		<hr>	
	</div>
</div>


<!---------------------------------------------- JAVASCRIPT ----------------------------------------------->
<script>
function  crearPregSelectObj(id,pregDesc,objEst){

	var html = $("#_pregSelectObj").html().replace("@pag_nro","pag_"+pagCon);
	html = html.replace("@btns_nro","btns_"+pagCon);
	html = html.replace("@btna_nro","btna_"+pagCon);
	html = html.replace("@preg_titulo",pregCon);
	html = html.replace("@preg_desc",pregDesc);
	html = html.replace("@objEsts","objEsts_"+pagCon);
	html = html.replace("@pgc",pagCon);
	html = html.replace("@pgt",pagTot);

	//hago append de la pagina 
	$("#encuesta").append(html);
	
	//incorporo los objetos de estudio
	for (var objCont = 0; objCont < objEst.length; objCont++) {
		var html = $("#_objEstSel").html().replace("@objDesc",objEst[objCont].descrip);
		html = html.replace("@objEstSelSI","objEstSel"+objEst[objCont].id_obj_estudio);
		html = html.replace("@objEstSelNO","objEstSel"+objEst[objCont].id_obj_estudio);
		$("#objEsts_"+pagCon).append(html);
	}
	//armo recolector

	//incremento pagina
	pagCon++;
}

function getPregSelectObj(objEst){
	
 	respS = [];
	for (var cant = 0; cant < objEst.length; cant++) {
		respObj ={};
		respObj.objEst = objEst[cant].id_obj_estudio;
		respObj.value = $("input[name=objEstSel"+respObj.objEst+"]:checked").val();
		respS.push(respObj);
	}		
	
	
 	return respS;
}
</script>


<!---------------------------------------------- ESTILOS ----------------------------------------------->
<style>


.objEst span.icono:before {
	position: relative;
	overflow: hidden;
	left:-20px;
	top:5px;
}

.objEsts{
	/* background: yellow; */
}
.objEst{
	text-align: left;
}
.objest-descripcion{
	position: relative;
	font-weight: bold;
	font-size:18px;
	left:-10px;
	top:5px;
}

.regla{
	margin: 0 auto;
	width: 100%;
}
.regla input[type='radio'] {
	position:relative;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none; 
	width: 25px;
	height: 25px;
	-moz-border-radius: 50%;
	-webkit-border-radius: 50%;
	border-radius: 50%;
	background: #b1cfd7;
	border: 2px solid #000000;
}
.regla input[type='radio']:checked{
	position:relative;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none; 
	width: 25px;
	height:25px;
	-moz-border-radius: 50%;
	-webkit-border-radius: 50%;
	border-radius: 50%;
	background: #73d216;
}
.radios{
	background: #e6ecf1;
	width: 100%;
	max-width: 350px;
}
.label-selObj{
	position:relative;
	font-size: 20px;
	bottom:5px;
	margin-right: 2px;
}
</style>