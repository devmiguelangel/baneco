<?php  
//include('configuration.class.php');
include('sibas-db.class.php');
/*$con= new ConfigurationSibas();
$host = $con->host;
$user = $con->user;
$password = $con->password;
$db = $con->db;

$conexion = mysql_connect($host, $user, $password) or die ("Fallo en el establecimiento de la conexi&oacute;n");
mysql_select_db($db,$conexion);*/
//echo $conexion;
    
$link = new SibasDB();
$ca=$cv=0;
	
	$cur_dia=date('d');//'01';
	$cur_mes=date('m');//'01';
	$cur_anio=date('Y');//'2014';
	$nom_dia=date('D');//'Wen';
	
	if($nom_dia=='Mon'){
		//Es Lunes
		$ant_dia=$cur_dia-3;
	}else{
		//No es Lunes
		$ant_dia=$cur_dia-1;
	}
	
	if($ant_dia<=0){
		//echo 'Es inicio de mes<br />';
		$ant_mes=$cur_mes-1;
		$ant_anio=$cur_anio;
		
		if($ant_mes==0){
			//echo 'Es Enero<br />';
			$ant_anio=$cur_anio-1;
			$ant_mes=12;
		}
		
		//Sacamos el ultimo dia del mes
		$new_dia=fecha($ant_mes,$ant_anio);
		
		$ant_dia=$new_dia + $ant_dia;
			
	}else{
		//echo 'No es inicio de mes<br />';
		$ant_mes=$cur_mes;
		$ant_anio=$cur_anio;
	}
	
	//echo 'Fecha resultado: '.$dia.'-'.$mes.'-'.$anio;
	//echo 'Fecha Anterior: '.$ant_anio.'-'.$ant_mes.'-'.$ant_dia;//$fecha_ant=date($anio.'-'.$mes.'-'.$dia);
	//$fecha_ant=date('2014-09-12');
	//echo '<br />Fecha Actual: '.$cur_anio.'-'.$cur_mes.'-'.$cur_dia;//$fecha_act=date('Y-m-d');
	
	function fecha($month, $year){
		switch ($month):
			case 1: case 3: case 5: case 7: case 8: case 10: case 12: return 31;
			case 4: case 6: case 9: case 11: return 30;			
			case 2:
				$val=bisiesto($year);
			return $val;
		endswitch;
	}
	
	function bisiesto($year){		
		if(($year % 4 == 0) && (($year % 100 != 0) || ($year % 400 == 0))){
			return 29;
		}else{
			return 28;
		}
	}
	
	$date2=date($ant_anio.'-'.$ant_mes.'-'.$ant_dia);
	
	$dia = date('d') - 1;
	$date = date('Y-m-'.$dia);

	
