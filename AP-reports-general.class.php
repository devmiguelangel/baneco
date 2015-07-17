<?php

require_once('sibas-db.class.php');

class ReportsGeneralAP{
	private 
		$cx, 
		$sql, 
		$rs, 
		$row, 
		$sqlcl, 
		$rscl, 
		$rowcl, 
		$pr, 
		$flag, 
		$token,	
		$nEF, 
		$dataToken, 
		$xls, 
		$xlsTitle, 
		$statement;

	protected $data = array();
	public $err;

	public function ReportsGeneralAP($data, $pr, $flag, $xls){
		$this->cx 		= new SibasDB();
		$this->pr 		= $this->cx->real_escape_string(trim(base64_decode($pr)));
		$this->flag 	= $this->cx->real_escape_string(trim($flag));
		$this->xls 		= $xls;
		$this->statement = 0;

		$this->set_variable($data);
		$this->get_query_report();
	}

	private function set_variable($data){
		$this->data['ms'] = $this->cx->real_escape_string(trim($data['ms']));
		$this->data['page'] = $this->cx->real_escape_string(trim($data['page']));
		//$this->data[''] = $this->cx->real_escape_string(trim($data['']));

		$this->data['idef'] = $this->cx->real_escape_string(trim(base64_decode($data['idef'])));
		$this->data['nc'] = $this->cx->real_escape_string(trim($data['r-nc']));
		if(empty($this->data['nc']) === TRUE) $this->data['nc'] = '%%';
		$this->data['user'] = $this->cx->real_escape_string(trim($data['r-user']));
		$this->data['subsidiary'] = $this->cx->real_escape_string(trim(base64_decode($data['r-subsidiary'])));
		if (empty($this->data['subsidiary']) === true) {
			$this->data['subsidiary'] = '%' . $this->data['subsidiary'] . '%';
		}
		$this->data['agency'] = $this->cx->real_escape_string(trim(base64_decode($data['r-agency'])));
		$this->data['client'] = $this->cx->real_escape_string(trim($data['r-client']));
		$this->data['dni'] = $this->cx->real_escape_string(trim($data['r-dni']));
		$this->data['comp'] = $this->cx->real_escape_string(trim($data['r-comp']));
		$this->data['ext'] = $this->cx->real_escape_string(trim($data['r-ext']));
		$this->data['date-begin'] = $this->cx->real_escape_string(trim($data['r-date-b']));
		$this->data['date-end'] = $this->cx->real_escape_string(trim($data['r-date-e']));
		$this->data['policy'] = $this->cx->real_escape_string(trim(base64_decode($data['r-policy'])));
		$this->data['r-pendant'] = $this->cx->real_escape_string(trim($data['r-pendant']));
		$this->data['r-state'] = $this->cx->real_escape_string(trim($data['r-state']));
		$this->data['r-free-cover'] = $this->cx->real_escape_string(trim($data['r-free-cover']));
		$this->data['r-extra-premium'] = $this->cx->real_escape_string(trim($data['r-extra-premium']));
		$this->data['r-issued'] = $this->cx->real_escape_string(trim($data['r-issued']));
		$this->data['r-rejected'] = $this->cx->real_escape_string(trim($data['r-rejected']));
		$this->data['r-canceled'] = $this->cx->real_escape_string(trim($data['r-canceled']));
		// $this->data['r-clientbs'] = $this->cx->real_escape_string(trim($data['r-clientbs']));
		$this->data['r-state-account'] = $this->cx->real_escape_string(trim($data['r-state-account']));
		$this->data['r-mora'] = $this->cx->real_escape_string(trim($data['r-mora']));
		$this->data['preprinted'] = $this->cx->real_escape_string(trim($data['r-preprinted']));
		$this->data['no_preprinted'] = $this->cx->real_escape_string(trim($data['r-no_preprinted']));
		if ((int)$this->data['preprinted'] !== 1) {
			$this->data['no_preprinted'] = '';
		}

		$this->data['idUser'] = $this->cx->real_escape_string(trim(base64_decode($data['r-idUser'])));

		$this->data['ef'] = '';
		$this->nEF = 0;
		if(($rsEf = $this->cx->get_financial_institution_user(base64_encode($this->data['idUser']))) !== FALSE){
			$this->nEF = $rsEf->num_rows;
			$k = 0;
			while($rowEf = $rsEf->fetch_array(MYSQLI_ASSOC)){
				$k += 1;
				$this->data['ef'] .= 'sef.id_ef like "'.$rowEf['idef'].'"';
				if($k < $this->nEF) $this->data['ef'] .= ' or ';
			}
			$rsEf->free();
		}else
			$this->data['ef'] = 'sef.id_ef like "%%"';
	}

