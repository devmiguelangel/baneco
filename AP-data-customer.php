<?php
date_default_timezone_set('America/La_Paz');

require_once 'sibas-db.class.php';
require 'classes/ws_baneco.php';

$link = new SibasDB();

$max_item = 0;
if (($rowBS = $link->get_max_amount_optional($_SESSION['idEF'], 'AP')) !== false) {
	$max_item = (int)$rowBS['max_item'];
}

$swCl = false;

$dc_code = '';
$dc_type = '';
$dc_name = '';
$dc_lnpatern = '';
$dc_lnmatern = '';
$dc_lnmarried = '';
$dc_status = '';
$dc_type_doc = '';
$dc_doc_id = '';
$dc_comp = '';
$dc_ext = '';
$dc_country = '';
$dc_birth = '';
$dc_place_birth = '';
$dc_place_res = '';
$dc_locality = '';
$dc_address = '';
$dc_occupation = '';
$dc_phone_1 = '';
$dc_desc_occ = '';
$dc_phone_2 = '';
$dc_email = '';
$dc_phone_office = '';
$dc_gender = '';
$dc_referredby = '';
$dc_weight = '';
$dc_height = '';
$dc_place_work = '';
$dc_plan = '';
$dc_hand = '';

$aux_type = 0;

$title_btn = 'Agregar Titular';
$err_search = '';

