<?PHP
/* ........................................................................................................
Funcion para parsear una cadena. Ejemplo:
$cadena = "abc,123,def,456";
$valores = arr_render($cadena,",",1);
$valores se convierte en una matriz de 1 x 4, con los valores abc, 123, def, 456
*/
$RData = "";
function arr_render($parametros, $separador, $x)
{
    $paso = "";
    $n = 1;
    for ($i = 0; $i <= strlen($parametros) - 1; $i++) {
        if (substr($parametros, $i, 1) == $separador) {
            $paso = "";
            $n = $n + 1;
        } else {
            $paso = $paso . substr($parametros, $i, 1);
            $RData[$x][$n] = $paso;
        }
    }
    return $RData;
    return $n;
}

?>
<?PHP
/* ........................................................................................................
Funcion de devolver si un numero es paro o non.
*/
function Par_Non($Valor)
{
    $Xrt = $Valor;
    $ParNon = ($Xrt % 2);
    if ($ParNon == 1) {
        $ResTN = "NON";
    } else {
        $ResTN = "PAR";
    }
    return $ResTN;
}

?>
<?PHP
/* ........................................................................................................
Funcion armar una tabla con un query enviado.
  $SQL - Sentencia SQL a ejecutar, por ejemplo "SELECT E.Name as 'Nombre', E.App as 'Apellido' FROM Empleados E Order By E.ID"
  $ColorSet - Grupo de Colores obtenido de la columna del array mySets
  $Spaces - Espacio en pixeles entre los bordes y el texto de las celdas
  $Tipo - Tipo 1. Solo devuelve los campos con el query. Tipo 2, ademas devuelve dos columnas mas con 2 botones, uno hacia una
  		   liga especificada, y el otro para borrar.
  $Liga - Liga que lleva el primer boton del tipo 2 de tabla, y/o el valor de la columna especificada
  $GetVariable - Variable que se enviara por GET a traves de la liga especificada en $Liga
  $PosLiga - Numero de columna cuyo contenido llevara la liga especificada en $Liga
  $tSize - Tamanio Horizontal de la Tabla, ya sea en pixeles o porcentajes.
  $Target - El target de las ligas
  $Liga2  - Si es tipo 6 se pone la liga2, en caso k sea distinto de tipo 6 dejar nulo este parametro

Ejemplo: (Funciones.php se incluye en cualquier archivo y luego se ejecuta:

  $SQL = "SELECT * FROM Products";
  $TablaMaster = BuildTable($SQL,1,3,1,"modificar.php?","ID",2,"");
  echo "Tabla <hr>".$TablaMaster."<hr>FIN Tabla";

Se genera una tabla con los valores de Products, con el conjunto de colores de la columna 1 del array mySets, con 3 pixeles entre
el texto y los bordes de la tabla. No se generan los 2 botones (es tipo1), y en la segunda columna de los datos de la tabla, el valor
escrito llevara liga a modificar.php?ID=ID_DEL_PRODUCTO

*/
$TablaMaster = "";
function BuildTable($SQL, $ColorSet, $Spaces, $Tipo, $Liga, $GetVariable, $PosLiga, $tSize, $Target, $Liga2 = "")
{

    $DArray = "";
    $mySets[0] = "#ffffff,#000099,#000000";
    $mySets[1] = "#D3DCE3,#3B5186,#BCCADA"; // TOP Background TopColors - Color de la Barra con el Nombre de los Campos
    $mySets[2] = "#FFFFFF,#000000,#ACA7A7"; // Border Color - Color del Borde de la Tabla
    $mySets[3] = "#CCCCCC,#99a9c8,#DBE0E5"; // Background line 1 colors - Color de intercalado 1
    $mySets[4] = "#DDDDDD,#ccd6ea,#F4F6F7"; // Background line 2 colors - Color de intercalado 2
    $mySets[5] = "#000000,#000000,#000000"; // Text Colors - Color de los textos
    $mySets[6] = "#28296B,#28296B,#28296B"; // Link Colors - Color de los links (si es que hay en los textos)

    if ($Target <> "") {
        $Target = " target='" . $Target . "'";
    } else {
        $Target = "";
    }

    for ($x = 0; $x <= 6; $x++) {
        $RData = arr_render($mySets[$x], ",", $x);
        $DArray[$x][$ColorSet] = $RData[$x][$ColorSet];
    }

    $TitleColor = $DArray[0][$ColorSet];
    $BGTop = $DArray[1][$ColorSet];
    $Border = $DArray[2][$ColorSet];
    $Line1Color = $DArray[3][$ColorSet];
    $Line2Color = $DArray[4][$ColorSet];
    $TextColor = $DArray[5][$ColorSet];
    $LinkColor = $DArray[6][$ColorSet];

    if ($tSize <> "") {
        $TableSize = "width='" . $tSize . "'";
    } else {
        $TableSize = "width=''";
    }

    $rsTable = mysql_query($SQL);

// ver si hay datos

    $NumCampos = mysql_num_fields($rsTable);
    $NumRegistros = mysql_num_rows($rsTable);

    if ($NumRegistros <= 0) {
        $TablaMaster = "<img src='" . rutaimagenes('') . "s_warn.png' width='16' height='16'>  No hay registros para mostrar</img>";
        // echo $TablaMaster;
        return $TablaMaster;
    }

    $TablaMaster = "<table  border='0' cellpadding='" . $Spaces . "' cellspacing='1' bgcolor='" . $Border . "'" . $TableSize . ">";
    $TablaMaster = $TablaMaster . "<tr bgcolor='" . $BGTop . "'>";
    if (($Tipo == 3) or ($Tipo == 4) or ($Tipo == 6) or ($Tipo == 7) or ($Tipo == 8)) {
        $initc = 1;
    } else {
        $initc = 0;
    }
    for ($campos = $initc; $campos < $NumCampos; $campos++) {
        $aname = mysql_field_name($rsTable, $campos);
        $TablaMaster = $TablaMaster . " <td class='Table_BackGroundTop_Lista'><b><font color=" . $TitleColor . ">" . $aname . "</font></b></td>";
    }
    if (($Tipo == 2) or ($Tipo == 3) or ($Tipo == 5) or ($Tipo == 6)) { // si la tabla es tipo 2, agrego dos columnas mas cuyos titulos seran:
        //Si es tipo 2 muestra el ID, Borrar y Actualizar
        //Si es tipo 3 Quita el ID y muestra Borrar y Actualizar
        $TablaMaster = $TablaMaster . " <td class='Table_BackGroundTop_Lista'><b><font color=" . $TitleColor . ">EDITAR</font></b></td>";
        $TablaMaster = $TablaMaster . " <td class='Table_BackGroundTop_Lista'><b><font color=" . $TitleColor . ">BORRAR</font></b></td>";
        if ($Tipo == 5) { //muestra Editar_Domicilio,ademas de Editar y Borrar
            $TablaMaster = $TablaMaster . " <td class='Table_BackGroundTop_Lista'><b><font color=" . $TitleColor . ">DOMICILIO</font></b></td>";
        }
        if ($Tipo == 6) { //muestra Ver_Curriculum,ademas de Editar y Borrar
            $TablaMaster = $TablaMaster . " <td class='Table_BackGroundTop_Lista'><b><font color=" . $TitleColor . ">VER</font></b></td>";
        }
    }
    if ($Tipo == 4) { //mostrar solo Editar
        $TablaMaster = $TablaMaster . " <td class='Table_BackGroundTop_Lista'><b><font color=" . $TitleColor . ">EDITAR</font></b></td>";
    }

    if ($Tipo == 7) { //mostar solo Borrado
        $TablaMaster = $TablaMaster . " <td class='Table_BackGroundTop_Lista'><b><font color=" . $TitleColor . ">BORRAR</font></b></td>";
    }
    $TablaMaster = $TablaMaster . "</tr>";

    $x = 1;
    while ($dato = mysql_fetch_array($rsTable)) {
        $ResTN = Par_Non($x);
        switch ($ResTN) {
            case "PAR":
                $Fondo = $Line1Color;
                break;
            case "NON":
                $Fondo = $Line2Color;
                break;
        }
        $TablaMaster = $TablaMaster . "<tr bgcolor='" . $Fondo . "'>";
        $y = 1;
        for ($campos = $initc; $campos < $NumCampos; $campos++) {
            if (($Tipo == 3) or ($Tipo == 4) or ($Tipo == 6) or ($Tipo == 7) or ($Tipo == 8)) {
                $plink = $campos - 1;
            } else {
                $plink = $campos;
            }
            if ($y == 1) {
                $IDLocal = $dato[$plink];
            }
            if (($Liga <> "") and ($PosLiga == $y)) {
                $TablaMaster = $TablaMaster . " <td><a href='" . $Liga . "" . $GetVariable . "=" . $IDLocal . "'" . $Target . "><font color='" . $LinkColor . "'>" . $dato[$campos] . "</font></a></td>";
            } else {
                $TablaMaster = $TablaMaster . " <td><font color='" . $TextColor . "'>" . $dato[$campos] . "</font></td>";
            }
            $y = $y + 1;
        }
        if (($Tipo == 2) or ($Tipo == 3) or ($Tipo == 5) or ($Tipo == 6)) { // si la tabla es tipo 2, agrego dos columnas con los botones a las ligas especificadas
            //Si es tipo 2 muestra el ID, Borrar y Actualizar
            //Si es tipo 3 Quita el ID y muestra Borrar y Actualizar
            $TablaMaster = $TablaMaster . " <td><div align='center'>"
                . "<a href='" . $Liga . "action=edit&" . $GetVariable . "=" . $IDLocal . "'" . $Target . "><img src='" . rutaimagenes('') . "editar.png' border=0 alt='Editar'></a></div></td>";
            $TablaMaster = $TablaMaster . " <td><div align='center'>"
                . "<a onclick='return confirmDel();' href='" . $Liga . "action=erase&" . $GetVariable . "=" . $IDLocal . "'" . $Target . "><img src='" . rutaimagenes('') . "eliminar.png' border=0 alt='Borrar'></div></td>";
            if ($Tipo == 5) { //muestra Editar_Domicilio,ademas de Editar y Borrar
                $TablaMaster = $TablaMaster . " <td><div align='center'>"
                    . "<a href='" . $Liga . "action=address&" . $GetVariable . "=" . $IDLocal . "'" . $Target . "><img src='" . rutaimagenes('') . "home2.png' border=0 alt='Editar Domicilio'></div></td>";
            }
            if ($Tipo == 6) {//muestra Ver_Curriculum,ademas de Editar y Borrar
                $TablaMaster = $TablaMaster . " <td><div align='center'>"
                    . "<a   href=\"javascript:Ventana('" . $Liga2 . "action=ver&" . $GetVariable . "=" . $IDLocal . "')\"><img src='" . rutaimagenes('') . "search2.png' border=0 alt='Ver Curriculum'></div></td>";
            }
        }
        if ($Tipo == 4) {//mostrar solo Editar
            $TablaMaster = $TablaMaster . " <td><div align='center'>"
                . "<a href='" . $Liga . "action=edit&" . $GetVariable . "=" . $IDLocal . "'" . $Target . "><img src='" . rutaimagenes('') . "b_edit.png' border=0 alt='Editar'></a></div></td>";
        }

        if ($Tipo == 7) {//mostar solo Borrado
            $TablaMaster = $TablaMaster . " <td><div align='center'>"
                . "<a onclick='confirmDel();' href='" . $Liga . "action=erase&" . $GetVariable . "=" . $IDLocal . "'" . $Target . "><img src='" . rutaimagenes('') . "b_drop.png' border=0 alt='Borrar'></div></td>";
        }
        $TablaMaster = $TablaMaster . "</tr>";
        $x = $x + 1;
    }

    $TablaMaster = $TablaMaster . "</table>";
//mysql_close($dato);
    return $TablaMaster;
}

