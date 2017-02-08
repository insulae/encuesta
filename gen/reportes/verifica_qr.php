<?php
require("qr.php");

$direccion = "http://fhu.unse.edu.ar/encuesta/index.php?codigo=";

$codigo = $_REQUEST["code"];

if (verificador($codigo, false)) {
	//echo "¡Codigo Correcto!. <br />1er Numero es el ID de la encuesta generada. Luego un . de separador. <br />Luego 4 numeros aleatorios en medio. <br />Al final el Digito verificador de todo.<br />";
	//QRcode::png($direccion . $_REQUEST["code"]);
	echo true;
} else {
	echo false;
}
?>