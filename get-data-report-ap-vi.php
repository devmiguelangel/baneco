<?php
if (isset($_GET['idef']) && isset($_GET['iduser'])) {
?>
<?php
require('sibas-db.class.php');
$link = new SibasDB();

//Reporte Produccion
if($_GET['frp-type']=='production'){

	$xls = FALSE;
	if(isset($_GET['frp-xls'])) {
		$xls = TRUE;
	}

	if($xls === TRUE){
		header("Content-Type:   application/vnd.ms-excel; charset=iso-8859-1");
		header("Content-Disposition: attachment; filename=produccion.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else{
		echo '<!doctype html>';
		echo '<head><link type="text/css" href="css/style.css" rel="stylesheet" /></head>';
		echo '<h2 style="text-align:center; font-weight:bold">INFORME EJECUTIVO DE PRODUCCION SEGUROS MASIVOS

</h2>';
	}
?>
	<table class="result-list" style="width:100%">
		<thead>
	      <tr>
		    <td>&nbsp;</td>
		    <td colspan="19">ACCIDENTES PERSONALES</td>
		    <td colspan="19">VIDA CORTO PLAZO</td>
		    <td colspan="2" rowspan="2">TOTAL</td>
		    <td rowspan="2">CG</td>
		    <td rowspan="2">CC</td>
		  </tr>
		  <tr>
		    <td>Ejecutivo</td>
		    <td colspan="2">PLAN A 14000</td>
		    <td>CG</td>
		    <td>CC</td>
		    <td colspan="2">PLAN B 21000</td>
		    <td>CG</td>
		    <td>CC</td>
		    <td colspan="2">PLAN C 35000</td>
		    <td>CG</td>
		    <td>CC</td>
		    <td colspan="2">PLAN D 69000</td>
		    <td>CG</td>
		    <td>CC</td>
		    <td colspan="3">TOTAL</td>
		    <td colspan="2">PLAN A 14000</td>
		    <td>CG</td>
		    <td>CC</td>
		    <td colspan="2">PLAN B 21000</td>
		    <td>CG</td>
		    <td>CC</td>
		    <td colspan="2">PLAN C 35000</td>
		    <td>CG</td>
		    <td>CC</td>
		    <td colspan="2">PLAN D 69000</td>
		    <td>CG</td>
		    <td>CC</td>
		    <td colspan="3">TOTAL</td>
		  </tr>
		</thead>

	<?php

		$s_agencia = $link->real_escape_string(trim($_GET['frp-agency']));

		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));


		$sqlUs = 'select ssu.nombre, ssu.id_usuario
			from s_usuario as ssu
			inner join s_agencia as sag ON (sag.id_agencia = ssu.id_agencia)
			where sag.agencia = "' .$s_agencia. '"
			order by ssu.nombre desc
			;';

		if(($rsUs = $link->query($sqlUs,MYSQLI_STORE_RESULT))){
			if($rsUs->num_rows > 0){
				$swBG = FALSE;
				$unread = '';
		?>
		<tbody>
		<?php
				while($rowUs = $rsUs->fetch_array(MYSQLI_ASSOC)){

					if($swBG === FALSE){
						$bg = 'background: #EEF9F8;';
					}elseif($swBG === TRUE){
						$bg = 'background: #D1EDEA;';
					}

					$rowSpan = FALSE;

					$sqlAp = 'select sp.nombre,
						sum(sac.monto_cuota) as monto_total,
						sum(if(sca.periodo = "Y", (sac.monto_cuota / 12), if(sac.numero_cuota = 1, sac.monto_cuota, 0))) as comision_ganada,
						sum(if(sca.periodo = "Y", (sac.monto_transaccion / 12), if(sac.numero_cuota = 1, sac.monto_transaccion, 0))) as comision_cobrada, count(distinct sca.id_emision) as cant_polizas,
						case when sp.id_plan = "1418936547" then "X" end as A,
						case when sp.id_plan = "1419371920" then "X" end as B,
						case when sp.id_plan = "1419372003" then "X" end as C,
						case when sp.id_plan = "1419372063" then "X" end as D
							from
						s_ap_em_cabecera as sca
							inner join
						s_ap_em_detalle as sde ON (sde.id_emision = sca.id_emision)
							inner join
						s_cliente as sc ON (sc.id_cliente = sde.id_cliente)
							inner join
						s_plan as sp ON (sp.id_plan = sca.id_plan)
							inner join
						s_ap_cobranza as sac ON (sac.id_emision = sca.id_emision)
							inner join
						s_cliente as scl ON (scl.id_cliente = sde.id_cliente)
							inner join
						s_usuario as sus ON (sus.id_usuario = sca.id_usuario)
							inner join
						s_departamento as sdep ON (sdep.id_depto = sus.id_depto)
							inner join
						s_tipo_cambio as stc ON (stc.id_ef = sca.id_ef)
						where
							sca.emitir = true and
							sca.anulado = false and
							sus.id_usuario = "'.$rowUs['id_usuario'].'" and
							sca.fecha_emision between "'.$date_b.'" and "'.$date_e.'"
						group by
							sp.id_plan
						;';

					$cant_ap_plan_a = 0;
					$monto_total_ap_plan_a = 0;
					$comision_ganada_ap_plan_a = 0;
					$comision_cobrada_ap_plan_a = 0;

					$cant_ap_plan_b = 0;
					$monto_total_ap_plan_b = 0;
					$comision_ganada_ap_plan_b = 0;
					$comision_cobrada_ap_plan_b = 0;

					$cant_ap_plan_c = 0;
					$monto_total_ap_plan_c = 0;
					$comision_ganada_ap_plan_c = 0;
					$comision_cobrada_ap_plan_c = 0;

					$cant_ap_plan_d = 0;
					$monto_total_ap_plan_d = 0;
					$comision_ganada_ap_plan_d = 0;
					$comision_cobrada_ap_plan_d = 0;

					$total_ap_comision_ganada = 0;
					$total_ap_comision_cobrada = 0;

					if(($rsAp = $link->query($sqlAp,MYSQLI_STORE_RESULT))){
						while($rowAp = $rsAp->fetch_array(MYSQLI_ASSOC)){

							if($rowAp['A']=="X"){
								$cant_ap_plan_a = $rowAp['cant_polizas'];
								$monto_total_ap_plan_a = $rowAp['monto_total'];
								$comision_ganada_ap_plan_a = $rowAp['comision_ganada'];
								$comision_cobrada_ap_plan_a = $rowAp['comision_cobrada'];
							}

							if($rowAp['B']=="X"){
								$cant_ap_plan_b = $rowAp['cant_polizas'];
								$monto_total_ap_plan_b = $rowAp['monto_total'];
								$comision_ganada_ap_plan_b = $rowAp['comision_ganada'];
								$comision_cobrada_ap_plan_b = $rowAp['comision_cobrada'];
							}

							if($rowAp['C']=="X"){
								$cant_ap_plan_c = $rowAp['cant_polizas'];
								$monto_total_ap_plan_c = $rowAp['monto_total'];
								$comision_ganada_ap_plan_c = $rowAp['comision_ganada'];
								$comision_cobrada_ap_plan_c = $rowAp['comision_cobrada'];
							}

							if($rowAp['D']=="X"){
								$cant_ap_plan_d = $rowAp['cant_polizas'];
								$monto_total_ap_plan_d = $rowAp['monto_total'];
								$comision_ganada_ap_plan_d = $rowAp['comision_ganada'];
								$comision_cobrada_ap_plan_d = $rowAp['comision_cobrada'];
							}
						}
					}

					$sqlVi = 'select sp.nombre,
						sum(sac.monto_cuota) as monto_total,
						sum(if(sca.periodo = "Y", (sac.monto_cuota / 12), if(sac.numero_cuota = 1, sac.monto_cuota, 0))) as comision_ganada,
						sum(if(sca.periodo = "Y", (sac.monto_transaccion / 12), if(sac.numero_cuota = 1, sac.monto_transaccion, 0))) as comision_cobrada, count(distinct sca.id_emision) as cant_polizas,
						case when sp.id_plan = "1419361194" then "X" end as A,
						case when sp.id_plan = "1419372863" then "X" end as B,
						case when sp.id_plan = "1419372915" then "X" end as C,
						case when sp.id_plan = "1419372982" then "X" end as D
							from
						s_vi_em_cabecera as sca
							inner join
						s_vi_em_detalle as sde ON (sde.id_emision = sca.id_emision)
							inner join
						s_cliente as sc ON (sc.id_cliente = sde.id_cliente)
							inner join
						s_plan as sp ON (sp.id_plan = sca.id_plan)
							inner join
						s_vi_cobranza as sac ON (sac.id_emision = sca.id_emision)
							inner join
						s_cliente as scl ON (scl.id_cliente = sde.id_cliente)
							inner join
						s_usuario as sus ON (sus.id_usuario = sca.id_usuario)
							inner join
						s_departamento as sdep ON (sdep.id_depto = sus.id_depto)
							inner join
						s_tipo_cambio as stc ON (stc.id_ef = sca.id_ef)
						where
							sca.emitir = true and
							sca.anulado = false and
							sus.id_usuario = "'.$rowUs['id_usuario'].'" and
							sca.fecha_emision between "'.$date_b.'" and "'.$date_e.'"
						group by
							sp.id_plan
						;';

					$cant_vi_plan_a = 0;
					$monto_total_vi_plan_a = 0;
					$comision_ganada_vi_plan_a = 0;
					$comision_cobrada_vi_plan_a = 0;

					$cant_vi_plan_b = 0;
					$monto_total_vi_plan_b = 0;
					$comision_ganada_vi_plan_b = 0;
					$comision_cobrada_vi_plan_b = 0;

					$cant_vi_plan_c = 0;
					$monto_total_vi_plan_c = 0;
					$comision_ganada_vi_plan_c = 0;
					$comision_cobrada_vi_plan_c = 0;

					$cant_vi_plan_d = 0;
					$monto_total_vi_plan_d = 0;
					$comision_ganada_vi_plan_d = 0;
					$comision_cobrada_vi_plan_d = 0;

					if(($rsVi = $link->query($sqlVi,MYSQLI_STORE_RESULT))){
						while($rowVi = $rsVi->fetch_array(MYSQLI_ASSOC)){

							if($rowVi['A']=="X"){
								$cant_vi_plan_a = $rowVi['cant_polizas'];
								$monto_total_vi_plan_a = $rowVi['monto_total'];
								$comision_ganada_vi_plan_a = $rowVi['comision_ganada'];
								$comision_cobrada_vi_plan_a = $rowVi['comision_cobrada'];
							}

							if($rowVi['B']=="X"){
								$cant_vi_plan_b = $rowVi['cant_polizas'];
								$monto_total_vi_plan_b = $rowVi['monto_total'];
								$comision_ganada_vi_plan_b = $rowVi['comision_ganada'];
								$comision_cobrada_vi_plan_b = $rowVi['comision_cobrada'];
							}

							if($rowVi['C']=="X"){
								$cant_vi_plan_c = $rowVi['cant_polizas'];
								$monto_total_vi_plan_c = $rowVi['monto_total'];
								$comision_ganada_vi_plan_c = $rowVi['comision_ganada'];
								$comision_cobrada_vi_plan_c = $rowVi['comision_cobrada'];
							}

							if($rowVi['D']=="X"){
								$cant_vi_plan_d = $rowVi['cant_polizas'];
								$monto_total_vi_plan_d = $rowVi['monto_total'];
								$comision_ganada_vi_plan_d = $rowVi['comision_ganada'];
								$comision_cobrada_vi_plan_d = $rowVi['comision_cobrada'];
							}
						}

					}


	$total_ap_monto = $monto_total_ap_plan_a + $monto_total_ap_plan_b + $monto_total_ap_plan_c + $monto_total_ap_plan_d;

	$total_ap_comision_ganada = $comision_ganada_ap_plan_a + $comision_ganada_ap_plan_b + $comision_ganada_ap_plan_c + $comision_ganada_ap_plan_d;

	$total_ap_comision_cobrada = $comision_cobrada_ap_plan_a + $comision_cobrada_ap_plan_b + $comision_cobrada_ap_plan_c + $comision_cobrada_ap_plan_d;


	$total_vi_monto = $monto_total_vi_plan_a + $monto_total_vi_plan_b + $monto_total_vi_plan_c + $monto_total_vi_plan_d;

	$total_vi_comision_ganada = $comision_ganada_vi_plan_a + $comision_ganada_vi_plan_b + $comision_ganada_vi_plan_c + $comision_ganada_vi_plan_d;

	$total_vi_comision_cobrada = $comision_cobrada_vi_plan_a + $comision_cobrada_vi_plan_b + $comision_cobrada_vi_plan_c + $comision_cobrada_vi_plan_d;


	$total_polizas = $cant_ap_plan_a + $cant_ap_plan_b + $cant_ap_plan_c + $cant_ap_plan_d + $cant_vi_plan_a + $cant_vi_plan_b + $cant_vi_plan_c + $cant_vi_plan_d

	?>
			<tr style=" <?=$bg;?> " class="row quote" rel="0"
				data-nc="<?=base64_encode($rowUs['ids']);?>" >

	        	<td <?=$rowSpan;?>><?=$rowUs['nombre'];?></td>

			    <td><?=$cant_ap_plan_a;?></td>
			    <td><?=number_format($monto_total_ap_plan_a, 0, '.', '');?></td>
			    <td><?=number_format($comision_ganada_ap_plan_a, 0, '.', '')?></td>
			    <td><?=number_format($comision_cobrada_ap_plan_a, 0, '.', '');?></td>

			    <td><?=$cant_ap_plan_b;?></td>
			    <td><?=number_format($monto_total_ap_plan_b, 0, '.', '');?></td>
			    <td><?=number_format($comision_ganada_ap_plan_b, 0, '.', '')?></td>
			    <td><?=number_format($comision_cobrada_ap_plan_b, 0, '.', '');?></td>

			    <td><?=$cant_ap_plan_c;?></td>
			    <td><?=number_format($monto_total_ap_plan_c, 0, '.', '');?></td>
			    <td><?=number_format($comision_ganada_ap_plan_c, 0, '.', '')?></td>
			    <td><?=number_format($comision_cobrada_ap_plan_c, 0, '.', '');?></td>

			    <td><?=$cant_ap_plan_d;?></td>
			    <td><?=number_format($monto_total_ap_plan_d, 0, '.', '');?></td>
			    <td><?=number_format($comision_ganada_ap_plan_d, 0, '.', '')?></td>
			    <td><?=number_format($comision_cobrada_ap_plan_d, 0, '.', '');?></td>


			    <td><?= $total_ap_monto?></td>
			    <td><?= $total_ap_comision_ganada?></td>
			    <td><?= $total_ap_comision_cobrada?></td>

			    <td><?=$cant_vi_plan_a;?></td>
			    <td><?=number_format($monto_total_vi_plan_a, 0, '.', '');?></td>
			    <td><?=number_format($comision_ganada_vi_plan_a, 0, '.', '')?></td>
			    <td><?=number_format($comision_cobrada_vi_plan_a, 0, '.', '');?></td>

			    <td><?=$cant_vi_plan_b;?></td>
			    <td><?=number_format($monto_total_vi_plan_b, 0, '.', '');?></td>
			    <td><?=number_format($comision_ganada_vi_plan_b, 0, '.', '')?></td>
			    <td><?=number_format($comision_cobrada_vi_plan_b, 0, '.', '');?></td>

			    <td><?=$cant_vi_plan_c;?></td>
			    <td><?=number_format($monto_total_vi_plan_c, 0, '.', '');?></td>
			    <td><?=number_format($comision_ganada_vi_plan_c, 0, '.', '')?></td>
			    <td><?=number_format($comision_cobrada_vi_plan_c, 0, '.', '');?></td>

			    <td><?=$cant_vi_plan_d;?></td>
			    <td><?=number_format($monto_total_vi_plan_d, 0, '.', '');?></td>
			    <td><?=number_format($comision_ganada_vi_plan_d, 0, '.', '')?></td>
			    <td><?=number_format($comision_cobrada_vi_plan_d, 0, '.', '');?></td>

			    <td><?= $total_vi_monto?></td>
			    <td><?= $total_vi_comision_ganada?></td>
			    <td><?= $total_vi_comision_cobrada?></td>

			    <td><?=$total_polizas?></td>
		        <td><?=$total_ap_monto + $total_vi_monto;?></td>
		        <td><?=$total_ap_comision_ganada + $total_vi_comision_ganada;?></td>
		        <td><?=$total_ap_comision_cobrada + $total_vi_comision_cobrada;?></td>

			</tr>
	<?php
				}
	?>
		</tbody>
		<tfoot>
	    <tr>
	        	<td colspan="29" style="text-align:left;">
	<?php
			if($xls === FALSE){
	?>

			<a href="get-data-report-ap-vi.php?idef=<?=$_GET['idef'];?>&
				iduser=<?=$_GET['iduser'];?>&
				frp-agency=<?=$_GET['frp-agency'];?>&
				frp-date-b=<?=$_GET['frp-date-b'];?>&
				frp-date-e=<?=$_GET['frp-date-e'];?>&
				frp-type=<?=$_GET['frp-type'];?>&
				frp-xls=''
				" class="send-xls" target="_blank">Exportar a Formato Excel</a>

	<?php
			}
	?>
				</td>
	        </tr>
	    </tfoot>

	<?php
			}
		}
	?>
</table>

<?php
	}

