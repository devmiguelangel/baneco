<?php
require('sibas-db.class.php');

$ide = null;
$link = new SibasDB();

if (isset($_GET['product']) && isset($_FILES['attached']) && isset($_POST['attached'])) {
	$product = $link->real_escape_string(trim($_GET['product']));
	$attached = $link->real_escape_string(trim(base64_decode($_POST['attached'])));

	Upload:
	$arr_type = array();
	$arr_extension = array();

	$arr_type = array(
		array(
			'type' 	=> 'application/pdf',
			'ext'	=> 'pdf'
		),
		array(
			'type' 	=> 'binary/octet-stream',
			'ext'	=> 'pdf'
		),
		array(
			'type' 	=> 'image/jpeg',
			'ext'	=> 'jpg'
		),
		array(
			'type' 	=> 'image/png',
			'ext'	=> 'png'
		),
		array(
			'type' 	=> 'image/pjpeg',
			'ext'	=> 'jpg'
		),
		array(
			'type' 	=> 'image/x-png',
			'ext'	=> 'png'
		),
	);
	
	$arr_extension = array('rar', 'zip');
	
	$sw = false;
	if (empty($attached) === false) { $sw = true; }
	
	$file_name = $_FILES['attached']['name'];
	$file_type = $_FILES['attached']['type'];
	$file_size = $_FILES['attached']['size'];
	$file_error = $_FILES['attached']['error'];
	$file_tmp = $_FILES['attached']['tmp_name'];
	
	$file_id = date('U') . '_' . strtolower($product) . '_' . md5(uniqid('@F#1$' . time(), true));
	$file_extension = null;

	foreach ($arr_type as $key => $type) {
		if (array_search($file_type, $type, true) !== false) {
			$file_extension = $type['ext'];
			break;
		}
	}

	if (is_null($file_extension)) {
		$ext 	= explode('.', $file_name);
		$fext	= end($ext);

		if (in_array($fext, $arr_extension)) {
			$file_extension = $fext;
		}
	}
	
	if ($_FILES['attached']['error'] > 0) {
		echo '0|' . fileUploadErrorMsg($_FILES['attached']['error']);
	} else {
		if ((20 * 1024 * 1024) >= $file_size 
            && (in_array($file_type, $arr_type) === true 
                || !is_null($file_extension))) {

			$file_new = $file_id . '.' . $file_extension;
            
			$dir = 'files/';
			if (!is_dir($dir)) {
				mkdir($dir, 0777);
			} else {
				chmod($dir, 0777);
			}
			
			if (file_exists($dir . $file_new) === true) {
				echo 'El Archivo ' . $file_new . ' ya existe.';
			} else {
				if ($sw === true) {
					if (file_exists($dir . $attached) === true) {
						//$old = getcwd(); // Save the current directory
						//chdir($dir);
						unlink($dir . $attached);
						//chdir($old); // Restore the old working directory
					}
				}
				
				if (move_uploaded_file($file_tmp, $dir . $file_new) === true) {
					if ($ide !== null) {
						$sql = 'update s_' . strtolower($product) . '_em_cabecera 
						set 
							po_archivo = "' . $file_new . '"
						where 
							id_emision = "' . $ide . '"
						;';

						if ($link->query($sql) === true) {
							echo 'OK';
						} else {
							echo 'Error';
						}
					} else {
						echo '1|' . base64_encode($file_new) . '|' . $attached;
					}
				} else {
					echo '0|El Archivo no pudo ser subido';
				}
			}
		} else {
			echo '0|El Archivo no puede ser Subido ';
		}
	}

	if ($ide !== null) {
		header('Location: index.php?ms=' . md5('MS_REP') 
			. '&page=' . md5('P_policy') . '&pr=' . base64_encode($product));
	}
} elseif (isset($_POST['ide'], $_POST['product'], $_FILES['attached'])) {
	$attached = '';
	$product = $link->real_escape_string(trim(base64_decode($_POST['product'])));
	$ide = $link->real_escape_string(trim(base64_decode($_POST['ide'])));

	goto Upload;
} else {
	echo '0|Error: El Archivo no puede ser Subido ';
}

function fileUploadErrorMsg($error_code) {
	switch ($error_code) {
		case UPLOAD_ERR_INI_SIZE:
			return 'El archivo es más grande que lo permitido por el Servidor.'; break;
        case UPLOAD_ERR_FORM_SIZE: 
            return 'El archivo subido es demasiado grande.'; break;
        case UPLOAD_ERR_PARTIAL: 
            return 'El archivo subido no se terminó de cargar (probablemente cancelado por el usuario).'; break;
        case UPLOAD_ERR_NO_FILE: 
            return 'No se subió ningún archivo'; break;
        case UPLOAD_ERR_NO_TMP_DIR: 
            return 'Error del servidor: Falta el directorio temporal.'; break;
        case UPLOAD_ERR_CANT_WRITE: 
            return 'Error del servidor: Error de escritura en disco'; break;
        case UPLOAD_ERR_EXTENSION: 
            return 'Error del servidor: Subida detenida por la extención'; break;
     	default: 
            return 'Error del servidor: '.$error_code; break;
    } 
}