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
f.btnAc.value="Modificando Usuario";
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


<?php

            // if ($_SESSION['super'] == 1) {
             $id = $_GET{'dato'};
            // }else{
            //   $id = $_SESSION['id_usu'];
            // }
             
              $re = $fun->cargar_usu($id);
              foreach($re as $row){    
              }
              
?>



<label>
<h2>Modificar Usuario Paulashes</h2>
</label><br /><br />
<a href="cambiar_pass.php" class="l">Cambiar Contraseña</a><br><br>
<hr><br />
<form role="form" action="../controles/control_modUsuario.php" method="post" id="upd_usu" name="upd_usu" onsubmit="return validar(this)">
<blockquote>

                <blockquote>
        <label for="id" > Id Usuario </label><br />
        <input type="text" name="id_usu" id="id_usu" style="border-width:0;" value="<?php echo $row['id_usu']; ?>" maxlength="10"  required readonly />
                </blockquote> <br /><br />
                
              

        <blockquote>
        <label for="nom" > Nombre Usuario </label><br />
        <input type="text" name="nom_usu" id="nom_usu" class="form-control" maxlength="100" size="30" value="<?php echo $row['nom_usu']; ?>"  required onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                </blockquote> <br /><br />
                
                
                <blockquote>
        <label for="mail" > Mail Usuario </label><br />
        <input type="text" name="mail_usu" id="mail_usu" class="form-control" maxlength="100" value="<?php echo $row['mail_usu']; ?>" size="30" required /><span id="emailOK" class="texto"></span>
                </blockquote> <br /><br />
        
                
                <blockquote>
        <label for="fono" > Fono Usuario</label><br />
        <input type="number" name="fono_usu" id="fono_usu" class="form-control" value="<?php echo $row['fono_usu']; ?>"  required />
                </blockquote> <br /><br />

                 <blockquote>
        <label for="color" > Color Usuario</label><br />
        <input type="color" name="color_usu" id="color_usu" value="<?php echo $row['color_usu']; ?>" class="form-control"  required />
                </blockquote> <br /><br />



                <blockquote>
                 <label for="grupo" > Grupo Usuario</label> El cambio de Estilistas a Recepcionistas bloqueara las citas asociadas<br />
                <select name="grupo_usu" size="1" class="form-control" id="grupo_usu" type="text" required >
                   <option value="" selected disabled>Seleccione el grupo del usuario</option>
                         <?php 
                          $re1 = $fun->cargar_grupos();   
                          foreach($re1 as $row1)      
                              {
                                ?>
                        <option value="<?php echo$row1["id_grupo"]?> "><?php echo $row1["desc_grupo"] ?></option>
                                    
                                <?php
                              }    
                          ?>           
                </select>
                </blockquote> <br /><br />

                <blockquote><input type="checkbox" name="super" value="super" <?php if ($row["super_usu"] == 1) { echo "checked"; } ?> > Super Usuario </blockquote><br /><br />
      


               <blockquote><input type="checkbox" name="vig" value="vig" id="vig" <?php if ($row["vigencia_usu"] == 1) { echo "checked"; } ?> />
               Vigencia </blockquote><br /><br />'

      
</div>
<center><input type="submit" value="Modificar Usuario" id="btnAc" /></center> 
</form>
<br><br>

</div>
</body>
<script>
document.getElementById('mail_usu').addEventListener('input', function(event) {
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