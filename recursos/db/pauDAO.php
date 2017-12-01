<?php
	require_once 'PHPMailer/class.phpmailer.php';
	require_once 'PHPMailer/class.smtp.php';	
	require_once 'db.php';
	
	
	
	class pauDAO{

		// /*///////////////////////////////////////
		// 	control choque citas
		// //////////////////////////////////////*/
		// public function control_cita($fec_cita,$hora_cita,$hora_ter,$id_cli,$id_estilista) {

		// 	try{
				
				
		// 		$pdo = AccesoDB::getCon();


		// 		$sql = "select id_cita from citas where fec_cita = '".$fec_cita."' 
		// 				and (cast(hora_cita as time) >= '".$hora_cita.":00' and cast(hora_cita as time) < '".$hora_ter.":00')
		// 				and (id_cli = ".$id_cli." or id_estilista = ".$id_estilista.") and estado_cita <> 4
		// 				union all
		// 				select id_cita from citas where fec_cita = '".$fec_cita."'
		// 				and (cast(hora_ter as time) > '".$hora_cita.":00' and cast(hora_ter as time) <= '".$hora_ter.":00')
		// 				and (id_cli = ".$id_cli." or id_estilista = ".$id_estilista.") and estado_cita <> 4";
				

		// 		$stmt = $pdo->prepare($sql);
		// 		$stmt->execute();

		// 		$response = $stmt->fetchAll();
		// 		return $response;

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }


		/*///////////////////////////////////////
			documentar cita
		//////////////////////////////////////*/
		public function doc_cit($id_cita, $estado, $fec, $obs) {

			try{
				
				$pdo = AccesoDB::getCon();

				$sql_upd_cit = "update citas
								set estado_cita = ".$estado." , fec_aten = '".$fec."', obs_age = '".$obs."'
								where id_cita = ".$id_cita."";


				$stmt = $pdo->prepare($sql_upd_cit);
				$stmt->execute();
		

			} catch (Exception $e) {
				throw $e;
			}
		}





		/*///////////////////////////////////////
			confirmar o anula cita
		//////////////////////////////////////*/
		public function upd_cit($id_cita, $estado, $fec) {

			try{
				
				$pdo = AccesoDB::getCon();

				$sql_upd_cit = "update citas
							set estado_cita = ".$estado." , fec_aten = '".$fec."'
							where id_cita = ".$id_cita."";


				$stmt = $pdo->prepare($sql_upd_cit);
				$stmt->execute();
		

			} catch (Exception $e) {
				throw $e;
			}
		}




		/*///////////////////////////////////////
			Cargar Datos cita
		//////////////////////////////////////*/
		public function cargar_datos_cita($id_cita) {

			try{
				
				
				$pdo = AccesoDB::getCon();


				$sql = "select a.fec_cita, b.rut_cli, b.fono_cli, b.mail_cli,a.hora_cita,a.hora_ter, ubi.desc_item ubi, est.desc_item est , a.estado_cita, a.obs_age
						from citas a, clientes b, parametros ubi, parametros est
						where  a.id_cli = b.id_cli
						and a.ubicacion_age = ubi.cod_item and ubi.cod_grupo = 1 and ubi.vigencia = 1
						and a.estado_cita = est.cod_item and est.cod_grupo = 2
						and id_cita = ".$id_cita."";
				

				$stmt = $pdo->prepare($sql);
				$stmt->execute();

				$response = $stmt->fetchAll();
				return $response;

			} catch (Exception $e) {
				throw $e;
			}
		}


		/*///////////////////////////////////////
			Cargar atenciones cliente
		//////////////////////////////////////*/
		public function cargar_atenciones_cli($id_cita) {

			try{
				
				
				$pdo = AccesoDB::getCon();


				$sql = "select b.nom_cli, a.fec_cita, e.nom_estilista, ubi.desc_item ubi, a.obs_age, e.color_estilista
						from citas a, clientes b, parametros ubi, estilistas e
						where  a.id_cli = b.id_cli
						and a.estado_cita =  3
						and a.ubicacion_age = ubi.cod_item and ubi.cod_grupo = 1 and ubi.vigencia = 1
						and a.id_estilista = e.id_estilista and b.id_cli = (select id_cli from citas where id_cita = ".$id_cita." )
						order by a.fec_cita";
				

				$stmt = $pdo->prepare($sql);
				$stmt->execute();

				$response = $stmt->fetchAll();
				return $response;

			} catch (Exception $e) {
				throw $e;
			}
		}



		// /*///////////////////////////////////////
		// 	restablecer pass estilista
		// //////////////////////////////////////*/
		// public function res_pass($correo,$new_pass){

		// 	try{
		// 		//echo generaPass();
		// 		//$pass = generaPass();
				
		// 		$pdo = AccesoDB::getCon();

		// 		$sql_usu = "UPDATE estilistas
		// 					SET pass_estilista = MD5('".$new_pass."') WHERE mail_estilista ='".$correo."'";

		// 		$stmt = $pdo->prepare($sql_usu);
		// 		$stmt->execute();
		

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }




		/*///////////////////////////////////////
			Definir color de estado
		//////////////////////////////////////*/
		public function colorestado($estado) {

			try{
				
				if ($estado == "Agendada"){
					$est = '<td style="background:#FE2E2E ; color:#FFFFFF">'.$estado.'</td> ';
				}else if ($estado == "Confirmada"){
					$est = '<td style="background:#DBA901 ; color:#FFFFFF">'.$estado.'</td> ';
				}else if ($estado == "Atendida"){
					$est = '<td style="background:#0B610B ; color:#FFFFFF">'.$estado.'</td> ';
				}else if ($estado == "Anulada"){
					$est = '<td style="background:#0B243B ; color:#FFFFFF">'.$estado.'</td> ';
				}else {
					$est = '<td>'.$estado.'</td> ';
						}
				return $est;

			} catch (Exception $e) {
				throw $e;
			}
		}



		/*///////////////////////////////////////
			Cargar citas estilista
		//////////////////////////////////////*/
		public function cargar_citas_est($us, $dia) {

			try{
				
				
				$pdo = AccesoDB::getCon();


				$sql = "select a.id_cita, b.nom_cli, b.fono_cli, a.hora_cita, a.hora_ter, est.desc_item est, ubi.desc_item ubi
						from citas a, clientes b, parametros ubi, parametros est
						where  a.id_cli = b.id_cli
						and a.ubicacion_age = ubi.cod_item and ubi.cod_grupo = 1 and ubi.vigencia = 1
						and a.estado_cita = est.cod_item and est.cod_grupo = 2 and est.vigencia = 1
						and a.id_estilista = ".$us." and a.fec_cita = '".$dia."' order by hora_cita";

				$stmt = $pdo->prepare($sql);
				$stmt->execute();

				$response = $stmt->fetchAll();
				return $response;

			} catch (Exception $e) {
				throw $e;
			}
		}



		// /*///////////////////////////////////////
		// 	Cargar horas agendadas del dia
		// //////////////////////////////////////*/
		// public function cargar_horas_agen($dia) {

		// 	try{
				
				
		// 		$pdo = AccesoDB::getCon();


		// 		$sql = "select a.hora_cita, a.hora_ter, b.nom_cli, c.nom_estilista, d.desc_item ubicacion, e.desc_item estado,c.color_estilista
		// 				from citas a, clientes b, estilistas c, parametros d, parametros e
		// 				where a.id_cli = b.id_cli and a.id_estilista = c.id_estilista and a.ubicacion_age = d.cod_item and d.cod_grupo = 1 and d.vigencia = 1
		// 				and a.estado_cita = e.cod_item and e.cod_grupo = 2 and e.vigencia = 1 and a.estado_cita <> 4 and  fec_cita = '".$dia."' order by a.hora_cita,a.hora_ter";

		// 		$stmt = $pdo->prepare($sql);
		// 		$stmt->execute();

		// 		$response = $stmt->fetchAll();
		// 		return $response;

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }




		// /*///////////////////////////////////////
		// 	crear cita
		// //////////////////////////////////////*/
		// public function ins_cit($fec_cita,$hora_cita,$hora_ter,$fec_reg,$ubicacion_age,$estado_cita,$id_cli,$id_estilista) {

		// 	try{
				
		// 		$pdo = AccesoDB::getCon();

		// 		$sql_usu = "INSERT INTO `citas`(`fec_cita`,`hora_cita`,`hora_ter`,`fec_reg`,`ubicacion_age`,`obs_age`,`estado_cita`,`id_cli`,`id_estilista`)
		// 								VALUES('".$fec_cita."','".$hora_cita."','".$hora_ter."','".$fec_reg."','".$ubicacion_age."','','".$estado_cita."','".$id_cli."','".$id_estilista."')";


		// 		$stmt = $pdo->prepare($sql_usu);
		// 		$stmt->execute();
		

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }




		// /*///////////////////////////////////////
		// 	modificar estilista
		// //////////////////////////////////////*/
		// public function upd_usu($id, $nom, $mail, $fono, $vig,$color) {

		// 	try{
				
		// 		$pdo = AccesoDB::getCon();

		// 		$sql_usu = "update estilistas
		// 					set  mail_estilista = '".$mail."', fono_estilista = ".$fono.", nom_estilista = '".$nom."', vigencia_estilista = ".$vig.", color_estilista = '".$color."'
		// 					where id_estilista = ".$id."";

		// 		$stmt = $pdo->prepare($sql_usu);
		// 		$stmt->execute();
		

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }




		// /*///////////////////////////////////////
		// 	Cargar usuario para modificar
		// //////////////////////////////////////*/
		// public function cargar_usu($id) {

		// 	try{
				
				
		// 		$pdo = AccesoDB::getCon();


		// 		$sql = "SELECT * FROM estilistas where  id_estilista = ".$id."";

		// 		$stmt = $pdo->prepare($sql);
		// 		$stmt->execute();

		// 		$response = $stmt->fetchAll();
		// 		return $response;

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }


		// /*///////////////////////////////////////
		// 	cambiar contraseña
		// //////////////////////////////////////*/
		// public function upd_pass($pass, $us) {

		// 	try{
				
		// 		$pdo = AccesoDB::getCon();

		// 		$sql_upd_pass = "UPDATE estilistas SET  pass_estilista =MD5('".$pass."') WHERE id_estilista =".$us."";

		// 		$stmt = $pdo->prepare($sql_upd_pass);
		// 		$stmt->execute();

			

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }


		// /*///////////////////////////////////////
		// 	validar contraseña para cambio
		// //////////////////////////////////////*/
		// public function old_pass($us) {

		// 	try{
				
				
		// 		$pdo = AccesoDB::getCon();

		// 		$sql = "SELECT pass_estilista FROM estilistas where id_estilista = ".$us."";


		// 		$stmt = $pdo->prepare($sql);
		// 		$stmt->execute();

		// 		$response = $stmt->fetchColumn();
		// 		return $response;

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }




		// /*///////////////////////////////////////
		// 	crear usuario
		// //////////////////////////////////////*/
		// public function add_usu($nom, $mail, $fono, $new_pass, $color, $vig) {

		// 	try{
				
		// 		$pdo = AccesoDB::getCon();

		// 		$sql_usu = "INSERT INTO `estilistas`(`nom_estilista`,`mail_estilista`,`fono_estilista`,`pass_estilista`,`vigencia_estilista`,`color_estilista`)
		// 					VALUES('".$nom."','".$mail."',".$fono.",MD5('".$new_pass."'),".$vig.",'".$color."')";


		// 		$stmt = $pdo->prepare($sql_usu);
		// 		$stmt->execute();
		

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }

		// /*///////////////////////////////////////
		// 	crear contraseña aleatoria
		// //////////////////////////////////////*/
		// public function generaPass(){
		//     //Se define una cadena de caractares. Te recomiendo que uses esta.
		//     $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		//     //Obtenemos la longitud de la cadena de caracteres
		//     $longitudCadena=strlen($cadena);
		     
		//     //Se define la variable que va a contener la contraseña
		//     $pass = "";
		//     //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
		//     $longitudPass=6;
		     
		//     //Creamos la contraseña
		//     for($i=1 ; $i<=$longitudPass ; $i++){
		//         //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
		//         $pos=rand(0,$longitudCadena-1);
		     
		//         //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
		//         $pass .= substr($cadena,$pos,1);
		//     }
		//     return $pass;
		// }



		// /*///////////////////////////////////////
		// 	validar correo nuevo
		// //////////////////////////////////////*/
		// public function validar_correo($mail) {

		// 	try{
				
				
		// 		$pdo = AccesoDB::getCon();

		// 		$sql = "SELECT mail_estilista FROM estilistas where mail_estilista = '".$mail."'";


		// 		$stmt = $pdo->prepare($sql);
		// 		$stmt->execute();

		// 		$response = $stmt->fetchColumn();
		// 		return $response;

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }


		// /*///////////////////////////////////////
		// 	Cargar usuarios
		// //////////////////////////////////////*/
		// public function cargar_usuarios() {

		// 	try{
				
				
		// 		$pdo = AccesoDB::getCon();


		// 		$sql = "SELECT * FROM estilistas";

		// 		$stmt = $pdo->prepare($sql);
		// 		$stmt->execute();

		// 		$response = $stmt->fetchAll();
		// 		return $response;

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }


		// /*///////////////////////////////////////
		// 	modificar cliente
		// //////////////////////////////////////*/
		// public function upd_cli($id,$rut, $nom, $mail, $fono,$fec_nac, $vig) {

		// 	try{
				
		// 		$pdo = AccesoDB::getCon();

		// 		$sql_cli = "update clientes
		// 					set rut_cli = '".$rut."', mail_cli = '".$mail."', fono_cli = ".$fono.", nom_cli = '".$nom."', vigencia_cli = ".$vig.", fec_nac_cli = '".$fec_nac."'
		// 					where id_cli = ".$id."";

		// 		$stmt = $pdo->prepare($sql_cli);
		// 		$stmt->execute();
		

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }


		// /*///////////////////////////////////////
		// 	Cargar cliente para modificar
		// //////////////////////////////////////*/
		// public function cargar_cli($id) {

		// 	try{
				
				
		// 		$pdo = AccesoDB::getCon();


		// 		$sql = "SELECT * FROM clientes where vigencia_cli = 1 and id_cli = ".$id."";

		// 		$stmt = $pdo->prepare($sql);
		// 		$stmt->execute();

		// 		$response = $stmt->fetchAll();
		// 		return $response;

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }


		// /*///////////////////////////////////////
		// 	Cargar clientes
		// //////////////////////////////////////*/
		// public function cargar_clientes() {

		// 	try{
				
				
		// 		$pdo = AccesoDB::getCon();


		// 		$sql = "SELECT * FROM clientes where vigencia_cli = 1 order by nom_cli";

		// 		$stmt = $pdo->prepare($sql);
		// 		$stmt->execute();

		// 		$response = $stmt->fetchAll();
		// 		return $response;

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }


		// /*///////////////////////////////////////
		// 	crear cliente
		// //////////////////////////////////////*/
		// public function add_cli($rut, $nom, $mail, $fono, $vig, $fec_nac) {

		// 	try{
				
		// 		$pdo = AccesoDB::getCon();

		// 		$sql_cli = "INSERT INTO `clientes`(`rut_cli`,`mail_cli`,`fono_cli`,`pass_cli`,`nom_cli`,`fec_nac_cli`,`vigencia_cli`)
		// 					VALUES('".$rut."','".$mail."',".$fono.",' ','".$nom."','".$fec_nac."',$vig)";
							

		// 		$stmt = $pdo->prepare($sql_cli);
		// 		$stmt->execute();
		

		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }


