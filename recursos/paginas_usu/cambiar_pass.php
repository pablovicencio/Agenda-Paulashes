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
  require_once '../db/pauDAO.php';
  
  

  $dao = new pauDAO(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<center><img src="../img/banner.jpg" width="100%" height="10%"/></center> 
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
<h2>Cambiar Contraseña</h2><br /><br />
<hr><br /><br />
<form role="form" action="../controles/control_actContrasena.php" method="post" id="new_inc" name="new_inc">
      <div id="new_inc">
            
        <label for="pass" class="inc"> Contraseña Actual </label>
        <input type="password" name="pass" id="pass" class="form-control"  maxlength="6" required/> <br /><br />
                
                <label for="new_pass" class="inc"> Nueva Contraseña (6 caracteres)</label>
        <input type="password" name="nueva_pass" id="nueva_pass" class="form-control" maxlength="6"  required/><br /><br />
                
                <label for="new_pass1" class="inc"> Confirmar Nueva Contraseña (6 caracteres)</label>
        <input type="password" name="nueva_pass1" id="nueva_pass1" class="form-control"  maxlength="6" required/><br /><br />
                 
                      
  <center><input type="submit" value="Cambiar Contraseña" id="btnAc" /></center> 
  </form>


</div>
</body>

</html>