<?php 
require("Base.php");

class class_ABMObjEstSubenc extends class_Base {
	function __construct() {
		parent::__construct();
	}
	
	function method_getObjEstSubenc($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		SELECT
		obj_estudio_subencuesta.id_obj_estudio_subencuesta,
		obj_estudio.descrip as obj_estudio,
		subencuesta.titulo as subencuesta
		FROM obj_estudio_subencuesta
		INNER JOIN obj_estudio USING(id_obj_estudio)
		INNER JOIN subencuesta USING(id_subencuesta)
		ORDER BY obj_estudio.descrip, subencuesta.titulo
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }

		$res = array();
		while ($r = $q->fetch_object()) {
			$row = array();
			array_push($row, $r->id_obj_estudio_subencuesta);
			array_push($row, $r->obj_estudio);
			array_push($row, $r->subencuesta);
			array_push($res, $row);
		}
		return $res;
	}
	
	function method_getObjEstudio($params, $error) {
		$result = new stdClass();
		$result->values = "";
		$result->nullValue = false;
		
		$q = $this->db->query("
		SELECT id_obj_estudio as value,
		descrip as label
		FROM obj_estudio
		ORDER BY descrip
		");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		while ($r = $q->fetch_object()) {
			$result->values []= $r;
		}
		return $result;
	}
	
	function method_getSubEncuesta($params, $error) {
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
		$q = $this->db->query("DELETE FROM obj_estudio_subencuesta WHERE id_obj_estudio_subencuesta = '" . $params[0] . "' LIMIT 1");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		return true;
	}
	
	function method_btnModificar($params, $error) {
	
		$q = $this->db->query("SELECT * FROM obj_estudio_subencuesta WHERE id_obj_estudio_subencuesta = '" . $params[0]. "' LIMIT 1");
	
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
	
		return $q->fetch_object();
	}
	
	function method_opAlta($params, $error) {
		$p = $params[0];
	
		$q = $this->db->query("
		INSERT INTO obj_estudio_subencuesta SET
		id_obj_estudio = '" . $p->id_obj_estudio . "',
		id_subencuesta = '" . $p->id_subencuesta . "'
		");
	
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
	
		return true;
	}
	
	function method_opModificacion($params, $error) {
		$p = $params[0];
	
		$q = $this->db->query("
		UPDATE obj_estudio_subencuesta SET
		id_obj_estudio = '" . $p->id_obj_estudio . "',
		id_subencuesta = '" . $p->id_subencuesta . "'
		WHERE id_obj_estudio_subencuesta = '" . $p->id_obj_estudio_subencuesta . "'
		LIMIT 1");
	
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
	
		return true;
	}
}
?>