?>
<?PHP
// Funciones para crear tablas para mensajes en HTML, tanto de error como de avisos.
$TablaError = "";
function ErrTable($msgerr)
{
    $TablaError = "<div align='center'><table width='80%' border='0' cellpadding='3' cellspacing='1' bgcolor='#FF0000'>";
    $TablaError = $TablaError . " <tr>";
    $TablaError = $TablaError . "    <td align='center' background='images/tbl_error.png' bgcolor='#221111'><font color='#FFFFFF'><b>" . $msgerr . "</b></font></td>";
    $TablaError = $TablaError . "  </tr>";
    $TablaError = $TablaError . "</table></div>";
    return $TablaError;
}

$TablaMensaje = "";
function MsgTable($msg)
{
    $TablaMensaje = "<div align='center'><table width='80%' border='0' cellpadding='3' cellspacing='1' bgcolor='#ffffff'>";
    alert('No se ha podido eliminar el registro...');
    $TablaMensaje = $TablaMensaje . " <tr>";
    $TablaMensaje = $TablaMensaje . "    <td bgcolor='#556583'><img src='" . rutaimagenes('') . "s_attention.png' align='left'><span class='Avisos'>&nbsp;" . $msg . "</span></td>";
    $TablaMensaje = $TablaMensaje . "  </tr>";
    $TablaMensaje = $TablaMensaje . "</table></div>";
    return $TablaMensaje;
}

$Mensaje = "";
// tipo 1 = error, 2 = aviso, 3 = satisfactorio, towrite = escribir o no al momento
function Mensaje($msg, $tipo, $towrite)
{
    switch ($tipo) {
        case "1":
            $borde = "#FF0000";
            $fondo = "#de0025";
            $letra = "#FFFFFF";
            $imgadicional = "s_error.png";
            break;
        case "2":
            $borde = "#000000";
            $fondo = "#E6C017";
            $letra = "#FFFFFF";
            $imgadicional = "s_attention.png";
            break;
        case "3":
            $borde = "#000000";
            $fondo = "#00682c";
            $letra = "#FFFFFF";
            $imgadicional = "b_usrcheck.png";
            break;
    }
    $Mensaje = "<div align='center'><table width='70%' border='0' cellpadding='3' cellspacing='1' bgcolor='" . $borde . "'>";
    $Mensaje = $Mensaje . " <tr>";
    $Mensaje = $Mensaje . "    <td bgcolor='" . $fondo . "'><font color='" . $letra . "'>&nbsp;" . $msg . "</font></td>";
    $Mensaje = $Mensaje . "  </tr>";
    $Mensaje = $Mensaje . "</table></div>";
    if ($towrite == "1") {
        echo $Mensaje;
        $Mensaje = "";
    } else {
        return $Mensaje;
    }
}

?>
<?PHP
$NumCampos = "";
$NumRegistros = "";
$Matrix = "";
function Query2Matrix($SQL)
{
    if ($rsData = mysql_query($SQL)) {
        $NumCampos = mysql_num_fields($rsData);
        $NumRegistros = mysql_num_rows($rsData);
        for ($x = 0; $x < $NumCampos; $x++) {
            $Matrix[0][$x] = mysql_field_name($rsData, $x);
        }
        $row = 0;
        while ($dato = mysql_fetch_row($rsData)) {
            $row = $row + 1;
            for ($x = 0; $x < $NumCampos; $x++) {
                $Matrix[$row][$x] = $dato[$x];
            }
        }
    } else {
        echo "Error en el Query...";
        die;
    }
    return $Matrix;
}

?>
<?PHP
function Request($GetValue)
{
    if ($GetValue <> "") {
        $RData = arr_render($GetValue, ",", "1");
        $n = count($RData[1]);
        for ($x = 1; $x <= $n; $x++) {
            if (isset($_POST[$RData[1][$x]]) and ($_POST[$RData[1][$x]] <> "")) {
                $Value[$x][1] = $_POST[$RData[1][$x]];
                $Value[$x][2] = 1;
            } else {
                if (isset($_GET[$RData[1][$x]]) and ($_GET[$RData[1][$x]] <> "")) {
                    $Value[$x][1] = $_GET[$RData[1][$x]];
                    $Value[$x][2] = 1;
                } else {
                    $Value[$x][1] = "";
                    $Value[$x][2] = 0;
                }
            }
        }
    } else {

        $Value[1][1] = "ERROR DE DATOS";
        $Value[1][2] = 0;
    }
    return $Value;
}

?>
<?PHP
/*
function mysql_insert_id() {
  $id = "";
  $rs = mysql_query("SELECT @@identity AS id");
  if ($row = mysql_fetch_row($rs)) {
   $id = trim($row[0]);
  }
  mysql_free_result($rs);

  return $id;
}
*/
?>
<?PHP
function rutaimagenes($carpeta)
{
    $uploaddir = getcwd();
    if ($carpeta == "") {
        $carpeta = "imagenes";
    }
    $rutaimagenes = "/" . $carpeta . "/"; // Ruta respecto al raiz de la aplicacion

    $RData = arr_render($_SERVER['SCRIPT_NAME'], "/", "1");
    $liga = "";
    $dir = "siscap2";
    for ($x = 2; $x <= count($RData[1]); $x++) {
        $liga = $liga . "/" . $RData[1][$x];
        if ($RData[1][$x] == $dir) {
            break;
        }
        //echo $liga."<br>";
    }

    $imgUrl = "http://" . $_SERVER['SERVER_NAME'] . $liga . $rutaimagenes;
    return $imgUrl;
}

?>
<?PHP
function makets($fecha)
{
    $valores = split("-", $fecha);
    $dia = $valores[0];
    $mes = $valores[1];
    $anio = $valores[2];
    $tstamp = mktime(0, 0, 0, $mes, $dia, $anio);
    return $tstamp;
}

?>
<?php
function undots($tstamp)
{
    $fecha = date("d-m-Y", $tstamp);
    return $fecha;
}

?>
<?php
function maketstoday()
{
    $fecha = date("d-m-Y");
    $hora = date("H");
    $min = date("i");
    $seg = date("s");
    $valores = split("-", $fecha);
    $dia = $valores[0];
    $mes = $valores[1];
    $anio = $valores[2];
    $tstamp = mktime($hora, $min, $seg, $mes, $dia, $anio);
    return $tstamp;
}

?>
<?PHP
function FormatClave($Clave, $numchar)
{
    $long = strlen($Clave);
    if ($long < $numchar) {
        for ($x = 1; $x <= ($numchar - $long); $x++) {
            $Clave = "0" . $Clave;
        }
    }
    return $Clave;
}

?>
<?php
/******   Convierte los tipos de datos para agregar correctamente a la BD ****/
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "blob":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}

?>
<?php
/* IMPORTANTE: trabaja con la extensión php_gd2
   campo         = nombre del campo tipo file del formulario que recibe la imagen
   anchura       = ancho final de la imagen
  anchura_thumb  = ancho final del thumb
*/
function subirImagen($campo, $anchura, $anchura_thumb)
{
    $_img;  // arreglo con datos e imagenes que se regresar�
    $carpeta = "tmp";
    $rutaimagenes = "/" . $carpeta . "/"; // Ruta respecto al raiz de la aplicacion
    $RData = arr_render($_SERVER['SCRIPT_NAME'], "/", "1");
    $liga = "";
    $dir = "siscap2";
    for ($x = 2; $x <= count($RData[1]); $x++) {
        $liga = $liga . "/" . $RData[1][$x];
        if ($RData[1][$x] == $dir) {
            break;
        }
        //echo $liga."<br>";
    }
    $imgUrl = "http://" . $_SERVER['SERVER_NAME'] . $liga . $rutaimagenes;
    $dirtmp = $imgUrl;  // directorio donde se crearan imagenes temporales (asignar derechos)
    if ($_FILES[$campo]['name'] != '') {
        /*** Verificar que el directorio temporal tenga permisos de escritura ***/
        //Si el campo est� lleno, es decir, si se subi� una foto...

        $name = $_FILES[$campo]['name'];
        $type = $_FILES[$campo]['type'];
        $image_name = $dirtmp . $name;
        $thumb_name = $dirtmp . 'tn_' . $name;

        //Imagen original copiada en el servidor

        $temp = $_FILES[$campo]['name'];
        //Objeto con el que trabajar� el programa
        $img = @imagecreatefromjpeg($temp) or die("No se encuentra la imagen <br>\n");

        //Para que acepte la transparencia del PNG
        imagealphablending($img, true);

        //INICIA PROCESO
        $dimensiones = getimagesize($temp); //Dimensiones originales de la imagen
        $ratio = ($dimensiones[0] / $anchura);
        $altura = round($dimensiones[1] / $ratio);
        $ratio_thumb = ($dimensiones[0] / $anchura_thumb);
        $altura_thumb = round($dimensiones[1] / $ratio_thumb);
        $image = imagecreatetruecolor($anchura, $altura); //crea la nueva imagen
        $thumb = imagecreatetruecolor($anchura_thumb, $altura_thumb); //crea la nueva imagen del thumb
        imagecopyresampled($image, $img, 0, 0, 0, 0, $anchura, $altura, $dimensiones[0], $dimensiones[1]);//reescala
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura_thumb, $altura_thumb, $dimensiones[0], $dimensiones[1]);//reescala el thumbnail

        imagejpeg($image, $image_name, 80);//mueve la imagen al server (el segundo par�metro es la calidad)
        imagejpeg($thumb, $thumb_name, 65);//mueve la imagen al server (el segundo par�metro es la calidad)
        imagedestroy($image); //destruye image
        imagedestroy($thumb); //destruye thumb
        imagedestroy($img); //destruye la imagen "origen"
        //TERMINA PROCESO
        $image_size = filesize($image_name);
        $thumb_size = filesize($thumb_name);
        $image = addslashes(fread(fopen($image_name, "rb"), $image_size)); //Arma el blob que se insertar� (formato binario)
        $thumb = addslashes(fread(fopen($thumb_name, "rb"), $thumb_size)); //Arma el blob que se insertar�


        unlink($image_name);//Borra la imagen del server una vez usada
        unlink($thumb_name);//Borra el thumbnail del server una vez usado

        // Si hay imagen los valores que regresa son:
        $_img['name'] = $image_name;
        $_img['type'] = $type;
        $_img['size'] = $image_size;
        $_img['image'] = $image;
        $_img['thumb_size'] = $thumb_size;
        $_img['thumb'] = $thumb;
    } else {
        //Pero si no hay imagen los valores que regresan son:
        $_img['name'] = NULL;
        $_img['type'] = NULL;
        $_img['size'] = NULL;
        $_img['image'] = NULL;
        $_img['thumb_size'] = NULL;
        $_img['thumb'] = NULL;
    }
    return $_img;
}

?>
<?php
function upload_image($args)
{
    # Extraemos los datos del array
    extract($args, EXTR_SKIP);
    # Le agregamos una id unica para evitar duplicado
    $filename = uniqid(microtime()) . $filename;
    # Con explore() obtenemos la extensi�n del archivo
    $ext = end(explode('.', $filename));
    # Encryptamos el nombre del archivo con md5() para evitar que el archivo tenga otra extensi�n y acortamos el nombre con substr()
    $filename = substr(md5($filename), 0, 10);
    # Le devolvemos la extensi�n al archivo
    $filename = $filename . '.' . $ext;
    # Creamos una variable con la ruta en donde estar� alojada la imagen
    $filepath = '../tmp/' . $filename;

    # Movemos el archivo temporal a donde lo queremos colocar
    move_uploaded_file($tmp_name, $filepath);
    # Le cambiamos los permisos al archivo
    chmod($filepath, 0644);
}


?>
<?php
function cambiafnormal($fecha)
{
    preg_match('/([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})/', $fecha, $mifecha);
    $lafecha = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
    return $lafecha;
}

?>
<?php
function cambiafmysql($fecha)
{
    ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
    $lafecha = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
    return $lafecha;
}

?>
<?php

/* Recibe un archivo subido con input tipo file
  y devuelve un arreglo con sus contenido binario y datos
  num = numero de archivo a subir
  campo = nombre del archivo enviado en el campo del formulario
  */