if(isset($_GET['idCl']) && isset($_GET['idd'])){
	$swCl = true;
	$title_btn = 'Actualizar datos';
	
	$sqlUp = 'select 
		scl.id_cliente,
		scl.tipo,
		scl.codigo_be,
		scl.paterno,
		scl.materno,
		scl.nombre,
		scl.ap_casada,
		scl.fecha_nacimiento,
		scl.lugar_nacimiento,
		scl.ci,
		scl.extension,
		scl.complemento,
		scl.tipo_documento,
		scl.estado_civil,
		scl.lugar_residencia,
		scl.localidad,
		scl.direccion,
		scl.pais,
		scl.id_ocupacion,
		scl.desc_ocupacion,
		scl.telefono_domicilio,
		scl.telefono_oficina,
		scl.telefono_celular,
		scl.email,
		scl.genero,
		scl.lugar_trabajo,
		scl.mano,
		sac.id_plan,
		sad.referido_por
	from
		s_ap_cot_cabecera as sac
			inner join
		s_ap_cot_detalle as sad ON (sad.id_cotizacion = sac.id_cotizacion)
			inner join
		s_ap_cot_cliente as scl ON (scl.id_cliente = sad.id_cliente)
			inner join 
		s_entidad_financiera as sef ON (sef.id_ef = sac.id_ef)
	where
		sac.id_cotizacion = "' . base64_decode($_GET['idc']) . '"
			and scl.id_cliente = "'.base64_decode($_GET['idCl']).'"
			and sad.id_detalle = "' . base64_decode($_GET['idd']) . '"
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
	;';
	
	$rsUp = $link->query($sqlUp);
	
	if($rsUp->num_rows === 1){
		$rowUp = $rsUp->fetch_array(MYSQLI_ASSOC);
		$rsUp->free();
		
		$dc_code = $rowUp['codigo_be'];
		$dc_type = (int)$rowUp['tipo'];
		$dc_name = $rowUp['nombre'];
		$dc_lnpatern = $rowUp['paterno'];
		$dc_lnmatern = $rowUp['materno'];
		$dc_lnmarried = $rowUp['ap_casada'];
		$dc_status = $rowUp['estado_civil'];
		$dc_type_doc = $rowUp['tipo_documento'];
		$dc_doc_id = $rowUp['ci'];
		$dc_comp = $rowUp['complemento'];
		$dc_ext = $rowUp['extension'];
		$dc_country = $rowUp['pais'];
		$dc_birth = $rowUp['fecha_nacimiento'];
		$dc_place_birth = $rowUp['lugar_nacimiento'];
		$dc_place_res = $rowUp['lugar_residencia'];
		$dc_locality = $rowUp['localidad'];
		$dc_address = $rowUp['direccion'];
		$dc_occupation = $rowUp['id_ocupacion'];
		$dc_desc_occ = $rowUp['desc_ocupacion'];
		$dc_phone_1 = $rowUp['telefono_domicilio'];
		$dc_phone_2 = $rowUp['telefono_celular'];
		$dc_phone_office = $rowUp['telefono_oficina'];
		$dc_email = $rowUp['email'];
		$dc_gender = $rowUp['genero'];
		$dc_place_work = $rowUp['lugar_trabajo'];
		$dc_hand = $rowUp['mano'];
		$dc_plan = $rowUp['id_plan'];
		$dc_referredby = $rowUp['referido_por'];
	}
}
?>
<h3>Datos del Titular</h3>
<?php
$nCl = 0;
if($swCl === false){
	$sqlCl = 'select 
		scl.id_cliente,
		sdd.id_detalle,
		scl.nombre as cl_nombre,
		scl.paterno as cl_paterno,
		scl.materno as cl_materno,
		concat(scl.ci, scl.complemento, " ", sde.codigo) as cl_dni,
		date_format(scl.fecha_nacimiento, "%d/%m/%Y") as cl_fn,
		(case scl.genero
			when "M" then "Hombre"
			when "F" then "Mujer"
		end) as cl_genero,
		sdd.porcentaje_credito as cl_pc,
		scl.tipo
	from
		s_ap_cot_cabecera as sdc 
			inner join
		s_ap_cot_detalle as sdd ON (sdd.id_cotizacion = sdc.id_cotizacion)
			inner join
		s_ap_cot_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
			inner join
		s_departamento as sde ON (sde.id_depto = scl.extension)
			inner join 
		s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
	where
		sdc.id_cotizacion = "'.base64_decode($_GET['idc']).'"
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
	order by sdd.id_detalle asc
	limit 0, '.$max_item.'
	;';
	
	$rsCl = $link->query($sqlCl,MYSQLI_STORE_RESULT);
	$nCl = $rsCl->num_rows;
	
	if($nCl < $max_item){
?>
<form id="fde-sc" name="fde-sc" action="" method="post" class="form-quote">
	<label>Documento de Identidad: <span>*</span></label>
	<div class="content-input" style="width:auto;">
		<input type="text" id="dsc-dni" name="dsc-dni" autocomplete="off" 
			value="" style="width:120px;" class="required number fbin">
	</div>
	<input type="submit" id="dsc-sc" name="dsc-sc" value="Buscar Titular" class="btn-search-cs">
</form>
<?php
		if (isset($_POST['dsc-dni'])) {
			$dni = $link->real_escape_string(trim($_POST['dsc-dni']));

			if ($link->checkWebService($_SESSION['idEF'], 'AP') === true) {
				$ws = new WSBaneco('SC');

				if ($ws->ws_connect(array('NroDocumento' => $dni)) === true) {
?>
<div class="form-quote" style="width: 70%; margin: 5px auto; border: 1px solid #ccc; background: #feffd8">
	<p style="font-weight: bold; border-bottom: 1px solid #999; margin-bottom: 5px;">
		Seleccione el Cliente
	</p>
	<table class="list-cl">
		<thead>
			<tr>
				<td>Código</td>
				<td>Cliente</td>
				<td>C.I.</td>
				<td>Fecha de Nacimiento</td>
				<td>Género</td>
			</tr>
		</thead>
		<tbody>
<?php
					if (array_key_exists(0, $ws->data) === true) {
						foreach ($ws->data as $key => $data_ws) {
							data_search_client($data_ws);
						}
					} else {
						$data_ws = $ws->data;
						data_search_client($data_ws);
					}
?>
		</tbody>
	</table>
</div>
<?php
				} else {
					$err_search = $ws->message;
				}
			} else {
				$sqlSc = 'select 
					scl.id_cliente,
					scl.codigo_be,
					scl.nombre as cl_nombre,
					scl.paterno as cl_paterno,
					scl.materno as cl_materno,
					scl.ap_casada as cl_ap_casada,
					scl.estado_civil as cl_estado_civil,
					scl.tipo_documento as cl_tipo_documento,
					scl.ci as cl_dni,
					scl.complemento as cl_complemento,
					scl.extension as cl_extension,
					scl.fecha_nacimiento as cl_fecha_nacimiento,
					scl.pais as cl_pais,
					scl.lugar_nacimiento as cl_lugar_nacimiento,
					scl.lugar_residencia as cl_lugar_residencia,
					scl.localidad as cl_localidad,
					scl.direccion as cl_direccion,
					scl.telefono_domicilio as cl_tel_domicilio,
					scl.telefono_celular as cl_tel_celular,
					scl.telefono_oficina as cl_tel_oficina,
					scl.email as cl_email,
					scl.id_ocupacion as cl_ocupacion,
					scl.desc_ocupacion as cl_desc_ocupacion,
					scl.genero as cl_genero,
					scl.lugar_trabajo as cl_lugar_trabajo,
					scl.mano as cl_mano
				from
					s_ap_cot_cliente as scl
						inner join 
					s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
				where
					scl.ci = "' . $dni . '"
						and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
						and sef.activado = true
				limit 0 , 1
				;';
				//echo $sqlSc;
				if(($rsSc = $link->query($sqlSc,MYSQLI_STORE_RESULT))){
					if($rsSc->num_rows === 1){
						$rowSc = $rsSc->fetch_array(MYSQLI_ASSOC);
						$rsSc->free();
						
						$dc_code = $rowSc['codigo_be'];
						$dc_name = $rowSc['cl_nombre'];
						$dc_lnpatern = $rowSc['cl_paterno'];
						$dc_lnmatern = $rowSc['cl_materno'];
						$dc_lnmarried = $rowSc['cl_ap_casada'];
						$dc_status = $rowSc['cl_estado_civil'];
						$dc_type_doc = $rowSc['cl_tipo_documento'];
						$dc_doc_id = $rowSc['cl_dni'];
						$dc_comp = $rowSc['cl_complemento'];
						$dc_ext = $rowSc['cl_extension'];
						$dc_country = $rowSc['cl_pais'];
						$dc_birth = $rowSc['cl_fecha_nacimiento'];
						$dc_place_birth = $rowSc['cl_lugar_nacimiento'];
						$dc_place_res = $rowSc['cl_lugar_residencia'];
						$dc_locality = $rowSc['cl_localidad'];
						$dc_address = $rowSc['cl_direccion'];
						$dc_occupation = $rowSc['cl_ocupacion'];
						$dc_phone_1 = $rowSc['cl_tel_domicilio'];
						$dc_desc_occ = $rowSc['cl_desc_ocupacion'];
						$dc_phone_2 = $rowSc['cl_tel_celular'];
						$dc_email = $rowSc['cl_email'];
						$dc_phone_office = $rowSc['cl_tel_oficina'];
						$dc_gender = $rowSc['cl_genero'];
						$dc_place_work = $rowSc['cl_lugar_trabajo'];
						$dc_hand = $rowSc['cl_mano'];
					}else{
						$err_search = 'El Titular no Existe !';
					}
				}else{
					$err_search = 'El Titular no Existe';
				}
			}
		} elseif(isset($_GET['code-cl'])) {
			$code_cl = $link->real_escape_string(base64_decode($_GET['code-cl']));
			$ws = new WSBaneco('DC');

			if ($ws->ws_connect(array('codigo' => $code_cl)) === true) {
				$data_ws = $ws->data;

				$data_ws['ci'] = preg_replace('/[^0-9]/', '', $data_ws['nrodocumento']);
				$data_ws['ext'] = preg_replace('/[^a-zA-Z]/', '', $data_ws['nrodocumento']);
				if (is_array($data_ws['telefono']) === true) {
					$data_ws['telefono'] = '';
				}
				if (is_array($data_ws['celular']) === true) {
					$data_ws['celular'] = '';
				}
				if (is_array($data_ws['email']) === true) {
					$data_ws['email'] = '';
				}

				if (($ext = $link->getExtenssionCode($data_ws['ext'])) !== false) {
					$data_ws['ext'] = $ext['id_depto'];
				} else {
					$data_ws['ext'] = '';
				}

				$dc_code = $data_ws['codigo'];
				$dc_name = trim($data_ws['nombres']);
				$dc_lnpatern = trim($data_ws['apellidopaterno']);
				$dc_lnmatern = trim($data_ws['apellidomaterno']);
				$dc_lnmarried = '';
				$dc_status = '';
				$dc_type_doc = '';
				$dc_doc_id = $data_ws['ci'];
				$dc_comp = '';
				$dc_ext = $data_ws['ext'];
				$dc_country = '';
				$dc_birth = date('Y-m-d', strtotime($data_ws['nacimiento']));
				$dc_place_birth = '';
				$dc_place_res = '';
				$dc_locality = '';
				$dc_address = trim($data_ws['direccion']);
				$dc_occupation = trim($data_ws['ocupacion']);
				$dc_phone_1 = trim($data_ws['telefono']);
				$dc_desc_occ = trim($data_ws['profesion']);
				$dc_phone_2 = trim($data_ws['celular']);
				$dc_email = trim($data_ws['email']);
				$dc_phone_office = '';
				$dc_gender = trim($data_ws['genero']);
				$dc_place_work = trim($data_ws['direcciontrabajo']);
				$dc_hand = '';
			} else {
				$err_search = $ws->message;
			}
		}
	}
}
?>
<div class="mess-err-sc"><?=$err_search;?></div>
<hr>
<form id="fde-customer" name="fde-customer" action="" method="post" class="form-quote form-customer">
<?php
if($swCl === false){
	if($nCl > 0){
?>
		<table class="list-cl">
			<thead>
				<tr>
					<td style="width:5%;"></td>
					<td style="width:10%;">Documento de Identidad</td>
					<td style="width:15%;">Nombres</td>
					<td style="width:16%;">Ap. Paterno</td>
					<td style="width:16%;">Ap. Materno</td>
					<td style="width:10%;">Fecha de Nacimiento</td>
					<td style="width:11%;">Género</td>
					<td style="width:5%;">% Crédito</td>
					<td style="width:12%;"></td>
				</tr>
			</thead>
			<tbody>
	<?php
		$cont = 1;
		while($rowCl = $rsCl->fetch_array(MYSQLI_ASSOC)){
			$aux_type = (int)$rowCl['tipo'];
	?>
				<tr>
					<td style="font-weight:bold;">T<?=$cont;?></td>
					<td><?=$rowCl['cl_dni'];?></td>
					<td><?=$rowCl['cl_nombre'];?></td>
					<td><?=$rowCl['cl_paterno'];?></td>
					<td><?=$rowCl['cl_materno'];?></td>
					<td><?=$rowCl['cl_fn'];?></td>
					<td><?=$rowCl['cl_genero'];?></td>
					<td><?=$rowCl['cl_pc'];?></td>
					<td>
						<a href="ap-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=
							$_GET['pr'];?>&idc=<?=$_GET['idc'];?>&idd=<?=
							base64_encode($rowCl['id_detalle']);?>&idCl=<?=
							base64_encode($rowCl['id_cliente']);?>" 
							title="Editar Información">
							<img src="img/edit-user-icon.png" width="40" height="40" 
								alt="Editar Información" title="Editar Información">
						</a>
					</td>
				</tr>
	<?php
			$cont += 1;
		}
		$rsCl->free();
	?>
			</tbody>
		</table>
		
		<div class="mess-cl">
			T1: Titular 1<br>
			T2: Titular 2
		</div>
		<input type="button" id="dc-next" name="dc-next" value="Continuar" class="btn-next" >
		<hr>
	<?php
	}
}