	private function get_query_report(){
		switch($this->flag){
		case md5('RG'):
			$this->token = 'RG';
			$this->xlsTitle = 'Accidentes Personales - Reporte General'; break;
		case md5('RP'):
			$this->token = 'RP';
			$this->xlsTitle = 'Accidentes Personales - Reporte Polizas Emitidas'; break;
		case md5('RQ'):
			$this->token = 'RQ';
			$this->xlsTitle = 'Accidentes Personales - Reporte Cotizaciones'; break;
		case md5('IQ'):
			$this->token = 'IQ';
			$this->xlsTitle = 'Accidentes Personales - Cotizaciones'; break;
		case md5('PA'):
			$this->token = 'PA';
			$this->xlsTitle = 'Accidentes Personales - Reporte Polizas Preaprobadas'; break;
		case md5('AN'):
			$this->token = 'AN';
			$this->xlsTitle = 'Accidentes Personales - Reporte Polizas Emitidas'; break;
		case md5('IM'):
			$this->token = 'IM';
			$this->xlsTitle = 'Accidentes Personales - Preaprobadas'; break;
		case md5('UF'):
			$this->token = 'UF';
			// $this->xlsTitle = 'Reporte Polizas Emitidas'; 
			break;
		case md5('RC'):
			$this->token = 'RC';
			$this->xlsTitle = 'Accidentes Personales - Reporte Cobranzas'; break;
		}

		if($this->token === 'RG' ||
			$this->token === 'RP' ||
			$this->token === 'PA' ||
			$this->token === 'AN' ||
			$this->token === 'IM' ||
			$this->token === 'AP' ||
			$this->token === 'UF' ||
			$this->token === 'RC'){

			$this->set_query_de_report();
		}elseif($this->token === 'RQ' || $this->token === 'IQ'){
			$this->set_query_de_report_quote();
		}else
			$this->err = TRUE;
	}

	private function set_query_de_report(){
		switch($this->token){
		case 'RG': $this->dataToken = 2; break;
		case 'RP': 
			$this->dataToken = 2; 
			$this->statement = 1;
			break;
		case 'PA': $this->dataToken = 3; break;
		case 'AN': $this->dataToken = 4; break;
		case 'IM': $this->dataToken = 5; break;
		case 'AP': $this->dataToken = 2; break;
		case 'UF': $this->dataToken = 4; break;
		case 'RC': $this->dataToken = 2; break;
		}

		$this->sql = "select
			sde.id_emision as ide, ";
		if ($this->token === 'RC') {
			$this->sql .= "1 as no_cl,
			sac.numero_cuota,
			date_format(sac.fecha_cuota, '%d/%m/%Y') as fecha_cuota,
			if(sac.fecha_transaccion = '0000-00-00', 
				'', 
				date_format(sac.fecha_transaccion, '%d/%m/%Y')) as fecha_transaccion,
			if(datediff(curdate(), sac.fecha_cuota) > 0 
					and sac.fecha_transaccion = '0000-00-00', 
				datediff(curdate(), sac.fecha_cuota), 0) as dias_mora,
			(case
				when sac.fecha_transaccion != '0000-00-00'
					then 'P'
				when datediff(curdate(), sac.fecha_cuota) < 0
					then 'V'
				when datediff(curdate(), sac.fecha_cuota) > 90
					then 'N'
				when datediff(curdate(), sac.fecha_cuota) >= 0
					then 'M'
				else
					''
			end) as estado_cuenta, ";
		} else {
			$this->sql .= "count(sc.id_cliente) as no_cl, ";
		}
		$this->sql .= "	
			sef.id_ef as idef,
			sef.nombre as ef_nombre,
			sde.no_emision as r_no_emision,
			sde.no_poliza as r_no_poliza,
			sde.prefijo as r_prefijo,
			sde.pre_impreso,
			sde.no_preprinted,
			sde.id_compania,
			su.nombre as r_creado_por,
			date_format(sde.fecha_creacion, '%d/%m/%Y') as r_fecha_creacion,
			sdep.departamento as r_sucursal,
			sag.agencia as r_agencia,
			sde.anulado,
			(case sde.anulado
				when 1 then 'SI'
				when 0 then 'NO'
			end) as r_anulado,
			if(sde.anulado = true, sua.nombre, '') as r_anulado_nombre,
			if(sde.anulado = true,
				date_format(sde.fecha_anulado, '%d/%m/%Y'),
				'') as r_anulado_fecha,
			(select
					count(sde1.id_emision)
				from
					s_de_em_cabecera as sde1
				where
					sde1.id_cotizacion = sde.id_cotizacion
						and sde1.anulado = true) as r_num_anulado,
			if(sde.anulado = 1,
				1,
				if(sde.emitir = 1, 2, 3)) as estado_banco,
			if(sde.fecha_emision != '0000-00-00',
				datediff(sde.fecha_emision, sde.fecha_creacion),
				0) as duracion_caso,
			(case sde.cobertura
				when 1 then 'Individual'
				when 2 then 'Mancomuno'
			end) as cobertura,
			sde.estado,
			sde.prima as prima,
			(case sde.forma_pago
				when 'CO' then 'Pago al Contado'
				when 'DA' then 'Débito Automatico'
				when 'DM' then 'Débito Manual'
			end) as forma_pago,
			(case sde.periodo
				when 'Y' then 'Anual'
				when 'M' then 'Mensual'
			end) as periodo,
			sde.po_archivo
		from
			s_ap_em_cabecera as sde
				inner join
			s_ap_em_detalle as sdd ON (sdd.id_emision = sde.id_emision)
				inner join
			s_cliente as sc ON (sc.id_cliente = sdd.id_cliente) ";
		if ($this->token === 'RC') {
			$this->sql .= "inner join
			s_ap_cobranza as sac ON (sac.id_emision = sde.id_emision) ";
		}
		$this->sql .= "
				inner join
			s_usuario as su ON (su.id_usuario = sde.id_usuario)
				left join
			s_departamento as sdep ON (sdep.id_depto = su.id_depto)
				left join
			s_agencia as sag ON (sag.id_agencia = su.id_agencia)
				inner join
			s_usuario as sua ON (sua.id_usuario = sde.and_usuario)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sde.id_ef)
		where
			sef.id_ef = '".$this->data['idef']."'
				and sde.no_poliza like '".$this->data['nc']."'
				and (".$this->data['ef'].")

