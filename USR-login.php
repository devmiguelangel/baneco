<?php
require 'sibas-db.class.php';
require 'session.class.php';
require 'classes/ws_baneco.php';

$arrUSR = array(0 => 0, 1 => 'R', 2 => '');

if (isset($_POST['l-user']) && isset($_POST['l-pass'])) {
	$link = new SibasDB();
	$user = $link->real_escape_string(trim($_POST['l-user']));
	$pass = $link->real_escape_string(trim($_POST['l-pass']));
	
	$flag = false;
	$ms = '';
	$page = '';
	$ide = '';
	$csrf_token = false;
	$idef = '';

	if (isset($_POST['csrf_token'])) {
		$csrf_token = true;
		$idef = $link->real_escape_string(trim(base64_decode($_POST['csrf_token'])));
	}

	if (isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['ide'])) {
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$ide = $link->real_escape_string(trim($_POST['ide']));
		$flag = true;
	}

	$sqlUser = 'select 
		su.id_usuario,
		su.usuario,
		su.password,
		sut.id_tipo as tipo,
		sut.codigo as tipo_codigo,
		sef.id_ef,
		sef.nombre as ef_nombre,
		sef.activado as ef_activado,
		su.cambio_password as cw
	from
		s_usuario as su
			inner join
		s_usuario_tipo as sut ON (sut.id_tipo = su.id_tipo)
			inner join
		s_ef_usuario as seu ON (seu.id_usuario = su.id_usuario)
			inner join
		s_entidad_financiera as sef ON (sef.id_ef = seu.id_ef)
	where
		su.usuario = "' . $user . '"
			and su.activado = true
			and sef.activado = true
	order by sef.id_ef asc
	limit 0 , 1
	;';
	
	if (($rs = $link->query($sqlUser,MYSQLI_STORE_RESULT))) {
		if ($rs->num_rows === 1) {
			$rowUSR = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();
			
			$token = false;
			if ($rowUSR['tipo_codigo'] !== 'ROOT') {
				// $token = true;
				if ((boolean)$rowUSR['ef_activado'] === true) {
					$token = true;
				}
			}
			
			if ($token === true) {
				if (crypt($pass, $rowUSR['password']) === $rowUSR['password']) {
					UserLogin:

					$session = new Session();
					$session->start_session($rowUSR['id_usuario'], $rowUSR['id_ef']);
					
					$arrUSR[0] = 1;
					if ($flag === false) {
						$arrUSR[1] = 'index.php';
					} elseif ($rowUSR['tipo_codigo'] === 'FAC' || $rowUSR['tipo_codigo'] === 'IMP') {
						$arrUSR[1] = 'index.php?ms=' . $ms . '&page=' . $page . '&ide=' . $ide;
					} else {
						$arrUSR[1] = 'index.php';
					}
					
					if ((boolean)$rowUSR['cw'] === false && $csrf_token === false) {
						$arrUSR[1] = 'index.php?ms=' . md5('MS_COMP') 
							. '&page=' . md5('P_change_pass') 
							. '&user=' . base64_encode($rowUSR['id_usuario']) 
							. '&url=' . base64_encode($arrUSR[1])
							. '&c-p=' . md5('true');
					}
					
					$arrUSR[2] = 'Bienvenido';
				} else {
					$arrUSR[2] = 'La Contraseña es incorrecta';
					goto UserWS;
				}
			} else {
				$arrUSR[2] = 'Usted no puede Iniciar Sesión';
				goto UserWS;
			}
		} else {
			$arrUSR[2] = 'El Usuario no existe';
			goto UserWS;
		}
	} else {
		UserWS:
		if ($csrf_token === true) {
			$ws = new WSBaneco('U');

			if ($ws->ws_connect(array('usuario' => $user, 'clave' => $pass)) === true) {
				$data_ws = $ws->data;

				$agency = '';
				$depto = 'null';

				if (($dp = $link->getExtenssionCode(
						$link->depto_be[$data_ws['regional']])) !== false) {
					$depto = $dp['id_depto'];
				}

				if (($ag = $link->verify_agency(
						array('ag_id' => '', 'ag_code' => $data_ws['agencia']))) !== false) {
					$agency = '"' . $ag['id_agencia'] . '"';
				} else {
					$agency = uniqid('@S#1$2013', true);

					$queryset = 'insert into s_agencia
					(id_agencia, codigo, agencia, id_depto, id_ef)
					values
					("' . $agency . '", "' . $data_ws['agencia'] . '", 
						"' . $data_ws['nomagencia'] . '", ' . $depto . ', 
						"' . $idef . '")
					;';
					
					if ($link->query($queryset) === true) {
						$agency = '"' . $agency . '"';
					} else {
						$agency = '';
					}
				}

				$queryset = '';
				$token_user = false;

				if (($rowUSR = $link->getDataUser($data_ws['idusuario'])) !== false) {
					$queryset = 'update s_usuario
					set 
						usuario = "' . $data_ws['idusuario'] . '", 
						password = "' . crypt_blowfish_bycarluys($pass) . '", 
						nombre = "' . trim($data_ws['nombre']) . '", 
						email = "' . trim($data_ws['mail']) . '", 
						id_depto = ' . $depto . ', 
						activado = true, 
						cambio_password = true, 
						id_agencia = ' . $agency . '
					where 
						id_usuario = "' . $rowUSR['id_usuario'] . '"
					;';

					$token_user = true;
				} else {
					$id_user = uniqid('@S#1$2013', true);
					$type = 5;

					$queryset = 'insert into s_usuario 
					(id_usuario, usuario, password, nombre, email, id_tipo, id_depto, 
						cargo, activado, cambio_password, id_agencia, fono_agencia, 
						fecha_creacion, nivel) 
					values
					("' . $id_user . '", 
						"' . $data_ws['idusuario'] . '", 
						"' . crypt_blowfish_bycarluys($pass) . '", 
						"' . trim($data_ws['nombre']) . '", 
						"' . trim($data_ws['mail']) . '", "' . $type . '", 
						' . $depto . ', "", true, true, 
						' . $agency . ', "", curdate(), 0)
					;';

					$rowUSR['id_usuario'] = $id_user;
					$rowUSR['usuario'] = $data_ws['idusuario'];
					$rowUSR['id_ef'] = $idef;
					$rowUSR['cw'] = 1;
				}

				if ($link->query($queryset) === true) {
					$id_eu = uniqid('@S#1$2013', true);

					if ($token_user === false) {
						$queryset = 'insert into s_ef_usuario
						(id_eu, usuario, id_usuario, id_ef)
						values
						("' . $id_eu . '", "' . $rowUSR['usuario'] . '", 
							"' . $rowUSR['id_usuario'] . '", "' . $rowUSR['id_ef'] . '")
						;';

						if ($link->query($queryset) === true) {
							goto UserLogin;
						} else {
							$arrUSR[2] = 'Usted no puede Iniciar Sesión!';
						}
					} else {
						goto UserLogin;
					}
				} else {
					$arrUSR[2] = 'Usted no puede Iniciar Sesión';
				}
			} else {
				$arrUSR[2] = $ws->message;
			}
		}
	}
	
	$link->close();
	echo json_encode($arrUSR);
} else {
	$arrUSR[2] = 'Intente de nuevo';
	echo json_encode($arrUSR);
}

function crypt_blowfish_bycarluys($password, $digito = 7) {
	//	El salt para Blowfish debe ser escrito de la siguiente manera: 
	//	$2a$, $2x$ o $2y$ + 2 números de iteración entre 04 y 31 + 22 caracteres
	$set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$salt = sprintf('$2a$%02d$', $digito);
	
	for($i = 0; $i < 22; $i++) {
		$salt .= $set_salt[mt_rand(0, 63)];
	}
	
	return crypt($password, $salt);
}

//echo crypt_blowfish_bycarluys('aperez123');
?>