<script type="text/javascript" src="plugins/ambience/jquery.ambiance.js"></script>
<script type="text/javascript">
  <?php 
    $op = $_GET["op"];
    $msg = $_GET["msg"];
	$var = $_GET["var"];
	if($op==1){$valor='success';}elseif($op==2){$valor='error';}
  ?>
  $(function(){
    //PLUGIN AMBIENCE
    <?php if($msg!=''){ ?>
		 $.ambiance({message: "<?php echo $msg;?>", 
				title: "Notificacion",
				type: "<?php echo $valor?>",
				timeout: 5
				});
		 //location.load("sgc.php?l=usuarios&idhome=1");
		 //$(location).attr('href', 'sgc.php?l=crearusuario&idhome=1');		
		 setTimeout( "$(location).attr('href', 'index.php?l=des_preguntas&var=<?php echo $var;?>');",5000 );
	<?php }?>
	 
  });
</script>

<?php
include('sgc_funciones.php');
include('sgc_funciones_entorno.php');
include('main_menu.php');
require_once('config.class.php');
$conexion = new SibasDB();

//TENGO Q VER SI EL USUARIO HA INICIADO SESION
if(isset($_SESSION['usuario_sesion']) && isset($_SESSION['tipo_sesion'])) {
	//SI EL USUARIO HA INICIADO SESION, MOSTRAMOS LA PAGINA
	mostrar_pagina($_SESSION['id_usuario_sesion'], $_SESSION['tipo_sesion'], $_SESSION['usuario_sesion'], $_SESSION['id_ef_sesion'], $conexion, $lugar);
	
} else {
	//SI EL USUARIO NO HA INICIADO SESION, VEMOS SI HA HECHO CLICK EN EL FORMULARIO DE LOGIN
	if(isset($_POST['username'])) {
		//SI HA HECHO CLICK EN EL FORM DE LOGIN, VALIDAMOS LOS DATOS Q HA INGRESADO
		if(validar_login($conexion)) {
			//SI LOS DATOS DEL FORM SON CORRECTOS, MOSTRAMOS LA PAGINA
			header('Location: index.php?l='.$_GET['l'].'&var=de&list_compania=v');
			exit;
		} else {
			//SI LOS DATOS NO SON CORRECTOS, MOSTRAMOS EL FORM DE LOGIN CON EL MENSAJE DE ERROR
			session_unset();
		    session_destroy();
			session_regenerate_id(true);
			mostrar_login_form(2);
		}
	} else {
		//SI NO HA HECHO CLICK EN EL FORM, MOSTRAMOS EL FORMULARIO DE LOGIN
		session_unset();
		session_destroy();
		session_regenerate_id(true);
		mostrar_login_form(1);
	}
}


//FUNCION PARA MOSTRAR EL SGC PARA ADMINISTRACION DE USUARIOS
function mostrar_pagina($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion, $lugar) {			
?>
       
	<!-- Main Wrapper. Set this to 'fixed' for fixed layout and 'fluid' for fluid layout' -->
	<div id="da-wrapper" class="fluid">
    
        <!-- Header -->
        <div id="da-header">
        
        	<div id="da-header-top">
                
                <!-- Container -->
                <div class="da-container clearfix">
                    
                    <!-- Logo Container. All images put here will be vertically centere -->
                    <div id="da-logo-wrap">
                        <?php logo_container($tipo_sesion,$id_ef_sesion,$id_usuario_sesion,$conexion);?>
                    </div>
                                      
                    <!-- Header Toolbar Menu -->
                    <div id="da-header-toolbar" class="clearfix">
                        <?php header_toolbar_menu($id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion);?>
                    </div>
                                    
                </div>
            </div>
            
            <div id="da-header-bottom">
                <?php header_bottom('i',$_GET['var'],1);?>
            </div>
        </div>
    
        <!-- Content -->
        <div id="da-content">
            
            <!-- Container -->
            <div class="da-container clearfix">
            
                <!-- Sidebar -->
                <div id="da-sidebar-separator"></div>
                <div id="da-sidebar">
                
                    <!-- Main Navigation -->
                    <div id="da-main-nav" class="da-button-container">
                        <?php main_navegation($lugar,$id_usuario_sesion,$tipo_sesion,$usuario_sesion,$conexion);?>
                    </div>
                    
                </div>
                
                <!-- Main Content Wrapper -->
                <div id="da-content-wrap" class="clearfix">
                
                	<!-- Content Area -->
                	<div id="da-content-area">
                    
                    	<div class="grid_4">
                           <?php
                            //NECESITO SABER SI DEBO CREAR UN NUEVO USUARIO
							if(isset($_GET['crear'])) {
						
								agregar_nueva_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								
							} else {
								//VEMOS SI NOS PASAN UN ID DE USUARIO
								if(isset($_GET['idpregunta'])) {
						
									if(isset($_GET['darbaja'])) {
										
										desactivar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									}elseif(isset($_GET['daralta'])){ 
									
									    activar_compania($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
								    
									}elseif(isset($_GET['editar'])) {
										//SI NO ME PASAN 'CPASS' NI 'ELIMINAR', MUESTRO EL FORM PARA EDITAR USUARIO
										editar_pregunta($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $conexion);
										
									} 
								}elseif(isset($_GET['listarpreguntas'])) {
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_preguntas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								}elseif(isset($_GET['list_compania'])){
									//SI NO ME PASAN UN ID DE USUARIO, MUESTRO LA LISTA DE FORMULARIOS EXISTENTES
									mostrar_lista_companias_x_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion);
								}
							}
							
						   ?>
                        </div>
                                                  
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
        <!-- Footer -->
        <div id="da-footer">
        	<?php footer();?>
        </div>
        
    </div>

<?php
}

