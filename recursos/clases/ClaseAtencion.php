<?php
require_once '../db/db.php';    


/*/////////////////////////////
Clase Atencion
////////////////////////////*/

class AtencionDAO 
{
    private $fecha;
    private $obs;

    public function __construct($fecha=null,$obs=null) {
        $this->fecha  = $fecha;
        $this->obs = $obs;
}

        /*///////////////////////////////////////
        Atender Cita
        //////////////////////////////////////*/
        public function atender_cita($id_cita, $id_usu) {


            try{

                
                $pdo = AccesoDB::getCon();

                $sql_ate_cita = "update citas
                                set estado_cita = 3 , obs_age = :obs
                                where id_cita = :id_cita";


                $stmt = $pdo->prepare($sql_ate_cita);
                $stmt->bindParam("fec_cita", $this->fecha_cita, PDO::PARAM_STR);
                $stmt->bindParam("hora_cita", $this->hora, PDO::PARAM_STR);
                $stmt->bindParam("hora_ter", $this->hora_termino, PDO::PARAM_STR);
                $stmt->bindParam("fec_reg", $this->fec_reg, PDO::PARAM_STR);
                $stmt->bindParam("suc",$id_suc , PDO::PARAM_INT);
                $stmt->bindParam("estado", $this->estado_cita, PDO::PARAM_INT);
                $stmt->bindParam("id_cli", $id_cli, PDO::PARAM_INT);
                $stmt->bindParam("id_est", $id_estilista, PDO::PARAM_INT);
                $stmt->execute();
        

            } catch (Exception $e) {
                 echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/agenda.php';</script>"; 
            }
        }


}
?>

