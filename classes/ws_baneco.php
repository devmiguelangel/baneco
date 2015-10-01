<?php
require_once ('nusoap/nusoap.php');
class WSBaneco {
    private
        $wsdl,
        $operation = array(
            'U'     => 'ValidacionUsuarioCmp',
            'SC'    => 'BuscarCliente',
            'DC'    => 'DatosCliente',
            'RC'    => 'RegistrarOrdenCobro',
            'VC'    => 'VerificarCobro',
            'CC'    => 'ValidarCuenta',
            'RP'    => 'RegistrarPignoracion',
            'VP'    => 'NroCasoTieneDesembolso',
        ),
        $namespace,
        $soapAction,
        $error_ws = true,
        $error = true,
        $response = null,
        $method = null;

    public 
        $message = '', 
        $data = array();

    public function __construct ($method) {
        $this->method = $method;

        $this->wsdl = 'https://192.168.3.1:82/ws_sudamericana/wstcorp.asmx?wsdl';
        // $this->operation = '';
        $this->namespace = 'http://schemas.xmlsoap.org/soap/envelope/';
        $this->soapAction = 'http://tempuri.org/' . $this->operation[$this->method];
    }

    public function ws_connect($kwargs) {
        $this->soapClient = new nusoap_client($this->wsdl, true);

        $this->error_ws = $this->soapClient->getError();

        if (empty($this->error_ws)) {
            $this->response = $this->soapClient->call($this->operation[$this->method], $kwargs);

            $this->error = $this->soapClient->getError();

            if (empty($this->error)) {
                if (is_array($this->response) && $this->method !== 'RC') {
                    switch ($this->method) {
                    case 'U':
                        return $this->get_user();
                        break;
                    case 'SC':
                        return $this->get_client();
                        break;
                    case 'DC':
                        return $this->get_client_data();
                        break;
                    case 'VC':
                        return $this->get_collection_check();
                        break;
                    case 'CC':
                        return $this->get_account_check();
                        break;
                    case 'RP':
                        return $this->getPledgeRegistered();
                        break;
                    case 'VP':
                        return $this->getPledgeCheck();
                        break;
                    }
                } elseif ($this->method === 'RC') {
                    return $this->get_collection_record();
                } else {
                    $this->message = 'No existen datos';
                }
            } else {
                $this->message = 'Error. No se puede conectar al Web Service!';
            }
        } else {
            $this->message = 'No se puede conectar al Web Service!';
        }
        
        return false;
    }

    private function get_user()
    {
        if (is_array($this->response['ValidacionUsuarioCmpResult'])) {
            $this->data = $this->response['ValidacionUsuarioCmpResult']['diffgram'];
            
            if (array_key_exists('NewDataSet', $this->data)) {
                $this->data = $this->data['NewDataSet']['usuario'];
                return true;
            } else {
                $this->message = $this->data['DocumentElement']['usuario']['mensaje'];
            }
        } else {
            $this->message = 'El usuario no existe.';
        }
            
        return false;
    }

    private function get_client()
    {
        if (is_array($this->response['BuscarClienteResult'])) {
            $this->data = $this->response['BuscarClienteResult']['diffgram'];
            
            if (is_array($this->data)) {
                $this->data = $this->data['NewDataSet']['cliente'];
                return true;
            } else {
                $this->message = 'El cliente no existe';
            }
        } else {
            $this->message = 'El cliente no existe.';
        }

        return false;
    }

    private function get_client_data()
    {
        if (is_array($this->response['DatosClienteResult'])) {
            $this->data = $this->response['DatosClienteResult']['diffgram'];
            
            if (is_array($this->data)) {
                $this->data = $this->data['NewDataSet']['cliente'];
                return true;
            } else {
                $this->message = 'El cliente no existe';
            }
        } else {
            $this->message = 'El cliente no existe.';
        }

        return false;
    }

    private function get_collection_record()
    {
        if (empty($this->response) && !is_array($this->response)) {
            return true;
        } else {
            $this->message = 'No se pudo registrar la Orden de Cobro';
        }

        return false;
    }

    private function get_collection_check()
    {
        if (is_string($this->response['VerificarCobroResult'])) {
            $this->data = $this->response['VerificarCobroResult'];
            
            if (!empty($this->data)) {
                return true;
                /*if ($this->data === 'true') {
                    return true;
                } elseif ($this->data === 'false') {
                    $this->message = 'No se realizó el Cobro en Caja';
                }*/
            } else {
                $this->message = 'No se realizó el Cobro en Caja.';
            }
        } else {
            $this->message = 'No existe la Orden de Cobro';
        }

        return false;
    }

    private function get_account_check()
    {
        if (is_array($this->response['ValidarCuentaResult'])) {
            $this->data = $this->response['ValidarCuentaResult'];
            
            if ($this->data['CodigoMensaje'] === '0000') {
                return true;
            } else {
                $this->message = $this->data['Mensaje'];
            }
        } else {
            $this->message = 'Error. La Cuenta no existe.';
        }

        return false;
    }

    private function getPledgeRegistered()
    {
        if (count($this->response) === 1) {
            if (array_key_exists('RegistrarPignoracionResult', $this->response)) {
                return (int)$this->response['RegistrarPignoracionResult'];
            }
        }

        return 0;
    }

    private function getPledgeCheck()
    {
        if (array_key_exists('NroCasoTieneDesembolsoResult', $this->response)) {
            if ($this->response['NroCasoTieneDesembolsoResult'] === 'true') {
                return true;
            }
        }

        return false;
    }

} 