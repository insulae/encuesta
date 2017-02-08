<?php 
require("Base.php");

class class_ABMPreguntas extends class_Base {
	function __construct() {
		parent::__construct();
	}
	
	function method_getPreguntas($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		SELECT
		pregunta.id_pregunta,
		subencuesta.titulo,
		pregunta.descrip as pregunta,
		tipo_respuesta.tipo_objeto,
		pregunta.obj_estudio
		FROM pregunta
		INNER JOIN subencuesta USING(id_subencuesta)
		INNER JOIN tipo_respuesta USING(id_tipo_respuesta)
		ORDER BY subencuesta.titulo, pregunta.descrip
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }

		$res = array();
		while ($r = $q->fetch_object()) {
			$row = array();
			array_push($row, $r->id_pregunta);
			array_push($row, $r->titulo);
			array_push($row, $r->pregunta);
			array_push($row, $r->tipo_objeto);
			array_push($row, $r->obj_estudio);
			array_push($res, $row);
		}
		return $res;
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
	
	function method_getTipoRespuesta($params, $error) {
		$result = new stdClass();
		$result->values = "";
		$result->nullValue = false;
	
		$q = $this->db->query("
		SELECT id_tipo_respuesta as value,
		tipo_objeto as label
		FROM tipo_respuesta
		ORDER BY tipo_objeto
		");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
	
		while ($r = $q->fetch_object()) {
			$result->values []= $r;
		}
		return $result;
	}
	
	function method_btnBorrar($params, $error) {
		$q = $this->db->query("DELETE FROM pregunta WHERE id_pregunta = '" . $params[0] . "' LIMIT 1");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		return true;
	}
	
	function method_btnModificar($params, $error) {
		
		$q = $this->db->query("SELECT * FROM pregunta WHERE id_pregunta = '" . $params[0]. "' LIMIT 1");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return $q->fetch_object();
	}
	
	function method_opAlta($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		INSERT INTO pregunta SET
		id_subencuesta = '" . $p->id_subencuesta . "',
		descrip = '" . $p->descrip . "',
		id_tipo_respuesta = '" . $p->id_tipo_respuesta . "',
		obj_estudio = '" . $p->obj_estudio . "'
		");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return true;
	}
	
	function method_opModificacion($params, $error) {
		$p = $params[0];

		$q = $this->db->query("
		UPDATE pregunta SET
		id_subencuesta = '" . $p->id_subencuesta . "',
		descrip = '" . $p->descrip . "',
		id_tipo_respuesta = '" . $p->id_tipo_respuesta . "',
		obj_estudio = '" . $p->obj_estudio . "'
		WHERE id_encuesta = '" . $p->id_encuesta . "' LIMIT 1");
		
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		return true;
	}
}
?>