				and (su.usuario like '%".$this->data['user']."%'
				or su.nombre like '".$this->data['user']."'
				or su.usuario like '%".$this->data['idUser']."%')
				and concat(sc.nombre,
					' ',
					sc.paterno,
					' ',
					sc.materno) like '%".$this->data['client']."%'
				and sc.ci like '%".$this->data['dni']."%'
				and sc.complemento like '%".$this->data['comp']."%'
				and sc.extension like '%".$this->data['ext']."%'
				and sde.fecha_creacion between '".$this->data['date-begin']."' and '".$this->data['date-end']."'
				and (sdep.id_depto like '" . $this->data['subsidiary'] . "'
					or sdep.id_depto is null)
				and (sag.id_agencia like '%" . $this->data['agency'] . "%'
					or sag.id_agencia is null) ";
		if($this->token === 'RG'){
			//and sde.id_poliza like '%".$this->data['policy']."%'
			$this->sql .= "and if(sde.emitir = true,
				'EM', 'NE') regexp '".$this->data['r-issued']."'
				and sde.anulado like '%".$this->data['r-canceled']."%'
				and if(sde.emitir = true, sde.estado, 'R') regexp '".$this->data['r-state-account']."'
				and sde.pre_impreso like '%" . $this->data['preprinted'] . "%'
				and sde.no_preprinted like '%" . $this->data['no_preprinted'] . "%'
				";
		}elseif($this->token === 'RP'){
			$this->sql .= "and sde.emitir = true
				and sde.anulado like '%".$this->data['r-canceled']."%'
				and sde.pre_impreso like '%" . $this->data['preprinted'] . "%'
				and sde.no_preprinted like '%" . $this->data['no_preprinted'] . "%'
				";
		}elseif($this->token === 'PA'){
			$this->sql .= "and sde.emitir = false
							and sde.anulado like '%".$this->data['r-canceled']."%'
							";
		}elseif($this->token === 'AN' || $this->token === 'UF'){
			$this->sql .= "and sde.emitir = true
							and sde.anulado like '%".$this->data['r-canceled']."%'
							";
		}elseif($this->token === 'AP'){
			$this->sql .= "and sde.emitir = false
					and (if(sde.aprobado = true,
			        'A',
			        if(sde.aprobado = false,
			            'R',
			            ''))) regexp '".$this->data['approved']."'
					and sde.anulado like '%".$this->data['r-canceled']."%'
					";
		}elseif($this->token === 'IM'){
			$idUser = base64_encode($this->data['idUser']);
			$idef = base64_encode($this->data['idef']);
			$sqlAg = '';
			if (($rsAg = $this->cx->get_agency_implant($idUser, $idef)) !== FALSE) {
				$sqlAg = ' and (';
				while ($rowAg = $rsAg->fetch_array(MYSQLI_ASSOC)) {
					$sqlAg .= 'sag.id_agencia = "'.$rowAg['ag_id'].'" or ';
				}
				$sqlAg = trim($sqlAg, 'or ').') ';
				$rsAg->free();
			}

			$this->sql .= $sqlAg." and sde.emitir = false
					and sde.anulado like '%".$this->data['r-canceled']."%'
					and sde.aprobado = false
					and sde.rechazado = false
					and not exists( select
						saf2.id_emision
					from
						s_de_facultativo as saf2
					where
						saf2.id_emision = sde.id_emision ) 
					";
		} elseif ($this->token === 'RC') {
			$this->sql .= "and sde.emitir = true
				and sde.anulado = false
				and (case
					when sac.fecha_transaccion != '0000-00-00'
						then 'P'
					when datediff(curdate(), sac.fecha_cuota) < 0
						then 'V'
					when datediff(curdate(), sac.fecha_cuota) > 90
						then 'N'
					when datediff(curdate(), sac.fecha_cuota) >= 0
						then 'M'
					else
						''
				end) regexp '" . $this->data['r-state-account'] . "'
			";
			if (empty($this->data['r-mora']) === false && $this->token === 'RC') {
				$this->sql .= "and datediff(curdate(), sac.fecha_cuota) 
					between " . $this->cx->days_mora[$this->data['r-mora']][0] . " 
						and " . $this->cx->days_mora[$this->data['r-mora']][1] . " 
				";
			}
		}

		if ($this->token !== 'RC') {
			$this->sql .= "group by sde.id_emision ";
		}
		$this->sql .= "order by sde.id_emision desc
		;";
		// echo $this->sql;
		if(($this->rs = $this->cx->query($this->sql, MYSQLI_STORE_RESULT))){
			$this->err = FALSE;
		}else{
			$this->err = TRUE;
		}

	}

	private function set_query_de_report_quote(){
		switch($this->token){
			case 'RQ': $this->dataToken = 2; break;
			case 'IQ': $this->dataToken = 3; break;
		}
		$this->sql = "select
			sdc.id_cotizacion as idc,
			sef.id_ef as idef,
			sef.nombre as ef_nombre,
			count(sc.id_cliente) as no_cl,
			sdc.no_cotizacion as r_no_cotizacion,
			su.nombre as r_creado_por,
			date_format(sdc.fecha_creacion, '%d/%m/%Y') as r_fecha_creacion,
			sdep.departamento as r_sucursal,
			sag.agencia as r_agencia,
			(case sdc.cobertura
				when 1 then 'Individual'
				when 2 then 'Mancomuno'
			end) as cobertura
		from
			s_ap_cot_cabecera as sdc
				inner join
			s_ap_cot_detalle as sdd ON (sdd.id_cotizacion = sdc.id_cotizacion)
				inner join
			s_ap_cot_cliente as sc ON (sc.id_cliente = sdd.id_cliente)
				inner join
			s_usuario as su ON (su.id_usuario = sdc.id_usuario)
				inner join
			s_departamento as sdep ON (sdep.id_depto = su.id_depto)
				left join
			s_agencia as sag ON (sag.id_agencia = su.id_agencia)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
		where
			sef.id_ef = '".$this->data['idef']."'
				and sdc.no_cotizacion like '".$this->data['nc']."'
				and (".$this->data['ef'].")
				and (su.usuario like '%".$this->data['user']."%'
				or su.nombre like '%".$this->data['user']."%'
				or su.usuario like '%".$this->data['idUser']."%')
				and concat(sc.nombre,
					' ',
					sc.paterno,
					' ',
					sc.materno) like '%".$this->data['client']."%'
				and sc.ci like '%".$this->data['dni']."%'
				and sc.complemento like '%".$this->data['comp']."%'
				and sc.extension like '%".$this->data['ext']."%'
				and sdc.fecha_creacion between '".$this->data['date-begin']."' and '".$this->data['date-end']."'
				and (exists( select
					sde1.id_cotizacion
				from
					s_de_em_cabecera as sde1
				where
					sde1.id_cotizacion = sdc.id_cotizacion
						and sde1.anulado = true
						and sde1.emitir = true)
				or not exists( select
					sde1.id_cotizacion
				from
					s_de_em_cabecera as sde1
				where
					sde1.id_cotizacion = sdc.id_cotizacion))";
		if ($this->token !== 'RQ') {
			$this->sql .= 'and (exists( select
					sde1.id_cotizacion
				from
					s_ap_em_cabecera as sde1
				where
					sde1.id_cotizacion = sdc.id_cotizacion
						and sde1.anulado = true
						and sde1.emitir = true)
				or not exists( select
					sde1.id_cotizacion
				from
					s_ap_em_cabecera as sde1
				where
					sde1.id_cotizacion = sdc.id_cotizacion)) ';
		}
		$this->sql .= "
		group by sdc.id_cotizacion
		order by sdc.id_cotizacion desc
		;";
		//echo $this->sql;
		if(($this->rs = $this->cx->query($this->sql,MYSQLI_STORE_RESULT))){
			$this->err = FALSE;
		}else{
			$this->err = TRUE;
		}
	}

	public function set_result(){
		if($this->xls === true){
			header("Content-Type:   application/vnd.ms-excel; charset=iso-8859-1");
			header("Content-Disposition: attachment; filename=".$this->xlsTitle.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		if($this->token === 'RG' ||
			$this->token === 'RP' ||
			$this->token === 'PA' ||
			$this->token === 'AN' ||
			$this->token === 'IM' ||
			$this->token === 'AP' ||
			$this->token === 'UF' ||
			$this->token === 'RC'){

			$this->set_result_de();
		}elseif($this->token === 'RQ' || $this->token === 'IQ'){
			$this->set_result_de_quote();
		}
	}

	//EMISION
	private function set_result_de(){
		//echo $this->data['idef'];
?>
<?php if ($this->token !== 'UF'): ?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".row").reportCxt({
    	product: 'AP'
    });
});
</script>
<?php endif ?>
<table class="result-list" id="result-de">
	<thead>
    	<tr>
        	<td>No. de Póliza</td>
            <td>Entidad Financiera</td>
        	<td>Tipo de Operación</td>
        	<td>Cliente Entidad Financiera</td>
        	<td>Estado</td>
            <td>Cliente</td>
            <td>C.I.</td>
