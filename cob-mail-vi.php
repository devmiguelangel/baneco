<?php  
include('configuration.class.php');
$con= new ConfigurationSibas();
$host = $con->host;
$user = $con->user;
$password = $con->password;
$db = $con->db;

$conexion = mysql_connect($host, $user, $password) or die ("Fallo en el establecimiento de la conexi&oacute;n");
mysql_select_db($db,$conexion);
    @$nombre = $_POST["nombre"]; 
    //echo "Nombre: ".$nombre; 
    $date=date("Y-m-d");
	//echo $date; 
	$aprov='';
	$dayt=date('D');
	if($dayt=='Mon'){
		$dia=date('d')-3;		
	}else{
		$dia=date('d')-1;		
	}
	$mes=date('m');
	$anio=date('Y');
	$ndia=date('d')-1;
	//echo $dia;
	//echo $mes;
	if($dia<=0){
		$mes=$mes-1;
		if($mes==0){
			$mes=12;
			$dia=31;
			$anio=$anio-1;
		}else{
			$ndia=fecha($mes,$anio);	
			//echo $ndia;
			if($dayt=='Mon'){
				$dia=$ndia+$dia;				
			}else{
				$dia=$ndia;
			}
		}	
	}	
	
	echo $fecha_ant=date($anio.'-'.$mes.'-'.$dia).'<br />';
	echo $fecha_act=date($anio.'-'.$mes.'-'.$ndia);
	
	//echo 'Fecha resultado: '.$dia.'-'.$mes.'-'.$anio;
	
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
	
			$sqlCo="select 
						sca.no_emision,
						curdate() as gestion,
						sde.cuenta_1,
						spo.no_poliza,
						sp.nombre as plan,
						sac.numero_cuota,
						sac.monto_cuota,
						sca.fecha_emision,
						scl.codigo_be,
						sus.nombre as usuario,
						sca.forma_pago,
						sca.emitir	
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
						s_poliza as spo ON (spo.id_poliza = sca.id_poliza)
							inner join
						s_cliente as scl ON (scl.id_cliente = sde.id_cliente)	
							inner join
						s_usuario as sus ON (sus.id_usuario = sca.id_usuario)						
					where
						sca.anulado = 0 and 
						sca.emitir = 1 and 
						sac.cobrado = 0 and
						sus.nombre not like '%sudamericana%' and
						sac.fecha_cuota <= curdate() 
					order by sca.no_emision";
					//echo $sqlCo;
			$rsCo = mysql_query($sqlCo, $conexion);
$date1=date("Ymd");
  //Creación de la tabla con formato HTML
//$shtml="IdAfiliacion|PLaza|Codigo Cliente Banco|Gestion(Año)|Nro. Cuenta|Nro. DE Identificacion|Nro. De Póliza|Tipo de Póliza|Nro. De Cuota|Monto|Fecha de Envio";
$shtml="";			
			
while($rowCo = mysql_fetch_array($rsCo)){	
	if($rowCo['formapago']=='CO'){
		if($rowCo['emitir']==1){
$shtml.=$rowCo['no_emision'].'|'.$rowCo['codigo_be'].'|'.$rowCo['gestion'].'|'.$rowCo['cuenta_1'].'|'.$rowCo['plan'].'|'.$rowCo['numero_cuota'].'|'.$rowCo['monto_cuota'].'|'.$rowCo['fecha_emision'].'
';	
		}
	}else{
$shtml.=$rowCo['no_emision'].'|'.$rowCo['codigo_be'].'|'.$rowCo['gestion'].'|'.$rowCo['cuenta_1'].'|'.$rowCo['plan'].'|'.$rowCo['numero_cuota'].'|'.$rowCo['monto_cuota'].'|'.$rowCo['fecha_emision'].'
';	
	}
}
			
$scarpeta="archivos_email"; //carpeta donde guardar el archivo.
//debe tener permisos 775 por lo menos
$sfile=$scarpeta."/VI-".$date1.".csv"; //ruta del archivo a generar
$fp=fopen($sfile,"w");
fwrite($fp,$shtml);//procedemos a escribir el archivo con los datos de $shtml
fclose($fp);
//echo "<a href='".$sfile."' target='_blank'>Haz click aqui</a>";
//Se muestra un hipervínculo para poder descargar la tabla en formato excel
?>