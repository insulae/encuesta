<?php
/* datos de conexion */
$servidor = "localhost";
$usuario = "root";
$password = "toor";
$base = "encuesta_test";
$db = new mysqli($servidor, $usuario, $password, $base);
$db->query("SET NAMES 'utf8'");
function queryToObject($query){
	while($rs = $query->fetch_object()){
		$result[] = $rs;
	}
	return @$result;
}
function queryToArray($query){
	while($rs = $query->fetch_array()){
		$result[] = $rs;
	}
	return $result;
}
?>