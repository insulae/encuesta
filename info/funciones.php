<?php
include("../engine/base.php");
switch(@$_REQUEST["accion"]){

// ################################### avion ###################################	

//guardar Preguntas Encuesta
	case "encuesta_materias":
		$q = $db->query("
			SELECT eg.id_encuesta_generada, ar.id_arbol, ar.id_padre,ar.descrip, sum(eg.cantidad_qr) as cantidad
			FROM encuesta_generada as eg
			INNER JOIN arbol as ar using(id_arbol) 
			GROUP BY (id_arbol)
		");
		if(!$db->errno){
				while($r = $q->fetch_object()){
					$q2 = $db->query("
						SELECT 
						SUM( CASE WHEN estado = 'R' THEN 1 ELSE 0 END ) as respondidos,
						SUM( CASE WHEN estado = 'A' THEN 1 ELSE 0 END ) as abiertos
						FROM respuesta_encuesta as re
						WHERE re.id_encuesta_generada ='".$r->id_encuesta_generada."'
					");
					$r2 = $q2->fetch_object();
					$r->respondidos = $r2->respondidos;
					$r->abiertos = $r2->abiertos;
					
					
					//UGLY FIX
					$q3 = $db->query("
						SELECT descrip
						FROM arbol
						WHERE id_arbol = '".$r->id_padre."'
					");
					$r3 = $q3->fetch_object();
					$r2 = $q2->fetch_object();
					
					$r->carrera = $r3->descrip;
					
					//DATOS A ENVIAR
					$result[] = $r;
				}
				echo json_encode($result);
		}else{
			echo $db->error;
		}
	break;
	
	//guardar Preguntas Encuesta
	case "encuesta_profes":
		$q1 = $db->query("
				SELECT json
				FROM encuesta_generada
				WHERE id_encuesta_generada = '".$_POST['id_encuesta_generada']."'
			");
		if(!$db->errno){
			$result = new stdClass;
			
			$result->obj_estudios = array();
			while($r = $q1->fetch_object()){
				$json = unserialize($r->json);
				$json = $json->encuesta_items[0];
				foreach ($json->obj_estudio as $oe){
					$row= new stdClass();
					$row->id_obj = $oe->id_obj_estudio;
					$row->obj_descrip = $oe->descrip;
					//busco respuestas
					$q2 = $db->query("
						SELECT *
						FROM respuesta_encuesta
						WHERE id_encuesta_generada = '".$_POST['id_encuesta_generada']."'
						AND estado = 'R'
					");
					if(!$db->errno){
						$val0 = 0;
						$val1 = 0;
						$val2 = 0;
						$val3 = 0;
						$val4 = 0;
						$val5 = 0;
						$total=0;
						$comentarios="";
						while($r2 = $q2->fetch_object()){
							
 							//$json2 = unserialize($r2->json);

							//fix del serialize
							$patron = '/s:(\d+):/';
							$cambio = '';
							$json2= preg_replace($patron, $cambio, $r2->json);
							$json2 = substr($json2, 1, -2);
							
							$json2 = json_decode($json2);
							//print_r($json2);
							
							//saco respuesta comentario
							if($_POST["preg_nro"]==12){
								if($json2[14]->resp[0]->value != ""){
									$comentarios.="*".$json2[14]->resp[0]->value."<hr/>";	
								}
							}
							
							//saco respuestas sliderEx
 							foreach($json2[$_POST["preg_nro"]]->resp as $resps){
								if($resps->objEst == $row->id_obj){
									$total++;
									switch($resps->value){
										case "0":
											$val0++;
										break;
										case "1":
											$val1++;
										break;
										case "2":
											$val2++;
										break;
										case "3":
											$val3++;
											break;
										case "4":
											$val4++;
											break;
										case "5":
											$val5++;
										break;										
									}
									
								}
							} 
						}
						
					}
					
					//calculo porcentajes
					if($total>0){
						$row->no_resp = (round($val0/$total*100,0))."% (".$val0.")";
						$row->malo = (round($val1/$total*100,0))."% (".$val1.")";
						$row->regular = (round($val2/$total*100,0))."% (".$val2.")";
						$row->bueno = (round($val3/$total*100,0))."% (".$val3.")";
						$row->muy_bueno = (round($val4/$total*100,0))."% (".$val4.")";
						$row->excelente = (round($val5/$total*100,0))."% (".$val5.")";
					}
					//guardo respuestas	
					array_push($result->obj_estudios,$row);
				}
			}			
		}
		else{
			echo $db->error;
			die;
		}

		//seteo titulo
		$orden = $_POST["preg_nro"]+1;
		$qp = $db->query("
						SELECT descrip
						FROM pregunta
						WHERE id_subencuesta = 1
						and orden = '".$orden."'
					");
		$resp = $qp->fetch_object();
		$result->materia = $resp->descrip;
		$result->comentarios = $comentarios;
		
		//envio json
		echo json_encode($result);
	break;
	
// ENCUESTA ALUMNOS
	case "encuesta_alumnos":
		$result = new stdClass();
		$resps = array();
		for($i=0;6>$i;$i++){
			$resp = new stdClass();
			$resp->val0 = 0;
			$resp->val1 = 0;
			$resp->val2 = 0;
			$resp->val3 = 0;
			$resp->val4 = 0;
			$resp->val5 = 0;
			$resp->val6 = 0;
			$resp->val7 = 0;
			$resp->val8 = 0;
			$resp->val9 = 0;
			$resp->val10 = 0;	
			array_push($resps,$resp);
		}
		$q2 = $db->query("
			SELECT *
			FROM respuesta_encuesta
			WHERE id_encuesta_generada = '".$_POST['id_encuesta_generada']."'
			AND estado = 'R'
		");
		if(!$db->errno){
			
			// descripcion preguntas
			$r = 0;
			$qp = $db->query("
						SELECT descrip
						FROM pregunta
						WHERE id_subencuesta = 2
						AND id_tipo_respuesta = 4
					");
			while($rs = $qp->fetch_object()){
				$resps[$r]->descrip = $rs->descrip;
				$r++;
			}
			
			//recorro resultados
			while($r2 = $q2->fetch_object()){
				$patron = '/s:(\d+):/';
				$cambio = '';
				$json2= preg_replace($patron, $cambio, $r2->json);
				$json2 = substr($json2, 1, -2);
				$json2 = json_decode($json2);
				$total++;
				for($i=15;22>$i;$i++){
					$jresp = $json2[$i]->resp;
					$j=-15+$i;
					switch($jresp[0]->value){						
						case "0":
							$resps[$j]->val0++;
							break;
						case "1":
							$resps[$j]->val1++;
							break;
						case "2":
							$resps[$j]->val2++;
							break;
						case "3":
							$resps[$j]->val3++;
							break;
						case "4":
							$resps[$j]->val4++;
							break;
						case "5":
							$resps[$j]->val5++;
							break;
						case "6":
							$resps[$j]->val6++;
							break;
						case "7":
							$resps[$j]->val7++;
							break;
						case "8":
							$resps[$j]->val8++;
							break;
						case "9":
							$resps[$j]->val9++;
							break;
						case "10":
							$resps[$j]->val10++;
							break;							
					}	
				}
			}
		}
		//calculo porcentajes
		for($i=0;6>$i;$i++){
			
	 		if($total>0){
	 			$resps[$i]->val0 = (round($resps[$i]->val0/$total*100,0))."% (".$resps[$i]->val0.")";
	 			$resps[$i]->val1 = (round($resps[$i]->val1/$total*100,0))."% (".$resps[$i]->val1.")";
	 			$resps[$i]->val2 = (round($resps[$i]->val2/$total*100,0))."% (".$resps[$i]->val2.")";
	 			$resps[$i]->val3 = (round($resps[$i]->val3/$total*100,0))."% (".$resps[$i]->val3.")";
	 			$resps[$i]->val4 = (round($resps[$i]->val4/$total*100,0))."% (".$resps[$i]->val4.")";
	 			$resps[$i]->val5 = (round($resps[$i]->val5/$total*100,0))."% (".$resps[$i]->val5.")";
	 			$resps[$i]->val6 = (round($resps[$i]->val6/$total*100,0))."% (".$resps[$i]->val6.")";
	 			$resps[$i]->val7 = (round($resps[$i]->val7/$total*100,0))."% (".$resps[$i]->val7.")";
	 			$resps[$i]->val8 = (round($resps[$i]->val8/$total*100,0))."% (".$resps[$i]->val8.")";
	 			$resps[$i]->val9 = (round($resps[$i]->val9/$total*100,0))."% (".$resps[$i]->val9.")";
	 			$resps[$i]->val10 = (round($resps[$i]->val10/$total*100,0))."% (".$resps[$i]->val10.")";
			}
		}
	
		//envio json
		$result->resps = $resps;
		echo json_encode($result);
		break;
}	
?>