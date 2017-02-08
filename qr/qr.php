<?php
require("phpqrcode/phpqrcode.php");
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
?>
