<?php 
require("Base.php");

class class_ABMSubEncuestas extends class_Base {
	function __construct() {
		parent::__construct();
	}
	
	function method_getSubEncuestas($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		SELECT
		subencuesta.*
		FROM subencuesta
		ORDER BY titulo
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }

		$res = array();
		while ($r = $q->fetch_object()) {
			$row = array();
			array_push($row, $r->id_subencuesta);
			array_push($row, $r->titulo);
			array_push($row, $r->descrip);
			array_push($row, $r->observaciones);
			array_push($res, $row);
		}
		return $res;
	}
	
	function method_btnBorrar($params, $error)
	{
		$q = $this->db->query("DELETE FROM subencuesta WHERE id_subencuesta = '" . $params[0] . "' LIMIT 1");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		return true;
	}
	
	function method_btnModificar($params, $error){
		
		$q = $this->db->query("SELECT * FROM subencuesta WHERE id_subencuesta = '" . $params[0]. "' LIMIT 1");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return $q->fetch_object();
	}
	
	function method_opAlta($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		INSERT INTO subencuesta SET
		titulo = '" . $p->titulo . "',
		descrip = '" . $p->descrip . "',
		observaciones = '" . $p->observaciones . "'
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return true;
	}
	
	function method_opModificacion($params, $error){
		$p = $params[0];

		$q = $this->db->query("
		UPDATE subencuesta SET
		titulo = '" . $p->titulo . "',
		descrip = '" . $p->descrip . "',
		observaciones = '" . $p->observaciones . "'
		WHERE id_subencuesta = '" . $p->id_subencuesta . "'
		LIMIT 1");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return true;
	}
}
?>