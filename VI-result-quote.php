<h3>Seguro de Vida Individual - Tenemos las siguientes ofertas</h3>
<h4>Escoge el plan que mas te convenga</h4>
<section style="text-align:center;">
<?php
require_once('sibas-db.class.php');
$link = new SibasDB();
$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));

$sqlCia = 'select 
	sdc.id_cotizacion,
	sdc.respuesta,
	sec.id_ef_cia,
	scia.id_compania as idcia,
	scia.nombre as cia_nombre,
	scia.logo as cia_logo,
	sp.nombre as plan_nombre,
	sp.prima_mensual,
	sp.prima_anual,
	sdc.prima as prima
from
	s_vi_cot_cabecera as sdc
		inner join
	s_plan as sp ON (sp.id_plan = sdc.id_plan)
		inner join
	s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
		inner join
	s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
		inner join
	s_compania as scia ON (scia.id_compania = sec.id_compania)
where
	sdc.id_cotizacion = "' . $idc . '"
		and sef.id_ef = "' . base64_decode($_SESSION['idEF']) . '"
		and sef.activado = true
		and sec.producto = "VI"
		and scia.activado = true
group by scia.id_compania
;';
// echo $sqlCia;
$rsCia = $link->query($sqlCia,MYSQLI_STORE_RESULT);
if($rsCia->num_rows > 0){
	$nForm = 0;
	
	while($rowCia = $rsCia->fetch_array(MYSQLI_ASSOC)){
		resultQuote($rowCia, true, $token);
	}
} else {
	echo 'No se puede obtener las Compañias';
}
?>
</section>
<br>
<br>

<div class="contact-phone">
	Todas las ofertas tienen las mismas condiciones, elige la compañía de tu elección<br><br>
	* Para cualquier duda o consulta, contacta a la Línea Gratuita de Sudamericana S.R.L. 800-10-3070
</div>
<?php
// --
function resultQuote ($rowCia, $modality, $token, $rowPe = null, $nForm = 0) {
	$response = (boolean)$rowCia['respuesta'];
?>
<div class="result-quote" style="height:300px;">
	<div class="rq-img">
		<img src="images/<?=$rowCia['cia_logo'];?>" alt="<?=$rowCia['cia_nombre'];?>" 
			title="<?=$rowCia['cia_nombre'];?>">
	</div>
	<span class="rq-tasa">
		<?=$rowCia['plan_nombre'];?> 
	</span>
	<span class="rq-tasa">
		Prima Mensual: 
		<?='Bs.' . number_format($rowCia['prima_mensual'], 2, '.', ',');?>
	</span>
	<span class="rq-tasa">
		Prima Anual: 
		<?='Bs.' . number_format(($rowCia['prima_anual']), 2, '.', ',');?>
	</span>
	
	<a href="certificate-detail.php?idc=<?=
		base64_encode($rowCia['id_cotizacion']);?>&cia=<?=
		base64_encode($rowCia['idcia']);?>&pr=<?=
		base64_encode('VI');?>&type=<?=base64_encode('PRINT');?>" 
		class="fancybox fancybox.ajax btn-see-slip">Ver Slip de Cotización</a>
<?php
if($token === true){
	if ($response === false) {
?>
	<a href="vi-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=
		base64_encode('VI|05');?>&idc=<?=$_GET['idc'];?>&flag=<?=
		md5('i-new');?>&cia=<?=base64_encode($rowCia['idcia']);?>" 
		class="btn-send">Emitir</a>
<?php
	} else {
?>
	<div style="border: 1px solid #fc9797; background: #fff2f2; 
		font-weight: bold; font-size: 70%;">
		Su solicitud no puede ser procesada debido a la(s) enfermedad(es) 
		del cuestionario de salud. Si desea mayor informacion porfavor 
		envie su solicitud mediante email.
	</div>
<?php
	}
}
?>
</div>
<?php
}
?>