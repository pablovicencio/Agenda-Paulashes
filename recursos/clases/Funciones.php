<?php

require_once '../db/db.php';
require_once '../db/PHPMailer/class.phpmailer.php';
require_once '../db/PHPMailer/class.smtp.php';

class Funciones 
{

        /*///////////////////////////////////////
        Cargar Datos cita
        //////////////////////////////////////*/
        public function cargar_datos_cita($id_cita) {

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql = "select a.fec_cita, b.rut_cli, b.fono_cli, b.mail_cli,a.hora_cita,a.hora_ter_cita, ubi.nom_suc ubi, est.desc_item est , a.estado_cita, a.obs_age
                        from citas a, clientes b, sucursales ubi, parametros est
                        where  a.fk_id_cli = b.id_cli
                        and a.fk_suc_cita = ubi.id_suc and ubi.vigencia_suc = 1
                        and a.estado_cita = est.cod_item and est.cod_grupo = 1
                        and id_cita = :id_cita";
                

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_cita", $id_cita, PDO::PARAM_INT);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/index_usuario.php';</script>";
            }
        }


        /*///////////////////////////////////////
        Cargar atenciones cliente
        //////////////////////////////////////*/
        public function cargar_atenciones($id_cita) {

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql = "select b.nom_cli, a.fec_cita, e.nom_usu, ubi.nom_suc ubi, a.obs_age, e.color_usu
                        from citas a, clientes b, sucursales ubi, usuarios e
                        where  a.fk_id_cli = b.id_cli
                        and a.estado_cita =  3
                        and a.fk_suc_cita = ubi.id_suc and ubi.vigencia_suc = 1
                        and a.fk_id_estilista = e.id_usu and b.id_cli = (select id_cli from citas where id_cita = :id_cita)
                        order by a.fec_cita;";
                

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id_cita", $id_cita, PDO::PARAM_INT);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                 echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/index_usuario.php';</script>";
            }
        }


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
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/index_usuario.php';</script>";
            }
        }


        /*///////////////////////////////////////
        Cargar citas
        //////////////////////////////////////*/
        public function cargar_citas($us, $fecha) {

            try{
                
                
                $pdo = AccesoDB::getCon();

                switch ($us) {
                    case 0:
                       $sql = "select a.id_cita,b.nom_cli,b.fono_cli, a.hora_cita, a.hora_ter_cita,  c.nom_usu, d.nom_suc ubicacion, e.desc_item estado,c.color_usu
                            from citas a, clientes b, usuarios c, sucursales d, parametros e
                            where a.fk_id_cli = b.id_cli  and a.fk_suc_cita = d.id_suc and d.vigencia_suc = 1
                            and a.estado_cita = e.cod_item and e.cod_grupo = 1 and e.vigencia = 1  and a.fk_id_estilista = c.id_usu
                            and a.fec_cita = :fecha order by hora_cita;";

                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
                        break;

                    default:
                        $sql = "select a.id_cita,b.nom_cli,b.fono_cli, a.hora_cita, a.hora_ter_cita,  c.nom_usu, d.nom_suc ubicacion, e.desc_item estado,c.color_usu
                            from citas a, clientes b, usuarios c, sucursales d, parametros e
                            where a.fk_id_cli = b.id_cli  and a.fk_suc_cita = d.id_suc and d.vigencia_suc = 1
                            and a.estado_cita = e.cod_item and e.cod_grupo = 1 and e.vigencia = 1  and a.fk_id_estilista = c.id_usu
                            and a.fk_id_estilista = :usu and a.fec_cita = :fecha order by hora_cita;";
                            
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(":usu", $us, PDO::PARAM_INT);
                            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
                        break;
                }
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/index_usuario.php';</script>";
            }
        }


        /*///////////////////////////////////////
        Control Choque de citas
        //////////////////////////////////////*/
        public function control_cita($fec_cita,$hora_cita,$hora_ter,$id_cli,$id_estilista) {

            try{

                $hora_cita = $hora_cita.":00";
                $hora_ter = $hora_ter.":00";
                
                $pdo = AccesoDB::getCon();


                $sql = "select id_cita from citas where fec_cita = :fec_cita 
                        and (cast(hora_cita as time) >= :hora_cita and cast(hora_cita as time) < :hora_ter)
                        and (fk_id_cli = :id_cli or fk_id_estilista = :id_estilista) and estado_cita <> 4
                        union all
                        select id_cita from citas where fec_cita = :fec_cita1
                        and (cast(hora_ter_cita as time) > :hora_cita1 and cast(hora_ter_cita as time) <= :hora_ter1)
                        and (fk_id_cli = :id_cli1 or fk_id_estilista = :id_estilista1) and estado_cita <> 4";
                

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":fec_cita", $fec_cita, PDO::PARAM_STR);
                $stmt->bindParam(":hora_cita", $hora_cita, PDO::PARAM_STR);
                $stmt->bindParam(":hora_ter", $hora_ter, PDO::PARAM_STR);
                $stmt->bindParam(":id_cli", $id_cli, PDO::PARAM_INT);
                $stmt->bindParam(":id_estilista", $id_estilista, PDO::PARAM_INT);

                $stmt->bindParam(":fec_cita1", $fec_cita, PDO::PARAM_STR);
                $stmt->bindParam(":hora_cita1", $hora_cita, PDO::PARAM_STR);
                $stmt->bindParam(":hora_ter1", $hora_ter, PDO::PARAM_STR);
                $stmt->bindParam(":id_cli1", $id_cli, PDO::PARAM_INT);
                $stmt->bindParam(":id_estilista1", $id_estilista, PDO::PARAM_INT);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/agenda.php';</script>";
            }
        }



    /*///////////////////////////////////////
    Cargar Estilistas
    //////////////////////////////////////*/
        public function cargar_estilistas() {

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql = "SELECT a.id_usu, a.nom_usu
                        FROM usuarios a inner join grupos_usuarios b on a.id_usu = b.fk_id_usu inner join grupos c on c.id_grupo = b.fk_id_grupo where c.id_grupo = 1 and a.vigencia_usu = 1";

                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/index_usuario.php';</script>";
            }
        }





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
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/cambiar_pass.php';</script>";
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
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/sucursales.php';</script>";
            }
        }

    /*///////////////////////////////////////
    Cargar sucursales
    //////////////////////////////////////*/
        public function cargar_sucursales($i) {

            try{
                
                
                $pdo = AccesoDB::getCon();

                switch ($i) {
                    case 0:
                        $sql = "SELECT id_suc, nom_suc, dir_suc,  fono_suc, if(vigencia_suc=1,'Si','No') as vigencia_suc FROM sucursales order by nom_suc";
                        break;

                    case 1:
                        $sql = "SELECT id_suc, nom_suc FROM sucursales where vigencia_suc = 1 order by nom_suc";
                        break;
                    
                    
                }


                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/sucursales.php';</script>";
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
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/sucursales.php';</script>";
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
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/clientes.php';</script>";
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
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/clientes.php;</script>";
            }
        }

    /*///////////////////////////////////////
    Cargar clientes
    //////////////////////////////////////*/
        public function cargar_clientes($i) {

            try{
                
                
                $pdo = AccesoDB::getCon();


                switch ($i) {
                    case 0:
                        $sql = "SELECT id_cli, nom_cli, rut_cli, mail_cli, fono_cli, if(vigencia_cli=1,'Si','No') as vigencia_cli FROM clientes order by nom_cli";
                        break;

                    case 1:
                        $sql = "SELECT id_cli, nom_cli FROM clientes where vigencia_cli = 1 order by nom_cli";
                        break;
                    
                    
                }


                

                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/clientes.php';</script>";
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
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/usuarios.php';</script>";
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
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/usuarios.php';</script>";
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
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/index_usuario.php';</script>";
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
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/usuarios.php';</script>";
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
