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

if (isset($_GET{'dato'})) {
 $fec = $_GET{'dato'};
}else{
  $fec = date("Y-m");
}
$fecha = strtotime($fec."-01");
$week = 1;

  for($i=1;$i<=date('t', $fecha);$i++) {

    $day_week = date('N', strtotime(date('Y-m', $fecha).'-'.$i));

    $calendar[$week][$day_week] = $i;

    if ($day_week == 7) { $week++; };

  }
  require_once '../clases/Funciones.php';
    $mes_lbl = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre', 'Octubre', 'Noviembre','Diciembre');

    $numeroMes = date('m', $fecha) -1;

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
function modal(fec,nom) {
    $("#horas tbody tr").remove(); 
    document.getElementById('fec_cit').innerHTML = fec;
    document.getElementById('nom_usu').innerHTML = nom;
    document.getElementById('nom').innerHTML = nom;

     $.ajax({
      url: '../controles/control_horasAgenda.php',
      type: 'POST',
      data: { fecha: fec},
      dataType:'json',
      success:function(result){
        //console.log(Object.keys(result).length);
        //console.log(result[0]);
        
        var filas = Object.keys(result).length;
     
        for (  i = 0 ; i < filas; i++){ //cuenta la cantidad de registros
          var nuevafila= "<tr><td>" +
          result[i].hora_cita + "</td><td>" +
          result[i].hora_ter_cita + "</td><td>" +
          result[i].nom_cli + "</td><td bgcolor='"+result[i].color_usu+"'>" +
          result[i].nom_usu + "</td><td>" +
          result[i].ubicacion + "</td><td>" +
          result[i].estado + "</td></tr>"
     
          $("#horas").append(nuevafila)
        }
  


      }
  })

}

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



function agregar_cita(fec_cit,cli,hora_ini,hora_ter,ubi,nom){
    var array = [fec_cit,cli,hora_ini,hora_ter,ubi,nom];
    
    $.ajax({
    // aqui va la ubicación de la página PHP
      url: '../controles/control_agregarCita.php',
      type: 'POST',
      //dataType: 'html',
      data: { cita:JSON.stringify(array)},
      success:function(result){
       alert(result);
       window.location='agenda.php';
      }
  })
}
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
<label>
<h2>Agenda - Paulashes</h2>
</label><br /><br />
<hr>
<h2>Año: <?php echo date('Y', $fecha) ; ?></h2>
<center>
<h2><?php echo $mes_lbl[$numeroMes]; ?></h2>
<a href=agenda.php?dato=<?php echo date("Y-m",(strtotime("-1 month", $fecha)));?> class="l" style='margin-right: 6em'>Anterior</a>
<a href=agenda.php?dato=<?php echo date("Y-m",(strtotime("+1 month", $fecha)));?> class="l">Siguiente</a>
<center> 
<table border="1" class="calendario">

    <thead>

      <tr>

        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Lunes</td>
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Martes</td>   
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Miércoles</td>   
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Jueves</td>   
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Viernes</td>   
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Sábado</td>   
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Domingo</td>   

      </tr>

    </thead>

    <tbody>

      <?php foreach ($calendar as $days) : ?>

        <tr>

          <?php for ($i=1;$i<=7;$i++) : 
            ?>


              <?php

              
              
    
              echo '<td class="td-cal">';
              echo isset($days[$i]) ? '<a class="l" title="Agendar Hora" href="#myModal" data-id="'.$days[$i].'" data-toggle="modal" data-target="#myModal" onclick="modal(\''.date("d-m-Y",(strtotime($fec.'-'.$days[$i]))).'\',\''.$_SESSION['nombre'].'\');">'.$days[$i].'</a>' : ''; 




              ?>


            </td> 

          <?php endfor; ?>

        </tr>

      <?php endforeach ?>

    </tbody>
    </table> 
</center></td>
  </tr>
</table>
</center>
</div>
</center>



    
</div>
<div class="modalDialog" id="myModal" >
    <div role="document">
        <a href="#close" title="Close" class="close">X</a>
        <header><h2><span id="nom_usu"></span></h2></header><br><br>
        <label>Fecha:</label><span id="fec_cit"></span><br><br>

        <label>Cliente:</label>
        <select name="cli" size="1" class="form-control" id="cli" type="text" required>
           <option value="" selected disabled>Seleccione el cliente</option>
                 <?php 
                  $re = $fun->cargar_clientes(1);   
                  foreach($re as $row)      
                      {
                        ?>
                        
                         <option value="<?php echo $row['id_cli'] ?> ">
                         <?php echo $row['nom_cli'] ?>
                         </option>
                            
                        <?php
                      }    
                  ?>           
        </select><br><br>
        <label>Hora Inicio:</label>
        <input type="time" name="hora_ini" id="hora_ini" required>
        <br><br>
        <label>Tiempo de duración de la atencion:</label>
        <select name="hora_ter" size="1" class="form-control" id="hora_ter" type="text" required>
                         <option value="" selected disabled>Seleccione duración</option>
                         <option value="60"> 1 Hr</option>
                         <option value="90"> 1:30 Hrs</option>
                         <option value="120"> 2 Hrs</option>     
        </select>
        <br><br>
        <label>Ubicación:</label>
        <select name="ubi" size="1" class="form-control" id="ubi" type="text" required>
           <option  value="" selected disabled>Seleccione Ubicación</option>
                         <?php 
                  $re1 = $fun->cargar_sucursales(1);   
                  foreach($re1 as $row1)      
                      {
                        ?>
                        
                         <option value="<?php echo $row1['id_suc'] ?> ">
                         <?php echo $row1['nom_suc'] ?>
                         </option>
                            
                        <?php
                      }    
                  ?>    
        </select>
        <br><br>

         <label>Estilista:</label>

        <?php if ($_SESSION['perfil'] == 1) {
          echo '<span id="nom"></span>';
        }else{
          echo ' <select name="nom" size="1" class="form-control" id="nom" type="text" required> 
          <option  value="" selected disabled>Seleccione Estilista</option> ';
          $re2 = $fun->cargar_estilistas();   
                  foreach($re2 as $row2)      
                      {
                        echo ' <option value="'.$row2['id_usu'].'">'.$row2['nom_usu'].'</option> ';
                      }

                      echo '</select> ';
        }


        ?>
        
        <br><br>

        <center><a href="#" onclick="agregar_cita(fec_cit.innerHTML,document.getElementById('cli').value,hora_ini.value,document.getElementById('hora_ter').value,document.getElementById('ubi').value,document.getElementById('nom').value);" class="l">Agendar</a></center> <br><br>

    <table border="1" class="grid" name="horas" id="horas">
    <caption>Horas agendadas</caption>
    <thead>

      <tr>

        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Hora cita</td>
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Hora termino</td>   
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Cliente</td>   
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Estilista</td>   
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Ubicación</td>   
        <td style="background:#74ccd1 ; font-size: 16px; color:grey">Estado</td>   
      </tr>

    </thead>

    <tbody>
    </tbody>
    </table>
    </div>
</body>
</html>