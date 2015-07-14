<?php
require_once('sibas-db.class.php');

$link = new SibasDB();
$ide = 0;
$idc = 0;
$print = false;
$print_url = '';

if (isset($_GET['ide'])) {
	$ide = $link->real_escape_string(base64_decode($_GET['ide']));
} elseif (isset($_GET['idc'])) {
	$idc = $link->real_escape_string(base64_decode($_GET['idc']));
}

if (isset($_GET['print'])) {
	if ($_GET['print'] === sha1('p_print')) {
		$print = true;
		$print_url = '&print=' . $_GET['print'];
		if (($rs_cia = $link->getFinancialInstitutionCompany($_SESSION['idEF'], 'AP')) !== false) {
			if ($rs_cia->num_rows === 1) {
				$row_cia = $rs_cia->fetch_array(MYSQLI_ASSOC);
				$rs_cia->free();
				$_GET['cia'] = base64_encode($row_cia['idcia']);
			}
		}
	}
}

$max_item = 0;
if (($rowBS = $link->get_max_amount_optional($_SESSION['idEF'], 'AP')) !== false) {
	$max_item = (int)$rowBS['max_item'];
}

$flag = $_GET['flag'];
$action = '';

$read_new = '';
$read_save = '';
$read_save_2 = '';
$read_edit = '';
$title = '';
$title_btn = '';
$target = '';
$target_flag = false;
$pp_check = '';
$pp_po_re = 'not-required';
$pp_po_ro = 'readonly';

if (isset($_GET['target'])) {
	$target_flag = true;
}

$sw = 0;

if ($print === true) {
	$pp_check = 'checked';
	$pp_po_re = 'required';
	$pp_po_ro = '';
}

switch($flag){
	case md5('i-new'):
		$action = 'AP-issue-record.php';
		$title = 'Emisión de Póliza de Seguro de Accidentes Personales';
		$title_btn = 'Guardar';
		
		if ($print === false) {
			$read_new = 'readonly';
		}
		$sw = 1;
		break;
	case md5('i-read'):
		$action = 'AP-policy-record.php';
		$title = 'Póliza N° ';
		$title_btn = 'Emitir';
		$read_new = 'disabled';
		$read_save = 'disabled';
		$sw = 2;
		break;
	case md5('i-edit'):
		$action = 'AP-issue-record.php';
		$title = 'Póliza N° ';
		$title_btn = 'Actualizar datos';
		$read_edit = 'readonly';

		if ($target_flag === true) {
			$read_new = 'readonly';
			$read_save_2 = 'readonly';
		}
		$sw = 3;
		break;
}

$sql = '';
switch($sw){
case 1:
	$sql = 'select 
		sdc.id_cotizacion as idc, ';
	if ($print === true) {
		$sql .= 'count(sdc.id_cotizacion), ';
	}
	$sql .= '
		sdc.cobertura,
		scl.id_cliente,
		scl.codigo_be,
		scl.nombre as cl_nombre,
		scl.paterno as cl_paterno,
		scl.materno as cl_materno,
		scl.ap_casada as cl_casada,
		scl.nombre as cl_nombre_tomador,
		scl.paterno as cl_paterno_tomador,
		scl.materno as cl_materno_tomador,
		scl.ap_casada as cl_casada_tomador,
		scl.genero as cl_genero,
		scl.estado_civil as cl_estado_civil,
		scl.tipo_documento as cl_tipo_documento,
		scl.ci as cl_ci,
		scl.complemento as cl_complemento,
		scl.extension as cl_extension,
		scl.fecha_nacimiento as cl_fecha_nacimiento,
		scl.pais as cl_pais,
		scl.lugar_nacimiento as cl_lugar_nacimiento,
		scl.lugar_residencia as cl_lugar_residencia,
		scl.localidad as cl_localidad,
		scl.direccion as cl_direccion,
		scl.telefono_domicilio as cl_telefono_domicilio,
		scl.telefono_celular as cl_telefono_celular,
		scl.email as cl_email,
		scl.id_ocupacion as cl_ocupacion,
		scl.desc_ocupacion as cl_desc_ocupacion,
		scl.telefono_oficina as cl_telefono_oficina,
		scl.edad as cl_edad,
		sdd.porcentaje_credito as cl_porcentaje_credito,
		sdd.id_detalle,
		scl.tipo,
		sdd.numero_cuenta,
		sdd.moneda,
		sdd.tipo_cuenta,
		sdd.referido_por,
		0 as cargo,
		"" as cargo_respuesta,
		"" as cargo_periodo,
		"" as voucher,
		scl.mano as cl_mano,
		scl.lugar_trabajo as cl_direccion_laboral,
		sdc.id_plan as cl_plan,
		scl.telefono_oficina as cl_telefono_oficina,
		"" as cl_forma_pago,
		"" as cl_periodo,
		"" as cl_cuenta_1,
		"" as cl_cuenta_2,
		"" as cl_tarjeta,
		sdc.prima
	from
		s_ap_cot_cabecera as sdc
			inner join
		s_ap_cot_detalle as sdd ON (sdd.id_cotizacion = sdc.id_cotizacion)
			inner join
		s_ap_cot_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
			inner join 
		s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
	where
		sdc.id_cotizacion = "'.$idc.'"
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
	order by sdd.id_detalle asc
	;';
	break;
}

