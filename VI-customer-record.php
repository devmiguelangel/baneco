<?php
require('sibas-db.class.php');
require('session.class.php');

$session = new Session();
$session->getSessionCookie();

$arrBS = array(0 => 0, 1 => 'R', 2 => '');

if (isset($_POST['dc-token']) 
	&& isset($_POST['dc-idc']) 
	&& isset($_POST['ms']) 
	&& isset($_POST['page']) 
	&& isset($_POST['pr']) 
	&& isset($_POST['id-ef'])) {
	$swEmpty = FALSE;
	
	if ($swEmpty === FALSE && $_POST['pr'] === base64_encode('VI|02')) {
		$link = new SibasDB();
		
		$idc = $link->real_escape_string(trim(base64_decode($_POST['dc-idc'])));
		$idef = $link->real_escape_string(trim(base64_decode($_POST['id-ef'])));
		
		$idClient = 0;
		$idd = 0;
		$flag = FALSE;
		if (isset($_POST['dc-idCl']) && isset($_POST['dc-idd'])) {
			$flag = TRUE;
			$idClient = $link->real_escape_string(trim(base64_decode($_POST['dc-idCl'])));
			$idd = $link->real_escape_string(trim(base64_decode($_POST['dc-idd'])));
		}
		
		$dc_code = $link->real_escape_string(trim(base64_decode($_POST['dc-code'])));
		$dc_name = $link->real_escape_string(trim($_POST['dc-name']));
		$dc_lnpatern = $link->real_escape_string(trim($_POST['dc-ln-patern']));
		$dc_lnmatern = $link->real_escape_string(trim($_POST['dc-ln-matern']));
		$dc_lnmarried = $link->real_escape_string(trim($_POST['dc-ln-married']));
		$dc_doc_id = $link->real_escape_string(trim($_POST['dc-doc-id']));
		$dc_comp = $link->real_escape_string(trim($_POST['dc-comp']));
		$dc_ext = $link->real_escape_string(trim($_POST['dc-ext']));
		$dc_birth = $link->real_escape_string(trim($_POST['dc-date-birth']));
		$dc_place_birth = $link->real_escape_string(trim($_POST['dc-place-birth']));
		$dc_address = $link->real_escape_string(trim($_POST['dc-address']));
		$dc_occupation = $link->real_escape_string(trim(base64_decode($_POST['dc-occupation'])));
		if (empty($dc_occupation) === true) {
			$dc_occupation = 'null';
		} else {
			$dc_occupation = '"' . $dc_occupation . '"';
		}
		$dc_phone_1 = $link->real_escape_string(trim($_POST['dc-phone-1']));
		$dc_desc_occ = $link->real_escape_string(trim($_POST['dc-desc-occ']));
		$dc_phone_2 = $link->real_escape_string(trim($_POST['dc-phone-2']));
		$dc_email = $link->real_escape_string(trim($_POST['dc-email']));
		$dc_phone_office = $link->real_escape_string(trim($_POST['dc-phone-office']));
		$dc_gender = $link->real_escape_string(trim($_POST['dc-gender']));
		if ($dc_gender === 'M' || empty($dc_gender) === true) {
			$dc_lnmarried = '';
		}

		$dc_weight = $link->real_escape_string(trim($_POST['dc-weight']));
		$dc_height = $link->real_escape_string(trim($_POST['dc-height']));
		$dc_place_work = $link->real_escape_string(trim($_POST['dc-work']));
		$dc_plan = $link->real_escape_string(trim($_POST['dc-plan']));
		
		$dc_payment = '';
		$dc_prima = 0;
		$plan = false;

		if (($plan = $link->get_plan($_SESSION['idEF'], 'VI', $dc_plan)) !== false) {
			$dc_prima = $plan['prima_anual'];
		}
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));

		if ($link->client_plan('VI', $dc_doc_id, $dc_ext, $plan) === true) {
			$sqlAge = 'select 
				count(ssh.id_home) as token,
				ssh.edad_max,
				ssh.edad_min,
				(TIMESTAMPDIFF(year,
					"'.$dc_birth.'",
					curdate()) between ssh.edad_min and ssh.edad_max) as flag
			from
				s_sgc_home as ssh
					inner join s_entidad_financiera as sef ON (sef.id_ef = ssh.id_ef)
			where
				ssh.producto = "VI"
					and sef.id_ef = "'.$idef.'"
					and sef.activado = true
			;';
			
			$rsAge = $link->query($sqlAge,MYSQLI_STORE_RESULT);
			$rowAge = $rsAge->fetch_array(MYSQLI_ASSOC);
			$rsAge->free();
			
			if ((int)$rowAge['flag'] === 1) {
				$sql = '';
				if ($flag === TRUE) {
					$sql = 'UPDATE s_vi_cot_cliente as sc
						INNER JOIN 
					s_vi_cot_detalle as scd ON (scd.id_cliente = sc.id_cliente)
					SET sc.codigo_be = "' . $dc_code . '",
						sc.razon_social = "", 
						sc.paterno = "' . $dc_lnpatern . '", 
						sc.materno = "' . $dc_lnmatern . '", 
						sc.nombre = "' . $dc_name . '", 
						sc.ap_casada = "' . $dc_lnmarried . '", 
						sc.ci = "' . $dc_doc_id . '", 
						sc.complemento = "' . $dc_comp . '", 
						sc.extension = ' . $dc_ext . ', 
						sc.genero = "' . $dc_gender . '", 
						sc.fecha_nacimiento = "' . $dc_birth . '", 
						sc.edad = TIMESTAMPDIFF(YEAR, "' . $dc_birth . '", curdate()),
						sc.lugar_nacimiento = "' . $dc_place_birth . '", 
						sc.lugar_trabajo = "' . $dc_place_work . '",
						sc.direccion = "' . $dc_address . '", 
						sc.telefono_domicilio = "' . $dc_phone_1 . '",
						sc.telefono_celular = "' . $dc_phone_2 . '",  
						sc.telefono_oficina = "' . $dc_phone_office . '", 
						sc.email = "' . $dc_email . '", 
						sc.id_ocupacion = ' . $dc_occupation . ', 
						sc.desc_ocupacion = "' . $dc_desc_occ . '", 
						sc.peso = "' . $dc_weight . '", 
						sc.estatura = "' . $dc_height . '"
					WHERE sc.id_cliente = "'.$idClient.'"
						and scd.id_detalle = "' . $idd . '" ;';
					
					if ($link->query($sql) === TRUE) {
						goto Plan;
					} else {
						$arrBS[2] = 'No se pudo actualizar los datos';
					}
				} else {
					$DC = $link->number_clients_VI($idc, $idef, FALSE);
					$vc = $link->verify_customer($dc_doc_id, $dc_ext, $idef, 'VI');
					
					$idd = uniqid('@S#1$2013',true);
					
					if ($vc[0] === TRUE) {
						$idClient = $vc[1];
						
						$sql = 'UPDATE s_vi_cot_cliente 
						SET codigo_be = "' . $dc_code . '",
							razon_social = "", 
							paterno = "' . $dc_lnpatern . '", 
							materno = "' . $dc_lnmatern . '", 
							nombre = "' . $dc_name . '", 
							ap_casada = "' . $dc_lnmarried . '", 
							fecha_nacimiento = "' . $dc_birth . '", 
							lugar_nacimiento = "' . $dc_place_birth . '", 
							ci = "' . $dc_doc_id . '", 
							extension = "' . $dc_ext . '", 
							complemento = "' . $dc_comp . '", 
							tipo_documento = "CI", estado_civil = "", 
							lugar_residencia = null, localidad = "", 
							direccion = "' . $dc_address . '", 
							lugar_trabajo = "' . $dc_place_work  . '", 
							pais = "", id_ocupacion = ' . $dc_occupation . ', 
							desc_ocupacion = "' . $dc_desc_occ . '", 
							telefono_domicilio = "' . $dc_phone_1 . '", 
							telefono_oficina = "' . $dc_phone_office . '", 
							telefono_celular = "' . $dc_phone_2 . '", 
							email = "' . $dc_email . '", 
							genero = "' . $dc_gender . '", 
							edad = TIMESTAMPDIFF(YEAR, "'.$dc_birth.'", curdate()), 
							peso = "' . $dc_weight . '",
							estatura = "' . $dc_height . '" 
						WHERE 
							id_cliente = "' . $idClient . '";';

					} else {
						$idClient = uniqid('@S#1$2013', true);

						$sql = 'INSERT INTO s_vi_cot_cliente 
						(id_cliente, id_ef, codigo_be, tipo, razon_social, paterno, materno, 
							nombre, ap_casada, fecha_nacimiento, lugar_nacimiento, 
							ci, extension, complemento, tipo_documento, estado_civil, 
							lugar_residencia, localidad, direccion, lugar_trabajo, 
							pais, id_ocupacion, desc_ocupacion, telefono_domicilio, 
							telefono_oficina, telefono_celular, email, peso, estatura, 
							genero, edad) 
							VALUES
						("' . $idClient . '", "' . $idef . '", "' . $dc_code . '", 0, "", 
							"' . $dc_lnpatern . '", "' . $dc_lnmatern . '", "' . $dc_name . '", 
							"' . $dc_lnmarried . '", "' . $dc_birth . '", "' . $dc_place_birth . '", 
							"' . $dc_doc_id . '", "' . $dc_ext . '", "' . $dc_comp . '", "CI", "", 
							null, "", "' . $dc_address . '", "' . $dc_place_work . '", "", 
							' . $dc_occupation . ', "' . $dc_desc_occ . '", "' . $dc_phone_1 . '", 
							"' . $dc_phone_office . '", "' . $dc_phone_2 . '", "' . $dc_email . '", 
							"' . $dc_weight . '", "' . $dc_height . '", "' . $dc_gender . '", 
							TIMESTAMPDIFF(YEAR, "' . $dc_birth . '", curdate()));' ;
					}
					
					if ($link->query($sql) === TRUE) {
						$sqlDet = 'INSERT INTO s_vi_cot_detalle 
						(id_detalle, id_cotizacion, id_cliente, forma_pago, 
							fecha_cancelacion, referido_por, numero_cuenta, moneda, 
							tipo_cuenta, cod_agenda, porcentaje_credito, titular)
						VALUES
						("' . $idd . '", "' . $idc . '", "' . $idClient . '", 
							"", curdate(), "", "", "", "", "", 100, "' . $DC . '") ;';
						
						if ($link->query($sqlDet) === TRUE) {
							Plan:
							$queryset = 'update s_vi_cot_cabecera
							set 
								id_plan = "' . $dc_plan . '", 
								prima = "' . $dc_prima . '"
							where 
								id_cotizacion = "' . $idc . '"
							;';

							if ($link->query($queryset) === true) {
								$arrBS[0] = 1;
								if ($flag === true || empty($dc_code) === false) {
									$arrBS[1] = 'vi-quote.php?ms=' . $ms . '&page=' . $page 
										. '&pr=' . $pr . '&idc=' . base64_encode($idc);
								}

								if ($flag === false) {
									$arrBS[2] = 'Cliente registrado con Exito';
								} else {
									$arrBS[2] = 'Los Datos se actualizaron correctamente';
								}
							} else {
								$arrBS[2] = 'No se pudo registrar el Plan';
							}
							
						} else {
							$arrBS[2] = 'No se pudo registrar el Detalle';
						}
					} else {
						$arrBS[2] = 'No se pudo registrar el Cliente';
					}
				}
			} else {
				$arrBS[2] = 'La Fecha de Nacimiento no esta en el rango permitido de Edades [ ' 
					. $rowAge['edad_min'] . ' - ' . $rowAge['edad_max'] . ' ]';
			}
		} else {
			$arrBS[2] = 'Imposible realizar cotización. 
				La suma total del valor asegurado para este cliente supera los 70000 Bs.';
		}
		
		echo json_encode($arrBS);
	} else {
		echo json_encode($arrBS);
	}
} else {
	echo json_encode($arrBS);
}
?>