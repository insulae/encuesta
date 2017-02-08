<?php 
require("Base.php");

class class_checks extends class_Base {
	function __construct() {
		parent::__construct();
	}
	
	function method_getDatos ($params, $error) {
		$p = $params[0];
		$db = $this->db;
		
		$q = $db->query("SELECT * FROM usuario");
		if ($db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 1) . ": " . $db->error)); return $error; }
		
		$res = Array();
		while ($r = $q->fetch_object()) {
			$res []= $r;
		}
		
		return $res;
	}
}
?>