if($sw !== 1){
	$sql = 'select 
	    sde.id_emision AS idc,
	    sde.id_cotizacion,
	    sde.no_emision,
	    sde.no_poliza,
	    sde.prefijo,
	    sde.pre_impreso,
	    sde.cobertura,
	    sde.id_poliza,
	    scl.tipo,
	    scl.codigo_be,
	    scl.id_cliente,
	    scl.nombre AS cl_nombre,
	    scl.paterno AS cl_paterno,
	    scl.materno AS cl_materno,
	    scl.ap_casada AS cl_casada,
	    sde.factura_nombre,
	    sde.factura_nit,
	    scl.genero AS cl_genero,
	    scl.estado_civil AS cl_estado_civil,
	    scl.tipo_documento AS cl_tipo_documento,
	    scl.ci AS cl_ci,
	    scl.complemento AS cl_complemento,
	    scl.extension AS cl_extension,
	    scl.fecha_nacimiento AS cl_fecha_nacimiento,
	    scl.pais AS cl_pais,
	    scl.lugar_nacimiento AS cl_lugar_nacimiento,
	    scl.lugar_residencia AS cl_lugar_residencia,
	    scl.localidad AS cl_localidad,
	    scl.direccion AS cl_direccion,
	    scl.telefono_domicilio AS cl_telefono_domicilio,
	    scl.telefono_celular AS cl_telefono_celular,
	    scl.email AS cl_email,
	    scl.id_ocupacion AS cl_ocupacion,
	    scl.desc_ocupacion AS cl_desc_ocupacion,
	    scl.telefono_oficina AS cl_telefono_oficina,
	    scl.edad AS cl_edad,
	    scl.avenida AS cl_avenida,
	    scl.no_domicilio AS cl_nd,
	    scl.direccion_laboral AS cl_direccion_laboral,
	    scl.mano AS cl_mano,
	    sdd.porcentaje_credito AS cl_porcentaje_credito,
	    sdd.id_detalle,
	    sdd.tipo_pago,
	    sdd.numero_cuenta,
	    sdd.moneda,
	    sdd.tipo_cuenta,
	    sdd.referido_por,
	    sdd.cargo,
	    sdd.cargo_respuesta,
	    sdd.cargo_periodo,
	    sdd.voucher,
	    sdd.tomador_nombre as cl_nombre_tomador,
		sdd.tomador_ci_nit as cl_ci_tomador,
	    sde.id_plan AS cl_plan,
	    sde.forma_pago AS cl_forma_pago,
	    sde.periodo AS cl_periodo,
	    sdd.cuenta_1 AS cl_cuenta_1,
	    sdd.cuenta_2 AS cl_cuenta_2,
	    sdd.tarjeta AS cl_tarjeta,
	    sde.prima
	from
	    s_ap_em_cabecera AS sde
	        LEFT JOIN
	    s_ap_cot_cabecera AS sdc ON (sdc.id_cotizacion = sde.id_cotizacion)
	        INNER JOIN
	    s_ap_em_detalle AS sdd ON (sdd.id_emision = sde.id_emision)
	        INNER JOIN
	    s_cliente AS scl ON (scl.id_cliente = sdd.id_cliente)
	        INNER JOIN
	    s_entidad_financiera AS sef ON (sef.id_ef = sde.id_ef)
	where
		sde.id_emision = "' . $ide . '"
			and sef.id_ef = "' . base64_decode($_SESSION['idEF']) . '"
			and sef.activado = true
	order by sdd.id_detalle asc
	;';
}
// echo $sql;
$rs = $link->query($sql,MYSQLI_STORE_RESULT);
$nCl = $rs->num_rows;

if(($nCl > 0 && $nCl <= $max_item) || $print === true){
	if($rs->data_seek(0) === true){
		$row = $rs->fetch_array(MYSQLI_ASSOC);
		if ($sw !== 1) {
			$idc = $row['id_cotizacion'];
		}
	}
?>
<script type="text/javascript">
$(document).ready(function(e) {
	$("select.readonly option").not(":selected").attr("disabled", "disabled");
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35);
		var _val = $(this).prop('value');
		
		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});
});


function validarRealf(dat){
	var er_num=/^([0-9])*[.]?[0-9]*$/;
	if(dat.value != ""){
		if(!er_num.test(dat))
			return false;
		return true
	}
}
</script>
<h3 id="issue-title"><?=$title;?></h3>
<?php if ($print === false): ?>
<a href="certificate-detail.php?idc=<?=base64_encode($idc);?>&cia=<?=
	$_GET['cia'];?>&type=<?=base64_encode('PRINT');?>&pr=<?=base64_encode('AP');?>" 
	class="fancybox fancybox.ajax btn-see-slip">
	Ver Slip de Cotización
</a>
<?php endif ?>

<form id="fde-issue" name="fde-issue" action="" method="post" class="form-quote form-customer">
<?php
$cont = 0;
$rsCl_1 = array();
$rsCl_2 = array();

$name_client = '';
$cr_coverage = 0;
$cr_modality = '';
$cr_policy = '';
$no_policy = '';
$no_emision = '';
$cr_prima = 0;
$cr_payment = '';
$bill_name = $taken_name = $bill_nit = $taken_nit = $taken_code = '';
$no_poliza = '';
$idNE = '';

$FC = false;

