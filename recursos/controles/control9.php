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

	require_once '../db/pauDAO.php';

	try{

		$dao = new pauDAO(); 

		if (isset($_POST['conf'])){

			$time = time();
			$fec_con = date("Y-m-d (H:i:s)", $time);
			$id_cita = $_POST['id'];
			$estado = 2;
			$upd_cit = $dao->upd_cit($id_cita, $estado, $fec_con);
			$aviso = 'Confirmada';

		}elseif (isset($_POST['anu'])) {
			$time = time();
			$fec_anu = date("Y-m-d (H:i:s)", $time);
			$id_cita = $_POST['id'];
			$estado = 4;
			$upd_cit = $dao->upd_cit($id_cita, $estado, $fec_anu);
			$aviso = 'Anulada';

		}elseif (isset($_POST['doc'])) {
			$time = time();
			$fec_aten = date("Y-m-d (H:i:s)", $time);
			$id_cita = $_POST['id'];
			$obs = $_POST['obs'];
			$estado = 3;
			$upd_cit = $dao->doc_cit($id_cita, $estado, $fec_aten, $obs);
			$aviso = 'Documentada';
		}
		
	
			
			if (count($upd_cit)>0){
			echo"<script type=\"text/javascript\">alert('Error de base de datos, comuniquese con el administrador'); window.location='../page_admin/index_estilista.php';</script>"; 
			} else {
				echo"<script type=\"text/javascript\">alert('Cita ".$aviso." correctamente'); window.location='../page_admin/index_estilista.php';</script>"; 
	}
	
	} catch (Exception $e) {
		//echo($e);
		echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../page_admin/index_estilista.php';</script>"; 

	}
?>