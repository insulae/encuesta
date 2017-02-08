<?php
class class_Base
{
	protected $db;
	function __construct() {
		require('conexion.php');
		
		$this->db = new mysqli($servidor, $usuario, $password, $base);
		$this->db->query("SET NAMES 'utf8'");
	}
}
?>
