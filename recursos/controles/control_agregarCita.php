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
	require_once '../clases/Funciones.php';
	include_once '../clases/ClasePersona.php';
	include_once '../clases/ClaseSucursal.php';

	try{

		$cita = json_decode($_POST['cita']);
		
		$fec_cita = date("Y-m-d",strtotime($cita[0]));
		$hora_cita = $cita[2];
		$segundos_horaInicial=strtotime($hora_cita);
		$segundos_minutoAnadir=$cita[3]*60;
		$hora_ter=date("H:i",$segundos_horaInicial+$segundos_minutoAnadir);
		$fec_reg =  date("Y-m-d (H:i:s)", time());
		$id_suc = $cita[4];
		$estado_cita = 1;
		$id_cli = $cita[1];
		

		if (is_numeric($cita[5])) {
			$id_estilista = $cita[5];
		}else{
			$id_estilista = $us;
		}
		
		$fun = new Funciones();

		$control = $fun->control_cita($fec_cita,$hora_cita,$hora_ter,$id_cli,$id_estilista);
		if ((count($control))==0) {


			$suc = new SucursalDAO($id_suc);
            

            $cli = new ClienteDAO($id_cli);
          

            $usu = new UsuarioDAO($id_estilista);
           


			$dao = new CitaDAO('',$fec_cita,$hora_cita,$hora_ter,$fec_reg, $estado_cita);
			//var_dump($dao);
			$agendar_cita = $dao->agendar_cita($suc->getSucursal(),$cli->getCliente(),$usu->getUsuario());
			
			if (count($agendar_cita)>0){
					echo"'Error de base de datos, comuniquese con el administrador'";  
			} else {
				echo"Cita Agendada correctamente"; 
			}
		}else{
			echo"Error, estilista o cliente tienen la HORA OCUPADA. Verifique";
		}


		
	
	} catch (Exception $e) {
		//echo($e);
		echo"'Error, verifique los datos'",  $e->getMessage(); 

	}
?>