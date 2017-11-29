<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Paulashes &#8211; Lash, Beauty &amp; Boutique</title>
<link href="../estilo.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="contenido1" >
<label>
<h2>Restablecer Contraseña - Paulashes</h2>
</label><br /><br />

<form method="post" action="../controles/control_restablecer.php"> 
<label >Ingrese el correo asociado a su cuenta:</label><input typr="email" name="mail" id="mail" class="form-control" size="90" required><span id="emailOK" class="texto"></span><br /><br />
</form>
</body>
<script>
document.getElementById('correo').addEventListener('input', function(event) {
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