$sap="SELECT 
	case sag.id_depto when 1 then 3 when 4 then 2 when 5 then 5 when 6 then 6 when 7 then 1 when 8 then 7 end as p_venta, 
	1 as p_ingresada, sca.no_poliza, Date_format(sca.fecha_emision,'%d/%m/%Y') as f_emision, 
	Date_format(sca.fecha_creacion,'%d/%m/%Y') as f_ingreso, if(sde.tomador_ci_nit = ' ', 'BSCI', 'CI') as tipo_doc_tom, 
	sde.tomador_ci_nit, if(sca.factura_nit = ' ', 'BSCI', 'CI') as  tipo_doc_fac, sca.factura_nit, 
	case sdep.id_depto when 1 then 3 when 4 then 2 when 5 then 5 when 6 then 6 when 7 then 4 when 8 then 7 end as sucursal, sp.plan, case sca.periodo when 'Y' then 1 when 'M' then 2 end as periodo, sca.no_emision, 0 as n_familiar, 'N' as beneficiario, 0 as parent, 'BSCI' as tipo_doc_ben, 0 as ci_ben, 
	sc.genero, sc.paterno, sc.materno, sc.nombre, Date_format(sc.fecha_nacimiento,'%d/%m/%Y') as f_nac, if(sc.extension = 11, 'CIE',  if(sc.complemento != '', if(sc.complemento = sd.codigo, 'CI','CID'), 'CI')) as tipo_doc_asoc, if(sc.extension = 11, concat('E-', sc.ci),if(sc.complemento = sd.codigo, concat(sc.ci, sd.codigo), if(sc.complemento != '', concat(sc.ci, '-', sc.complemento, sd.codigo), concat(sc.ci, sd.codigo)))) as id_asoc, sp.plan as base_aseg, 337 as tipo_dir, 
	sc.direccion, case sdep.id_depto when 1 then 4 when 2 then 5 when 3 then 7 when 4 then 3 when 5 then 2 when 6 then 9 when 7 then 8 when 8 then 1 when 9 then 6 end as departamento, case sdep.id_depto when 1 then 75 when 2 then 181 when 3 then 217 when 4 then 126 when 5 then 17 when 6 then 243 when 7 then 297 when 8 then 322 when 9 then 326 end as ciudad, sc.email, sc.telefono_domicilio, sc.telefono_celular, 0 as part, sc.desc_ocupacion, case sca.forma_pago when 'DA' then 15 when 'DM' then 15 when 'CO' then 14 end as forma_pago, if(sca.forma_pago = 'CO', 5, 4) as conducto, 
	if(sca.forma_pago = 'CO', '', 1) as banco, if(sca.forma_pago = 'CO', 'CRE', 'AHO') as tipo_cuenta, if(sde.cuenta_1 = 0, ' ', sde.cuenta_1) as cuenta_1, sde.tarjeta, if(sca.forma_pago = 'CO', 9, 1) as canal, sag.codigo as agencia, sc.ci, 1 as convenio, case sp.nombre when 'Plan A' then 200 when 'Plan B' then 201 when 'Plan C' then 202 when 'Plan D' then 203 end as n_plan, 19 as red_b, sde.id_detalle
	
from 
						s_ap_em_cabecera as sca
							inner join 
						s_ap_em_detalle as sde ON (sde.id_emision = sca.id_emision)
							inner join
						s_cliente as sc ON (sc.id_cliente = sde.id_cliente)
							inner join
						s_plan as sp ON (sp.id_plan = sca.id_plan)													
							inner join
						s_usuario as sus ON (sus.id_usuario = sca.id_usuario)	
							inner join
						s_departamento as sdep ON (sdep.id_depto = sus.id_depto)
							inner join
						s_tipo_cambio as stc ON (stc.id_ef = sca.id_ef)							
							left outer join
						s_agencia as sag ON (sag.id_agencia = sus.id_agencia)
							inner join
						s_departamento as sd ON (sd.id_depto = sc.extension)
						
where
						sca.emitir = 1 and 
						sus.nombre not like '%sudamericana%' and
						sus.usuario not like '%emontano%' and
						sca.fecha_emision between '".$date2."' and '".$date."'
ORDER BY
sca.no_emision ASC"; 				
echo $sap.'<br><br><br>';
			$cap = $link->query($sap,MYSQLI_STORE_RESULT);

