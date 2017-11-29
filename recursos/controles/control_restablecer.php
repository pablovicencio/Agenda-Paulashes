<?php
	require_once '../clases/ClasePersona.php';
	require_once '../clases/Funciones.php';

	try{


		if (isset($_POST['mail']) and ($_POST['emailOK'] = 'valido')){ 
		$mail = $_POST['mail'];

		$fun = new Funciones();

			$nueva_pass = $fun->generaPass();
		
			$upd_pass = UsuarioDAO::actualizar_contraseña($mail,md5($nueva_pass));
			
			if (count($upd_pass)>0){
			echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador del sistema'); window.location='../../index.html';</script>";  
			} else {
				$enviar_pass = $fun->correo_upd_pass($mail,$nueva_pass);
				echo"<script type=\"text/javascript\">alert('Su nueva contraseña ha sido enviada al correo (Buzon de entrada, correos no deseados o spam)'); 		window.location='../../index.html';</script>"; 
					}
		}else{
		echo"Error";
	}
	} catch (Exception $e) {
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../page_admin/index_estilista.php';</script>"; 
	}
?>