if($nCl < $max_item || $swCl === true){
	$check01 = $check02 = '';
	if ($nCl < 1) {
		TypeClient:
		if ($dc_type === 0) {
			$check02 = 'checked';
		} elseif ($dc_type === 1) {
			$check01 = 'checked';
		} else {
			$check02 = 'checked';
		}
	} else {
		$dc_type = $aux_type;
		goto TypeClient;
	}

	$display_type = 'display: block;';
	if ($nCl > 0) {
		$display_type = 'display: none;';
	}
?>
	<div class="form-col">
		<input type="hidden" id="dc-code" name="dc-code" 
			value="<?=base64_encode($dc_code);?>">

		<label>Nombres: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-name" name="dc-name" autocomplete="off" 
				value="<?=$dc_name;?>" class="required text fbin">
		</div><br>		
		
		<label>Apellido Paterno: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-ln-patern" name="dc-ln-patern" autocomplete="off" 
				value="<?=$dc_lnpatern;?>" class="required text fbin">
		</div><br>
		
		<label>Apellido Materno: </label>
		<div class="content-input">
			<input type="text" id="dc-ln-matern" name="dc-ln-matern" autocomplete="off" 
				value="<?=$dc_lnmatern;?>" class="not-required text fbin">
		</div><br>
		
		<label>Apellido de Casada: </label>
		<div class="content-input">
			<input type="text" id="dc-ln-married" name="dc-ln-married" autocomplete="off" 
				value="<?=$dc_lnmarried;?>" class="not-required text fbin">
		</div><br>
		
		<label>Documento de Identidad: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-doc-id" name="dc-doc-id" autocomplete="off" 
				value="<?=$dc_doc_id;?>" class="required number fbin">
		</div><br>
		
		<label>Complemento: </label>
		<div class="content-input">
			<input type="text" id="dc-comp" name="dc-comp" autocomplete="off" 
				value="<?=$dc_comp;?>" class="not-required dni fbin" style="width:60px;">
		</div><br>
		
		<label>Extensión <span>*</span></label>
		<div class="content-input">
			<select id="dc-ext" name="dc-ext" class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$rsDep = null;
if (($rsDep = $link->get_depto()) === false) {
	$rsDep = null;
}
if ($rsDep->data_seek(0) === true) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === true){
			if($rowDep['id_depto'] === $dc_ext) {
				echo '<option value="' . $rowDep['id_depto'] . '" selected>' 
					. $rowDep['departamento'].'</option>';
			} else {
				echo '<option value="' . $rowDep['id_depto'] . '">' 
					. $rowDep['departamento'] . '</option>';
			}
		}
	}
}
?>
			</select>
		</div><br>
		
		<label>Género: <span>*</span></label>
		<div class="content-input">
			<select id="dc-gender" name="dc-gender" class="required fbin">
				<option value="">Seleccione...</option>
