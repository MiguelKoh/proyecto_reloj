<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Escuela Preparatoria Dos - UADY</title>
        <link href="../adds/estilo.css" rel="stylesheet" type="text/css" />
        <link type="text/css" rel="stylesheet" href="Estilo_registrados.css" />       
        <script language="javascript" type="text/javascript">
            function botonPresionado(iNum){
            //		location.href='fin.php';
                    if(iNum == 1){
                          //  location.href='imprimir.php';//document.frmImprimir.submit();
                          location.href='salir.php';
                    }
            }
            
            function confirmarSal(){
                    alert("Las credenciales no son validas");
                    location.href='salir.php';
             
            }                            

            function validarSelect(){
                
                var usuario = document.getElementById("usuario").value;
                var pwd = document.getElementById("pwd").value; 
                var registro = 1;
                                
                if (usuario.length == 0){
                    alert("Debes escribir tu usuario.");
                    registro = 0;
                }

                if (pwd.length == 0){
                    alert("Debes escribir tu contrase�a.");
                    registro = 0;
                }                
                
                if (registro == 1){
                    document.login.submit();
                }
            }                    
                   

            function confirmarSal(){
                location.href='salir.php';            
            }            
        </script>        
    </head>
<table width="731" align="center"  cellpadding="0" cellspacing="0" id="tabla">
  <!--Se incluye la cabecera -->
  <tr> 
      <?php include_once ("../adds/cabecera.php")?> 
  </tr>
  <!--Termina la cabecera -->

  <!--Se incluye la Fecha -->
  <tr> 
    <td  valign="middle" align="right" height="20"> <font color="#FFFFFF"><strong>Hoy es:</strong>
	 <?php include("../adds/lib.php"); echo fecha();?> 
      </font></td>
  </tr>
  <!--Termina la Fecha -->
</table>
<table width="731" align="center"  id="tabla" border="0">
  <tr>
   <!--Se incluye el menu lateral,de la carpeta adds -->
   <!-- ?php include_once("../adds/menulateral.php");?> -->
   </table></td>    
    <body>
        <form action="validaUsr.php" name="login" id="login" method="post"> 
            <table  id="login" border="0" width="25%" style="height:50%" cellpadding="1" cellspacing="1">
                <tr>
                    <td colspan ="3">Ingresa tus credenciales INET</td>
                </tr>
                <tr><td coslpan="3">&nbsp;</td></tr>
                <tr>
                    <td align ="left" >Usuario:</td>
                    <td colspan="2"><input name="usuario" type="text" id="usuario" size="20" maxlength="20"></td>
                </tr>
                <tr>
                    <td align ="left" >Contrase&ntilde;a:</td>
                    <td colspan="2"><input name="pwd" type="password" id="pwd" size="20" maxlength="20"></td>
                </tr>  
                <tr>
                    <td colspan="3" style="text-align:center;vertical-align:middle">
                        <input type="submit" value="Enviar" id="btnRegis" name="btnRegis" onclick="validarSelect()"/>
                    </td>
                </tr>            
            </table>
        </form>
    </body>
</html>
