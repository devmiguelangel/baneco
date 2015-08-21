<?php
function ap_em_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '', $rsBs) {
	
		ob_start();
?>

<div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
		<div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
        font-family: Arial, Helvetica, sans-serif; color: #000000;">
	
<?php
			$j = 1;
			$num_titulares=$rsDt->num_rows;
			
			while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
				$date_birth = strtotime($rowDt['fecha_nacimiento']);
?>

                <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
                    <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 100%; height: auto; font-family: Arial;">
                        <tr>
                          <td style="width:100%; text-align:right;">
                             <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/>
                          </td> 
                        </tr>
                        <tr>
                          <td style="width:100%; font-weight:bold; text-align:center; font-size: 80%;">
                             DECLARACIÓN JURADA DE SALUD<br />SOLICITUD DE SEGURO MASIVO ACCIDENTES PERSONALES
                          </td> 
                        </tr>
                        <tr>
                          <td style="width:100%; font-size:70%; text-align:center;">
                             Formato aprobado por la Autoridad de Fiscalización y Control de Pensiones 
                    y Seguros –APS  mediante RA APS/DS/No. 663 del 24/07/2013<br />Código 206-935022-2013 07 034 3001
                          </td>
                        </tr>
                        <tr>
                          <td style="width:100%; font-weight:bold; font-size:70%; text-align:center; padding-top:10px;">
                            El interesado solicita  a Nacional Vida Seguros de 
                    Personas S.A, un seguro contra Accidentes Personales, basado en las declaraciones que siguen a 
                    continuación, las  mismas que formaran parte integrante e indivisible de la póliza:
                          </td> 
                        </tr>
                    </table>     
                </div>
                <br/>

				
		 		<div style="width: 775px; border: 0px solid #FFFF00;">
			    	<span style="font-weight:bold; font-size:80%;">DATOS PERSONALES DEL SOLICITANTE:</span>

                    <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                        <tr> 
                          <td style="width:100%; padding-bottom:4px;">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                <tr>
                                  <td style="width:7%;">Nombres: </td>
                                  <td style="border-bottom: 1px solid #333; width:43%;">
                                      <?=$rowDt['nombre'];?>
                                  </td>
                                  <td style="width:7%;">Apellidos: </td>
                                  <td style="border-bottom: 1px solid #333; width:43%;">
                                      <?=$rowDt['paterno'] . ' ' . $rowDt['materno'];?>   
                                  </td>
                                </tr>
                             </table>
                          </td>      
                        </tr>
                        <tr>
                          <td style="width:100%; padding-bottom:4px;">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                <tr>
                                  <td style="width:14%;">Lugar de Nacimiento: </td>
                                  <td style="border-bottom: 1px solid #333; width:16%;">
                                      <?=$rowDt['lugar_nacimiento'];?>
                                  </td>
                                  <td style="width:14%;">Fecha de Nacimiento: </td>
                                  <td style="width:16%; border-bottom: 1px solid #333;">
                                      <?=date('d', $date_birth) . ' de ' 
                                      . date('m', $date_birth) . ' de ' . date('Y', $date_birth);?>
                                  </td>
                                  <td style="width:5%;">Edad: </td>
                                  <td style="width:35%; border-bottom: 1px solid #333;">
                                      <?=$rowDt['edad'];?>
                                  </td>   
                                </tr>
                              </table>                                  
                          </td>
                        </tr>   
                        <tr>
                          <td style="width:100%; padding-bottom:4px;">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                  <td style="width:18%;">N&deg; Documento de Identidad: </td>
                                  <td style="border-bottom: 1px solid #333; width:15%;">
                                     <?=$rowDt['ci'].' '.$rowDt['ext'];?>
                                  </td>
                                  <td style="width:67%;">&nbsp;</td>  
                                 </tr> 
                              </table>
                          </td>              
                        </tr>
                        <tr>
                          <td style="width:100%; padding-bottom:4px;">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                  <td style="width:13%;">Dirección Domicilio: </td>
                                  <td style="border-bottom: 1px solid #333; width:87%;">
                                     <?=$rowDt['direccion'];?>
                                  </td>
                                 </tr> 
                              </table>
                          </td> 
                        </tr>
                        <tr>
                          <td style="width:100%; padding-bottom:4px;">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width: 13%;">Teléfono Domicilio: </td>
                                   <td style="width: 22%; border-bottom: 1px solid #333;">
								     <?=$rowDt['telefono_domicilio'];?>
                                   </td>
                                   <td style="width: 12%;">Teléfono Oficina: </td>
                                   <td style="width: 19%; border-bottom: 1px solid #333;">
<?php
                                        if(!empty($rowDt['telefono_oficina']))
										   echo $rowDt['telefono_oficina'];
										else
										   echo'&nbsp;';   
?>
                                   </td>
                                   <td style="width: 12%;">Teléfono Celular: </td>
                                   <td style="width: 22%; border-bottom: 1px solid #333;">
<?php
                                         if(!empty($rowDt['telefono_celular']))
										   echo $rowDt['telefono_celular'];
										 else
										   echo'&nbsp;';  
?>
                                   </td>
                                 </tr>
                              </table> 
                          </td> 
                        </tr>
                        <tr>
                          <td style="width:100%; padding-bottom:4px;">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                  <td style="width:12%;">Lugar de Trabajo: </td>
                                  <td style="width:88%; border-bottom: 1px solid #333;">
<?php
                                        if(!empty($rowDt['lugar_trabajo']))
										  echo $rowDt['lugar_trabajo'];
										else
										  echo'&nbsp;';  
?>
                                  </td> 
                                 </tr> 
                              </table>
                          </td>     
                        </tr> 
                    </table><br/>
                    
			        <span style="font-weight:bold; font-size:80%;">CUESTIONARIO:</span>
                    <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                        <tr>
                            <td style="width:28%;">¿Cuál es su profesión u ocupación habitual?</td>
                            <td style="border-bottom: 1px solid #333; width:72%;">
                                <?=$rowDt['desc_ocupacion'];?>
                            </td>
                        </tr>
                        <tr><td style="width:100%;" colspan="2"><div style="height:5px;"></div></td></tr>
                        <tr>
                          <td colspan="2" style="width:100%;">                       
<?php
						$hand = array('DE' => '', 'IZ' => '');
					
						switch ($rowDt['mano']) {
						case 'DE':
							$hand['DE'] = 'X';
							break;
						case 'IZ':
							$hand['IZ'] = 'X';
							break;
						}
					
						$rowDt['respuesta'] = json_decode($rowDt['respuesta']);
						$response = array();
					
						foreach ($rowDt['respuesta'] as $key => $value) {
							$res = explode('|', $value);
							
							switch ($res[1]) {
							case 0:
								$response[$key][0] = '';
								$response[$key][1] = 'X';
								break;
							case 1:
								$response[$key][0] = 'X';
								$response[$key][1] = '';
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
					
						$plan = json_decode($row['plan'], true);
?>                         <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                             <tr>
                                <td style="width:80%; text-align:left;">
                                    ¿Practica algún deporte a nivel profesional o considerado de alto riesgo?
                                    (Alpinismo, automovilismo, etc.)
                                </td>
                                <td style="width:4%;">SI</td>
                                <td style="width:6%;">
                                   <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                                    <?=$response[1][0];?>
                                   </div> 
                                </td>
                                <td style="width:4%;">NO</td>
                                <td style="width:6%;">
                                   <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                                    <?=$response[1][1];?>
                                   </div> 
                                </td>
                             </tr>
                             <tr>
                                <td style="width:80%; text-align:left;">
                                    ¿Tiene alguna discapacidad fisica (visual, auditiva, paralisis)?
                                </td>
                                <td style="width:4%;">SI</td>
                                <td style="width:6%;">
                                   <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                                    <?=$response[2][0];?>
                                   </div> 
                                </td>
                                <td style="width:4%;">NO</td>
                                <td style="width:6%;">
                                   <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                                    <?=$response[2][1];?>
                                   </div> 
                                </td>
                             </tr>
                             <tr>
                                <td style="width:80%; text-align:left;">¿Es zurdo?</td>
                                <td style="width:4%;">SI</td>
                                <td style="width:6%;">
                                   <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
								    <?=$hand['IZ'];?>
                                   </div> 
                                </td>
                                <td style="width:4%;">NO</td>
                                <td style="width:6%;">
                                   <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
								    <?=$hand['DE'];?>
                                   </div> 
                                </td>
                             </tr>
                           </table> 
                          </td>
                        </tr>
			        </table><br/>  
			        
			        <span style="font-weight:bold; font-size:80%;">ELECCION DEL PLAN:</span><br/>
			        
                    <span style="font-weight:bold; font-size:80%;">1. Planes del Seguro</span>
			        <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 70%; height: auto; font-size: 80%; font-family: Arial; color:#fff;">
			
                         <tr><td colspan="2" style="text-align:center; background:#000;
                         border-bottom: 1px solid #333; border-top: 1px solid #333; border-right: 1px solid #333;
                         width:70%; border-left: 1px solid #333;">Expresado en Bolivianos</td></tr>
                         <tr style="background:#333;">
                             <td style="width:50%; border-right: 1px solid #333; border-bottom: 1px solid #333;
                             border-left: 1px solid #333;">
                             Coberturas</td>
                             <td style="width:20%; border-bottom: 1px solid #333; 
                             border-right: 1px solid #333;">Rango de Capitales</td>                     
                         </tr>
                         <tr>
                            <td style="width:50%; border-bottom: 1px solid #333; border-left: 1px solid #333; 
                            background:#000; border-right: 1px solid #333;"><?=$plan[0]['cov'];?></td>
                            <td style="width:20%; color:#000; border-bottom: 1px solid #333; 
                            border-right: 1px solid #333;">
                                Hasta Bs. <?=number_format($plan[0]['rank'], 0, '.', ',');?>
                            </td>
                         </tr>
                         <tr>
                            <td style="width:50%; border-bottom: 1px solid #333; border-left: 1px solid #333; 
                            background:#000; border-right: 1px solid #333;"><?=$plan[1]['cov'];?></td>
                            <td style="width:20%; color:#000; border-bottom: 1px solid #333; 
                            border-right: 1px solid #333;">
                                Hasta Bs. <?=number_format($plan[1]['rank'], 0, '.', ',');?>
                            </td>
                         </tr>
                         <tr>
                            <td style="width:50%; background:#000; border-right: 1px solid #333; 
                            border-bottom: 1px solid #333; border-left: 1px solid #333;">
							<?=$plan[2]['cov'];?></td>
                            <td style="width:20%; color:#000; border-right: 1px solid #333; 
                            border-bottom: 1px solid #333;">
                                Hasta Bs. <?=number_format($plan[2]['rank'], 0, '.', ',');?>
                            </td>
                         </tr>
			        </table><br/>
			        
                    <span style="font-weight:bold; font-size:80%;">2. FORMA DE PAGO</span>
			        <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 50%; height: auto; font-size: 80%; font-family: Arial; margin-left:40px;">
                        <tr>
                            <td style="width: 22%;">Pago al Contado</td>
                            <td style="width: 28%;" align="left">
                              <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                                <?=$payment[0];?>
                              </div>  
                            </td>
                        </tr>
                        <tr><td colspan="2" style="width:50%;"><div style="height:5px;">&nbsp;</div></td></tr>
                        <tr>    
                            <td style="width: 22%;">Debito Automático</td>
                            <td style="width: 28%;" align="left">
                               <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                                <?=$payment[1];?>
                               </div> 
                            </td>
                        </tr>
                    </table><br/>
		
		            <span style="font-weight:bold; font-size:80%;">3. PERIODICIDAD</span>
		            <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 50%; height: auto; font-size: 80%; font-family: Arial; margin-left:40px;">
                        <tr>
                            <td style="width: 22%;">Pago Anual</td>
                            <td style="width: 28%;" align="left">
                               <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                                <?=$period[0];?>
                               </div> 
                            </td>
                        </tr>
                        <tr><td style="width: 50%;" colspan="2"><div style="height:5px;">&nbsp;</div></td></tr>
                        <tr>    
                            <td style="width: 22%;">Pago Mensual</td>
                            <td style="width: 28%;" align="left">
                              <div style="width: 25px; height: 12px; border: 1px solid #000; text-align:center;">
                                <?=$period[1];?>
                              </div>  
                            </td>
                        </tr>
                    </table> <br />
                    		            
                    <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 60%; height: auto; font-size: 80%; font-family: Arial; margin-left:40px; 
                        color:#FFF;">
                        <tr><td colspan="2" style="width:60%; text-align:left; color:#000;">
                        Debito en Cuenta de la Entidad Financiera</td></tr>
                        <tr>
                            <td style="width:30%; background:#000; border-top:1px solid #333; 
                            border-left:1px solid #333; border-right:1px solid #333; 
                            border-bottom:1px solid #333;">Número de Cuenta 1</td>
                            <td style="width:30%; border-bottom:1px solid #333; border-right:1px solid #333; color:#000;">
<?php
                                if(!empty($rowDt['cuenta_1']))
								  echo $rowDt['cuenta_1'];
								else
								  echo'&nbsp;';  
?> 
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%; background:#000; border-left:1px solid #333; 
                            border-right:1px solid #333; border-bottom:1px solid #333;">Número de Cuenta 2</td>
                            <td style="width:30%; border-bottom:1px solid #333; border-left:1px solid #333; 
                            border-right:1px solid #333; color:#000;">
<?php
                                if(!empty($rowDt['cuenta_2']))
								   echo $rowDt['cuenta_2'];
								else
								   echo'&nbsp;';    
?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%; background:#000; border-left:1px solid #333; 
                            border-right:1px solid #333; border-bottom:1px solid #333;">Tarjeta de Crédito</td>
                            <td style="width:30%; border-bottom:1px solid #333; border-left:1px solid #333; 
                            border-right:1px solid #333; color:#000;">
<?php
                                  if(!empty($rowDt['tarjeta']))
								     echo $rowDt['tarjeta'];
								  else
								     echo'&nbsp;';	
?>
                            </td>
                        </tr>
                    </table><br/>
	 
			        <span style="font-weight:bold; font-size:80%;">4. Beneficiarios</span>
                    <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                        <tr>
                            <td style="width:10%; background:#000; border-top:1px solid #333;
                            border-right:1px solid #333; border-bottom:1px solid #333; border-left:1px solid #333;">&nbsp;
                            </td>
                            <td style="width: 50%; background:#000; color:#FFF; text-align:left; 
                            background:#000; border-top:1px solid #333; border-right:1px solid #333; 
                            border-bottom:1px solid #333;">
                            Nombre Completo</td>
                            <td style="width: 15%; text-align: center; background:#000; color:#FFF; 
                            border-top:1px solid #333; border-right:1px solid #333; 
                            border-bottom:1px solid #333;">
                            Parentesco</td>
                            <td style="width: 15%; text-align: center; background:#000; color:#FFF;
                            border-top:1px solid #333; border-right:1px solid #333; 
                            border-bottom:1px solid #333;">
                            Carnet de Identidad</td>
                            <td style="width: 10%; text-align: center; background:#000; color:#FFF;
                            border-top:1px solid #333; border-right:1px solid #333; 
                            border-bottom:1px solid #333;">
                            Proporcion<br />(%)</td>
                        </tr>
<?php
						$c=1;
						while ($rowBs = $rsBs->fetch_array(MYSQLI_ASSOC)) {
?>
                            <tr>
                                <td style="width:10%; background:#000; color:#FFF; 
                                border-left:1px solid #333; border-right:1px solid #333; 
                                border-bottom:1px solid #333;">Beneficiario <?=$c;?></td>
                                <td style="width:50%; border-right:1px solid #333; border-bottom:1px solid #333;">
                                    <?=$rowBs['nombre'] . ' ' . $rowBs['paterno'] . ' ' . $rowBs['materno'];?>
                                </td>
                                <td style="width:15%; border-right:1px solid #333; 
                                border-bottom:1px solid #333;"><?=$rowBs['parentesco'];?></td>
                                <td style="width:15%; border-right:1px solid #333; 
                                border-bottom:1px solid #333;">
<?php
                                if(!empty($rowBs['ci']))
								   echo $rowBs['ci'];
								else
								   echo'&nbsp;';   
?>
                                </td>
                                <td style="width:10%; border-right:1px solid #333;
                                border-bottom:1px solid #333;"><?=$rowBs['porcentaje_credito'];?></td>
                            </tr>
<?php
						  $c++;
                        }
?>
		            </table><br />
 
			        <div style="font-size: 80%; text-align:justify;">  
				    Declaro haber contestado con total veracidad  y máxima buena fe a todas las preguntas del presente 
                    cuestionario y no haber omitido u ocultado hechos y/o circunstancias que hubieran podido influir en la
                    celebración del contrato de seguro. Las declaraciones de salud que hacen anulable el Contrato de 
                    Seguros y por las que EL ASEGURADO pierde su derecho a indemnización, se enmarcan en los artículos 
                    992: OBLIGACION DE DECLARAR;  993: RETICENCIA O INEXACTTUD; 994: AUSENCIA DE DOLO; 999: DOLO O MALA 
                    FE; 1038: PERDIDA AL DERECHO DE LA INDEMNIZACION; 1138: IMPUGNACION DEL CONTRATO; 1140: ERROR EN LA 
                    EDAD DEL ASEGURADO, del Código de Comercio.<br/>
				
				    Por la presente acepto que esta solicitud no es un contrato de seguro y que este solo existirá si se 
                    emite y entrega el Certificado de Cobertura de acuerdo con esta solicitud y los reglamentos de Seguros
                    Masivos autorizados por la APS.<br/>
				
				    Autorizo a Médicos, Clínicas e Institutos de Salud para suministrar a Nacional Vida Seguro de Personas
                    S.A., todos los datos que requiera sobre mi estado de salud antes o después de mi fallecimiento.
                    </div>  
			        <br/>
			        <br/>
                    <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                        <tr>
                            <td style="width: 6%;">Fecha</td>
                            <td style="width: 25%; border-bottom:1px solid #000;">&nbsp;</td>
                            <td style="width: 6%;">Firma:</td>
                            <td style="width: 40%; border-bottom:1px solid #000;">&nbsp;</td>
                            <td style="width: 23%;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="width:37%;">&nbsp;</td>
                            <td style="text-align:center; width:40%;">SOLICITANTE</td>
                            <td style="width: 23%;">&nbsp;</td>
                        </tr>
                        <tr><td colspan="5" style="width:100%;">&nbsp;</td></tr>
                        <tr><td colspan="5" style="width:100%;">&nbsp;</td></tr>
                        <tr><td colspan="5" style="width:100%;">&nbsp;</td></tr>
                        <tr>
                            <td colspan="2" style="width:31%;">&nbsp;</td>
                            <td style="width:6%;">Firma</td>
                            <td style="border-bottom:1px solid #000; width:40%;">&nbsp;</td>
                            <td style="width: 23%;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="width:37%;">&nbsp;</td>
                            <td style="text-align:center; width:40%;">BANCO</td>
                            <td style="width: 23%;">&nbsp;</td>
                        </tr>
                    </table>
			       
		        </div>

		        <page><div style="page-break-before: always;">&nbsp;</div></page>

		        <div style="width: 775px; border: 0px solid #FFFF00;">
                    
                    <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 100%; height: auto; font-size: 70%; font-family: Arial;">
				        <tr>
					      <td style="width:50%; font-size:100%; text-align: justify; padding:5px; 
                          border:0px solid #333;" valign="top">
						    <div style="text-align: center; ">
							   <strong>CERTIFICADO INDIVIDUAL DE SEGURO MASIVO ACCIDENTES PERSONALES</strong><br/>
						       Formato aprobado por la Autoridad de Fiscalización y Control de Pensiones y Seguros –APS  
                               mediante RA APS/DS/No. 663 del 24/07/2013 <br />
                               Código 206 935022-2013 07 034 4001<br />
                               POLIZA DE  SEGURO MASIVOS DE ACCIDENTES PERSONALES A CORTO PLAZO N° <?=$row['no_poliza'];?>
				            </div><br/> 
							
                            <!--<div style="text-align:right; font-weight:bold;">CERTIFICADO No <?=$row['no_emision'];?></div><br />-->
							
							NACIONAL VIDA Seguros de Personas S.A., (denominada en adelante "LA COMPAÑÍA "), por el 
                            presente CERTIFICADO INDIVIDUAL DE SEGURO hace constar que la persona nominada en la 
                            declaración jurada de salud / formulario de seguro, (denominado en adelante "EL ASEGURADO"), 
                            está protegido por la Póliza de Seguro Masivo de Accidentes Personales arriba mencionada, de 
                            acuerdo a las  siguientes Condiciones Particulares:<br /><br/>
							
							<span style="font-weight: bold;">1. DATOS DEL ASEGURADO</span>
                            							
							<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                              <tr>
                                <td style="width:24%;">Nombre Completo: </td>
                                <td style="width:76%; border-bottom: 1px solid #333;">
                                    <?=$rowDt['nombre'] . ' ' . $rowDt['paterno'] . ' ' . $rowDt['materno'];?>
                                </td>                      
                              </tr>
							  <tr>
								<td style="width:24%;">Cedula de Identidad: </td>
								<td style="border-bottom: 1px solid #333; width:76%;">
								    <?=$rowDt['ci'].' '.$rowDt['ext'];?>
                                </td>                      
							  </tr>
							  <tr>
								<td style="width:24%;">Dirección Domicilio: </td>
								<td style="border-bottom: 1px solid #333; width:76%;">
								   <?=$rowDt['direccion'];?>
                                </td>                      
							  </tr>
							  <tr>
								<td style="width:24%;">Fecha de Nacimiento: </td>
								<td style="border-bottom: 1px solid #333; width:76%;">
								   <?=$rowDt['fecha_nacimiento'];?>
                                </td>                      
							  </tr>
							</table><br/>
							<span style="font-weight: bold;">2. COBERTURAS Y CAPITALES ASEGURADOS:</span>
							
							<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
								<td style="width:4%;" valign="top">
                                 a. 
                                </td>
                                <td style="width:96%;"> 
                                    <u>Muerte Accidental:</u><br />
								    <u>Capitales Asegurados:</u><br /><br />
									<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; 
                                       font-size:100%;">
                                       <tr>
                                         <td colspan="2" style="text-align:center; width:100%; background:#000; 
                                         color:#FFF; border: 1px solid #333;">
                                         Expresado en Bolivianos</td>
                                       </tr>
                                       <tr>
                                         <td style="width:70%; background:#000; color:#FFF;
                                         border-bottom: 1px solid #333; border-right: 1px solid #333;
                                         border-left: 1px solid #333;">Coberturas</td>
                                         <td style="width:30%; background:#000; color:#FFF; 
                                          border-bottom: 1px solid #333;  
                                          border-right: 1px solid #333;">Rango de Capitales</td>
                                       </tr>
                                       <tr>
                                         <td style="width:70%; background:#000; color:#FFF;
                                         border-bottom: 1px solid #333; 
                                         border-right: 1px solid #333; 
                                         border-left: 1px solid #333;"><?=$plan[0]['cov'];?></td>
                                         <td style="width:30%; border-bottom: 1px solid #333; 
                                           border-right: 1px solid #333;">
                                             Hasta Bs. <?=number_format($plan[0]['rank'], 0, '.', ',');?>
                                         </td>
                                       </tr>
                                       <tr>
                                          <td style="width:70%; background:#000; color:#FFF;
                                          border-bottom: 1px solid #333; 
                                          border-right: 1px solid #333; 
                                          border-left: 1px solid #333;"><?=$plan[1]['cov'];?></td>
                                          <td style="width:30%; border-bottom: 1px solid #333; 
                                           border-right: 1px solid #333;">
                                             Hasta Bs. <?=number_format($plan[1]['rank'], 0, '.', ',');?>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td style="width:70%; background:#000; color:#FFF;
                                          border-bottom: 1px solid #333; 
                                          border-right: 1px solid #333; 
                                          border-left: 1px solid #333;"><?=$plan[2]['cov'];?></td>
                                          <td style="width:30%; border-bottom: 1px solid #333; 
                                           border-right: 1px solid #333;">
                                            Hasta Bs. <?=number_format($plan[2]['rank'], 0, '.', ',');?>
                                          </td>
                                       </tr>
									</table><br />
									
									<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
									   <tr>
										 <td style="width:20%;" valign="top">Límites de edad:</td>
										 <td style="width:20%;" valign="top">De Ingreso:</td>
										 <td style="width:60%;">Mayores de 14 años y  hasta los 65 años para la cobertura de muerte por Accidente. 
                                         <br />Mayores de 14 años hasta los 64 años para las coberturas de 
                                         Incapacidad Total y/o Parcial Permanente por Accidente y Rembolso de Gastos Médicos por Accidente.</td>
									   </tr>
									   <tr>
										 <td style="width:20%;">&nbsp;</td>
										 <td style="width:20%;" valign="top">De permanencia:</td>
										 <td style="width:60%;">Hasta los 70 años, cobertura para Muerte.
                                         <br />Hasta los 65 años, coberturas de Incapacidad total y/o parcial permanente y 
                                         Rembolso de Gastos Médicos por Accidente.</td>
									   </tr>
									</table>                    
								</td>
                               </tr> 
							</table>
							<span style="font-weight: bold;">3. PUNTO DE VENTA:</span>
							<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
							   <tr>
								 <td style="width: 30%;">Nombre de la Razón Social: </td>
								 <td style="width: 70%; border-bottom: 1px solid #333;">Banco Económico S.A.</td>
							   </tr>
							   <tr>
								 <td style="width:30%;">Dirección: </td>
								 <td style="border-bottom: 1px solid #333; width:70%;">
								   <?=$row['user_departamento'].', '.$row['agencia'];?>
                                 </td>                      
								</tr>                    
							</table><br />
							<span style="font-weight: bold;">4.  EXCLUSIONES:</span><br/>
							Este seguro de  accidentes personales no será aplicable en ninguna de las siguientes 
                            circunstancias:
							<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                              <tr>
                                <td style="width:4%;" valign="top">a.</td>
								<td style="width:96%;">Cualquier enfermedad corporal o mental, y las consecuencias de tratamiento médico o 
                                quirúrgico que no sean motivados por accidentes amparados por la presente Póliza.</td>
                              </tr>
                              <tr>
                                <td style="width:4%;" valign="top">b.</td>  
								<td style="width:96%;">Los denominados "Accidentes Médicos", tales como apoplejías, congestiones, síncopes, 
                                vértigos, edemas agudos, infartos del miocardio, trombosis, ataques epilépticos u otros 
                                análogos.</td>
                              </tr>
                              <tr>
                                <td style="width:4%;" valign="top">c.</td>  
								<td style="width:96%;">Los accidentes que se produzcan en situación de estado de embriaguez o mientras el 
                                Asegurado se encuentre bajo la influencia de substancias controladas o en estado de 
                                sonambulismo.</td>
                              </tr>
                              <tr>
                                <td style="width:4%;" valign="top">d.</td>  
								<td style="width:96%;">Lesiones que EL ASEGURADO sufra en Servicio Militar de cualquier clase, en actos de 
                                guerra internacional (con o sin declaración) o civil, insurrección, rebelión, invasión, 
                                huelgas, motín o tumultos populares, terrorismo; en actos delictuosos en que EL ASEGURADO 
                                participe voluntariamente, así como tampoco peleas o riñas, salvo en aquellos casos en que 
                                se establezca legalmente que se ha tratado de legítima defensa.</td>
                              </tr>
                              <tr>
                                <td style="width:4%;" valign="top">e.</td>  
								<td style="width:96%;">Lesiones causadas por EL ASEGURADO voluntariamente a sí mismo, así como el suicidio o 
                                tentativa de suicidio y lesiones causadas intencionalmente (incluyendo homicidio o 
                                tentativa de homicidio) al Asegurado por los beneficiarios de esta Póliza.</td>
                              </tr>
                              <tr>
                                <td style="width:4%;" valign="top">f.</td>    
								<td style="width:96%;">Accidentes ocasionados por fenómenos de la naturaleza de carácter catastrófico tales 
                                como: Sismos, inundaciones, riadas, erupciones volcánicas o a consecuencia de tales, y por 
                                la emisión de radiaciones ionizantes o contaminación por radioactividad de cualquier 
                                combustible nuclear o de cualquier desperdicio proveniente de la combustión de dicho 
                                combustible.</td>
                              </tr>
                              <tr>
                                <td style="width:4%;" valign="top">g.</td>  
								<td style="width:96%;">Los accidentes que se produzcan en la práctica de actividades peligrosas y que no 
                                guarden relación con la ocupación declarada por EL ASEGURADO, ni mencionadas por el mismo 
                                en la Solicitud respectiva, o aún cuando lo hubiera hecho, tales como: carreras de 
                                automóviles (maneje o viaje como pasajero), motocicletas y motonetas, aviones de uso 
                                privado, helicópteros o vehículos similares, concursos o prácticas hípicas, alpinismo o 
                                andinismo, cacería de fieras o pesca submarina, viajes por regiones inexploradas, boxeo, 
                                rodeo, rugby y otros deportes peligrosos.</td>
                              </tr>
                              <tr>
                                <td style="width:4%;" valign="top">h.</td>  
								<td style="width:96%;">Las consecuencias de hernias, enredamientos intestinales y várices, como tampoco las 
                                intoxicaciones y envenenamientos que no sean accidentales.</td>
                              </tr>  
							</table><br/>
							<span style="font-weight: bold;">5. COSTO DE LA COBERTURA:</span>								
							<table cellpadding="0" cellspacing="0" border="0" style="width: 80%; font-size:100%;">
                               <tr>
                                 <td style="width:35%; background:#000; color:#fff; 
                                 border-bottom: 1px solid #333; border-top: 1px solid #333;
                                 border-left: 1px solid #333; border-right: 1px solid #333;">Prima Neta </td>
                                 <td style="width:45%; border-bottom: 1px solid #333; border-top: 1px solid #333;
                                 border-right: 1px solid #333;">
                                    <?=$link->prima['AP'][$row['plan_nombre']]['PN'];?>
                                </td>
                               </tr>
                               <tr>
                                 <td style="width:35%; background:#000; color:#fff;
                                 border-bottom: 1px solid #333; border-right: 1px solid #333;
                                 border-left: 1px solid #333;">Impuestos</td>
                                 <td style="width:45%; border-bottom: 1px solid #333; border-top: 1px solid #333;
                                 border-right: 1px solid #333;">
                                    <?=$link->prima['AP'][$row['plan_nombre']]['IM'];?>
                                 </td>
                               </tr>
                               <tr>
                                 <td style="width:35%; background:#000; color:#fff;
                                 border-bottom: 1px solid #333; border-right: 1px solid #333;
                                 border-left: 1px solid #333;">Comision Corretaje </td>
                                 <td style="width:45%; border-bottom: 1px solid #333; border-top: 1px solid #333;
                                 border-right: 1px solid #333;">
                                    <?=$link->prima['AP'][$row['plan_nombre']]['CC'];?>
                                 </td>
                               </tr>
                               <tr>
                                 <td style="width:35%; background:#000; color:#fff;
                                 border-bottom: 1px solid #333; border-right: 1px solid #333;
                                 border-left: 1px solid #333;">Prima Comercial </td>
                                 <td style="width:45%; border-bottom: 1px solid #333; border-top: 1px solid #333;
                                 border-right: 1px solid #333;"><?=$row['prima'];?></td>
                               </tr>
							</table><br/>
							<span style="font-weight: bold;">6. BENEFICIARIOS:</span>
							<table cellpadding="0" cellspacing="0" border="0" style="width: 95%; font-size:100%;">
                                <tr style="background:#000; color:#fff;">
                                  <td style="width:14%; border-top: 1px solid #333; border-bottom: 1px solid #333;
                                 border-left: 1px solid #333;">&nbsp;</td>
                                  <td style="width:39%; border-top: 1px solid #333; border-bottom: 1px solid #333;
                                 border-left: 1px solid #333;">Nombre Completo</td>
                                  <td style="width:15%; border-top: 1px solid #333; border-bottom: 1px solid #333;
                                 border-left: 1px solid #333;">Parentesco</td>
                                  <td style="width:15%; border-top: 1px solid #333; border-bottom: 1px solid #333;
                                 border-left: 1px solid #333;">C.I.</td>
                                  <td style="width:12%; border-top: 1px solid #333; border-bottom: 1px solid #333;
                                 border-left: 1px solid #333; border-right:1px solid #333;">Proporcion</td>
                                </tr>
				
<?php
								$k = 1;
								if ($rsBs->data_seek(0) === true) {
									while ($rowBs = $rsBs->fetch_array(MYSQLI_ASSOC)) {
?>
                                      <tr>
                                        <td style="width:14%; background:#000; color:#fff;
                                        border-bottom: 1px solid #333; border-left: 1px solid #333;">
                                        Beneficiario <?=$k;?></td>
                                        <td style="width:39%;
                                        border-bottom: 1px solid #333; border-left: 1px solid #333;">
                                           <?=$rowBs['nombre'] .' '.$rowBs['paterno'].' '. $rowBs['materno'];?>
                                        </td>
                                        <td style="width:15%;
                                        border-bottom: 1px solid #333; border-left: 1px solid #333;">
                                           <?=$rowBs['parentesco'];?>
                                        </td>
                                        <td style="width:15%;
                                        border-bottom: 1px solid #333; border-left: 1px solid #333;">
<?php
                                           if(!empty($rowBs['ci']))
										      echo $rowBs['ci'];
										   else
										      echo'&nbsp;';	  
?>
                                        </td>
                                        <td style="width:12%; border-right: 1px solid #333;
                                        border-bottom: 1px solid #333; border-left: 1px solid #333;">
                                           <?=$rowBs['porcentaje_credito'];?>
                                        </td>
                                      </tr>
<?php
										$k++;
									}
								}
?>
									<tr>
										<td colspan="4" style="width:90%; 
                                        border-bottom: 1px solid #333; border-left: 1px solid #333;">&nbsp;</td>
										<td style="width: 10%; text-align:center; border-bottom: 1px solid #333; 
                                        border-left: 1px solid #333; border-right: 1px solid #333;">100%</td>
									</tr>                        
							</table>
					      </td>
					      <td style="width:50%; font-size:100%; text-align: justify; padding:5px; 
                          border:0px solid #333;" valign="top">
						    <span style="font-weight: bold;">7. PROCEDIMIENTO A SEGUIR EN CASO DE SINIESTRO:</span><br/>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">                              <tr>                  
                                <td style="width:4%;" valign="top">a.</td>
                                <td style="width:96%; padding-left:6px;" valign="top"><b>PLAZO PARA PRONUNCIARSE</b><br />
                                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                     <tr>
                                       <td style="width:4%;" valign="top">1.</td>   
                                       <td style="width:96%;">El asegurado o beneficiario, tan pronto y a más tardar dentro de los treinta días 
                                       de tener conocimiento del siniestro, deben comunicar tal hecho al asegurador, salvo 
                                       fuerza mayor o impedimento justificado.</td>
                                     </tr>
                                     <tr>
                                       <td style="width:4%;" valign="top">2.</td>  
                                       <td style="width:96%;">LA COMPAÑÍA se pronunciará sobre el derecho de EL ASEGURADO o beneficiario 
                                       dentro de los treinta (30) días de recibidas la información y evidencias citadas en
                                       el punto 8 del presente Certificado.  Se dejará constancia escrita de la fecha de 
                                       recepción de la información y evidencias a efecto del cómputo del plazo.</td>
                                     </tr>
                                     <tr>   
                                       <td style="width:4%;" valign="top">3.</td>
                                       <td style="width:96%;">En el plazo de treinta (30) días, fenece con la aceptación o rechazo del siniestro 
                                       o con la solicitud del asegurador al asegurado que se complementen los 
                                       requerimientos contemplados en el punto 8 del presente Certificado  y no vuelve a 
                                       correr hasta que el asegurado haya cumplido con tales requerimientos.</td>
                                     </tr>
                                     <tr>  
                                       <td style="width:4%;" valign="top">4.</td>
                                       <td style="width:96%;"> La solicitud de complementos establecidos en el punto 8 del presente 
                                       certificado, por parte del Asegurador no podrá extenderse por más de dos veces a 
                                       partir de la primera solicitud de informes y evidencias, debiendo pronunciarse 
                                       dentro del plazo establecido y de manera definitiva sobre el derecho del asegurado, 
                                       después de la entrega por parte del asegurado del último requerimiento de 
                                       información.</td>
                                     </tr>  
                                     <tr>
                                       <td colspan="2" style="width:100%;"> 
                                         El silencio del asegurador, vencido el término  para pronunciarse o vencidas las 
                                         solicitudes de complementación, importa la aceptación del reclamo.
                                       </td>
                                     </tr>
                                  </table> 
                                </td>       
                              </tr>
                              <tr>
                                <td style="width:4%;" valign="top">b.</td>
                                <td style="width:96%;" valign="top"><b>TERMINO PARA EL PAGO DEL SINIESTRO</b><br />
                                      En caso de conformidad, LA COMPAÑÍA satisfará la indemnización AL ASEGURADO O 
                                      BENEFICIARIO, dentro de los quince (15) días siguientes al término del plazo anterior
                                      y contra la firma del finiquito correspondiente
                                </td>
                              </tr>  
							</table><br/>
                            <span style="font-weight: bold;">8.  DOCUMENTOS  A PRESENTAR  EN CASO DE  FALLECIMIENTO, 
                            INCAPACIDAD O GASTOS MEDICOS: <br/>
							PARA MUERTE POR ACCIDENTE:</span>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
								 <td style="width:4%;">&bull;</td>
                                 <td style="width:96%;">Certificado de defunción.</td>
                               </tr>
                               <tr>  
                                 <td style="width:4%;">&bull;</td> 
								 <td style="width:97%;">Certificado de la Autoridad competente (si corresponde).</td>
                               </tr>
                               <tr>
                                 <td style="width:4%;">&bull;</td>     
								 <td style="width:96%;">Documento de identidad (carnet de identidad o certificado de nacimiento) del asegurado.
                                </td>
                               </tr>
                               <tr> 
								 <td style="width:4%;">&bull;</td> 
                                 <td style="width:96%;">Documento de identidad (Carnet de identidad o Certificado de  nacimiento) del 
                                beneficiario.</td>
                               </tr>
                               <tr>
                                <td style="width:4%;">&bull;</td>  
								<td style="width:96%;">Declaratoria de Herederos si no existieran Beneficiarios nominados en la Póliza.</td>
                               </tr> 
							</table>
                            <span style="font-weight: bold;">INCAPACIDAD TOTAL Y/O PARCIAL PERMANENTE POR ACCIDENTE
                            </span>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                            	<tr>
                                <td style="width:4%;">&bull;</td>  
								<td style="width:96%;">Declaratoria médica de invalidez emitida por un médico autorizado por la APS.</td>
                               </tr> 
                            </table>
                            <span style="font-weight: bold;">REMBOLSO POR GASTOS MEDICOS POR ACCIDENTE
                            </span>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
							  <tr>	
                                <td style="width:4%;" valign="top">&bull;</td>
                                <td style="width:96%;">Certificado Médico.<br />
								La Factura  deberá ser a nombre de Nacional Vida  Seguros de Personas S.A NIT: 1028483024 
                                detallando los servicios médicos y medicamentos utilizados. <br />
								La factura debe ser presentada dentro del mes de la ocurrencia del evento.
								</td>
                              </tr>                      
							</table><br/>
                            <span style="font-weight: bold;">9. COMPAÑÍA ASEGURADORA</span>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                              <tr>
                                <td style="width: 15%;">Razón Social: </td>
                                <td style="width: 85%; border-bottom: 1px solid #000;">
                                 Nacional Vida Seguros de Personas S.A
                                </td>                  
                              </tr>
                              <tr>
                                 <td colspan="2" style="width:100%;">
                                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; font-size:100%;">
                                      <tr>
                                        <td style="width:10%;">Dirección: </td>
                                        <td style="border-bottom: 1px solid #000; width:53%;">Av. Monseñor Rivero  N 223</td>                      
                                        <td style="width:10%;">Teléfono: </td>
                                        <td style="width:10%; border-bottom: 1px solid #000;">3716262</td>
                                        <td style="width:7%;">Fax: </td>
                                        <td style="width:10%; border-bottom: 1px solid #000;">3337969</td>
                                      </tr>
                                    </table>
                                 </td>                       
                              </tr>                    
							</table><br />
                            <span style="font-weight: bold;">10. CORREDOR DE SEGUROS</span>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                              <tr>
                                <td style="width: 15%;">Razón Social: </td>
                                <td style="width: 85%; border-bottom: 1px solid #000;">
                                 Sudamericana S.R.L.
                                </td>                  
                              </tr>
                              <tr>
                                 <td colspan="2" style="width:100%;">
                                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; font-size:100%;">
                                      <tr>
                                        <td style="width:10%;">Dirección: </td>
                                        <td style="border-bottom: 1px solid #000; width:53%;">Equipetrol Calle Nº 8 Este Nº 25</td>                      
                                        <td style="width:10%;">Teléfono: </td>
                                        <td style="width:10%; border-bottom: 1px solid #000;">3416055</td>
                                        <td style="width:7%;">Fax: </td>
                                        <td style="width:10%; border-bottom: 1px solid #000;">3416056</td>
                                      </tr>
                                    </table>
                                 </td>                       
                              </tr>                    
							</table><br />
<?php
							$dat=explode('-', $row['fecha_emision']);
?>
							<table border="0" cellpadding="0" cellspacing="0" style="width:100%; font-size:100%;">
                              <tr>
                                <td style="width:20%;">&nbsp;</td>
                                <td style="width:15%; border-bottom:1px solid #000;">Santa Cruz</td>
                                <td style="width:2%;">, </td>
                                <td style="width:5%; border-bottom:1px solid #000;"><?=$dat[2];?></td>
                                <td style="width:4%;"> de </td>
                                <td style="width:20%; border-bottom:1px solid #000;"><?=mes($dat[1]);?></td>
                                <td style="width:4%;"> de </td>
                                <td style="width:10%; border-bottom:1px solid #000;"><?=$dat[0];?></td>
                                <td style="width:20%;">&nbsp;</td>
                              </tr>
                            </table><br/>
                            <div style="font-size:80%; text-align:center;">
                            NACIONAL VIDA SEGURO DE  PERSONAS S.A.<br />FIRMAS AUTORIZADAS</div>
                            
							<table style="width:100%; font-size: 100%">
                                <tr>
                                    <td style="width:8%;">&nbsp;</td>
                                    <td style="width:30%; border-bottom:1px solid #000; text-align: center;">
                                        <img src="<?=$url;?>img/f_joaquin.jpg" alt="" height="80"/>
                                    </td>
                                    <td style="width:9%;">&nbsp;</td>
                                    <td style="width:44%; border-bottom:1px solid #000; text-align: center;">
                                        <img src="<?=$url;?>img/f_mario.jpg" alt="" height="80"/>
                                    </td>
                                    <td style="width:8%;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="width:8%;">&nbsp;</td>
                                    <td style="width:30%;" valign="top" align="center">
                                        Juaquin Montaño Salas<br/>Gerente Regional
                                    </td>
                                    <td style="width:8%;">&nbsp;</td>
                                    <td style="width:44%;" valign="top" align="center">
                                        Mario Aguirre<br/>Gerente Nacional de Seguros Masivos
                                    </td>
                                    <td style="width:8%;">&nbsp;</td>
                                </tr>
                            </table><br/>
							<b>Discrepancias en la Póliza;</b> (Art. 1013) "Si el Tomador o ASEGURADO encuentran que la 
                            póliza no concuerda con lo convenido o con lo propuesto, pueden pedir rectificación 
                            correspondiente por escrito, dentro de los quince días siguientes a la recepción de la Poliza. 
                            Se consideran aceptadas las estipulaciones de esta si durante dicho plazo no se solicita la 
                            mencionada rectificación. Si dentro de los quince días siguientes al de la reclamación LA 
                            COMPAÑÍA no da curso a la rectificación solicitada o mantiene silencio, se entiende aceptada en
                            los términos de la modificación".<br />
							
							<b>Omisión de Aviso.</b> (Art. 1030) " LA COMPAÑIA puede liberarse de sus obligaciones cuando 
                            EL ASEGURADO o beneficiario, según el caso, omitan dar el aviso dentro del plazo del articulo 
                            1028 con el fin de impedir la comprobación oportuna de las circunstancias del siniestro o el de
                            la magnitud de los daños.(Art.1035CódigodeComercio)".<br />
							
							<b>Plazo para Pronunciarse. </b> (Art. 1033) LA COMPAÑÍA  debe pronunciarse sobre el derecho 
                            del asegurado o beneficiario dentro de los treinta días de recibidas la información y evidencia
                            citadas en el artículo 1031. Se dejará constancia escrita de la fecha de recepción de la 
                            información y evidencias a efecto del cómputo del plazo.<br />
							En caso de demora u omisión del asegurado o beneficiario en proporcionar la información y 
                            evidencias sobre el siniestro, el término señalado no corre hasta el cumplimiento de estas 
                            obligaciones.<br />
							El silencio de LA COMPAÑÍA, vencido el término para pronunciarse, importa la aceptación del 
                            reclamo.<br />
							<b>Termino para el pago de Siniestro.</b> (Art. 1034) En los seguros de vida, el pago se hará 
                            dentro de los quince días posteriores al aviso del siniestro o tan pronto sean llenados los 
                            requerimientos señalados en el artículo 1031".<br />
                            <b>Vigencia.</b> Esta póliza tendráuna duración de un año contando desde su fecha de vigencia 
                            inicial y se renovará automaticamente por periodos iguales por el solo hecho de continuar pagando 
                            la prima especificada en las condiciones particulares.<br />
                            <b>Efecto del no pago de Prima: Terminación del Contrato.</b>  Si la Prima se encontrare impaga.
                            el Contrato del Seguro caducará en forma inmediata, sin necesidad de aviso, notificación o 
                            requerimiento alguno, liberándose LA COMPAÑIA de toda obligación y responsabilidad derivada de la 
                            Póliza, según lo prescrito en el articulo 58 inciso d) de la Ley 1883 (Ley de Seguros).<!---->
					</td>
				</tr>
                
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                	<td colspan="2" style="font-weight:bold; text-align: center; font-size: 11px;">"En caso de haber adquirido este seguro bajo la modalidad de débito automático exija su factura en el área de Servicio a Instituciones de oficina central o al Jefe de Agencia en caso de Agencias. Próximamente este servicio estará disponible en nuestra página de internet"</td>
                </tr>
			</table>
		</div>
<?php
		 if($num_titulares <> $j)
				 echo "<page><div style='page-break-before: always;'>&nbsp;</div></page>";
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