$svi="SELECT 
	case sag.id_depto when 1 then 3 when 4 then 2 when 5 then 5 when 6 then 6 when 7 then 1 when 8 then 7 end as p_venta, 1 as p_ingresada, sca.no_poliza, Date_format(sca.fecha_emision,'%d/%m/%Y') as f_emision, Date_format(sca.fecha_creacion,'%d/%m/%Y') as f_ingreso, if(sde.tomador_ci_nit = ' ', 'BSCI', 'CI') as tipo_doc_tom, sde.tomador_ci_nit, if(sca.factura_nit = ' ', 'BSCI', 'CI') as  tipo_doc_fac, sca.factura_nit, 
	case sdep.id_depto when 1 then 3 when 4 then 2 when 5 then 5 when 6 then 6 when 7 then 4 when 8 then 7 end as sucursal, sp.plan, case sca.periodo when 'Y' then 1 when 'M' then 2 end as periodo, sca.no_emision, 0 as n_familiar, 'N' as beneficiario, 0 as parent, 'BSCI' as tipo_doc_ben, 0 as ci_ben, 
	sc.genero, sc.paterno, sc.materno, sc.nombre, Date_format(sc.fecha_nacimiento,'%d/%m/%Y') as f_nac, if(sc.extension = 11, 'CIE',  if(sc.complemento != '', if(sc.complemento = sd.codigo, 'CI','CID'), 'CI')) as tipo_doc_asoc, if(sc.extension = 11, concat('E-', sc.ci),if(sc.complemento = sd.codigo, concat(sc.ci, sd.codigo), if(sc.complemento != '', concat(sc.ci, '-', sc.complemento, sd.codigo), concat(sc.ci, sd.codigo)))) as id_asoc, sp.plan as base_aseg, 337 as tipo_dir, 	
	sc.direccion, case sdep.id_depto when 1 then 4 when 2 then 5 when 3 then 7 when 4 then 3 when 5 then 2 when 6 then 9 when 7 then 8 when 8 then 1 when 9 then 6 end as departamento, case sdep.id_depto when 1 then 75 when 2 then 181 when 3 then 217 when 4 then 126 when 5 then 17 when 6 then 243 when 7 then 297 when 8 then 322 when 9 then 326 end as ciudad, sc.email, sc.telefono_domicilio, sc.telefono_celular, 0 as part, sc.desc_ocupacion, case sca.forma_pago when 'DA' then 15 when 'DM' then 15 when 'CO' then 14 end as forma_pago, if(sca.forma_pago = 'CO', 5, 4) as conducto, 
	if(sca.forma_pago = 'CO', '', 1) as banco, if(sca.forma_pago = 'CO', 'CRE', 'AHO') as tipo_cuenta, if(sde.cuenta_1 = 0, ' ', sde.cuenta_1) as cuenta_1, sde.tarjeta, if(sca.forma_pago = 'CO', 9, 1) as canal, sag.codigo as agencia, sc.ci, 1 as convenio, case sp.nombre when 'Plan A' then 300 when 'Plan B' then 301 when 'Plan C' then 302 when 'Plan D' then 303 end as n_plan, 19 as red_b, sde.id_detalle
	
from 
						s_vi_em_cabecera as sca
							inner join 
						s_vi_em_detalle as sde ON (sde.id_emision = sca.id_emision)
							inner join
						s_cliente as sc ON (sc.id_cliente = sde.id_cliente)
							inner join
						s_plan as sp ON (sp.id_plan = sca.id_plan)													
							inner join
						s_usuario as sus ON (sus.id_usuario = sca.id_usuario)	
							inner join
						s_departamento as sdep ON (sdep.id_depto = sus.id_depto)
							inner join
						s_tipo_cambio as stc ON (stc.id_ef = sca.id_ef)							
							left outer join
						s_agencia as sag ON (sag.id_agencia = sus.id_agencia)
							inner join
						s_departamento as sd ON (sd.id_depto = sc.extension)
						
where
						sca.emitir = 1 and 
						sus.nombre not like '%sudamericana%' and
						sus.usuario not like '%emontano%' and
						sca.fecha_emision between '".$date2."' and '".$date."'
ORDER BY
sca.no_emision ASC"; 				
//echo $svi;
			$cvi = $link->query($svi,MYSQLI_STORE_RESULT);
			//echo $consultades;
					 $date1=date("d-m-Y");
  //Creación de la tabla con formato HTML
