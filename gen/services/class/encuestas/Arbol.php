<?php 
require("Base.php");

class class_Arbol extends class_Base {
	private $hojas = array();
	
	function __construct() {
		parent::__construct();
	}
	
	function method_getArbol ($params, $error) {
		$p = $params[0];
		
		$arbol = $this->getNodosHoja();
		return $arbol;
	}

	public function getNodosHoja () {
		$db = $this->db;
		
		$q = $db->query("
		SELECT *
		FROM arbol
		WHERE id_padre = 1
		ORDER BY descrip
		");
		if ($db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $db->error)); return $error; }
		
		$arbol = array();
		while ($r = $q->fetch_object()) {
			array_push($arbol, $this->hojas1($r));
		}
		return $arbol;
	}
	
	private function hojas1 ($p) {
		$db = $this->db;
		
		$q = $db->query("
		SELECT *
		FROM arbol
		WHERE id_padre = '" . $p->id_arbol . "'
		ORDER BY descrip
		");
		if ($db->error) { $error->SetError(JsonRpcError_Unknown, (__FILE__ . " - " . (__LINE__ - 2) . ": " . $db->error)); return $error; }
		
		if ($q->num_rows > 0) {
			$rama = "";
			$rama = new stdClass();
			$rama->id = $p->id_arbol;
			$rama->descrip = $p->descrip;
			$rama->items = array();
			while ($r = $q->fetch_object()) {
				array_push($rama->items, $this->hojas1($r));
			}
			return $rama;
		} else {
			$hoja = "";
			$hoja = new stdClass();
			$hoja->value = $p->id_arbol;
			$hoja->label = $p->descrip;
			
			array_push($this->hojas, $hoja);
			 
			$rama = "";
			$rama = new stdClass();
			$rama->id = $p->id_arbol;
			$rama->descrip = $p->descrip;
			
			return $rama;
		}
	}
	
	function method_getEncuestasArbol ($params, $error) {
		$p = $params[0];
		
		$q = $this->db->query("
		SELECT
		encuesta_generada.id_encuesta_generada,
		encuesta.titulo as encuesta,
		encuesta_generada.cantidad_qr,
		encuesta_generada.estado,
		encuesta_generada.fyh
		FROM encuesta_generada
		INNER JOIN encuesta USING(id_encuesta)
		WHERE id_arbol = '" . $p->id_arbol . "'
		");
		
		$res = array();
		while ($r = $q->fetch_object()) {
			switch ($r->estado) {
				case "R":
					$r->estado = "Esperando Respuestas";
				break;
				case "C":
					$r->estado = "Cerrada";
				break;
			}
			$res []= $r;
		}
		
		return $res;
	}
}
?>