//Reporte Comision
elseif($_GET['frp-type']=='users'){

	$xls = FALSE;
	if(isset($_GET['frp-xls'])) {
		$xls = TRUE;
	}

	if($xls === true){
		header("Content-Type:   application/vnd.ms-excel; charset=iso-8859-1");
		header("Content-Disposition: attachment; filename=usuarios.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else{
		echo '<!doctype html>';
		echo '<head><link type="text/css" href="css/style.css" rel="stylesheet" /></head>';
		echo '<h2 style="text-align:center; font-weight:bold">REPORTE DE USUARIOS HABILITADOS</h2>';
	}
?>
	<table class="result-list" style="width:100%">
		<thead>
		    <td>Usuario</td>
		    <td>Nombre</td>
		    <td>Email</td>
		    <td>Cargo</td>
		    <td>Sucursal</td>
		    <td>Agencia</td>
		    <td>Fono Agencia</td>
		    <td>Fecha Creacion</td>
		  </tr>
		</thead>

	<?php

		$s_agencia = $link->real_escape_string(trim($_GET['frp-agency']));

		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));


		$sqlUs = 'select sua.usuario, sua.nombre, sua.email, sua.cargo, sua.fono_agencia, sag.agencia, sua.fecha_creacion, sdep.departamento
			from
				s_usuario sua
			left join
				s_agencia as sag ON (sag.id_agencia = sua.id_agencia)
			left join
				s_departamento as sdep ON (sdep.id_depto = sua.id_depto)';

		if($s_agencia == 'Sin Sucursal')
			$sqlUs .= 'where sdep.id_depto IS NULL and
				sua.id_tipo=5';
		else
			$sqlUs .= 'where sag.agencia = "' .$s_agencia. '" and
				sua.id_tipo=5';


		if(($rsUs = $link->query($sqlUs,MYSQLI_STORE_RESULT))){
			if($rsUs->num_rows > 0){
				$swBG = FALSE;
				$unread = '';
		?>
		<tbody>
		<?php
				while($rowUs = $rsUs->fetch_array(MYSQLI_ASSOC)){

					if($swBG === FALSE){
						$bg = 'background: #EEF9F8;';
					}elseif($swBG === TRUE){
						$bg = 'background: #D1EDEA;';
					}

					$rowSpan = FALSE;
?>

			<tr style=" <?=$bg;?> " class="row quote" rel="0"
				data-nc="<?=base64_encode($rowUs['id']);?>" >

			    <td><?=$rowUs['usuario'];?></td>
			    <td><?=$rowUs['nombre'];?></td>
			    <td><?=$rowUs['email'];?></td>
			    <td><?=$rowUs['cargo'];?></td>
			    <td><?=$rowUs['departamento'];?></td>
			    <td><?=$rowUs['agencia'];?></td>
			    <td><?=$rowUs['fono_agencia'];?></td>
			    <td><?=$rowUs['fecha_creacion'];?></td>
			</tr>
	<?php
				}



	?>
		</tbody>
		<tfoot>
	    <tr>
	        	<td colspan="29" style="text-align:left;">
	<?php
			if($xls === FALSE){
	?>
				<a href="get-data-report-ap-vi.php?idef=<?=$_GET['idef'];?>&
					iduser=<?=$_GET['iduser'];?>&
					frp-agency=<?=$_GET['frp-agency'];?>&
					frp-date-b=<?=$_GET['frp-date-b'];?>&
					frp-date-e=<?=$_GET['frp-date-e'];?>&
					frp-type=<?=$_GET['frp-type'];?>&
					frp-xls=''
					" class="send-xls" target="_blank">Exportar a Formato Excel</a>

	<?php
			}
	?>
				</td>
	        </tr>
	    </tfoot>

	<?php
			}
		}
	?>
</table>

	<?php
	}



}
?>