//FUNCION QUE PERMITE LISTAR LOS SEGUROS DE COMPAÑIA ACTIVOS
function mostrar_lista_companias_x_entidad($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){

//SACAMOS LAS ENTIDADES FINANCIERAS EXISTENTES Y POSTERIOR ESTEN ACTIVADAS
if($tipo_sesion=='ROOT'){
	  $selectEf="select 
					ef.id_ef, ef.nombre, ef.logo, ef.activado
				from
					s_entidad_financiera as ef
				where
					ef.activado = 1
						and exists( select 
							sh.id_ef
						from
							s_sgc_home as sh
						where
							sh.id_ef = ef.id_ef and sh.producto='".base64_decode($_GET['producto'])."');";
}else{
	 $selectEf="select 
					  ef.id_ef, ef.nombre, ef.logo, ef.activado
				  from
					  s_entidad_financiera as ef
				  where
					  ef.activado = 1 
						and exists( select 
							sh.id_ef
						from
							s_sgc_home as sh
						where
							sh.id_ef = ef.id_ef and sh.producto='".base64_decode($_GET['producto'])."')
						  and ef.id_ef = '".$id_ef_sesion."';";
}
$resef = $conexion->query($selectEf,MYSQLI_STORE_RESULT);
$num_regi_ef = $resef->num_rows;
if($num_regi_ef>0){
/*echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
				   <a href="adicionar_registro.php?opcion=crear_tipo_producto&tipo_sesion='.base64_encode($tipo_sesion).'&id_ef_sesion='.base64_encode($id_ef_sesion).'" class="da-tooltip-s various fancybox.ajax" title="Añadir registro">
				   <img src="images/add_new.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';*/
	 
	 while($regief = $resef->fetch_array(MYSQLI_ASSOC)){		 
		$select="select
				   sef.id_ef_cia,
				   sef.id_ef,
				   sef.id_compania,
				   sc.nombre as compania,
				   sc.logo
				from
				  s_ef_compania sef
				  inner join s_compania as sc on (sc.id_compania=sef.id_compania)
				where
				  sef.id_ef='".$regief['id_ef']."' and sef.activado=1 and sc.activado=1 and sef.producto='".base64_decode($_GET['producto'])."';";
		$res = $conexion->query($select,MYSQLI_STORE_RESULT);		  
		echo'
			<div class="da-panel collapsible" style="width:700px;">
				<div class="da-panel-header">
					<span class="da-panel-title">
						<img src="images/icons/black/16/list.png" alt="" />
						<b>'.$regief['nombre'].'</b> - <span lang="es">Administrar preguntas</span> 
					</span>
				</div>
				<div class="da-panel-content">
					<table class="da-table">
						<thead>
							<tr>
							  <th style="text-align:center;"><b><span lang="es">Compañía de Seguros</span></b></th>
							  <th style="text-align:center;"><b><span lang="es">Imagen</span></b></th>
							  <th></th>
							</tr>
						</thead>
						<tbody>';
						  $num = $res->num_rows;
						  if($num>0){
							    $c=1;
								while($regi = $res->fetch_array(MYSQLI_ASSOC)){
									echo'<tr>
											<td>'.$regi['compania'].'</td>
											<td style="text-align:center;">';
											   if($regi['logo']!=''){
												   if(file_exists('../images/'.$regi['logo'])){  
													   $imagen = getimagesize('../images/'.$regi['logo']); 
													   $ancho = $imagen[0];   
													   $alto = $imagen[1]; 
													  echo'<img src="../images/'.$regi['logo'].'" width="'.($ancho/2).'" height="'.($alto/2).'"/>';
												   }else{
													  echo'no existe el archivo fisico';   
												   }
											   }else{
												  echo'no existe el nombre del archivo en la base de datos';   
											   }
									   echo'</td>
											<td class="da-icon-column">
											   <ul class="action_user">';
											   
												   /*echo'<li style="padding-right:5px;"><a href="?l=des_producto&var='.$_GET['var'].'&listarproductos=v&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&id_producto='.base64_encode($regi['id_producto']).'&compania='.base64_encode($regi['compania']).'&entidad_fin='.base64_encode($regief['nombre']).'" class="add_mod da-tooltip-s various" title="Agregar Productos"></a></li>';*/
												   echo'<li style="margin-right:5px;"><a href="?l='.$_GET['l'].'&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&id_ef='.base64_encode($regi['id_ef']).'&listarpreguntas=v&var='.$_GET['var'].'&producto='.$_GET['producto'].'" class="admi-preg da-tooltip-s" title="<span lang=\'es\'>Administrar preguntas</span>"></a></li>';
												   /*echo'<li><a href="?l=au_incremento&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&entidad='.base64_encode($regief['nombre']).'&compania='.base64_encode($regi['compania']).'&listarincremento=v&var='.$_GET['var'].'" class="ad_incre da-tooltip-s" title="Administrar Incremento"></a></li>';*/
											   
											 												 
										  echo'</ul>	
											</td>
										</tr>';
										$c++;
								}
								$res->free();			
						  }else{
							 echo'<tr><td colspan="7">
									  <div class="da-message warning">
										 No existe registros alguno, razones alguna:
										 <ul>
											<li>Verifique que la Compañia de Seguros este activada</li>
											<li>Verifique que la Compañia asignada a la Entidad Financiera este activada</li>
											<li>Verifique que el producto exista en la Compañia asignada a la Entidad Financiera</li>
										  </ul>
									  </div>
								  </td></tr>';
						  }
				   echo'</tbody>
					</table>
				</div>
			</div>';
	 }
	 $resef->free();
 }else{
	echo'<div class="da-message warning">
			   <span lang="es">No existe ningun registro, probablemente se debe a</span>:
			   <ul>
				  <li lang="es">La Entidad Financiera no tiene asignado el producto Accidentes Personales</li>
				  <li lang="es">La Entidad Financiera no esta activado</li>
				  <li lang="es">La Entidad Financiera no esta creada</li>
				</ul>
		  </div>'; 
 }
}


