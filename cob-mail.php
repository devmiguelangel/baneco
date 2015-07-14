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
	
	//echo $fecha_ant=date($anio.'-'.$mes.'-'.$dia).'<br />';
	//echo $fecha_act=date($anio.'-'.$mes.'-'.$ndia);
	
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
	
			$sqlAp="select 
						sca.no_emision,
						YEAR(NOW()) as gestion,
						sde.cuenta_1,
						sca.no_poliza,
						sp.nombre as plan,
						sac.numero_cuota,
						(sac.monto_cuota / stc.valor_boliviano) as cuota,
						date_format(sca.fecha_emision, '%d/%m/%Y') as fecha_emision,
						scl.codigo_be,
						sus.nombre as usuario,
						sca.forma_pago,
						sca.emitir,
						37 as t_poliza,
						10 as plaza,
						scl.ci
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
						s_cliente as scl ON (scl.id_cliente = sde.id_cliente)		
							inner join
						s_usuario as sus ON (sus.id_usuario = sca.id_usuario)
							inner join
						s_tipo_cambio as stc ON (stc.id_ef = sca.id_ef)					
					where
						sca.anulado = 0 and 
						sca.emitir = 1 and 
						sac.cobrado = 0 and			
						sus.nombre not like '%sudamericana%' and
						sus.usuario not like '%emontano%' and	
						sca.forma_pago <> 'CO' and
						sca.fecha_emision between '2015-01-12' and curdate() and 		
						sac.fecha_cuota <= curdate()						
					order by sca.no_emision";
					//echo $sqlCo;
			$rsAp = mysql_query($sqlAp, $conexion);
			
			$sqlVi="select 
						sca.no_emision,
						YEAR(NOW()) as gestion,
						sde.cuenta_1,
						sde.tarjeta,
						sca.no_poliza,
						sp.nombre as plan,
						sac.numero_cuota,
						(sac.monto_cuota / stc.valor_boliviano) as cuota,
						date_format(sca.fecha_emision, '%d/%m/%Y') as fecha_emision,
						scl.codigo_be,
						sus.nombre as usuario,
						sca.forma_pago,
						sca.emitir,
						36 as t_poliza,
						10 as plaza,
						scl.ci
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
						s_cliente as scl ON (scl.id_cliente = sde.id_cliente)	
							inner join
						s_usuario as sus ON (sus.id_usuario = sca.id_usuario)	
							inner join
						s_tipo_cambio as stc ON (stc.id_ef = sca.id_ef)					
					where
						sca.anulado = 0 and 
						sca.emitir = 1 and 
						sac.cobrado = 0 and
						sus.nombre not like '%sudamericana%' and
						sus.usuario not like '%emontano%' and
						sca.forma_pago <> 'CO' and
						sca.fecha_emision between '2015-01-12' and curdate() and 
						sac.fecha_cuota <= curdate() 
					order by sca.no_emision";
					//echo $sqlCo;
			$rsVi = mysql_query($sqlVi, $conexion);

$yr=date('Y');
$mt=date('m');
$ld=fecha($mt,$yr);
echo $ld;
$dia=date('d') + 1;
if($dia<=9){$dia = '0'.$dia;}elseif($dia > $ld){$dia='01'; $mt=$mt+1;}
if($mt<=9){$mt = '0'.$mt;}

$date1=date("Ym".$dia);
echo $date1;
  //Creación de la tabla con formato HTML
$shtml="";			
$shtml='Oficina|CodigoCliente|CodigoNoCliente|NroCuenta|NumeroCI|CodigoPoliza|TipoSeguro|NroCuota|MontoTotal|FechaEnvio
';

			
while($rowAp = mysql_fetch_array($rsAp)){	
if($rowAp['cuenta_1']==''){$c_ap=$rowAp['tarjeta']; }else{$c_ap=$rowAp['cuenta_1']; }
if($rowAp['codigo_be'] == ''){$ccli = 0; $cncli = $rowAp['ci']; }else{$ccli = $rowAp['codigo_be']; $cncli = 0; }

$shtml.=$rowAp['plaza'].'|'.$ccli.'|'.$cncli.'|'.$c_ap.'|'.$rowAp['ci'].'|'.$rowAp['no_poliza'].'|'.$rowAp['t_poliza'].'|'.$rowAp['numero_cuota'].'|'.number_format($rowAp['cuota'],2,".",",").'|'.$rowAp['fecha_emision'].' '.date('H:i:s').'
';		
}

while($rowVi = mysql_fetch_array($rsVi)){	
if($rowVi['cuenta_1']==''){$c_vi=$rowVi['tarjeta']; }else{$c_vi=$rowVi['cuenta_1']; }
if($rowAp['codigo_be'] == ''){$ccli = 0; $cncli = $rowVi['ci']; }else{$ccli = $rowAp['codigo_be']; $cncli = 0; }

$shtml.=$rowVi['plaza'].'|'.$ccli.'|'.$cncli.'|'.$c_vi.'|'.$rowVi['ci'].'|'.$rowVi['no_poliza'].'|'.$rowVi['t_poliza'].'|'.$rowVi['numero_cuota'].'|'.number_format($rowVi['cuota'],2,".",",").'|'.$rowVi['fecha_emision'].' '.date('H:i:s').'
';		
}


			
$scarpeta="archivos_email"; //carpeta donde guardar el archivo.
//debe tener permisos 775 por lo menos
$sfile=$scarpeta."/".$date1."b.csv"; //ruta del archivo a generar
$fp=fopen($sfile,"w");
//echo $fp;
fwrite($fp,$shtml);//procedemos a escribir el archivo con los datos de $shtml
fclose($fp);
//echo "<a href='".$sfile."' target='_blank'>Haz click aqui</a>";
//Se muestra un hipervínculo para poder descargar la tabla en formato excel
?>