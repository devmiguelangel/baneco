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
	
	//$date_ini = '2015-02-01';
$link = new SibasDB();
	
$sap="select sdep.departamento, 'Nueva' as tipo_poliza, sde.cuenta_1, sde.tarjeta, 43 as cod_poliza, concat(sc.nombre, ' ', sc.paterno, ' ', sc.materno, ' ', sc.ap_casada) as contratante, concat(sc.nombre, ' ', sc.paterno, ' ', sc.materno, ' ', sc.ap_casada) as asegurado, 'Accidentes Personales' as producto, sca.no_poliza, sc.edad, sc.fecha_nacimiento, sca.fecha_emision, sca.fecha_emision as inicio_vigencia, ADDDATE(sca.fecha_emision, INTERVAL 1 YEAR) as termino_vigencia, 0 as recargo, sp.prima_anual, case sca.forma_pago when 'DA' then 'Debito Automatico' when 'CO' then 'Pago al Contado' when 'DM' then 'Debito Manual' end as forma_pago, sus.nombre as vendedor, sag.agencia, sca.no_emision, sp.nombre as plan_nombre, sp.plan, case sca.periodo when 'M' then 'Mensual' when 'Y' then 'Anual' end as periodo, case sca.anulado when 0 then 'NO' when 1 then 'SI' end as anulado
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
where
						sca.emitir = 1 and 
						sus.nombre not like '%sudamericana%' and
						sus.usuario not like '%emontano%' and
						sca.fecha_emision between '".$date_ini."' and '".$date_fin."'
ORDER BY
sca.no_emision ASC"; 				
//echo $sap.'<br><br><br>';
			$cap = $link->query($sap,MYSQLI_STORE_RESULT);

$svi="select 'Nueva' as tipo_poliza, sde.cuenta_1, sde.tarjeta, 41 as cod_poliza, sdep.departamento, concat(sde.tomador_nombre) as contratante, concat(sc.nombre, ' ', sc.paterno, ' ', sc.materno, ' ', sc.ap_casada) as asegurado, 'Vida Individual' as producto, sca.no_poliza, sc.edad, sc.fecha_nacimiento, sca.fecha_emision, sca.fecha_emision as inicio_vigencia, ADDDATE(sca.fecha_emision, INTERVAL 1 YEAR) as termino_vigencia, 0 as recargo, sp.prima_anual, case sca.forma_pago when 'DA' then 'Debito Automatico' when 'CO' then 'Pago al Contado' when 'DM' then 'Debito Manual' end as forma_pago, sus.nombre as vendedor, sag.agencia, sca.no_emision, sp.nombre as plan_nombre, sp.plan, case sca.periodo when 'M' then 'Mensual' when 'Y' then 'Anual' end as periodo, case sca.anulado when 0 then 'NO' when 1 then 'SI' end as anulado
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
where
						sca.emitir = 1 and 
						sus.nombre not like '%sudamericana%' and
						sus.usuario not like '%emontano%' and
						sca.fecha_emision between '".$date_ini."' and '".$date_fin."'
ORDER BY
sca.no_emision ASC"; 				
//echo $svi;
			$cvi = $link->query($svi,MYSQLI_STORE_RESULT);
			//echo $consultades;
					 $date1=date("d-m-Y");
  //Creación de la tabla con formato HTML
