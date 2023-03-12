<html> 
<html> 
<head> 
<title>pagina1.php</title> 
</head> 

<body> 
<form method="post" action="pagina2.php"> 

    <table width="70%" border="0" align="center"> 
    <?php 
// Me conecto a la base de datos 
mysql_connect("localhost","root","adminpr2"); 
mysql_select_db("reloj"); 

// Declaro la variable $paisant que es la que me va a indicar si hay que volver a cargar los datos de las provincias 
$pais="";
$paisant=$pais; 
$nombre="";

print (" 
    <tr> 
       <td><div align=\"right\"><strong>Nombre y Apellido:</strong></div></td> 
       <td> <input type=\"text\" name=\"nombre\" value=\"$nombre\"></td> 
    </tr> 

    <input type=\"hidden\" name=\"paisant\" value=\"$paisant\"> 

    <tr> 
       <td><div align=\"right\"><strong>Pais:</strong></div></td> 
       <td><select name=\"pais\" onchange=\"submit();\"> 
       "); 
//Muestra el combobox de las provincias una vez que se haya elegido el país, no antes 
if (!isset($pais)){ 
    print ("<option selected>Seleccione el pais</option>"); 
    $pais="0"; 
} 

$sql="select iddepto,nombre from departamento"; 
$res=mysql_query($sql); 

while($fila=mysql_fetch_array($res)){ 
print("<option value=".$fila['iddepto']); 
if ($fila['iddepto'] == $pais) { 
print ("selected"); 
} 
print(">".$fila['nombre']."</option>"); 
} 
print("</select></td></tr>"); 

if ($pais!="0"){ 
print(" 
<tr> 
    <td><div align=\"right\"><strong>Provincia:</strong></div></td> 
<td><select name=\"prov\"> 
"); 

$sqlprov="select idemp,nombre,iddepto from empleado where iddepto='$pais' order by nombre"; 
$resprov=mysql_query($sqlprov); 

while($filaprov=mysql_fetch_array($resprov)){ 
print("<option value=".$filaprov['idemp'].">".$filaprov['nombre']."</option>"); 
} 
print(" 
    </select> 
    </td> 
       </tr> 
"); 
} 
       ?> 
    <tr> 
       <td><div align="right"><input name="button" type="submit" value="Enviar"></div></td> 
       <td><input name="reset" type="reset" value="Borrar"></td> 
    </tr> 
    </table> 

</form> 

</body> 
</html> 