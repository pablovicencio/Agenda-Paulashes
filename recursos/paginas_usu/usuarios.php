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
  require_once '../clases/funciones.php';
  
  $fun = new funciones(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">

<title>Paulashes &#8211; Lash, Beauty &amp; Boutique</title>
<link href="../estilo.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
function validar(f){
f.btnAc.value="Creando Usuario";
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
<h2>Usuarios - Paulashes</h2>
</label><br /><br />

<table border="1" class="grid">
  <caption class="texto" ><b>Lista de Usuarios</b></caption>
<tr> 
    <td style="background:#74ccd1 ; color:black" >ID USUARIO</td> 
    <td style="background:#74ccd1 ; color:black">NOMBRE</td> 
    <td style="background:#74ccd1 ; color:black">MAIL</td>
    <td style="background:#74ccd1 ; color:black">FONO</td> 
    <td style="background:#74ccd1 ; color:black">GRUPO</td>  
    <td style="background:#74ccd1 ; color:black">VIGENCIA</td>  
</tr> 
<?php
              $re = $fun->cargar_usuarios();
              foreach($re as $row){

                $id_user = $row['id_usu'];
                $link = '<a href=user.php?dato='.$id_user.' class="l">';
                
                echo ('<td ><center>'.$link.$row['id_usu'].'</a></center></td>');
                echo ('<td bgcolor="'.$row['color_usu'].'">'.$row['nom_usu'].'</td>');
                echo ('<td>'.$row['mail_usu'].'</td>');
                echo ('<td>'.$row['fono_usu'].'</td>');
                echo ('<td>'.$row['desc_grupo'].'</td>');
                echo ('<td>'.$row['vigencia_usu'].'</td></tr>');
    
              }
?>
</table><br><br>


<hr>
<label>
<h2>Crear Nuevo Usuario Paulashes</h2>
</label><br /><br />
<form role="form" action="../controles/control_crearUsuario.php" method="post" id="new_user" name="new_user" onsubmit="return validar(this)">
<blockquote>
                
        <blockquote>
        <label for="nom" > Nombre Usuario </label><br />
        <input type="text" name="nom_usu" id="nom_usu" class="form-control" maxlength="100" size="30"  required onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                </blockquote> <br /><br />
                
                
                <blockquote>
        <label for="mail" > Mail Usuario </label> *a este correo se enviara la clave para ingresar<br />
        <input type="text" name="mail_usu" id="mail_usu" class="form-control" maxlength="100" size="30" required /><span id="emailOK" class="texto"></span>
                </blockquote> <br /><br />
        
                
                <blockquote>
        <label for="fono" > Fono Usuario</label><br />
        <input type="number" name="fono_usu" id="fono_usu" class="form-control"  required />
                </blockquote> <br /><br />

                <blockquote>
        <label for="color" > Color Usuario</label> *elija colores claros por el contraste de las letras<br />
        <input type="color" name="color_usu" id="color_usu" class="form-control"  required />
                </blockquote> <br /><br />

                <blockquote>
        <label for="grupo" > Grupo Usuario</label><br />
        <select name="grupo_usu" size="1" class="form-control" id="grupo_usu" type="text" required>
           <option value="" selected disabled>Seleccione el grupo del usuario</option>
                 <?php 
                  $re1 = $fun->cargar_grupos();   
                  foreach($re1 as $row1)      
                      {
                        ?>
                        
                         <option value="<?php echo $row1['id_grupo'] ?> ">
                         <?php echo $row1['desc_grupo'] ?>
                         </option>
                            
                        <?php
                      }    
                  ?>           
        </select>
                </blockquote> <br /><br />
        <blockquote>
        <input type="checkbox" name="super" value="super"> Super Usuario<br /><br />
        </blockquote>

<center><input type="submit" value="Agregar Usuario" id="btnAc" /></center> 
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