if($rs->data_seek(0) === true){
while($row = $rs->fetch_array(MYSQLI_ASSOC)){
	$cont += 1;
	
	if($sw !== 1){
		// $idNE = $row['prefijo'] . '-' . $row['no_emision'];
		$idNE = $row['no_poliza'];
		$no_emision = $row['no_emision'];
		$cr_policy = $row['id_poliza'];
		$cr_prima = $row['prima'];
		$cr_payment = $row['cl_forma_pago'];

		if ((boolean)$row['pre_impreso'] === true) {
			$pp_check = 'checked';
			$no_poliza = $idNE;
		}

		$bill_name = $row['factura_nombre'];
		$bill_nit = $row['factura_nit'];
		$taken_name = $row['cl_nombre_tomador'];
		$taken_nit = $row['cl_ci_tomador'];
	} else {
		$bill_name = $taken_name = $row['cl_nombre'] . ' ' . $row['cl_paterno'] . ' ' . $row['cl_materno'];
		$bill_nit = $taken_nit = $row['cl_ci'];
		$taken_code = $row['codigo_be'];
	}
	
	$cl_avc = $cl_nd = ''; 
	if($sw === 2 || $sw === 3){
		$cl_avc = $row['cl_avenida'];
		$cl_nd = $row['cl_nd'];
	}

	$name_client = $row['cl_nombre'] . ' ' . $row['cl_paterno'] . ' ' . $row['cl_materno'];
?>
	<h4>Titular <?=$cont;?></h4>
	<div class="form-col">
		<input type="hidden" id="dc-<?=$cont;?>-code" name="dc-<?=$cont;?>-code" 
			autocomplete="off" value="<?=base64_encode($row['codigo_be']);?>">

		<label>Nombres: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-name" name="dc-<?=$cont;?>-name" autocomplete="off" 
				value="<?=$row['cl_nombre'];?>" class="required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Apellido Paterno: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-ln-patert" name="dc-<?=$cont;?>-ln-patern" autocomplete="off" 
				value="<?=$row['cl_paterno'];?>" class="required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Apellido Materno: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-ln-matern" name="dc-<?=$cont;?>-ln-matern" autocomplete="off" 
				value="<?=$row['cl_materno'];?>" class="not-required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Apellido de Casada: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-ln-married" name="dc-<?=$cont;?>-ln-married" autocomplete="off" 
				value="<?=$row['cl_casada'];?>" class="not-required text fbin" <?=$read_new;?>>
		</div><br>

		<label>Género: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-gender" name="dc-<?=$cont;?>-gender" 
				class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
$arr_gender = $link->gender;
for($i = 0; $i < count($arr_gender); $i++){
	$gender = explode('|',$arr_gender[$i]);
	if($gender[0] === $row['cl_genero']) {
		echo '<option value="'.$gender[0].'" selected>'.$gender[1].'</option>';
	} else {
		echo '<option value="'.$gender[0].'">'.$gender[1].'</option>';
	}
}
?>
			</select>
		</div><br>
		
		<label>Documento de Identidad: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-doc-id" name="dc-<?=$cont;?>-doc-id" autocomplete="off" 
				value="<?=$row['cl_ci'];?>" class="required number fbin" <?=$read_new . ' ' . $read_edit;?>>
		</div><br>
		
		<label>Complemento: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-comp" name="dc-<?=$cont;?>-comp" autocomplete="off" 
				value="<?=$row['cl_complemento'];?>" class="not-required dni fbin" 
				style="width:60px;" <?=$read_new;?>>
		</div><br>
		
		<label>Extensión: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-ext" name="dc-<?=$cont;?>-ext" 
				class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
$rsDep = null;
if (($rsDep = $link->get_depto()) === false) {
	$rsDep = null;
}

if ($rsDep->data_seek(0) === true) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === true){
			if($rowDep['id_depto'] === $row['cl_extension']) {
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			} else {
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
			}
		}
	}
}
?>
			</select>
		</div><br>
		
		<label>Fecha de Nacimiento: <span>*</span></label>
		<div class="content-input">
        	<input type="hidden" id="dc-<?=$cont;?>-age" name="dc-<?=$cont;?>-age" value="<?=$row['cl_edad'];?>">
			<input type="text" id="dc-<?=$cont;?>-date-birth" name="dc-<?=$cont;?>-date-birth" 
				autocomplete="off" value="<?=$row['cl_fecha_nacimiento'];?>" 
				class="required fbin date-birth" <?=$read_new;?>>
		</div><br>
		
		<label>Lugar de Nacimiento: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-place-birth" name="dc-<?=$cont;?>-place-birth" 
				autocomplete="off" value="<?=$row['cl_lugar_nacimiento'];?>" 
				class="required fbin" <?=$read_save . ' ' . $read_save_2;?>>
		</div><br>
		
		<label>Avenida o Calle: </label>
		<div class="content-input">
        	<select id="dc-<?=$cont;?>-avc" name="dc-<?=$cont;?>-avc" 
        		class="not-required fbin <?=$read_save . ' ' . $read_save_2;?>" <?=$read_save;?>>
            	<option value="">Seleccione...</option>
<?php
	$arr_AC = $link->avc;
	for($i = 0; $i < count($arr_AC); $i++){
		$AC = explode('|',$arr_AC[$i]);
		if($AC[0] === $cl_avc) {
			echo '<option value="'.$AC[0].'" selected>'.$AC[1].'</option>';
		} else {
			echo '<option value="'.$AC[0].'">'.$AC[1].'</option>';
		}
	}
?>
            </select>
		</div><br>

        <label>Dirección domicilio: <span>*</span></label><br>
		<textarea id="dc-<?=$cont;?>-address-home" name="dc-<?=$cont;?>-address-home" 
			class="required fbin" <?=$read_save . ' ' . $read_save_2;?>><?=$row['cl_direccion'];?></textarea><br>

		<label>Número de domicilio:</label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-nhome" name="dc-<?=$cont;?>-nhome" autocomplete="off" 
				value="<?=$cl_nd;?>" class="not-required number fbin" 
				<?=$read_save . ' ' . $read_save_2;?>>
		</div><br>

		<label>Lugar de Trabajo: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-work" name="dc-<?=$cont;?>-work" autocomplete="off" 
				value="<?=$row['cl_direccion_laboral'];?>" 
				class="not-required fbin" <?=$read_save . ' ' . $read_save_2;?>>
		</div><br>
	</div><!--
	--><div class="form-col">
		<label>Teléfono 1: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-phone-1" name="dc-<?=$cont;?>-phone-1" autocomplete="off" 
				value="<?=$row['cl_telefono_domicilio'];?>" class="required phone fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Teléfono 2: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-phone-2" name="dc-<?=$cont;?>-phone-2" autocomplete="off" 
				value="<?=$row['cl_telefono_celular'];?>" class="not-required phone fbin" <?=$read_new;?>>
		</div><br>
        
        <label>Teléfono oficina: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-phone-office" name="dc-<?=$cont;?>-phone-office" autocomplete="off" 
				value="<?=$row['cl_telefono_oficina'];?>" class="not-required phone fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Email: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-email" name="dc-<?=$cont;?>-email" autocomplete="off" 
				value="<?=$row['cl_email'];?>" class="not-required email fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Ocupación: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-occupation" name="dc-<?=$cont;?>-occupation" 
				class="required fbin <?=$read_save . ' ' . $read_save_2;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
