<?php
include('configuration.class.php');
$con= new ConfigurationSibas();
$host = $con->host;
$user = $con->user;
$password = $con->password;
$db = $con->db;

$conexion = mysql_connect($host, $user, $password) or die ("Fallo en el establecimiento de la conexi&oacute;n");
mysql_select_db($db,$conexion);
$msg='';

$handle = fopen('archivos_email/c20150302b.csv', "r");
			
	while (($data = fgetcsv($handle, 1000, "|")) !== FALSE)
	{
		if($data[9]==1){
			
			$d=explode('/', $data[8]);
			$ft=$d[2].'-'.$d[1].'-'.$d[0];
			
			
			if($data[4]==43){ //Preguntamos si es accidentes personales
			@$sql='update s_ap_cobranza as t0 
						inner join 
					s_ap_em_cabecera as t1 ON (t0.id_emision = t1.id_emision) 
						inner join					
					s_tipo_cambio as t2 ON (t2.id_ef = t1.id_ef) 
				  set t0.numero_transaccion = "'.$data[7].'", t0.fecha_transaccion = "'.$ft.'", t0.monto_transaccion = ROUND("'.$data[6].'" * t2.valor_dolar), t0.cobrado = "'.$data[9].'", t0.observacion = "'.$data[10].'" where t1.no_poliza = '.$data[3].' and t0.numero_cuota = '.$data[5];
			mysql_query($sql,$conexion);
			}else{
			@$sql='update s_vi_cobranza as t0 
						inner join 
					s_vi_em_cabecera as t1 ON t0.id_emision = t1.id_emision
						inner join					
					s_tipo_cambio as t2 ON (t2.id_ef = t1.id_ef)
					set t0.numero_transaccion = "'.$data[7].'", t0.fecha_transaccion = "'.$ft.'", t0.monto_transaccion = ROUND("'.$data[6].'" * t2.valor_dolar), t0.cobrado = "'.$data[9].'", t0.observacion = "'.$data[10].'" where t1.no_poliza = '.$data[3].' and t0.numero_cuota = '.$data[5];
			mysql_query($sql,$conexion);
			}
			echo $sql.'<br>';		
			
		}
	}
		if(mysql_errno($conexion)==0){
				echo'<label style="color: #2B7C57; font-weight: bold; font-size: 24px;">CORRECTO</label>';
				echo $msg .= '<label style="color: #2B7C57; font-weight: bold; font-size: 14px;">Importacion exitosa!</label>';
			}else{
				echo'<label style="color: #D51414; font-weight: bold; font-size: 24px;">ERROR</label>';
				echo $msg .= '<label style="color: #D51414; font-weight: bold; font-size: 14px;">Ocurrio un Error en la Importacion!</label>';
			}
?>