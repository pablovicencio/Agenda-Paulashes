<?php
require_once '../db/db.php';    


/*/////////////////////////////
Clase Cita
////////////////////////////*/

class CitaDAO 
{
    private $id;
    private $fecha_cita;
    private $hora_cita;
    private $hora_termino;
    private $fec_reg;
    private $estado_cita; 

    public function __construct($id=null,$fecha=null, $hora=null, $hora_termino=null,$fec_reg=null, $estado=null) {
        $this->id  = $id;
        $this->fecha_cita  = $fecha;
        $this->hora  = $hora;
        $this->hora_termino  = $hora_termino;
        $this->fec_reg  = $fec_reg;
        $this->estado_cita  = $estado;
}

public function getCita() {
    return $this->id;
 }

        /*///////////////////////////////////////
        Agendar Cita
        //////////////////////////////////////*/
        public function agendar_cita($id_suc,$id_cli,$id_estilista) {


            try{

                
                $pdo = AccesoDB::getCon();

                $sql_crear_cita = "INSERT INTO `citas`(`fec_cita`,`hora_cita`,`hora_ter_cita`,`fec_reg_cita`,`fk_suc_cita`,`obs_age`,`estado_cita`,`fk_id_cli`,`fk_id_estilista`)
                            VALUES(:fec_cita,:hora_cita,:hora_ter,:fec_reg,:suc,'',:estado,:id_cli,:id_est)";


                $stmt = $pdo->prepare($sql_crear_cita);
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


        /*///////////////////////////////////////
        Listar Citas
        //////////////////////////////////////*/
        public function listar_citas($fec) {

            $this->fecha_cita = $fec; 

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql_lista = "select a.hora_cita, a.hora_ter_cita, b.nom_cli, c.nom_usu, d.nom_suc ubicacion, e.desc_item estado,c.color_usu
                        from citas a, clientes b, usuarios c, sucursales d, parametros e
                        where a.fk_id_cli = b.id_cli and a.fk_id_estilista = c.id_usu and a.fk_suc_cita = d.id_suc and d.vigencia_suc = 1
                        and a.estado_cita = e.cod_item and e.cod_grupo = 1 and e.vigencia = 1 
                        and a.estado_cita <> 4 and  a.fec_cita = :fecha order by a.hora_cita,a.hora_ter_cita";

                $stmt = $pdo->prepare($sql_lista);
                $stmt->bindParam("fecha", $this->fecha_cita, PDO::PARAM_STR);
                $stmt->execute();

                $response = $stmt->fetchAll();
                return $response;

            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/agenda.php';</script>"; 
            }
        }


        /*///////////////////////////////////////
        Confirmar Cita
        //////////////////////////////////////*/
        public function confirmar_cita() {
 

            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql_con_cit = "update citas
                                set estado_cita = 2 
                                where id_cita = :id_cita";

                $stmt = $pdo->prepare($sql_con_cit);
                $stmt->bindParam("id_cita", $this->id, PDO::PARAM_INT);
                $stmt->execute();


            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/agenda.php';</script>"; 
            }
        }



        /*///////////////////////////////////////
        Anular Cita
        //////////////////////////////////////*/
        public function anular_cita() {


            try{
                
                
                $pdo = AccesoDB::getCon();


                $sql_anu_cit = "update citas
                                set estado_cita = 4 
                                where id_cita = :id_cita";

                $stmt = $pdo->prepare($sql_anu_cit);
                $stmt->bindParam("id_cita", $this->id, PDO::PARAM_INT);
                $stmt->execute();

            
            } catch (Exception $e) {
                echo"<script type=\"text/javascript\">alert('Error, comuniquese con el administrador".  $e->getMessage()." '); window.location='../paginas_usu/agenda.php';</script>"; 
            }
        }



       	


}
?>