<?php
$arr_gender = $link->gender;
for($i = 0; $i < count($arr_gender); $i++){
	$gender = explode('|',$arr_gender[$i]);
	if($gender[0] === $dc_gender) {
		echo '<option value="' . $gender[0] . '" selected>' . $gender[1] . '</option>';
	} else {
		echo '<option value="' . $gender[0] . '">' . $gender[1] . '</option>';
	}
}
?>
			</select>
		</div><br>

		<label>Fecha de Nacimiento: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-date-birth" name="dc-date-birth" autocomplete="off" 
				value="<?=$dc_birth;?>" class="required fbin" readonly style="cursor:pointer;">
		</div><br>
		
		<label>Lugar de Nacimiento: </label>
		<div class="content-input">
			<input type="text" id="dc-place-birth" name="dc-place-birth" autocomplete="off" 
				value="<?=$dc_place_birth;?>" class="not-required fbin">
		</div><br>
		
        <label>Lugar de Trabajo: </label>
		<div class="content-input">
			<input type="text" id="dc-work" name="dc-work" autocomplete="off" 
				value="<?=$dc_place_work;?>" class="not-required fbin">
		</div><br>
	</div><!--
	--><div class="form-col">
		<label>Dirección domicilio: </label><br>
		<textarea id="dc-address" name="dc-address" 
			class="not-required fbin"><?=$dc_address;?></textarea>
		<br>    
		
		<label>Teléfono 1: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-phone-1" name="dc-phone-1" autocomplete="off" 
				value="<?=$dc_phone_1;?>" class="required phone fbin">
		</div><br>
		
		<label>Teléfono 2: </label>
		<div class="content-input">
			<input type="text" id="dc-phone-2" name="dc-phone-2" autocomplete="off" 
				value="<?=$dc_phone_2;?>" class="not-required phone fbin">
		</div><br>
		
		<label>Teléfono oficina: </label>
		<div class="content-input">
			<input type="text" id="dc-phone-office" name="dc-phone-office" autocomplete="off" 
				value="<?=$dc_phone_office;?>" class="not-required phone  fbin">
		</div><br>
		
		<label>Email: </label>
		<div class="content-input">
			<input type="text" id="dc-email" name="dc-email" autocomplete="off" 
				value="<?=$dc_email;?>" class="not-required email fbin">
		</div><br>
		
        <label>Ocupación: </label>
		<div class="content-input">
			<select id="dc-occupation" name="dc-occupation" class="not-required fbin">
				<option value="">Seleccione...</option>
