<?php

require_once '../db/db.php';
require_once '../db/PHPMailer/class.phpmailer.php';
require_once '../db/PHPMailer/class.smtp.php';

class Funciones 
{


        /*///////////////////////////////////////
        Validar contraseña para cambio
        //////////////////////////////////////*/
        public function old_pass($id) {

            try{
                
                
                $pdo = AccesoDB::getCon();

                $sql = "SELECT pass_usu FROM usuarios where id_usu = :id";


                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                $response = $stmt->fetchColumn();
                return $response;

            } catch (Exception $e) {
                throw $e;
            }
        }




        /*///////////////////////////////////////
        Cargar sucursal para modificar
        //////////////////////////////////////*/
        public function cargar_suc($id) {

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql = "SELECT id_suc, nom_suc, dir_suc,  fono_suc, vigencia_suc FROM sucursales where id_suc = :id";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                throw $e;
            }
        }

    /*///////////////////////////////////////
    Cargar sucursales
    //////////////////////////////////////*/
        public function cargar_sucursales() {

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql = "SELECT id_suc, nom_suc, dir_suc,  fono_suc, if(vigencia_suc=1,'Si','No') as vigencia_suc FROM sucursales order by nom_suc";

                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                throw $e;
            }
        }

    /*///////////////////////////////////////
    Validar datos sucursal
    //////////////////////////////////////*/
        public function validar_suc($nom, $dir) {

            try{
                $pdo = AccesoDB::getCon();

                $sql = "SELECT count(id_suc) FROM sucursales where nom_suc = :nom or dir_suc = :dir";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
                $stmt->bindParam(":dir", $dir, PDO::PARAM_STR);
                $stmt->execute();

                $response = $stmt->fetchColumn();
                return $response;

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../../index.html';</script>";
            }
        }

    /*///////////////////////////////////////
    Validar rut cliente
    //////////////////////////////////////*/
        public function validar_rut($rut) {

            try{
                $pdo = AccesoDB::getCon();

                $sql = "SELECT rut_cli FROM clientes where rut_cli = :rut";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":rut", $rut, PDO::PARAM_STR);
                $stmt->execute();

                $response = $stmt->fetchColumn();
                return $response;

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../../index.html';</script>";
            }
        }

        /*///////////////////////////////////////
        Cargar cliente para modificar
        //////////////////////////////////////*/
        public function cargar_cli($id) {

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql = "SELECT id_cli, nom_cli, rut_cli, mail_cli, fono_cli, vigencia_cli FROM clientes where id_cli = :id";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                throw $e;
            }
        }

    /*///////////////////////////////////////
    Cargar clientes
    //////////////////////////////////////*/
        public function cargar_clientes() {

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql = "SELECT id_cli, nom_cli, rut_cli, mail_cli, fono_cli, if(vigencia_cli=1,'Si','No') as vigencia_cli FROM clientes order by nom_cli";

                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                throw $e;
            }
        }


    /*///////////////////////////////////////
    Cargar usuario para modificar
    //////////////////////////////////////*/
        public function cargar_usu($id) {

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql = "SELECT * FROM usuarios where  id_usu = :id";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                throw $e;
            }
        }


    /*///////////////////////////////////////
    Validar correo nuevo
    //////////////////////////////////////*/
        public function validar_correo($mail) {

            try{
                $pdo = AccesoDB::getCon();

                $sql = "SELECT mail_usu FROM usuarios where mail_usu = :mail";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":mail", $mail, PDO::PARAM_STR);
                $stmt->execute();

                $response = $stmt->fetchColumn();
                return $response;

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../../index.html';</script>";
            }
        }
   


    /*///////////////////////////////////////
    Cargar grupos
    //////////////////////////////////////*/
        public function cargar_grupos() {

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql = "SELECT * FROM grupos where vigencia_grupo = 1";

                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                throw $e;
            }
        }


    /*///////////////////////////////////////
    Cargar usuarios
    //////////////////////////////////////*/
        public function cargar_usuarios() {

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql = "SELECT a.id_usu, a.nom_usu, a.mail_usu,a.color_usu, a.fono_usu, c.desc_grupo, if(a.vigencia_usu=1,'Si','No') as vigencia_usu
                        FROM usuarios a inner join grupos_usuarios b on a.id_usu = b.fk_id_usu inner join grupos c on c.id_grupo = b.fk_id_grupo";

                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                throw $e;
            }
        }



    /*///////////////////////////////////////
    Generar password
    //////////////////////////////////////*/
    public function generaPass(){
            //Se define una cadena de caractares. Te recomiendo que uses esta.
            $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            //Obtenemos la longitud de la cadena de caracteres
            $longitudCadena=strlen($cadena);
             
            //Se define la variable que va a contener la contraseña
            $pass = "";
            //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
            $longitudPass=6;
             
            //Creamos la contraseña
            for($i=1 ; $i<=$longitudPass ; $i++){
                //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
                $pos=rand(0,$longitudCadena-1);
             
                //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
                $pass .= substr($cadena,$pos,1);
            }
            return $pass;
        }










         /*//////////////////////////////////////
         //////////////////////////////////////
         ///////////////////////////////////////
            Notificaciones mail
        //////////////////////////////////////
        //////////////////////////////////////
        //////////////////////////////////////*/

        /*///////////////////////////////////////
            enviar mail nuevo usuario
        //////////////////////////////////////*/
        public function enviar_correo_pass($nombre,$mail_usu,$nueva_pass) {
            try{
                $mail = new PHPMailer(true);
                                                                // Configuramos el protocolo SMTP con autenticación

                $mail->IsSMTP();

                $mail->SMTPAuth = true;
                                                                // Configuración del servidor SMTP
                $mail->SMTPSecure = 'ssl';

                $mail->Port = 465;
                $mail->Host = 'smtp.gmail.com';
                $mail->Username   = 'pablo.vicencioc@gmail.com';
                $mail->Password = 'jklas123';




                                                                $mail->FromName = "Paulashes";

                                                                $mail->AddAddress($mail_usu); 
                    $mail->Subject = $nombre.", te damos la bienvenida a Paulashes"; 
                    $mail->MsgHTML(' 
                    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
                    
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>'.$nombre.'<br>te damos la bienvenida a Paulashes</title> 
                    </head> 
                    <body style="font: Verdana, Geneva, sans-serif">
 
                    Sus credenciales para ingresar son las siguientes:<p>
                    Usuario:    '.$mail_usu.'<br /> 
                    Contraseña: '.$nueva_pass.'<p>
                    Le recomendamos cambiar su contraseña al ingresar.

<p>         
                    
</body>
                    </html> 
                    '); 
                    $mail->CharSet = 'UTF-8';
                                        $exito = $mail->Send(); // Envía el correo.
        } catch (Exception $e) {
                throw $e;
        }
        }



        /*///////////////////////////////////////
            Actualizar contraseña usuario
        //////////////////////////////////////*/
        public function correo_upd_pass($mail_usu,$nueva_pass) {
            

            try{
                $mail = new PHPMailer(true);
                                                                // Configuramos el protocolo SMTP con autenticación

                $mail->IsSMTP();

                $mail->SMTPAuth = true;
                                                                // Configuración del servidor SMTP
                $mail->SMTPSecure = 'ssl';

                $mail->Port = 465;
                $mail->Host = 'smtp.gmail.com';
                $mail->Username   = 'pablo.vicencioc@gmail.com';
                $mail->Password = 'jklas123';




                                                                $mail->FromName = "Paulashes";

                                                                $mail->AddAddress($mail_usu); 
                    $mail->Subject = "Paulashes - CAMBIO DE CONTRASEÑA"; 
                    $mail->MsgHTML(' 
                    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
                    
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>Cambio de Contraseña</title> 
                    </head> 
                    <body style="font: Verdana, Geneva, sans-serif">
 
                    Estimad@ usuario se ha actualizado la contraseña de su cuenta Paulashes.<p>
                    Usuario:    '.$mail_usu.'<br /> 
                    Contraseña: '.$nueva_pass.'<p>

<p>         
                    
</body>
                    </html> 
                    '); 
                    $mail->CharSet = 'UTF-8';
                                        $exito = $mail->Send(); // Envía el correo.
        } catch (Exception $e) {
                throw $e;
        }
        }





}
