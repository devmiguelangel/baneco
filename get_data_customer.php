<?php
require 'classes/ws_baneco.php';
$response = array("mess" => '', "data" => array());

if (isset($_GET['dni'])) {
	$dni = $_GET['dni'];

	$ws = new WSBaneco('SC');

	if ($ws->ws_connect(array('NroDocumento' => $dni)) === true) {
		$k = 0;
		if (array_key_exists(0, $ws->data) === true) {
			foreach ($ws->data as $key => $data_ws) {
				$k += 1;
				$response['data'][$k] = array(
					"code" => $data_ws['codigo'],
					"name" => trim($data_ws['nombrecompleto']),
					"dni" => $data_ws['nrodocumento']
				);
			}
		} else {
			$data_ws = $ws->data;
			$response['data'][$k + 1] = array(
				"code" => $data_ws['codigo'],
				"name" => trim($data_ws['nombrecompleto']),
				"dni" => $data_ws['nrodocumento']
			);
		}
	} else {
		$response['mess'] = $ws->message;
	}
}

echo json_encode($response);
?>