<?php
if (($rsOcc = $link->get_occupation($_SESSION['idEF'], 'AP')) !== false) {
	while($rowOcc = $rsOcc->fetch_array(MYSQLI_ASSOC)){
		if($rowOcc['id_ocupacion'] === $dc_occupation) {
			echo '<option value="' . base64_encode($rowOcc['id_ocupacion']) 
				. '" selected>' . $rowOcc['ocupacion'] . '</option>';
		} else {
			echo '<option value="' . base64_encode($rowOcc['id_ocupacion']) 
				. '">' . $rowOcc['ocupacion'] . '</option>';
		}
	}
}
?>
			</select>
		</div><br>
		
		<label style="width:auto;">
			¿Cuál es su profesión u ocupación habitual?: <span>*</span></label>
		<br>
		<textarea id="dc-desc-occ" name="dc-desc-occ" 
			class="required fbin"><?=$dc_desc_occ;?></textarea><br>
        
        <label>Mano utilizada para escribir y/o firmar: <span>*</span></label>
		<div class="content-input">
			<select id="dc-hand" name="dc-hand" 
				class="required fbin">
            	<option value="">Seleccione...</option>
<?php
$arr_Ha = $link->hand;
for ($i = 0; $i < count($arr_Ha); $i++) {
	$ha = explode('|',$arr_Ha[$i]);
	if ($ha[0] === $dc_hand) {
		echo '<option value="' . $ha[0] . '" selected>' . $ha[1] . '</option>';
	} else {
		echo '<option value="' . $ha[0] . '">' . $ha[1] . '</option>';
	}
}
?>
            </select>
		</div><br>
        <label>Plan: <span>*</span></label>
		<div class="content-input">
			<select id="dc-plan" name="dc-plan" 
				class="required fbin ">
				<option value="">Seleccione...</option>
