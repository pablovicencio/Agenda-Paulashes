<?php
require_once '../db/db.php';

/*/////////////////////////////
Clase Sucursal
////////////////////////////*/

class SucursalDAO 
{
    private $id;
    private $nombre;
    private $direccion;
    private $fono;
    private $vigencia; 

    public function __construct($id=null,$nombre=null, $direccion=null, $fono=null, $vigencia=null) {
        $this->id  = $id;
        $this->nombre  = $nombre;
        $this->direccion  = $direccion;
        $this->fono  = $fono;
        $this->vigencia  = $vigencia;
}

public function getSucursal() {
    return $this->id;
 }

   	/*///////////////////////////////////////
    Crear Sucursal
    //////////////////////////////////////*/
    public function crear_sucursal() {


        try{
             
                $pdo = AccesoDB::getCon();

                $sql_crear_suc = "INSERT INTO `sucursales`(`nom_suc`,`dir_suc`,`fono_suc`,`vigencia_suc`)
                            VALUES(:nom,:dir,:fono,:vig)";
                

                $stmt = $pdo->prepare($sql_crear_suc);
                $stmt->bindParam(":nom", $this->nombre, PDO::PARAM_STR);
                $stmt->bindParam(":dir", $this->direccion, PDO::PARAM_STR);
                $stmt->bindParam(":fono", $this->fono, PDO::PARAM_INT);
                $stmt->bindParam(":vig", $this->vigencia, PDO::PARAM_BOOL);
                $stmt->execute();

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/sucursales.php';</script>"; 
            }
    }


    /*///////////////////////////////////////
    Modificar Sucursal
    //////////////////////////////////////*/

    public function modificar_suc()  {


        try{
                $pdo = AccesoDB::getCon();

                $sql_mod_suc = "update sucursales
                            set nom_suc = :nom, fono_suc = :fono, vigencia_suc = :vig where id_suc =:id ";


                $stmt = $pdo->prepare($sql_mod_suc);
                $stmt->bindParam(":nom", $this->nombre, PDO::PARAM_STR);
                $stmt->bindParam(":fono", $this->fono, PDO::PARAM_INT);
                $stmt->bindParam(":vig", $this->vigencia, PDO::PARAM_BOOL);
                $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
                $stmt->execute();
                        
            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/sucursales.php';</script>"; 
            }
    } 


}
?>