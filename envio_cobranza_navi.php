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
    $date=date("Y-m-d");
	//echo $date; 
$link = new SibasDB();
	
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
	
$sap="SELECT 
			if(sc.extension = 11, 'CIE', if(sc.complemento != '', if(sc.complemento = sd.codigo, 'CI','CID'), 'CI')) as tipo_doc_asoc, 
			if(sc.extension = 11, concat('E-', sc.ci),if(sc.complemento = sd.codigo, concat(sc.ci, sd.codigo), if(sc.complemento != '', concat(sc.ci, '-', sc.complemento, sd.codigo), concat(sc.ci, sd.codigo)))) as id_asoc,
			sac.monto_transaccion, sac.fecha_cuota, sca.no_poliza, sac.fecha_transaccion, sca.forma_pago
from 
						s_ap_em_cabecera as sca
							inner join 
						s_ap_em_detalle as sde ON (sde.id_emision = sca.id_emision)
							inner join
						s_cliente as sc ON (sc.id_cliente = sde.id_cliente)
							inner join
						s_plan as sp ON (sp.id_plan = sca.id_plan)
							inner join
						s_ap_cobranza as sac ON (sac.id_emision = sca.id_emision)
							inner join
						s_usuario as sus ON (sus.id_usuario = sca.id_usuario)	
							inner join
						s_departamento as sdep ON (sdep.id_depto = sus.id_depto)
							inner join
						s_tipo_cambio as stc ON (stc.id_ef = sca.id_ef)
							inner join
						s_departamento as sd ON (sd.id_depto = sc.extension)
where
						sca.emitir = 1 and 
						sus.nombre not like '%sudamericana%' and
						sus.usuario not like '%emontano%' and
						sac.cobrado = 1 and
						sac.fecha_transaccion between '".$date2."' and curdate()
ORDER BY
sca.no_emision ASC,
sac.numero_cuota ASC"; 				
//echo $selectdes;
			$cap = $link->query($sap,MYSQLI_STORE_RESULT);

$svi="SELECT 
			if(sc.extension = 11, 'CIE', if(sc.complemento != '', if(sc.complemento = sd.codigo, 'CI','CID'), 'CI')) as tipo_doc_asoc, 
			if(sc.extension = 11, concat('E-', sc.ci),if(sc.complemento = sd.codigo, concat(sc.ci, sd.codigo), if(sc.complemento != '', concat(sc.ci, '-', sc.complemento, sd.codigo), concat(sc.ci, sd.codigo)))) as id_asoc,
			sac.monto_transaccion, sac.fecha_cuota, sca.no_poliza, sac.fecha_transaccion, sca.forma_pago
from 
						s_vi_em_cabecera as sca
							inner join 
						s_vi_em_detalle as sde ON (sde.id_emision = sca.id_emision)
							inner join
						s_cliente as sc ON (sc.id_cliente = sde.id_cliente)
							inner join
						s_plan as sp ON (sp.id_plan = sca.id_plan)
							inner join
						s_vi_cobranza as sac ON (sac.id_emision = sca.id_emision)	
							inner join
						s_usuario as sus ON (sus.id_usuario = sca.id_usuario)	
							inner join
						s_departamento as sdep ON (sdep.id_depto = sus.id_depto)
							inner join
						s_tipo_cambio as stc ON (stc.id_ef = sca.id_ef)
							inner join
						s_departamento as sd ON (sd.id_depto = sc.extension)
where
						sca.emitir = 1 and 
						sus.nombre not like '%sudamericana%' and
						sus.usuario not like '%emontano%' and
						sac.cobrado = 1 and
						sac.fecha_transaccion between '".$date2."' and curdate()
ORDER BY
sca.no_emision ASC,
sac.numero_cuota ASC"; 				
//echo $selectdes;
			$cvi = $link->query($svi,MYSQLI_STORE_RESULT);
			//echo $consultades;
					 $date1=date("d-m-Y");
  //Creación de la tabla con formato HTML
	