//////////////////////////mails paulashes

// 		/*///////////////////////////////////////
// 			enviar mail nuevo usuario
// 		//////////////////////////////////////*/
// 		public function enviar_correo_pass($nombre,$correo,$new_pass) {
// 			try{
// 					$destinatario = $correo; 
// 					$asunto =  $nombre.", te damos la bienvenida a Paulashes";

// 					$cuerpo = ' 
// 					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
// <html xmlns="http://www.w3.org/1999/xhtml">
// <head>
					
// 					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
// 					   <title>'.$nombre.'<br>te damos la bienvenida a Paulashes</title> 
// 					</head> 
// 					<body style="font: Verdana, Geneva, sans-serif">
 
// 					Sus credenciales para ingresar son las siguientes:<p>
//                     Usuario:    '.$correo.'<br /> 
//                     Contraseña: '.$new_pass.'<p>
//                     Le recomendamos cambiar su contraseña al ingresar.

// <p>			
					
// </body>
// 					</html> 
// 					'; 
// 					//para el envío en formato HTML 
// $headers = "MIME-Version: 1.0\r\n"; 
// $headers .= "Content-type: text/html; charset=UTF-8\r\n"; 

// //dirección del remitente 
// $headers .= "From: Paulashes <paulashes@paulashes.com>\r\n"; ; 

