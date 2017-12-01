<?php
session_start();
	//$_SESSION["correo"];
	if( isset($_SESSION['id_usu']) ){
		//Si la sesión esta seteada no hace nada
		$us = $_SESSION['id_usu'];
	}
	else{
		//Si no lo redirige a la pagina index para que inicie la sesion	
		header("location: ../../index.html");
	}      

	require_once '../clases/ClaseCita.php';
	require_once '../clases/ClaseAtencion.php';
	require_once '../clases/ClasePersona.php';


	try{

		$cita = new CitaDAO($id_cita); 

		$id_cita = $_POST['id'];
		$est_ant = $_POST['est'];


		if (isset($_POST['conf'])){
			$upd_cit = $cita->confirmar_cita();
			$aviso = 'Confirmada';

		}elseif (isset($_POST['anu'])) {
			$upd_cit = $cita->anular_cita();
			$aviso = 'Anulada';

		}elseif (isset($_POST['doc'])) {
			$fecha = date("Y-m-d (H:i:s)", time());
			$obs = $_POST['obs'];

			$usu = new UsuarioDAO($us);

			$ate = new AtencionDAO($fecha, $obs);

			$upd_cit = $dao->atender_cita($cita->getCita(), $usu->getUsuario());
			$aviso = 'Documentada';
		}
		
	
			
			if (count($upd_cit)>0){
			echo"<script type=\"text/javascript\">alert('Error de base de datos, comuniquese con el administrador'); window.location='../page_admin/index_estilista.php';</script>"; 
			} else {
				echo"<script type=\"text/javascript\">alert('Cita ".$aviso." correctamente'); window.location='../page_admin/index_estilista.php';</script>"; 
	}
	
	} catch (Exception $e) {
		//echo($e);
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/index_usuario.php';</script>"; 

	}
?>