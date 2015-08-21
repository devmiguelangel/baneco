<?php
require('sibas-db.class.php');
session_start();

if(isset($_GET['ide']) && isset($_GET['pr'])){
	$link = new SibasDB();
	
	$ide = $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$pr = strtoupper($link->real_escape_string(trim(base64_decode($_GET['pr']))));

	$table = '';
	switch ($pr) {
		case 'DE':
			$table = 's_de_em_cabecera';
			break;
		case 'AU':
			$table = 's_au_em_cabecera';
			break;
		case 'TRD':
			$table = 's_trd_em_cabecera';
			break;
		case 'TRM':
			$table = 's_trm_em_cabecera';
			break;
		case 'AP':
			$table = 's_ap_em_cabecera';
			break;
		case 'VI':
			$table = 's_vi_em_cabecera';
			break;
	}

	$sql = 'select stec.prefijo, stec.no_emision';
	      if($pr==='AP' || $pr==='VI')
		    $sql.=', stec.periodo, stec.forma_pago, stec.prima, sc.fecha_cuota, (concat("Formulario de anulación Póliza N° "," ",stec.no_poliza)) as no_cert ';
		  else
		    $sql.=', (concat("Formulario de anulación Certificado N° "," ",stec.prefijo," - ",stec.no_emision)) as no_cert ';	 
	$sql.='from ' . $table . ' as stec ';
	       if($pr === 'AP')
		       $sql.='inner join  s_ap_cobranza as sc ON (sc.id_emision = stec.id_emision)';
	$sql.='where stec.id_emision = "' . $ide . '"
		   limit 0, 1';
   	// echo $sql;

	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();
?>
<script type="text/javascript">
$(document).ready(function(e) {
    get_tinymce('fp-obs');
	
	$("#form-cancel").validateForm({
		action: 'cancel-policy-record.php',
		method: 'GET'
	});
});
</script>
<?php
  if(($pr === 'AP') && ($row['periodo']==='Y')){
    $prima_devengada = 0;
	$texto_devolucion = '';
	$prima_devolucion = '';
	$date_now = new DateTime();
	$date_payment = new DateTime($row['fecha_cuota']);
	$interval = $date_now->diff($date_payment);
	$elapsed_days = $interval->format('%a');
	//echo $elapsed_days.' dias';echo'<br>';
	$time='';
	if($elapsed_days<=15){
		$percentage = $link->cobertura[0];
		$time = $elapsed_days.' dias';
	}else{
		if(($elapsed_days>=16) && ($elapsed_days<=31)){
		  $percentage = $link->cobertura[1];
		  $time = $elapsed_days.' dias';
		}else{
			$n_mes = ceil($elapsed_days/30);
			$percentage = $link->cobertura[$n_mes];
			$time =  $n_mes.' meses';
		}  
	}
	//echo $time;echo'<br>';
	//echo $percentage.'%';
	$prima_devengada = ($row['prima']*$percentage)/100;
	$operacion = $row['prima']-$prima_devengada;
	$prima_devolucion = ($operacion*87)/100;
	$texto_devolucion = 'El monto a devolver por esta anulaci&oacute;n es de Bs '.$prima_devolucion.' que ser&aacute; emitido mediante cheque de la compa&ntilde;&iacute;a de seguros.';
  }elseif(($pr === 'AP') && ($row['periodo'] === 'M')){
	 $texto_devolucion = 'La finalizaci&oacute;n de la vigencia de esta p&oacute;liza ser&aacute; la fecha de su pr&oacute;xima fecha de pago.'; 
  }
?>
  
<form id="form-cancel" name="form-cancel" class="f-process" style="width:570px; font-size:130%;">
	<h4 class="h4"><?=$row['no_cert'];?></h4>
	<label class="fp-lbl" style="text-align:left; width:auto;">Observaciones: <span>*</span></label>
	<div style="width:auto; font-size:60%; font-weight:bold; padding:7px; text-align:center; background:#faffe5; border-radius: 5px; border: 0px solid #e06262; margin-top:5px; margin-bottom:8px;">
      <?=$texto_devolucion;?>
    </div>
    <textarea id="fp-obs" name="fp-obs" class="required"></textarea><br>
    <div style="text-align:center">
		<input type="hidden" id="fp-ide" name="fp-ide" value="<?=base64_encode($ide);?>">
        <input type="hidden" id="idUser" name="idUser" value="<?=$_SESSION['idUser'];?>">
        <input type="hidden" id="pr" name="pr" value="<?=base64_encode($pr);?>">
        <input type="hidden" id="pd" name="pd" value="<?=base64_encode($prima_devolucion);?>"/>
        <input type="hidden" id="prd" name="prd" value="<?=base64_encode($row['periodo']);?>"/>
        <input type="submit" id="fp-process" name="fp-process" value="Anular Certificado" class="fp-btn">
    </div>
    
    <div class="loading">
        <img src="img/loading-01.gif" width="35" height="35" />
    </div>
</form>
<?php
		} else {
			echo 'No se puede anular la Póliza.';
		}
	} else {
		echo 'No se puede anular la Póliza .';
	}
}else
	echo 'No se puede anular la Póliza';
?>