function subirArchivo($num, $campo)
{
    $name = $_FILES[$campo]['name'];
    if ($name != '') {
        $type = $_FILES[$campo]['type'];
        $size = $_FILES[$campo]['size'];

        //Archivo original en el servidor
        $temp = $_FILES[$campo]['tmp_name'];

        //Genera el blob
        $file = addslashes(fread(fopen($temp, "rb"), filesize($temp)));

        //Los valores que regresa son:
        $_file['name' . $num] = $name;
        $_file['type' . $num] = $type;
        $_file['size' . $num] = $size;
        $_file['file' . $num] = $file;
    } else {
        $_file['name' . $num] = NULL;
        $_file['type' . $num] = NULL;
        $_file['size' . $num] = NULL;
        $_file['file' . $num] = NULL;
    }
    return $_file;
}

?>
<?php
//Convierte numeros a formato Hora:minutos:segundo
function str2hora($h, $m, $s = 0)
{
    settype($h, "integer");
    settype($m, "integer");
    settype($s, "integer");
    $hora = strftime("%H:%M:%S", mktime($h, $m, $s));
    return $hora;
}

?>
<?php
//Convierte numeros a formato fecha mm-dd-aa
function calcular_edad($f_nac)
{
    $hoy = date("d-m-Y");
    $edad = strtotime($hoy) - strtotime($f_nac);
    $edad = $edad / (31536000);
    $edad = floor($edad);
    return $edad;
}

?>
<?php
// REQUIERE del archivo manejoCapas.js para controlar evento de los botones
// dibuja una tabla con botones para cambiar hora y minutos
// esta puesto de 15 en 15 minutos
function time_picker($idInput)
{
    ?>
    <div style="display:none; text-align:center; font-family:Arial, Helvetica, sans-serif; font-size:10px"
         id="<?php echo 'C' . $idInput; ?>">
        <table align="left" border="0" cellpadding="0" cellspacing="0" bordercolor="#999999" bgcolor="#CCCCCC">
            <tr>
                <td width="18" height="18"><a href="javascript: timePicker('1','1','<?php echo $idInput; ?>')"><img
                                src="../images/b_up.gif" width="18" height="18" border="0"/></a></td>
                <td width="18" height="18">
                    <div align="center"><a href="javascript: timePicker('2','15','<?php echo $idInput; ?>')"><img
                                    src="../images/b_up.gif" width="18" height="18" border="0"/></a></div>
                </td>
            </tr>
            <tr valign="middle">
                <td colspan="2" height="5" align="center"><img src="../images/b_cerrar.png"
                                                               onclick="ocultaCapa('<?php echo "C" . $idInput; ?>')"/>
                </td>

            </tr>
            <tr>
                <td width="18" height="18">
                    <div align="center"><a href="javascript: timePicker('1','-1','<?php echo $idInput; ?>')"><img
                                    src="../images/b_down.gif" width="18" height="18" border="0"/></a></div>
                </td>
                <td width="18" height="18">
                    <div align="center"><a href="javascript: timePicker('2','-15','<?php echo $idInput; ?>')"><img
                                    src="../images/b_down.gif" width="18" height="18" border="0"/></a></div>
                </td>
            </tr>
        </table>
    </div>
    <?php
} // fin de funcion time_picker()
?>
<?php
//reemplaza una cadena con acentos a una sin acentos
function replace($cadena)
{
    $cadena = str_replace("á", "a", $cadena);
    $cadena = str_replace("é", "e", $cadena);
    $cadena = str_replace("í", "i", $cadena);
    $cadena = str_replace("ó", "o", $cadena);
    $cadena = str_replace("ú", "u", $cadena);
    return $cadena;
}

?>
<?php
// Cambia todos los acentos mayusculas a minusculas
function acentos($cadena)
{
    $cadena = str_replace("Á", "á", $cadena);
    $cadena = str_replace("É", "é", $cadena);
    $cadena = str_replace("Í", "í", $cadena);
    $cadena = str_replace("Ó", "ó", $cadena);
    $cadena = str_replace("Ú", "ú", $cadena);
    $cadena = str_replace("Ý", "ý", $cadena);
    return $cadena;
}

//Devuelve una palabra sin acento inicial
function ucSinAcentos($cadena)
{
    $sub = strtolower(substr($cadena, 1));
    $sub = acentos($sub);
    $cadena[0] = str_replace("Á", "A", $cadena[0]);
    $cadena[0] = str_replace("á", "a", $cadena[0]);
    $cadena[0] = str_replace("É", "E", $cadena[0]);
    $cadena[0] = str_replace("é", "e", $cadena[0]);
    $cadena[0] = str_replace("Í", "I", $cadena[0]);
    $cadena[0] = str_replace("í", "i", $cadena[0]);
    $cadena[0] = str_replace("Ó", "O", $cadena[0]);
    $cadena[0] = str_replace("ó", "o", $cadena[0]);
    $cadena[0] = str_replace("Ú", "U", $cadena[0]);
    $cadena[0] = str_replace("ú", "u", $cadena[0]);
    $cadena[0] = str_replace("Ý", "Y", $cadena[0]);
    $cadena[0] = str_replace("ý", "y", $cadena[0]);
    $regresa = $cadena[0] . $sub;
    $regresa = ucfirst($regresa);
    return $regresa;
}

// convierte a minusculas, con iniciales en mayusculas, incluye acentos
function ucMinusc($cadena)
{
// $loc_original=setlocale(LC_CTYPE,"0"); // valor actual de codificacion
    $nombre = trim($cadena);
// separar la frase por cualquier numero de comas o caracteres de espacio,
// incluyendo " ", \r, \t, \n y \f
    $palabras = preg_split("/[\s,]+/", $nombre);
    $pp = "";
    $i = 0;
    foreach ($palabras as $palabra) {
        $palabra = ucSinAcentos($palabra);
        $pp[$i] = $palabra;
        $i++;
    }
    $nombre = implode(" ", $pp);
    return $nombre;
}

?>
<?php
function dameofertaalumno($IDalumno, $IDTipo = "", $IDoNombre = "")
{
    if ($IDTipo == "") {
        $SQL = "SELECT oa.IDciclo,oa.siglas FROM oferta_academica oa INNER JOIN alumnos_movimientos am ON am.IDciclo = oa.IDciclo
			WHERE am.IDsituacion IN (SELECT IDsituacion FROM tipos_movimientos WHERE enlista = 1) AND am.IDalumno = " . $IDalumno . "";
    } else {
        $SQL = "SELECT oa.IDciclo,oa.siglas FROM oferta_academica oa INNER JOIN alumnos_movimientos am ON am.IDciclo = oa.IDciclo
			INNER JOIN tipo_of_ac toa ON toa.IDTipo = oa.IDTipo
			WHERE am.IDsituacion IN (SELECT IDsituacion FROM tipos_movimientos WHERE enlista = 1) AND am.IDalumno = " . $IDalumno . "
			AND toa.IDTipo = " . $IDTipo . "";
    }
    //echo $SQL;
    $planquery = mysql_query($SQL);
    $planrs = mysql_fetch_array($planquery);
    $myPlan = "";
    if ($IDoNombre == "") {
        $myPlan = $planrs['siglas'];
    } else {
        $myPlan = $planrs['IDciclo'];
    }
    mysql_free_result($planquery);
    $plan = trim($myPlan);
    return $plan;
}

?>
<?php
function checamovimientosalumno($IDalumno, $IDciclo)
{
    $SQL = "SELECT * FROM alumnos_movimientos
				WHERE IDciclo=" . $IDciclo . " AND IDalumno=" . $IDalumno . "
				AND (IDsituacion=11 OR IDsituacion=17)";
    // echo $SQL;
    $movequery = mysql_query($SQL);
    $found = mysql_num_rows($movequery);
    //echo $found;
    if ($found > 0) {
        //$movers = mysql_fetch_array($movequery);
        //$myMove = "";
        // $myMove = $movers['IDsituacion'];
        $movimientos = " **";
    } else {
        $movimientos = "";
    }
    mysql_free_result($movequery);
    //echo $movimientos;
    return $movimientos;
}

?>
<?php
function ponerhorario($IDcarga, $band = "")
{
    if ($band == "") {
        $SQL = "SELECT distinct hora_inicial, hora_final, horario  FROM cargas_horarios_salones WHERE IDcarga=" . $IDcarga . " AND horario=1 order by hora_inicial";
    } else {
        $SQL = "SELECT distinct hora_inicial, hora_final, horario  FROM cargas_horarios_salones WHERE IDcarga=" . $IDcarga . " order by hora_inicial";
    }
    //echo $SQL;
    $query = mysql_query($SQL);
    $total = mysql_num_rows($query);
    //echo $total;
    if ($total > 0) {
        while ($rs = mysql_fetch_array($query)) {
            // echo $rs['horario']."<br>";
            $horario_dias = $horario_dias . $rs['hora_inicial'] . "-" . $rs['hora_final'] . ";";
        }
        mysql_free_result($query);
        $horario_dias[strlen($horario_dias) - 1] = ' '; //sustituir la ultima ',' por vac�o
        //echo $horario_dias;
        $dias = "";
        $SQL = "SELECT distinct dia FROM cargas_horarios_salones WHERE IDcarga=" . $IDcarga . "";
        //echo $SQL;
        $query = mysql_query($SQL);
        while ($rs = mysql_fetch_array($query)) {
            $dias = $dias . $rs['dia'] . ",";
            //echo "<br>".$rs['dia'];
        }
        $dias[strlen($dias) - 1] = ' '; //sustituir la ultima ',' por vac�o
        //echo "Dias:".$dias;
        mysql_free_result($query);
        $cadena = $dias . " de " . $horario_dias;
    } else {//fin total
        $cadena = "";
    }
    return $cadena;
}

?>
<?php
function poner_matricula($IDalumno, $completa = 0)
{
    // echo $IDalumno;
    $SQLM = "SELECT * FROM matriculas WHERE IDalumno=" . $IDalumno . "";
    //echo $SQLM;
    $query = mysql_query($SQLM);
    $rs = mysql_fetch_array($query);
    if (strlen($rs['matricula_oficial']) > 0) {
        //echo $rs['matricula_oficial'];
        $matricula = $rs['matricula_oficial'];
    } else {
        if (strlen($rs['matricula_codi']) > 0) {
            ///echo $rs['matricula_codi'];
            $div = explode("-", $rs['matricula_codi']);
            //echo count($div);
            if (count($div) == 3 && $completa == 0) {
                /*echo $div[0];
			echo $div[1];
			echo $div[2];*/
                $matricula = $div[1] . "-" . $div[2];
                //echo $matricula;
            } else {
                $matricula = $rs['matricula_codi'];
            }
        } else {
            //echo $rs['matricula_temporal'];
            $div = explode("-", $rs['matricula_temporal']);
            //echo count($div);
            if (count($div) == 3 && $completa == 0) {
                /*echo $div[0];
			echo $div[1];
			echo $div[2];*/
                $matricula = $div[1] . "-" . $div[2];
                //echo $matricula;
            } else {
                $matricula = $rs['matricula_temporal'];
            }
        }
    }
    mysql_free_result($query);
    return $matricula;
}

