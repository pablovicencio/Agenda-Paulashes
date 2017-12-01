<?php 
session_start();
if( isset($_SESSION['id_usu']) ){
    //Si la sesión esta seteada no hace nada
    $us = $_SESSION['id_usu'];
    header("Refresh: 180");
    if(isset($_GET{'fec'})){
                    $dia = strtotime($_GET{'fec'});
                    
                }else{
                    $dia = time();
                }
              
    
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
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">

<title>Paulashes &#8211; Lash, Beauty &amp; Boutique</title>
<link href="../estilo.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
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
</div><br> <br>

<label for="usuario"> Usuario </label>
<input name="usu" type="text" id="usu" value="<?php echo $_SESSION['nombre']?>" readonly size="15" /><br> <br>
<center><div class="contenido1" >
<label>
<h2>Mi Agenda - Paulashes</h2>
</label><br /><br />
<hr><br><br>

</left><form action="index_usuario.php" method="get">
Fecha a consultar: <input type="date" name="fec">
<input type="submit" value="Buscar">
</form><br><br>

<table border="1" class="grid">
  <caption class="texto" ><b>Horas del día <?php echo date('d-m-Y', $dia) ; ?></b></caption>
<tr> 
    <td style="background:#74ccd1 ; color:black"></td> 
    <td style="background:#74ccd1 ; color:black">CLIENTE</td>
    <td style="background:#74ccd1 ; color:black">TELEFONO</td>
    <td style="background:#74ccd1 ; color:black">HORA CITA</td> 
    <td style="background:#74ccd1 ; color:black">HORA TERMINO</td>  
    <td style="background:#74ccd1 ; color:black">ESTADO</td>
    <td style="background:#74ccd1 ; color:black">UBICACION</td> 
    <td style="background:#74ccd1 ; color:black">ESTILISTA</td> 
</tr> 
<?php
              
              switch ($_SESSION['perfil']) {
                case 1:
                  $usuario = $us;
                  break;

                case 2:
                  $usuario = 0;
                  break;
                
                
              }
              
              $re = $fun->cargar_citas($usuario, date('Y-m-d', $dia));

              
              foreach($re as $row){

                $id_cita = $row['id_cita'];
                $link = '<a href="atencion.php?dato='.$id_cita.'&dato2='.$row['nom_cli'].'" class="l">';
                $estado = $row['estado'];
                $ce = $fun->colorestado($estado);
                
                echo ('<td ><center>'.$link.'Actualizar</a></center></td>');
                echo ('<td>'.$row['nom_cli'].'</td>');
                echo ('<td>'.$row['fono_cli'].'</td>');
                echo ('<td>'.$row['hora_cita'].'</td>');
                echo ('<td>'.$row['hora_ter_cita'].'</td>');
                echo ($ce);
                echo ('<td>'.$row['ubicacion'].'</td>');
                echo ('<td bgcolor='.$row['color_usu'].'>'.$row['nom_usu'].'</td></tr>');
    
              }
?>
</table><br><br>


</div></center>

</body>
</html>