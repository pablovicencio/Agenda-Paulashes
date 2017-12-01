<?php 
session_start(); 
if( isset($_SESSION['id_usu']) ){
    //Si la sesión esta seteada no hace nada
    $us = $_SESSION['id_usu'];
  }
  else{
    //Si no lo redirige a la pagina index para que inicie la sesion 
    header("location: ../../index.html");
  }  
  require_once '../clases/Funciones.php';
  
  

  $fun = new Funciones(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Paulashes &#8211; Lash, Beauty &amp; Boutique</title>
<link href="../estilo.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>

function validar(f){
f.btnAc.value="Actualizando Cita";
f.btnAc.disabled=true;
return true}

$(window).scroll(function()
            {
                if ($(this).scrollTop() > 90){
           $('#menu').addClass("fixed").fadeIn();         $('#menu').css('width','100%')
$('#logo').css('visibility','visible')
        }
                else {
          $('#menu').removeClass("fixed");
          $('#logo').css('visibility','hidden')
          
        }
            });


</script>
</head>

<body>
<div id="header">
<center><img src="../img/banner.jpg" width="1180" height="148"/></center> 
</div><br>


<div id="menu">
<ul >
  <li><a href="../controles/logout.php" onclick="return confirm('¿Deseas finalizar sesion?');">Cerrar Sesión</a></li>
  <?php if ($_SESSION['super'] == 1) {echo ('<li><a  href="sucursales.php">Sucursales</a></li>');}  ?>
  <?php if ($_SESSION['super'] == 1) {echo ('<li><a  href="usuarios.php">Usuarios</a></li>');}  ?>
  <li><a  href="actualizar_datos.php">Actualizar Mis Datos</a></li>
  <li><a  href="clientes.php">Clientes</a></li>
  <li><a href="agenda.php">Agenda</a></li>
  <li><a href="index_usuario.php">Mi Agenda</a></li>
  <img src="../img/icon.jpg"  name="logo" width="60" height="31" class="logo" id="logo" style="visibility:hidden">
</ul>
</div>
<br> <br>

<label for="usuario"> Usuario </label>
<input name="usu" type="text" id="usu" value="<?php echo $_SESSION['nombre']?>" readonly size="15" /><br> <br>
<div class="contenido1" >


<label>
<h2>Atención - Paulashes</h2>
</label><br /><br />
<hr>

<h2>Cliente <?php echo $_GET{'dato2'}; ?></h2>
<table border="1" class="grid">
  <caption class="texto" ><b>Atenciones anteriores</b></caption>
<tr> 
    <td style="background:#74ccd1 ; color:black" >FECHA</td> 
    <td style="background:#74ccd1 ; color:black">ESTILISTA</td>
    <td style="background:#74ccd1 ; color:black">UBICACIÓN</td> 
    <td style="background:#74ccd1 ; color:black">OBSERVACIÓN</td>
</tr> 
<?php
              $id_cita = $_GET{'dato'};
              $re = $fun->cargar_atenciones($id_cita);
              foreach($re as $row){
                  $fecha = date('d-m-Y',strtotime($row['fec_cita']));
                
                echo ('<td>'.$fecha.'</td>');
                echo ('<td  bgcolor="'.$row['color_usu'].'">'.$row['nom_usu'].'</td>');
                echo ('<td>'.$row['ubi'].'</td>');
                echo ('<td>'.$row['obs_age'].'</td></tr>');
    
              }
?>
</table><br><br>

<?php
              $re1 = $fun->cargar_datos_cita($id_cita);
              foreach($re1 as $row1){
              }
?>


<hr>
<label>
<h2>Actualizar Cita</h2>
</label><br /><br />
<form role="form" action="../controles/control_actCita.php" method="post" id="new_cli" name="new_cli" onsubmit="return validar(this)">
<blockquote>
              <input type="hidden"  name="id" id="id" class="form-control" value="<?php echo $id_cita; ?>" required readonly/>
              
            <label for="fecha" >Fecha Cita <?php echo date('d-m-Y',strtotime($row1['fec_cita'])); ?> </label><br /><br />
                
                <blockquote>
        <label for="rut" > Rut Cliente </label><br />
        <input type="text" name="rut_cli" id="rut_cli" class="form-control" value="<?php echo $row1['rut_cli']; ?>" required readonly />
                </blockquote> <br /><br />

                <blockquote>
        <label for="fono" > Fono </label><br />
        <input type="text" name="fono_cli" id="fono_cli" class="form-control" value="<?php echo $row1['fono_cli']; ?>" required readonly />
                </blockquote> <br /><br />

                
                <blockquote>
        <label for="mail" > Mail Cliente </label><br />
        <input type="text" name="mail_cli" id="mail_cli" class="form-control" value="<?php echo $row1['mail_cli']; ?>" size="30" required readonly />
                </blockquote> <br /><br />

                <blockquote>
        <label for="hora_cita" > Hora Cita </label><br />
        <input type="text" name="hora_cita" id="hora_cita" class="form-control" value="<?php echo $row1['hora_cita']; ?>"  required readonly />
                </blockquote> <br /><br />

                <blockquote>
        <label for="hora_ter" > Hora Termino aproximado </label><br />
        <input type="text" name="hora_ter" id="hora_ter" class="form-control" value="<?php echo $row1['hora_ter_cita']; ?>"  required readonly />
                </blockquote> <br /><br />

                <blockquote>
        <label for="est" > Estado </label><br />
        <input type="text" name="est" id="est" class="form-control" value="<?php echo $row1['est']; ?>"  required readonly />
                </blockquote> <br /><br />
        

        <?php
            switch ($row1['estado_cita']) {
              case '1':
                echo '<center><input type="submit" value="Confirmar Cita" id="conf" name="conf" onclick=\'return confirm("¿Desea Confirmar la cita?");\'  /> 
                      <input type="submit" value="Anular Cita" id="anu" name="anu" onclick=\'return confirm("¿Deseas Anular la cita?");\'/></center> ';
                break;
              case '2':
                echo '<blockquote>
                      <label for="obs" > Observación </label><br />
                      <textarea name="obs" cols="70" rows="5" id="obs" maxlength="500" required></textarea  />
                      </blockquote> <br /><br />
                      <center><input type="submit" value="Documentar Cita" id="doc" name="doc"  onclick=\'return confirm("¿Desea Documentar la atencion?");\'/> </center>';
                break;
              case '3':
                echo '<blockquote>
                      <label for="obs" > Observación </label><br />
                      <textarea name="obs" cols="70" rows="5" id="obs" maxlength="500"  required readonly>'.$row1['obs_age'].'</textarea  />
                      </blockquote> <br /><br />
                      Cita Documentada';
                break;
              case '4':
                echo 'Cita Anulada';
                break;
              
            }
        ?>
                
</form>
<br><br>

</div>
</body>
</html>