// /*//dirección de respuesta, si queremos que sea distinta que la del remitente 
// //$headers .= "Reply-To: mariano@desarrolloweb.com\r\n"; 

// //ruta del mensaje desde origen a destino 
// //$headers .= "Return-path: holahola@desarrolloweb.com\r\n"; 

// //direcciones que recibián copia 
// //$headers .= "Cc: maria@desarrolloweb.com\r\n"; 

// //direcciones que recibirán copia oculta 
// //$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; */

// mail($destinatario,$asunto,$cuerpo,$headers); 
// 		} catch (Exception $e) {
// 				throw $e;
// 		}
// 		}







// 		/*///////////////////////////////////////
// 			enviar mail cambio contraseña
// 		//////////////////////////////////////*/
// 		public function enviar_correo_updpass($correo, $new_pass, $user) {
// 			try{
				


//                     $destinatario = $correo; 
// 					$asunto = $user.", haz actualizado tu contraseña"; 
// 					$cuerpo = ' 
// 					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
// <html xmlns="http://www.w3.org/1999/xhtml">
// <head>
					
// 					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
// 					   <title>'.$user.'<br> Haz actualizado tu contraseña paulashes con exito</title> 
// 					</head> 
// 					<body style="font: Verdana, Geneva, sans-serif">
 
