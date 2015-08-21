<?php  
//include('configuration.class.php');
include('sibas-db.class.php');

$m=date('m') - 1;
$y=date('Y');
$d=fecha($m,$y);

if($m <= 0){$y = $y - 1; $m = 12 + $m; }


function month($mo){
	switch ($mo):
		case 1: return 'Enero'; case 2: return 'Febrero'; case 3: return 'Marzo'; case 4: return 'Abril';
		case 5: return 'Mayo'; 	case 6: return 'Junio'; case 7: return 'Julio';	case 8: return 'Agosto';
		case 9: return 'Septiembre'; case 10: return 'Octubre';	case 11: return 'Noviembre'; case 12: return 'Diciembre';
	endswitch;	
}

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
    
	
	if($m == 1 and $y == 2015){
		$date_ini = $y.'-'.$m.'-12';
	}else{
		$date_ini = $y.'-'.$m.'-01';
	}
	$date_fin = $y.'-'.$m.'-'.$d;
	//echo $date_fin; 
$link = new SibasDB();
	
$sap="select sca.id_emision, sca.no_emision, sdep.departamento, sde.cuenta_1, sde.tarjeta, sc.ci, concat(sc.nombre, ' ', sc.paterno, ' ', sc.materno, ' ', sc.ap_casada) as contratante, sac.numero_transaccion, 'Accidentes Personales' as producto, sca.no_poliza, case sca.forma_pago when 'DA' then 'Debito Automatico' when 'CO' then 'Pago al Contado' when 'DM' then 'Debito Manual' end as forma_pago, sac.monto_cuota as cuota, sac.numero_cuota, sac.fecha_cuota, sca.no_poliza, case sac.cobrado when 0 then 'NO' when 1 then 'SI' end as cobrado, sac.fecha_transaccion, sac.monto_transaccion, sus.nombre as usuario, sca.fecha_emision as inicio_vigencia, ADDDATE(sca.fecha_emision, INTERVAL 1 YEAR) as termino_vigencia, case sca.anulado when 0 then 'NO' when 1 then 'SI' end as anulado, case sca.periodo when 'M' then 'Mensual' when 'Y' then 'Anual' end as tipo_pago, if(sca.anulado = 1 , 0 ,if(sca.periodo = 'Y', (sac.monto_transaccion / 12), if(sac.numero_cuota = 1, sac.monto_transaccion, 0))) as comision, sp.nombre as plan_nombre, sp.plan
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
where
						sca.emitir = 1 and 
						sus.nombre not like '%sudamericana%' and
						sus.usuario not like '%emontano%' and
						sac.cobrado = 1 and
						sac.fecha_transaccion between '".$date_ini."' and '".$date_fin."'
ORDER BY
sca.no_emision ASC,
sac.numero_cuota ASC"; 				
echo $sap.'<br /><br />';
			$cap = $link->query($sap,MYSQLI_STORE_RESULT);

$svi="select sca.id_emision, sca.no_emision, sdep.departamento, sde.cuenta_1, sde.tarjeta, sc.ci, concat(sc.nombre, ' ', sc.paterno, ' ', sc.materno, ' ', sc.ap_casada) as contratante, sac.numero_transaccion, 'Vida Individual' as producto, sca.no_poliza, case sca.forma_pago when 'DA' then 'Debito Automatico' when 'CO' then 'Pago al Contado' when 'DM' then 'Debito Manual' end as forma_pago, sac.monto_cuota as cuota, sac.numero_cuota, sac.fecha_cuota, sca.no_poliza, case sac.cobrado when 0 then 'NO' when 1 then 'SI' end as cobrado, sac.fecha_transaccion, sac.monto_transaccion, sus.nombre as usuario, sca.fecha_emision as inicio_vigencia, ADDDATE(sca.fecha_emision, INTERVAL 1 YEAR) as termino_vigencia, case sca.anulado when 0 then 'NO' when 1 then 'SI' end as anulado, case sca.periodo when 'M' then 'Mensual' when 'Y' then 'Anual' end as tipo_pago, if(sca.anulado = 1 , 0 ,if(sca.periodo = 'Y', (sac.monto_transaccion / 12), if(sac.numero_cuota = 1, sac.monto_transaccion, 0))) as comision, sp.nombre as plan_nombre, sp.plan
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
where
						sca.emitir = 1 and 
						sus.nombre not like '%sudamericana%' and
						sus.usuario not like '%emontano%' and
						sac.cobrado = 1 and
						sac.fecha_transaccion between '".$date_ini."' and '".$date_fin."'
ORDER BY
sca.no_emision ASC,
sac.numero_cuota ASC"; 				
echo $svi;
			$cvi = $link->query($svi,MYSQLI_STORE_RESULT);
			//echo $consultades;
					 $date1=date("d-m-Y");
  //Creación de la tabla con formato HTML