?>
<?php
/*!
  @function num2letras ()
  @abstract Dado un n?mero lo devuelve escrito.
  @param $num number - N?mero a convertir.
  @param $fem bool - Forma femenina (true) o no (false).
  @param $dec bool - Con decimales (true) o no (false).
  @result string - Devuelve el n?mero escrito en letra.

*/
function num2letras($num, $fem = true, $dec = true)
{
//if (strlen($num) > 14) die("El n?mero introducido es demasiado grande");
    $matuni[2] = "dos";
    $matuni[3] = "tres";
    $matuni[4] = "cuatro";
    $matuni[5] = "cinco";
    $matuni[6] = "seis";
    $matuni[7] = "siete";
    $matuni[8] = "ocho";
    $matuni[9] = "nueve";
    $matuni[10] = "diez";
    $matuni[11] = "once";
    $matuni[12] = "doce";
    $matuni[13] = "trece";
    $matuni[14] = "catorce";
    $matuni[15] = "quince";
    $matuni[16] = "dieciséis";
    $matuni[17] = "diecisiete";
    $matuni[18] = "dieciocho";
    $matuni[19] = "diecinueve";
    $matuni[20] = "veinte";
    $matunisub[1] = "uno";
    $matunisub[2] = "dós";
    $matunisub[3] = "trés";
    $matunisub[4] = "cuatro";
    $matunisub[5] = "cinco";
    $matunisub[6] = "séis";
    $matunisub[7] = "siete";
    $matunisub[8] = "ocho";
    $matunisub[9] = "nueve";
    $matdec[2] = "veint";
    $matdec[3] = "treinta";
    $matdec[4] = "cuarenta";
    $matdec[5] = "cincuenta";
    $matdec[6] = "sesenta";
    $matdec[7] = "setenta";
    $matdec[8] = "ochenta";
    $matdec[9] = "noventa";
    $matsub[3] = 'mill';
    $matsub[5] = 'bill';
    $matsub[7] = 'mill';
    $matsub[9] = 'trill';
    $matsub[11] = 'mill';
    $matsub[13] = 'bill';
    $matsub[15] = 'mill';
    $matmil[4] = 'millones';
    $matmil[6] = 'billones';
    $matmil[7] = 'de billones';
    $matmil[8] = 'millones de billones';
    $matmil[10] = 'trillones';
    $matmil[11] = 'de trillones';
    $matmil[12] = 'millones de trillones';
    $matmil[13] = 'de trillones';
    $matmil[14] = 'billones de trillones';
    $matmil[15] = 'de billones de trillones';
    $matmil[16] = 'millones de billones de trillones';
    $num = trim((string)@$num);
    if ($num[0] == '-') {
        $neg = 'menos ';
        $num = substr($num, 1);
    } else
        $neg = '';
    while ($num[0] == '0') $num = substr($num, 1);
    if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
    $zeros = true;
    $punt = false;
    $ent = '';
    $fra = '';
    for ($c = 0; $c < strlen($num); $c++) {
        $n = $num[$c];
        if (!(strpos(".,'''", $n) === false)) {
            if ($punt) break;
            else {
                $punt = true;
                continue;
            }
        } elseif (!(strpos('0123456789', $n) === false)) {
            if ($punt) {
                if ($n != '0') $zeros = false;
                $fra .= $n;
            } else

                $ent .= $n;
        } else
            break;
    }
    $ent = '     ' . $ent;
    if ($dec and $fra and !$zeros) {
        $fin = ' coma';
        for ($n = 0; $n < strlen($fra); $n++) {
            if (($s = $fra[$n]) == '0')
                $fin .= 'cero';
            elseif ($s == '1')
                $fin .= $fem ? ' uno' : ' un';//modif una por uno
            else
                $fin .= ' ' . $matuni[$s];
        }
    } else
        $fin = '';
    if ((int)$ent === 0) return 'Cero' . $fin;
    $tex = '';
    $sub = 0;
    $mils = 0;
    $neutro = false;
    while (($num = substr($ent, -3)) != '   ') {
        $ent = substr($ent, 0, -3);
        if (++$sub < 3 and $fem) {
            $matuni[1] = 'uno';//modif una por uno
            $subcent = 'os';
        } else {
            $matuni[1] = $neutro ? 'un' : 'uno';
            $subcent = 'os';
        }
        $t = '';
        $n2 = substr($num, 1);
        if ($n2 == '00') {
        } elseif ($n2 < 21)
            $t = ' ' . $matuni[(int)$n2];
        elseif ($n2 < 30) {
            $n3 = $num[2];
            if ($n3 != 0) $t = 'i' . $matunisub[$n3];//en lugar de hiba $matuni
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        } else {
            $n3 = $num[2];
            if ($n3 != 0) $t = ' y ' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }
        $n = $num[0];
        if ($n == 1) {
            $t = ' ciento' . $t;
        } elseif ($n == 5) {
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
        } elseif ($n != 0) {
            $t = ' ' . $matuni[$n] . 'cient' . $subcent . $t;
        }
        if ($sub == 1) {
        } elseif (!isset($matsub[$sub])) {
            if ($num == 1) {
                $t = ' mil';
            } elseif ($num > 1) {
                $t .= ' mil';
            }
        } elseif ($num == 1) {
            $t .= ' ' . $matsub[$sub] . '?n';
        } elseif ($num > 1) {
            $t .= ' ' . $matsub[$sub] . 'ones';
        }
        if ($num == '000') $mils++;
        elseif ($mils != 0) {
            if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
            $mils = 0;
        }
        $neutro = true;
        $tex = $t . $tex;
    }
    $tex = $neg . substr($tex, 1) . $fin;
    return ucfirst($tex);
}

?>
<?php
function numes2str($numes)
{
    switch ($numes) {
        case 1:
            return "enero";
            break;
        case 2:
            return "febrero";
            break;
        case 3:
            return "marzo";
            break;
        case 4:
            return "abril";
            break;
        case 5:
            return "mayo";
            break;
        case 6:
            return "junio";
            break;
        case 7:
            return "julio";
            break;
        case 8:
            return "agosto";
            break;
        case 9:
            return "septiembre";
            break;
        case 10:
            return "octubre";
            break;
        case 11:
            return "noviembre";
            break;
        case 12:
            return "diciembre";
    }
}

function numSemestre2letras($semestre)
{
    switch ($semestre) {
        case 1:
            return "PRIMER SEMESTRE";
            break;
        case 2:
            return "SEGUNDO SEMESTRE";
            break;
        case 3:
            return "TERCER SEMESTRE";
            break;
        case 4:
            return "CUARTO SEMESTRE";
            break;
        case 5:
            return "QUINTO SEMESTRE";
            break;
        case 6:
            return "SEXTO SEMESTRE";

    }
}

function numSemestre($semestre)
{
    switch ($semestre) {
        case 1:
            return "1°";
            break;
        case 2:
            return "1�";
            break;
        case 3:
            return "2°";
            break;
        case 4:
            return "2�";
            break;
        case 5:
            return "3°";
            break;
        case 6:
            return "3�";

    }
}

?>
<?php
function dameperiodoanterior($IDalumno, $IDperiodo, $IDciclo)
{
    // devuelve el periodo inmediato anterior donde el alumno estuvo inscrito en el tipo de oferta academica dada
    //echo "-".$IDperiodo."-";
    $fSQL = "SELECT IDtipo FROM oferta_academica WHERE IDciclo = " . $IDciclo . "";
    $fquery = mysql_query($fSQL);
    $frs = mysql_fetch_array($fquery);
    $IDtipo = $frs['IDtipo'];
    mysql_free_result($fquery);
    $pSQL = "SELECT distinct(IDperiodo) from inscritos_ids where idalumno = " . $IDalumno . " AND IDtipo = " . $IDtipo . " Order By Inicial asc";
    //echo $pSQL;
    $pquery = mysql_query($pSQL);
    $prows = mysql_num_rows($pquery);
    if ($prows <= 0) { // si no hay periodos anteriores
        $periodoanterior = 0;
        //echo "a";
    } else {
        if ($prows == 1) {
            $prs = mysql_fetch_array($pquery);
            if ($prs['IDperiodo'] == $IDperiodo) {  // si hay 1 periodo donde se inscribi� pero es el mismo entonces no existe periodo anterior
                $periodoanterior = 0;
                //echo "b";
            } else {
                $periodoanterior = $prs['IDperiodo'];
                //echo "c";
            }
        } else {
            $pc = -1;
            $pfound = 0;
            while ($prs = mysql_fetch_array($pquery)) {
                $pc = $pc + 1;
                $idsp[$pc] = $prs['IDperiodo'];
                if ($prs['IDperiodo'] == $IDperiodo) {
                    $ppa = $pc;
                    $pfound = 1;
                } // Guardo la posicion del periodo que se le envia si es que lo encuentra
            }
            if ($pfound) { // si lo encontro hay que ver que no sea el primero, si es el primero entonces no hay anterior
                if ($ppa == 0) {
                    $periodoanterior = 0;//echo "d";
                } else {
                    $periodoanterior = $idsp[$ppa - 1];//echo "e";
                }
            } else {
                $periodoanterior = $idsp[$pc];
            }
            // si no encuentra el periodo dado entonces el ultimo registro es el periodo anterior
            // si lo encuentra, entonces el periodo anterior es el de la posicion anterior en el arreglo
        }
    }
    mysql_free_result($pquery);
    return $periodoanterior;
}

?>
<?php
function checarevaluacion($IDalumno, $IDperiodo, $IDciclo)
{
    // Obtener tipo de oferta del $IDciclo

    $IDperiodo_anterior = dameperiodoanterior($IDalumno, $IDperiodo, $IDciclo);
    if (($IDperiodo_anterior == 0) or ($IDperiodo_anterior == "")) {  // Si no hay otros periodos entonces permito evaluarporque no existe uno anterior
        $permitirevaluar = 1;
    } else {
        $IDperiodoAnterior = $IDperiodo_anterior;
        //echo "<br>anterior: ".$IDperiodoAnterior."<br>";
        $fSQL = "SELECT IDcarga FROM inscritos_ids WHERE IDalumno = " . $IDalumno . " and IDperiodo = " . $IDperiodoAnterior . ""; // Obtener cargas donde debe haber evaluado
        //echo $fSQL."<br>";
        $fquery = mysql_query($fSQL);
        $frows = mysql_num_rows($fquery);
        if ($frows > 0) {
            $numerocargas = $frows;
            $IDcargas = array();
            while ($frs = mysql_fetch_array($fquery)) {
                $IDcargas[] = $frs['IDcarga'];
            }
            $cargas = implode(",", $IDcargas);
            mysql_free_result($fquery);
            DBConn("localhost", "sifeenc", "sife", "sife");
            // verificar cuales de las cargas inscritas ya fueron evaluadas en sifenc
            $fSQL = "SELECT * FROM status_general WHERE Status = 1 AND IDalumno = " . $IDalumno . " AND IDcarga IN (" . $cargas . ")";
            // echo $fSQL;
            $fquery = mysql_query($fSQL);
            $numerocargasevaluadas = mysql_num_rows($fquery);
            mysql_free_result($fquery);
            //	  echo $numerocargasevaluadas."-".$numerocargas;
            if ($numerocargasevaluadas < $numerocargas) {
                $permitirevaluar = 0;
            } else {
                $permitirevaluar = 1;
            }
        } else {
            mysql_free_result($fquery);
            $permitirevaluar = 1;
        }
    }
    return $permitirevaluar;
}

function check_email_address($email)
{
    // First, we check that there's one @ symbol, and that the lengths are right
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
        // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
        return false;
    }
    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            return false;
        }
    }
    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
        // Check if domain is IP. If not, it should be valid domain name
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                return false;
            }
        }
    }
    return true;
}

function sendmail($subject, $body, $to)
{
    $mailorigen = "educacion@www.uady.mx";
    $servidor = "http://localhost/";
    $fecha = date("d-M-y H:i");
    $contenido = $body;
    $header = "From:" . $mailorigen . "\n";
    $header .= "Reply-To:" . $to . "\n";
    $header .= "X-Mailer:PHP/" . phpversion() . "\n";
    $header .= "Mime-Version: 1.0\n";
    $header .= "Content-Type: text/plain";

    mail($to, $subject, utf8_decode($contenido), $header);
}