$shtml = $shtmlc = "<table border='0' cellspacing='1' cellpadding='0' style='width:100%; font-size:9pt;'>
			<tr><td colspan='8' align='center'>REPORTE COBROS</td></tr>
			<tr style='background:#D3DCE3;'>
					  <td align='center'><b>Cod. Tipo Identificacion</b></td>
					  <td align='center'><b>Identificacion</b></td>
					  <td align='center'><b>Valor Cuota</b></td>
					  <td align='center'><b>Fecha Vencimiento</b></td>
					  <td align='center'><b>Cod. Moneda</b></td>
					  <td align='center'><b>Nro. Poliza</b></td>
					  <td align='center'><b>Fecha Pago</b></td>
					  <td align='center'><b>Nro. Recibo/Factura</b></td>
					 </tr>";

				while($rap = $cap->fetch_array(MYSQL_ASSOC)){				
					if($rap['forma_pago'] == 'DA'){
						 $shtml.='<tr>
							  <td align="center">'.$rap['tipo_doc_asoc'].'</td>
							  <td align="center">'.$rap['id_asoc'].'</td>
							  <td align="center">'.$rap['monto_transaccion'].'</td>
							  <td align="center">'.$rap['fecha_cuota'].'</td>
							  <td align="center">BOLIVIANOS</td>
							  <td align="center">'.$rap['no_poliza'].'</td>
							  <td align="center">'.$rap['fecha_transaccion'].'</td>
							  <td align="center">0</td>							  
						</tr>';
					}else{
						$shtmlc.='<tr>
							  <td align="center">'.$rap['tipo_doc_asoc'].'</td>
							  <td align="center">'.$rap['id_asoc'].'</td>
							  <td align="center">'.$rap['monto_transaccion'].'</td>
							  <td align="center">'.$rap['fecha_cuota'].'</td>
							  <td align="center">BOLIVIANOS</td>
							  <td align="center">'.$rap['no_poliza'].'</td>
							  <td align="center">'.$rap['fecha_transaccion'].'</td>
							  <td align="center">0</td>							  
						</tr>';
					}
				}
				
				while($rvi = $cvi->fetch_array(MYSQL_ASSOC)){
					if($rvi['forma_pago'] == 'DA'){
						 $shtml.='<tr>
							  <td align="center">'.$rvi['tipo_doc_asoc'].'</td>
							  <td align="center">'.$rvi['id_asoc'].'</td>
							  <td align="center">'.$rvi['monto_transaccion'].'</td>
							  <td align="center">'.$rvi['fecha_cuota'].'</td>
							  <td align="center">BOLIVIANOS</td>
							  <td align="center">'.$rvi['no_poliza'].'</td>
							  <td align="center">'.$rvi['fecha_transaccion'].'</td>
							  <td align="center">0</td>	
						</tr>';
					}else{
						$shtmlc.='<tr>
							  <td align="center">'.$rvi['tipo_doc_asoc'].'</td>
							  <td align="center">'.$rvi['id_asoc'].'</td>
							  <td align="center">'.$rvi['monto_transaccion'].'</td>
							  <td align="center">'.$rvi['fecha_cuota'].'</td>
							  <td align="center">BOLIVIANOS</td>
							  <td align="center">'.$rvi['no_poliza'].'</td>
							  <td align="center">'.$rvi['fecha_transaccion'].'</td>
							  <td align="center">0</td>	
						</tr>';
					}

				}

$shtml.= "</table>";
$shtmlc.= "</table>";

	
			
$scarpeta="archivos_email"; //carpeta donde guardar el archivo.
//debe tener permisos 775 por lo menos
$sfile=$scarpeta."/Planilla informacion Cobros Debito Automatico.xls"; //ruta del archivo a generar
$fp=fopen($sfile,"w");
fwrite($fp,$shtml);//procedemos a escribir el archivo con los datos de $shtml
fclose($fp);

$scarpetac="archivos_email"; //carpeta donde guardar el archivo.
//debe tener permisos 775 por lo menos
$sfilec=$scarpetac."/Planilla informacion Cobros Pago al Contado.xls"; //ruta del archivo a generar
$fpc=fopen($sfilec,"w");
fwrite($fpc,$shtmlc);//procedemos a escribir el archivo con los datos de $shtml
fclose($fpc);
//echo "<a href='".$sfile."' target='_blank'>Haz click aqui</a>";
//Se muestra un hipervínculo para poder descargar la tabla en formato excel
?>