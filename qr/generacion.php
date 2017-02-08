<?php
require("../engine/base.php");
$db = new mysqli($servidor, $usuario, $password, $base);
$db->query("SET NAMES 'utf8'");
require("impresion.php");
//require("genera_qr.php");

function cupon($codigo,$padre,$hijo,$cantdiv){
	if($hijo){
		$padre = $padre." / ".$hijo;
	}
	$div='<div class="troquel">
	<div class="troq-ladoizq">
			<div class="divtext1"><img src="img/logo.png" width="20em" align="top"/>ENCUESTA DE OPCION ACADEMICA FHU UNSE</div>
		<div class="divtext6">- Tu encuesta será anónima - </div>
		<div class="divtext2"><label id="arbol_text">'.$padre.'</label></div>
		<div class="divtext3">Desde tu celular o PC ingresa a:</div>
		<div class="divtext4">http://fhu.unse.edu.ar/encuesta</div>
		<div class="divtext5"><small>CODIGO: </small><span id="codigo_nro" class="codigo_nro">'.$codigo.'</span></div>
		
	</div>
	<div class="troq-ladoder">
		<div class="divqr">'.QRcode::png($direccion . $codigo).'
		O Escaneame!<br>
		<small><small><small>(instalá en tu cel. barcode scanner)</small></small></small>
		</div>
	</div>
</div>
';
	if($cantdiv == 5){
		$div.='<div class="cont-troqueles">&nbsp;</div>';
	}
	return $div;
}
function hojas($p, $db) {
	$q = $db->query("
	SELECT *
	FROM arbol
	WHERE id_padre = '" . $p->id_arbol . "'
	ORDER BY descrip
	");
	if ($db->error) { die($db->error); }

	if ($q->num_rows > 0) {
		$rama = "";
		$rama = new stdClass();
		$rama->id = $p->id_arbol;
		$rama->descrip = $p->descrip;
		$rama->items = array();
		while ($r = $q->fetch_object()) {
			array_push($rama->items, hojas($r, $db));
		}
		return $rama;
	} else {
		$rama = "";
		$rama = new stdClass();
		$rama->id = $p->id_arbol;
		$rama->descrip = $p->descrip;
		return $rama;
	}
}

function verificador($codigo, $generar) {
	$codigo = str_replace(".", "", $codigo);

	$multiplos[0] = 4;
	$multiplos[1] = 3;
	$multiplos[2] = 2;
	$multiplos[3] = 7;
	$multiplos[4] = 6;
	$multiplos[5] = 5;
	$multiplos[6] = 4;
	$multiplos[7] = 3;
	$multiplos[8] = 2;
	$multiplos[9] = 7;
	$multiplos[10] = 6;
	$multiplos[11] = 5;
	$multiplos[12] = 4;
	$multiplos[13] = 3;
	$multiplos[14] = 2;

	$sumador = 0;

	if ($generar) {
		$length = strlen($codigo);
	} else {
		$length = (strlen($codigo)-1);
	}

	for($i=0; $i<$length; $i++) {
		$sumador = $sumador + ($codigo[$i] * $multiplos[$i]);
	}

	$sumador = (11 - ($sumador % 11)) % 11;

	switch ($sumador) {
		case 10:
			$sumador = 1;
			break;
		case 11:
			$sumador = 0;
			break;
	}

	if ($generar) {
		return $sumador;
	} else {
		if ($codigo[(strlen($codigo)-1)] != $sumador) {
			return false;
		} else {
			return true;
		}
	}
}

$db = new mysqli($servidor, $usuario, $password, $base);
$db->query("SET NAMES 'utf8'");

$q = $db->query("SELECT * FROM encuesta_generada WHERE id_encuesta_generada = '" . $_REQUEST["id_encuesta_generada"] . "' LIMIT 1");
$encuesta_generada = $q->fetch_object();
$id_arbol = $encuesta_generada->id_arbol;

$q = $db->query("SELECT * FROM arbol WHERE id_arbol = '". $id_arbol . "'");
$root = $q->fetch_object();

$qfix = $db->query("SELECT * FROM arbol WHERE id_arbol = '". $root->id_padre. "'");
$qfix = $qfix->fetch_object();

$q = $db->query("
SELECT *
FROM arbol
WHERE id_padre = '" . $id_arbol . "' 
ORDER BY descrip
");
if ($db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $db->error)); return $error; }

$arbol = array();
$cantdiv=1;
while ($r = $q->fetch_object()) {
	array_push($arbol, hojas($r, $db));
}
if (count($arbol) > 0) {

		for ($i=0; $i<5; $i++) {

		for ($j=1; $j<=$encuesta_generada->cantidad_qr; $j++) {
			$verificador = "0";
// 			while ($verificador == "0") {
				$codigo = $encuesta_generada->id_encuesta_generada . "." . rand(1111, 9999);
				$verificador = verificador($codigo, true);
				$codigo .= $verificador;
// 				echo $verificador;
// 			}
			if (trim($verificador) != "0") {
				echo cupon($codigo,$root->descrip,$arbol[$i]->descrip,$cantdiv);
				if($cantdiv==5){
					$cantdiv=1;
				}
				else{
					$cantdiv++;
				}
			} else {
				$j--;
			}
		}
	}
} else {
	for ($j=1; $j<=$encuesta_generada->cantidad_qr; $j++) {
		$codigo = $encuesta_generada->id_encuesta_generada . "." . rand(1111, 9999);
		$codigo .= verificador($codigo, true);
		$verificador = "0";
// 		while ($verificador == "0") {
			$codigo = $encuesta_generada->id_encuesta_generada . "." . rand(1111, 9999);
			$verificador = verificador($codigo, true);
			$codigo .= $verificador;
// 			echo $verificador;
// 		}
		if (trim($verificador) != "0") {
			echo cupon($codigo,$qfix->descrip,$root->descrip,$cantdiv);
			if($cantdiv==5){
				$cantdiv=1;
			}
			else{
				$cantdiv++;
			}
			
		} else {
			$j--;
		}
	}
}
if ($_REQUEST["print"] == "1") {
	echo "<script>window.print()</script>";
}
?>
