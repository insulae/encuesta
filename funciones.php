<?php
include("engine/base.php");
include("qr/qr.php");
switch(@$_REQUEST["accion"]){

// ################################### avion ###################################	

//Validar Codigo
	case "validarCodigo":
		if(@$_POST['codigo'] == "NaN"){
			echo "ko";
		}
		else if (verificador(@$_POST['codigo'], false)) {
			//BUSCO SI YA INGRESO EL CODIGO
			$query = $db->query('
				SELECT codigo_autorizacion, estado
				FROM respuesta_encuesta
				WHERE codigo_autorizacion = "'.@$_POST['codigo'].'"
			');	
			if($db->errno){
				echo $db->error;
				die;
			}
			$datos = $query->fetch_object();
			
			if(!$datos->codigo_autorizacion){
				//GUARDO CODIGO OK INGRESADO
				$id = explode(".", $_POST["codigo"]);
				$query = $db->query('
				INSERT INTO respuesta_encuesta SET
					id_encuesta_generada = '.@$id[0].'
					, codigo_autorizacion = "'.@$_POST['codigo'].'"
					, estado = "A"
				');
				if(!$db->errno){
					echo "ok";
				}else{
					echo $db->error;
					die;
				}
			}
			else{
				if($datos->estado == "R"){
					//DEVUELVO CODIGO YA USADO
					echo "usado"; //cambiar a usado
				}
				else if($datos->estado == "A"){
					echo "ok";
				}
			}
		}else{
			echo "ko";
		}
	break;
	
//Buscar Json Encuesta
	case "buscarJsonEncuesta":
		$id = explode(".", $_POST["codigo"]);
		
		$query = $db->query('
			SELECT json
			FROM encuesta_generada
			WHERE id_encuesta_generada = "'.$id[0].'"
		');
		if($datos = $query->fetch_object()){
			$datos = unserialize($datos->json);
			echo json_encode($datos);
		}else{
			echo false;
		}
		
	break;
	
//guardar Preguntas Encuesta
	case "guardarRespuestas":
		 /* while(true){
			
		 } */ 
		$query = $db->query("
			UPDATE respuesta_encuesta SET
				json = '".serialize($_POST['respuestas'])."'
				, estado = 'R'
			WHERE 
				codigo_autorizacion = '".@$_POST['codigo']."'
			");
		if(!$db->errno){
				echo "ok";
		}else{
			echo $db->error;
		}
	break;
}	