if (($rsOcc = $link->get_occupation($_SESSION['idEF'], 'AP')) !== false) {
	while($rowOcc = $rsOcc->fetch_array(MYSQLI_ASSOC)){
		if($rowOcc['id_ocupacion'] === $row['cl_ocupacion']) {
			echo '<option value="'.base64_encode($rowOcc['id_ocupacion']).'" selected>'.$rowOcc['ocupacion'].'</option>';
		} else {
			echo '<option value="'.base64_encode($rowOcc['id_ocupacion']).'">'.$rowOcc['ocupacion'].'</option>';
		}
	}
}
?>
			</select>
		</div><br>
		
		<label style="width:auto;">¿Cuál es su profesión u ocupación habitual?: <span>*</span></label><br>
		<textarea id="dc-<?=$cont;?>-desc-occ" name="dc-<?=$cont;?>-desc-occ" 
			class="required fbin" <?=$read_new;?>><?=$row['cl_desc_ocupacion'];?></textarea><br>
		
        <label>Mano utilizada para escribir y/o firmar: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-hand" name="dc-<?=$cont;?>-hand" 
				class="required fbin <?=$read_new;?>" <?=$read_save;?>>
            	<option value="">Seleccione...</option>
<?php
$arr_HA = $link->hand;
for($i = 0; $i < count($arr_HA); $i++){
	$HA = explode('|',$arr_HA[$i]);
	if($HA[0] === $row['cl_mano']) {
		echo '<option value="'.$HA[0].'" selected>'.$HA[1].'</option>';
	} else {
		echo '<option value="'.$HA[0].'">'.$HA[1].'</option>';
	}
}
?>
            </select>
		</div><br>
        
        <label>Forma de Pago: <span>*</span></label>
        <div class="content-input">
			<select id="dc-payment" name="dc-payment" 
				class="required fbin <?=$read_save . ' ' . $read_save_2 . ' ' . $read_edit;?>" <?=$read_save;?>>
            	<option value="">Seleccione...</option>
<?php
$arr_mp = $link->payment;
for($i = 0; $i < count($arr_mp); $i++){
	$MP = explode('|',$arr_mp[$i]);
	if($MP[0] === $row['cl_forma_pago']) {
		echo '<option value="' . $MP[0] . '" selected>' . $MP[1] . '</option>';
	} else {
		echo '<option value="' . $MP[0] . '">' . $MP[1] . '</option>';
	}
}
?>
            </select>
		</div><br>
        
        <label>Periodicidad: <span>*</span></label>
        <div class="content-input">
			<select id="dc-period" name="dc-period" 
				class="required fbin <?=$read_edit . ' ' . $read_save . ' ' . $read_save_2;?>" <?=$read_save;?>>
            	<option value="">Seleccione...</option>
<?php
$arr_PR = $link->period;
for($i = 0; $i < count($arr_PR); $i++){
	$PR = explode('|',$arr_PR[$i]);
	if($PR[0] === $row['cl_periodo']) {
		echo '<option value="'.$PR[0].'" selected>'.$PR[1].'</option>';
	} else {
		echo '<option value="'.$PR[0].'">'.$PR[1].'</option>';
	}
}
?>
            </select>
		</div><br>
        
        <label>Número de Cuenta 1: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-account-1" name="dc-<?=$cont;?>-account-1" 
				autocomplete="off" value="<?=$row['cl_cuenta_1'];?>" 
				class="required fbin number account" <?=$read_save . ' ' . $read_save_2;?>
				rel="<?=$cont;?>" >
		</div><br>
        
        <label>Número de Cuenta 2:</label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-account-2" name="dc-<?=$cont;?>-account-2" 
				autocomplete="off" value="<?=$row['cl_cuenta_2'];?>" 
				class="not-required number fbin" <?=$read_save . ' ' . $read_save_2;?>>
		</div><br>
        
        <label>Tarjeta de Crédito: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-card" name="dc-<?=$cont;?>-card" 
				autocomplete="off" value="<?=$row['cl_tarjeta'];?>" 
				class="required fbin number card" <?=$read_save . ' ' . $read_save_2;?>
				rel="<?=$cont;?>" >
		</div><br>

		<label>Plan: <span>*</span></label>
		<div class="content-input">
			<select id="dc-plan" name="dc-plan" 
				class="required fbin <?=$read_save . ' ' . $read_edit . ' ' . $read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
if (($rsPn = $link->get_plan($_SESSION['idEF'], 'AP')) !== false) {
	while($rowPn = $rsPn->fetch_array(MYSQLI_ASSOC)){
		if($rowPn['id_plan'] === $row['cl_plan']) {
			echo '<option value="' . $rowPn['id_plan'] 
				. '" selected>' . $rowPn['nombre'] . '</option>';
		} else {
			echo '<option value="' . $rowPn['id_plan'] 
				. '">' . $rowPn['nombre'] . '</option>';
		}
	}
}
?>
			</select>
<?php if ($target_flag === true): ?>
			<input type="hidden" name="plan_sv" id="plan_sv" value="<?=$row['cl_plan'];?>">
<?php endif ?>
		</div><br>
<?php
	echo '<input type="hidden" id="dc-' . $cont . '-type" name="dc-' . $cont . '-type" 
		value="' . (int)$row['tipo'] . '">';
