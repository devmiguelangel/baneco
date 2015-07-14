<?php
require('sibas-db.class.php');

$arrAP = array(0 => 0, 1 => 'R', 2 => 'Error.');

if(isset($_POST['dq-idc']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr'])){
	$swEmpty = false;

	if($swEmpty === false && $_POST['pr'] === base64_encode('AP|03')){
		$link = new SibasDB();
		
		$idc = $link->real_escape_string(trim(base64_decode($_POST['dq-idc'])));
		$idef = $link->real_escape_string(trim(base64_decode($_POST['dq-idef'])));
		
		$max_item = 0;
		if (($rowAP = $link->get_max_amount_optional(base64_encode($idef), 'AP')) !== false) {
			$max_item = (int)$rowAP['max_item'];
		}
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		$nCl = $link->number_clients_AP($idc, $idef, true);
		$response = 0;
		
		if($nCl > 0 && $nCl <= $max_item){
			$flag = array();
			$resp = array();
			$idd = array();
			$arr_QR = array();

			for ($i=1; $i <= $nCl; $i++) {
				$flag[$i] = false;
				$resp[$i] = $link->real_escape_string(trim($_POST['dq-resp-' . $i]));
				$idd[$i] = $link->real_escape_string(trim(base64_decode($_POST['dq-idd-' . $i])));

				$arr_QR[$i] = array();
			}
			
			if (($rsQs = $link->get_question(base64_encode($idef), 'AP')) !== false) {
				for ($k=1; $k <= $nCl; $k++) {
					if ($rsQs->data_seek(0) === true) {
						$i = 0;
						while($rowQs = $rsQs->fetch_array(MYSQLI_ASSOC)){
							$i += 1;

							$valPr = $link->real_escape_string(trim($_POST['dq-qs-' . $k 
								. '-' . $rowQs['id_pregunta']]));
							
							if($rowQs['respuesta'] !== $valPr) {
								$flag[$k] = true;
								$response = 1;
							}
							
							$arr_QR[$k][$rowQs['id_pregunta']] = $rowQs['id_pregunta'] . '|' . $valPr;
						}
					}

				}

				$sql = 'insert into s_ap_cot_respuesta 
					(id_respuesta, id_detalle, respuesta, observacion) 
					values ';

				for ($i=1; $i <= $nCl; $i++) {
					if ($flag[$i] === false) {
						$resp[$i] = '';
					}

					$sql .= '("' . uniqid('@S#1$2013-' . $i, true) . '", 
						"' . $idd[$i] . '", 
						"' . $link->real_escape_string(json_encode($arr_QR[$i])) . '", 
						"' . $resp[$i] . '")';

					if ($i === $nCl) {
						$sql .= ';';
					} else {
						$sql .= ',';
					}
				}

				//echo $sql;
				if($link->query($sql) === true){
					$sql = 'update s_ap_cot_cabecera 
					set cobertura = "' . $nCl . '",
						respuesta = "' . $response . '"
					where 
						id_cotizacion = "' . $idc . '"
					;';

					if ($link->query($sql) === true) {
						$arrAP[0] = 1;
						$arrAP[1] = 'ap-quote.php?ms=' . $ms . '&page=' . $page 
							. '&pr=' . base64_encode('AP|04') . '&idc=' . base64_encode($idc);
						$arrAP[2] = 'Las respuestas se registraron correctamente';
					} else {
						$arrAP[2] = 'Error al registrar las respuestas.';
					}
				}else{
					$arrAP[2] = 'Error al registrar las respuestas';
				}
			}else{
				$arrAP[2] = 'No existen Preguntas';
			}
		}else{
			$arrAP[2] = 'No se pueden registrar las respuestas';
		}
		echo json_encode($arrAP);
	}else{
		$arrAP[2] = 'Respuestas incompletas';
		echo json_encode($arrAP);
	}
}else{
	echo json_encode($arrAP);
}
?>