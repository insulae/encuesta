<?php 
require("Base.php");

class class_ABMEncuestas extends class_Base {
	function __construct() {
		parent::__construct();
	}
	
	function method_getEncuestasCMB ($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		SELECT
		id_encuesta as value,
		titulo as label
		FROM encuesta
		ORDER BY titulo
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		$res = array();
		while ($r = $q->fetch_object()) {
			$res []= $r;
		}
		
		return $res;
	}
	
	function method_getEncuestas($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		SELECT
		encuesta.*
		FROM encuesta
		ORDER BY titulo
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }

		$res = array();
		while ($r = $q->fetch_object()) {
			$row = array();
			array_push($row, $r->id_encuesta);
			array_push($row, $r->titulo);
			array_push($row, $r->descrip);
			array_push($row, $r->observaciones);
			array_push($res, $row);
		}
		return $res;
	}

	function method_btnBorrar($params, $error) {
		$q = $this->db->query("DELETE FROM encuesta WHERE id_encuesta = '" . $params[0] . "' LIMIT 1");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		return true;
	}
	
	function method_btnModificar($params, $error) {
		
		$q = $this->db->query("SELECT * FROM encuesta WHERE id_encuesta = '" . $params[0]. "' LIMIT 1");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return $q->fetch_object();
	}
	
	function method_opAlta($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		INSERT INTO encuesta SET
		titulo = '" . $p->titulo . "',
		descrip = '" . $p->descrip . "',
		observaciones = '" . $p->observaciones . "'
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return true;
	}
	
	function method_opModificacion($params, $error) {
		$p = $params[0];

		$q = $this->db->query("
		UPDATE encuesta SET
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