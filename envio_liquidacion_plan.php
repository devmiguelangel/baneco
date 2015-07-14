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
$total_a_ap = $total_b_ap = $total_c_ap = $total_d_ap = $comd_a_ap = $comd_b_ap = $comd_c_ap = $comd_d_ap = $combr_a_ap = $combr_b_ap = $combr_c_ap = $combr_d_ap = $combdc_a_ap = $combdc_b_ap = $combdc_c_ap = $combdc_d_ap = 0;

$total_a_vi = $total_b_vi = $total_c_vi = $total_d_vi = $comd_a_vi = $comd_b_vi = $comd_c_vi = $comd_d_vi = $combr_a_vi = $combr_b_vi = $combr_c_vi = $combr_d_vi = $combdc_a_vi = $combdc_b_vi = $combdc_c_vi = $combdc_d_vi = 0;

$link = new SibasDB();
	
$sap="select 'Accidentes Personales' as producto, case sac.cobrado when 0 then 'NO' when 1 then 'SI' end as cobrado, sac.monto_transaccion, if(sca.anulado = 1 , 0 ,if(sca.periodo = 'Y', (sac.monto_transaccion / 12), if(sac.numero_cuota = 1, sac.monto_transaccion, 0))) as comision, sp.nombre as plan_nombre, sp.plan
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
						sca.fecha_emision between '2015-01-12' and curdate()
ORDER BY
sca.no_emision ASC,
sac.numero_cuota ASC"; 				
//echo $selectdes;
			$cap = $link->query($sap,MYSQLI_STORE_RESULT);

$svi="select 'Accidentes Personales' as producto, case sac.cobrado when 0 then 'NO' when 1 then 'SI' end as cobrado, sac.monto_transaccion, if(sca.anulado = 1 , 0 ,if(sca.periodo = 'Y', (sac.monto_transaccion / 12), if(sac.numero_cuota = 1, sac.monto_transaccion, 0))) as comision, sp.nombre as plan_nombre, sp.plan
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
						sca.fecha_emision between '2015-01-12' and curdate()
ORDER BY
sca.no_emision ASC,
sac.numero_cuota ASC"; 				
//echo $selectdes;
			$cvi = $link->query($svi,MYSQLI_STORE_RESULT);
			//echo $consultades;
					 $date1=date("d-m-Y");
  //Creación de la tabla con formato HTML
