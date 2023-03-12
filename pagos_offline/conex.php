<?php 
function Conectarse() 
{ 
   if (!($link=mysql_connect("chicbul.uady.mx","prepa2_user","pr207ti"))) 
   //if (!($link=mysql_connect("localhost","root","adminpr2"))) 
   { 
      echo "Error conectando a la base de datos."; 
      exit(); 
   } 
   if (!mysql_select_db("prepa2",$link)) 
   { 
      echo "Error seleccionando la base de datos."; 
      exit(); 
   } 
   return $link; 
} 
?>