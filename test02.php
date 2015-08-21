<form id="form-ws" name="form-ws" action="" method="post">
    <!-- <select name="ws-poliza" id="ws-poliza">
        <option value="POL-VIM-SC-">POL-VIM-SC-</option>
        <option value="POL-APM-SC-">POL-APM-SC-</option>
    </select> -->
    <input type="text" id="ws-ci" name="ws-ci" value="" ><br>
    <input type="text" id="ws_user" name="ws_user" value="">
    <input type="submit" value="Enviar" >
</form>

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// echo date('Y-m-d', strtotime('1989-05-03T00:00:00-04:00'));

if (isset($_POST['ws-ci'])) {
    require_once ('nusoap/nusoap.php');

    $ci = $_POST['ws-ci'];
    $user = $_POST['ws_user'];
    // $poliza = $_POST['ws-poliza'];

    $wsdl = 'https://192.168.3.1:82/ws_sudamericana/wstcorp.asmx?wsdl';
	
	$operation = 'VerificarCobro';
    $namespace = 'http://schemas.xmlsoap.org/soap/envelope/';
    $soapAction = 'http://tempuri.org/VerificarCobro';

    $soapClient = new nusoap_client($wsdl, true);

    $error_ws = $soapClient->getError();

    if (empty($error_ws)) {
        $params = array(
            // 'NroDocumento' => $ci,
            // 'nroPoliza' => $poliza . $ci . '-2015',
            'nroPoliza' => $ci,
            // 'nombre' => 'Juan Perez',
            // 'importe' => 300,
            'moneda' => 1,
            'usuario' => 'HGM',
            'tipoPoliza' => 'AP'
            // 'nit' => '1234567'
            // 'Usuario' => 'FPE',
            // 'Clave' => 'Mediador10',
            // 'NroDocumento' => $ci,
            // 'codigo' => $ci
            // 'NroCuenta' => $ci,
            // 'codigoCliente' => $user
        );
        // echo var_dump($params);
        $response = $soapClient->call($operation, $params);

        $error = $soapClient->getError();
        // var_dump($error);
        var_dump($response);
        if (empty($error)) {
            //var_dump($response);
            // if (is_array($response) === true) {
            //     if (is_array($response['DatosClienteResult']) === true) {
            //         $data_client = $response['BuscarClienteResult']['diffgram']['NewDataSet']['cliente'];

            //         // $data_client = $response['BuscarClienteResult'];

            //         var_dump($data_client);
                    

            //         // foreach ($data_client as $key => $value) {
            //         //     echo '[' . $key . '] <= ' . $value . '<br>';
            //         // }

            //     } else {
            //         echo 'El titular no existe'. var_dump($response);
            //     }
            // } else {
            //     echo '<br><br>No existen datos'. var_dump($response);
            // }
        } else {
            echo 'Error. No se puede conectar al Web Service!';
        }
    } else {
        echo 'No se puede conectar al Web Service!';
    }
}
//  1763341PA
//  7188126TJ
?>