// 					Sus nuevas credenciales para ingresar son las siguientes:<p>
//                     Usuario:    '.$correo.'<br /> 
//                     Contraseña: '.$new_pass.'<p>

// <p>			
					
// </body>
// 					</html> 
// 					'; 
// 										//para el envío en formato HTML 
// $headers = "MIME-Version: 1.0\r\n"; 
// $headers .= "Content-type: text/html; charset=UTF-8\r\n"; 

// //dirección del remitente 
// $headers .= "From: Paulashes <paulashes@paulashes.com>\r\n"; ; 

// /*//dirección de respuesta, si queremos que sea distinta que la del remitente 
// //$headers .= "Reply-To: mariano@desarrolloweb.com\r\n"; 

// //ruta del mensaje desde origen a destino 
// //$headers .= "Return-path: holahola@desarrolloweb.com\r\n"; 

// //direcciones que recibián copia 
// //$headers .= "Cc: maria@desarrolloweb.com\r\n"; 

// //direcciones que recibirán copia oculta 
// //$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; */

// mail($destinatario,$asunto,$cuerpo,$headers); 
// 		} catch (Exception $e) {
// 				throw $e;
// 		}
// 		}





// 		/*///////////////////////////////////////
// 			Reestablecer contraseña usuario
// 		//////////////////////////////////////*/
// 		public function enviar_correo_upd_pass($correo,$new_pass) {
// 			try{
// 						$destinatario = $correo; 
// $asunto = "Paulashes - REESTABLECER CONTRASEÑA"; 
// $cuerpo = ' <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
// <html xmlns="http://www.w3.org/1999/xhtml">
// <head>
					