$shtml="<table border='0' cellspacing='1' cellpadding='0' style='width:150%; font-size:9pt;'>";
			$shtml.='<tr><td colspan="23" align="center"> PLANILLA INFORMACION ASEGURADOS</td></tr>';
			
			$shtml.='<tr style="background:#D3DCE3;">
					  <td align="center"><b>Punto de Venta</b></td>
					  <td align="center"><b>Sec. Poliza Ingresada</b></td>
					  <td align="center"><b>Nr. Poliza Aseguradora</b></td>
					  <td align="center"><b>Fecha Expedicion</b></td>
					  <td align="center"><b>Fecha Ingreso</b></td>
					  <td align="center"><b>Tipo Identif. Tomador</b></td>
					  <td align="center"><b>Identificacion Tomador</b></td>
					  <td align="center"><b>Tipo Identif. Pagador</b></td>
					  <td align="center"><b>Identificacion Pagador</b></td>
					  <td align="center"><b>Sucursal</b></td>
					  <td align="center"><b>Sum. Aseg. Neta Riesgo</b></td>
					  <td align="center"><b>Frecuencia Cobro</b></td>
					  <td align="center"><b>Nro. Grupo</b></td>
					  <td align="center"><b>Nro. del Familiar</b></td>
					  <td align="center"><b>Es Beneficiario?</b></td>
					  <td align="center"><b>Parentesco</b></td>
					  <td align="center"><b>Tipo Identificacion</b></td>
					  <td align="center"><b>Identificacion</b></td>
					  <td align="center"><b>Genero</b></td>
					  <td align="center"><b>Primer Apellido</b></td>
					  <td align="center"><b>Segundo Apellido</b></td>
					  <td align="center"><b>Primer Nombre</b></td>
					  <td align="center"><b>Segundo Nombre</b></td>
					  <td align="center"><b>Fecha Nacimiento</b></td>
					  <td align="center"><b>Tipo Identificacion Asociado</b></td>
					  <td align="center"><b>Identificacion Asociado</b></td>
					  <td align="center"><b>Base Suma Asegurada</b></td>
					  <td align="center"><b>Tipo Direccion</b></td>
					  <td align="center"><b>Direccion</b></td>
					  <td align="center"><b>Departamento</b></td>
					  <td align="center"><b>Ciudad</b></td>
					  <td align="center"><b>Correo Electronico</b></td>
					  <td align="center"><b>Telefono Principal</b></td>
					  <td align="center"><b>Telefono Movil</b></td>
					  <td align="center"><b>% Part.</b></td>
					  <td align="center"><b>Actividad Economica</b></td>
					  <td align="center"><b>Tipo. Conducto de pago</b></td>
					  <td align="center"><b>Conducto de pago</b></td>
					  <td align="center"><b>Banco</b></td>
					  <td align="center"><b>Tipo Cuenta Recaudo</b></td>
					  <td align="center"><b>Nro. Cuenta Recaudo</b></td>
					  <td align="center"><b>Canal Recaudo</b></td>
					  <td align="center"><b>Sede Canal Recaudo</b></td>
					  <td align="center"><b>Codigo Cliente</b></td>
					  <td align="center"><b>Convenio de Descuento</b></td>
					  <td align="center"><b>Cod. Plan Cobertura</b></td>
					  <td align="center"><b>Red Banco</b></td>
					 </tr>';
				while($rap = $cap->fetch_array(MYSQL_ASSOC)){					
					if($rap['cuenta_1']==''){$c_ap=$rap['tarjeta']; }else{$c_ap=$rap['cuenta_1']; }
					$capital = json_decode($rap['plan'], true);
					$base = json_decode($rap['base_aseg'], true);
					$ca++;
					
					if(preg_match('/^[6|7|9][0-9]+$/',$rap['telefono_domicilio'])){
						  //echo 'es celular<br /><br />';
						  $fonop='3155500';
					 }else{
						  //echo 'no es celular<br /><br />';
						  $fonop=$rap['telefono_domicilio'];
					 }
					
						 $shtml.='<tr>
						 	  <td align="center">'.$rap['agencia'].'</td>
							  <td align="center">'.$ca.'</td>
							  <td align="center">'.$rap['no_poliza'].'</td>
							  <td align="center">'.$rap['f_emision'].'</td>
							  <td align="center">'.$rap['f_ingreso'].'</td>
							  <td align="center">'.$rap['tipo_doc_asoc'].'</td>
							  <td align="center">'.$rap['id_asoc'].'</td>
							  <td align="center">'.$rap['tipo_doc_asoc'].'</td>
							  <td align="center">'.$rap['id_asoc'].'</td>
							  <td align="center">'.$rap['sucursal'].'</td>
							  <td align="center">'.$capital[0]["rank"].'</td>
							  <td align="center">'.$rap['periodo'].'</td>
							  <td align="center">'.$rap['no_emision'].'</td>
							  <td align="center">'.$rap['n_familiar'].'</td>							  
							  <td align="center">'.$rap['beneficiario'].'</td>
							  <td align="center">'.$rap['parent'].'</td>
							  <td align="center">'.$rap['tipo_doc_asoc'].'</td>
							  <td align="center">'.$rap['id_asoc'].'</td>							  
							  <td align="center">'.$rap['genero'].'</td>
							  <td align="center">'.$rap['paterno'].'</td>
							  <td align="center">'.$rap['materno'].'</td>
							  <td align="center">'.$rap['nombre'].'</td>
							  <td align="center">&nbsp;</td>
							  <td align="center">'.$rap['f_nac'].'</td>
							  <td align="center">&nbsp;</td>
							  <td align="center">&nbsp;</td>
							  <td align="center">'.$base[0]["rank"].'</td>
							  <td align="center">'.$rap['tipo_dir'].'</td>							  
							  <td align="center">'.$rap['direccion'].'</td>							  
							  <td align="center">'.$rap['departamento'].'</td>
							  <td align="center">'.$rap['ciudad'].'</td>
							  <td align="center">'.$rap['email'].'</td>
							  <td align="center">'.$fonop.'</td>
							  <td align="center">'.$rap['telefono_celular'].'</td>
							  <td align="center">'.$rap['part'].'</td>							  
							  <td align="center">'.$rap['desc_ocupacion'].'</td>
							  <td align="center">'.$rap['forma_pago'].'</td>
							  <td align="center">'.$rap['conducto'].'</td>
							  <td align="center">'.$rap['banco'].'</td>
							  <td align="center">'.$rap['tipo_cuenta'].'</td>
							  <td align="center">'.$c_ap.'</td>
							  <td align="center">'.$rap['canal'].'</td>
							  <td align="center">'.$rap['agencia'].'</td>
							  <td align="center">'.$rap['ci'].'</td>
							  <td align="center">'.$rap['convenio'].'</td>
							  <td align="center">'.$rap['n_plan'].'</td>
							  <td align="center">'.$rap['red_b'].'</td>
						</tr>';
						
						$sba="select cobertura, paterno, materno, nombre, if(ci = ' ', 'BSCI', 'CI') as tipo_ci, ci, parentesco, porcentaje_credito from s_ap_beneficiario where id_detalle = '".$rap['id_detalle']."'";
						$cba = $link->query($sba,MYSQLI_STORE_RESULT);
						$c=1;
						while($rba = $cba->fetch_array(MYSQL_ASSOC)){
							$shtml.='<tr>
								<td align="center">&nbsp;</td>
								<td align="center">'.$ca.'</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">'.$rap['no_emision'].'</td>
								<td align="center">'.$rap['n_familiar'].'</td>
								<td align="center">Y</td>
								<td align="center">45</td>
								<td align="center">BSCI</td>
								<td align="center">'.$c.'-'.$rap['id_asoc'].'</td>
								<td align="center">&nbsp;</td>
								<td align="center">'.$rba['paterno'].'</td>
								<td align="center">'.$rba['materno'].'</td>
								<td align="center">'.$rba['nombre'].'</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">'.$rap['tipo_doc_asoc'].'</td>
								<td align="center">'.$rap['id_asoc'].'</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">'.number_format($rba['porcentaje_credito'], 0, '.', ',').'</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">'.$rap['n_plan'].'</td>
								<td align="center">&nbsp;</td>
							</tr>';
						$c++;	
						}
				}
				
				while($rvi = $cvi->fetch_array(MYSQL_ASSOC)){
					if($rvi['cuenta_1']==''){$c_vi=$rvi['tarjeta']; }else{$c_vi=$rvi['cuenta_1']; }
					$capital = json_decode($rvi['plan'], true);
					$base = json_decode($rvi['base_aseg'], true);
					$cv++;
					
					if(preg_match('/^[6|7|9][0-9]+$/',$rvi['telefono_domicilio'])){
						  //echo 'es celular<br /><br />';
						  $fonop='3155500';
					 }else{
						  //echo 'no es celular<br /><br />';
						  $fonop=$rvi['telefono_domicilio'];
					 }
						 $shtml.='<tr>
						 	  <td align="center">'.$rvi['agencia'].'</td>
							  <td align="center">'.$cv.'</td>
							  <td align="center">'.$rvi['no_poliza'].'</td>
							  <td align="center">'.$rvi['f_emision'].'</td>
							  <td align="center">'.$rvi['f_ingreso'].'</td>
							  <td align="center">'.$rvi['tipo_doc_asoc'].'</td>
							  <td align="center">'.$rvi['id_asoc'].'</td>
							  <td align="center">'.$rvi['tipo_doc_asoc'].'</td>
							  <td align="center">'.$rvi['id_asoc'].'</td>
							  <td align="center">'.$rvi['sucursal'].'</td>
							  <td align="center">'.$capital[0]["rank"].'</td>
							  <td align="center">'.$rvi['periodo'].'</td>
							  <td align="center">'.$rvi['no_emision'].'</td>
							  <td align="center">'.$rvi['n_familiar'].'</td>							  
							  <td align="center">'.$rvi['beneficiario'].'</td>
							  <td align="center">'.$rvi['parent'].'</td>
							  <td align="center">'.$rvi['tipo_doc_asoc'].'</td>
							  <td align="center">'.$rvi['id_asoc'].'</td>							  
							  <td align="center">'.$rvi['genero'].'</td>
							  <td align="center">'.$rvi['paterno'].'</td>
							  <td align="center">'.$rvi['materno'].'</td>
							  <td align="center">'.$rvi['nombre'].'</td>
							  <td align="center">&nbsp;</td>
							  <td align="center">'.$rvi['f_nac'].'</td>
							  <td align="center">&nbsp;</td>
							  <td align="center">&nbsp;</td>
							  <td align="center">'.$base[0]["rank"].'</td>
							  <td align="center">'.$rvi['tipo_dir'].'</td>							  
							  <td align="center">'.$rvi['direccion'].'</td>							  
							  <td align="center">'.$rvi['departamento'].'</td>
							  <td align="center">'.$rvi['ciudad'].'</td>
							  <td align="center">'.$rvi['email'].'</td>
							  <td align="center">'.$fonop.'</td>
							  <td align="center">'.$rvi['telefono_celular'].'</td>
							  <td align="center">'.$rvi['part'].'</td>							  
							  <td align="center">'.$rvi['desc_ocupacion'].'</td>
							  <td align="center">'.$rvi['forma_pago'].'</td>
							  <td align="center">'.$rvi['conducto'].'</td>
							  <td align="center">'.$rvi['banco'].'</td>
							  <td align="center">'.$rvi['tipo_cuenta'].'</td>
							  <td align="center">'.$c_vi.'</td>
							  <td align="center">'.$rvi['canal'].'</td>
							  <td align="center">'.$rvi['agencia'].'</td>
							  <td align="center">'.$rvi['ci'].'</td>
							  <td align="center">'.$rvi['convenio'].'</td>
							  <td align="center">'.$rvi['n_plan'].'</td>
							  <td align="center">'.$rvi['red_b'].'</td>
						</tr>';
						
						$sbv="select cobertura, paterno, materno, nombre, if(ci = ' ', 'BSCI', 'CI') as tipo_ci, ci, parentesco, porcentaje_credito from s_vi_beneficiario where id_detalle = '".$rvi['id_detalle']."'";
						$cbv = $link->query($sbv,MYSQLI_STORE_RESULT);
						$c=1;
						while($rbv = $cbv->fetch_array(MYSQL_ASSOC)){
							$shtml.='<tr>
								<td align="center">&nbsp;</td>
								<td align="center">'.$cv.'</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">'.$rvi['no_emision'].'</td>
								<td align="center">'.$rvi['n_familiar'].'</td>
								<td align="center">Y</td>
								<td align="center">45</td>
								<td align="center">BSCI</td>
								<td align="center">'.$c.'-'.$rvi['id_asoc'].'</td>
								<td align="center">&nbsp;</td>
								<td align="center">'.$rbv['paterno'].'</td>
								<td align="center">'.$rbv['materno'].'</td>
								<td align="center">'.$rbv['nombre'].'</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">'.$rvi['tipo_doc_asoc'].'</td>
								<td align="center">'.$rvi['id_asoc'].'</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">'.number_format($rbv['porcentaje_credito'], 0, '.', ',').'</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
								<td align="center">'.$rvi['n_plan'].'</td>
								<td align="center">&nbsp;</td>
							</tr>';
						$c++;	
						}
				}		
			$shtml.="</table>";
			
$scarpeta="archivos_email"; //carpeta donde guardar el archivo.
//debe tener permisos 775 por lo menos
$sfile=$scarpeta."/Planilla Informacion Asegurados.xls"; //ruta del archivo a generar
$fp=fopen($sfile,"w");
fwrite($fp,$shtml);//procedemos a escribir el archivo con los datos de $shtml
fclose($fp);
//echo "<a href='".$sfile."' target='_blank'>Haz click aqui</a>";
//Se muestra un hipervínculo para poder descargar la tabla en formato excel
?>