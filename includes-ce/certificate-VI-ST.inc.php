<?php
function vi_st_certificate($link, $rsPo, $ide, $url) {
	$c=0;
	$k=0;
	ob_start();

?>

<div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
    <div id="main-c" style="width: 775px; font-weight: normal; font-size: 11px; 
        font-family: Arial, Helvetica, sans-serif; color: #000000;">
<div style="width: 675px; border: 0px solid #FFFF00; text-align:left;">
           <table 
              cellpadding="0" cellspacing="0" border="0" 
              style="width: 100%; height: auto; font-size:80%; font-family: Arial;">
              <tr>
                <td style="width:100%; text-align:center; ">ESTADO DE CUENTAS POR CLIENTE</td>
              </tr>
           </table>         
        </div>
        <br>
<?php
    $num_reg = $rsPo->num_rows;
	$st_importe = 0;
	$st_monto_pago = 0;
	$st_pendiente = 0;
	$fecha = new DateTime();
	$date_now = $fecha->format('d/m/Y');
   if($rsPo->data_seek(0)){	
       while($rowPo = $rsPo->fetch_array(MYSQLI_ASSOC)){
		   $c++;
		   $k++;	
	?>        	
			<div style="width: 675px; border: 0px solid #FFFF00; text-align:left;">
			   <table 
				  cellpadding="0" cellspacing="0" border="0" 
				  style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
	<?php
			   if($c===1){   
	?>              
				  <tr> 
					<td style="width:100%; text-align:left;">
					   Fecha:&nbsp;<?=$date_now;?>
					</td>      
				  </tr>
				  <tr> 
					<td style="width:100%; text-align:left;">
					   Nombre y Apellidos del Asegurado:&nbsp;<?=$rowPo['cl_nombre'].' '.$rowPo['cl_paterno'].' '.$rowPo['cl_materno'];?>
					</td>      
				  </tr>
				  <tr> 
					<td style="width:100%; text-align:left;">
					   <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
						  <tr>
							<td style="width:70%; text-align:left;">
							   Domicilio:&nbsp;<?=utf8_encode($rowPo['cl_direccion']);?>
							</td>
							<td style="width:30%; text-align:left;">
							   Telefono:&nbsp;
							   <?php
								 if($rowPo['cl_fono']!=''){
									 echo $rowPo['cl_fono'];
								 }elseif($rowPo['cl_celular']!=''){
									 echo $rowPo['cl_celular'];
								 }
							   ?>
							</td>
						  </tr>
					   </table>
					</td>      
				  </tr>
				  <tr> 
					<td style="width:100%; text-align:left;">
					   <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
						  <tr>
							<td style="width:60%; text-align:left;">
							   <b>Nro Poliza:</b>&nbsp;<?=$rowPo['no_poliza'];?>
							</td>
							<td style="width:40%; text-align:left;">
							   VIDA INDIVIDUAL
							</td>
						  </tr>
					   </table>
					</td>      
				  </tr>
	<?php
			   }else{
	?>              
				  <tr> 
					<td style="width:100%; text-align:left;">
					   <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
						  <tr>
							<td style="width:60%; text-align:left;">
							   <b>Nro Poliza:</b>&nbsp;<?=$rowPo['no_poliza'];?>
							</td>
							<td style="width:40%; text-align:left;">
							   VIDA INDIVIDUAL
							</td>
						  </tr>
					   </table>
					</td>      
				  </tr>
	<?php
			   }
	?>              
			   </table>  
			</div>
	<?php
			   $sqlDt="select 
							id_cobranza,
							id_emision,
							numero_cuota as nro_cuota,
							date_format(fecha_cuota, '%d/%m/%Y') as fecha_prevista,
							monto_cuota as importe,
							numero_transaccion,
							date_format(fecha_transaccion, '%d/%m/%Y') as fecha_pago,
							monto_transaccion as monto_pagado,
							(if(cobrado = 1, 'Pagada', 'Pendiente')) as estado,
							observacion
						from
							s_vi_cobranza
						where
							id_emision = '".$rowPo['id_emision']."';";
			  if($rsDt = $link->query($sqlDt,MYSQLI_STORE_RESULT)){ 
				 if($rsDt->num_rows > 0){
					 $sum_importe=0;
					 $sum_monto_pagado=0;				
	?>        
					<div style="width: 675px; border: 0px solid #FFFF00; text-align:left;">
					   <table 
						  cellpadding="0" cellspacing="0" border="0" 
						  style="width: 100%; height: auto; font-size:80%; font-family: Arial;">
						  <tr>
							<td style="width:17%; text-align:center; height:30px; vertical-align:middle;
							  border-top: 1px solid #333; border-left: 1px solid #333;
							  border-bottom: 1px solid #333;"><b>Fecha Prevista</b></td>
							<td style="width:16%; text-align:center; height:30px; vertical-align:middle;
							  border-top: 1px solid #333; border-left: 1px solid #333;
							  border-bottom: 1px solid #333;"><b>Nro Cuota</b></td>
							<td style="width:16%; text-align:center; height:30px; vertical-align:middle;
							  border-top: 1px solid #333; border-left: 1px solid #333;
							  border-bottom: 1px solid #333;"><b>Importes</b></td>
							<td style="width:16%; text-align:center; height:30px; vertical-align:middle;
							  border-top: 1px solid #333; border-left: 1px solid #333;
							  border-bottom: 1px solid #333;"><b>Monto Pagado</b></td>
							<td style="width:18%; text-align:center; height:30px; vertical-align:middle;
							  border-top: 1px solid #333; border-left: 1px solid #333;
							  border-bottom: 1px solid #333;"><b>Estado</b></td>
							<td style="width:17%; text-align:center; height:30px; vertical-align:middle;
							  border-top: 1px solid #333; border-left: 1px solid #333;
							  border-bottom: 1px solid #333; border-right: 1px solid #333;"><b>Fecha Pago</b></td>
						  </tr>
	<?php
						  while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
							  $sum_importe = $sum_importe+$rowDt['importe'];
							  $sum_monto_pagado = $sum_monto_pagado+$rowDt['monto_pagado'];
							  if($rowDt['estado']==='Pendiente'){
								 $st_pendiente = $st_pendiente+$rowDt['importe'];
								 $fecha_pago = ''; 
							  }else{
								 $fecha_pago = $rowDt['fecha_pago'];  
							  }
	?>                      
							  <tr>
								<td style="width:17%; text-align:center; height:20px; vertical-align:middle;
								  border-left: 1px solid #333;"><?=$rowDt['fecha_prevista'];?></td>
								<td style="width:16%; text-align:right; height:20px; vertical-align:middle;
								  border-left: 1px solid #333; padding-right:5px;"><?=$rowDt['nro_cuota'];?></td>
								<td style="width:16%; text-align:right; height:20px; vertical-align:middle;
								  border-left: 1px solid #333; padding-right:5px;"><?=$rowDt['importe'];?></td>
								<td style="width:16%; text-align:right; height:20px; vertical-align:middle;
								  border-left: 1px solid #333; padding-right:5px;"><?=$rowDt['monto_pagado'];?></td>
								<td style="width:18%; text-align:left; height:20px; vertical-align:middle;
								  border-left: 1px solid #333; padding-left:5px;"><?=$rowDt['estado'];?></td>
								<td style="width:17%; text-align:center; height:20px; vertical-align:middle;
								  border-left: 1px solid #333; border-right: 1px solid #333;"><?=$fecha_pago;?></td>
							  </tr>
	<?php
						  }
						  $st_importe = $st_importe+$sum_importe;
						  $st_monto_pago = $st_monto_pago+$sum_monto_pagado;
	?>                          
						  <tr>
							<td style="width:17%; text-align:center; height:30px; border-top: 1px solid #333;
							  border-left: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;</td>
							<td style="width:16%; text-align:right; height:30px; border-top: 1px solid #333;
							  border-left: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;</td>
							<td style="width:16%; text-align:right; height:30px; border-top: 1px solid #333;
							  border-left: 1px solid #333; border-bottom: 1px solid #333;
							  padding-right:5px;"><?=$sum_importe;?></td>
							<td style="width:16%; text-align:right; height:30px; border-top: 1px solid #333;
							  border-left: 1px solid #333; border-bottom: 1px solid #333;
							  padding-right:5px;"><?=$sum_monto_pagado;?></td>
							<td style="width:18%; text-align:left; height:30px; border-top: 1px solid #333;
							  border-left: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;</td>
							<td style="width:17%; text-align:center; height:30px; border-top: 1px solid #333;
							  border-left: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;</td>
						  </tr>
					   </table>  
					</div>
	<?php
				 }else{
					echo 'No existen datos';	 
				 }
			  }else{
				  echo 'Error en la consulta';  
			  }
			  if($c<$num_reg){
				  echo'<br>';
			  }
			  if($k==2 && $c<$num_reg){
				  echo "<page><div style='page-break-before: always;'>&nbsp;</div></page>";
				  $k=0;
			  }
	?>           
			<!--
			<br>
			<div style="width: 675px; border: 1px solid #FFFF00; text-align:left;">
			   <table 
				  cellpadding="0" cellspacing="0" border="0" 
				  style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
				  <tr> 
					<td style="width:100%; text-align:left;">
					   <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
						  <tr>
							<td style="width:60%; text-align:left;">
							   <b>Nro Poliza:</b>&nbsp;
							</td>
							<td style="width:40%; text-align:left;">
							   PROTECCION CONTRA ACCIDENTES PERSONALES
							</td>
						  </tr>
					   </table>
					</td>      
				  </tr>
			   </table>  
			</div>
			<div style="width: 675px; border: 1px solid #FFFF00; text-align:left;">
			   <table 
				  cellpadding="0" cellspacing="0" border="0" 
				  style="width: 100%; height: auto; font-size:80%; font-family: Arial;">
				  <tr>
					<td style="width:17%; text-align:center; height:30px; vertical-align:middle;
					  border-top: 1px solid #333; border-left: 1px solid #333;
					  border-bottom: 1px solid #333;"><b>Fecha Prevista</b></td>
					<td style="width:16%; text-align:center; height:30px; vertical-align:middle;
					  border-top: 1px solid #333; border-left: 1px solid #333;
					  border-bottom: 1px solid #333;"><b>Nro Cuota</b></td>
					<td style="width:16%; text-align:center; height:30px; vertical-align:middle;
					  border-top: 1px solid #333; border-left: 1px solid #333;
					  border-bottom: 1px solid #333;"><b>Importes</b></td>
					<td style="width:16%; text-align:center; height:30px; vertical-align:middle;
					  border-top: 1px solid #333; border-left: 1px solid #333;
					  border-bottom: 1px solid #333;"><b>Monto Pagado</b></td>
					<td style="width:18%; text-align:center; height:30px; vertical-align:middle;
					  border-top: 1px solid #333; border-left: 1px solid #333;
					  border-bottom: 1px solid #333;"><b>Estado</b></td>
					<td style="width:17%; text-align:center; height:30px; vertical-align:middle;
					  border-top: 1px solid #333; border-left: 1px solid #333;
					  border-bottom: 1px solid #333; border-right: 1px solid #333;"><b>Fecha Pago</b></td>
				  </tr>
				  <tr>
					<td style="width:17%; text-align:center; height:20px; vertical-align:middle;
					  border-left: 1px solid #333;">26/01/2012</td>
					<td style="width:16%; text-align:right; height:20px; vertical-align:middle;
					  border-left: 1px solid #333;">1</td>
					<td style="width:16%; text-align:right; height:20px; vertical-align:middle;
					  border-left: 1px solid #333;">5.62</td>
					<td style="width:16%; text-align:right; height:20px; vertical-align:middle;
					  border-left: 1px solid #333;">5.62</td>
					<td style="width:18%; text-align:left; height:20px; vertical-align:middle;
					  border-left: 1px solid #333;">Pagada</td>
					<td style="width:17%; text-align:center; height:20px; vertical-align:middle;
					  border-left: 1px solid #333; border-right: 1px solid #333;">27/01/2012</td>
				  </tr>
				  <tr>
					<td style="width:17%; text-align:center; height:30px; border-top: 1px solid #333;
					  border-left: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;</td>
					<td style="width:16%; text-align:right; height:30px; border-top: 1px solid #333;
					  border-left: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;</td>
					<td style="width:16%; text-align:right; height:30px; border-top: 1px solid #333;
					  border-left: 1px solid #333; border-bottom: 1px solid #333;">67.44</td>
					<td style="width:16%; text-align:right; height:30px; border-top: 1px solid #333;
					  border-left: 1px solid #333; border-bottom: 1px solid #333;">67.44</td>
					<td style="width:18%; text-align:left; height:30px; border-top: 1px solid #333;
					  border-left: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;</td>
					<td style="width:17%; text-align:center; height:30px; border-top: 1px solid #333;
					  border-left: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333;">&nbsp;</td>
				  </tr>
			   </table>  
			</div>
			-->
<?php
	   }
   }
