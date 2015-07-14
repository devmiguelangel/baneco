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
						sac.fecha_transaccion between '2015-01-12' and curdate()
ORDER BY
sca.no_emision ASC,
sac.numero_cuota ASC"; 				
//echo $selectdes;
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
						sac.fecha_transaccion between '2015-01-12' and curdate()
ORDER BY
sca.no_emision ASC,
sac.numero_cuota ASC"; 				
//echo $selectdes;
			$cvi = $link->query($svi,MYSQLI_STORE_RESULT);
			//echo $consultades;
					 $date1=date("d-m-Y");
  //Creación de la tabla con formato HTML
$shtml="<table border='0' cellspacing='1' cellpadding='0' style='width:150%; font-size:9pt;'>";
			$shtml.='<tr><td colspan="11" align="center"> REPORTE DE COBRANZA</td></tr>';
			
			$shtml.='<tr style="background:#D3DCE3;">
					  <td align="center"><b>REGIONAL</b></td>
					  <td align="center"><b>NUMERO CUENTA</b></td>
					  <td align="center"><b>CI</b></td>
					  <td align="center"><b>CONTRATANTE</b></td>
					  <td align="center"><b>Nro. TRANSACCION</b></td>
					  <td align="center"><b>PRODUCTO</b></td>
					  <td align="center"><b>Nro. POLIZA</b></td>
					  <td align="center"><b>FORMA</b></td>
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
$sfile=$scarpeta."/Reporte de Cobranza_".$date1.".xls"; //ruta del archivo a generar
$fp=fopen($sfile,"w");
fwrite($fp,$shtml);//procedemos a escribir el archivo con los datos de $shtml
fclose($fp);
//echo "<a href='".$sfile."' target='_blank'>Haz click aqui</a>";
//Se muestra un hipervínculo para poder descargar la tabla en formato excel
?>