$shtml='<table border="0" cellspacing="1" cellpadding="0" style="width:150%; font-size:9pt;">
			<tr><td colspan="5" align="center"> RESUMEN DE LIQUIDACION</td></tr>';			
			
				while($rap = $cap->fetch_array(MYSQL_ASSOC)){
					$c_banco=$link->prima['AP'][$rap['plan_nombre']]['CS'];
					$comb = $link->prima['AP'][$rap['plan_nombre']]['CC'];
					$cb_du=$c_banco - $rap['comision'];
					
					if($rap['plan_nombre'] == 'Plan A'){						
						$total_a_ap = $total_a_ap + $rap['monto_transaccion'];
						$comd_a_ap = $comd_a_ap + $rap['comision'];
						$combr_a_ap = $combr_a_ap + $comb;
						$combdc_a_ap = $combdc_a_ap + $cb_du;
					}elseif($rap['plan_nombre'] == 'Plan B'){
						$total_b_ap = $total_b_ap + $rap['monto_transaccion'];
						$comd_b_ap = $comd_b_ap + $rap['comision']; 
						$combr_b_ap = $combr_b_ap + $comb;
						$combdc_b_ap = $combdc_b_ap + $cb_du;
					}elseif($rap['plan_nombre'] == 'Plan C'){
						$total_c_ap = $total_c_ap + $rap['monto_transaccion'];
						$comd_c_ap = $comd_c_ap + $rap['comision']; 
						$combr_c_ap = $combr_c_ap + $comb;
						$combdc_c_ap = $combdc_c_ap + $cb_du;
					}elseif($rap['plan_nombre'] == 'Plan D'){
						$total_d_ap = $total_d_ap + $rap['monto_transaccion'];
						$comd_d_ap = $comd_d_ap + $rap['comision']; 
						$combr_d_ap = $combr_d_ap + $comb;
						$combdc_d_ap = $combdc_d_ap + $cb_du;
					}
				}
				
				while($rvi = $cvi->fetch_array(MYSQL_ASSOC)){
					$c_banco=$link->prima['VI'][$rap['plan_nombre']]['CS'];
					$comb = $link->prima['VI'][$rap['plan_nombre']]['CC'];
					$cb_du=$c_banco - $rvi['comision'];
					
					if($rvi['plan_nombre'] == 'Plan A'){						
						$total_a_vi = $total_a_vi + $rvi['monto_transaccion'];
						$comd_a_vi = $comd_a_vi + $rvi['comision'];
						$combr_a_vi = $combr_a_vi + $comb;
						$combdc_a_vi = $combdc_a_vi + $cb_du;
					}elseif($rvi['plan_nombre'] == 'Plan B'){
						$total_b_vi = $total_b_vi + $rvi['monto_transaccion'];
						$comd_b_vi = $comd_b_vi + $rvi['comision']; 
						$combr_b_vi = $combr_b_vi + $comb;
						$combdc_b_vi = $combdc_b_vi + $cb_du;
					}elseif($rvi['plan_nombre'] == 'Plan C'){
						$total_c_vi = $total_c_vi + $rvi['monto_transaccion'];
						$comd_c_vi = $comd_c_vi + $rvi['comision']; 
						$combr_c_vi = $combr_c_vi + $comb;
						$combdc_c_vi = $combdc_c_vi + $cb_du;
					}elseif($rvi['plan_nombre'] == 'Plan D'){
						$total_d_vi = $total_d_vi + $rvi['monto_transaccion'];
						$comd_d_vi = $comd_d_vi + $rvi['comision']; 
						$combr_d_vi = $combr_d_vi + $comb;
						$combdc_d_vi = $combdc_d_vi + $cb_du;
					}
				}
					   $shtml.='<tr><td colspan = "5" style = "text-align:center; font-size: 12px; font-weight: bold;"> Accidentes Personales</td></tr>
						 		<tr style="background:#D3DCE3;">
									<td align="center"><b>PLAN</b></td>
									<td align="center"><b>TOTAL PRIMA RECAUDADA</b></td>
									<td align="center"><b>COMISION BROKER</b></td>
									<td align="center"><b>COMISION (DUODECIMA)</b></td>
								 	<td align="center"><b>COMISION DEL BANCO DESCONTANDO COMISION DE LA DUODECIMA</b></td>
								</tr>
								<tr>
									  <td align="center">Plan A</td>
									  <td align="center">'.$total_a_ap.'</td>
									  <td align="center">'.$comd_a_ap.'</td>
									  <td align="center">'.$combr_a_ap.'</td>
									  <td align="center">'.$combdc_a_ap.'</td>							  				  
								</tr>
								<tr>
									  <td align="center">Plan B</td>
									  <td align="center">'.$total_b_ap.'</td>
									  <td align="center">'.$comd_b_ap.'</td>
									  <td align="center">'.$combr_b_ap.'</td>
									  <td align="center">'.$combdc_b_ap.'</td>							  				  
								</tr>
								<tr>
									  <td align="center">Plan C</td>
									  <td align="center">'.$total_c_ap.'</td>
									  <td align="center">'.$comd_c_ap.'</td>
									  <td align="center">'.$combr_c_ap.'</td>
									  <td align="center">'.$combdc_c_ap.'</td>							  				  
								</tr>
								<tr>
									  <td align="center">Plan D</td>
									  <td align="center">'.$total_d_ap.'</td>
									  <td align="center">'.$comd_d_ap.'</td>
									  <td align="center">'.$combr_d_ap.'</td>
									  <td align="center">'.$combdc_d_ap.'</td>							  				  
								</tr>
								
								<tr><td colspan="5">&nbsp;</td></tr>
								
								<tr><td colspan = "5" style = "text-align:center; font-size: 12px; font-weight: bold;"> Vida Individual</td></tr>
						 		<tr style="background:#D3DCE3;">
									<td align="center"><b>PLAN</b></td>
									<td align="center"><b>TOTAL PRIMA RECAUDADA</b></td>
									<td align="center"><b>COMISION BROKER</b></td>
									<td align="center"><b>COMISION (DUODECIMA)</b></td>
								 	<td align="center"><b>COMISION DEL BANCO DESCONTANDO COMISION DE LA DUODECIMA</b></td>
								</tr>								
								<tr>
									  <td align="center">Plan A</td>
									  <td align="center">'.$total_a_vi.'</td>
									  <td align="center">'.$comd_a_vi.'</td>
									  <td align="center">'.$combr_a_vi.'</td>
									  <td align="center">'.$combdc_a_vi.'</td>							  				  
								</tr>
								<tr>
									  <td align="center">Plan B</td>
									  <td align="center">'.$total_b_vi.'</td>
									  <td align="center">'.$comd_b_vi.'</td>
									  <td align="center">'.$combr_b_vi.'</td>
									  <td align="center">'.$combdc_b_vi.'</td>							  				  
								</tr>
								<tr>
									  <td align="center">Plan C</td>
									  <td align="center">'.$total_c_vi.'</td>
									  <td align="center">'.$comd_c_vi.'</td>
									  <td align="center">'.$combr_c_vi.'</td>
									  <td align="center">'.$combdc_c_vi.'</td>							  				  
								</tr>
								<tr>
									  <td align="center">Plan D</td>
									  <td align="center">'.$total_d_vi.'</td>
									  <td align="center">'.$comd_d_vi.'</td>
									  <td align="center">'.$combr_d_vi.'</td>
									  <td align="center">'.$combdc_d_vi.'</td>							  				  
								</tr>';								
					
			$shtml.="</table>";
			
$scarpeta="archivos_email"; //carpeta donde guardar el archivo.
//debe tener permisos 775 por lo menos
$sfile=$scarpeta."/Resumen de Liquidacion_".$date1.".xls"; //ruta del archivo a generar
$fp=fopen($sfile,"w");
fwrite($fp,$shtml);//procedemos a escribir el archivo con los datos de $shtml
fclose($fp);
//echo "<a href='".$sfile."' target='_blank'>Haz click aqui</a>";
//Se muestra un hipervínculo para poder descargar la tabla en formato excel
?>