function validaCalificacionMEFI($IDalumno, $IDcarga, $IDperiodo, $cal, $tipoExamen = 1, $tipoOferta = 1)
{
    //tipoexamen= 1-ordinario,2-extraodinario

    $Calificacion_Minima_Aprobatoria = 70;
    $Asistencia_Minima_Aprobatoria = 100;


    switch ($cal) {

        case 101:
            return array("A", "APROBADO");
        case 102:
            return array("R", "REPROBADO");
        case 103:
            return array(" ", "  Desertó");

        default :
            if ($cal != NULL) {
                /*$SQL="SELECT PORC_ASIST FROM alumnos_asistencias WHERE IDalumno=".$IDalumno." AND IDcarga=".$IDcarga." AND IDperiodo=".$IDperiodo."";
			$r=mysql_query($SQL);
			$d=mysql_fetch_array($r);*/
                $asistencia = 100;
                if ($asistencia) {
                    if ($cal >= $Calificacion_Minima_Aprobatoria && $asistencia >= $Asistencia_Minima_Aprobatoria)
                        return array($cal, "APROBADO");
                    else
                        if ($cal < $Calificacion_Minima_Aprobatoria && $asistencia >= $Asistencia_Minima_Aprobatoria)
                            return array($cal, "REPROBADO");
                        else
                            if ($cal >= $Calificacion_Minima_Aprobatoria && $asistencia < $Asistencia_Minima_Aprobatoria)
                                return array("Sin Derecho", "REPROBADO");
                } else
                    if ($cal >= $Calificacion_Minima_Aprobatoria) return array($cal, "APROBADO"); else return array($cal, "REPROBADO");
            } else
                return array(0, "REPROBADO");
    }
}

function validaCalificacion($IDalumno, $IDcarga, $IDperiodo, $cal, $tipoExamen = 1, $tipoOferta = 1)
{
    //tipoexamen= 1-ordinario,2-extraodinario
    if ($tipoOferta == 2 || $tipoOferta == 3 || $tipoOferta == 6)
        $Calificacion_Minima_Aprobatoria = 80;
    else
        $Calificacion_Minima_Aprobatoria = 60;
    if ($tipoExamen == 1)
        $Asistencia_Minima_Aprobatoria = 80;
    if ($tipoExamen == 2)
        $Asistencia_Minima_Aprobatoria = 40;
    switch ($cal) {
        /*case 101: return array("No presentó","REPROBADO");
	  case 110: return array("Sin Derecho","REPROBADO");
	  case 111: return array("A","APROBADO");
	  case 112: return array("R","REPROBADO");*/
        case 101:
            return array("A", "APROBADO");
        case 102:
            return array("R", "REPROBADO");
        case 103:
            return array(" ", "  Desertó");

        default :
            if ($cal != NULL) {
                /*$SQL="SELECT PORC_ASIST FROM alumnos_asistencias WHERE IDalumno=".$IDalumno." AND IDcarga=".$IDcarga." AND IDperiodo=".$IDperiodo."";
			$r=mysql_query($SQL);
			$d=mysql_fetch_array($r);*/
                $asistencia = round(100 * asistenciaTotal($IDalumno, $IDcarga, $IDperiodo));//round($d['PORC_ASIST']);
                if ($asistencia) {
                    if ($cal >= $Calificacion_Minima_Aprobatoria && $asistencia >= $Asistencia_Minima_Aprobatoria)
                        return array($cal, "APROBADO");
                    else
                        if ($cal < $Calificacion_Minima_Aprobatoria && $asistencia >= $Asistencia_Minima_Aprobatoria)
                            return array($cal, "REPROBADO");
                        else
                            if ($cal >= $Calificacion_Minima_Aprobatoria && $asistencia < $Asistencia_Minima_Aprobatoria)
                                return array("Sin Derecho", "REPROBADO");
                } else
                    if ($cal >= $Calificacion_Minima_Aprobatoria) return array($cal, "APROBADO"); else return array($cal, "REPROBADO");
            } else
                return array(0, "REPROBADO");
    }
}

function validaCalificacionExtra($IDalumno, $IDcarga, $IDperiodo, $cal, $tipoExamen = 1, $tipoOferta = 1)
{
    //tipoexamen= 1-ordinario,2-extraodinario
    if ($tipoOferta == 2 || $tipoOferta == 3 || $tipoOferta == 6)
        $Calificacion_Minima_Aprobatoria = 80;
    else
        $Calificacion_Minima_Aprobatoria = 60;
    if ($tipoExamen == 1)
        $Asistencia_Minima_Aprobatoria = 80;
    if ($tipoExamen == 2)
        $Asistencia_Minima_Aprobatoria = 40;
    switch ($cal) {
        /*case 101: return array("No presentó","REPROBADO");
	  case 110: return array("Sin Derecho","REPROBADO");
	  case 111: return array("A","APROBADO");
	  case 112: return array("R","REPROBADO");*/
        case 101:
            return array("A", "APROBADO");
        case 102:
            return array("R", "REPROBADO");
        case 103:
            return array(" ", "  Desertó");

        default :
            if ($cal != NULL || $cal == 0) {
                /*$SQL="SELECT PORC_ASIST FROM alumnos_asistencias WHERE IDalumno=".$IDalumno." AND IDcarga=".$IDcarga." AND IDperiodo=".$IDperiodo."";
			$r=mysql_query($SQL);
			$d=mysql_fetch_array($r);*/
                $asistencia = round(100 * asistenciaTotal($IDalumno, $IDcarga, $IDperiodo));//round($d['PORC_ASIST']);
                if ($asistencia) {
                    if ($cal >= $Calificacion_Minima_Aprobatoria && $asistencia >= $Asistencia_Minima_Aprobatoria)
                        return array($cal, "APROBADO");
                    else
                        if ($cal < $Calificacion_Minima_Aprobatoria && $asistencia >= $Asistencia_Minima_Aprobatoria)
                            return array($cal, "REPROBADO");
                        else
                            if ($cal >= $Calificacion_Minima_Aprobatoria && $asistencia < $Asistencia_Minima_Aprobatoria)
                                return array("Sin Derecho", "REPROBADO");
                } else
                    if ($cal >= $Calificacion_Minima_Aprobatoria) return array($cal, "APROBADO"); else return array($cal, "REPROBADO");
            } else
                return array("S/E", "S/E");
    }
}

function asistenciaTotal($IDalumno, $IDcarga, $IDperiodo)
{
    $SQL = "SELECT fecha_inicial,fecha_final FROM periodo_escolar WHERE IDperiodo=" . $IDperiodo . "";
    $resultado = mysql_query($SQL);
    $datos = mysql_fetch_array($resultado);
    $fecha_i = $datos['fecha_inicial'];
    $fecha_f = $datos['fecha_final'];
    mysql_free_result($resultado);
    $SQL = "SELECT * FROM faltas_fechas f WHERE IDcarga=" . $IDcarga . " AND fecha>='" . $fecha_i . "' AND fecha<='" . $fecha_f . "'";
    $resultado = mysql_query($SQL);
    $nLista = mysql_num_rows($resultado);
    $nLista = $nLista ? $nLista : 1;
    mysql_free_result($resultado);
    $SQL = "SELECT * 
			FROM faltas_alumnos fa 
			INNER JOIN faltas_fechas ff ON fa.IDfecha=ff.IDfecha AND fa.IDalumno=" . $IDalumno . " AND ff.IDcarga=" . $IDcarga . " AND status=1";
    $resultado = mysql_query($SQL);
    $nFaltas = mysql_num_rows($resultado);
    mysql_free_result($resultado);
    return ($nLista - $nFaltas) / $nLista;
}

function calificacionFinal($IDalumno, $IDperiodo, $IDasignatura, $IDcarga, $fecha, $ordinario, $creditos, $IDtipoOferta, $idPlan)
{
    //echo $idPlan;
    $tipo = "ORD";
    if ($idPlan == 1)
        $calificacion = validaCalificacion($IDalumno, $IDcarga, $IDperiodo, $ordinario, 1, $IDtipoOferta);
    if ($idPlan == 2)
        $calificacion = validaCalificacionMEFI($IDalumno, $IDcarga, $IDperiodo, $ordinario, 1, $IDtipoOferta);

    $estado = $calificacion[1];
    $cal = $calificacion[0];
    if ($cal == "S/E") {
        $creditos = 0;
        $tipo = "---";
    }
    if ($estado == "REPROBADO") {
        $calificacion = calExt($IDasignatura, $IDperiodo, $IDalumno, $cal, $fecha, $idPlan);
        $tipo = $calificacion['tipo'];
        $cal = $calificacion['calificacion'];
        $IDasignatura = $calificacion['asignatura'];
        $fecha = $calificacion['fecha'];
        $IDalumno = $calificacion['alumno'];
        $estado = $calificacion['estado'] ? $calificacion['estado'] : $estado;
        if ($estado == "REPROBADO" || $estado == "---")
            $creditos = 0;
    }
    return array("calificacion" => $cal, "creditos" => $creditos, "tipo" => $tipo, "estado" => $estado, "fecha" => $fecha, "asignatura" => $IDasignatura, "alumno" => $IDalumno);
}

