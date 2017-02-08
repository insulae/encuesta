<?php 
require("Base.php");

class class_ABMObjetosEstudio extends class_Base {
	function __construct() {
		parent::__construct();
	}
	
	function method_getObjetosEstudio($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		SELECT
		obj_estudio.*
		FROM obj_estudio
		ORDER BY descrip
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }

		$res = array();
		while ($r = $q->fetch_object()) {
			$row = array();
			array_push($row, $r->id_obj_estudio);
			array_push($row, $r->descrip);
			array_push($res, $row);
		}
		return $res;
	}
	
	function method_btnBorrar($params, $error)
	{
		$q = $this->db->query("DELETE FROM obj_estudio WHERE id_obj_estudio = '" . $params[0] . "' LIMIT 1");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		return true;
	}
	
	function method_btnModificar($params, $error){
		
		$q = $this->db->query("SELECT * FROM obj_estudio WHERE id_obj_estudio = '" . $params[0]. "' LIMIT 1");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return $q->fetch_object();
	}
	
	function method_opAlta($params, $error) {
		$p = $params[0];
		
		$p->fecha_ingreso = explode("/", $p->fecha_ingreso);
		$p->fecha_ingreso = $p->fecha_ingreso[2] . "-" . $p->fecha_ingreso[1]. "-" . $p->fecha_ingreso[0];
		
		$p->fecha_vencimiento = explode("/", $p->fecha_vencimiento);
		$p->fecha_vencimiento = $p->fecha_vencimiento[2] . "-" . $p->fecha_vencimiento[1]. "-" . $p->fecha_vencimiento[0]; 
		
		$q = $this->db->query("
		INSERT INTO obj_estudio SET
		descrip = '" . $p->descrip . "'
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return true;
	}
	
	function method_opModificacion($params, $error){
		$p = $params[0];

		$q = $this->db->query("
		UPDATE obj_estudio SET
		descrip = '" . $p->descrip . "' 
		WHERE id_obj_estudio = '" . $p->id_obj_estudio . "'
		LIMIT 1");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return true;
	}
}
?>