<?php
require_once '../db/db.php';
include_once 'ClasePersona.php';

/*/////////////////////////////
Clase abstracta Persona
////////////////////////////*/

abstract class PersonaDAO
{
    protected $id;
    protected $nombre; 
    protected $rut; 
    protected $mail; 
    protected $fono; 

}

/*/////////////////////////////
Clase Usuario
////////////////////////////*/

class UsuarioDAO extends PersonaDAO
{
    private $contraseña;
    private $vigencia;
    private $super_usu;
    private $color;
    private $tipo; 

    public function __construct($id=null,$nombre=null, $mail=null, $fono=null, $contraseña=null,$vigencia=null,$super_usu=null, $color=null, $tipo=null) {
        $this->id  = $id;
        $this->nombre  = $nombre;
        $this->mail  = $mail;
        $this->fono  = $fono;
        $this->contraseña  = $contraseña;
        $this->vigencia  = $vigencia;
        $this->super_usu  = $super_usu;
        $this->color  = $color;
        $this->tipo  = $tipo;
    }

    public function getUsuario() {
    return $this->id;
    }


    /*///////////////////////////////////////
    Crear Usuario
    //////////////////////////////////////*/
    public function crear_usuario() {


        try{
             
                $pdo = AccesoDB::getCon();

                $sql_crear_usu = "INSERT INTO `usuarios`(`nom_usu`,`mail_usu`,`fono_usu`,`pass_usu`,`vigencia_usu`,`super_usu`,`color_usu`)
                            VALUES(:nom,:mail,:fono,:pass,:vig,:super,:color)";


                $stmt = $pdo->prepare($sql_crear_usu);
                $stmt->bindParam(":nom", $this->nombre, PDO::PARAM_STR);
                $stmt->bindParam(":mail", $this->mail, PDO::PARAM_STR);
                $stmt->bindParam(":fono", $this->fono, PDO::PARAM_INT);
                $stmt->bindParam(":pass", $this->contraseña, PDO::PARAM_STR);
                $stmt->bindParam(":vig", $this->vigencia, PDO::PARAM_BOOL);
                $stmt->bindParam(":super", $this->super_usu, PDO::PARAM_BOOL);
                $stmt->bindParam(":color", $this->color, PDO::PARAM_STR);
                $stmt->execute();


                $sql_usu = "select id_usu from usuarios where mail_usu = :mail";

                $stmt = $pdo->prepare($sql_usu);
                $stmt->bindParam(':mail', $this->mail, PDO::PARAM_STR);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql_tipo_usu = "INSERT INTO `grupos_usuarios`(`fk_id_grupo`,`fk_id_usu`)
                            VALUES(:grupo,:usu)";


                $stmt = $pdo->prepare($sql_tipo_usu);
                $stmt->bindParam(":grupo", $this->tipo, PDO::PARAM_INT);
                $stmt->bindParam(":usu", $row['id_usu'], PDO::PARAM_INT);
                $stmt->execute();
        

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../../index.html';</script>"; 
            }
    }


    /*///////////////////////////////////////
    Modificar Usuario
    //////////////////////////////////////*/

    public function modificar_usuario()  {

        

        try{
                $pdo = AccesoDB::getCon();

                $sql_mod_usu = "update usuarios
                            set  mail_usu = :mail, fono_usu = :fono, nom_usu = :nom, vigencia_usu = :vig, color_usu = :color, super_usu = :super
                            where id_usu =:id ";


                $stmt = $pdo->prepare($sql_mod_usu);
                $stmt->bindParam(":mail", $this->mail, PDO::PARAM_STR);
                $stmt->bindParam(":fono", $this->fono, PDO::PARAM_INT);
                $stmt->bindParam(":nom", $this->nombre, PDO::PARAM_STR);
                $stmt->bindParam(":vig", $this->vigencia, PDO::PARAM_BOOL);
                $stmt->bindParam(":color", $this->color, PDO::PARAM_STR);
                $stmt->bindParam(":super", $this->super_usu, PDO::PARAM_BOOL);
                $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
                $stmt->execute();

                $sql_mod_tipo_usu = "update grupos_usuarios
                            set  fk_id_grupo = :tipo
                            where fk_id_usu = :fk_usu";



                $stmt = $pdo->prepare($sql_mod_tipo_usu);
                $stmt->bindParam(":tipo", $this->tipo, PDO::PARAM_INT);
                $stmt->bindParam(":fk_usu", $id, PDO::PARAM_INT);
                $stmt->execute();
        

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../../index.html';</script>"; 
            }
    }


