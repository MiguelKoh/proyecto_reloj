<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->


<?php
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
  
    $variables=Request("idDepto,idEmp");
 
    if ($variables[1][2]) {$idDepto    = $variables[1][1];} else {$idDepto = 0;}       
    if ($variables[2][2]) {$idEmp  = $variables[2][1];} else {$idEmp = "";}  

    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <form name="form1" method="post" action="">
        <table width="100%" border="0" cellpadding="3" cellspacing="1">
                <tr>
                  <td width="14%" class="LetraGris14BoldForm">
                    Departamento: </td>
                  <td width="37%"> <select name="idDepto" id="idDepto" onChange="javascript:submit()">
                        <?php 
                             $SQLp="SELECT * FROM departamento";
                             $queryA = mysql_query($SQLp,$cn);
                        ?>
                        <option value="0">Seleccione</option>
                        <?php 
                            while( $rsA=mysql_fetch_array($queryA) ) { 
                                if ($idDepto == $rsA["idDepto"]) {
                                    $selected = " selected";
                                    } 
                                else {
                                    $selected = "";
                                    } 
                        ?>
                        <option value="<?php echo $rsA["idDepto"]; ?>"<?php echo $selected;?>>
                            <?php echo $rsA["Nombre"];?>                </option>
                        <?php } mysql_free_result($queryA);?>
                      </select></td>
                  <td width="49%"><label></label></td>
                </tr>
                        <tr>
                  <td width="14%" class="LetraGris14BoldForm">
                    Empleado:</td>
                  <td width="37%"><label>
                         <?php 
                                $SQL = "SELECT idemp, Nombre from empleado WHERE idDepto = ".$idDepto." ORDER BY idemp";
                                $query = mysql_query($SQL,$cn);
                                $frows = mysql_num_rows($query);
                                if ($frows > 0) {
                        ?>
                  <select name="idEmp" id="idEmp" onChange="javascript:submit()">
                    <option value="0"<?php if ($idEmp == 0) {echo " selected";}?>>Seleccione un Periodo</option>
                    <?php while ($rs = mysql_fetch_array($query)) {
                                 if ($idEmp == $rs["idemp"]) {$selected = " selected";} else {$selected = "";} ?>
                    <option value="<?PHP echo $rs["idemp"]; ?>"<?php echo $selected;?>><?php echo $rs['idemp']." - ". $rs['Nombre']?></option>
                    <?php } mysql_free_result($query);?>
                  </select>
                <?php } else { ?>
               <p class="LetraGris14Bold"> N/A</p>  
                <?php } ?></td>
                  <td width="49%"></td>
                </tr>
                
              </table>
        </form>
        
        <tr><td>Departamento elegido: <?php echo $idDepto; ?> </td></tr>
        <tr><td>Empleado elegido: <?php echo $idEmp; ?> </td></tr>
                
    </body>
</html>