<?php if ($this->token === 'RC'): ?>
            <td>No. Cuota</td>
            <td>Fecha de Pago</td>
            <td><?=htmlentities('Fecha de Transacción');?></td>
            <td><?=htmlentities('Días en Mora');?></td>
            <td>Estado</td>
<?php else: ?>
			<td><?=htmlentities('Género');?></td>
            <td>Ciudad</td>
            <td><?=htmlentities('Teléfono');?></td>
            <td>Celular</td>
            <td>Email</td>
            <td>Deudor / Codeudor</td>
            <td>Edad</td>
<?php endif ?>
            <td>Prima</td>
            <td>Forma de Pago</td>
            <td>Periodicidad</td>
            <td>Creado Por</td>
            <td>Fecha de Ingreso</td>
            <td>Sucursal</td>
            <td>Agencia</td>
            <td>Certificados Anulados</td>
            <td>Anulado Por</td>
            <td><?=htmlentities('Fecha de Anulación');?></td>
            <td>Pre-Impreso</td>
            <td>No. Certificado Pre-Impreso</td>
            <!--<td>Días de Ultima Modificación</td>-->
<?php if ($this->token === 'RP' && $this->xls === false): ?>
			<td>Adjuntar Archivo</td>
<?php endif ?>
        </tr>
    </thead>
    <tbody>
