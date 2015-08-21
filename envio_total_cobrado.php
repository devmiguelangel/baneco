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
	
$sap="SELECT sca.id_emision, sca.no_emision, sdep.departamento, sde.cuenta_1, sde.tarjeta, sc.ci, 
			 concat(sc.nombre, ' ', sc.paterno, ' ', sc.materno, ' ', sc.ap_casada) as contratante, 
			 sac.numero_transaccion, 'Accidentes Personales' as producto, sca.no_poliza, 
			 case sca.forma_pago when 'DA' then 'Debito Automatico' when 'CO' then 'Pago al Contado' when 'DM' then 'Debito Manual' end as forma_pago, 
			 sac.monto_cuota as cuota, sac.numero_cuota, sac.fecha_cuota, sca.no_poliza, 
			 case sac.cobrado when 0 then 'NO' when 1 then 'SI' end as cobrado, sac.fecha_transaccion, 
			 sac.monto_transaccion, sus.nombre as usuario, sca.fecha_emision as inicio_vigencia, 
			 ADDDATE(sca.fecha_emision, INTERVAL 1 YEAR) as termino_vigencia, 
			 case sca.anulado when 0 then 'NO' when 1 then 'SI' end as anulado, 
			 case sca.periodo when 'M' then 'Mensual' when 'Y' then 'Anual' end as tipo_pago, 
			 if(sca.anulado = 1 , 0 ,if(sca.periodo = 'Y', (sac.monto_transaccion / 12), 
			 	if(sac.numero_cuota = 1, sac.monto_transaccion, 0))) as comision, sp.nombre as plan_nombre, sp.plan
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

$svi="SELECT sca.id_emision, sca.no_emision, sdep.departamento, sde.cuenta_1, sde.tarjeta, sc.ci, 
			 concat(sc.nombre, ' ', sc.paterno, ' ', sc.materno, ' ', sc.ap_casada) as contratante, 
			 sac.numero_transaccion, 'Vida Individual' as producto, sca.no_poliza, 
			 case sca.forma_pago when 'DA' then 'Debito Automatico' when 'CO' then 'Pago al Contado' when 'DM' then 'Debito Manual' end as forma_pago, 
			 sac.monto_cuota as cuota, sac.numero_cuota, sac.fecha_cuota, sca.no_poliza, 
			 case sac.cobrado when 0 then 'NO' when 1 then 'SI' end as cobrado, sac.fecha_transaccion, 
			 sac.monto_transaccion, sus.nombre as usuario, sca.fecha_emision as inicio_vigencia, 
			 ADDDATE(sca.fecha_emision, INTERVAL 1 YEAR) as termino_vigencia, 
			 case sca.anulado when 0 then 'NO' when 1 then 'SI' end as anulado, 
			 case sca.periodo when 'M' then 'Mensual' when 'Y' then 'Anual' end as tipo_pago, 
			 if(sca.anulado = 1 , 0 ,if(sca.periodo = 'Y', (sac.monto_transaccion / 12), 
			 	if(sac.numero_cuota = 1, sac.monto_transaccion, 0))) as comision, sp.nombre as plan_nombre, sp.plan
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
					  <td align="center"><b>PRODUCTO</b></td>
					  <td align="center"><b>MONTO TOTAL COBRADO</b></td>
					 </tr>';
				$c_ap = $c_vi = 0;

				while($rap = $cap->fetch_array(MYSQL_ASSOC)){
					$c_ap = $c_ap + $rap['monto_transaccion'];
					
				}	
				
				while($rvi = $cvi->fetch_array(MYSQL_ASSOC)){
					$c_vi = $c_vi + $rvi['monto_transaccion'];
				}

						 $shtml.='<tr>
							  <td align="center">Accidentes Personales</td>
							  <td align="center">'.number_format($c_ap, 1, '.', ',').' Bs.</td>
							  				  
						</tr>
						<tr>
							  <td align="center">Vida Individual</td>
							  <td align="center">'.number_format($c_vi, 1, '.', ',').' Bs.</td>							  
						</tr>';						
			$shtml.="</table>";
			
$scarpeta="archivos_email"; //carpeta donde guardar el archivo.
//debe tener permisos 775 por lo menos
$sfile=$scarpeta."/Reporte Total Cobrado al ".$date1.".xls"; //ruta del archivo a generar
$fp=fopen($sfile,"w");
fwrite($fp,$shtml);//procedemos a escribir el archivo con los datos de $shtml
fclose($fp);
//echo "<a href='".$sfile."' target='_blank'>Haz click aqui</a>";
//Se muestra un hipervínculo para poder descargar la tabla en formato excel
?>