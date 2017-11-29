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
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">

<title>Paulashes &#8211; Lash, Beauty &amp; Boutique</title>
<link href="../estilo.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>

function validar(f){
f.btnAc.value="Modificando Cliente";
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
  <?php if ($us == 1) {echo ('<li><a  href="sucursales.php">Sucursales</a></li>');}  ?>
  <?php if ($us == 1) {echo ('<li><a  href="usuarios.php">Usuarios</a></li>');}  ?>
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


<?php
             $id = $_GET{'dato'};
              $re = $fun->cargar_cli($id);
              foreach($re as $row){    
              }
              
?>



<label>
<h2>Modificar Cliente Paulashes</h2>
</label><br /><br />
<hr><br />
<form role="form" action="../controles/control_modCliente.php" method="post" id="upd_cli" name="upd_cli" onsubmit="return validar(this)">
<blockquote>

                <blockquote>
        <label for="rut" > Id Cliente </label><br />
        <input type="text" name="id_cli" id="id_cli" class="form-control" style="border-width:0;" value="<?php echo $row['id_cli']; ?>" maxlength="10"  required readonly />
                </blockquote> <br /><br />
                
                <blockquote>
        <label for="rut" > Rut Cliente </label><br />
        <input type="text" name="rut_cli" id="rut_cli" class="form-control" value="<?php echo $row['rut_cli']; ?>" maxlength="10"  required  />
                </blockquote> <br /><br />

        <blockquote>
        <label for="nom" > Nombre Cliente </label><br />
        <input type="text" name="nom_cli" id="nom_cli" class="form-control" maxlength="100" size="30" value="<?php echo $row['nom_cli']; ?>"  required onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                </blockquote> <br /><br />
                
                
                <blockquote>
        <label for="mail" > Mail Cliente </label><br />
        <input type="text" name="mail_cli" id="mail_cli" class="form-control" maxlength="100" value="<?php echo $row['mail_cli']; ?>" size="30" required /><span id="emailOK" class="texto"></span>
                </blockquote> <br /><br />
        
                
                <blockquote>
        <label for="fono" > Fono </label><br />
        <input type="number" name="fono_cli" id="fono_cli" class="form-control" value="<?php echo $row['fono_cli']; ?>"  required />
                </blockquote> <br /><br />
                
              <blockquote>
               <input type="checkbox" name="vig" value="vig" id="vig" <?php if ($row['vigencia_cli'] == 1) { echo 'checked'; } ?> />
               <label for="coment" > Vigencia</label>
               </blockquote><br /><br />
      

<center><input type="submit" value="Modificar Cliente" id="btnAc" /></center> 
</form>
<br><br>

</div>
</body>
<script>
document.getElementById('mail_cli').addEventListener('input', function(event) {
    campo = event.target;
    valido = document.getElementById('emailOK');
        
    emailRegex = /^(?:[^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*|"[^\n"]+")@(?:[^<>()[\].,;:\s@"]+\.)+[^<>()[\]\.,;:\s@"]{2,63}$/i;
    if (emailRegex.test(campo.value)) {
      valido.innerText = "válido";
    } else {
      valido.innerText = "incorrecto";
    }
});
</script>
</html>