// 					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
// 					   <title>BlackCat - Clothing</title> 
// 					</head> 
// 					<body style="font: Verdana, Geneva, sans-serif">
 
					 
// 					<center><h3 style="background-color:#0080C0; color:#FFF" >Estimad@ recientemente hemos recibido una solicitud de reestablecimiento de contraseña para tu cuenta.</h3> </center>
// 					<p> 
// 					La nueva contraseña es:<p>
//                     <b>'.$new_pass.'</b><p>
                    

// <p>			
					
// </body>
// 					</html> 
// 					'; 

// //para el envío en formato HTML 
// $headers = "MIME-Version: 1.0\r\n"; 
// $headers .= "Content-type: text/html; charset=UTF-8\r\n"; 

// //dirección del remitente 
// $headers .= "From: Paulashes <paulashes@paulashes.com>\r\n"; ; 

// /*//dirección de respuesta, si queremos que sea distinta que la del remitente 
// //$headers .= "Reply-To: mariano@desarrolloweb.com\r\n"; 

// //ruta del mensaje desde origen a destino 
// //$headers .= "Return-path: holahola@desarrolloweb.com\r\n"; 

// //direcciones que recibián copia 
// //$headers .= "Cc: maria@desarrolloweb.com\r\n"; 

// //direcciones que recibirán copia oculta 
// //$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; */

// mail($destinatario,$asunto,$cuerpo,$headers); 
// 		} catch (Exception $e) {
// 				throw $e;
// 		}
// 		}









		////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////


	}
	?>