?>
		
		<label style="display: none;">Participación: <span>*</span></label>
		<div class="content-input" style="display: none;">
			<input type="text" id="dc-<?=$cont;?>-share" name="dc-<?=$cont;?>-share" 
				autocomplete="off" value="<?=(int)$row['cl_porcentaje_credito'];?>" 
				class="required real fbin" <?=$read_new . $read_edit;?>>
		</div><br>
<?php
if($sw === 1){
	$row['id_cliente'] = uniqid('@S#1$2013-' . $cont . '-', true);
	echo '<input type="hidden" id="dd-' . $cont . '-id_detalle" name="dd-' . $cont . '-id_detalle" 
    	value="' . base64_encode($row['id_detalle']) . '">';
} else {
	echo '<input type="hidden" id="dd-' . $cont . '-idd" name="dd-' . $cont . '-idd" 
    	value="' . base64_encode($row['id_detalle']) . '">';
}
?>
		<input type="hidden" id="dc-<?=$cont;?>-idcl" name="dc-<?=$cont;?>-idcl" autocomplete="off" 
			value="<?=base64_encode($row['id_cliente']);?>" class="required fbin" <?=$read_new;?>>
	</div><br>

	<div id="content-plan">
<script type="text/javascript">
$(document).ready(function(e){
	$("#dc-plan").change(function(e){
		var plan = $(this).prop('value');
		var plan_sv = $('#plan_sv').prop('value');
		
		$('.plan-detail').hide();

		if (plan !== '') {
			$('#' + plan).show();
		}

		if (plan !== plan_sv) {
			$('#plan_ch').prop('value', 1);
		} else {
			$('#plan_ch').prop('value', 0);
		}
	});

	$('#dc-payment').change(function(e){
		var payment = $(this).prop('value');

		$('#dc-period option').not(":selected").attr("disabled", false);

		switch (payment) {
		case 'CO':
			$('.account')
				.removeClass('required error-text')
				.addClass('not-required')
				.prop('readonly', true)
				.prop('value', 0);
			
			if($('.account + .msg-form').length) {
				$('.account + .msg-form').remove();
			}

			$('#dc-period option').each(function(index) {
				var value = $(this).prop('value');
				if (value === 'Y') {
					$(this).prop('selected', true);
				}
			});

			$('#dc-period').removeClass('error-text');
			if($('#dc-period + .msg-form').length) {
				$('#dc-period + .msg-form').remove();
			}

			$('.card')
				.removeClass('required error-text')
				.addClass('not-required');

			if($('.card + .msg-form').length) {
				$('.card + .msg-form').remove();
			}

			$('#dc-period option').not(":selected").attr("disabled", true);
			break;
		default:
			$('.account')
				.removeClass('not-required error-text')
				.addClass('required')
				.prop('value', '')
				.prop('readonly', false);

			$('.card')
				.removeClass('not-required')
				.addClass('required');

			$('#dc-period').prop('value', '');
			break;
		}
	});
});
</script>
<?php
$plan_dis = '';

if (($rsPl = $link->get_plan($_SESSION['idEF'], 'AP')) !== false) {
	while ($rowPl = $rsPl->fetch_array(MYSQLI_ASSOC)) {
		if ($row['cl_plan'] === $rowPl['id_plan']) {
			$plan_dis = '';
		} else {
			$plan_dis = 'display: none;';
		}
?>
		<div class="plan-detail" id="<?=$rowPl['id_plan'];?>" style="<?=$plan_dis;?>">
			<?=$rowPl['descripcion'];?>
		</div>
<?php
	}
}
?>
    </div><br>
<?php
if($cont === 1) {
	echo '<input type="hidden" id="dc-'.$cont.'-titular" name="dc-'.$cont.'-titular" value="DD">';
} elseif ($cont > 1) {
	echo '<input type="hidden" id="dc-'.$cont.'-titular" name="dc-'.$cont.'-titular" value="CC">';
}

$beneficiary = $link->getBeneficiary();
$beneficiaryData = array();

for ($i = 1; $i <= $beneficiary; $i++) { 
	$beneficiaryData[$i]['id'] = '';
	$beneficiaryData[$i]['name'] = '';
	$beneficiaryData[$i]['patern'] = '';
	$beneficiaryData[$i]['matern'] = '';
	$beneficiaryData[$i]['relation'] = '';
	$beneficiaryData[$i]['dni'] = '';
	$beneficiaryData[$i]['percentage'] = 0;

	if ($i === 1) {
		$beneficiaryData[$i]['class'] = 'required';
		$beneficiaryData[$i]['star'] = '*';
	} else {
		$beneficiaryData[$i]['class'] = 'not-required';
		$beneficiaryData[$i]['star'] = '';
	}
}

if ($sw !== 1) {
	$sqlBN = 'select
		sab.id_beneficiario, 
		sab.nombre, 
		sab.paterno, 
		sab.materno, 
		sab.parentesco, 
		sab.ci, 
		sab.porcentaje_credito 
	from
		s_ap_beneficiario as sab
			inner join
		s_ap_em_detalle as sdd ON (sdd.id_detalle = sab.id_detalle)
	where
		sab.id_detalle = "' . $row['id_detalle'] . '"
	;';
	//'echo $sqlBN;
	$rsBN = $link->query($sqlBN,MYSQLI_STORE_RESULT);
	// echo $rsBN->num_rows;
	if($rsBN->num_rows > 0){
		$bn = 1;
		while ($rowBN = $rsBN->fetch_array(MYSQLI_ASSOC)) {
			$beneficiaryData[$bn]['id'] = $rowBN['id_beneficiario'];
			$beneficiaryData[$bn]['name'] = $rowBN['nombre'];
			$beneficiaryData[$bn]['patern'] = $rowBN['paterno'];
			$beneficiaryData[$bn]['matern'] = $rowBN['materno'];
			$beneficiaryData[$bn]['relation'] = $rowBN['parentesco'];
			$beneficiaryData[$bn]['dni'] = $rowBN['ci'];
			$beneficiaryData[$bn]['percentage'] = $rowBN['porcentaje_credito'];
			$bn += 1;
		}
	}
}

