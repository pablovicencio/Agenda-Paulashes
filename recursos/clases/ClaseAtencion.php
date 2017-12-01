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

            $est_ant = 2;
            $est_new = 3;

            try{

                
                $pdo = AccesoDB::getCon();

                $sql_ate_cita = "update citas
                                set estado_cita = 3 , obs_age = :obs
                                where id_cita = :id_cita";


                $stmt = $pdo->prepare($sql_ate_cita);
                $stmt->bindParam("obs", $this->obs, PDO::PARAM_STR);
                $stmt->bindParam("id_cita", $id_cita, PDO::PARAM_INT);
                $stmt->execute();

                $sql_log = "INSERT INTO `log_citas`
                                (`estado_ant_log`,`estado_new_log`,`fec_log`,`id_usu_log`,`fk_id_cita`)
                                VALUES(:est_ant, :est_new, :fec, :id_usu, :id_cita)";

                $stmt = $pdo->prepare($sql_log);
                $stmt->bindParam("est_ant", $est_ant, PDO::PARAM_INT);
                $stmt->bindParam("est_new", $est_new, PDO::PARAM_INT);
                $stmt->bindParam("fec", $this->fecha, PDO::PARAM_STR);
                $stmt->bindParam("id_usu", $id_usu, PDO::PARAM_INT);
                $stmt->bindParam("id_cita", $id_cita, PDO::PARAM_INT);
                $stmt->execute();
        

            } catch (Exception $e) {
                 echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/agenda.php';</script>"; 
            }
        }


}
?>

