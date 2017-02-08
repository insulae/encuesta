<?php 
require("Base.php");

class class_ABMSubEncEnc extends class_Base {
	function __construct() {
		parent::__construct();
	}
	
	function method_getEncuestaItems($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		SELECT
		encuesta_item.id_encuesta_item,
		encuesta.titulo as encuesta,
		subencuesta.titulo as subencuesta
		FROM encuesta_item
		INNER JOIN encuesta USING(id_encuesta)
		INNER JOIN subencuesta USING(id_subencuesta)
		ORDER BY encuesta.titulo, subencuesta.titulo
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }

		$res = array();
		while ($r = $q->fetch_object()) {
			$row = array();
			array_push($row, $r->id_encuesta_item);
			array_push($row, $r->encuesta);
			array_push($row, $r->subencuesta);
			array_push($res, $row);
		}
		return $res;
	}
	
	function method_getEncuestas($params, $error) {
		$result = new stdClass();
		$result->values = "";
		$result->nullValue = false;
	
		$q = $this->db->query("
		SELECT id_encuesta as value,
		titulo as label
		FROM encuesta
		ORDER BY titulo
		");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
	
		while ($r = $q->fetch_object()) {
			$result->values []= $r;
		}
		return $result;
	}
	
	function method_getSubEncuestas($params, $error) {
		$result = new stdClass();
		$result->values = "";
		$result->nullValue = false;
		
		$q = $this->db->query("
		SELECT id_subencuesta as value,
		titulo as label
		FROM subencuesta
		ORDER BY titulo
		");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		while ($r = $q->fetch_object()) {
			$result->values []= $r;
		}
		return $result;
	}
	
	function method_btnBorrar($params, $error) {
		$q = $this->db->query("DELETE FROM encuesta_item WHERE id_encuesta_item = '" . $params[0] . "' LIMIT 1");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		return true;
	}
	
	function method_btnModificar($params, $error) {
	
		$q = $this->db->query("SELECT * FROM encuesta_item WHERE id_encuesta_item = '" . $params[0]. "' LIMIT 1");
	
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
	
		return $q->fetch_object();
	}
	
	function method_opAlta($params, $error) {
		$p = $params[0];
	
		$q = $this->db->query("
		INSERT INTO encuesta_item SET
		id_encuesta = '" . $p->id_encuesta . "',
		id_subencuesta = '" . $p->id_subencuesta . "'
		");
	
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
	
		return true;
	}
	
	function method_opModificacion($params, $error) {
		$p = $params[0];
	
		$q = $this->db->query("
		UPDATE encuesta_item SET
		id_encuesta = '" . $p->id_encuesta . "',
		id_subencuesta = '" . $p->id_subencuesta . "'
		WHERE id_encuesta_item = '" . $p->id_encuesta_item . "'
		LIMIT 1");
	
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
	
		return true;
	}
}
?>