$nbn = 0;
foreach ($beneficiaryData as $key => $value) {
	$nbn += 1;
?>
	<div class="form-col">
		<h4>Declaratoria de Beneficiario <?=$nbn;?> - Titular <?=$cont;?></h4>
		<label>Nombres: <span><?=$beneficiaryData[$nbn]['star'];?></span></label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-name-<?=$nbn;?>" 
				name="dsp-<?=$cont;?>-name-<?=$nbn;?>" 
				autocomplete="off" value="<?=$beneficiaryData[$nbn]['name'];?>" 
				class="<?=$beneficiaryData[$nbn]['class'];?> text fbin" <?=$read_save;?>>
		</div><br>		
		
		<label>Apellido Paterno: <span><?=$beneficiaryData[$nbn]['star'];?></span></label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-ln-patern-<?=$nbn;?>" 
				name="dsp-<?=$cont;?>-patern-<?=$nbn;?>" 
				autocomplete="off" value="<?=$beneficiaryData[$nbn]['patern'];?>" 
				class="<?=$beneficiaryData[$nbn]['class'];?> text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Apellido Materno: </label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-ln-matern-<?=$nbn;?>" 
				name="dsp-<?=$cont;?>-matern-<?=$nbn;?>" 
				autocomplete="off" value="<?=$beneficiaryData[$nbn]['matern'];?>" 
				class="not-<?=$beneficiaryData[$nbn]['class'];?> text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Parentesco: <span><?=$beneficiaryData[$nbn]['star'];?></span></label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-relation-<?=$nbn;?>" 
				name="dsp-<?=$cont;?>-relation-<?=$nbn;?>" 
				autocomplete="off" value="<?=$beneficiaryData[$nbn]['relation'];?>" 
				class="<?=$beneficiaryData[$nbn]['class'];?> text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Documento de Identidad: <span></span></label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-dni-<?=$nbn;?>" 
				name="dsp-<?=$cont;?>-dni-<?=$nbn;?>" 
				autocomplete="off" value="<?=$beneficiaryData[$nbn]['dni'];?>" 
				class="not-<?=$beneficiaryData[$nbn]['class'];?> dni fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Participación %: <span><?=$beneficiaryData[$nbn]['star'];?></span></label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-por-<?=$nbn;?>" 
				name="dsp-<?=$cont;?>-por-<?=$nbn;?>" 
				maxlength="3" autocomplete="off" value="<?=$beneficiaryData[$nbn]['percentage'];?>" 
				class="<?=$beneficiaryData[$nbn]['class'];?> real fbin" <?=$read_save;?>>
		</div><br>
	</div>
<?php
	if($sw !== 1) {
		echo '<input type="hidden" id="dsp-' . $cont . '-idb-' . $nbn . '" 
			name="dsp-' . $cont . '-idb-' . $nbn . '" 
			value="' . base64_encode($beneficiaryData[$nbn]['id']) . '">';
	}
}
	}
}
?>
	<br>
<?php if ($print === true): ?>
	<h4>Seguro de Accidentes Personales - Cuestionario</h4>
	<div>
<?php
		if (($rsQs = $link->get_question($_SESSION['idEF'], 'AP')) !== FALSE) {
			$arr_qs = array();
			if ($sw !== 1) {
				if (($row_qs = $link->get_question_be($_GET['ide'], 'AP')) !== false) {
					$arr_qs = json_decode($row_qs['respuesta'], true);
				}
			}

			$res = '';

			while ($rowQs = $rsQs->fetch_array(MYSQLI_ASSOC)) {
                $class_yes = $class_no = $check_yes = $check_no = $disabled_yes = $disabled_no = '';

				if ($rowQs['respuesta'] == 0) {
					$class_no = 'class="required"';
					$res .= $rowQs['orden'].', ';
					$check_no = 'checked';
				} elseif($rowQs['respuesta'] == 1) {
					$class_yes = 'class="required"';
					$check_yes = 'checked';
				}

				if ($sw !== 1 && count($arr_qs) > 0) {
					foreach ($arr_qs as $key => $value) {
						$data = explode('|', $value);

						if ($data[0] === $rowQs['id_pregunta']) {
							if ($data[1] == 1) {
								$check_yes = 'checked';
								$check_no = '';
							} elseif ($data[1] == 0) {
								$check_yes = '';
								$check_no = 'checked';
							}
							break;
						}
					}
				}
?>
		<div class="question">
			<span class="qs-no"><?=$rowQs['orden'];?></span>
			<p class="qs-title"><?=$rowQs['pregunta'];?></p>
			<div class="qs-option">
	            <label class="check">SI&nbsp;&nbsp;
                <input type="radio" id="dq-qs<?=$cont;?>-<?=$rowQs['id_pregunta'];?>1"
                    	name="dq-qs-<?=$cont;?>-<?=$rowQs['id_pregunta'];?>" value="1"
                       	<?=$class_yes . ' ' . $check_yes. ' ' . $disabled_yes 
                       		. ' ' . $read_new;?>></label>
            <label class="check">NO&nbsp;&nbsp;
                <input type="radio" id="dq-qs<?=$cont;?>-<?=$rowQs['id_pregunta'];?>2"
                       	name="dq-qs-<?=$cont;?>-<?=$rowQs['id_pregunta'];?>" value="0"
                       	<?=$class_no . ' ' . $check_no . ' ' . $disabled_no 
                       		. ' ' . $read_new;?>></label>
			</div>
		</div>
<?php
			}
		}
?>
	</div>
	<br>