function calExt($IDasignatura, $IDperiodo, $IDalumno, $cal, $fecha = "", $idPlan)
{
    $calificacion['calificacion'] = $cal;
    $calificacion['tipo'] = "ORD";
    $calificacion['fecha'] = $fecha;
    $MESES = array(1 => "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
    //$SQL="SELECT IDexamen,fecha FROM fechas_examenes_extras WHERE IDasignatura=".$IDasignatura." AND IDperiodo=".$IDperiodo." ORDER BY fecha";
    //$resultado=mysql_query($SQL);
    //while($datos=mysql_fetch_array($resultado))
    //{
    //$mkfecha=strtotime($datos['fecha']."+6 day");
    $hoy = strtotime("now");
    //$SQL="SELECT calificacion FROM cargas_historial_global_extraordinarios WHERE IDexamen=".$datos['IDexamen']." AND IDalumno=".$IDalumno."";
    if ($idPlan == 1) {
        $SQL = "SELECT extraordinario,fecha FROM cargas_historial_global_extraordinarios WHERE idAlumno=" . $IDalumno . " AND idAsignatura=" . $IDasignatura . " ORDER BY fecha DESC";
    }

    if ($idPlan == 2) {
        $SQL = "SELECT acreditacion as extraordinario, fecha, l.nombre AS libro,pagina
FROM cargas_historial_global_acreditacion chge
INNER JOIN libros l ON l.IDlibro = chge.idLibro
WHERE idAlumno=" . $IDalumno . " AND idAsignatura=" . $IDasignatura . " ORDER BY fecha DESC";
    }

    $r = mysql_query($SQL);
    $d = mysql_fetch_array($r);
    //$mkfecha=strtotime($r['fecha']."+6 day");
    if (mysql_num_rows($r) > 0) {
        $c = califica($d['extraordinario'], $idPlan);
        //$fecha=date("j/",$mkfecha).$MESES[date("n",$mkfecha)].date("/Y",$mkfecha);
        $calificacion['fecha'] = $fecha;
        $calificacion['asignatura'] = $IDasignatura;
        $calificacion['tipo'] = "EXT";
        $calificacion['calificacion'] = $c[0];
        $calificacion['estado'] = $c[1];
    }
    mysql_free_result($r);
    //}
    //mysql_free_result($resultado);
    return $calificacion;
}

function califica($cal, $idPlan)
{

    if ($idPlan == 1)
        $califNoBGU = 60;
    if ($idPlan == 2)
        $califNoBGU = 70;
    switch ($cal) {
        /* case 101: return array("No presentó","REPROBADO");
	  case 110: return array("Sin Derecho","REPROBADO");
	  case 111: return array("A","APROBADO");
	  case 112: return array("R","REPROBADO");*/
        case 101:
            return array("A", "APROBADO");
        case 102:
            return array("R", "REPROBADO");
        default:
            if ($cal) {
                if ($cal >= $califNoBGU)
                    return array($cal, "APROBADO");
                else
                    return array($cal, "REPROBADO");
            } else
                return array("S/E", "---");
    }
}

function calculaPromedio($IDalumno, $IDciclo)
{
    $suma = 0;
    $num_materias = 0;
    $fechas_se = 0;
    $calif_se = 0;
    $creditos = 0;
    $fecha_r = "2008-02-01";
    $SQL = "SELECT DISTINCT(chg.idAsignatura),chg.idAlumno,m.nombre,chg.ordinario,m.creditos,t.nombre as tipo, l.nombre as libro, chg.pagina,chg.fecha,t.idTipo
FROM cargas_historial_global_ordinarios chg 
INNER JOIN alumno_cicloescolar ac ON ac.idPeriodo =chg.idPeriodo AND ac.movimiento NOT IN ('Baja') AND  ac.idAlumno=" . $IDalumno . "
INNER JOIN asignaturas roa ON chg.idAsignatura=roa.idAsignatura 
INNER join tipoasignatura t ON t.idTipo=roa.idTipo
INNER JOIN materias m ON roa.idMateria=m.idMateria
INNER JOIN libros l ON l.idLibro=chg.idLibro
WHERE chg.idAlumno=" . $IDalumno . " ORDER BY roa.ordenar,m.nombre asc";
    $resultado = mysql_query($SQL);
    while ($datos = mysql_fetch_array($resultado)) {
        $calificacion = calificacionFinal($datos['idAlumno'], 0, $datos['idAsignatura'], 0, $datos['fecha'], $datos['ordinario'], $datos['creditos'], 0);
        if ($calificacion['calificacion'] != "S/E" && $calificacion['fecha'] != "S/E" && strtotime($calificacion['fecha']) <= strtotime('now')) {
            if (is_numeric($calificacion['calificacion'])) {
                $calificacion['calificacion'] . "<br/>";
                $suma += $calificacion['calificacion'];
                $num_materias++;
                $creditos += $calificacion['creditos'];
                //echo "agrega ";
            } else
                if ($calificacion['calificacion'] != "A" && $calificacion['calificacion'] != "R") {
                    $num_materias++;
                    //echo "agrega ";
                }
            if ($calificacion['calificacion'] == "A")
                $creditos += $calificacion['creditos'];
            //echo "IDtipo".$datos['IDtipo']." tipo=".$datos['tipo']." fechas= ".strtotime($calificacion['fecha'])." - ".strtotime($fecha_r)." estado=".$calificacion['estado']."<br>";
            if ($datos['idTipo'] == 1 && $datos['tipo'] == "Optativa" && strtotime($calificacion['fecha']) < strtotime($fecha_r) && $calificacion['estado'] == "REPROBADO") {
                $suma -= $calificacion['calificacion'];
                $num_materias--;
            }
        }
        if ($calificacion['calificacion'] == "S/E")
            $calif_se++;
        if (!$calificacion['fecha'] || $calificacion['fecha'] == "S/E")
            $fechas_se++;
        //echo $datos['tipo']."   --- "; print_r($calificacion);echo "<br>";
    }
    //echo $suma." / ".$num_materias." = ".round($suma/$num_materias,2)."<br>calificaiones S/E = ".$calif_se."<br>de las cuales hay ".$fechas_se." fechas S/E";
    $num_materias = ($num_materias) ? $num_materias : 1;
    return array("promedio" => round($suma / $num_materias, 2), "cal_se" => $calif_se, "fechas_se" => $fechas_se, "creditos" => $creditos);
}

?>
<?php
function calculaPromedioGrados($IDalumno, $semestres)
{
    $suma = 0;
    $num_materias = 0;
    $fechas_se = 0;
    $calif_se = 0;
    $creditos = 0;
    $fecha_r = "2008-02-01";
    $SQL = "SELECT DISTINCT(chg.idAsignatura),chg.idAlumno,m.nombre,chg.ordinario,m.creditos,t.nombre as tipo, l.nombre as libro, chg.pagina,chg.fecha,roa.idPlan 
FROM cargas_historial_global_ordinarios chg 
LEFT JOIN alumno_cicloescolar ac ON ac.idAlumno=chg.idAlumno AND ac.movimiento NOT IN ('Baja') AND ac.idPeriodo=chg.idPeriodo
INNER JOIN asignaturas roa ON chg.idAsignatura=roa.idAsignatura 
INNER join tipoasignatura t ON t.idTipo=roa.idTipo
INNER JOIN materias m ON roa.idMateria=m.idMateria
INNER JOIN libros l ON l.idLibro=chg.idLibro
WHERE chg.idAlumno=" . $IDalumno . " AND roa.semestres IN (" . $semestres . ") AND roa.idAsignatura NOT IN(99,113) 
AND ac.movimiento NOT IN ('Baja') ORDER BY roa.ordenar,m.nombre asc";
    $resultado = mysql_query($SQL);
    while ($datos = mysql_fetch_array($resultado)) {
        $calificacion = calificacionFinal($datos['idAlumno'], 0, $datos['idAsignatura'], 0, $datos['fecha'], $datos['ordinario'], $datos['creditos'], 0, $datos['idPlan']);
        if ($calificacion['calificacion'] != "S/E" && $calificacion['fecha'] != "S/E" && strtotime($calificacion['fecha']) <= strtotime('now')) {
            if (is_numeric($calificacion['calificacion']) && $datos['idAsignatura'] != 99) {
                $calificacion['calificacion'] . "<br/>";
                $suma += $calificacion['calificacion'];
                $num_materias++;
                $creditos += $calificacion['creditos'];
                //echo "agrega ";
            } else
                if ($calificacion['calificacion'] != "A" && $calificacion['calificacion'] != "R") {
                    $num_materias++;
                    //echo "agrega ";
                }

            if ($calificacion['calificacion'] == "A")
                $creditos += $calificacion['creditos'];
            //echo "IDtipo".$datos['IDtipo']." tipo=".$datos['tipo']." fechas= ".strtotime($calificacion['fecha'])." - ".strtotime($fecha_r)." estado=".$calificacion['estado']."<br>";
            if ($datos['IDtipo'] == 1 && $datos['tipo'] == "Optativa" && strtotime($calificacion['fecha']) < strtotime($fecha_r) && $calificacion['estado'] == "REPROBADO") {
                $suma -= $calificacion['calificacion'];
                $num_materias--;
            }
        }
        if ($calificacion['calificacion'] == "S/E")
            $calif_se++;
        if (!$calificacion['fecha'] || $calificacion['fecha'] == "S/E")
            $fechas_se++;
        //echo $datos['tipo']."   --- "; print_r($calificacion);echo "<br>";
    }
    //echo $suma." / ".$num_materias." = ".round($suma/$num_materias,2)."<br>calificaiones S/E = ".$calif_se."<br>de las cuales hay ".$fechas_se." fechas S/E";
    $num_materias = ($num_materias) ? $num_materias : 1;
    return array("promedio" => round($suma / $num_materias, 2), "cal_se" => $calif_se, "fechas_se" => $fechas_se, "creditos" => $creditos);
}

?>
<?php
function numGrados($semestre)
{
    switch ($semestre) {
        case 1:
            return "primer";
            break;
        case 2:
            return "segundo";
            break;
        case 3:
            return "tercer";
            break;
        case 4:
            return "cuarto";
            break;
        case 5:
            return "quinto";
            break;
        case 6:
            return "sexto";

    }
}

?>
<?php
function semestre($IDalumno, $IDciclo, $IDperiodo)
{
    $SQL = "Select * FROM oferta_academica Where IDciclo = " . $IDciclo . "";
    $query = mysql_query($SQL);
    if (mysql_num_rows($query) > 0) {
        $rs = mysql_fetch_array($query);
        $credplan = $rs['creditos_totales'];
        $totalsem = $rs['semestres'];
        if (($totalsem == 0) or ($totalsem == "")) {
            $totalsem = 8;
        }
        mysql_free_result($query);
        // ahora obtenemos los creditos aprobados por el alumno
        $cap = calculaPromedio($IDalumno, $IDciclo);
        $credaprob = $cap['creditos'];
        $SQL = "SELECT sum(creditos) as creditos FROM inscritos_lite Where IDalumno = " . $IDalumno . " and IDperiodo = " . $IDperiodo . "";
        //echo $SQL;
        $query = mysql_query($SQL);
        if (mysql_num_rows($query) > 0) {
            $rs = mysql_fetch_array($query);
            $credinsc = $rs['creditos'];
        } else {
            $credinsc = 0;
        }
        mysql_free_result($query);

        // inicia calculos (2 * CredAprob + CredInsc + 1) / (2 * CredPerLect)
        $credperlect = $credplan / $totalsem;
        $semen = (2 * $credaprob + $credinsc + 1) / (2 * $credperlect);
        $semen = floor($semen);
    } else {
        $semen = -1;
        mysql_free_result($query);
    }
    return $semen;
}

function es_tutor()
{
    DBConn("localhost", "tutorias", "sife", "sife");
    $IDpersona = $_SESSION['IDpersonallogeada'];
    $SQL = "SELECT * FROM tutores WHERE IDpersona=" . $IDpersona . " AND activo=1";
    $resultado = mysql_query($SQL);
    $r = (mysql_num_rows($resultado) > 0) ? true : false;
    return $r;
}

function permiso($s, $ss, $IDpersonal = 0)
{
    $IDpersonal = ($IDpersonal) ? $IDpersonal : $_SESSION['IDpersonallogeada'];
    DBConn("localhost", "sifeuady", "sife", "sife");
    $allow = 0;
    $SQL = "SELECT * FROM permisos_n WHERE IDPersona = " . $IDpersonal . "";
    //echo $SQL;
    $query = mysql_query($SQL);
    $rs = mysql_fetch_array($query);
    $TipoUsuario = $rs['IDtipo'];
    mysql_free_result($query);

    $SQL = "SELECT subniveles FROM permisos_usuarios_n WHERE IDtipo = " . $TipoUsuario . " AND IDseccion = " . $s . "";
    //echo $SQL;
    $query = mysql_query($SQL);
    if (mysql_num_rows($query) > 0) {
        $rs = mysql_fetch_array($query);
        $cadena = $rs['subniveles'];
        mysql_free_result($query);
        $bit = substr($cadena, $ss, 1);
        //echo "cadena: ".$cadena." -- ss: ".$ss." -- substr: ".substr($cadena,$ss,1)."<br>";
        if ($bit == 1) {
            $allow = 1;
//	  echo "si<br>";
        } else {
            $allow = 0;
//	  echo "no1<br>";
        }
    } else {
        $allow = 0;
//	echo "no2<br>";
    }
    return $allow;
}

?>
<?php
function array_envia($array)
{
    $tmp = serialize($array);
    $tmp = urlencode($tmp);
    return $tmp;
}

?>
<?php
function array_recibe($url_array)
{
    $tmp = stripslashes($url_array);
    $tmp = urldecode($tmp);
    $tmp = unserialize($tmp);
    return $tmp;
}

?>
<?php
/*!
  @function num2letras ()
  @abstract Dado un n?mero lo devuelve escrito.
  @param $num number - N?mero a convertir.
  @param $fem bool - Forma femenina (true) o no (false).
  @param $dec bool - Con decimales (true) o no (false).
  @result string - Devuelve el n?mero escrito en letra.

*/
function num2letrasNew($num, $fem = false, $dec = true)
{
    $matuni[2] = "dos";
    $matuni[3] = "tres";
    $matuni[4] = "cuatro";
    $matuni[5] = "cinco";
    $matuni[6] = "seis";
    $matuni[7] = "siete";
    $matuni[8] = "ocho";
    $matuni[9] = "nueve";
    $matuni[10] = "diez";
    $matuni[11] = "once";
    $matuni[12] = "doce";
    $matuni[13] = "trece";
    $matuni[14] = "catorce";
    $matuni[15] = "quince";
    $matuni[16] = "dieciseis";
    $matuni[17] = "diecisiete";
    $matuni[18] = "dieciocho";
    $matuni[19] = "diecinueve";
    $matuni[20] = "veinte";
    $matunisub[2] = "dos";
    $matunisub[3] = "tres";
    $matunisub[4] = "cuatro";
    $matunisub[5] = "quin";
    $matunisub[6] = "seis";
    $matunisub[7] = "sete";
    $matunisub[8] = "ocho";
    $matunisub[9] = "nove";

    $matdec[2] = "veint";
    $matdec[3] = "treinta";
    $matdec[4] = "cuarenta";
    $matdec[5] = "cincuenta";
    $matdec[6] = "sesenta";
    $matdec[7] = "setenta";
    $matdec[8] = "ochenta";
    $matdec[9] = "noventa";
    $matsub[3] = 'mill';
    $matsub[5] = 'bill';
    $matsub[7] = 'mill';
    $matsub[9] = 'trill';
    $matsub[11] = 'mill';
    $matsub[13] = 'bill';
    $matsub[15] = 'mill';
    $matmil[4] = 'millones';
    $matmil[6] = 'billones';
    $matmil[7] = 'de billones';
    $matmil[8] = 'millones de billones';
    $matmil[10] = 'trillones';
    $matmil[11] = 'de trillones';
    $matmil[12] = 'millones de trillones';
    $matmil[13] = 'de trillones';
    $matmil[14] = 'billones de trillones';
    $matmil[15] = 'de billones de trillones';
    $matmil[16] = 'millones de billones de trillones';

    //Zi hack
    $float = explode('.', $num);
    $num = $float[0];

    $num = trim((string)@$num);
    if ($num[0] == '-') {
        $neg = 'menos ';
        $num = substr($num, 1);
    } else
        $neg = '';
    while ($num[0] == '0') $num = substr($num, 1);
    if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
    $zeros = true;
    $punt = false;
    $ent = '';
    $fra = '';
    for ($c = 0; $c < strlen($num); $c++) {
        $n = $num[$c];
        if (!(strpos(".,'''", $n) === false)) {
            if ($punt) break;
            else {
                $punt = true;
                continue;
            }

        } elseif (!(strpos('0123456789', $n) === false)) {
            if ($punt) {
                if ($n != '0') $zeros = false;
                $fra .= $n;
            } else

                $ent .= $n;
        } else

            break;

    }
    $ent = '     ' . $ent;
    if ($dec and $fra and !$zeros) {
        $fin = ' coma';
        for ($n = 0; $n < strlen($fra); $n++) {
            if (($s = $fra[$n]) == '0')
                $fin .= ' cero';
            elseif ($s == '1')
                $fin .= $fem ? ' una' : ' un';
            else
                $fin .= ' ' . $matuni[$s];
        }
    } else
        $fin = '';
    if ((int)$ent === 0) return 'Cero ' . $fin;
    $tex = '';
    $sub = 0;
    $mils = 0;
    $neutro = false;
    while (($num = substr($ent, -3)) != '   ') {
        $ent = substr($ent, 0, -3);
        if (++$sub < 3 and $fem) {
            $matuni[1] = 'una';
            $subcent = 'as';
        } else {
            $matuni[1] = $neutro ? 'un' : 'uno';
            $subcent = 'os';
        }
        $t = '';
        $n2 = substr($num, 1);
        if ($n2 == '00') {
        } elseif ($n2 < 21)
            $t = ' ' . $matuni[(int)$n2];
        elseif ($n2 < 30) {
            $n3 = $num[2];
            if ($n3 != 0) $t = 'i' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        } else {
            $n3 = $num[2];
            if ($n3 != 0) $t = ' y ' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }
        $n = $num[0];
        if ($n == 1) {
            $t = ' ciento' . $t;
        } elseif ($n == 5) {
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
        } elseif ($n != 0) {
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
        }
        if ($sub == 1) {
        } elseif (!isset($matsub[$sub])) {
            if ($num == 1) {
                $t = ' mil';
            } elseif ($num > 1) {
                $t .= ' mil';
            }
        } elseif ($num == 1) {
            $t .= ' ' . $matsub[$sub] . '?n';
        } elseif ($num > 1) {
            $t .= ' ' . $matsub[$sub] . 'ones';
        }
        if ($num == '000') $mils++;
        elseif ($mils != 0) {
            if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
            $mils = 0;
        }
        $neutro = true;
        $tex = $t . $tex;
    }
    $tex = $neg . substr($tex, 1) . $fin;
    //Zi hack --> return ucfirst($tex);

    if (!empty($float[1]))
        $end_num = ucfirst($tex) . ' pesos ' . $float[1] . '/100 M.N.';
    else
        $end_num = ucfirst($tex) . ' pesos 00/100 M.N.';

    return strtoupper($end_num);
}

?>
<?php
function verificarSeriacion($asignaturas)
{
    $arrayAntes = array(54, 59, 61);
    $arrayDespues = array(55, 60, 62);
    $arraySeriados[0] = "";
    $j = 0;
    foreach ($asignaturas as $idAsignatura) {
        if ($idAsignatura == 53) {
            if (in_array(52, $asignaturas))
                echo "";
            else
                $arraySeriados[$j] = $idAsignatura;

        }
        if ($idAsignatura == 58) {
            if (in_array(57, $asignaturas))
                echo "";
            else
                $arraySeriados[$j] = $idAsignatura;

        }
        if ($idAsignatura == 60) {
            if (in_array(59, $asignaturas))
                echo "";
            else
                $arraySeriados[$j] = $idAsignatura;

        }
        $j++;
    }

    return $arraySeriados;
}

?>
<?php
// Aplicar comillas sobre la variable para hacerla segura
function comillas_inteligentes($valor)
{
    // Retirar las barras
    if (get_magic_quotes_gpc()) {
        $valor = stripslashes($valor);
    }

    // Colocar comillas si no es entero
    if (!is_numeric($valor)) {
        $valor = "'" . mysql_real_escape_string($valor) . "'";
    }
    return $valor;
}

?>
<?php
function verificarCursos($asignaturas)
{
    //$arrayManana   = array(1, 3, 7);
    //$arrayTarde = array(2,4,5, 6, 8,9,10,11);

    $arrayManana = array(19, 21, 23, 24, 25, 26);
    $arrayTarde = array(18, 20, 22, 27);

    $j = 0;
    $x = 0;
    $z = 0;
    foreach ($asignaturas as $idAsignatura) {
        if (in_array($idAsignatura, $arrayManana)) {
            $j++;
        }

        if (in_array($idAsignatura, $arrayTarde)) {
            $x++;
        }


    }
    if ($j > 1 or $x > 1)
        $z = 1;//hay choques de horario
    else
        $z = 2; //no hay choque de horario

    return $z;
}

?>
<?php
function mefi($calif)
{
    $nivel = "";
    if ($calif >= 90 && $calif <= 100)
        $nivel = "Sobresaliente";
    if ($calif >= 80 && $calif <= 89)
        $nivel = "Satisfactorio";
    if ($calif >= 70 && $calif <= 79)
        $nivel = "Suficiente";
    if ($calif >= 0 && $calif <= 69)
        $nivel = "No acreditado";
    return $nivel;
}

?>
<?php
/**
 * @param int $idAlumno ID del alumno del cual se quiere obtener sus datos personales.
 * @return array Arreglo de los datos personales del alumno.
 */
function obtenerDatosAlumno($idAlumno)
{
    $SQL = "SELECT * FROM alumnos WHERE idAlumno = '" . $idAlumno . "'";
    $query = mysql_query($SQL);
    $datosAlumno = mysql_fetch_array($query);

    return $datosAlumno;
}

?>
<?php
/**
 * @param int $idAlumno ID del alumno del cual se quiere obtener los períodos cursados.
 * @return array Arreglo de períodos cursados por el alumno.
 */
function obtenerPeriodosCursados($idAlumno)
{
    $SQL = "SELECT pe.idPeriodo, pe.nombre
		    FROM alumno_cicloescolar il
		    INNER JOIN periodos pe ON il.idPeriodo = pe.idPeriodo
		    WHERE il.idAlumno = " . $idAlumno . " 
		    GROUP BY pe.idPeriodo
		    ORDER BY pe.fechaInicio";
    $query = mysql_query($SQL);

    $periodosCursados = array();
    while ($periodo = mysql_fetch_array($query)) {
        $periodosCursados[] = $periodo;
    }

    return $periodosCursados;
}

?>
<?php
/**
 * Obtener asignaturas cursadas por un alumno en un período dado.
 *
 * @param int $idAlumno ID del alumno del cual se quiere obtener las asignaturas.
 * @param int $idPeriodo ID del período del cual se quiere obtener las asignaturas
 * @return array Asignaturas cursadas por un alumno en el período dado.
 */
function obtenerAsignaturasPeriodo($idAlumno, $idPeriodo)
{
    $SQL = "SELECT chg.idAlumno, m.nombre, chg.ordinario, m.creditos, chg.idAsignatura, t.nombre AS tipo, roa.idPlan 
            FROM cargas_historial_global_ordinarios chg 
            INNER JOIN asignaturas roa ON chg.idAsignatura=roa.idAsignatura 
            INNER JOIN tipoasignatura t ON t.idTipo=roa.idTipo 
            INNER JOIN materias m ON roa.idMateria=m.idMateria
		    WHERE chg.idPeriodo = " . $idPeriodo . " 
		    AND chg.idAlumno = " . $idAlumno . " 
		    ORDER BY roa.ordenar";
    $query = mysql_query($SQL);
    $numeroOrdinarios = mysql_num_rows($query);

    if ($numeroOrdinarios == 0) {
        $SQL = "SELECT DISTINCT(chg.idAsignatura), m.nombre, m.creditos, chg.idAsignatura, t.nombre as tipo, roa.idPlan 
                FROM cargas_academicas chg 
                INNER JOIN asignaturas roa ON chg.idAsignatura=roa.idAsignatura
                INNER JOIN tipoasignatura t ON t.idTipo=roa.idTipo 
                INNER JOIN materias m ON roa.idMateria=m.idMateria
                INNER JOIN horarios h ON h.idCargaAcademica=chg.idCarga
                INNER JOIN alumno_cicloescolar ac ON ac.idAula=h.idAulaCarga AND ac.idPeriodo=" . $idPeriodo . "
                WHERE chg.idPeriodo = " . $idPeriodo . " 
                AND ac.idAlumno = " . $idAlumno . " 
                AND chg.idAsignatura NOT IN (71,72,73,74,75,76,77,78,79,80,81,88,85,84,86,87,89) 
                ORDER by roa.ordenar";
        $query = mysql_query($SQL);
    }

    $asignaturasPeriodo = array();
    while ($asignatura = mysql_fetch_array($query)) {
        $asignaturasPeriodo[] = $asignatura;
    }

    return $asignaturasPeriodo;
}

?>
<?php
/**
 * @param int $idAlumno ID del alumno del cual se quiere obtener el promedio.
 * @param $idPeriodo ID del período del cual se quiere obtener el promedio.
 * @return float Promedio final del período especificado.
 */
function obtenerPromedioPeriodo($idAlumno, $idPeriodo)
{
    $TICS = array(99, 113);
    $asignaturasPeriodo = obtenerAsignaturasPeriodo($idAlumno, $idPeriodo);
    $promedioPeriodo = 0;
    $sumaCalificacion = 0;
    $materiasPromediables = 0;

    foreach ($asignaturasPeriodo as $asignatura) {
        $calificacionFinal = calificacionFinal($idAlumno, $idPeriodo, $asignatura['idAsignatura'], $asignatura['IDcarga'], "", $asignatura['ordinario'], $asignatura['creditos'], 1, $asignatura['idPlan']);

        if ((!in_array($asignatura['idAsignatura'], $TICS)) && is_numeric($calificacionFinal['calificacion'])) {
            $sumaCalificacion += $calificacionFinal['calificacion'];
            $materiasPromediables++;
        }
    }

    if (1 < $materiasPromediables) {
        $promedioPeriodo = round($sumaCalificacion / $materiasPromediables, 2);
    }

    return $promedioPeriodo;
}

?>
<?php
/**
 * Se promediarán todos los períodos hasta llegar al período que se indicó.
 *
 * @param int $idAlumno ID del alumno del cual se quiere obtener el promedio.
 * @param string $idPeriodo ID del período hasta el cual se quiere calcular el promedio final.
 * @return float Promedio final del alumno.
 */
function obtenerPromedioFinal($idAlumno, $idPeriodo)
{
    $periodosCursados = obtenerPeriodosCursados($idAlumno);
    $sumaPromedios = 0;
    $periodosTotales = 0;
    $promedioFinal = 0;

    foreach ($periodosCursados as $periodo) {
        $sumaPromedios += obtenerPromedioPeriodo($idAlumno, $periodo['idPeriodo']);
        $periodosTotales++;

        if ($idPeriodo == $periodo['idPeriodo']) {
            break;
        }
    }

    if (0 < $periodosTotales) {
        $promedioFinal = round($sumaPromedios / $periodosTotales, 2);
    }

    return $promedioFinal;
}

?>
<?php
/**
 * @param $idAlumno
 * @param $idPeriodo
 * @return int|mixed
 */
function obtenerCreditosAprobadosOrdinario($idAlumno, $idPeriodo)
{
    $asignaturasPeriodo = obtenerAsignaturasPeriodo($idAlumno, $idPeriodo);
    $creditosOrdinario = 0;

    foreach ($asignaturasPeriodo as $asignatura) {
        $calificacionFinal = calificacionFinal($idAlumno, $idPeriodo, $asignatura['idAsignatura'], $asignatura['IDcarga'], "", $asignatura['ordinario'], $asignatura['creditos'], 1, $asignatura['idPlan']);

        if ($calificacionFinal['tipo'] == 'ORD') {
            $creditosOrdinario += $calificacionFinal['creditos'];
        }
    }

    return $creditosOrdinario;
}

?>
<?php
/**
 * @param $idAlumno
 * @param $idPeriodo
 * @return int|mixed
 */
function obtenerCreditosTotalesOrdinario($idAlumno, $idPeriodo)
{
    $periodosCursados = obtenerPeriodosCursados($idAlumno);
    $creditosTotales = 0;

    foreach ($periodosCursados as $periodo) {
        $creditosTotales += obtenerCreditosAprobadosOrdinario($idAlumno, $periodo['idPeriodo']);

        if ($idPeriodo == $periodo['idPeriodo']) {
            break;
        }
    }

    return $creditosTotales;
}

?>
<?php
/**
 * @param $idAlumno
 * @param $idPeriodo
 * @return int|mixed
 */
function obtenerCreditosPeriodo($idAlumno, $idPeriodo)
{
    $asignaturasPeriodo = obtenerAsignaturasPeriodo($idAlumno, $idPeriodo);
    $creditosPeriodo = 0;

    foreach ($asignaturasPeriodo as $asignatura) {
        $calificacionFinal = calificacionFinal($idAlumno, $idPeriodo, $asignatura['idAsignatura'], $asignatura['IDcarga'], "", $asignatura['ordinario'], $asignatura['creditos'], 1, $asignatura['idPlan']);
        $creditosPeriodo += $calificacionFinal['creditos'];
    }

    return $creditosPeriodo;
}

?>
<?php
/**
 * @param $idAsignatura
 * @return mixed
 */
function obtenerCreditosAsignatura($idAsignatura)
{
    $SQL = "SELECT m.creditos
            FROM materias m
            INNER JOIN asignaturas a ON a.idMateria = m.idMateria
            WHERE a.idAsignatura = " . $idAsignatura;
    $query = mysql_query($SQL);
    $resultado = mysql_fetch_array($query);

    return $resultado['creditos'];
}

?>
<?php
/**
 * @param $idAlumno
 * @param $idPeriodo
 * @return int|mixed
 */
function obtenerCreditosTotalesAsignaturasCursadas($idAlumno, $idPeriodo)
{
    $periodosCursados = obtenerPeriodosCursados($idAlumno);
    $totalCreditosAsignaturasCursadas = 0;

    foreach ($periodosCursados as $periodo) {
        $asignaturasCursadas = obtenerAsignaturasPeriodo($idAlumno, $periodo['idPeriodo']);

        foreach ($asignaturasCursadas as $asignatura) {
            $totalCreditosAsignaturasCursadas += obtenerCreditosAsignatura($asignatura['idAsignatura']);
        }

        if ($idPeriodo == $periodo['idPeriodo']) {
            break;
        }
    }

    return $totalCreditosAsignaturasCursadas;
}

?>
<?php
/**
 * @param $idAlumno
 * @param $idPeriodo
 * @return int
 */
function obtenerTotalAsignaturasCursadas($idAlumno, $idPeriodo)
{
    $periodosCursados = obtenerPeriodosCursados($idAlumno);
    $totalAsignaturasCursadas = 0;

    foreach ($periodosCursados as $periodo) {
        $asignaturasCursadas = obtenerAsignaturasPeriodo($idAlumno, $periodo['idPeriodo']);
        $totalAsignaturasCursadas += sizeof($asignaturasCursadas);

        if ($idPeriodo == $periodo['idPeriodo']) {
            break;
        }
    }

    return $totalAsignaturasCursadas;
}

?>
<?php /**
 * @param $idAlumno
 * @param $idPeriodo
 * @return int|mixed
 */
function obtenerCreditosTotales($idAlumno, $idPeriodo)
{
    $periodosCursados = obtenerPeriodosCursados($idAlumno);
    $creditosTotales = 0;

    foreach ($periodosCursados as $periodo) {
        $creditosTotales += obtenerCreditosPeriodo($idAlumno, $periodo['idPeriodo']);

        if ($idPeriodo == $periodo['idPeriodo']) {
            break;
        }
    }

    return $creditosTotales;
}

?>
<?php
/**
 * @param $idAlumno
 * @param $idPeriodo
 * @return float
 */
function obtenerIndicePromocion($idAlumno, $idPeriodo)
{
    $totalCreditosAsignaturasCursadas = obtenerCreditosTotalesAsignaturasCursadas($idAlumno, $idPeriodo);
    $indicePromocion = 0;

    if (0 < $totalCreditosAsignaturasCursadas) {
        $indicePromocion = ((obtenerCreditosTotales($idAlumno, $idPeriodo)) / ($totalCreditosAsignaturasCursadas)) * 100;
    }

    return round($indicePromocion, 2);
}

?>
<?php
/**
 * @param $idAlumno
 * @param $idPeriodo
 * @return float
 */
function obtenerIndiceAprobacionOrdinario($idAlumno, $idPeriodo)
{
    $totalCreditosAsignaturasCursadas = obtenerCreditosTotalesAsignaturasCursadas($idAlumno, $idPeriodo);
    $indiceAprobacion = 0;

    if (0 < $totalCreditosAsignaturasCursadas) {
        $indiceAprobacion = ((obtenerCreditosTotalesOrdinario($idAlumno, $idPeriodo)) / ($totalCreditosAsignaturasCursadas)) * 100;
    }

    return round($indiceAprobacion, 2);
}

?>
<?php
function obtenerDesempenoEscolar($categoriaIndiceAprobacion, $categoriaIndicePromocion, $categoriaPromedio)
{
    $valorIndiceAprobacion = obtenerValorCategoria($categoriaIndiceAprobacion);
    $valorIndicePromocion = obtenerValorCategoria($categoriaIndicePromocion);
    $valorPromedio = obtenerValorCategoria($categoriaPromedio);

    $sumatoria = $valorIndiceAprobacion + $valorIndicePromocion + $valorPromedio;

    if (8 <= $sumatoria) {
        return 'Alto';
    } else if (6 <= $sumatoria && !($valorIndiceAprobacion == 1 && $valorIndicePromocion == 2 && $valorPromedio == 3)) {
        return 'Medio';
    } else {
        return 'Bajo';
    }
}

?>
<?php
function obtenerValorCategoria($categoria)
{
    switch ($categoria) {
        case 'Alto':
            return 3;
        case 'Medio':
            return 2;
        case 'Bajo':
            return 1;
        default:
            return 0;
    }
}

?>
<?php
/**
 * @param $indiceAprobacionOrdinario
 * @return string
 */
function obtenerCategoriaIndiceAprobacionOrdinario($indiceAprobacionOrdinario)
{
    if (91 <= $indiceAprobacionOrdinario) {
        return 'Alto';
    } else if (80 <= $indiceAprobacionOrdinario) {
        return 'Medio';
    } else {
        return 'Bajo';
    }
}

?>
<?php
/**
 * @param $indicePromocion
 * @return string
 */
function obtenerCategoriaIndicePromocion($indicePromocion)
{
    if ($indicePromocion == 100) {
        return 'Alto';
    } else if (90 <= $indicePromocion) {
        return 'Medio';
    } else {
        return 'Bajo';
    }
}

?>
<?php
/**
 * @param $promedio
 * @return string
 */
function obtenerCategoriaPromedio($promedio)
{
    if (85 <= $promedio) {
        return 'Alto';
    } else if (75 <= $promedio) {
        return 'Medio';
    } else {
        return 'Bajo';
    }
}

?>
<?php
function obtenerCategoriaSituacionEscolar($situacionEscolar)
{
    if (100 <= $situacionEscolar) {
        return 'Óptimo';
    } else if (90 <= $situacionEscolar) {
        return 'Regular';
    } else {
        return 'Rezagado';
    }
}

?>
<?php
/**
 * La situación escolar se calcula a partir de los créditos (obligatorios, optativos y de componente ocupacional) entre los
 * créditos requeridos
 *
 * @param $idAlumno
 * @param $idPeriodo
 */
function obtenerSituacionEscolar($creditosPromovidos, $creditosRequeridos)
{
    $situacionEscolar = 0;

    if (0 < $creditosRequeridos) {
        $situacionEscolar = ($creditosPromovidos / $creditosRequeridos) * 100;
    }

    return round($situacionEscolar, 2);
}

?>
<?php
function dater($x)
{
    $year = substr($x, 0, 4);
    $mon = substr($x, 5, 2);
    switch ($mon) {
        case "01":
            $month = "Enero";
            break;
        case "02":
            $month = "Febrero";
            break;
        case "03":
            $month = "Marzo";
            break;
        case "04":
            $month = "Abril";
            break;
        case "05":
            $month = "Mayo";
            break;
        case "06":
            $month = "Junio";
            break;
        case "07":
            $month = "Julio";
            break;
        case "08":
            $month = "Agosto";
            break;
        case "09":
            $month = "Septiembre";
            break;
        case "10":
            $month = "Octubre";
            break;
        case "11":
            $month = "Noviembre";
            break;
        case "12":
            $month = "Diciembre";
            break;
    }
    $day = substr($x, 8, 2);
    return $day . " de " . $month . " de " . $year;
}

?>
<?php
function obtenerProfesoresPorAreaPeriodo($idArea, $idPeriodo)
{
    if ($idArea == 255) {
        $SQL = "Select DISTINCT(p.idProfesor), CONCAT(p.apellidos,' ',p.nombres) as Profesor 
	FROM profesores p where p.idProfesor NOT IN (SELECT c.idProfesor FROM cargas_academicas c 
	WHERE c.idPeriodo='" . $idPeriodo . "') AND p.activo>0 AND p.apellidos !='' 
	ORDER BY p.apellidos, p.nombres ";
    } else {
        $SQL = "SELECT DISTINCT(p.idProfesor), CONCAT(p.apellidos,' ',p.nombres) as Profesor 
            FROM cargas_academicas ca
            INNER JOIN profesores p ON p.idProfesor = ca.idProfesor
            INNER JOIN asignaturas a ON a.idAsignatura = ca.idAsignatura
            INNER JOIN areasdisciplina ad ON ad.idArea = a.idArea
            WHERE ca.idPeriodo='" . $idPeriodo . "' 
            AND ad.idArea='" . $idArea . "' 
            GROUP BY ca.idProfesor
            ORDER BY p.apellidos,p.nombres";
    }
    $query = mysql_query($SQL);


    while ($profesor = mysql_fetch_array($query)) {
        $profesores[] = $profesor;
    }

    return $profesores;
}

?>
<?php
/**************************************************************************************************************************************/
/*Es IMPERATIVO verificar que NO existan líneas en blanco despues de terminar las funciones y en las etiquetas que marquen código php */
/**************************************************************************************************************************************/
?>