<?php 
require("Base.php");

class class_Generador extends class_Base {
	function __construct() {
		parent::__construct();
	}
	
	function method_generar ($params, $error) {
		$p = $params[0];
		
		$qEncuesta = $this->db->query("
		SELECT * 
		FROM encuesta
		WHERE id_encuesta = '" . $p->id_encuesta . "'
		LIMIT 1
		");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		$json = new stdClass();
		
		if ($rEncuesta = $qEncuesta->fetch_object()) {
			$json->titulo = $rEncuesta->titulo;
			$json->descrip = $rEncuesta->descrip;
			$json->observaciones = $rEncuesta->observaciones;
			$json->encuesta_items = Array();
			
			$qEncuestaItems = $this->db->query("
			SELECT subencuesta.*
			FROM encuesta_item
			INNER JOIN subencuesta USING(id_subencuesta)
			WHERE id_encuesta = '" . $rEncuesta->id_encuesta . "'
			");
			if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
			
			while ($rSubencuesta = $qEncuestaItems->fetch_object()) {
				$qPreguntas = $this->db->query("
				SELECT pregunta.* 
				FROM pregunta
				WHERE id_subencuesta = '" . $rSubencuesta->id_subencuesta . "'
				ORDER BY orden
				");
				if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
				while ($rPreguntas = $qPreguntas->fetch_object()) {
					
					$qTipoRespuesta = $this->db->query("
					SELECT tipo_respuesta.* 
					FROM tipo_respuesta
					WHERE id_tipo_respuesta = '" . $rPreguntas->id_tipo_respuesta . "'
					LIMIT 1
					");
					if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
					if ($rTipoRespuesta = $qTipoRespuesta->fetch_object()) {
						$rTipoRespuesta->json = unserialize($rTipoRespuesta->json); 
						$rPreguntas->tipo_respuesta = $rTipoRespuesta;
					}
					
					$rSubencuesta->preguntas []= $rPreguntas;
				}
				
				$qObjEstudios = $this->db->query("
				SELECT obj_estudio.*
				FROM obj_estudio_subencuesta
				INNER JOIN obj_estudio USING(id_obj_estudio)
				INNER JOIN arbol_obj_estudio ON obj_estudio.id_obj_estudio = arbol_obj_estudio.id_obj_estudio AND arbol_obj_estudio.id_arbol = '" . $p->id_arbol . "' 
				WHERE id_subencuesta = '" . $rSubencuesta->id_subencuesta . "'
				ORDER BY obj_estudio.descrip
				");
				if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
				while ($rObjEstudios = $qObjEstudios->fetch_object()) {
					$rSubencuesta->obj_estudio []= $rObjEstudios;
				}
				
				$json->encuesta_items []= $rSubencuesta;
			}
		}
		
		$q = $this->db->query("
		INSERT INTO encuesta_generada SET
		id_arbol = '" . $p->id_arbol . "',
		id_encuesta = '" . $p->id_encuesta . "',
		json = '" . serialize($json) . "',
		cantidad_qr = '" . $p->cantidad_qr . "',
		estado = 'R'
		");
		if ($this->db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $this->db->error)); return $error; }
		
		$id = $this->db->insert_id;
		
		$q = $this->db->query("
		SELECT * FROM encuesta_generada ORDER BY id_encuesta_generada DESC LIMIT 1
		");
		$r = $q->fetch_object();
		$r->json = unserialize($r->json); 
		return $id;
	}
}
?>