<?php endif ?>

	<div>
        <div class="form-col">
			<h4>Datos del Tomador</h4>

        	<label style="width: auto; font-size: 90%;">
	            <input class="check various fancybox.ajax" type="checkbox" id="taken-flag" 
	            	name="taken-flag" value="1" <?=$read_save;?>>
				&nbsp;&nbsp;El Tomador de la Póliza no es igual al Asegurado
			</label>

			<div class="taken">
				Documento de Identidad:
				<input type="text" id="dsc-dni" autocomplete="off" 
					value="" class="text fbin">
				<input type="button" id="dsc-sc" value="Buscar Titular" class="btn-search-cs">
				<div class="taken__result"></div>
			</div>
			
			<label>Nombre: <span>*</span></label><br>
			<input type="hidden" name="taken-code" id="taken-code" value="<?=$taken_code;?>">
        	<div class="content-input">
                <textarea id="taken-name" name="taken-name" class="required fbin" 
                	<?=$read_save . $read_edit;?>><?=trim($taken_name);?></textarea><br>
            </div><br>

            <label>CI/NIT: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="taken-nit" name="taken-nit" autocomplete="off" value="<?=$taken_nit;?>" 
                	class="required dni fbin field-company" <?=$read_save . $read_edit;?>>
            </div><br>
        </div><!--
        --><div class="form-col">
        	<h4>Datos de Facturación</h4>
			<label>Facturar a: <span>*</span></label><br>
            <div class="content-input">
                <textarea id="bl-name" name="bl-name" class="required fbin" 
                	<?=$read_save . $read_edit;?>><?=trim($bill_name);?></textarea><br>
            </div><br>
        	
        	<label>NIT: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="bl-nit" name="bl-nit" autocomplete="off" value="<?=$bill_nit;?>" 
                	class="required dni fbin field-company" <?=$read_save . $read_edit;?>>
            </div><br>
        </div>
    </div>
    <br>

	<h4>Datos adicionales</h4>
	<div class="form-col">
		<label style="width: auto; font-size: 110%; 
			margin-left: 40px;">
            <input class="check" type="checkbox" id="di-print" name="di-print" 
            	value="1" <?=$pp_check . ' ' . $read_new;?>>
			&nbsp;&nbsp;Certificado Pre-Impreso
		</label>
	</div><!--
	--><div class="form-col">
		<label style="width: auto;">Número de Póliza: <span></span></label>
		<div class="content-input">
			<input type="text" id="di-policy-pp" name="di-policy-pp" 
				autocomplete="off" value="<?=$no_poliza;?>" 
				class="fbin number <?=$pp_po_re;?>" <?=$pp_po_ro . ' ' . $read_new;?>>
		</div><br>
	</div>
	<br>

	<div class="form-col">
		<label style="display: none;">Póliza: <span>*</span></label>
		<div class="content-input"  style="display: none;">
			<select id="di-policy" name="di-policy" class="required fbin" <?=$read_save;?>>
<?php
if (($rsPl = $link->get_policy($_SESSION['idEF'], 'AP')) !== false) {
	while($rowPl = $rsPl->fetch_array(MYSQLI_ASSOC)){
		if($rowPl['id_poliza'] === $cr_policy) {
			echo '<option value="' . base64_encode($rowPl['id_poliza']) 
				. '" selected>' . $rowPl['no_poliza'] . '</option>';
			$no_policy = $rowPl['no_poliza'];
		} else {
			echo '<option value="' . base64_encode($rowPl['id_poliza']) 
				. '">' . $rowPl['no_poliza'] . '</option>';
		}
	}
}
?>
			</select>
		</div><br>
	</div><!--
	--><div class="form-col">
<?php if ($sw === 2 && ($cr_payment === 'CO' || $cr_payment === 'DM')): ?>
		<div class="collection">
			<p>
				Orden de Cobro en Caja
			</p>
			<table class="collection-data">
				<tr>
					<td style="width: 30%; ">Póliza: </td>
					<td style="width: 70%; color: #000;"><?=$idNE;?></td>
				</tr>
				<tr>
					<td style="width: 30%; ">Cliente: </td>
					<td style="width: 70%; color: #000;"><?=$name_client;?></td>
				</tr>
				<tr>
					<td style="width: 30%; ">Importe: </td>
					<td style="width: 70%; color: #000;"><?=$cr_prima;?></td>
				</tr>
				<tr>
					<td style="width: 30%; ">Moneda: </td>
					<td style="width: 70%; color: #000;">Bolivianos</td>
				</tr>
			</table>
		</div>
<?php endif ?>
	</div>

    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=$_GET['pr'];?>">
    <input type="hidden" id="flag" name="flag" value="<?=$_GET['flag'];?>">
    <input type="hidden" id="cia" name="cia" value="<?=$_GET['cia'];?>">
<?php if ($print === true): ?>
    <input type="hidden" id="print" name="print" value="<?=$_GET['print'];?>">
<?php endif ?>
<?php
	
	if ($target_flag === true) {
		echo '<input type="hidden" id="target" name="target" value="' . $_GET['target'] . '">';
		$target = '&target=' . $_GET['target'];
	}

	if (isset($_GET['ide'])) {
		echo '<input type="hidden" id="de-ide" name="de-ide" value="' 
			. base64_encode($ide) . '" >';
	} elseif(isset($_GET['idc'])) {
		echo '<input type="hidden" id="de-idc" name="de-idc" value="' 
			. base64_encode($idc) . '" >';
	}
?>
	<div style="text-align:center;">
<?php
	if ($sw === 2) {
		echo '<input type="button" id="dc-edit" name="dc-edit" 
			value="Editar" class="btn-next btn-issue" > ';
	}
	
	echo '<input type="submit" id="dc-issue" name="dc-issue" 
		value="' . $title_btn . '" class="btn-next btn-issue" > ';
	
	if ($sw === 2) {
		echo '<input type="button" id="dc-save" name="dc-save" 
			value="Guardar/Cerrar" class="btn-next btn-issue" >';
	}
	
