<?php

    session_start();
    session_unset();
    include("conex.php");
    include("ldap_auth.php");
    
    $con = Conectarse();


    $nombre = mysql_real_escape_string($_POST["usuario"],$con);
    $pass = mysql_real_escape_string($_POST["pwd"],$con);

    //verifico si el usuario INET es valio
    $verificaINET = ldaph_auth_b ($nombre,$pass);
    //$verificaINET=1;
    
    if ($verificaINET == 1){
        $sql = "SELECT usuario FROM usuarios WHERE usuario = '".$nombre."'";
        $result = mysql_query($sql);
        if ($rs = mysql_fetch_array($result)){
            echo "<script>location.href='administracion.php'</script>";
        }else{
            echo "<script>alert('Credenciales no autorizadas')</script>";
            echo "<script>location.href='index.php'</script>";
        }        
    }else{
        echo "<script>alert('Las credenciales proporcionadas no son correctas.')</script>";
        echo "<script>location.href='index.php'</script>";
    }


?>