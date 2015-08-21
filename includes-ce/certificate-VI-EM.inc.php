<?php
function vi_em_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '', $rsBs) {
	ob_start();

	$plan = json_decode($row['plan'], true);
	$nCl = $rsDt->num_rows;
?>

<div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
    <div id="main-c" style="width: 775px; font-weight: normal; font-size: 11px; 
        font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
	$nt = 0;
	while ($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)) {
		$nt += 1;
?>
        <div style="width: 775px; border: 0px solid #FFFF00; ">
        	<table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr>
                    <td style="width: 100%;">
                        <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" 
                            height="60" class="container-logo" align="right" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; text-align: center; font-weight: bold; font-size: 120%; " >
                        DECLARACIÓN JURADA DE SALUD <br>
                        SOLICITUD DE SEGURO MASIVO VIDA INDIVIDUAL DE CORTO PLAZO
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; text-align: center; font-size: 100%;" >
                        Formato aprobado por la Autoridad de Fiscalización y Control de Pensiones y 
                        Seguros – APS  mediante Resolución Administrativa APS/DS/No.687-2013 <br>
                        Código 206-934215-2013 07 035 3001
                        <br><br>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; text-align: center; font-size: 100%; font-weight: bold;" >
                        El interesado solicita  a Nacional Vida Seguros de Personas S.A, un seguro 
                        de vida, basado en las declaraciones que siguen a continuación, las mismas 
                        que formaran parte integrante e indivisible de la póliza:
                    </td>
                </tr>
            </table>
        </div>
        <br>

        <div style="text-align: left; font-size: 95%; font-weight: bold;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; font-weight: bold; height: 25px; padding-left: 15px;" 
                    	colspan="4" >
                        I.	DATOS PERSONALES DEL SOLICITANTE:
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%;">
                    	<table style="width: 100%; font-size: 100%;">
                    	 	<tr>
                    	 		<td style="width: 15%;">Nombre del Tomador</td>
                    	 		<td style="width: 35%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['tomador_nombre'];?>&nbsp;
                    	 		</td>
                    	 		<td style="width: 7%;"></td>
                    	 		<td style="width: 43%; border-bottom: 1px solid #999;">
                    	 			<!-- <?=$rowDt['tomador_paterno'] . ' ' . $rowDt['tomador_materno'];?>&nbsp; -->
                    	 		</td>
                    	 	</tr>
                	 	</table> 
                	 	<table style="width: 100%; font-size: 100%;">
                    	 	<tr>
                    	 		<td style="width: 16%;">Nombre del Asegurado</td>
                    	 		<td style="width: 36%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['nombre'];?>&nbsp;
                    	 		</td>
                    	 		<td style="width: 7%;">Apellidos</td>
                    	 		<td style="width: 41%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['paterno'] . ' ' . $rowDt['materno'];?>&nbsp;
                    	 		</td>
                    	 	</tr>
                	 	</table>
                    	<table style="width: 100%; font-size: 100%;">
                    	 	<tr>
                    	 		<td style="width: 15%;">Lugar de Nacimiento:</td>
                    	 		<td style="width: 33%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['lugar_nacimiento'];?>&nbsp;
                    	 		</td>
                    	 		<td style="width: 16%;">Fecha de Nacimiento:</td>
                    	 		<td style="width: 36%; border-bottom: 1px solid #999;">
                    	 			<?=date('d', strtotime($rowDt['fecha_nacimiento']));?>
                    	 			 de 
                    	 			<?=$link->get_month(date('n', strtotime($rowDt['fecha_nacimiento'])));?>
                    	 			 de 
                    	 			<?=date('Y', strtotime($rowDt['fecha_nacimiento']));?>
                    	 		</td>
                    	 	</tr>
                    	</table>
						<table style="width: 100%; font-size: 100%;">
                    	 	<tr>
                    	 		<td style="width: 19%;">No Documento de Identidad:</td>
                    	 		<td style="width: 28%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['ci'];?>&nbsp;
                    	 		</td>
                    	 		<td style="width: 10%;">Expedido en:</td>
                    	 		<td style="width: 10%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['ext'];?>&nbsp;
                    	 		</td>
                    	 		<td style="width: 4%;">Edad:</td>
                    	 		<td style="width: 6%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['edad'];?>&nbsp;
                    	 		</td>
                    	 		<td style="width: 4%;">Peso:</td>
                    	 		<td style="width: 6%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['peso'];?> Kg
                    	 		</td>
                    	 		<td style="width: 7%;">Estatura:</td>
                    	 		<td style="width: 6%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['estatura'];?> cm
                    	 		</td>
                    	 	</tr>
                	 	</table>
                	 	<table style="width: 100%; font-size: 100%;">
                    	 	<tr>
                    	 		<td style="width: 14%;">Dirección Domicilio:</td>
                    	 		<td style="width: 36%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['direccion'];?>&nbsp;
                    	 		</td>
                    	 		<td style="width: 13%;">Correo Electronico</td>
                    	 		<td style="width: 37%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['email'];?>&nbsp;
                    	 		</td>
                    	 	</tr>
                	 	</table>
                	 	<table style="width: 100%; font-size: 100%;">
                    	 	<tr>
                    	 		<td style="width: 14%;">Teléfono Domicilio:</td>
                    	 		<td style="width: 26%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['telefono_domicilio'];?>&nbsp;
                    	 		</td>
                    	 		<td style="width: 12%;">Teléfono Oficina:</td>
                    	 		<td style="width: 18%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['telefono_oficina'];?>&nbsp;
                    	 		</td>
                    	 		<td style="width: 12%;">Teléfono Celular:</td>
                    	 		<td style="width: 18%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['telefono_celular'];?>&nbsp;
                    	 		</td>
                    	 	</tr>
                	 	</table>
						<table style="width: 100%; font-size: 100%;">
                    	 	<tr>
                    	 		<td style="width: 12%;">Actividad Laboral</td>
                    	 		<td style="width: 88%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['desc_ocupacion'];?>&nbsp;
                    	 		</td>
                    	 	</tr>
                    	 	<tr>
                    	 		<td style="width: 12%;">Lugar de Trabajo</td>
                    	 		<td style="width: 88%; border-bottom: 1px solid #999;">
                    	 			<?=$rowDt['direccion_laboral'];?>&nbsp;
                    	 		</td>
                    	 	</tr>
                	 	</table>
                    </td>
                </tr>
                
            </table>
       	</div>
		<br>

       	<div style="text-align: left; font-size: 95%; font-weight: bold;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; font-weight: bold; height: 15px; padding-left: 15px; " >
                        II.	ELECCION DEL PLAN
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; font-weight: bold; height: 15px;" >
                        1.  Planes del Seguro
                    </td>
                </tr>
            </table>
			<table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                	<td style="width: 10%;"></td>
                    <td style="width: 75%; background: #000; color: #fff; text-align: center; 
                        height: 15px; border: 1px solid #000;" colspan="2">
                        Expresado en Bolivianos
                    </td>
                	<td style="width: 15%;"></td>
                </tr>
                <tr>
                	<td style="width: 10%;"></td>
                    <td style="width: 55%; background: #353535; color: #fff; height: 15px;
                        border: 1px solid #000;" >
                        Coberturas
                    </td>
                    <td style="width: 20%; background: #353535; color: #fff; 
                        border: 1px solid #000;" >
                        Rangos de Capitales
                    </td>
                	<td style="width: 15%;"></td>
                </tr>
                <tr>
                	<td style="width: 10%;"></td>
                    <td style="width: 55%; background: #000; color: #fff; border: 1px solid #000;" >
                        <?=$plan[0]['cov'];?>&nbsp;
                    </td>
                    <td style="width: 20%; border: 1px solid #000;" >
                        Hasta Bs. <?=number_format($plan[0]['rank'], 0, '.', ',');?>&nbsp;
                    </td>
                	<td style="width: 15%;"></td>
                </tr>
                <tr>
                	<td style="width: 10%;"></td>
                    <td style="width: 55%; background: #000; color: #fff; border: 1px solid #000;" >
                        <?=$plan[1]['cov'];?>&nbsp;
                    </td>
                    <td style="width: 20%; border: 1px solid #000;" >
                        Hasta Bs. <?=number_format($plan[1]['rank'], 0, '.', ',');?>&nbsp;
                    </td>
                	<td style="width: 15%;"></td>
                </tr>
                <tr>
                	<td style="width: 10%;"></td>
                    <td style="width: 55%; background: #000; color: #fff; border: 1px solid #000;" >
                        <?=$plan[2]['cov'];?>&nbsp;
                    </td>
                    <td style="width: 20%; border: 1px solid #000;" >
                        Hasta Bs. <?=number_format($plan[2]['rank'], 0, '.', ',');?>&nbsp;
                    </td>
                	<td style="width: 15%;"></td>
                </tr>
            </table>
       	</div>
		<br>

		<div style="text-align: left; font-size: 95%; font-weight: bold;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; font-weight: bold; height: 15px; padding-left: 15px; " >
                        III.	BENEFICIARIO
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; font-weight: bold; height: 15px;" >
                        Beneficiarios:
                    </td>
                </tr>
            </table>
			<table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                	<td style="width: 10%;"></td>
                    <td style="width: 10%; background: #000; color: #fff; text-align: center; 
                    	height: 10px; padding-top: 5px;" >
                        
                    </td>
                    <td style="width: 35%; background: #000; color: #fff; text-align: center; 
                    	padding-top: 5px; border: 1px solid #000;" >
                        Nombre Completo
                    </td>
                    <td style="width: 10%; background: #000; color: #fff; text-align: center; 
                    	padding-top: 5px; border: 1px solid #000;" >
                        Parentesco
                    </td>
                    <td style="width: 15%; background: #000; color: #fff; text-align: center; 
                    	padding-top: 5px; border: 1px solid #000;" >
                        Carnet de Identidad
                    </td>
                    <td style="width: 10%; background: #000; color: #fff; text-align: center; 
                    	padding-top: 5px; border: 1px solid #000;" >
                        Proporcion (%)
                    </td>
                	<td style="width: 10%;"></td>
                </tr>
<?php
		$k = 0;
		while ($rowBs = $rsBs->fetch_array(MYSQLI_ASSOC)) {
			$k += 1;
?>
				<tr>
                	<td style="width: 10%;"></td>
                    <td style="width: 10%; background: #000; color: #fff; text-align: center; 
                    	height: 10px; padding-top: 2px; border: 1px solid #000;" >
                        Beneficiario <?=$k;?>&nbsp;
                    </td>
                    <td style="width: 35%; border: 1px solid #000; text-align: center; 
                    	padding-top: 2px;" >
                        <?=$rowBs['nombre'] . ' ' . $rowBs['paterno'] . ' ' . $rowBs['materno'];?>&nbsp;
                    </td>
                    <td style="width: 10%; border: 1px solid #000; text-align: center; 
                    	padding-top: 2px;" >
                        <?=$rowBs['parentesco'];?>&nbsp;
                    </td>
                    <td style="width: 15%; border: 1px solid #000; text-align: center; 
                    	padding-top: 2px;" >
                        <?=$rowBs['ci'];?>&nbsp;
                    </td>
                    <td style="width: 10%; border: 1px solid #000; text-align: center; 
                    	padding-top: 2px;" >
                        <?=$rowBs['porcentaje_credito'];?>&nbsp;
                    </td>
                	<td style="width: 10%;"></td>
                </tr>
<?php
		}
?>
            </table>
   		</div>
   		<br>
<?php
		$rowDt['respuesta'] = json_decode($rowDt['respuesta']);

		$response = array();
		for ($i = 1; $i <= 6;$i ++) { 
			$response[$i] = '';
		}

		$k = 0;
		foreach ($rowDt['respuesta'] as $key => $value) {
			$res = explode('|', $value);
			$k += 1;
			
			switch ($res[1]) {
			case 1:
				$response[$k] = 'X';
				break;
			}
		}

		$payment = array(0 => '', 1 => '');
		switch ($row['forma_pago']) {
		case 'CO':
			$payment[0] = 'X';
			break;
		case 'DA':
			$payment[1] = 'X';
			break;
		}

		$period = array(0 => '', 1 => '');
		switch ($row['periodo']) {
		case 'Y':
			$period[0] = 'X';
			break;
		case 'M':
			$period[1] = 'X';
			break;
		}
?>
   		<div style="text-align: left; font-size: 95%; font-weight: bold;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; font-weight: bold; height: 15px; padding-left: 15px; " >
                        IV.	CUESTIONARIO DE SALUD: (Marque con una X si corresponde)
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; font-weight: bold; height: 15px;" >
                        1. ¿Usted padece de alguna de las siguientes enfermedades?
                    </td>
                </tr>
            </table>
			<table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial; ">
                <tr>
                	<td style="width: 10%; "></td>
                    <td style="width: 20%; height: 5px; padding: 5px; " >
                        Cáncer
                    </td>
                    <td style="width: 5%; padding: 2px 0; " >
                    	<table style="font-size: 100%;">
                    		<tr>
                    			<td style="width: 16px; height:12px; border: 1px solid #000; 
                    				text-align: center;">
                    				<?=$response[1];?>&nbsp;
                    			</td>
                    		</tr>
                    	</table> 
                    </td>
                    <td style="width: 15%;"></td>
                    <td style="width: 25%; padding: 5px;" >
                        Sida
                    </td>
                    <td style="width: 5%; padding: 2px 0;" >
						<table style="font-size: 100%;">
                    		<tr>
                    			<td style="width: 16px; height:12px; border: 1px solid #000; 
                    				text-align: center;">
                    				<?=$response[4];?>&nbsp;
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                	<td style="width: 20%; "></td>
                </tr>
                <tr>
                	<td style="width: 10%; "></td>
                    <td style="width: 20%; height: 5px; padding: 5px; " >
                        Diabetes
                    </td>
                    <td style="width: 5%; padding: 2px 0; " >
                        <table style="font-size: 100%;">
                    		<tr>
                    			<td style="width: 16px; height:12px; border: 1px solid #000; 
                    				text-align: center;">
                    				<?=$response[2];?>&nbsp;
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                    <td style="width: 15%;"></td>
                    <td style="width: 25%; padding: 5px;" >
                        Enfermedades del Corazón
                    </td>
                    <td style="width: 5%; padding: 2px 0; " >
						<table style="font-size: 100%;">
                    		<tr>
                    			<td style="width: 16px; height:12px; border: 1px solid #000; 
                    				text-align: center;">
                    				<?=$response[5];?>&nbsp;
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                	<td style="width: 20%; "></td>
                </tr>
                <tr>
                	<td style="width: 10%; "></td>
                    <td style="width: 20%; height: 5px; padding: 5px; " >
                        Insuficiencia Renal
                    </td>
                    <td style="width: 5%; padding: 2px 0; " >
                        <table style="font-size: 100%;">
                    		<tr>
                    			<td style="width: 16px; height:12px; border: 1px solid #000; 
                    				text-align: center;">
                    				<?=$response[3];?>&nbsp;
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                    <td style="width: 15%;"></td>
                    <td style="width: 25%; padding: 5px;" >
                        Enfermedades Cerebro Vasculares
                    </td>
                    <td style="width: 5%; padding: 2px 0; " >
						<table style="font-size: 100%;">
                    		<tr>
                    			<td style="width: 16px; height:12px; border: 1px solid #000; 
                    				text-align: center;">
                    				<?=$response[6];?>&nbsp;
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                	<td style="width: 20%; "></td>
                </tr>
            </table>
        </div>
		<br>

		<div style="text-align: left; font-size: 95%; font-weight: bold;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; font-weight: bold; height: 15px; padding-left: 15px; " >
                        V. FORMA DE PAGO
                    </td>
                </tr>
            </table>
			<table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial; ">
                <tr>
                	<td style="width: 10%; "></td>
                    <td style="width: 20%; height: 5px; padding: 5px; " >
                        Pago al Contado
                    </td>
                    <td style="width: 5%; padding: 2px 0; " >
                    	<table style="font-size: 100%;">
                    		<tr>
                    			<td style="width: 16px; height:12px; border: 1px solid #000; 
                    				text-align: center;">
                    				<?=$payment[0];?>&nbsp;
                    			</td>
                    		</tr>
                    	</table> 
                    </td>
                    <td style="width: 15%;"></td>
                    <td style="width: 25%; padding: 5px;" >
                        Debito Automatico
                    </td>
                    <td style="width: 5%; padding: 2px 0;" >
						<table style="font-size: 100%;">
                    		<tr>
                    			<td style="width: 16px; height:12px; border: 1px solid #000; 
                    				text-align: center;">
                    				<?=$payment[1];?>&nbsp;
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                	<td style="width: 20%; "></td>
                </tr>
            </table>
       	</div>
       	<br>

       	<div style="text-align: left; font-size: 95%; font-weight: bold;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; font-weight: bold; height: 15px; padding-left: 15px; " >
                       	VI. PERIODICIDAD
                    </td>
                </tr>
            </table>
			<table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial; ">
                <tr>
                	<td style="width: 10%; "></td>
                    <td style="width: 20%; height: 5px; padding: 5px; " >
                        Pago Anual
                    </td>
                    <td style="width: 5%; padding: 2px 0; " >
                    	<table style="font-size: 100%;">
                    		<tr>
                    			<td style="width: 16px; height:12px; border: 1px solid #000; 
                    				text-align: center;">
                    				<?=$period[0];?>&nbsp;
                    			</td>
                    		</tr>
                    	</table> 
                    </td>
                    <td style="width: 15%;"></td>
                    <td style="width: 25%; padding: 5px;" >
                        Pago Mensual
                    </td>
                    <td style="width: 5%; padding: 2px 0;" >
						<table style="font-size: 100%;">
                    		<tr>
                    			<td style="width: 16px; height:12px; border: 1px solid #000; 
                    				text-align: center;">
                    				<?=$period[1];?>&nbsp;
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                	<td style="width: 20%; "></td>
                </tr>
            </table>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                    <td style="width: 100%; font-weight: bold; height: 15px; padding-left: 15px; " >
                       	Debito en Cuenta de la Entidad Financiera
                    </td>
                </tr>
            </table>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial;">
                <tr>
                	<td style="width: 10%;"></td>
                    <td style="width: 20%; background: #000; color: #fff; 
                    	height: 10px; padding-top: 5px;" >
                        Número de Cuenta 1
                    </td>
                    <td style="width: 40%; padding-top: 5px; padding-left: 5px; 
                    	border-bottom: 1px solid #000;" >
                        <?=$rowDt['cuenta_1'];?>&nbsp;
                    </td>
                    <td style="width: 30%; border-left: 1px solid #000;"></td>
                </tr>
                <tr>
                	<td style="width: 10%;"></td>
                    <td style="width: 20%; background: #000; color: #fff; 
                    	height: 10px; padding-top: 5px;" >
                        Número de Cuenta 2
                    </td>
                    <td style="width: 40%; padding-top: 5px; padding-left: 5px; 
                    	border-bottom: 1px solid #000;" >
                        <?=$rowDt['cuenta_2'];?>&nbsp;
                    </td>
                    <td style="width: 30%; border-left: 1px solid #000;"></td>
                </tr>
                <tr>
                	<td style="width: 10%;"></td>
                    <td style="width: 20%; background: #000; color: #fff; 
                    	height: 10px; padding-top: 5px;" >
                        Tarjeta de Crédito
                    </td>
                    <td style="width: 40%; padding-top: 5px; padding-left: 5px; 
                    	border-bottom: 1px solid #000;" >
                        <?=$rowDt['tarjeta'];?>&nbsp;
                    </td>
                    <td style="width: 30%; border-left: 1px solid #000;"></td>
                </tr>
            </table>
       	</div>
       	<br>

       	<div style="text-align: left; font-size: 85%; font-weight: normal; text-align: justify;">
			Declaro haber contestado con total veracidad  y máxima buena fe a todas las preguntas 
			del presente cuestionario y no haber omitido u ocultado hechos y/o circunstancias que 
			hubieran podido influir en la celebración del contrato de seguro, las mismas que son 
			completas y verídicas.
			<br><br>
			Las declaraciones de salud que hacen anulable el Contrato de Seguros y por las que el 
			asegurado pierde su derecho a indemnización, se enmarcan en los artículos 992: OBLIGACION 
			DE DECLARAR;  993: RETICENCIA O INEXACTTUD; 994: AUSENCIA DE DOLO; 999: DOLO O MALA FE; 
			1038: PERDIDA AL DERECHO DE LA INDEMNIZACION; 1138: IMPUGNACION DEL CONTRATO; 1140: ERROR 
			EN LA EDAD DEL ASEGURADO, del Código de Comercio.
			<br><br>
			Por la presente acepto que esta solicitud no es un contrato de seguro y que este solo 
			existirá si se emite y entrega el Certificado de Cobertura de acuerdo con esta solicitud 
			y los reglamentos de Seguros Masivos autorizados por la APS.
			<br><br>
			Autorizo a Médicos, Clínicas e Institutos de Salud para suministrar a Nacional Vida Seguro 
			de Personas S.A., todos los datos que requiera sobre mi estado de salud antes o después 
			de mi fallecimiento. 
       	</div>
       	<br><br><br>

       	<table 
            cellpadding="0" cellspacing="0" border="0" 
            style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial; ">
            <tr>
            	<td style="width: 5%; ">
            		Fecha:
            	</td>
                <td style="width: 35%; height: 5px; border-bottom: 1px solid #000;" >&nbsp;
                    
                </td>
                <td style="width: 5%;" >
                	Firma:
                </td>
                <td style="width: 35%; border-bottom: 1px solid #000;">&nbsp;
                	
                </td>
                <td style="width: 20%; " >
                </td>
            </tr>
            <tr>
            	<td style="width: 5%; ">
            	</td>
                <td style="width: 35%; height: 5px;" >
                </td>
                <td style="width: 5%;" >
                </td>
                <td style="width: 35%; text-align: center;">
                	SOLICITANTE
                </td>
                <td style="width: 20%; " >
                </td>
            </tr>
        </table>
        <br><br><br>
        <table 
            cellpadding="0" cellspacing="0" border="0" 
            style="width: 100%; height: auto; font-size: 90%; font-weight: bold; font-family: Arial; ">
            <tr>
            	<td style="width: 5%; ">
            	</td>
                <td style="width: 35%; height: 5px; " >
                </td>
                <td style="width: 5%;" >
                	Firma:
                </td>
                <td style="width: 35%; border-bottom: 1px solid #000;">&nbsp;
                	
                </td>
                <td style="width: 20%; " >
                </td>
            </tr>
            <tr>
            	<td style="width: 5%; ">
            	</td>
                <td style="width: 35%; height: 5px;" >
                </td>
                <td style="width: 5%;" >
                </td>
                <td style="width: 35%; text-align: center;">
                	BANCO
                </td>
                <td style="width: 20%; " >
                </td>
            </tr>
        </table>

        <page><div style="page-break-before: always;">&nbsp;</div></page>
		
		<div style="width: 780px; border: 0px solid #FFFF00; ">
        	<table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 70%; font-family: Arial;">
                <tr>
                	<td style="width: 50%; padding: 2px;" valign="top">
            			<table style="width: 100%; font-size: 100%;">
            				<tr>
			                    <td style="width: 100%; text-align: center; font-weight: bold; 
			                    	font-size: 100%; " >
			                        CERTIFICADO INDIVIDUAL DE SEGURO MASIVO VIDA CORTO PLAZO
			                    </td>
			                </tr>
			                <tr>
			                    <td style="width: 100%; text-align: center; font-size: 90%;" >
			                        Formato aprobado por la Autoridad de Fiscalización y Control de 
			                        Pensiones y Seguros APS  mediante Resolución Administrativa 
			                        <br>
			                        APS/DSNo.687-2013 Código 206-934215-2013 07  035 400
			                        <br>
			                        POLIZA DE  SEGURO MASIVO VIDA DE CORTO PLAZO N° <?=$row['no_poliza'];?>&nbsp;
			                    </td>
			                </tr>
			                <!-- <tr>
                                <td style="width: 100%; text-align: right; font-size: 90%; 
                                    font-weight: bold; padding-top: 5px;" >
                                    CERTIFICADO No <?=$row['no_emision'];?>&nbsp;
                                </td>
                            </tr> -->
            			</table>
                        <br>

            			<p style="text-align: justify;">
            				NACIONAL VIDA Seguros de Personas S.A., (denominada en adelante "LA COMPAÑÍA "), 
            				por el presente CERTIFICADO INDIVIDUAL DE SEGURO hace constar que la persona 
            				nominada en la declaración jurada de salud / formulario de seguro, (denominado 
            				en adelante "EL ASEGURADO"), está protegido por la Póliza de Seguro de Vida 
            				Masiva arriba mencionada, de acuerdo a las  siguientes Condiciones Particulares:
            			</p>

            			<table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 90%; font-weight: bold; 
			                	font-family: Arial;">
			                <tr>
			                    <td style="width: 100%; font-weight: bold; height: 15px; 
			                    	padding-left: 15px; " colspan="3">
			                       	1. DATOS DEL ASEGURADO
			                       	<br>
			                       	ASEGURADO
			                    </td>
			                </tr>
			                <tr>
			                	<td style="width: 25%;">
			                		Nombre Completo:
			                	</td>
			                	<td style="width: 65%; border-bottom: 1px solid #000;">
			                		<?=$rowDt['nombre'] . ' ' . $rowDt['paterno'] . ' ' . $rowDt['materno'];?>&nbsp;
			                	</td>
			                	<td style="width: 10%;"></td>
			                </tr>
			                <tr>
			                	<td style="width: 25%;">
			                		Cedula de Identidad:
			                	</td>
			                	<td style="width: 65%; border-bottom: 1px solid #000;">
			                		<?=$rowDt['ci'] . ' ' . $rowDt['ext'];?>&nbsp;
			                	</td>
			                	<td style="width: 10%;"></td>
			                </tr>
			                <tr>
			                	<td style="width: 25%;">
			                		Dirección Domicilio:
			                	</td>
			                	<td style="width: 65%; border-bottom: 1px solid #000;">
			                		<?=$rowDt['direccion'];?>&nbsp;
			                	</td>
			                	<td style="width: 10%;"></td>
			                </tr>
			                <tr>
			                	<td style="width: 25%;">
			                		Fecha de Nacimiento:
			                	</td>
			                	<td style="width: 65%; border-bottom: 1px solid #000;">
			                		<?=$rowDt['fecha_nacimiento'];?>&nbsp;
			                	</td>
			                	<td style="width: 10%;"></td>
			                </tr>
			            </table>
			            <br>

			            <table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 90%; font-weight: normal; 
			                	font-family: Arial;">
			                <tr>
			                    <td style="width: 100%; font-weight: bold; height: 10px; 
			                    	padding-left: 15px; " colspan="2">
			                       	2. COBERTURAS Y CAPITALES ASEGURADOS:
			                    </td>
			                </tr>
			                <tr>
			                	<td style="width: 5%;">
									a. 
			                	</td>
			                	<td style="width: 95%; text-align: justify;">
			                		<u>Muerte por cualquier causa:</u> La compañía asume la muerte  
			                		por cualquier causa de EL ASEGURADO, siempre y cuando la causa 
			                		no se encuentre excluida en el punto  4 del presente certificado.
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%;">
			                		b. 
			                	</td>
			                	<td style="width: 95%; text-align: justify;">
			                		<u>Pago anticipado del Capital Asegurado en caso de Invalidez Total y Permanente:</u>
                                    Cobertura aplicable cuando se presenta la Incapacidad Total y Permanente en forma 
                                    irreversible y por lo menos en un 60% de incapacidad, por Accidente o por Enfermedad
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%;">
			                		c. 
			                	</td>
			                	<td style="width: 95%; text-align: justify;">
			                		<u>Sepelio:</u> El beneficio de sepelio no considera exclusiones 
			                		para EL ASEGURADO y se paga al ocurrir la muerte por cualquier causa.
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%;">
			                		d. 
			                	</td>
			                	<td style="width: 95%; text-align: justify;">
			                		<u>Capitales Asegurados:</u>
			                		<br><br>
			                		<table cellpadding="0" cellspacing="0" border="0" 
			                			style="width: 100%; font-size: 100%; font-weight: bold;">
			                			<tr>
			                				<td style="width: 100%; background: #000; color: #fff;
			                					text-align: center; border: 1px solid #000;" colspan="2">
			                					Expresado en Bolivianos
			                				</td>
			                			</tr>
			                			<tr>
			                				<td style="width: 70%; background: #353535; color: #fff;
                                                border: 1px solid #000;">
			                					Coberturas
			                				</td>
			                				<td style="width: 30%; background: #353535; color: #fff;
                                                border: 1px solid #000;">
			                					Rango de Capitales
			                				</td>
			                			</tr>
			                			<tr>
			                				<td style="width: 70%; border: 1px solid #000; 
			                					background: #000; color: #fff;">
			                					<?=$plan[0]['cov'];?>&nbsp;
			                				</td>
			                				<td style="width: 30%; padding-left: 5px; border: 1px solid #000;">
			                					Hasta Bs. <?=number_format($plan[0]['rank'], 0, '.', ',');?>&nbsp;
			                				</td>
			                			</tr>
			                			<tr>
			                				<td style="width: 70%; border: 1px solid #000; 
			                					background: #000; color: #fff;">
			                					<?=$plan[1]['cov'];?>&nbsp;
			                				</td>
			                				<td style="width: 30%; padding-left: 5px; border: 1px solid #000;">
			                					Hasta Bs. <?=number_format($plan[1]['rank'], 0, '.', ',');?>&nbsp;
			                				</td>
			                			</tr>
			                			<tr>
			                				<td style="width: 70%; border: 1px solid #000; 
			                					background: #000; color: #fff;">
			                					<?=$plan[2]['cov'];?>&nbsp;
			                				</td>
			                				<td style="width: 30%; padding-left: 5px; border: 1px solid #000;">
			                					Hasta Bs. <?=number_format($plan[2]['rank'], 0, '.', ',');?>&nbsp;
			                				</td>
			                			</tr>
			                		</table>

									<table cellpadding="0" cellspacing="0" border="0" 
			                			style="width: 100%; font-size: 100%; font-weight: normal;
			                				margin-top: 10px;">
			                			<tr>
			                				<td style="width: 20%;">
			                					Límites de edad:
			                				</td>
			                				<td style="width: 20%;">
			                					De Ingreso:
			                				</td>
			                				<td style="width: 60%; text-align: justify;">
			                					De Ingreso:	Mayores de 14 años y  hasta los 70 años por 
			                					muerte por cualquier causa. Mayores de 14 años y hasta 
			                					los 64 años para Pago anticipado del Capital Asegurado
                                                en caso de Invalidez Total y Permanente
			                				</td>
			                			</tr>
			                			<tr>
			                				<td style="width: 20%;">
			                					
			                				</td>
			                				<td style="width: 20%;">
			                					De permanencia:
			                				</td>
			                				<td style="width: 60%; text-align: justify;">
			                					De permanencia: Hasta los 75 años en caso de muerte por 
			                					cualquier causa Hasta los 65 años de Pago anticipado del 
                                                Capital Asegurado en caso de Invalidez Total y Permanente.
			                				</td>
			                			</tr>
			                		</table>

			                	</td>
			                </tr>
		                </table>
						<br>

						<table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 90%; font-weight: normal; 
			                	font-family: Arial;">
			                <tr>
			                    <td style="width: 100%; font-weight: bold; height: 10px; 
			                    	padding-left: 15px; " colspan="2">
			                       	3. PUNTO DE VENTA:
			                    </td>
			                </tr>
			                <tr>
			                	<td style="width: 30%;">
			                		Nombre de la Razón Social:
			                	</td>
			                	<td style="width: 70%;">
			                		Banco Económico S.A.
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 30%;">
			                		Dirección:
			                	</td>
			                	<td style="width: 70%;">
			                		<?=$row['user_departamento'] . ', ' . $row['agencia'];?>&nbsp;
			                	</td>
			                </tr>
		                </table>
		                <br>

		                <table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 90%; font-weight: normal; 
			                	font-family: Arial;">
			                <tr>
			                    <td style="width: 100%; font-weight: bold; height: 10px; 
			                    	padding-left: 15px; " colspan="2">
			                       	4. EXCLUSIONES: 
			                       	<span style="font-weight: normal;">
			                       		Este seguro de  vida no será aplicable en ninguna de 
			                       		las siguientes circunstancias:
			                       	</span>
			                    </td>
			                </tr>
                            <tr>
			                	<td colspan="2">Para Cobertura de Muerte por Cualquier Causa:</td>			                	
			                </tr>
			                <tr>
			                	<td style="width: 5%; text-align: center; font-weight: normal;">*</td>
			                	<td style="width: 95%;">
			                		Participación de EL ASEGURADO en actos de guerra, declarada o no, sedición, 
                                    rebelión, asonada, conspiración, motín, tumulto, o cualquier acto que tenga 
                                    relación con ellos, salvo comprobación de que EL ASEGURADO no haya participado, 
                                    o formado parte activa de dichos actos de manera directa.
			                	</td>
			                </tr>			                
							
			                <tr>
			                	<td style="width: 5%; text-align: center; font-weight: normal;">*</td>
			                	<td style="width: 95%;">
			                		Participación de EL ASEGURADO como conductor o acompañante en competencias de 
                                    velocidad o resistencia, de automóviles motocicletas, lanchas o en cualquier 
                                    clase de vehículos a motor.
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%; text-align: center; font-weight: normal;">*</td>
			                	<td style="width: 95%;">
			                		Suicidio durante el primer año de haber estado asegurado ininterrumpidamente.
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%; text-align: center; font-weight: normal;">*</td>
			                	<td style="width: 95%;">
			                		Fisión o Fusión nuclear o contaminación radioactiva.
			                	</td>
			                </tr>
                            <tr>
			                	<td colspan="2">La Cobertura de Sepelio no tiene exclusiones.</td>			                	
			                </tr>
                            <tr>
			                	<td colspan="2">Para la cobertura Pago anticipado del Capital Asegurado 
                                en caso de Invalidez Total y Permanente:</td>			                	
			                </tr>
			                <tr>
			                	<td style="width: 5%; text-align: center; font-weight: normal;">*</td>
			                	<td style="width: 95%;">
			                		La utilización por EL ASEGURADO de medios de transporte aéreo no comercial, 
                                    salvo en calidad de pasajero de líneas aéreas debidamente autorizadas para 
                                    el transporte público.
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%; text-align: center; font-weight: normal;">*</td>
			                	<td style="width: 95%;">
			                		Que EL ASEGURADO se encuentre en estado de ebriedad o bajo los efectos de Alcohol, 
                                    drogas o alucinógenos.
			                	</td>
			                </tr>
							<tr>
			                	<td style="width: 5%; text-align: center; font-weight: normal;">*</td>
			                	<td style="width: 95%;">
			                		Falsas declaraciones, omisión o reticencia del Asegurado que puedan influir en la 
                                    comprobación de su estado de invalidez.
			                	</td>
			                </tr>
							<tr>
			                	<td style="width: 5%; text-align: center; font-weight: normal;">*</td>
			                	<td style="width: 95%;">
			                		Intento de suicidio cualquiera sea la época en que ocurra o por medidas o lesiones 
                                    inferidas AL ASEGURADO por sí mismo o por terceros con su consentimiento.
			                	</td>
			                </tr>
							
		                </table>
		                <br>

		                <table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 90%; font-weight: normal; 
			                	font-family: Arial;">
			                <tr>
			                    <td style="width: 100%; font-weight: bold; height: 10px; 
			                    	padding-left: 15px; " colspan="3">
			                       	5. COSTO DE LA COBERTURA: 
			                    </td>
			                </tr>
			                <tr>
			                	<td style="width: 22%; background: #000; color: #FFF; padding: 3px;
                                    border: 1px solid #000;">
			                		Prima Neta
			                	</td>
			                	<td style="width: 28%; border: 1px solid #000; padding: 5px;">
                                    <?=$link->prima['VI'][$row['plan_nombre']]['PN'];?>
                                </td>
			                	<td style="width: 50%;"></td>
			                </tr>
			                <tr>
			                	<td style="width: 22%; background: #000; color: #FFF; padding: 3px;
                                    border: 1px solid #000;">
			                		Impuestos
			                	</td>
			                	<td style="width: 28%; border: 1px solid #000; padding: 5px;">
                                    <?=$link->prima['VI'][$row['plan_nombre']]['IM'];?>
                                </td>
			                	<td style="width: 50%;"></td>
			                </tr>
			                <tr>
			                	<td style="width: 22%; background: #000; color: #FFF; padding: 3px;
                                    border: 1px solid #000;">
			                		Comision Corretaje
			                	</td>
			                	<td style="width: 28%; border: 1px solid #000; padding: 5px;">
                                    <?=$link->prima['VI'][$row['plan_nombre']]['CC'];?>
                                </td>
			                	<td style="width: 50%;"></td>
			                </tr>
			                <tr>
			                	<td style="width: 22%; background: #000; color: #FFF; padding: 3px;
                                    border: 1px solid #000;">
			                		Prima Comercial
			                	</td>
			                	<td style="width: 28%; border: 1px solid #000; padding: 5px;">
			                		<?=$row['prima'];?>&nbsp;
			                	</td>
			                	<td style="width: 50%;"></td>
			                </tr>
		                </table>
                	</td>
                	<td style="width: 50%; padding: 2px;" valign="top">
            			 <table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 90%; font-weight: normal; 
			                	font-family: Arial;">
			                <tr>
			                    <td style="width: 100%; font-weight: bold; height: 10px; 
			                    	padding-left: 15px; padding-bottom: 5px;" colspan="5">
			                       	6. BENEFICIARIOS: 
			                    </td>
			                </tr>
			                <tr>
                                <td style="width: 15%; text-align: center; background: #000;
                                    color: #FFF; height: 10px;">
                                </td>
			                	<td style="width: 35%; background: #000; color: #FFF; 
			                		height: 10px; text-align: center; border: 1px solid #000;">
			                		Nombre Completo
			                	</td>
			                	<td style="width: 15%; background: #000; color: #FFF;
			                		text-align: center; border: 1px solid #000;">
			                		Parentesco
			                	</td>
			                	<td style="width: 20%; background: #000; color: #FFF;
			                		text-align: center; border: 1px solid #000;">
			                		C.I.
		                		</td>
			                	<td style="width: 15%; background: #000; color: #FFF;
			                		text-align: center; border: 1px solid #000;">
			                		Proporción
			                	</td>
			                </tr>
<?php
		if ($rsBs->data_seek(0) === true) {
			$k = 0;
			while ($rowBs = $rsBs->fetch_array(MYSQLI_ASSOC)) {
				$k += 1;
?>
							<tr>
			                	<td style="width: 15%; text-align: center; background: #000;
			                		color: #FFF; border: 1px solid #000; height: 10px;">
			                		Beneficiario <?=$k;?>&nbsp;
			                	</td>
			                	<td style="width: 35%; text-align: center; border: 1px solid #000;">
			                		<?=$rowBs['nombre'] . ' ' . $rowBs['paterno'] . ' ' . $rowBs['materno'];?>&nbsp;
			                	</td>
			                	<td style="width: 15%; text-align: center; border: 1px solid #000;">
			                		<?=$rowBs['parentesco'];?>&nbsp;
			                	</td>
			                	<td style="width: 20%; text-align: center; border: 1px solid #000;">
			                		<?=$rowBs['ci'];?>&nbsp;
			                	</td>
			                	<td style="width: 15%; text-align: center; border: 1px solid #000;">
			                		<?=$rowBs['porcentaje_credito'];?>&nbsp;
			                	</td>
			                </tr>
<?php
			}
		}
?>
		                	<tr>
		                		<td style="width: 85%; background: #000; color: #FFF;" colspan="4"></td>
		                		<td style="width: 15%; background: #000; color: #FFF; 
		                			text-align: center; border: 1px solid #000;">100%
		                		</td>
		                	</tr>
		                </table>
		                <br>

		                <table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 90%; font-weight: normal; 
			                	font-family: Arial;">
			                <tr>
			                    <td style="width: 100%; font-weight: bold; height: 10px; 
			                    	padding-left: 15px; " colspan="2">
			                       	7. PROCEDIMIENTO A SEGUIR EN CASO DE SINIESTRO: 
			                    </td>
			                </tr>
			                <tr>
			                	<td style="width: 100%;" colspan="2">
			                    	El Asegurado o Beneficiario, tan pronto y a más tardar dentro de los 30 días de tener conocimiento del siniestro, debe comunicar tal hecho a la aseguradora, salvo fuerza mayor o impedimiento jurídico.
									<br>
									Para reclamar el pago de cualquier indemnización con cargo 
			                    	a esta póliza, EL ASEGURADO O BENEFICIARIO deberá remitir a 
			                    	LA COMPAÑÍA su solicitud junto con los documentos a presentar 
			                    	en caso de fallecimiento o invalidez. LA COMPAÑÍA podrá, a sus 
			                    	expensas, recabar informes o pruebas complementarias.
			                    </td>
			                </tr>
			                <tr>
			                	<td style="width: 100%; font-weight: bold; padding: 10px 0;" 
			                		colspan="2">
			                		DOCUMENTOS  A PRESENTAR  EN CASO DE  MUERTE POR CUALQUIER CAUSA:
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Certificado Médico de defunción.
			                	</td>
			                </tr>
			                <!--<tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Certificado Médico indicando la causa primaria, secundaria y la 
			                		causa agravante del fallecimiento de acuerdo a normas de la 
			                		Organización Mundial de la Salud.
			                	
								</td>
			                </tr>-->
			                <!--<tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Certificado del Médico Forense (si corresponde). 
			                	</td>
			                </tr>-->
			                <tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Certificado de la Autoridad competente (si corresponde).
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Documento de identidad (carnet de identidad o certificado 
			                		de nacimiento) del asegurado.
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Documento de identidad (Carnet de identidad o Certificado 
			                		de nacimiento) del beneficiario.
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Declaratoria de Herederos si no existieran Beneficiarios 
			                		nominados en la Póliza.
			                	</td>
			                </tr>
							<tr>
			                	<td style="width: 100%; font-weight: bold; padding: 10px 0;" 
			                		colspan="2">
			                		DOCUMENTOS  A PRESENTAR  EN CASO DE  INVALIDEZ TOTAL PERMANENTE
			                	</td>
			                </tr>
							<tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%; text-align: justify;">
			                	<!--	Informe detallado del médico o médicos que hayan tratado al Asegurado, 
			                		con indicación del origen, de la naturaleza, del desarrollo y de las 
			                		consecuencias de la enfermedad o de las lesiones causantes de la 
			                		invalidez, así como de la probable duración de la misma; Asimismo 
			                		deberá presentar todos los exámenes de laboratorio y otros que 
			                		certifiquen el diagnóstico. -->
			                	Declaración médica de invalidez, emitida por un médico autorizado por la APS.
								</td>
			                </tr>
							<tr>
			                	<td style="width: 100%; font-weight: bold; padding: 10px 0;" 
			                		colspan="2">
			                		DOCUMENTOS  A PRESENTAR  EN CASO DE  SEPELIO
			                	</td>
			                </tr>
							<tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Certificado de defunción.
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Documento de identidad (carnet de identidad o certificado 
			                		de nacimiento) del asegurado.
								
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Documento de identidad (Carnet de identidad o Certificado de 
			                		nacimiento) del beneficiario.
			                	</td>
			                </tr>
			                <tr>
			                	<td style="width: 5%; font-weight: bold; text-align: center;">*</td>
			                	<td style="width: 95%;">
			                		Declaratoria de Herederos si no existieran Beneficiarios 
			                		nominados en la Póliza.
			                	</td>
			                </tr>
		                </table>
		                <br>

		                <table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 90%; font-weight: normal; 
			                	font-family: Arial;">
			                <tr>
			                    <td style="width: 100%; font-weight: bold; height: 10px; 
			                    	padding-left: 15px; ">
			                       	8. COMPAÑÍA ASEGURADORA
			                    </td>
			                </tr>
			                <tr>
			                	<td style="width: 100%;">
			                		<table cellpadding="0" cellspacing="0" border="0" 
			                			style="font-size: 100%;">
			                			<tr>
			                				<td style="width: 14%;">
			                					Razón Social:
			                				</td>
			                				<td style="width: 86%; border-bottom: 1px solid #000;" 
			                					colspan="5">
			                					Nacional Vida Seguros de Personas S.A.
			                				</td>
			                			</tr>
			                			<tr>
			                				<td style="14%;">Dirección:</td>
			                				<td style="36%; border-bottom: 1px solid #000;">
			                					Av. Monseñor Rivero  N 223
			                				</td>
			                				<td style="7%;">Teléfono:</td>
			                				<td style="18%; border-bottom: 1px solid #000;">
			                					3716262
			                				</td>
			                				<td style="7%;">Fax:</td>
			                				<td style="18%; border-bottom: 1px solid #000;">
			                					3337969
			                				</td>
			                			</tr>
			                		</table>
			                	</td>
			                </tr>
		                </table>
		                <br>

		                <table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 90%; font-weight: normal; 
			                	font-family: Arial;">
			                <tr>
			                    <td style="width: 100%; font-weight: bold; height: 10px; 
			                    	padding-left: 15px; ">
			                       	9. CORREDOR DE SEGUROS
			                    </td>
			                </tr>
			                <tr>
			                	<td style="width: 100%;">
			                		<table cellpadding="0" cellspacing="0" border="0" 
			                			style="font-size: 100%;">
			                			<tr>
			                				<td style="width: 14%;">
			                					Razón Social:
			                				</td>
			                				<td style="width: 86%; border-bottom: 1px solid #000;" 
			                					colspan="5">
			                					Sudamericana S.R.L.
			                				</td>
			                			</tr>
			                			<tr>
			                				<td style="14%;">Dirección:</td>
			                				<td style="36%; border-bottom: 1px solid #000;">
			                					Equipetrol Calle Nº 8 Este Nº 25
			                				</td>
			                				<td style="7%;">Teléfono:</td>
			                				<td style="18%; border-bottom: 1px solid #000;">
			                					3416055
			                				</td>
			                				<td style="7%;">Fax:</td>
			                				<td style="18%; border-bottom: 1px solid #000;">
			                					3416056
			                				</td>
			                			</tr>
			                		</table>
			                	</td>
			                </tr>
		                </table>
		                <br>
						<!--
		                <p style="text-align: justify;">
		                	<b>Discrepancias en la Póliza;</b> (Art. 1013) " Si el Tomador o 
		                	ASEGURADO encuentran que la póliza no concuerda con lo convenido o 
		                	con lo propuesto, pueden pedir rectificación correspondiente por escrito, 
		                	dentro de los quince días siguientes a la recepción de la Póliza. Se 
		                	consideran aceptadas las estipulaciones de esta si durante dicho plazo 
		                	no se solicita la mencionada rectificación. Si dentro de los quince días 
		                	siguientes al de la reclamación LA COMPAÑÍA no da curso a la rectificación 
		                	solicitada o mantiene silencio, se entiende aceptada en los términos de 
		                	la modificación".
		                </p>

		                <p style="text-align: justify;">
		                	<b>Omisión de Aviso (Art. 1030).</b> " LA COMPAÑIA puede liberarse de 
		                	sus obligaciones cuando EL ASEGURADO o beneficiario, según el caso, 
		                	omitan dar el aviso dentro del plazo del articulo 1028 con el fin de 
		                	impedir la comprobación oportuna de las circunstancias del siniestro 
		                	o el de la magnitud de los daños.(Art.1035CódigodeComercio)".
		                </p>
						-->		                
						<br>

						<table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 110%; font-weight: normal; 
			                	font-family: Arial; margin-top: 5px;">
			                	<tr>
			                		<td style="width: 13%;"></td>
			                		<td style="width: 20%; border-bottom: 1px solid #000; 
			                			text-align: center;">
			                			Santa Cruz
			                		</td>
			                		<td style="width: 4%; text-align: center;"> , </td>
			                		<td style="width: 10%; border-bottom: 1px solid #000; 
			                			text-align: center;">
			                			<?=date('d', strtotime($row['fecha_emision']));?>&nbsp;
			                		</td>
			                		<td style="width: 5%; text-align: center;"> de </td>
			                		<td style="width: 20%; border-bottom: 1px solid #000; 
			                			text-align: center;">
			                			<?=$link->get_month(date('n', strtotime($row['fecha_emision'])));?>&nbsp;
			                		</td>
			                		<td style="width: 5%; text-align: center;"> de </td>
			                		<td style="width: 10%; border-bottom: 1px solid #000; 
			                			text-align: center;">
			                			<?=date('Y', strtotime($row['fecha_emision']));?>&nbsp;
			                		</td>
			                		<td style="width: 13%;"></td>
			                	</tr>
	               		</table>
	               		<br>

	               		<table 
			                cellpadding="0" cellspacing="0" border="0" 
			                style="width: 100%; height: auto; font-size: 110%; font-weight: normal; 
			                	font-family: Arial; margin-top: 5px;">
			                <tr>
			                	<td style="width: 5%;"></td>
			                	<td style="width: 90%; text-align: center; padding-bottom: 20px;"
			                		colspan="3">
			                		NACIONAL VIDA SEGURO DE  PERSONAS S.A.
			                		<br>
			                		FIRMAS AUTORIZADAS
			                	</td>
			                	<td style="width: 5%;"></td>
			                </tr>
			                <tr>
			                	<td style="width: 5%;"></td>
			                	<td style="width: 35%; border-bottom: 1px solid #000; 
			                		text-align: center;">
			                		<img src="<?=$url;?>img/f_joaquin.jpg" alt="" height="80">
		                		</td>
			                	<td style="width: 10%;"></td>
			                	<td style="width: 45%; border-bottom: 1px solid #000; 
			                		text-align: center;">
			                		<img src="<?=$url;?>img/f_mario.jpg" alt="" height="80">
		                		</td>
			                	<td style="width: 5%;"></td>
			                </tr>
			                <tr>
			                	<td style="width: 5%;"></td>
			                	<td style="width: 35%; text-align: center;">
			                		Joaquín Montaño Salas <br>
									Gerente Regional
			                	</td>
			                	<td style="width: 10%;"></td>
			                	<td style="width: 45%; text-align: center;">
			                		Mario Aguirre <br>
									Gerente Nacional de Seguros Masivos
			                	</td>
			                	<td style="width: 5%;"></td>
			                </tr>
	                	</table>
                	</td>
                </tr>
                
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                
                <tr>
                	<td colspan="2">
                    	<p style="text-align: justify;">
		                	<b>Discrepancias en la Póliza (Art. 1013) </b>"Si el Tomador o ASEGURADO 
                            encuentran que la póliza no concuerda con lo convenido o con lo propuesto, 
                            pueden pedir rectificación correspondiente por escrito, dentro de los quince 
                            días siguientes a la recepción de la Póliza. Se consideran aceptadas las 
                            estipulaciones de esta si durante dicho plazo no se solicita la mencionada 
                            rectificación. Si dentro de los quince días siguientes al de la reclamación 
                            LA COMPAÑÍA no da curso a la rectificación solicitada o mantiene silencio, 
                            se entiende aceptada en los términos de la modificación".
		                	<br>
                            <b>Omisión de Aviso (Art. 1030) </b>"LA COMPAÑIA puede liberarse de sus 
                            obligaciones cuando EL ASEGURADO o beneficiario, según el caso, omitan dar el 
                            aviso dentro del plazo del articulo 1028  con el fin de impedir la comprobación 
                            oportuna de las circunstancias del siniestro o el de la magnitud de los daños.
                            (Art.1035 Código de Comercio)".
		                	<br>
                            <b>Iniciación y Duración del Contrato. </b>"El presente seguro tendrá una duración 
                            de un año a contar de la fecha de inicio de la cobertura, salvo que en las 
                            Condiciones Particulares, Certificado de Cobertura  se estipule una duración 
                            diferente, el cual se entenderá prorrogado en forma tácita, sucesiva y automática 
                            por nuevos e iguales períodos en cada ocasión".
		                	<br>
                            <b>Periodo de Gracia. </b>LA COMPAÑÍA concederá al ASEGURADO un periodo de gracia para 
                            efectuar el pago de la prima correspondiente sin intereses de treinta (30) días calendario, 
                            contados desde el vencimiento de la fecha establecida en el convenio de pago para el 
                            pago de la misma.  Si el fallecimiento ocurriese dentro del periodo de gracia, la prima 
                            adeudada para completar la anualidad por EL TOMADOR/ ASEGURADO será deducida del 
                            beneficio correspondiente.
		                	<br>
                            <b>Efecto del no pago de Prima: Terminación del Contrato. </b>Si habiendo vencido el 
                            período de gracia fijado por el artículo precedente, la Prima se encontrare impaga, 
                            el Contrato de Seguro caducara en forma inmediata, sin necesidad de aviso, notificación 
                            o requerimiento alguno, liberándose LA COMPAÑÍA  de toda obligación y responsabilidad 
                            derivada de la Póliza.
		                	<br>
                            <b>Plazo para Pronunciarse (Art. 1033). </b>LA COMPAÑÍA se pronunciará 
		                	sobre el derecho de EL ASEGURADO o beneficiario dentro de los treinta 
		                	(30) días de recibidas la información y evidencias citadas en  el 
		                	Art.1031 del Código de Comercio.  Se dejará constancia escrita de la 
		                	fecha de recepción de la información y evidencias a efecto del cómputo 
		                	del plazo. En plazo de treinta (30) días, fenece con la aceptación o 
		                	rechazo del siniestro o con la solicitud del asegurador al asegurado 
		                	que se complementen los requerimientos contemplados en el Art. 1031 
		                	del Código de Comercio y no vuelve a correr hasta que el asegurado haya 
		                	cumplido con tales requerimientos.
		                	<br>
		                	La solicitud de complementos establecidos en el Art. 1031 del Código 
		                	de Comercio, por parte del Asegurador no podrá extenderse por mas de 
		                	dos veces a partir de la primera solicitud de informes y evidencias, 
		                	debiendo pronunciarse dentro del plazo establecido y de manera definitiva 
		                	sobre el derecho del asegurado, después de la entrega por parte del 
		                	asegurado del último requerimiento de información.
		                	<br>
		                	El silencio del asegurador, vencido el término  para pronunciarse o 
		                	vencidas las solicitudes de complementación, importa la aceptación del 
		                	reclamo.
		                </p>

		                <p style="text-align: justify;">
		                	<b>Termino para el pago de Siniestro (Art. 1034).</b> En los seguros de 
		                	vida, el pago se hará dentro de los quince días posteriores al aviso del 
		                	siniestro o tan pronto sean llenados los requerimientos señalados en el 
		                	artículo 1031".
		                </p>
                    </td>
                </tr>    
            </table>
		</div>
<?php
		if ($nt < $nCl) {
?>
		<page><div style="page-break-before: always;">&nbsp;</div></page>
<?php
		}
?>
    </div>
</div>
<?php
	}
	$html = ob_get_clean();
    return $html;
}
?>