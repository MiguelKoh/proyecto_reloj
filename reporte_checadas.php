<?php
    session_start();
    include('conex.php'); 
    include('funciones_reloj.php');
    $cn = ConectaBD();
  $fecha1=$_GET['fecha1'];
  $fecha2=$_GET['fecha2'];
  $idEmp=$_GET['idEmpleado'];


     	header ("Content-type: application/vnd.ms-excel");
	header ("Expires: 0");  
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	header ("Pragma: no-cache");  
	header ("Content-Disposition: attachment; filename=ReporteChecadas.xls" );

?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000" style=" border-collapse: collapse;border: 1px solid black;">                      																	
                      <tr align="center" style="border: 1px solid black;">
                      <th width="10%">Clave</th>
                      <th width="30%">Empleado</th>
                      <th width="30%">Departamento</th>
                      <th width="10%">Fecha</th>
                       <th width="10%">Entrada</th>
                       <th width="10%">Salida</th>                      
      </tr>
					
<?php 
  $SQL="SELECT e.idEmp,e.Nombre as Empleado,d.Nombre as Departamento,c.fecha,c.horaini,c.horafin FROM checadas_nuevo c 
   INNER JOIN empleado e ON e.idEmp=c.idEmp
   INNER JOIN Departamento d ON d.idDepto=e.idDepto 
   WHERE c.fechar BETWEEN '".$fecha1."' AND '".$fecha2."' AND c.idEmp='".$idEmp."'";
$query=mysqli_query($cn,$SQL);
while($mysql=mysqli_fetch_array($query)){
?>
      <tr align="center" style="border: 1px solid black;">
        <td width="10%"><?php echo $idEmp; ?></td>      
        <td width="30%"><?php  echo  $mysql['Empleado'];?></td>
        <td width="30%"><?php echo $mysql['Departamento']; ?></td> 
        <td width="10%"><?php echo $mysql['fecha']; ?></td>      
        <td width="10%"><?php echo $mysql['horaini'];?></td>
        <td width="10%"><?php echo $mysql['horafin']; ?></td>         
       </tr>				  
<?php }?>
</table>
 