    /*///////////////////////////////////////
    Actualizar contraseña usuario
    //////////////////////////////////////*/
    public static function actualizar_contraseña($mail,$nueva_pass){

        try{


                
                $pdo = AccesoDB::getCon();

                $sql_act_pass = "UPDATE usuarios
                            SET pass_usu = :pass WHERE mail_usu = :mail";

                $stmt = $pdo->prepare($sql_act_pass);
                $stmt->bindParam(":mail", $mail, PDO::PARAM_STR);
                $stmt->bindParam(":pass", $nueva_pass, PDO::PARAM_STR);
                $stmt->execute();
        

        } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../../index.html';</script>"; 
        }
    }


    /*///////////////////////////////////////
    Login usuario
    //////////////////////////////////////*/
    public static function login($mail,$pass){

        try{

                
                $pdo = AccesoDB::getCon();

                $sql_login = "select a.id_usu id,a.nom_usu nom,a.mail_usu mail,b.fk_id_grupo perfil, a.pass_usu pass, a.super_usu super from usuarios a, grupos_usuarios b where a.mail_usu = :mail and a.vigencia_usu = 1 and a.id_usu = b.fk_id_usu";

                $stmt = $pdo->prepare($sql_login);
                $stmt->bindParam(":mail", $mail, PDO::PARAM_STR);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
             
                 if ($pass == $row["pass"]) { 
                        session_start();
                        $_SESSION['id_usu'] = $row['id'];
                        $_SESSION['nombre'] = $row['nom'];
                        $_SESSION['correo'] = $row['mail'];
                        $_SESSION['perfil'] = $row['perfil'];
                        $_SESSION['super'] = $row['super'];
                        $_SESSION['start'] = time();
                        $_SESSION['expire'] = $_SESSION['start'] + (5 * 60);
                        
                        echo"<script type=\"text/javascript\">      window.location='../paginas_usu/index_usuario.php';</script>"; 
                        }else { 
                           echo"<script type=\"text/javascript\">alert('Error, favor verifique sus datos e intente nuevamente o comuniquese con un super usuario para revisar su vigencia.');window.location='../../index.html';        </script>"; 
                         }

        

        } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../../index.html';</script>"; 
        }
    }


}








/*/////////////////////////////
Clase Cliente
////////////////////////////*/

class ClienteDAO extends PersonaDAO
{
    private $contraseña  ;
    private $vigencia ;

    public function __construct($id=null,$nombre=null,$rut=null, $mail=null, $fono=null, $vigencia=null) {
        $this->id  = $id;
        $this->nombre  = $nombre;
        $this->rut  = $rut;
        $this->mail  = $mail;
        $this->fono  = $fono;
        $this->vigencia  = $vigencia;
    }

    public function getCliente() {
    return $this->id;
    }

   /*///////////////////////////////////////
    Crear Cliente
    //////////////////////////////////////*/
    public function crear_cliente() {


        try{
             
                $pdo = AccesoDB::getCon();

                $sql_crear_cli = "INSERT INTO `clientes`(`nom_cli`,`rut_cli`,`mail_cli`,`fono_cli`,`vigencia_cli`)
                            VALUES(:nom,:rut,:mail,:fono,:vig)";


                $stmt = $pdo->prepare($sql_crear_cli);
                $stmt->bindParam(":nom", $this->nombre, PDO::PARAM_STR);
                $stmt->bindParam(":rut", $this->rut, PDO::PARAM_STR);
                $stmt->bindParam(":mail", $this->mail, PDO::PARAM_STR);
                $stmt->bindParam(":fono", $this->fono, PDO::PARAM_INT);
                $stmt->bindParam(":vig", $this->vigencia, PDO::PARAM_BOOL);
                $stmt->execute();

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/clientes.php';</script>"; 
            }
    } 

    /*///////////////////////////////////////
    Modificar Cliente
    //////////////////////////////////////*/

    public function modificar_cli()  {


        try{
                $pdo = AccesoDB::getCon();

                $sql_mod_cli = "update clientes
                            set  mail_cli = :mail, rut_cli = :rut, fono_cli = :fono, nom_cli = :nom, vigencia_cli = :vig where id_cli =:id ";


                $stmt = $pdo->prepare($sql_mod_cli);
                $stmt->bindParam(":mail", $this->mail, PDO::PARAM_STR);
                $stmt->bindParam(":rut", $this->rut, PDO::PARAM_STR);
                $stmt->bindParam(":fono", $this->fono, PDO::PARAM_INT);
                $stmt->bindParam(":nom", $this->nombre, PDO::PARAM_STR);
                $stmt->bindParam(":vig", $this->vigencia, PDO::PARAM_BOOL);
                $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
                $stmt->execute();
                        
            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/clientes.php';</script>"; 
            }
    }




}




