<?php
require 'session.class.php';
require 'classes/ws_baneco.php';

$session = new Session();

$session->getSessionCookie();
$token = $session->check_session();
$arrDE = array(0 => 0, 1 => 'R', 2 => '');

if ($token === true){
	require 'sibas-db.class.php';
	$link = new SibasDB();

	if (isset($_POST['flag']) && (isset($_POST['de-idc']) || isset($_POST['de-ide'])) 
		&& isset($_POST['ms']) && isset($_POST['page']) 
		&& isset($_POST['pr']) && isset($_POST['cia']) ){

		if ($_POST['pr'] === base64_encode('VI|05')){
			$sw = 0;
		
			$ms = $link->real_escape_string(trim($_POST['ms']));
			$page = $link->real_escape_string(trim($_POST['page']));
			$pr = $link->real_escape_string(trim($_POST['pr']));
			
			$beneficiary = $link->getBeneficiary();
		
			$max_item = 0;
			if (($rowDE = $link->get_max_amount_optional($_SESSION['idEF'], 'VI')) !== false) {
				$max_item = (int)$rowDE['max_item'];
			}
		
			$target = false;
			$target_url = '';
			if (isset($_POST['target'])) {
				$target = true;
				$target_url = '&target=' . $link->real_escape_string(trim($_POST['target']));
			}
		
			$ws_cnx = false;
			$flag = $_POST['flag'];
			$percentage = $account = $rank = true;
			$per_mess = $account_mess = $rank_mess = $policy_mess = '';
		
			switch($flag){
			case md5('i-new'):		//POLIZA NUEVA
				$sw = 1;
				break;
			case md5('i-read'):		//
				$sw = 2;
				break;
			case md5('i-edit'):		//POLIZA ACTUALIAZADA
				$sw = 3;
				break;
			}

			if ($sw !== 0){
				if ($link->checkWebService($_SESSION['idEF'], 'VI') === true) {
					$ws_cnx = true;
				}

				$ws_code = $ws_client = $ws_usuario = '';
				$taken_flag = false;
				$bill_name = $bill_nit = $taken_code = $taken_name = $taken_nit = '';
				$arr_cl = array();
				$nCl = 0;

				if (isset($_POST['dc-1-idcl'])) {		//TITULAR 1
					$nCl = 1;
				}
				if (isset($_POST['dc-2-idcl'])) {		//TITULAR 2
					$nCl = 2;
				}
			
				if ($nCl > 0 && $nCl <= $max_item){		// VERIFICAR SI EXISTEN CLIENTES
					$swDE = false;
					
					$dcr_cia = $link->real_escape_string(trim(base64_decode($_POST['cia'])));
					$dcr_policy = $link->real_escape_string(trim(base64_decode($_POST['di-policy'])));
					$dcr_no_policy = $link->policy['VI'];
					$bill_name = $link->real_escape_string(trim($_POST['bl-name']));
					$bill_nit = $link->real_escape_string(trim($_POST['bl-nit']));
					if (isset($_POST['taken-flag'])) {
						$taken_flag = true;
					}
					$taken_code = $link->real_escape_string(trim($_POST['taken-code']));
					$taken_name = $link->real_escape_string(trim($_POST['taken-name']));
					$taken_nit = $link->real_escape_string(trim($_POST['taken-nit']));

					$dcr_plan = $link->real_escape_string(trim($_POST['dc-plan']));
					$dcr_prima = 0;
					if (($plan = $link->get_plan($_SESSION['idEF'], 'VI', $dcr_plan)) !== false) {
						$dcr_prima = $plan['prima_anual'];
					}
					$dcr_payment = $link->real_escape_string(trim($_POST['dc-payment']));
					$dcr_period = $link->real_escape_string(trim($_POST['dc-period']));
					$dcr_print = 0;
					$dcr_policy_pp = '';
					$dcr_rango = 'null';
					$arr_qs = array(1 => array());
					$arr_res = array(1 => '');

					if (isset($_POST['di-print'], $_POST['di-policy-pp'])) {
						$dcr_print = 1;
						$dcr_policy_pp = $link->real_escape_string(trim($_POST['di-policy-pp']));

						/*if (($data = $link->check_policy_pp($_SESSION['idUser'], 'VI', $dcr_policy_pp)) !== false) {
							$dcr_rango = '"' . $data['id_rango'] . '"';

							if (($rsQs = $link->get_question($_SESSION['idEF'], 'VI')) !== false) {
								for ($k=1; $k <= $nCl; $k++) {
									if ($rsQs->data_seek(0) === true) {
										$i = 0;
										while($rowQs = $rsQs->fetch_array(MYSQLI_ASSOC)){
											$i += 1;

											$valPr = $link->real_escape_string(trim($_POST['dq-qs-' 
												. $k . '-' . $rowQs['id_pregunta']]));
											
											if($rowQs['respuesta'] === $valPr) {
												$arr_res[$k] = '';
											}
											
											$arr_qs[$k][$rowQs['id_pregunta']] = 
												$rowQs['id_pregunta'] . '|' . $valPr;
										}
									}
								}
							}
						} else {
							$rank = false;
							$rank_mess = 'La Agencia no tiene asignado el Número de Póliza ' 
								. $dcr_policy_pp;
						}*/
					}
										
					$cont = 0;

					while($cont < $nCl){
						$cont += 1;
						
						$arr_cl[$cont]['cl-id'] = 
							$link->real_escape_string(trim(base64_decode($_POST['dc-'.$cont.'-idcl'])));
						$ws_code = $arr_cl[$cont]['cl-code'] = 
							$link->real_escape_string(trim(base64_decode($_POST['dc-'.$cont.'-code'])));
						$arr_cl[$cont]['cl-name'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-name']));
						$arr_cl[$cont]['cl-patern'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-ln-patern']));
						$arr_cl[$cont]['cl-matern'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-ln-matern']));
						$ws_client = $arr_cl[$cont]['cl-name'] . ' ' . $arr_cl[$cont]['cl-patern'] 
							. ' ' . $arr_cl[$cont]['cl-matern'];
						$arr_cl[$cont]['cl-married'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-ln-married']));
						$arr_cl[$cont]['cl-gender'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-gender']));
						$arr_cl[$cont]['cl-doc-id'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-doc-id']));
						$arr_cl[$cont]['cl-comp'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-comp']));
						$arr_cl[$cont]['cl-ext'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-ext']));
						$arr_cl[$cont]['cl-date'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-date-birth']));
						$arr_cl[$cont]['cl-age'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-age']));
						$arr_cl[$cont]['cl-place-birth'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-place-birth']));
						$arr_cl[$cont]['cl-avc'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-avc']));
						$arr_cl[$cont]['cl-address-home'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-address-home']));
						$arr_cl[$cont]['cl-nhome'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-nhome']));
						$arr_cl[$cont]['cl-phone-1'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-phone-1']));
						$arr_cl[$cont]['cl-phone-2'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-phone-2']));
						$arr_cl[$cont]['cl-phone-office'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-phone-office']));
						$arr_cl[$cont]['cl-email'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-email']));
						$arr_cl[$cont]['cl-occupation'] = 
							$link->real_escape_string(trim(base64_decode($_POST['dc-'.$cont.'-occupation'])));
						$arr_cl[$cont]['cl-desc-occ'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-desc-occ']));
						$arr_cl[$cont]['cl-weight'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-weight']));
						$arr_cl[$cont]['cl-height'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-height']));
						$arr_cl[$cont]['cl-work'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-work']));
						$arr_cl[$cont]['cl-account-1'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-account-1']));
						$arr_cl[$cont]['cl-account-2'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-account-2']));
						$arr_cl[$cont]['cl-card'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-card']));
						$arr_cl[$cont]['cl-share'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-share']));
						$arr_cl[$cont]['cl-titular'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-titular']));
						$arr_cl[$cont]['cl-type'] = 
							$link->real_escape_string(trim($_POST['dc-'.$cont.'-type']));

						if ($ws_cnx === true) {
							if ($dcr_payment === 'DA' || $dcr_payment === 'DM') {
								if ($taken_flag === true && empty($taken_code) === false) {
									$ws_code = $taken_code;
								}

								if (empty($arr_cl[$cont]['cl-account-1']) === false) {
									if (empty($ws_code) === false) {
										$ws = new WSBaneco('CC');

										$params = array(
											'NroCuenta' => $arr_cl[$cont]['cl-account-1'],
											'codigoCliente' => $ws_code
										);

										if ($ws->ws_connect($params) === true) {
											// $account = true;
										} else {
											$account = false;
											$account_mess = $ws->message;
										}
									} else {
										Account01:
										$account = false;
										$account_mess = 'El Numero de cuenta es invalido.';
									}
								} else {
									goto Account01;
								}

								if (empty($arr_cl[$cont]['cl-account-2']) === false) {
									if (empty($ws_code) === false) {
										$ws = new WSBaneco('CC');

										$params = array(
											'NroCuenta' => $arr_cl[$cont]['cl-account-2'],
											'codigoCliente' => $ws_code
										);

										if ($ws->ws_connect($params) === true) {
											// $account = true;
										} else {
											$account = false;
											$account_mess = $ws->message;
										}
									} else {
										$account = false;
										$account_mess = 'El Numero de cuenta es invalido';
									}
								}
							}
						}
						
						// Beneficiarios
						$arr_cl[$cont]['percentage'] = 0;
						$arr_cl[$cont]['per_mess'] = '';

						for ($i = 1; $i <= $beneficiary; $i++) {
							$arr_cl[$cont]['cl-bn-active-' . $i] = false;

							if (isset($_POST['dsp-active-' . $cont . '-' . $i])) {
								$arr_cl[$cont]['cl-bn-active-' . $i] = true;

								if (isset($_POST['dsp-' . $cont . '-idb-' . $i])) {
									$arr_cl[$cont]['cl-bn-idb-' . $i] = 
										$link->real_escape_string(trim(
											base64_decode($_POST['dsp-' . $cont . '-idb-' . $i])));
								} else {
									$arr_cl[$cont]['cl-bn-idb-' . $i] = uniqid('@S#1$2013' . $cont, true);
								}

								$arr_cl[$cont]['cl-bn-name-' . $i] = 
									$link->real_escape_string(trim($_POST['dsp-' . $cont . '-name-' . $i]));
								$arr_cl[$cont]['cl-bn-patern-' . $i] = 
									$link->real_escape_string(trim($_POST['dsp-' . $cont . '-patern-' . $i]));
								$arr_cl[$cont]['cl-bn-matern-' . $i] = 
									$link->real_escape_string(trim($_POST['dsp-' . $cont . '-matern-' . $i]));
								$arr_cl[$cont]['cl-bn-relation-' . $i] = 
									$link->real_escape_string(trim($_POST['dsp-' . $cont . '-relation-' . $i]));
								$arr_cl[$cont]['cl-bn-dni-' . $i] = 
									$link->real_escape_string(trim($_POST['dsp-' . $cont . '-dni-' . $i]));
								$arr_cl[$cont]['cl-bn-por-' . $i] = 
									$link->real_escape_string(trim($_POST['dsp-' . $cont . '-por-' . $i]));
								$arr_cl[$cont]['cl-bn-cov-' . $i] = 'BN';

								$arr_cl[$cont]['percentage'] += (float)$arr_cl[$cont]['cl-bn-por-' . $i];
							} elseif (isset($_POST['dsp-' . $cont . '-idb-' . $i])) {
								$arr_cl[$cont]['cl-bn-idb-' . $i] = 
									$link->real_escape_string(trim(
										base64_decode($_POST['dsp-' . $cont . '-idb-' . $i])));
							}
						}

						if ($arr_cl[$cont]['percentage'] != 100) {
							$percentage = false;
							$per_mess .= 'La suma de porcentajes de 
								Beneficiarios del Titular ' . $cont . ' debe ser del 100% <br>';
						}

						if (isset($_POST['dd-'.$cont.'-idd'])) {
							$arr_cl[$cont]['cl-d-idd'] = 
								$link->real_escape_string(trim(base64_decode($_POST['dd-'.$cont.'-idd'])));
						} else {
							$arr_cl[$cont]['cl-d-idd'] = uniqid('@S#1$2013' . $cont, true);
						}

						$arr_cl[$cont]['sql'] = '';
						if (isset($_POST['dd-'.$cont.'-id_detalle'])) {
							$id_detalle = base64_decode($_POST['dd-' . $cont . '-id_detalle']);
							$arr_cl[$cont]['sql'] = 
								get_result_question($link, $id_detalle, $arr_cl[$cont]['cl-d-idd']);
						}
					}
					
					$prefix = array();
					$arrPrefix = 'null';

					$prefix[0] = 'VI';
					
					$sqlC = '';
					$sqlCL = '';
					$sqlD = '';
					$sqlBN = '';
					$sqlRs = '';
					$ID = '';
					
					if ($percentage === true && $account === true && $rank === true) {
					if ($sw === 1){
						if (($row_user = $link->get_data_user($_SESSION['idUser'], $_SESSION['idEF'])) !== false) {
							$ws_usuario = $row_user['u_usuario'];
						}

						$idc = '"' . $link->real_escape_string(trim(base64_decode($_POST['de-idc']))) . '"';
						
						$ID = uniqid('@S#1$2013',true);
						$record = $link->getRegistrationNumber($_SESSION['idEF'], 'VI', 1, $prefix[0]);
						$dcr_no_policy += $record;

						if ($link->check_no_policy('VI', $dcr_no_policy) === false) {
							$policy_mess = 'El Número de Póliza ya fue registrado. <br>';
							goto NoPoliza;
						}
						
						$sqlC = 'insert into s_vi_em_cabecera 
						(id_emision, no_emision, no_poliza, id_ef, id_cotizacion, prefijo, prefix,
							pre_impreso, no_preprinted, id_rango, cobertura, id_usuario, factura_nombre, 
							factura_nit, fecha_creacion, anulado, and_usuario, fecha_anulado, 
							motivo_anulado, emitir, fecha_emision, id_compania, id_poliza, id_plan, 
							forma_pago, periodo, prima, no_copia, leido, id_certificado) 
						values 
						("' . $ID . '", "' . $record . '", "' . $dcr_no_policy . '", 
							"' . base64_decode($_SESSION['idEF']) . '", ' . $idc . ', 
							"' . $prefix[0] . '", ' . $arrPrefix . ', "' . $dcr_print . '", 
							"' . $dcr_policy_pp . '", ' . $dcr_rango . ', ' . $cont . ', 
							"' . base64_decode($_SESSION['idUser']) . '", "' . $bill_name . '", 
							"' . $bill_nit . '", curdate(), false, 
							"' . base64_decode($_SESSION['idUser']) . '", "", "", false, "", 
							"' . $dcr_cia . '", "' . $dcr_policy . '", "' . $dcr_plan . '", 
							"' . $dcr_payment . '", "' . $dcr_period . '", "' . $dcr_prima . '", 
							0, false, 1 ) ;';
						
						$sqlCL = '';
						
						$sqlD = 'INSERT INTO s_vi_em_detalle 
						(id_detalle, id_emision, id_cliente, tipo_pago, 
							cargo, cargo_respuesta, cargo_periodo, numero_cuenta, 
							moneda, tipo_cuenta, fecha_cancelacion, voucher, 
							referido_por, cod_agenda, tomador_nombre, tomador_ci_nit, 
							cuenta_1, cuenta_2, tarjeta, porcentaje_credito, titular)
						VALUES ';
						
						$sqlRs = 'insert into s_vi_em_respuesta 
						(id_respuesta, id_detalle, respuesta, observacion)
						values ';

						$sqlBN = 'INSERT INTO s_vi_beneficiario 
							(id_beneficiario, id_detalle, cobertura, paterno, materno, 
								nombre, ci, parentesco, porcentaje_credito)
							VALUES ';
						
						$k = 0;
						while($k < $nCl){
							$k += 1;
							
							$flagCl = false;
							$sqlSCl = 'select 
								scl.id_cliente as idCl, 
								scl.ci as cl_ci, 
								scl.extension as cl_extension
							from
								s_cliente as scl
									inner join 
								s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
							where
								scl.ci = "' . $arr_cl[$k]['cl-doc-id'] . '" 
									and scl.extension = ' . $arr_cl[$k]['cl-ext'] . ' 
									and scl.tipo = 0 
									and sef.id_ef = "' . base64_decode($_SESSION['idEF']) . '"
							;';
							
							if (($rsSCl = $link->query($sqlSCl,MYSQLI_STORE_RESULT))) {
								if ($rsSCl->num_rows === 1){
									$rowSCl = $rsSCl->fetch_array(MYSQLI_ASSOC);
									$rsSCl->free();

									$sqlCL .= 'update s_cliente 
									set 
										codigo_be = "' . $arr_cl[$k]['cl-code'] . '",
										paterno = "' . $arr_cl[$k]['cl-patern'] . '", 
										materno = "' . $arr_cl[$k]['cl-matern'] . '", 
										nombre = "' . $arr_cl[$k]['cl-name'] . '", 
										ap_casada = "' . $arr_cl[$k]['cl-married'] . '", 
										fecha_nacimiento = "' . $arr_cl[$k]['cl-date'] . '", 
										lugar_nacimiento = "' . $arr_cl[$k]['cl-place-birth'] . '", 
										extension = "' . $arr_cl[$k]['cl-ext'] . '", 
										complemento = "' . $arr_cl[$k]['cl-comp'] . '", 
										tipo_documento = "CI", 
										avenida = "' . $arr_cl[$k]['cl-avc'] . '", 
										direccion = "' . $arr_cl[$k]['cl-address-home'] . '", 
										no_domicilio = "' . $arr_cl[$k]['cl-nhome'] . '", 
										direccion_laboral = "' . $arr_cl[$k]['cl-work'] . '", 
										id_ocupacion = "' . $arr_cl[$k]['cl-occupation'] . '", 
										desc_ocupacion = "' . $arr_cl[$k]['cl-desc-occ'] . '", 
										telefono_domicilio = "' . $arr_cl[$k]['cl-phone-1'] . '", 
										telefono_oficina = "' . $arr_cl[$k]['cl-phone-office'] . '", 
										telefono_celular = "' . $arr_cl[$k]['cl-phone-2'] . '", 
										email = "' . $arr_cl[$k]['cl-email'] . '", 
										peso = "' . $arr_cl[$k]['cl-weight'] . '", 
										estatura = "' . $arr_cl[$k]['cl-height'] . '",
										genero = "' . $arr_cl[$k]['cl-gender'] . '", 
										edad = TIMESTAMPDIFF(YEAR, "' . $arr_cl[$k]['cl-date'] . '", curdate())
									where id_cliente = "' . $rowSCl['idCl'] . '" ;';
									
									$arr_cl[$k]['cl-id'] = $rowSCl['idCl'];
									$flagCl = true;
								}
							}
							
							if ($flagCl === false){
								$sqlCL .= 'INSERT INTO s_cliente 
								(id_cliente, id_ef, codigo_be, tipo, razon_social, paterno, 
									materno, nombre, ap_casada, fecha_nacimiento, 
									lugar_nacimiento, ci, extension, complemento, 
									tipo_documento, estado_civil, ci_archivo, 
									lugar_residencia, localidad, avenida, direccion, 
									no_domicilio, direccion_laboral, pais, 
									id_ocupacion, desc_ocupacion, telefono_domicilio, 
									telefono_oficina, telefono_celular, 
									email, peso, estatura, genero, edad) 
								VALUES (
									"' . $arr_cl[$k]['cl-id'] . '", 
									"' . base64_decode($_SESSION['idEF']) . '", 
									"' . $arr_cl[$k]['cl-code'] . '",
									"' . $arr_cl[$k]['cl-type'] . '", "", 
									"' . $arr_cl[$k]['cl-patern'] . '", 
									"' . $arr_cl[$k]['cl-matern'] . '", 
									"' . $arr_cl[$k]['cl-name'] . '", 
									"' . $arr_cl[$k]['cl-married'] . '", 
									"' . $arr_cl[$k]['cl-date'] . '", 
									"' . $arr_cl[$k]['cl-place-birth'] . '", 
									"' . $arr_cl[$k]['cl-doc-id'] . '", 
									"' . $arr_cl[$k]['cl-ext'] . '", 
									"' . $arr_cl[$k]['cl-comp'] . '", 
									"CI", "", "", null, "", 
									"' . $arr_cl[$k]['cl-avc'] . '", 
									"' . $arr_cl[$k]['cl-address-home'] . '", 
									"' . $arr_cl[$k]['cl-nhome'] . '", 
									"' . $arr_cl[$k]['cl-work'] . '", "", 
									"' . $arr_cl[$k]['cl-occupation'] . '", 
									"' . $arr_cl[$k]['cl-desc-occ'] . '", 
									"' . $arr_cl[$k]['cl-phone-1'] . '", 
									"' . $arr_cl[$k]['cl-phone-office'] . '", 
									"' . $arr_cl[$k]['cl-phone-2'] . '", 
									"' . $arr_cl[$k]['cl-email'] . '", 
									"' . $arr_cl[$k]['cl-weight'] . '",
									"' . $arr_cl[$k]['cl-height'] . '", 
									"' . $arr_cl[$k]['cl-gender'] . '", 
									TIMESTAMPDIFF(YEAR, "' . $arr_cl[$k]['cl-date'] . '", curdate())) ;';
							}
							
							$sqlD .= '(
							"' . $arr_cl[$k]['cl-d-idd'] . '", 
							"' . $ID . '", "' . $arr_cl[$k]['cl-id'] . '",  
							"", 0, "", "", "", "", "", "", "", "", "", 
							"' . $taken_name . '", 
							"' . $taken_nit . '", 
							"' . $arr_cl[$k]['cl-account-1'] . '", 
							"' . $arr_cl[$k]['cl-account-2'] . '", 
							"' . $arr_cl[$k]['cl-card'] . '", 
							"' . $arr_cl[$k]['cl-share'] . '", 
							"' . $arr_cl[$k]['cl-titular'] . '" ),';
							
							$sqlRs .= $arr_cl[$k]['sql'] . ',';
							
							for ($i = 1; $i <= $beneficiary ; $i++) {
								if ($arr_cl[$k]['cl-bn-active-' . $i]
									&& !empty($arr_cl[$k]['cl-bn-name-' . $i])
									&& !empty($arr_cl[$k]['cl-bn-patern-' . $i])
									&& !empty($arr_cl[$k]['cl-bn-relation-'.$i])
									&& !empty($arr_cl[$k]['cl-bn-por-' . $i])) {
									
									$sqlBN .= '(
									"' . $arr_cl[$k]['cl-bn-idb-' . $i] . '", 
									"' . $arr_cl[$k]['cl-d-idd'] . '", 
									"' . $arr_cl[$k]['cl-bn-cov-' . $i] . '", 
									"' . $arr_cl[$k]['cl-bn-patern-' . $i] . '", 
									"' . $arr_cl[$k]['cl-bn-matern-' . $i] . '", 
									"' . $arr_cl[$k]['cl-bn-name-' . $i] . '", 
									"' . $arr_cl[$k]['cl-bn-dni-' . $i] .'", 
									"' . $arr_cl[$k]['cl-bn-relation-' . $i] . '", 
									"' . $arr_cl[$k]['cl-bn-por-' . $i] . '"),';
								}
							}
						}
						//echo $sqlBN;
						$sqlCL = trim(trim($sqlCL), ',');
						$sqlD = trim(trim($sqlD), ',') . ';';
						$sqlRs = trim(trim($sqlRs), ',') . ';';
						$sqlBN = trim(trim($sqlBN), ',') . ';';
						
						if ($link->query($sqlC) === true) {
							if ($link->multi_query($sqlCL) === true) {
								$swCl = false;
								do{
									if ($link->errno !== 0)
										$swCl = true;
								}while($link->more_results() && $link->next_result());
								
								if ($swCl === false){
									if ($link->query($sqlD) === true){
										if ($link->query($sqlRs) === true) {
											if ($link->query($sqlBN) === true){
												$ws_mess = '';
												if ($ws_cnx === true 
														&& ($dcr_payment === 'CO' || $dcr_payment === 'DM')) {
													$ws = new WSBaneco('RC');
													$params = array(
														'nroPoliza' => $dcr_no_policy,
											            'nombre' => $ws_client,
											            'importe' => $dcr_prima,
											            'moneda' => 1,
											            'usuario' => $ws_usuario,
											            'tipoPoliza' => 'VI',
											            'nit' => $bill_nit,
											            'nombreFactura' => $bill_name
													);
													
													if ($ws->ws_connect($params) === true) {
														goto Issue;
													} else {
														$ws_mess = '<br>' . $ws->message;
													}
												} else {
													Issue:
													$arrDE[0] = 1;
												}

												$arrDE[1] = 'vi-quote.php?ms=' . $ms 
												. '&page=' . $page . '&pr=' . $pr 
												. '&ide=' . base64_encode($ID) 
												. '&flag=' . md5('i-read') . '&cia=' 
												. base64_encode($dcr_cia);
												$arrDE[2] = 'La Póliza fue registrada con exito' . $ws_mess;
											}else {
												$arrDE[2] = 'No se pudo registrar a los Beneficiarios';
											}
										} else {
											$arrDE[2] = 'No se pudo registrar las Respuestas';
										}
									}else {
										$arrDE[2] = 'No se pudo registrar el Detalle';
									}
								}else {
									$arrDE[2] = 'No se pudo registrar a los Titulares';
								}
							}else {
								$arrDE[2] = 'No se pudo registrar a los Titulares .';
							}
						}else {
							$arrDE[2] = 'La Póliza no pudo ser registrada';
						}
					} elseif ($sw === 3){
						$ide = $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));
						
						$sqlC = 'update s_vi_em_cabecera 
						set 
							id_poliza = "' . $dcr_policy . '",
							pre_impreso = "' . $dcr_print . '",
							no_preprinted = "' . $dcr_policy_pp . '",
							no_copia = 0, 
							leido = false,
							id_certificado = 1,
							id_plan = "' . $dcr_plan . '", 
							forma_pago = "' . $dcr_payment . '", 
							periodo = "' . $dcr_period . '", 
							prima = "' . $dcr_prima . '"
						where id_emision = "' . $ide . '"
						;';
						
						$sw_UCl = false;
						$sw_UB = false;
						$sw_Dt = false;
						
						if ($link->query($sqlC) === true){
							$k = 0;
							while($k < $nCl){
								$k += 1;

								$sqlCL = 'update s_cliente 
								set 
									codigo_be = "' . $arr_cl[$k]['cl-code'] . '",
									paterno = "' . $arr_cl[$k]['cl-patern'] . '", 
									materno = "' . $arr_cl[$k]['cl-matern'] . '", 
									nombre = "' . $arr_cl[$k]['cl-name'] . '", 
									ap_casada = "' . $arr_cl[$k]['cl-married'] . '", 
									fecha_nacimiento = "' . $arr_cl[$k]['cl-date'] . '", 
									lugar_nacimiento = "' . $arr_cl[$k]['cl-place-birth'] . '", 
									extension = "' . $arr_cl[$k]['cl-ext'] . '", 
									complemento = "' . $arr_cl[$k]['cl-comp'] . '", 
									tipo_documento = "CI", 
									avenida = "' . $arr_cl[$k]['cl-avc'] . '", 
									direccion = "' . $arr_cl[$k]['cl-address-home'] . '", 
									no_domicilio = "' . $arr_cl[$k]['cl-nhome'] . '", 
									direccion_laboral = "' . $arr_cl[$k]['cl-work'] . '", 
									id_ocupacion = "' . $arr_cl[$k]['cl-occupation'] . '", 
									desc_ocupacion = "' . $arr_cl[$k]['cl-desc-occ'] . '", 
									telefono_domicilio = "' . $arr_cl[$k]['cl-phone-1'] . '", 
									telefono_oficina = "' . $arr_cl[$k]['cl-phone-office'] . '", 
									telefono_celular = "' . $arr_cl[$k]['cl-phone-2'] . '", 
									email = "' . $arr_cl[$k]['cl-email'] . '", 
									peso = "' . $arr_cl[$k]['cl-weight'] . '", 
									estatura = "' . $arr_cl[$k]['cl-height'] . '",
									genero = "' . $arr_cl[$k]['cl-gender'] . '", 
									edad = TIMESTAMPDIFF(YEAR, "' . $arr_cl[$k]['cl-date'] . '", curdate())
								where id_cliente = "' . $arr_cl[$k]['cl-id'] . '" ;';
								
								if ($link->query($sqlCL) === true) {
									$sw_UCl = true;
								} else {
									$sw_UCl = false;
								}

								$sqlDt = 'update 
									s_vi_em_detalle
								set 
									tomador_nombre = "' . $taken_name . '",
									tomador_ci_nit = "' . $taken_nit . '",
									cuenta_1 = "' . $arr_cl[$k]['cl-account-1'] . '", 
									cuenta_2 = "' . $arr_cl[$k]['cl-account-2'] . '", 
									tarjeta = "' . $arr_cl[$k]['cl-card'] . '", 
									porcentaje_credito = "' . $arr_cl[$k]['cl-share'] . '", 
									titular = "' . $arr_cl[$k]['cl-titular'] . '"
								where 
									id_detalle = "' . $arr_cl[$k]['cl-d-idd'] . '" ;';
								
								if ($link->query($sqlDt) === true) {
									$sw_Dt = true;
								} else {
									$sw_Dt = false;
								}
								
								for ($i = 1; $i <= $beneficiary; $i++) {
									if ($arr_cl[$k]['cl-bn-active-' . $i]) {
										if (empty($arr_cl[$k]['cl-bn-idb-' . $i])
											&& !empty($arr_cl[$k]['cl-bn-name-' . $i]) 
											&& !empty($arr_cl[$k]['cl-bn-patern-' . $i])
											&& !empty($arr_cl[$k]['cl-bn-relation-'.$i]) 
											&& !empty($arr_cl[$k]['cl-bn-por-' . $i])) {

											$arr_cl[$cont]['cl-bn-idb-' . $i] = 
												uniqid('@S#1$2013' . $i, true);

											$sqlBN = 'INSERT INTO s_vi_beneficiario 
											(id_beneficiario, id_detalle, cobertura, paterno, materno, 
												nombre, ci, parentesco, porcentaje_credito)
											VALUES 
											("' . $arr_cl[$k]['cl-bn-idb-' . $i] . '", 
											"' . $arr_cl[$k]['cl-d-idd'] . '", 
											"' . $arr_cl[$k]['cl-bn-cov-' . $i] . '", 
											"' . $arr_cl[$k]['cl-bn-patern-' . $i] . '", 
											"' . $arr_cl[$k]['cl-bn-matern-' . $i] . '", 
											"' . $arr_cl[$k]['cl-bn-name-' . $i] . '", 
											"' . $arr_cl[$k]['cl-bn-dni-' . $i] .'", 
											"' . $arr_cl[$k]['cl-bn-relation-' . $i] . '", 
											"' . $arr_cl[$k]['cl-bn-por-' . $i] . '");';
										} else {
											$sqlBN = 'update 
												s_vi_beneficiario 
											set
												cobertura = "' . $arr_cl[$k]['cl-bn-cov-' . $i] . '", 
												paterno = "' . $arr_cl[$k]['cl-bn-patern-' . $i] . '", 
												materno = "' . $arr_cl[$k]['cl-bn-matern-' . $i] . '", 
												nombre = "' . $arr_cl[$k]['cl-bn-name-' . $i] . '", 
												ci = "' . $arr_cl[$k]['cl-bn-dni-' . $i] .'", 
												parentesco = "' . $arr_cl[$k]['cl-bn-relation-' . $i] . '", 
												porcentaje_credito = "' . $arr_cl[$k]['cl-bn-por-' . $i] . '"
											where 
												id_beneficiario = "' . $arr_cl[$k]['cl-bn-idb-' . $i] . '" 
													and cobertura = "BN" ;';
										}
										
										goto Bn_sql;
									} elseif (!empty($arr_cl[$k]['cl-bn-idb-' . $i])) {
										$sqlBN = 'delete from s_vi_beneficiario
										where
											id_beneficiario = "' . $arr_cl[$k]['cl-bn-idb-' . $i] . '"
												and cobertura = "BN" ;';

										Bn_sql:
										if ($link->query($sqlBN) === true) {
											$sw_UB = true;
										} else {
											$sw_UB = false;
										}
									}
								}
							}
							
							if ($sw_UCl === true){
								if ($sw_Dt === true){
									if ($sw_UB === true){
										$arrDE[0] = 1;
										$arrDE[1] = 'vi-quote.php?ms=' . $ms 
											. '&page=' . $page . '&pr=' . $pr 
											. '&ide=' . base64_encode($ide) 
											. '&flag=' . md5('i-read') 
											. '&cia=' . base64_encode($dcr_cia) 
											. $target_url;
										$arrDE[2] = 'La Póliza fue actualizada correctamente !';
									}else {
										$arrDE[2] = 'No se pudo actualizar los Beneficiarios';
									}
								}else {
									$arrDE[2] = 'No se pudo actualizar las Observaciones de las respuestas';
								}
							}else {
								$arrDE[2] = 'No se pudo actualizar los Titulares';
							}
						}else {
							$arrDE[2] = 'La Póliza no pudo ser actualizada';
						}
					}
					} else {
						NoPoliza:
						$arrDE[2] = $policy_mess . $per_mess . '<br>' . utf8_encode($account_mess) . 
							'<br>' . $rank_mess;
					}
				}else{
					$arrDE[2] = 'No existen Titulares';
				}
			}else{
				$arrDE[2] = 'No se puede guardar la Póliza';
			}
			echo json_encode($arrDE);
		}else{
			$arrDE[2] = 'No se puede registrar la Póliza';
			echo json_encode($arrDE);
		}
	}else{
		$arrDE[2] = 'La Póliza no puede ser registrada';
		echo json_encode($arrDE);
	}
}else{
	echo json_encode($arrDE);
}

function get_result_question($link, $id_detalle, $idd)
{
	$sql = 'select 
		sar.id_respuesta, sar.respuesta, sar.observacion
	from 
		s_vi_cot_detalle as sdd
			inner join
		s_vi_cot_respuesta as sar ON (sar.id_detalle = sdd.id_detalle)
	where
		sdd.id_detalle = "' . $id_detalle . '"
	;';

	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();

			$sqlRs = '("' . uniqid('@S#1$2014', true) . '", 
				"' . $idd . '", 
				"' . $link->real_escape_string($row['respuesta']) . '", 
				"' . $row['observacion'] . '")';

			return $sqlRs;
		}
	}

	return '';
}
?>