<?php
function vi_sc_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	$conexion = $link;

		$marginUl = 'margin: 0 0 0 -20px; padding:0;';
		$fontSize = 'font-size: 10px;';
		$fontsizeh2 = 'font-size: 40%';
		$fontsizeh3 = 'font-size: 37%';
		$width_ct = 'width: 785px;';
		$width_ct2 = 'width: 775px;';
		$url_img = '';

	ob_start();
?>

<div style="<?=$width_ct;?> height: auto; border: 0px solid #0081C2; padding: 5px;">
   <div  style="<?=$width_ct2;?> font-weight: normal; font-size: 80%; font-family: Arial, Helvetica, sans-serif; color: #000000;">
	<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; <?=$fontSize;?>">
         <tr>           
           <td style="width:50%;">&nbsp;</td>
           <td style="text-align:right; width:50%;">
                <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" width="200"/>
           </td>
         </tr>
         <tr><td colspan="2">&nbsp;</td></tr>
         <tr>
           <td style="text-align: center;" colspan="2">
                <b>SLIP DE COTIZACIÓN<br/>VI-<?=$row['no_cotizacion'];?></b>
           </td>
         </tr> 
         <tr><td colspan="2" style="border-bottom:1px solid #2178B6;">&nbsp;</td></tr>        
     </table><br/>

      <?php
		$j=1;
      	$num_titulares=$rsDt->num_rows;
		
		
			while($rowDt=$rsDt->fetch_array(MYSQLI_ASSOC)){
				
				$date=explode('-', $rowDt['fecha_creacion']);
				$fecha = $date[2].' DE '.strtoupper(mes($date[1])).' DE '.$date[0];
				if($rowDt['ap_casada']!=''){$casada='de '.$rowDt['ap_casada'];}else{$casada='';}					
		    ?>
            <h2 style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; <?=$fontSize;?>">Titular <?= $j;?></h2>
            	I. Información General<br /><br />
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; background:#00A3C0; color:#000; font-weight:bold; <?=$fontSize;?>">
                   <tr>
                     <td style="width:35%; height:12px; vertical-align:middle; border-bottom:2px solid #000; border-top:2px solid #000;">Fecha:</td>
                     <td style="width:65%; vertical-align:middle; border-bottom:2px solid #000; border-top:2px solid #000;">Santa Cruz, <?=$fecha;?></td>                     
                   </tr>
                   <tr>
                   	<td>Contratante: </td>
                    <td style="background:#CCC; height:10px;">Banco Económico S.A</td>
                   </tr>
                   <tr>
                   	<td>Ramo: </td>
                    <td style="background:#FFF; height:10px;">Vida</td>
                   </tr>
                   <tr>
                   	<td>Línea de Negocio: </td>
                    <td style="background:#CCC; height:10px;">Masivos</td>
                   </tr>
                   <tr>
                   	<td>Canal de Venta: </td>
                    <td style="background:#FFF; height:10px;">Entidad Financiera</td>
                   </tr>
                   <tr>
                   	<td style="border-bottom:2px solid #000;">Asegurado: </td>
                    <td style="background:#CCC; height:10px; border-bottom:2px solid #000;"><?=$rowDt['nombre'].' '.$rowDt['paterno'].' '.$rowDt['materno'].' '.$casada;?></td>
                   </tr>
                </table><br />
                
                II. Especificaciones del Producto<br /><br />
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; background:#00A3C0; color:#000; font-weight:bold; <?=$fontSize;?>">
                   <tr>
                     <td style="width:50%; height:12px; vertical-align:middle; border-bottom:2px solid #000; border-top:2px solid #000;">Nombre del Producto</td>
                     <td style="width:50%; vertical-align:middle; border-bottom:2px solid #000; border-top:2px solid #000;">&nbsp;</td>                     
                   </tr>
                   <tr>
                   	<td valign="top">Cobertura Complementaria: </td>
                    <td style="background:#FFF; height:10px;">                    	
                    	Sepelio 
                    </td>
                   </tr>
                   <tr>
                   	<td valign="top">Exclusiones: </td>
                    <td style="background:#CCC; width:50%;">
                    	Según Condicionado General  COD.206-934215-2013 07 035, enmendado únicamente para la exclusión de suicidio que funcionará durante el 1er año de haber estado asegurado ininterrumpidamente, este riesgo quedará cubierto a partir del primer día del segundo año. 
                    </td>
                   </tr>
                   <tr>
                   	<td valign="top">Nota Importante</td>
                    <td style="background:#FFF; width:50%;">
                    	En caso que, se cargase primas a personas no asegurables por haber excedido o alcanzado la edad límite según las condiciones de esta póliza, la responsabilidad máxima del asegurador frente a las mismas o a sus beneficiarios, será la devolución de las primas pagadas inadecuadamente por dichas personas no asegurables bajo este contrato.
                    </td>
                   </tr>
                   <tr>
                   	<td>Vigencia: </td>
                    <td style="background:#FFF; height:10px;">
                    	Un año/ Anual renovable- Renovación automática
                    </td>
                   </tr>
                   <tr>
                   	<td>Periodo de Gracia: </td>
                    <td style="background:#CCC; height:10px;">60 días con cobertura</td>
                   </tr>
                   <tr>
                   	<td>Beneficiario: </td>
                    <td style="background:#FFF; height:10px;">
                    	El titular/opción de mayores de edad
                    </td>
                   </tr>
                   <tr>
                   	<td>REQUISITOS DE ASEGURABILIDAD </td>
                    <td style="background:#FFF; height:10px; width:50%;">
                    	El presente seguro no requiere de exámenes médicos, solo la firma del formulario de Solicitud de Seguro de Vida Individual, y entrega de una copia del C.I. del titular.
                    </td>
                   </tr>
                   <tr>
                   	<td>INICIO DE COBERTURA</td>
                    <td style="background:#CCC; height:10px; width:50%;">
                    	Inicia la Cobertura con la entrega del Certificado de Seguro.
                    </td>
                   </tr>
                   <tr>
                   	<td valign="top" style="border-bottom:2px solid #000;">FENECIMIENTO DE LA COBERTURA PARA CADA ASEGURADO </td>
                    <td style="background:#CCC; height:10px; width:50%; border-bottom:2px solid #000;">
                    	La cobertura para cada asegurado fenece, a la presencia de las siguientes circunstancias, lo que primero suceda:
                        <ol style="list-style-type:disc;">
                        	<li>Una vez alcanzada la edad límite de permanencia en el seguro.</li>
                            <li>Al vencimiento de esta póliza en caso de que la misma no haya sido renovada por el Contratante.</li>
                            <li>Mora mayor a 90 días.</li>
                        </ol>
                    </td>
                   </tr>
               </table> <br />
                
                III. Forma de Cobranza<br /><br />   
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; background:#00A3C0; color:#000; font-weight:bold; <?=$fontSize;?>">
                   <tr>
                     <td valign="top" style="width:50%; height:12px; border-bottom:2px solid #000; border-top:2px solid #000;">Montos Asegurados</td>
                     <td style="width:50%; vertical-align:middle; border-bottom:2px solid #000; border-top:2px solid #000; background:#FFF;"><?=$row['desc_plan'];?></td>                     
                   </tr>
                   <tr>
                     <td style="height:12px;">Forma de pago:</td>
                     <td>Al contado/ cobro mensual mediante debito automático</td>                     
                   </tr>
                   <tr>
                     <td style="height:12px; border-bottom:2px solid #000; border-top:2px solid #000;">Periodicidad de pago:</td>
                     <td style="border-bottom:2px solid #000; border-top:2px solid #000;">Al contado o Mensual</td>                     
                   </tr>
                   <tr>
                     <td style="height:12px;">Anulación de Pólizas:</td>
                     <td>Automático 90 días</td>                     
                   </tr>
                </table><br />
                
                IV. Pagos<br /><br />   
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; background:#00A3C0; color:#000; font-weight:bold; <?=$fontSize;?>">
                   <tr>
                     <td style="width:50%; height:12px; border-bottom:2px solid #000; border-top:2px solid #000;">Comisiones a Terceros</td>
                     <td style="width:50%; vertical-align:middle; border-bottom:2px solid #000; border-top:2px solid #000;">10%</td>                     
                   </tr>
                   <tr>
                     <td style="height:12px; border-bottom:2px solid #000;">Canal de ventas</td>
                     <td style="background:#CCC; vertical-align:middle; border-bottom:2px solid #000;">Plataforma de Banco / Fuerza de ventas Entidad Financiera</td>                     
                   </tr>
                </table>
                
                <page><div style="page-break-before: always;">&nbsp;</div></page>
                
                V. Siniestros<br /><br />   
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; background:#00A3C0; color:#000; font-weight:normal; <?=$fontSize;?>">
                   <tr>
                     <td style="width:50%; height:12px; border-bottom:2px solid #000; border-top:2px solid #000;">Documentación a presentar:</td>
                     <td style="width:50%; vertical-align:middle; border-bottom:2px solid #000; border-top:2px solid #000;">
                     	<ol style="list-style-type:disc;">
                            <li>Certificado de Defunción.</li>
                            <li>Documento de identidad (carnet de identidad o certificado de nacimiento) del asegurado.</li>
                            <li>Documento de identidad (Carnet de identidad o Certificado de  nacimiento) del beneficiario.</li>
                            
                            <li>Declaración médica de invalidez, emitida por un médico autorizado por la APS.</li>                            
                            <li>Certificado de Defunción.</li>
                            <li>Documento de identidad (Carnet de identidad o Certificado de  nacimiento) del beneficiario.</li>
                        </ol>
                     </td>                     
                   </tr>
                   <tr>
                     <td style="height:12px; border-bottom:1px solid #000;">Forma de Pago:</td>
                     <td style="background:#FFF; border-bottom:1px solid #000; width:50%;">
                     	<ol style="list-style-type:disc;">
                        	<li>30 días para el aviso de Siniestro/por parte del Cliente.</li>
                            <li>Para la cobertura de sepelio, el interesado (beneficiario) deberá presentar el certificado de defunción  del titular del seguro con el cual se realizara el pago de manera automática. </li>
                            <li>El capital principal será cancelado una vez la compañía haya evaluado la información adicional.</li>
                            <li>En caso de Incapacidad se pagara de acuerdo al dictamen definido por el medico autorizado el mismo que deberá establecer una incapacidad mayor al  60%. El pago se realizara en un solo pago.</li>
                        </ol>
                     </td>                     
                   </tr>
                   <tr>
                     <td style="border-bottom:1px solid #000; border-left:1px solid #000;">&nbsp;</td>
                     <td style="border-bottom:1px solid #000; border-right:1px solid #000; width:50%;"><!--Se incrementa 5% comisión de Cobranza para el pago de incentivos por cumplimiento de objetivos a la fuerza de ventas.--></td>
                   </tr>  
                </table>
    	<?php
		  $titulares[$j] =  $rowDt['nombre']." ".$rowDt['paterno']." ".$rowDt['materno'];
        if($num_titulares <> $j)
          echo "<hr style='border: 2px solid #fc9;'>";

		 $j++;
    }
		?>
   </div>
</div>

<?php
	$html = ob_get_clean();
	return $html;
}


function mes($month){
	switch($month){
		case 1: return 'Enero';	case 2: return 'Febrero';	case 3: return 'Marzo';	case 4: return 'Abril'; case 5: return 'Mayo';
		case 6: return 'Junio';	case 7: return 'Julio';	case 8: return 'Agosto'; case 9: return 'Septiembre'; case 10: return 'Octubre'; 		case 11: return 'Noviembre'; case 12: return 'Diciembre';
	}
}
?>