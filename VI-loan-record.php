<?php

require('sibas-db.class.php');
require('session.class.php');

$session = new Session();

$session->getSessionCookie();
$token = $session->check_session();

$link = new SibasDB();

$arrBS = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo procesar la Cotizaciónf');

if ($token === false) {
    $arrBS[0] = 1;
    $arrBS[1] = 'logout.php';
    $arrBS[2] = 'La Cotización no puede ser registrada, intentelo mas tarde';
} elseif (isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr'])) {
    $pr = $link->real_escape_string(trim(base64_decode($_POST['pr'])));

    if ($pr === 'VI|01') {
        $ms = $link->real_escape_string(trim($_POST['ms']));
        $page = $link->real_escape_string(trim($_POST['page']));
        $tc = $link->get_rate_exchange();
        $idc = NULL;
        $cp = NULL;
        $sql = '';
    
        if (isset($_POST['idc'])) {
            $idc = $link->real_escape_string(trim(base64_decode($_POST['idc'])));
        }
    
        if (isset($_POST['fq-cp'])) {
            $cp = $link->real_escape_string(trim($_POST['fq-cp']));
            if ($cp === md5(1)) {
                $cp = 1;
            } elseif ($cp === md5(0)) {
                $cp = 0;
            }
        }
    
        if ($cp !== NULL) {
            $idc = uniqid('@S#1$2013',true);
            $record = $link->getRegistrationNumber($_SESSION['idEF'], 'VI', 0);
            
            $sql = 'insert into s_vi_cot_cabecera
            (id_cotizacion, no_cotizacion, id_ef, cobertura, fecha_creacion, id_usuario)
            values
            ("'.$idc.'", '.$record.', "'.base64_decode($_SESSION['idEF']).'", 
                0, curdate(), "'.base64_decode($_SESSION['idUser']).'")
            ;';
            
            if ($link->query($sql) === true) {
                $arrBS[0] = 1;
                $arrBS[1] = 'vi-quote.php?ms=' . $ms . '&page=' . $page 
                    . '&pr=' . base64_encode('VI|02') . '&idc=' . base64_encode($idc);
                $arrBS[2] = 'La Cotización fue registrada con exito !';
            } else{
                $arrBS[2] = 'No se pudo registrar la Cotización !';
            }
        }
    
        $link->close();
    }
}

echo json_encode($arrBS);

?>