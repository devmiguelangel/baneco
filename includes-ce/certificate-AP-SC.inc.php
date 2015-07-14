<?php
function ap_sc_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
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
                <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" width="200" />
           </td>
         </tr>
         <tr><td colspan="2">&nbsp;</td></tr>
         <tr>
           <td style="text-align: center;" colspan="2">
                <b>SLIP DE COTIZACIÓN<br/>AP-<?=$row['no_cotizacion'];?></b>
           </td>
         </tr> 
         <tr><td colspan="2" style="border-bottom:1px solid #2178B6;">&nbsp;</td></tr>        
     </table><br/>

      <?php
		$j=1;
      	$num_titulares=$rsDt->num_rows;
		
		
			while($regiDt=$rsDt->fetch_array(MYSQLI_ASSOC)){
				
				$date=explode('-', $regiDt['fecha_creacion']);
				$fecha = $date[2].' DE '.strtoupper(mes($date[1])).' DE '.$date[0];
				if($regiDt['ap_casada']!=''){$casada='de '.$regiDt['ap_casada'];}else{$casada='';}					
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
                    <td style="background:#FFF; height:10px;">Accidentes Personales</td>
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
                    <td style="background:#CCC; height:10px; border-bottom:2px solid #000;"><?=$regiDt['nombre'].' '.$regiDt['paterno'].' '.$regiDt['materno'].' '.$casada;?></td>
                   </tr>
                </table><br />
                
                II. Especificaciones del Producto<br /><br />
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; background:#00A3C0; color:#000; font-weight:bold; <?=$fontSize;?>">
                   <tr>
                     <td style="width:50%; height:12px; vertical-align:middle; border-bottom:2px solid #000; border-top:2px solid #000;">Nombre del Producto</td>
                     <td style="width:50%; vertical-align:middle; border-bottom:2px solid #000; border-top:2px solid #000;">&nbsp;</td>                     
                   </tr>
                   <tr>
                   	<td>Cobertura Principal: </td>
                    <td style="background:#CCC; height:10px;">1. Muerte Natural o Accidental</td>
                   </tr>
                   <tr>
                   	<td valign="top">Cobertura Complementaria: </td>
                    <td style="background:#FFF; height:10px;">
                    	2. Incapacidad total y/o Permanente por Accidente<br />
                    	3. Reembolso de Gastos Médicos por Accidente 
                    </td>
                   </tr>
                   <tr>
                   	<td valign="top">Exclusiones: </td>
                    <td style="background:#CCC; width:50%;">
                    	Las establecidas en las Condiciones Generales de la Póliza Principal COD.206-935022-2013 07 034.
                    </td>
                   </tr>
                   <tr>
                   	<td valign="top">Nota Importante: </td>
                    <td style="background:#FFF; height:10px;  width:50%;">
                    	En caso que, se cargase primas a personas no asegurables por haber excedido o alcanzado la edad límite según las condiciones de esta póliza, la responsabilidad máxima del asegurador frente a las mismas o a sus beneficiarios, será la devolución de las primas pagadas inadecuadamente por dichas personas no asegurables bajo este contrato 
                    </td>
                   </tr>
                   <tr>
                   	<td>Límite de Edad de ingreso: </td>
                    <td style="background:#FFF; height:10px;  width:50%;">
                    	Mayores de 14 años hasta los 65 años Cobertura por Accidente.<br />
                        Mayores de 14 años hasta los 64 años Incapacidad y Reembolso de Gastos Médicos por Accidentes. 
                    </td>
                   </tr>
                   <tr>
                   	<td>Límite de Permanencia: </td>
                    <td style="background:#FFF; height:10px;  width:50%;">
                    	70 años Cobertura de muerte por Accidente<br />
                    	65 años.
                    </td>
                   </tr>
                   <tr>
                   	<td>Vigencia: </td>
                    <td style="background:#CCC; height:10px;">
                    	Un año- Anual renovable de manera automática.
                    </td>
                   </tr>
                   <tr>
                   	<td style="border-bottom:2px solid #000;">Periodo de Gracia: </td>
                    <td style="background:#FFF; height:10px; border-bottom:2px solid #000;">60 días con cobertura</td>
                   </tr>
                   
                   <tr>
                   	<td>REQUISITOS DE ASEGURABILIDAD</td>
                    <td style="background:#FFF; height:10px; width:50%;">
                    	El presente seguro no requiere de exámenes médicos, solo la firma del formulario de Solicitud de Seguro de Vida Individual, y entrega de una copia del C.I. del titular.
                    </td>
                   </tr>
                   <tr>
                   	<td>INICIO DE COBERTURA</td>
                    <td style="background:#CCC; height:10px;">
                    	A la entrega del Certificado de Seguro.
                    </td>
                   </tr>
                   <tr>
                   	<td style="border-bottom:2px solid #000;" valign="top">FENECIMIENTO DE LA COBERTURA PARA CADA ASEGURADO</td>
                    <td style="background:#CCC; height:10px; border-bottom:2px solid #000; width:50%;">
                    	La cobertura para cada asegurado fenece, a la presencia de las siguientes circunstancias, lo que primero suceda:
                        <ol style="list-style-type:disc;">
                        	<li>Una vez alcanzada la edad límite de permanencia en el seguro.</li>
                            <li>Al vencimiento de esta póliza en caso de que la misma no haya sido renovada</li>
                        </ol>
                        por el Contratante
                   </td>
                   </tr>
                   
                   <tr>
                   	<td style="border-bottom:2px solid #000;">Beneficiario: </td>
                    <td style="height:10px; border-bottom:2px solid #000;">
                    	Opción de mayores de edad
                    </td>
                   </tr>
               </table> <br />
                
                III. Forma de Cobranza<br /><br />   
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; background:#00A3C0; color:#000; font-weight:bold; <?=$fontSize;?>">
                   <tr>
                     <td valign="top" style="width:50%; height:12px; border-bottom:2px solid #000; border-top:2px solid #000;">Montos Asegurados</td>
                     <td style="width:50%; vertical-align:middle; border-bottom:2px solid #000; border-top:2px solid #000;"><?=$row['desc_plan'];?></td>                     
                   </tr>
                   <tr>
                     <td style="height:12px;">Forma de pago:</td>
                     <td style="background:#CCC;">Al contado/ cobro mensual mediante debito automático</td>                     
                   </tr>
                   <tr>
                     <td style="height:12px;">Periodicidad de pago:</td>
                     <td style="background:#FFF;">Al contado o Mensual</td>                     
                   </tr>
                   <tr>
                     <td style="height:12px;">Anulación de Pólizas:</td>
                     <td style="background:#CCC;">Automática a los 91 de mora</td>                     
                   </tr>
                   <tr>
                     <td style="height:12px; border-bottom:2px solid #000;">Periodo de Gracia:</td>
                     <td style="border-bottom:2px solid #000; background:#FFF;">60 días con cobertura</td>                     
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
                     <td style="background:#CCC; vertical-align:middle; border-bottom:2px solid #000;">Plataforma de Banco / Fuerza de ventas Entidad Financiera.</td>                     
                   </tr>
                </table>
                
                <page><div style="page-break-before: always;">&nbsp;</div></page>
                
                V. Siniestros<br /><br />   
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; background:#00A3C0; color:#000; font-weight:normal; <?=$fontSize;?>">
                   <tr>
                     <td style="width:50%; height:12px; border-bottom:1px solid #000; border-top:2px solid #000;">Documentación a presentar:</td>
                     <td style="width:50%; vertical-align:middle; border-bottom:1px solid #000; border-top:2px solid #000;">
                     	En caso de fallecimiento:
                     	<ol style="list-style-type:disc;">
                            <li>Certificado de defunción.</li>
                            <li>Documentación de la autoridad competente.(FELCC-Transito).</li>
                            <li>Documento de identidad del asegurado.</li>
                            <li>Documento de identidad del o (los) beneficiarios.</li>
                        </ol>
                        
                        En caso de Incapacidad Total o Permanente :
                        <ol style="list-style-type:disc;">  
                        	<li>Declaración médica de invalidez, emitida por un médico autorizado por la APS.</li>  
                        </ol>
                        
                        En caso de Incapacidad Temporal:
                        <ol style="list-style-type:disc;">  
                        	<li>Certificado Medico..</li>  
                            <li>Historial Clínico.</li>
                        </ol>
                        
                        En caso de Reembolso de Gastos Medico:
                        <ol style="list-style-type:disc;">  
                        	<li>Certificado Medico.</li>  
                            <li>La Factura  deberá ser a nombre de Nacional Vida  Seguro de Personas S.A NIT: 1028483024 detallando los servicios médicos y medicamentos utilizados. <br />
                            La factura debe ser presentada dentro del mes de la ocurrencia del evento.
</li>
                        </ol>
                        <u>BENEFICIARIOS EN CASO DE MUERTE:</u><br />
                        Este beneficio será entregado al beneficiario designado en el formulario de solicitud de seguro, en ausencia de éste; a los herederos legales según Declaratoria de Herederos.
                     </td>                     
                   </tr>
                   <tr>
                     <td valign="top" style="border-bottom:1px solid #000; border-left:1px solid #000;">Forma de Pago:</td>
                     <td style="background:#CCC; height:12px; width:50%; border-bottom:1px solid #000; border-right:1px solid #000;">
                     	<ol style="list-style-type:disc;">
                        	<li>30 días para el aviso.</li>
                            <li>Total del monto asegurado y demás coberturas la Compañía realizara el pago  posterior a la evaluación de la documentación establecida en cada caso.</li>
                        </ol>
                     </td>                     
                   </tr>
                   <tr>
                     <td style="border-bottom:2px solid #000;">&nbsp;</td>
                     <td style="background:#FFF; height:12px; width:50%; border-bottom:2px solid #000;">
                     	<!--Se incrementa 5% comisión de Cobranza para el pago de incentivos por cumplimiento de objetivos a la fuerza de ventas.-->
                     </td>                     
                   </tr>                   
                </table>
    	<?php
		  $titulares[$j] =  $regiDt['nombre']." ".$regiDt['paterno']." ".$regiDt['materno'];
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
?>