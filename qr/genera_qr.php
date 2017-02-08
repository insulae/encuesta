<?php
require("qr.php");

$direccion = "http://fhu.unse.edu.ar/encuesta/index.php?codigo=";

$id_encuesta = 33;
$aleatorios_4 = rand(0000, 9999);
$digito_verificador = verificador(($id_encuesta . "." . $aleatorios_4), true); // aqui envia los 2 datos: id_encuesta, un punto y luego 4 numeros aleatorios. Se genera el ultimo, el digito verificador.

$codigo = $id_encuesta . "." . $aleatorios_4 . $digito_verificador;

echo $codigo;
//QRcode::png($direccion . $codigo);
?>