<?php
if (($rsPl = $link->get_plan($_SESSION['idEF'], 'AP')) !== false) {
	while ($rowPl = $rsPl->fetch_array(MYSQLI_ASSOC)) {
		if ($rowPl['id_plan'] === $dc_plan) {
			echo '<option value="' . $rowPl['id_plan'] . '" selected>' 
				. $rowPl['nombre'] . '</option>';
		} else {
			echo '<option value="' . $rowPl['id_plan'] . '">' 
				. $rowPl['nombre'] . '</option>';
		}
	}
}
?>
			</select>
		</div><br>
	</div><br>
    
    <div id="content-plan">
<?php
$plan_dis = '';

if (($rsPl = $link->get_plan($_SESSION['idEF'], 'AP')) !== false) {
	while ($rowPl = $rsPl->fetch_array(MYSQLI_ASSOC)) {
		if ($dc_plan === $rowPl['id_plan']) {
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
    </div>
    
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=base64_encode('AP|02');?>">
	<input type="hidden" id="dc-idc" name="dc-idc" value="<?=$_GET['idc'];?>" >
	<input type="hidden" id="dc-token" name="dc-token" value="<?=base64_encode('dc-OK');?>" >
    <input type="hidden" id="id-ef" name="id-ef" value="<?=$_SESSION['idEF'];?>" >
<?php
if($swCl === true) {
	echo '<input type="hidden" id="dc-idCl" name="dc-idCl" value="' . $_GET['idCl'] . '" >
		<input type="hidden" id="dc-idd" name="dc-idd" value="' . $_GET['idd'] . '" >';
}
?>
	<input type="submit" id="dc-customer" name="dc-customer" value="<?=$title_btn;?>" class="btn-next" >
<?php
}
?>	
	<div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(e) {
	$("#dc-plan").change(function(e){
		var plan = $(this).prop('value');
		
		$('.plan-detail').hide();

		if (plan !== '') {
			$('#' + plan).show();
		}
	});
	
	$("#fde-customer").validateForm({
		action: 'AP-customer-record.php'
	});
	
	$("#dc-date-birth").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$("#dc-date-birth").datepicker($.datepicker.regional[ "es" ]);
	
	$("#dc-next").click(function(e){
		e.preventDefault();
		location.href = 'ap-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=base64_encode('AP|03');?>&idc=<?=$_GET['idc']?>';
	});
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
		var _val = $(this).prop('value');
		
		if ($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false) {
			$(this).prop('value',_val.toUpperCase());
		}
	});
	
	
});
</script>
<?php 
function data_search_client($data_ws) {
	switch ($data_ws['genero']) {
	case 'M':
		$data_ws['genero'] = 'Masculino';
		break;
	case 'F':
		$data_ws['genero'] = 'Femenino';
		break;
	}

	$data_ws['nacimiento'] = date('d/m/Y', strtotime($data_ws['nacimiento']));
?>
<tr>
	<td>
		<a href="?ms=<?=$_GET['ms'];?>&page=<?=
			$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=
			$_GET['idc'];?>&code-cl=<?=base64_encode($data_ws['codigo']);?>" 
			tittle="Codigo de Cliente" class="code-cl">
			<?=$data_ws['codigo'];?>
		</a>
	</td>
	<td><?=$data_ws['nombrecompleto'];?></td>
	<td><?=$data_ws['nrodocumento'];?></td>
	<td><?=$data_ws['nacimiento'];?></td>
	<td><?=$data_ws['genero'];?></td>
</tr>
<?php
}
?>