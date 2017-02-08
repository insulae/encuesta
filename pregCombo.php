<div id="_pregCombo">
	<div id="@pag_nro" class="container-fluid contenedor pregCombo">
	
		<!-- PREGUNTA -->
		<div class="alert alert-info preg-contenedor">
			<span class="glyph-icon flaticon-circulo-pregunta"></span>
			<span class="preg-nro">@preg_titulo:</span>
	  		<span class="preg">@preg_desc</span>
		</div>
		
		<div class="form-group">
			<select id="@selectCombo" class="form-control selecto" style="font-weight: bold; font-size: 20px" ></select>
		</div>
	
		<div class="botonera">
			<button id="@btna_nro" type="button" class="btn btn-warning btn-atras"><span class="glyph-icon flaticon-atras"></span></button>
			<span class="faltantes">@pgc/@pgt</span>
			<button id="@btns_nro" type="button" class="btn btn-primary btn-sig"><span class="glyph-icon flaticon-siguiente"></span></button>
		</div>
	</div>
</div>


<script>
function  crearPregCombo(id,pregDesc,pregJson){
		var html= $("#_pregCombo").html().replace("@pag_nro","pag_"+pagCon);
		html = html.replace("@preg_titulo","PREGUNTA "+pregCon);
		html = html.replace("@preg_desc",pregDesc);
		html = html.replace("@selectCombo","selectCombo_pag"+pagCon);
		html = html.replace("@btns_nro","btns_"+pagCon);
		html = html.replace("@btna_nro","btna_"+pagCon);
		html = html.replace("@pgc",pagCon);
		html = html.replace("@pgt",pagTot);

		//armo recolector
		recolector.push({tipo:"combo",nro:pagCon,id:id});

		//hago append de la pagina 
		$("#encuesta").append(html);

		//opciones
		for (var opc = 0; opc < pregJson.length; opc++) {
			$('<option/>').val(pregJson[opc].value).html(pregJson[opc].label).appendTo('#selectCombo_pag'+pagCon);
		}		
		
		//incremento pagina
		pagCon++;
		pregCon++;
		
}
function getPregCombo(nro){
	respC = [];
 	respObj ={};
	respObj.objEst = 1;
	respObj.value = $("#selectCombo_pag"+nro).val();
	respC.push(respObj);
	return respC;
}

</script>

