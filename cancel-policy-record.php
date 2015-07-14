<?php
require('sibas-db.class.php');
require('PHPMailer/class.phpmailer.php');

$arrTR = array(0 => 0, 1 => 'R', 2 => '');
if(isset($_GET['fp-ide']) && isset($_GET['idUser']) && isset($_GET['fp-obs']) && isset($_GET['pr'])){
	$link = new SibasDB();
	$ide = $link->real_escape_string(trim(base64_decode($_GET['fp-ide'])));
	$user = $link->real_escape_string(trim(base64_decode($_GET['idUser'])));
	$obs = $link->real_escape_string(trim($_GET['fp-obs']));
	$pr = strtoupper($link->real_escape_string(trim(base64_decode($_GET['pr']))));
	$pd = $link->real_escape_string(trim(base64_decode($_GET['pd'])));
	$prd = $link->real_escape_string(trim(base64_decode($_GET['prd'])));
	if(($pr === 'AP') && ($prd === 'Y')){
	  $text_devolucion = 'El monto a devolver por esta anulación es de Bs '.$pd.' que será emitido mediante cheque de la compañía de seguros.';	
	}elseif(($pr === 'AP') && ($prd === 'M')){
	  $text_devolucion = 'La finalizaci&oacute;n de la vigencia de esta p&oacute;liza ser&aacute; la fecha de su pr&oacute;xima fecha de pago.';	
	}
	
	$flag = true;
	$_TEXT = $obs;
	$patrones = array('@<script[^>]*?>.*?</script>@si',  	// Strip out javascript
			'@<colgroup[^>]*?>.*?</colgroup>@si',			// Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',				// Strip style tags properly
			'@<style[^>]*>.*</style>@siU',					// Strip style
			'@<![\s\S]*?--[ \t\n\r]*>@siU',					// Strip multi-line comments including CDATA,
			'@width:[^>].*;@siU',							// Strip width
			'@width="[^>].*"@siU',							// Strip width style
			'@height="[^>].*"@siU',							// Strip height
			'@class="[^>].*"@siU',							// Strip class
			'@border="[^>].*"@siU',							// Strip border
			'@font-family:[^>].*;@siU'						// Strip fonts
	);
	$sus = array('','','','','','width: 500px;','width="500"','','','','font-family: Helvetica, sans-serif, Arial;');
	$obs = preg_replace($patrones,$sus,$_TEXT);
	
	$obs = $text_devolucion.' '.$obs;
	
	$table = $tableCot = '';
	switch ($pr) {
		case 'DE':
			$table = 's_de_em_cabecera';
			$tableCot = 's_de_cot_cabecera';
			break;
		case 'AU':
			$table = 's_au_em_cabecera';
			$tableCot = 's_au_cot_cabecera';
			break;
		case 'TRD':
			$table = 's_trd_em_cabecera';
			$tableCot = 's_trd_cot_cabecera';
			break;
		case 'TRM':
			$table = 's_trm_em_cabecera';
			$tableCot = 's_trm_cot_cabecera';
			break;
		case 'AP':
			$table = 's_ap_em_cabecera';
			$tableCot = 's_ap_cot_cabecera';
			break;
		case 'VI':
			$table = 's_vi_em_cabecera';
			$tableCot = 's_vi_cot_cabecera';
			break;
	}
	
	$sql = 'update ' . $table . ' as tbl1
		left join
	' . $tableCot . ' as tbl2 ON (tbl2.id_cotizacion = tbl1.id_cotizacion)
	set tbl1.anulado = true, tbl1.and_usuario = "' . $user . '", 
		tbl1.motivo_anulado = "' . $obs . '", tbl1.fecha_anulado = curdate()
	where tbl1.id_emision = "' . $ide . '" 
	;';
	
	if($link->query($sql) === true){
		if(($pr === 'AP') && ($prd === 'Y')){
			$id_devolucion = date('U');
			$sql = 'insert into s_ap_devolucion (`id_devolucion`, `id_emision`, `fecha_creacion`, `monto`, `pagado`) 
					values('.$id_devolucion.', "'.$ide.'", curdate(), '.$pd.', 1)';
			if($link->query($sql) === true){ 			
				$flag = true;
			}else{
				$flag = false;
			}
		}
		if($flag === true){
			$sqlEm = 'select 
				sem.id_emision as ide,
				sem.prefijo,
				sem.no_emision, ';
			if ($pr === 'AP' || $pr === 'VI') {
				$sqlEm .= ' (concat("Anulacion de Poliza No ", " ", sem.no_poliza)) as no_cert, ';
			} else {
				$sqlEm .= ' (concat("Anulacion de Certificado No ", " ", sem.prefijo," - ", sem.no_emision)) as no_cert, ';
			}
			$sqlEm .= 'su.usuario,
				su.email,
				su.nombre,
				su2.nombre as usuario_c,
				su2.email as email_c,
				su2.nombre as nombre_c,
				sdep.departamento,
				sem.motivo_anulado,
				sef.id_ef as idef,
				sef.nombre as ef_nombre,
				sef.logo as ef_logo
			from
				' . $table . ' as sem
					inner join
				s_usuario as su2 ON (su2.id_usuario = sem.id_usuario)
					inner join
				s_usuario as su ON (su.id_usuario = sem.and_usuario)
					left join
				s_departamento as sdep ON (sdep.id_depto = su.id_depto)
					inner join 
				s_entidad_financiera as sef ON (sef.id_ef = sem.id_ef)
			where
				sem.id_emision = "' . $ide . '"
					and sem.anulado = true
					and sem.emitir = true
			limit 0 , 1
			;';
			
			if(($rsEm = $link->query($sqlEm,MYSQLI_STORE_RESULT))){
				$rowEm = $rsEm->fetch_array(MYSQLI_ASSOC);
				$rsEm->free();
				
				$mail = new PHPMailer();
				/*$mail->Host = $rowEm['email'];
				$mail->From = $rowEm['email'];
				$mail->FromName = $rowEm['ef_nombre'];*/
				$mail->Host = 'emontano@sudseguros.com';
				$mail->From = 'emontano@sudseguros.com';
				$mail->FromName = $rowEm['ef_nombre'];
	
				$mail->Subject = $rowEm['ef_nombre'] . ': ' . $rowEm['no_cert'];		
				$mail->addAddress($rowEm['email_c'], $rowEm['nombre_c']);
				$mail->addCC($rowEm['email'], $rowEm['nombre']);
				
				$sqlc = 'SELECT sc.correo, sc.nombre 
					FROM s_correo as sc
						INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sc.id_ef)
					WHERE sc.producto = "'.$pr.'" AND sef.id_ef = "'.$rowEm['idef'].'" AND sef.activado = true ;';
				if(($rsc = $link->query($sqlc, MYSQLI_STORE_RESULT))){
					if($rsc->num_rows > 0){
						while($rowc = $rsc->fetch_array(MYSQLI_ASSOC)){
							$mail->addCC($rowc['correo'], $rowc['nombre']);
						}
					}
				}
				
				$body = get_html_body($rowEm, $pr);
				
				$mail->Body = $body;
				$mail->AltBody = $body;
				
				if($mail->send()){
					$arrTR[0] = 1;
					$arrTR[2] = 'La Póliza fue anulada exitosamente';
				}else {
					$arrTR[2] = 'La Póliza no pudo ser anulada !'.$sql;
				}
			}else {
				$arrTR[2] = 'La Póliza no pudo ser anulada |';
			}
		}else{
			$arrTR[2] = 'No se incerto el monto devuelto';
		}
	}else{
		$arrTR[2] = 'La Póliza no pudo ser anulada';
	}
	echo json_encode($arrTR);
}else{
	$arrTR[2] = 'La Póliza no puede ser anulada';
	echo json_encode($arrTR);
}

