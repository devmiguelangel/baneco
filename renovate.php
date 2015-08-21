<?php

require 'sibas-db.class.php';

$link = new SibasDB();

if (isset($_GET['ide'], $_GET['pr'])) {
	$ide 	= $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$pr 	= $link->real_escape_string(trim(base64_decode($_GET['pr'])));

	$sql = 'select 
		sde.id_emision as ide,
		sde.no_poliza,
		sde.fecha_emision,
		sde.periodo,
		DATE_ADD(sde.fecha_emision, INTERVAL 1 YEAR) as fecha_renovacion,
		sde.renovation,
		sde.data_renovation,
		sco.numero_cuota,
    	sco.cobrado
	from 
		s_' . strtolower($pr) . '_em_cabecera as sde
			inner join
		s_' . strtolower($pr) . '_cobranza as sco ON (sco.id_emision = sde.id_emision)
	where
		sde.id_emision = "' . $ide . '"
			and sde.emitir = true
			and sde.anulado = false
	order by sco.numero_cuota desc
	limit 0, 1
	;';
	
	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();

			if ((boolean)$row['cobrado']) {
				$data = array();
				$current_date 	= date('Y-m-d H:i:s');
				$renovation		= (int)$row['renovation'];
				
				if ($renovation > 0) {
					$data = json_decode($row['data_renovation'], true);
				}

				$data[] = array(
					'no_poliza'			=> $row['no_poliza'],
					'previous_idate'	=> $row['fecha_emision'],
					'date_renovation' 	=> $current_date
				);

				$nc = 0;
				switch ($row['periodo']) {
				case 'M':
					$nc = 12;
					break;
				case 'Y':
					$nc = 1;
					break;
				}

				$sql = 'select 
					sco.numero_cuota,
					sco.fecha_cuota,
					sco.monto_cuota,
					DATE_ADD(sco.fecha_cuota, INTERVAL 1 YEAR) as fecha_cuota_renovacion
				from
					s_' . strtolower($pr) . '_cobranza as sco
				where
					sco.id_emision = "' . $row['ide'] . '"
				order by sco.numero_cuota desc
				limit ' . $nc . '
				;';

				if (($rs_co = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
					if ($rs_co->num_rows > 0 && $rs_co->num_rows <= 12) {
						$data_co = array();

						while ($row_co = $rs_co->fetch_array(MYSQLI_ASSOC)) {
							$no_cuota = (int)$row_co['numero_cuota'];

							$data_co[$no_cuota] = array(
								'numero_cuota'	=> $no_cuota,
								'fecha_cuota' 	=> $row_co['fecha_cuota_renovacion'],
								'monto_cuota' 	=> $row_co['monto_cuota']
							);
						}

						ksort($data_co);

						$sql = 'insert into s_' . strtolower($pr) . '_cobranza
						(id_cobranza, id_emision, numero_cuota, fecha_cuota, 
							monto_cuota, numero_transaccion, fecha_transaccion, 
							monto_transaccion, cobrado)
						values';

						$idc = date('U');

						foreach ($data_co as $key => $value) {
							$idc += $key;

							$sql .= '
							("' . $idc . '", "' . $row['ide'] . '", "' . $value['numero_cuota'] . '", 
								"' . $value['fecha_cuota'] . '", "' . $value['monto_cuota'] . '", 
								"0", "" , "", "0"),';
						}

						$sql = trim($sql, ',') . ';';

						if ($link->query($sql)) {
							$sql = 'update s_' . strtolower($pr) . '_em_cabecera as sde
							set
								sde.fecha_emision = "' . $row['fecha_renovacion'] . '",
								sde.renovation = "' . ($renovation + 1) . '",
								sde.data_renovation = "' . $link->real_escape_string(json_encode($data)) . '"
							where
								sde.id_emision = "' . $row['ide'] . '"
							;';
							
							if ($link->query($sql)) {
	?>
	<div>La Póliza fue renovada.</div>
	<script type="text/javascript">
	setTimeout(function () {
		location.reload();
	}, 500);
	</script>
	<?php
							} else {
								goto ErrRev;
							}
						} else {
							goto ErrRev;
						}
					} else {
						goto ErrRev;
					}
				} else {

				}
			} else {
?>
<div>La Póliza no puede ser renovada</div>
<?php				
			}
		} else {
			goto ErrRev;
		}
	} else {
		goto ErrRev;
	}
} else {
	ErrRev:
?>
<div>La Póliza no puede ser renovada.</div>
<?php
}

?>