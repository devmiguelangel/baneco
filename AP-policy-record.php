<?php
require 'session.class.php';
require 'classes/ws_baneco.php';

$session = new Session();

$session->getSessionCookie();

$token = $session->check_session();
$arrBS = array(0 => 0, 1 => 'R', 2 => 'Error');

if ($token === true) {
    require('sibas-db.class.php');
    $link = new SibasDB();

    if (isset($_POST['flag']) && isset($_POST['de-ide']) 
        && isset($_POST['ms']) && isset($_POST['page']) 
        && isset($_POST['pr']) && isset($_POST['cia'])) {
        if ($_POST['pr'] === base64_encode('AP|05')) {

            $fecha_emision = '';
            $target = false;
            if (isset($_POST['target'])) {
                $target = true;
            }
            
            $ID = $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));
            $ws_token = false;
            $ws_usuario = '';
            $no_transaction = 0;
            $fecha_trans = '';
            $monto_trans = 0;
            $cashed = 0;
            $payment = '';

            if (empty($ID) === false) {
                $sql = 'select 
                    sae.id_emision as ide,
                    sae.no_emision,
                    sae.no_poliza,
                    sae.id_plan,
                    sae.forma_pago, 
                    sae.periodo, 
                    sae.prima,
                    sae.fecha_emision,
                    sp.prima_mensual,
                    sp.prima_anual,
                    sae.pledge,
                    sae.case_number
                from 
                    s_ap_em_cabecera as sae
                        inner join
                    s_plan as sp ON (sp.id_plan = sae.id_plan)
                where
                    sae.id_emision = "' . $ID . '"
                ;';

                if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
                    if ($rs->num_rows === 1) {
                        $row = $rs->fetch_array(MYSQLI_ASSOC);
                        $rs->free();

                        $payment = $row['forma_pago'];

                        $ws_cnx = $link->checkWebService($_SESSION['idEF'], 'AP');

                        if ($payment === 'DA' && (boolean)$row['pledge'] && $ws_cnx) {
                            $ws = new WSBaneco('VP');
                            $params = array(
                                'nroCaso' => $row['case_number']
                            );

                            if ($ws->ws_connect($params)) {
                                goto OrderCollection;
                            } else {
                                $arrBS[2] = 'No se realizó el desembolso de la pignoración';
                            }
                        } else {
                            OrderCollection:
                            if ($ws_cnx && ($payment === 'CO' || $payment === 'DM')) {
                                if (($row_user = $link->get_data_user($_SESSION['idUser'], $_SESSION['idEF'])) !== false) {
                                    $ws_usuario = $row_user['u_usuario'];
                                }

                                $ws = new WSBaneco('VC');
                                $params = array(
                                    'nroPoliza' => $row['no_poliza'],
                                    'moneda' => 1,
                                    'usuario' => $ws_usuario,
                                    'tipoPoliza' => 'AP'
                                );
                                // echo var_dump($params);
                                if ($ws->ws_connect($params) === true) {
                                    $no_transaction = (int)$ws->data;
                                    $ws_token = true;
                                    goto Issue;
                                } else {
                                    $arrBS[2] = 'La Póliza no puede ser Emitida<br>' . $ws->message;
                                }
                            } else {
                                Issue:

                                $fecha_emision = date('Y-m-d');

                                $sql = 'update 
                                s_ap_em_cabecera as sae
                                    inner join 
                                s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
                                set 
                                    sae.emitir = true, ';
                                if ($target === false) {
                                    $sql .= 'sae.fecha_emision = "' . $fecha_emision . '", ';
                                }
                                $sql .= '
                                    sae.estado = "V", 
                                    sae.leido = false
                                where 
                                    sae.id_emision = "' . $ID . '" 
                                        and sef.id_ef = "' . base64_decode($_SESSION['idEF']) . '"
                                        and sef.activado = true ;';

                                if ($link->query($sql) === true) {
                                    if ($target === true) {
                                        goto Emitir;
                                    } else {
                                        $collections = $link->verifyCollections('ap', $ID);

                                        if ($collections === 0) {
                                            $queryset = 'insert into s_ap_cobranza
                                            (id_cobranza, id_emision, numero_cuota, fecha_cuota, 
                                                monto_cuota, numero_transaccion, fecha_transaccion, 
                                                monto_transaccion, cobrado)
                                            values
                                            ';

                                            $nc = 0;
                                            $prima = 0;
                                            switch ($row['periodo']) {
                                            case 'M':
                                                $nc = 12;
                                                $prima = $row['prima_mensual'];
                                                break;
                                            case 'Y':
                                                $nc = 1;
                                                $prima = $row['prima_anual'];
                                                break;
                                            }

                                            $idc = date('U');

                                            // $fecha = date('Y-m-d', strtotime($row['fecha_emision']));
                                            $fecha = $fecha_emision;
                                            $fecha_cuota = strtotime('+0 day', strtotime($fecha));
                                            $fecha_cuota = date('Y-m-d', $fecha_cuota);

                                            for ($i = 1; $i <= $nc ; $i++) { 
                                                $idc += $i;

                                                if ($ws_token === true && $i === 1 && $payment === 'CO') {
                                                    $fecha_trans = $fecha_cuota;
                                                    $monto_trans = $prima;
                                                    $cashed = 1;
                                                }

                                                if ($i !== 1) {
                                                    $no_transaction = 0;
                                                    $fecha_trans = '';
                                                    $monto_trans = 0;
                                                    $cashed = 0;
                                                }

                                                $queryset .= '
                                                ("' . $idc . '", "' . $ID . '", "' . $i . '", 
                                                    "' . $fecha_cuota . '", "' . $prima . '", 
                                                    "' . $no_transaction . '", 
                                                    "' . $fecha_trans . '" , "' . $monto_trans . '", 
                                                    "' . $cashed . '"),';
                                                
                                                $fecha = strtotime($fecha_cuota);
                                                $month = date('n', $fecha);
                                                $year = date('Y', $fecha);
                                                $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                                                $fecha_cuota = strtotime('+' . $days . ' day', $fecha);
                                                $fecha_cuota = date('Y-m-d', $fecha_cuota);
                                            }

                                            $queryset = trim($queryset, ',') . ';';

                                            if ($link->query($queryset) === true && $link->affected_rows > 0) {
                                                Emitir:
                                                $arrBS[0] = 1;
                                                $arrBS[1] = 'certificate-policy.php?ms=' . $_POST['ms'] 
                                                    . '&page=' . $_POST['page'] . '&pr=' . base64_encode('AP') 
                                                    . '&ide=' . base64_encode($ID);
                                                $arrBS[2] = 'LA PÓLIZA FUE EMITIDA CON EXITO !!!';
                                            } else {
                                                $arrBS[2] = 'Error. No se pudo registrar las cuotas .';
                                                
                                                NotIssue:
                                                if ($link->notIssue('ap', $ID) !== false) {
                                                    $arrBS[2] .= '<br>La Póliza no pudo ser Emitida.';
                                                }
                                            }
                                        } elseif ($collections > 0) {
                                            goto Emitir;
                                        } elseif ($collections === false) {
                                            goto NotIssue;
                                        }
                                    }
                                } else {
                                    $arrBS[2] = 'La Póliza no pudo ser Emitida';
                                }
                            }
                        }
                    } else {
                        $arrBS[2] = 'Error. No se pudo emitir la Póliza.';
                    }
                } else {
                    $arrBS[2] = 'Error. No se pudo emitir la Póliza!';
                }
            } else {
                $arrBS[2] = 'La Póliza no puede ser Emitida';
            }
        } else {
            $arrBS[2] = 'Error La Póliza no puede ser Emitida';
        }
    } else {
        $arrBS[2] = 'Error La Póliza no puede ser Emitida |';
    }
}

echo json_encode($arrBS);

?>