$shtml="<table border='0' cellspacing='1' cellpadding='0' style='width:150%; font-size:9pt;'>";
			$shtml.='<tr><td colspan="23" align="center"> REPORTE DE PRODUCCION '.month($m).' '.$y.'</td></tr>';
			
			$shtml.='<tr style="background:#D3DCE3;">
					  <td align="center"><b>Regional</b></td>
					  <td align="center"><b>Tipo de Poliza</b></td>
					  <td align="center"><b>Codigo Poliza</b></td>
					  <td align="center"><b>Contratante</b></td>
					  <td align="center"><b>Asegurado</b></td>
					  <td align="center"><b>Producto</b></td>
					  <td align="center"><b>Nro. Poliza</b></td>
					  <td align="center"><b>Edad</b></td>
					  <td align="center"><b>Fecha Nacimiento</b></td>
					  <td align="center"><b>Fecha Emision</b></td>
					  <td align="center"><b>Inicio Vigencia</b></td>
					  <td align="center"><b>Termino Vigencia</b></td>
					  <td align="center"><b>Capital Asegurado</b></td>
					  <td align="center"><b>Prima Neta</b></td>
					  <td align="center"><b>Impuesto</b></td>
					  <td align="center"><b>Prima APS</b></td>
					  <td align="center"><b>Recargo</b></td>
					  <td align="center"><b>Prima Total</b></td>
					  <td align="center"><b>Forma Pago</b></td>
					  <td align="center"><b>Tipo Pago</b></td>
					  <td align="center"><b>Vendedor</b></td>
					  <td align="center"><b>Canal Ventas</b></td>
					  <td align="center"><b>Anulado</b></td>
					 </tr>';
				while($rap = $cap->fetch_array(MYSQL_ASSOC)){
					$capital = json_decode($rap['plan'], true);
					
					

						 $shtml.='<tr>
						 	  <td align="center">'.$rap['departamento'].'</td>
						 	  <td align="center">'.$rap['tipo_poliza'].'</td>
							  <td align="center">'.$rap['cod_poliza'].'</td>
							  <td align="center">'.$rap['contratante'].'</td>
							  <td align="center">'.$rap['asegurado'].'</td>
							  <td align="center">'.$rap['producto'].'</td>
							  <td align="center">'.$rap['no_poliza'].'</td>
							  <td align="center">'.$rap['edad'].'</td>
							  <td align="center">'.$rap['fecha_nacimiento'].'</td>
							  <td align="center">'.$rap['fecha_emision'].'</td>
							  <td align="center">'.$rap['inicio_vigencia'].'</td>
							  <td align="center">'.$rap['termino_vigencia'].'</td>
							  
							  <td align="center">'.number_format($capital[0]["rank"], 1, '.', ',').'</td>
							  <td align="center">'.$link->prima['AP'][$rap['plan_nombre']]['PN'].'</td>
							  <td align="center">'.$link->prima['AP'][$rap['plan_nombre']]['IM'].'</td>
							  <td align="center">'.number_format(($rap['prima_anual'] * 0.02), 1, '.', ',').'</td>
							  <td align="center">'.$rap['recargo'].'</td>
							  <td align="center">'.$rap['prima_anual'].'</td>
							  
							  <td align="center">'.$rap['forma_pago'].'</td>
							  <td align="center">'.$rap['periodo'].'</td>
							  <td align="center">'.$rap['vendedor'].'</td>
							  <td align="center">'.$rap['agencia'].'</td>
							  <td align="center">'.$rap['anulado'].'</td>
						</tr>';
				}
				
				while($rvi = $cvi->fetch_array(MYSQL_ASSOC)){
					$capital = json_decode($rvi['plan'], true);
					
						 $shtml.='<tr>
						 	  <td align="center">'.$rvi['departamento'].'</td>
						 	  <td align="center">'.$rvi['tipo_poliza'].'</td>
							  <td align="center">'.$rvi['cod_poliza'].'</td>
							  <td align="center">'.$rvi['contratante'].'</td>
							  <td align="center">'.$rvi['asegurado'].'</td>
							  <td align="center">'.$rvi['producto'].'</td>
							  <td align="center">'.$rvi['no_poliza'].'</td>
							  <td align="center">'.$rvi['edad'].'</td>
							  <td align="center">'.$rvi['fecha_nacimiento'].'</td>
							  <td align="center">'.$rvi['fecha_emision'].'</td>
							  <td align="center">'.$rvi['inicio_vigencia'].'</td>
							  <td align="center">'.$rvi['termino_vigencia'].'</td>
							  
							  <td align="center">'.number_format($capital[0]["rank"], 1, '.', ',').'</td>
							  <td align="center">'.$link->prima['VI'][$rvi['plan_nombre']]['PN'].'</td>
							  <td align="center">'.$link->prima['VI'][$rvi['plan_nombre']]['IM'].'</td>
							  <td align="center">'.number_format(($rvi['prima_anual'] * 0.01), 1, '.', ',').'</td>
							  <td align="center">'.$rvi['recargo'].'</td>
							  <td align="center">'.$rvi['prima_anual'].'</td>
							  
							  <td align="center">'.$rvi['forma_pago'].'</td>
							  <td align="center">'.$rvi['periodo'].'</td>
							  <td align="center">'.$rvi['vendedor'].'</td>
							  <td align="center">'.$rvi['agencia'].'</td>
							  <td align="center">'.$rvi['anulado'].'</td>
						</tr>';
				}		
			$shtml.="</table>";
			
$scarpeta="archivos_email"; //carpeta donde guardar el archivo.
//debe tener permisos 775 por lo menos
$sfile=$scarpeta."/Reporte de Produccion ".month($m)." ".$y.".xls"; //ruta del archivo a generar
$fp=fopen($sfile,"w");
fwrite($fp,$shtml);//procedemos a escribir el archivo con los datos de $shtml
fclose($fp);
//echo "<a href='".$sfile."' target='_blank'>Haz click aqui</a>";
//Se muestra un hipervínculo para poder descargar la tabla en formato excel
?>