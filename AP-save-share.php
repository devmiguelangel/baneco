<?php
require_once 'sibas-db.class.php';

/************ Session EXP - LANG *******/
$lang = $session->getSessionLanguage();
// define('SPATH_BASE', base64_decode($session->getSessionPath()));

/*require(SPATH_BASE . '/classes/language.php');
$language = new Language($lang);
$file_inc = $language->getLanguage();

require($file_inc);*/
/***************************************/

$arrDE = array(0 => 0, 1 => 'R', 2 => $lang_quote['mess_01']);

if (isset($_GET['idc'])
	&& isset($_GET['cia'])
	&& isset($_GET['ms'])
	&& isset($_GET['page'])) {
	
	$link = new SibasDB();
	
	$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));
	$idCia = $link->real_escape_string(trim(base64_decode($_GET['cia'])));
	$ms = $link->real_escape_string(trim($_GET['ms']));
	$page = $link->real_escape_string(trim($_GET['page']));
	
	$sql = 'update s_ap_cot_cabecera as sdc
		inner join 
	s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
	set 
		sdc.id_compania = "' . $idCia . '"
	where sdc.id_cotizacion = "' . $idc . '" 
		and sef.id_ef = "' . base64_decode($_SESSION['idEF']) . '"
	;';
	
	if ($link->query($sql) === true) {
		$arrDE[0] = 1;
		echo '<meta http-equiv="Refresh" content="0;url=ap-quote.php?ms=' 
			. $ms.'&page=' . $page . '&pr=' . base64_encode('AP|05')
			.'&idc=' . base64_encode($idc) . '&flag=' . md5('i-new') . '&cia=' 
			. base64_encode($idCia) . '">';
		if ($link->affected_rows > 0) {
			/*$arrDE[1] = 'ap-quote.php?ms='.$ms.'&page='.$page.'&pr='.base64_encode('AP|05')
				.'&idc='.base64_encode($idc).'&flag='.md5('i-new').'&cia='.base64_encode($idCia);
			$arrDE[2] = $lang_quote['mess_48'];*/
		} else {
			$arrDE[2] = $lang_quote['mess_49'];
		}
	} else {
		$arrDE[2] = $lang_quote['mess_50'];
	}
	// echo json_encode($arrDE);
} else {
	// echo json_encode($arrDE);
}
?>