//FUNCION QUE PERMITE LISTAR LOS FORMULARIOS
function mostrar_lista_preguntas($id_usuario_sesion, $tipo_sesion, $usuario_sesion, $id_ef_sesion, $conexion){
?>
<link type="text/css" rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css"/>
<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript">
     $('.pregunta').fancybox({
	    maxWidth	: 500,
		maxHeight	: 400,
		fitToView	: false,
		width		: '70%',
		height		: '100%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'elastic',
		closeEffect	: 'elastic'	 
	 });
</script>
<link type="text/css" rel="stylesheet" href="plugins/jalerts/jquery.alerts.css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="plugins/jalerts/jquery.alerts.js"></script>
<script type="text/javascript">
   $(function(){
	   $("a[href].accion_active").click(function(e){
		   var valor = $(this).attr('id');
		   var vec = valor.split('|');
		   var id_pregunta = vec[0];
		   var id_ef_cia = vec[1];
		   var producto = vec[2];
		   var text = vec[3]; 		  
		   jConfirm("¿Esta seguro de "+text+" la pregunta?", ""+text+" registro", function(r) {
				//alert(r);
				if(r) {
						var dataString ='id_pregunta='+id_pregunta+'&id_ef_cia='+id_ef_cia+'&producto='+producto+'&text='+text+'&opcion=active_pregunta';
						$.ajax({
							   async: true,
							   cache: false,
							   type: "POST",
							   url: "accion_registro.php",
							   data: dataString,
							   success: function(datareturn) {
									  //alert(datareturn);
									  if(datareturn==1){
										 location.reload(true);
									  }else if(datareturn==2){
										jAlert("El registro no se proceso correctamente intente nuevamente", "Mensaje");
										 e.preventDefault();
									  }
									  
							   }
					    });
					
				} else {
					//jAlert("No te gusta Actualidad jQuery", "Actualidad jQuery");
				}
		   });
		   e.preventDefault();
	   }); 
	   
	});
</script>
<?php
$id_ef_cia=base64_decode($_GET['id_ef_cia']);
$entidad=base64_decode($_GET['entidad']);
$compania=base64_decode($_GET['compania']);
$id_ef=base64_decode($_GET['id_ef']);

$selectFor="select
			   id_pregunta,
			   pregunta,
			   orden,
			   respuesta,
			   case respuesta
			     when 0 then 'No'
				 when 1 then 'Si'
			   end as respuesta_text,	 
			   producto,
			   id_ef_cia,
			   activado,
			   case activado
			     when 0 then 'inactivo'
				 when 1 then 'activo'
			   end as activado_text	  
			from
			  s_pregunta
			where
			  id_ef_cia='".$id_ef_cia."' and producto='".base64_decode($_GET['producto'])."';";
$res = $conexion->query($selectFor,MYSQLI_STORE_RESULT);		  
echo'<div class="da-panel collapsible">
		<div class="da-panel-header" style="text-align:right; padding-top:5px; padding-bottom:5px;">
			<ul class="action_user">
				<li style="margin-right:6px;">
					 <a href="?l='.$_GET['l'].'&var='.$_GET['var'].'&list_compania=v&producto='.$_GET['producto'].'" class="da-tooltip-s" title="<span lang=\'es\'>Volver</span>">
					 <img src="images/retornar.png" width="32" height="32"></a>
				</li>
				<li style="margin-right:6px;">
				   <a href="adicionar_registro.php?opcion=crear_pregunta&id_ef_cia='.$_GET['id_ef_cia'].'&compania='.$_GET['compania'].'&entidad='.$_GET['entidad'].'&id_ef='.$_GET['id_ef'].'&producto='.$_GET['producto'].'" class="da-tooltip-s pregunta fancybox.ajax" title="<span lang=\'es\'>Añadir nueva pregunta</span>">
				   <img src="images/add_new.png" width="32" height="32"></a>
				</li>
			</ul>
		</div>
	 </div>';
echo'
<div class="da-panel collapsible" style="width:850px;">
	<div class="da-panel-header">
		<span class="da-panel-title">
			<img src="images/icons/black/16/list.png" alt="" />
			<b>'.$entidad.' - '.$compania.'</b> - <span lang="es">Listado de Preguntas</span>
		</span>
	</div>
	<div class="da-panel-content">
		<table class="da-table">
			<thead>
				<tr>
					<th style="width:25px;"><b>No</b></th>
					<th style="width:300px;"><b><span lang="es">Preguntas</span></b></th>
					<th style="width:20px; text-align:center;"><b><span lang="es">Respuesta esperada</span></b></th>
					<th style="width:20px; text-align:center;"><b><span lang="es">Estado</span></b></th>
					<th style="width:20px;">&nbsp;</th>
				</tr>
			</thead>
			<tbody>';
			  $num = $res->num_rows;
			  if($num>0){
				    $c=0;
					while($regi = $res->fetch_array(MYSQLI_ASSOC)){
						$c++;
						echo'<tr ';
								  if($regi['activado']==0){
									  echo'style="background:#D44D24; color:#ffffff;"'; 
								   }else{
									  echo'';	 
								   }
						  echo'>
								<td>'.$regi['orden'].'</td>
								<td>'.$regi['pregunta'].'</td>
								<td style="text-align:center;" lang="es">'.$regi['respuesta_text'].'</td>
								<td style="text-align:center;" lang="es">'.$regi['activado_text'].'</td>
								<td class="da-icon-column">
								   <ul class="action_user">
									  <li style="margin-right:5px;"><a href="adicionar_registro.php?opcion=edita_pregunta&idpregunta='.base64_encode($regi['id_pregunta']).'&editar=v&var='.$_GET['var'].'&id_ef_cia='.base64_encode($regi['id_ef_cia']).'&id_ef='.$_GET['id_ef'].'&entidad='.$_GET['entidad'].'&compania='.$_GET['compania'].'&producto='.$_GET['producto'].'" class="edit da-tooltip-s pregunta fancybox.ajax" title="<span lang=\'es\'>Editar</span>"></a></li>';
									  if($regi['activado']==0){
										  echo'<li><a href="#" id="'.$regi['id_pregunta'].'|'.$regi['id_ef_cia'].'|'.$_GET['producto'].'|activar" class="daralta da-tooltip-s accion_active" title="<span lang=\'es\'>Activar</span>"></a></li>';
									  }else{
										  echo'<li><a href="#" id="'.$regi['id_pregunta'].'|'.$regi['id_ef_cia'].'|'.$_GET['producto'].'|desactivar" class="darbaja da-tooltip-s accion_active" title="<span lang=\'es\'>Desactivar</span>"></a></li>';  
									  }	  
								  //echo'<li><a href="?l=compania&idcompania='.base64_encode($regi['id_compania']).'&darbaja=v&var='.$_GET['var'].'&idcompania='.base64_encode($idcompania).'" class="eliminar da-tooltip-s" title="Eliminar"></a></li>';  	 
							  echo'</ul>	
								</td>
							</tr>';
					}
					$res->free();			
			  }else{
			     echo'<tr><td colspan="7">
				          <div class="da-message info">
                               No existe ningun registro, ingrese nuevos registros
                          </div>
				      </td></tr>';
			  }
	   echo'</tbody>
		</table>
	</div>
</div>';
}

?>