function get_html_body($rowEm, $pr){
	ob_start();
?>
<div style="width:600px; border:1px solid #CCCCCC; color:#000000; font-weight:bold; font-size:12px; text-align:left;">
	<div style="padding:5px 10px; background:#006697; color:#FFFFFF;">
    	SE HA RECIBIDO UN MENSAJE EN EL SITIO <?=$rowEm['ef_nombre'];?>
	</div><br>
    
    <div style="padding:5px 10px;">
		<?=htmlentities($rowEm['no_cert'], ENT_QUOTES, 'UTF-8');?>
	</div>
    <div style="padding:5px 10px;">
		<?=htmlentities('Oficial de Crédito '.$rowEm['nombre'], ENT_QUOTES, 'UTF-8');?>
	</div>
    <div style="padding:5px 10px;">
		<?=htmlentities('Departamento '.$rowEm['departamento'], ENT_QUOTES, 'UTF-8');?>
	</div><br><br>

	<div style="padding:5px 10px; background:#006697; color:#FFFFFF;">
    	<?=htmlentities('Motivo de Anulación', ENT_QUOTES, 'UTF-8');?>
	</div>
    <div style="padding:5px 10px;">
		<?=$rowEm['motivo_anulado'];?>
	</div>
</div>
<?php
	$html = ob_get_clean();
	return $html;
}
?>