<?php
		$swBG 		= FALSE;
		$bgCheck 	= '';
		$arr_state 	= array('txt' => '', 'action' => '', 'obs' => '', 'link' => '', 'bg' => '');
		$preprinted = array(1 => 'SI', 0 => 'NO');
		$bg_row 	= '';

		while($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)){
			$nCl = (int)$this->row['no_cl'];
			
			if ($this->token === 'RP') {
				if (empty($this->row['po_archivo'])) {
					$bg_row = 'atch_pn';
				} else {
					$bg_row = 'atch_py';
				}
			} else {
				if($swBG === FALSE){
					$bg = 'background: #EEF9F8;';
				}elseif($swBG === TRUE){
					$bg = 'background: #D1EBSA;';
				}
			}

			$rowSpan = FALSE;
			if($nCl === 2)
				$rowSpan = TRUE;

			$arr_state['txt'] = '';		$arr_state['txt_bank'] = '';	$arr_state['action'] = '';
			$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'] = '';

			// $this->cx->get_state($arr_state, $this->row, 2, 'BS', FALSE);

			if (empty($this->row['estado']) === false) {
				$this->row['estado'] = $this->cx->state_account[$this->row['estado']];
			}

			$this->sqlcl = "select
				sdc.id_cliente as idCl,
				concat(sdc.nombre,
						' ',
						sdc.paterno,
						' ',
						sdc.materno) as cl_nombre,
				sdc.ci as cl_ci,
				sdc.complemento as cl_complemento,
				sdep.codigo as cl_extension,
				sdep.departamento as cl_ciudad,
				(case sdc.genero
					when 'M' then 'Hombre'
					when 'F' then 'Mujer'
				end) as cl_genero,
				sdc.telefono_domicilio as cl_telefono,
				sdc.telefono_celular as cl_celular,
				sdc.email as cl_email,
				(case sdd.titular
					when 'DD' then 'Deudor'
					when 'CC' then 'Codeudor'
				end) as cl_titular,
				sdc.estatura as cl_estatura,
				sdc.peso as cl_peso,
				sdd.porcentaje_credito as cl_participacion,
				(year(curdate()) - year(sdc.fecha_nacimiento)) as cl_edad,
				(case sdc.tipo
					when 1 then 'SI'
					when 0 then 'NO'
				end) as cl_cliente
			from
				s_cliente as sdc
					inner join
				s_ap_em_detalle as sdd ON (sdd.id_cliente = sdc.id_cliente)
					inner join
				s_departamento as sdep ON (sdep.id_depto = sdc.extension)
			where
				sdc.id_ef = '".$this->data['idef']."'
					and sdd.id_emision = '".$this->row['ide']."'
					and concat(sdc.nombre,
						' ',
						sdc.paterno,
						' ',
						sdc.materno) like '%".$this->data['client']."%'
					and sdc.ci like '%".$this->data['dni']."%'
					and sdc.complemento like '%".$this->data['comp']."%'
					and sdc.extension like '%".$this->data['ext']."%'
			order by sdc.id_cliente asc
			limit 0 , 2
			;";

			if(($this->rscl = $this->cx->query($this->sqlcl,MYSQLI_STORE_RESULT))){
				if($this->rscl->num_rows === $nCl){
					while($this->rowcl = $this->rscl->fetch_array(MYSQLI_ASSOC)){
						if($rowSpan === TRUE){
							$rowSpan = 'rowspan="2"';
						}elseif($rowSpan === FALSE){
							$rowSpan = '';
						}elseif($rowSpan === 'rowspan="2"'){
							$rowSpan = 'style="display:none;"';
						}
						if($this->xls === TRUE) {
							$rowSpan = '';
						}
?>
		<tr style=" <?=$bg;?> " class="row <?= $bg_row ;?>" rel="0"
			data-nc="<?=base64_encode($this->row['ide']);?>"
			data-token="<?=$this->dataToken;?>"
			data-issue="<?=base64_encode(0);?>"
			data-st="<?= $this->statement ;?>" >
        	<!-- <td <?=$rowSpan;?>><?=$this->row['r_prefijo'] . '-' . $this->row['r_no_emision'];?></td> -->
			<td <?=$rowSpan;?>><?=$this->row['r_no_poliza'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['ef_nombre'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['cobertura'];?></td>
            <td <?=$rowSpan;?>><?=$this->rowcl['cl_cliente'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['estado'];?></td>
            <td><?=htmlentities($this->rowcl['cl_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->rowcl['cl_ci'].$this->rowcl['cl_complemento'] . ' ' . $this->rowcl['cl_extension'];?></td>
<?php if ($this->token === 'RC'): ?>
			<td><?=$this->row['numero_cuota'];?></td>
			<td><?=$this->row['fecha_cuota'];?></td>
            <td><?=$this->row['fecha_transaccion'];?></td>
            <td><?=$this->row['dias_mora'];?></td>
            <td><?=$this->cx->state_account[$this->row['estado_cuenta']];?></td>
<?php else: ?>
			<td><?=$this->rowcl['cl_genero'];?></td>
            <td><?=$this->rowcl['cl_ciudad'];?></td>
            <td><?=$this->rowcl['cl_telefono'];?></td>
            <td><?=$this->rowcl['cl_celular'];?></td>
            <td><?=$this->rowcl['cl_email'];?></td>
            <td><?=$this->rowcl['cl_titular'];?></td>
            <td><?=$this->rowcl['cl_edad'];?></td>
<?php endif ?>
            <td><?=$this->row['prima'];?></td>
            <td><?=$this->row['forma_pago'];?></td>
            <td><?=$this->row['periodo'];?></td>
            <td><?=htmlentities($this->row['r_creado_por'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['r_fecha_creacion'];?></td>
            <td><?=$this->row['r_sucursal'];?></td>
            <td><?=htmlentities($this->row['r_agencia'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['r_anulado'];?></td>
            <td><?=htmlentities($this->row['r_anulado_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['r_anulado_fecha'];?></td>
            <td><?= $preprinted[$this->row['pre_impreso']] ;?></td>
            <td><?= $this->row['no_preprinted'] ;?></td>
            <!--<td>Días de Ultima Modificación</td>-->
<?php if ($this->token === 'RP' && $this->xls === false): ?>
			<td style="padding: 3px 5px;">
<?php if (empty($this->row['po_archivo']) && $this->row['anulado'] == 0): ?>
				<form action="upload-file.php" method="post" enctype="multipart/form-data" 
					style="display: block; width: 300px;">
					<input type="file" name="attached" style="cursor: pointer;">
					<input type="hidden" name="product" value="<?=base64_encode('AP');?>">
					<input type="hidden" name="ide" value="<?=base64_encode($this->row['ide']);?>">
					<input type="submit" value="Subir" style="cursor: pointer;">
				</form>
<?php elseif (!empty($this->row['po_archivo'])): ?>
				<a href="<?='files/' . $this->row['po_archivo'];?>" 
					target="_blank" class="attached-link" style="width: 100px;">Ver Documentación</a>
<?php endif ?>
			</td>
<?php endif ?>
        </tr>
<?php
					}
				}
			}
			if($swBG === FALSE)
				$swBG = TRUE;
			elseif($swBG === TRUE)
				$swBG = FALSE;
		}
		$this->rs->free();
?>
    </tbody>
    <tfoot>
    	<tr>
        	<td colspan="29" style="text-align:left;">
<?php
			if($this->xls === false && $this->token !== 'UF'){
?>
				<a href="rp-records.php?data-pr=<?=base64_encode($this->pr);?>&flag=<?=
					$this->flag;?>&ms=<?=$this->data['ms'];?>&page=<?=
					$this->data['page'];?>&xls=<?=md5('true');?>&idef=<?=
					base64_encode($this->data['idef']);?>&frp-policy=<?=
					$this->data['policy'];?>&frp-nc=<?=$this->data['nc'];?>&frp-subsidiary=<?=
					base64_encode($this->data['subsidiary']);?>&frp-agency=<?=
					base64_encode($this->data['agency']);?>&frp-user=<?=
					$this->data['user'];?>&frp-client=<?=$this->data['client'];?>&frp-dni=<?=
					$this->data['dni'];?>&frp-comp=<?=$this->data['comp'];?>&frp-ext=<?=
					$this->data['ext'];?>&frp-date-b=<?=$this->data['date-begin'];?>&frp-date-e=<?=
					$this->data['date-end'];?>&frp-id-user=<?=
					base64_encode($this->data['idUser']);?>&frp-pendant=<?=
					$this->data['r-pendant'];?>&frp-state=<?=
					$this->data['r-state'];?>&frp-free-cover=<?=
					$this->data['r-free-cover'];?>&frp-extra-premium=<?=
					$this->data['r-extra-premium'];?>&frp-issued=<?=
					$this->data['r-issued'];?>&frp-rejected=<?=
					$this->data['r-rejected'];?>&frp-canceled=<?=
					$this->data['r-canceled'];?>&frp-state-account=<?=
					$this->data['r-state-account'];?>&frp-mora=<?=$this->data['r-mora'];?>"
					class="send-xls" target="_blank">
					Exportar a Formato Excel
				</a>
<?php
			}
?>
			</td>
        </tr>
    </tfoot>
</table>
<?php
	}

	//COTIZACION
	private function set_result_de_quote(){
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".row").reportCxt({
		context: '',
		product: 'AP'
	});
});
</script>
<table class="result-list" id="result-de">
	<thead>
    	<tr>
    		<td><?=htmlentities('No. de Cotización');?></td>
            <td>Entidad Financiera</td>
        	<td>Tipo de Operación</td>
        	<td>Cliente Entidad Financiera</td>
            <td>Cliente</td>
            <td>C.I.</td>
            <td><?=htmlentities('Género');?></td>
            <td>Ciudad</td>
            <td><?=htmlentities('Teléfono');?></td>
            <td>Celular</td>
            <td>Email</td>
            <td>Deudor / Codeudor</td>
            <td>Edad</td>
            <td>Creado Por</td>
            <td>Fecha de Ingreso</td>
            <td>Sucursal</td>
            <td>Agencia</td>
            <!--<td>&nbsp;</td>-->
        </tr>
    </thead>
    <tbody>
<?php
		$swBG = FALSE;
		$arr_state = array('txt' => '', 'action' => '', 'obs' => '', 'link' => '', 'bg' => '');
		$bgCheck = '';

		while($this->row = $this->rs->fetch_array(MYSQLI_ASSOC)){
			$nCl = (int)$this->row['no_cl'];
			if($swBG === FALSE){
				$bg = 'background: #EEF9F8;';
			}elseif($swBG === TRUE){
				$bg = 'background: #D1EDEA;';
			}

			$rowSpan = FALSE;
			if($nCl === 2)
				$rowSpan = TRUE;

			$arr_state['txt'] = '';		$arr_state['txt_bank'] = '';	$arr_state['action'] = '';
			$arr_state['obs'] = '';		$arr_state['link'] = '';	$arr_state['bg'];

			//$this->cx->get_state($arr_state, $this->row, 2);

			$this->sqlcl = "select
				sdc.id_cliente as idCl,
				concat(sdc.nombre,
						' ',
						sdc.paterno,
						' ',
						sdc.materno) as cl_nombre,
				sdc.ci as cl_ci,
				sdc.complemento as cl_complemento,
				sdep.codigo as cl_extension,
				sdep.departamento as cl_ciudad,
				(case sdc.genero
					when 'M' then 'Hombre'
					when 'F' then 'Mujer'
				end) as cl_genero,
				sdc.telefono_domicilio as cl_telefono,
				sdc.telefono_celular as cl_celular,
				sdc.email as cl_email,
				(case sdd.titular
					when 'DD' then 'Deudor'
					when 'CC' then 'Codeudor'
				end) as cl_titular,
				sdd.porcentaje_credito as cl_participacion,
				(year(curdate()) - year(sdc.fecha_nacimiento)) as cl_edad,
				(case sdc.tipo
					when 1 then 'SI'
					when 0 then 'NO'
				end) as cl_cliente,
				sdd.tipo_cuenta as cl_tipo_cuenta
			from
				s_ap_cot_cliente as sdc
					inner join
				s_ap_cot_detalle as sdd ON (sdd.id_cliente = sdc.id_cliente)
					inner join
				s_departamento as sdep ON (sdep.id_depto = sdc.extension)
			where
				sdc.id_ef = '".$this->data['idef']."'
					and sdd.id_cotizacion = '".$this->row['idc']."'
					and concat(sdc.nombre,
						' ',
						sdc.paterno,
						' ',
						sdc.materno) like '%".$this->data['client']."%'
					and sdc.ci like '%".$this->data['dni']."%'
					and sdc.complemento like '%".$this->data['comp']."%'
					and sdc.extension like '%".$this->data['ext']."%'
			order by sdc.id_cliente asc
			limit 0 , 2
			;";

			if(($this->rscl = $this->cx->query($this->sqlcl,MYSQLI_STORE_RESULT))){
				if($this->rscl->num_rows === $nCl){
					while($this->rowcl = $this->rscl->fetch_array(MYSQLI_ASSOC)){
						if($rowSpan === TRUE){
							$rowSpan = 'rowspan="2"';
						}elseif($rowSpan === FALSE){
							$rowSpan = '';
						}elseif($rowSpan === 'rowspan="2"'){
							$rowSpan = 'style="display:none;"';
						}

						if($this->xls === TRUE)
							$rowSpan = '';
?>
		<tr style=" <?=$bg;?> " class="row quote" rel="0"
			data-nc="<?=base64_encode($this->row['idc']);?>"
			data-token="<?=$this->dataToken;?>">
        	<td <?=$rowSpan;?>><?=$this->row['r_no_cotizacion'];?></td>
            <td <?=$rowSpan;?>><?=$this->row['ef_nombre'];?></td>
        	<td <?=$rowSpan;?>><?=$this->row['cobertura'];?></td>
        	<td <?=$rowSpan;?>><?=$this->rowcl['cl_cliente'];?></td>
            <td><?=htmlentities($this->rowcl['cl_nombre'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->rowcl['cl_ci'].$this->rowcl['cl_complemento'].' '.$this->rowcl['cl_extension'];?></td>
            <td><?=$this->rowcl['cl_genero'];?></td>
            <td><?=$this->rowcl['cl_ciudad'];?></td>
            <td><?=$this->rowcl['cl_telefono'];?></td>
            <td><?=$this->rowcl['cl_celular'];?></td>
            <td><?=$this->rowcl['cl_email'];?></td>
            <td><?=$this->rowcl['cl_titular'];?></td>
            <td><?=$this->rowcl['cl_edad'];?></td>
            <td><?=htmlentities($this->row['r_creado_por'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?=$this->row['r_fecha_creacion'];?></td>
            <td><?=$this->row['r_sucursal'];?></td>
            <td><?=htmlentities($this->row['r_agencia'], ENT_QUOTES, 'UTF-8');?></td>
            <!--<td><a href="detalle-cotizacion/detalle-certificado.php?idcotiza=<?=base64_encode($this->row['idc']);?>&cat=<?=base64_encode('AP');?>&type=PRINT" class="fancybox fancybox.ajax observation">Ver Slip de Cotización</a></td>-->
        </tr>
<?php
					}
				}
			}
			if($swBG === FALSE)
				$swBG = TRUE;
			elseif($swBG === TRUE)
				$swBG = FALSE;
		}
		$this->rs->free();
?>
    </tbody>
    <tfoot>
    	<tr>
        	<td colspan="29" style="text-align:left;">
<?php
			if($this->xls === FALSE){
?>
				<a href="rp-records.php?data-pr=<?=base64_encode($this->pr);?>&flag=<?=
					$this->flag;?>&ms=<?=$this->data['ms'];?>&page=<?=
					$this->data['page'];?>&xls=<?=md5('true');?>&idef=<?=
					base64_encode($this->data['idef']);?>&frp-policy=<?=
					$this->data['policy'];?>&frp-nc=<?=$this->data['nc'];?>&frp-user=<?=
					$this->data['user'];?>&frp-client=<?=$this->data['client'];?>&frp-dni=<?=
					$this->data['dni'];?>&frp-comp=<?=$this->data['comp'];?>&frp-ext=<?=
					$this->data['ext'];?>&frp-date-b=<?=$this->data['date-begin'];?>&frp-date-e=<?=
					$this->data['date-end'];?>&frp-id-user=<?=
					base64_encode($this->data['idUser']);?>&frp-pendant=<?=
					$this->data['r-pendant'];?>&frp-state=<?=
					$this->data['r-state'];?>&frp-free-cover=<?=
					$this->data['r-free-cover'];?>&frp-extra-premium=<?=
					$this->data['r-extra-premium'];?>&frp-issued=<?=
					$this->data['r-issued'];?>&frp-rejected=<?=
					$this->data['r-rejected'];?>&frp-canceled=<?=$this->data['r-canceled'];?>"
					class="send-xls" target="_blank">
					Exportar a Formato Excel
				</a>
<?php
			}
?>
			</td>
        </tr>
    </tfoot>
</table>
<?php

	}
}
?>