?>        
        <div style="width: 675px; border: 0px solid #FFFF00; text-align:left; margin-top:5px;">
           <table 
              cellpadding="0" cellspacing="0" border="0" 
              style="width: 100%; height: auto; font-family: Arial;">
              <tr>
                <td style="width:17%; text-align:left; height:30px; border-top: 1px solid #333;
                  border-left: 1px solid #333; font-size: 100%;"><b>TOTALES</b></td>
                <td style="width:16%; text-align:right; height:30px; border-top: 1px solid #333;">&nbsp;</td>
                <td style="width:16%; text-align:right; height:30px; border-top: 1px solid #333;
                  font-size: 100%; padding-right:10px;"><b><?=$st_importe;?></b></td>
                <td style="width:16%; text-align:right; height:30px; border-top: 1px solid #333;
                  font-size: 100%; padding-right:10px;"><b><?=$st_monto_pago;?></b></td>
                <td style="width:18%; height:30px;  border-right: 1px solid #333; 
                  border-top: 1px solid #333;">&nbsp;</td>
                <td style="width:17%; height:30px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="width:17%; text-align:left; height:30px; 
                  border-left: 1px solid #333; border-bottom: 1px solid #333; font-size: 100%;">
                  <b>PENDIENTE</b>  
                </td>
                <td style="width:16%; text-align:right; height:30px; 
                  border-bottom: 1px solid #333;"></td>
                <td colspan="2" style="width:32%; text-align:center; height:30px; 
                  border-bottom: 1px solid #333; font-size: 100%;"><b><?=$st_pendiente;?></b></td>
                <td style="width:18%; height:30px; border-bottom: 1px solid #333; 
                  border-right: 1px solid #333;"></td>
                <td style="width:17%; height:30px;">&nbsp;</td>
              </tr>
           </table>   
        </div>
    </div>
</div>
<?php
	
	$html = ob_get_clean();
    return $html;
}
?>