<?php
	require_once '../clases/ClasePersona.php';

	try{

		if (!empty($_POST['mail']) and !empty($_POST['password'])){ 
		$mail = $_POST['mail'];
		$pass = md5($_POST['password']);

		
		$dao = UsuarioDAO::login($mail,$pass);
			
		}else{
		echo"<script type=\"text/javascript\">alert('Error, favor verifique sus datos e intente nuevamente.');       window.location='../../index.html';</script>"
		;
		}
	} catch (Exception $e) {
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../../index.html';</script>"; 
	}
?>