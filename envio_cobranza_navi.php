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
	
$sap="SELECT 
			if(sc.extension = 11, 'CIE', if(sc.complemento != '', if(sc.complemento = sd.codigo, 'CI','CID'), 'CI')) as tipo_doc_asoc, 
			if(sc.extension = 11, concat('E-', sc.ci),if(sc.complemento = sd.codigo, concat(sc.ci, sd.codigo), if(sc.complemento != '', concat(sc.ci, '-', sc.complemento, sd.codigo), concat(sc.ci, sd.codigo)))) as id_asoc,
			sac.monto_transaccion, sac.fecha_cuota, sca.no_poliza, sac.fecha_transaccion
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
						sac.fecha_transaccion between '2015-01-12' and curdate()
ORDER BY
sca.no_emision ASC,
sac.numero_cuota ASC"; 				
//echo $selectdes;
			$cap = $link->query($sap,MYSQLI_STORE_RESULT);

$svi="SELECT 
			if(sc.extension = 11, 'CIE', if(sc.complemento != '', if(sc.complemento = sd.codigo, 'CI','CID'), 'CI')) as tipo_doc_asoc, 
			if(sc.extension = 11, concat('E-', sc.ci),if(sc.complemento = sd.codigo, concat(sc.ci, sd.codigo), if(sc.complemento != '', concat(sc.ci, '-', sc.complemento, sd.codigo), concat(sc.ci, sd.codigo)))) as id_asoc,
			sac.monto_transaccion, sac.fecha_cuota, sca.no_poliza, sac.fecha_transaccion
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
			$shtml.='<tr><td colspan="8" align="center"> REPORTE COBROS</td></tr>';
			
			$shtml.='<tr style="background:#D3DCE3;">
					  <td align="center"><b>Cod. Tipo Identificacion</b></td>
					  <td align="center"><b>Identificacion</b></td>
					  <td align="center"><b>Valor Cuota</b></td>
					  <td align="center"><b>Fecha Vencimiento</b></td>
					  <td align="center"><b>Cod. Moneda</b></td>
					  <td align="center"><b>Nro. Poliza</b></td>
					  <td align="center"><b>Fecha Pago</b></td>
					  <td align="center"><b>Nro. Recibo/Factura</b></td>
					 </tr>';
				while($rap = $cap->fetch_array(MYSQL_ASSOC)){				
					
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
				}
				
				while($rvi = $cvi->fetch_array(MYSQL_ASSOC)){
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
				}		
			$shtml.="</table>";
			
$scarpeta="archivos_email"; //carpeta donde guardar el archivo.
//debe tener permisos 775 por lo menos
$sfile=$scarpeta."/Cobros.xls"; //ruta del archivo a generar
$fp=fopen($sfile,"w");
fwrite($fp,$shtml);//procedemos a escribir el archivo con los datos de $shtml
fclose($fp);
//echo "<a href='".$sfile."' target='_blank'>Haz click aqui</a>";
//Se muestra un hipervínculo para poder descargar la tabla en formato excel
?>