<div id="pag_0" class="container-fluid contenedor" style="display: block">

	<div class="alert alert-info">
  		<span class="inicio-titulo">BIENVENIDO AL SISTEMA DE ENCUESTAS</span>
	</div>
	
	<div><img class="logo" src="logo.jpg"/></div>
	<div class="logo-descripcion"><span>Facultad de Humanidades Ciencias Sociales y de la Salud</span></div>
	<br><br>
	
	<div class="codigo-contenedor">
  		<div><input id="codigo" class="codigo" type="text" placeholder="INGRESAR CÓDIGO" value="" /></div>
  		<span><label class="codigo-error-tit">(código no valido)</label></span>
	</div>

	<div class="botonera">
		<button id="btns_0" type="button" class="btn btn-primary btn-cod"><span class="glyph-icon flaticon-siguiente"></span></button>
	</div>
</div>

<style>
.inicio-titulo{
	font-weight: bold;
	font-size: 20px;
}
}
.logo{
 	width: 50%;
    height: auto;
}
.logo-descripcion span{
	font-weight: bold;
	font-size: 20px;
}
.codigo-contenedor{
	width:100%;
	margin-bottom: 5px;
}
.codigo-contenedor input{
	
	font-weight: bold;
	font-size: 20px;
	text-transform: uppercase;
	text-align: center;
	border: 1px solid blue;
}
.codigo-error{
  outline: 2px solid red;
}
.codigo-error-tit{
	visibility: hidden;
	color:red;
	font-size: 18px;
	font-weight: bold;
	
}
.btn-cod{
	width: 90px;
	margin-bottom: 10px;
}
</style>