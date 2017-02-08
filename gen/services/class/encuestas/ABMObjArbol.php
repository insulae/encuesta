<?php 
require("Base.php");

class class_ABMObjArbol extends class_Base {
	function __construct() {
		parent::__construct();
	}
	
	function method_getObjArbol ($params, $error) {
		$p = $params[0];
		
		$res = new stdClass();
		$res->arbol = array();
		$res->obj_est = array();
		
		$q = $this->db->query("
		SELECT id_arbol as value, descrip as label FROM arbol WHERE id_arbol > 1
		");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		while ($r = $q->fetch_object()) {
			$res->arbol []= $r;
		}
		
		$q = $this->db->query("
		SELECT id_obj_estudio as value, descrip as label FROM obj_estudio
		");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		while ($r = $q->fetch_object()) {
			$res->obj_est []= $r;
		}
		
		return $res;
	}
	
	function method_getDatos ($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		SELECT id_obj_estudio,
		obj_estudio.descrip as obj_estudio
		FROM arbol_obj_estudio
		INNER JOIN obj_estudio USING(id_obj_estudio)
		WHERE arbol_obj_estudio.id_arbol = '" . $p->id_arbol . "'
		");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		$res = array();
		while ($r = $q->fetch_object()) {
			$res []= $r;
		}
		
		return $res;
	}
	
	function method_setDatos ($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("DELETE FROM arbol_obj_estudio WHERE id_arbol = '" . $p->id_arbol . "'");
		
		for ($i=0; $i<count($p->items); $i++) {
			$q = $this->db->query("
			INSERT INTO arbol_obj_estudio SET
			id_arbol = '" . $p->id_arbol . "',
			id_obj_estudio = '" . $p->items[$i] . "'
			");
			if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
			
		}
	}
}
?>