$shtml="<table border='0' cellspacing='1' cellpadding='0' style='width:150%; font-size:9pt;'>";
			$shtml.='<tr><td colspan="11" align="center">REPORTE DE LIQUIDACION '.month($m).' '.$y.'</td></tr>';
			
			$shtml.='<tr style="background:#D3DCE3;">
					  <td align="center"><b>REGIONAL</b></td>
					  <td align="center"><b>NUMERO CUENTA</b></td>
					  <td align="center"><b>CI</b></td>
					  <td align="center"><b>CONTRATANTE</b></td>
					  <td align="center"><b>Nro. TRANSACCION</b></td>
					  <td align="center"><b>PRODUCTO</b></td>
					  <td align="center"><b>Nro. POLIZA</b></td>
					  <td align="center"><b>FORMA DE PAGO</b></td>
					  <td align="center"><b>PERIODICIDAD</b></td>
					  <td align="center"><b>CAPITAL ASEGURADO</b></td>
					  <td align="center"><b>MONTO DE PRIMA RECAUDADA</b></td>
					  <td align="center"><b>Nro. CUOTA</b></td>
					  <td align="center"><b>FECHA</b></td>
					  <td align="center"><b>INICIO VIGENCIA</b></td>
					  <td align="center"><b>TERMINO VIGENCIA</b></td>
					  <td align="center"><b>COBRADO</b></td>
					  <td align="center"><b>USUARIO</b></td>
					  <td align="center"><b>ANULADO</b></td>
					  <td align="center"><b>COMISION BROKER</b></td>
					  <td align="center"><b>COMISION DEL BANCO TOTAL</b></td>
					  <td align="center"><b>COMISION (DUODECIMA)</b></td>
					  <td align="center"><b>COMISION DEL BANCO DESCONTANDO COMISION DE LA DUODECIMA</b></td>
					 </tr>';
				while($rap = $cap->fetch_array(MYSQL_ASSOC)){
					if($rap['cuenta_1']==''){$c_ap=$rap['tarjeta']; }else{$c_ap=$rap['cuenta_1']; }
					$c_banco=$link->prima['AP'][$rap['plan_nombre']]['CS'];
					$cb_du=$c_banco - $rap['comision'];
					$capital = json_decode($rap['plan'], true);
					
						 $shtml.='<tr>
							  <td align="center">'.$rap['departamento'].'</td>
							  <td align="center">'.$c_ap.'</td>
							  <td align="center">'.$rap['ci'].'</td>
							  <td align="center">'.$rap['contratante'].'</td>
							  <td align="center">'.$rap['numero_transaccion'].'</td>
							  <td align="center">'.$rap['producto'].'</td>
							  <td align="center">'.$rap['no_poliza'].'</td>
							  <td align="center">'.$rap['forma_pago'].'</td>
							  <td align="center">'.$rap['tipo_pago'].'</td>
							  <td align="center">'.number_format($capital[0]["rank"], 1, '.', ',').'</td>							  
							  <td align="center">'.number_format($rap['monto_transaccion'],2,".",",").'</td>
							  <td align="center">'.$rap['numero_cuota'].'</td>
							  <td align="center">'.$rap['fecha_transaccion'].'</td>
							  <td align="center">'.$rap['inicio_vigencia'].'</td>
							  <td align="center">'.$rap['termino_vigencia'].'</td>
							  <td align="center">'.$rap['cobrado'].'</td>
							  <td align="center">'.$rap['usuario'].'</td>
							  <td align="center">'.$rap['anulado'].'</td>
							  <td align="center">'.$link->prima['AP'][$rap['plan_nombre']]['CC'].'</td>
							  <td align="center">'.$link->prima['AP'][$rap['plan_nombre']]['CS'].'</td>
							  <td align="center">'.number_format($rap['comision'],2,".",",").'</td>
							  <td align="center">'.$cb_du.'</td>						  
						</tr>';
				}
				
				while($rvi = $cvi->fetch_array(MYSQL_ASSOC)){
					if($rvi['cuenta_1']==''){$c_vi=$rvi['tarjeta']; }else{$c_vi=$rvi['cuenta_1']; }
					$c_banco=$link->prima['VI'][$rvi['plan_nombre']]['CS'];
					$cb_du=$c_banco - $rvi['comision'];
					$capital = json_decode($rvi['plan'], true);
					
						 $shtml.='<tr>
							  <td align="center">'.$rvi['departamento'].'</td>
							  <td align="center">'.$c_vi.'</td>
							  <td align="center">'.$rvi['ci'].'</td>
							  <td align="center">'.$rvi['contratante'].'</td>
							  <td align="center">'.$rvi['numero_transaccion'].'</td>
							  <td align="center">'.$rvi['producto'].'</td>
							  <td align="center">'.$rvi['no_poliza'].'</td>
							  <td align="center">'.$rvi['forma_pago'].'</td>	
							  <td align="center">'.$rvi['tipo_pago'].'</td>
							  <td align="center">'.number_format($capital[0]["rank"], 1, '.', ',').'</td>					  
							  <td align="center">'.number_format($rvi['monto_transaccion'],2,".",",").'</td>
							  <td align="center">'.$rvi['numero_cuota'].'</td>
							  <td align="center">'.$rvi['fecha_transaccion'].'</td>
							  <td align="center">'.$rvi['inicio_vigencia'].'</td>
							  <td align="center">'.$rvi['termino_vigencia'].'</td>
							  <td align="center">'.$rvi['cobrado'].'</td>
							  <td align="center">'.$rvi['usuario'].'</td>
							  <td align="center">'.$rvi['anulado'].'</td>
							  <td align="center">'.$link->prima['VI'][$rvi['plan_nombre']]['CC'].'</td>
							  <td align="center">'.$link->prima['VI'][$rvi['plan_nombre']]['CS'].'</td>
							  <td align="center">'.number_format($rvi['comision'],2,".",",").'</td>
							  <td align="center">'.$cb_du.'</td>
						</tr>';
				}		
			$shtml.="</table>";
			
$scarpeta="archivos_email"; //carpeta donde guardar el archivo.
//debe tener permisos 775 por lo menos
$sfile=$scarpeta."/Reporte de Liquidacion ".month($m)." ".$y.".xls"; //ruta del archivo a generar
$fp=fopen($sfile,"w");
fwrite($fp,$shtml);//procedemos a escribir el archivo con los datos de $shtml
fclose($fp);
//echo "<a href='".$sfile."' target='_blank'>Haz click aqui</a>";
//Se muestra un hipervínculo para poder descargar la tabla en formato excel
?>