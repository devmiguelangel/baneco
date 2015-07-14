<?php
$handle = fopen('archivos_email/AP-20150112.csv', "r");
			
 while (($data = fgetcsv($handle, 1000, "|")) !== FALSE)
 {
   /*echo $data[0].'<br />';
   echo $data[1].'<br />';
   echo $data[2].'<br />';
   echo $data[3].'<br />';
   echo $data[4].'<br />';
   echo $data[5].'<br />';
   echo $data[6].'<br /><br />';*/
   //echo $_POST['tipo'].'<br /><br />';
   //Insertamos los datos con los valores...
	@$sql='update s_vi_cobranza set numero_transaccion = "'.$data[8].'", fecha_transaccion = "'.$data[9].'", cobrado = "'.$data[10].'", observacion = "'.$data[11].'" where '.$data[0];
	mysql_query($sql,$conexion);
	
	//echo $sql;
	
	if(mysql_errno($conexion)==0){
		echo'<label style="color: #2B7C57; font-weight: bold; font-size: 24px;">CORRECTO</label>';
		$msg .= '<label style="color: #2B7C57; font-weight: bold; font-size: 14px;">Importacion exitosa!</label>';
	}else{
		echo'<label style="color: #D51414; font-weight: bold; font-size: 24px;">ERROR</label>';
		$msg .= '<label style="color: #D51414; font-weight: bold; font-size: 14px;">Ocurrio un Error en la Importacion!</label>';
	}
 }				 
 //cerramos la lectura del archivo "abrir archivo" con un "cerrar archivo"
 fclose($handle);
?>