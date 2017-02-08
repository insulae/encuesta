<?php 
require("Base.php");

class class_Maestros extends class_Base {
	function __construct() {
		parent::__construct();
	}
	
	function method_getDatos ($params, $error) {
		$p = $params[0];
		
		$res = new stdClass();

		$q = $this->db->query("
		SELECT id_llamado as value,
		nro_llamado, descripcion
		FROM llamados
		");
		$res->llamados = Array();
		while ($r = $q->fetch_object()) {
			$r->label = $r->nro_llamado . " - " . $r->descripcion;
			$res->llamados []= $r;
		}
		
		$q = $this->db->query("
		SELECT id_docente as value,
		nro_doc, apellido, nombres
		FROM docentes
		");
		$res->docentes = Array();
		while ($r = $q->fetch_object()) {
			$r->label = $r->nro_doc . " - " . $r->apellido . ", " . $r->nombres;
			$res->docentes []= $r;
		}
		
		$q = $this->db->query("
		SELECT id_antecedente as value,
		codigo, denominacion
		FROM antecedentes
		WHERE id_antecedente IN (4, 5, 6, 7, 8, 9, 37, 54, 55, 56, 57, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 77, 78, 79, 80, 81, 82, 83, 84, 104, 105, 106, 107)
		");
		$res->antecedentes = Array();
		while ($r = $q->fetch_object()) {
			$r->label = $r->codigo . " - " . $r->denominacion;
			$res->antecedentes []= $r;
		}
		
		return $res;
	}
}
?>