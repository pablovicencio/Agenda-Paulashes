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

	try{

		$fecha = date("Y-m-d",strtotime($_POST['fecha']));

		$dao = new CitaDAO();
		 $re = $dao->listar_citas($fecha);


          $horas = array();


          foreach($re as $row){

                $horas[] = $row;
    
              }
		ob_end_clean();
		
		echo json_encode($horas);
	
	} catch (Exception $e) {
		//echo($e);
		echo"'Error, verifique los datos'",  $e->getMessage(); 

	}
?>