?>
    </div>
    
    <div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(e) {
	$('.check').iCheck({
		checkboxClass: 'icheckbox_flat-red',
		radioClass: 'iradio_flat-red'
	});

	$('.account, .card').keyup(function(e){
		var rel = 0;
		var field = 0;
		var value = '';

		if ($(this).hasClass('account') === true) {
			field = 1;
		} else if ($(this).hasClass('card') === true) {
			field = 2;
		}

		rel = $(this).attr('rel');
		value = $(this).prop('value');

		if (value.length !== 0) {
			switch (field) {
			case 1:
				$('#dc-' + rel + '-card')
					.removeClass('required error-text')
					.addClass('not-required');

				if($('#dc-' + rel + '-card + .msg-form').length) {
					$('#dc-' + rel + '-card + .msg-form').remove();
				}
				break;
			case 2:
				$('#dc-' + rel + '-account-1')
					.removeClass('required error-text')
					.addClass('not-required');

				if($('#dc-' + rel + '-account-1 + .msg-form').length) {
					$('#dc-' + rel + '-account-1 + .msg-form').remove();
				}
				break;
			}
		} else {
			switch (field) {
			case 1:
				$('#dc-' + rel + '-card').removeClass('not-required').addClass('required');
				break;
			case 2:
				$('#dc-' + rel + '-account-1').removeClass('not-required').addClass('required');
				break;
			}
		}
	});

	$('.account, .card').trigger('keyup');

	$('input[type="checkbox"], input[type="radio"]').on('ifChanged', function(e) {
		if ($(this).prop('readonly') === true) {
			if ($(this).is(':checked')) {
				$(this).iCheck('uncheck');
			} else {
				$(this).iCheck('check');
			}
		} else {
			if ($(this).prop('name') === 'di-print') {
				if ($(this).is(':checked')) {
					$('#di-policy-pp').removeClass('not-required').addClass('required')
						.prop('readonly', false);
				} else {
					$('#di-policy-pp').removeClass('required').addClass('not-required')
						.prop('readonly', true).prop('value', '');
				}
			}
		}
	});

	$('#taken-flag').on('ifChanged', function(e) {
		var payment = $('#dc-payment').prop('value');
		
		if (payment !== 'CO' && payment.length > 0) {
			if ($(this).is(':checked')) {
				$('.taken').slideDown();
			} else {
				$('.taken').slideUp();
			}
		}

	});

	$('#dsc-sc').click(function(e) {
		var dni = $('#dsc-dni').prop('value');

		if (dni.length > 0) {
			$.ajax({
				url: 'get_data_customer.php',
				type: 'GET',
				data: 'dni=' + dni,
				dataType: 'json',
				async: true,
				cache: false,
				beforeSend: function(){
					$('.taken__result').html('Espere...');
				},
				complete: function(){
				},
				success: function(result){
					$('.taken__result').html('');

					if (result["mess"] === '') {
						$.each(result["data"], function(index, value) {
							$('.taken__result').append('<a href="" tittle="Codigo de Cliente" \
								class="code-cl" data-code="' + value['code'] + '" \
								data-name="' + value['name'] + '" \
								data-nit="' + value['dni'] + '">' + value['code'] + ' - \
								' + value['name'] + ' - ' + value['dni'] + ' </a><br>');
						});
						set_data_customer();
					} else{
						$('.taken__result').html(result["mess"]);
					}
				}
			});
		}
	});

	function set_data_customer () {
		$('.code-cl').click(function(e) {
			e.preventDefault();
			var code = $(this).attr('data-code');
			var name = $(this).attr('data-name');
			var nit = $(this).attr('data-nit');
			
			$('#taken-code').prop('value', code);
			$('#taken-name').prop('value', name);
			$('#taken-nit').prop('value', nit);
		});
	}

	/*$('#di-print').on('ifChanged', function(e) {
		if ($(this).is(':checked')) {
			if ($(this).prop('readonly') === false) {
				$('#di-policy-pp').removeClass('not-required').addClass('required')
					.prop('readonly', false);
			} else {

			}
		} else {
			if ($(this).prop('readonly') === false) {
				$('#di-policy-pp').removeClass('required').addClass('not-required')
					.prop('readonly', true).prop('value', '');
			}
		}
	});*/

	$("#dc-save").click(function(e){
		e.preventDefault();
		location.href = 'index.php';
	});
	
	$("#dc-edit").click(function(e){
		e.preventDefault();
		location.href = 'ap-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&ide=<?=base64_encode($ide);?>&flag=<?=md5('i-edit');?>&cia=<?=$_GET['cia'].$target.$print_url;?>';
	});
<?php
switch($sw){
	case 1:
?>
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true
	});		
<?php
		if ($print === true) {
?>
	$(".date-birth").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date-birth").datepicker($.datepicker.regional[ "es" ]);
<?php
		}
		break;
	case 2:
?>
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true,
		issue: true
	});
	$("#issue-title").append(' <?=$idNE;?>');
<?php
		break;
	case 3:
		if ($target_flag === false) {
?>
	$(".date-birth").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date-birth").datepicker($.datepicker.regional[ "es" ]);
<?php
		}
?>
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true
	});
	$("#issue-title").append(' <?=$idNE;?>');
<?php
		break;
}

if($FC === true && ($sw === 2 || $sw === 3)){
?>
	$("#issue-title:last").after('<div class="fac-mess"><strong>Nota:</strong> Se deshabilitó el boton "Emitir" por las siguientes razones: <br><?=$mFC;?><br><br><strong>Por tanto:</strong><br>Debe solicitar aprobación a la Compañía de Seguros. </div>');
<?php
}
?>
});
</script>
<?php
}else{
	echo 'No existen Clientes';
	exit();
}