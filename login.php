<?PHP session_start();?>
<?PHP include('conex.php'); 
 $cn = ConectaBD();
?>

<?php
  if ((isset($_POST['usuario']) and !empty($_POST['usuario'])) or isset($_POST['contra'])and !empty($_POST['contra'])) { //if 2
   
    //recuperamos los valores sino estan vacios
    $usuario   =  htmlentities($_POST['usuario']);
	$password  = htmlentities($_POST['contra']);

	 $autenti="SELECT * FROM usuarios WHERE usuario='".$usuario."' AND clave='".$password."'";
	$result = mysqli_query($cn,$autenti);	
	$rs=mysqli_fetch_array($result);
	$verifica=mysqli_num_rows($result);
	if($verifica>0){
		$_SESSION['Access']         = true;				
	    $_SESSION['idUsuario']       = htmlentities($rs['idUsuario']);
	    $_SESSION['NombreCompleto'] = htmlentities($rs['nombre']);
	    echo "<script>location.href='index.php'</script>";
	} 
}

?>
<html>
<head>
<title>Control Asistencias PR2</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
			<!-- vinculo a bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!-- Temas-->

<!-- se vincula al hoja de estilo para definir el aspecto del formulario de login-->  
<link href="assets/css/styles.css" rel="stylesheet">
<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon_uady.ico">

<style type="text/css">

/*body{
	font-size: 12px;
	background-color:#002E5F;
}*/
/**
 * se aplica el ancho, margen centrado
 * borde de un pixel con redondeado, y rellenado
 * a la izquierda y derecha
 */
#Contenedor{
	width: 400px;
	margin: 50px auto;
	background-color: #F3EDED;
        border: 1px solid #ECE8E8;
	height: 400px;
	border-radius:8px;
	padding: 0px 9px 0px 9px;
}
 
/**
 * Aplicando al icono de usuario el color de fondo,
 * rellenado de 20px y un redondeado de 120px en forma
 * de un circulo
 */
.Icon span{
      background: #A8A6A6;
      padding: 20px;
      border-radius: 120px;
}
/**
 * Se aplica al contenedor madre un margen de tamaño 10px hacia la cabecera y pie,
 * color de fuente blanco,un tamaño de fuente 50px y texto centrado.
 */
.Icon{
     margin-top: 10px;
     margin-bottom:10px; 
     color: #FFF;
     font-size: 50px;
     text-align: center;
}
/**
 * Se aplica al contenedor donde muestra en el pie
 * la opción de olvidaste tu contraseña?
 */
.opcioncontra{
	text-align: center;
	margin-top: 20px;
	font-size: 14px;
}
 
/**
 * En las siguientes lineas
 * se define el diseño adaptable, para que
 * se muestre en los dispositivos móviles
 */
 
/******************************************/
/***    DISEÑO PARA MOVILES 320        ****/
/******************************************/
@media only screen and (max-width:320px){
#Contenedor{
	width: 100%;
	height: auto;
	margin: 0px;
}

 
/******************************************/
/***    DISEÑO PARA MOVILES 240        ****/
/******************************************/
@media only screen and (max-width:240px){	
}
}
#titulo{
	text-align: center;
	font-size: 25px;
	margin-top: 45px;
	color: white;
	
}
#logoLogin{
	width:50%;
	margin-bottom:5px ;
}
.tituloLogin{
	display: flex;
	justify-content: center;
	color: #fff;
	align-content: center;
	font:400 26px/40px Roboto,Helvetica Neue,sans-serif;
	margin:0;

}
.fondoTitulo {
	background-color:#12295B;
	margin: 0;
	padding: 5px;
}

</style>
		</head>
		
		<body>
		
		<div class="fondoTitulo">
			<div class="col w-100">
		     <h2 class="tituloLogin">Control de Asistencia del Personal de la Escuela Preparatoria Dos</h2>
		  </div>

		</div>
		
		
		<div id="Contenedor"><!--Inicio contenedor-->

			<div class="Icon">
						<img src="imagen/LOGO_PREPA.png" id="logoLogin" class="mb-2">
						<!--Icono de usuario-->
			</div>

			<div class="ContentForm" class="row">
		 		
				<form action="login.php" method="post" name="FormEntrar" class="px-3">
		 			<p class="h5 mb-3">Iniciar sesion</p>
		 			<div class="input-group input-group-lg">

						<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-user"></i></span>
				 	    <input type="text" class="form-control" name="usuario" placeholder="Usuario" id="usuario" aria-describedby="sizing-addon1" required>
					
				     </div>
					<br>
					<div class="input-group input-group-lg">
				  <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
				  <input type="password" name="contra" class="form-control" placeholder="******" aria-describedby="sizing-addon1" required>
			</div>
				<br>
				<button class="btn btn-lg btn-primary w-100" id="IngresoLog" type="submit">Entrar</button>
				
		 		</form>
		 
		</div>	<!--fin contenedor-->
	
		<div class="row"></div>
		 
</div>
</body>
 <!-- vinculando a libreria Jquery-->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
 <!-- Libreria java scritp de bootstrap -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</html>