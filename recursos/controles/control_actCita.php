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
	require_once '../clases/Funciones.php';


	try{

		$id_cita = $_POST['id'];

		$cita = new CitaDAO($id_cita); 


		if (isset($_POST['conf'])){
			$upd_cit = $cita->confirmar_cita($us);
			$aviso = 'Confirmada';

		}elseif (isset($_POST['anu'])) {
			$upd_cit = $cita->anular_cita($us);
			$aviso = 'Anulada';

		}elseif (isset($_POST['doc'])) {

			$fun = new funciones();
			$validar_cita = $fun->validar_est_cita($id_cita);
			if ($validar_cita['usu']<>$us ) {
				echo"<script type=\"text/javascript\">alert('Error. No estas autorizado para atender esta cita'); window.location='../paginas_usu/index_usuario.php'; </script>";
				goto fin;
			}
			$fecha = date("Y-m-d (H:i:s)", time());
			$obs = $_POST['obs'];

			$usu = new UsuarioDAO($us);

			$ate = new AtencionDAO($fecha, $obs);

			$upd_cit = $ate->atender_cita($cita->getCita(), $usu->getUsuario());
			$aviso = 'Documentada';
		}
		
	
			
			if (count($upd_cit)>0){
			echo"<script type=\"text/javascript\">alert('Error de base de datos, comuniquese con el administrador'); window.location='../paginas_usu/index_usuario.php';</script>"; 
			} else {
				echo"<script type=\"text/javascript\">alert('Cita ".$aviso." correctamente'); window.location='../paginas_usu/index_usuario.php';</script>"; 
	}

	fin:
	
	} catch (Exception $e) {
		//echo($e);
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/index_usuario.php';</script>"; 

	}
?>