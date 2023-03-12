<?php
require_once("Laboratorios.php");
/*Funciones varias*/
$SEMESTRE = 1;
$TURNO = "MATUTINO";
$INICIO = 1;
$FINAL = 8;

function generarAleatorio($tama)
{
      //Generamos el secuencial
	$b = array(1);
	if ($tama == 1)
		return $b;
	$a = array();
      for ($i = 0; $i < $tama ; $i++)
          {
               $a[$i] = $i + 1;
          }
      //Generamos el aleatorio
      for ($i = $tama - 1 ; $i  >=0 ; $i--)
          {
               $aleatorio = rand(1, $tama);
               $aux = $a[$aleatorio -1];
               $a[$aleatorio - 1] = $a[$i];
               $a[$i] = $aux;
          }
        return $a;
}

function removerElemento($elemento, $arreglo)
{
        $result = array();
        foreach($arreglo as $e)
        {
                if ($e != $elemento)
                {
                        array_push($result, $e);
                }
        }
        return $result;
}

function printPosibilidades($posibilidades)
{
        global $INICIO,$FINAL;
	$total = 0;
        for($i = 1; $i <= 5; $i++)
        {
		for($j = $INICIO; $j <= $FINAL; $j++)
                {
                        if ($posibilidades[$i][$j] == 1)
                        {
                                $total++;
                                echo "Posibilidad: DIa = ".$i." Hora = ".$j."<br>";
                        }
                }
        }
        echo "Total = ".$total."<br>";                  
}

function printHorarioProfe($id, $horarioProfe)
{
	global $INICIO,$FINAL;
        $cadena = " <p> PROFE ID = ".$id."</p>";
	for($i = $INICIO; $i <= $FINAL; $i++)
        {
            $cadena = $cadena . "Lunes    ". $i." = ".$horarioProfe[$i][1]." | ";
            $cadena = $cadena . "Martes   ". $i." = ".$horarioProfe[$i][2]." | ";
            $cadena = $cadena . "Miecoles ". $i." = ".$horarioProfe[$i][3]." | ";
            $cadena = $cadena . "Jueves   ". $i." = ".$horarioProfe[$i][4]." | ";
            $cadena = $cadena . "Viernes  ". $i." = ".$horarioProfe[$i][5]." | ";
            $cadena = $cadena . "<br>";
        }
        echo $cadena;
}

function printHorarioSalon($horariosSalon, $idSalon)
{
        $horario = $horariosSalon[$idSalon];
        return printHorarioSalonInd($horario, $idSalon);

}

function printHorarioSalonInd($horariosSalon, $idSalon)
{
        $horario = $horariosSalon;
	global $INICIO,$FINAL;
       #Voy a poner las 8 horas pero hay q recordar q una sesion son 2 horas
        echo "<br>SALON = $idSalon<br>";
        $nombres = array("lunes","martes","miercoles","jueves","viernes");
        $cadena = "";
	for($i = $INICIO; $i <= $FINAL; $i++)
        {
            $cadena = $cadena . "Lunes    ". $i." = ".$horario[$i][1]." | "; #profe-MAteria
            $cadena = $cadena . "Martes   ". $i." = ".$horario[$i][2]." | ";
            $cadena = $cadena . "Miecoles ". $i." = ".$horario[$i][3]." | ";
            $cadena = $cadena . "Jueves   ". $i." = ".$horario[$i][4]." | ";
            $cadena = $cadena . "Viernes  ". $i." = ".$horario[$i][5]." | ";
            $cadena = $cadena . "<br>";
        }
        echo $cadena;
}

function calcularHuecos($idsMaterias, $materiasSalones)
{
	########################################################
        # Imprimir salones y materias que todavia no se han asignado
	#echo "<br>Imprimir salones y materias que todavia no se han asignado<br>";
        $huecos = 0; $test = 0;
        foreach($idsMaterias as &$idMateria)
        {
		$test = count($materiasSalones[$idMateria]);
		if ($test > 0)
                {
                        $huecos += $test;
                        #echo "<p> ID Materia = ". $idMateria. "</p>";
                        #print_r($materiasSalones[$idMateria]);
                        #echo "<br>";
                }
        }
	return $huecos;
}

function printResultados($nuevosHorariosSalon, $nuevosHorariosProfes, $huecos)
{
	$ids = array_keys($nuevosHorariosSalon);
	foreach($ids as $s)
        {
                printHorarioSalon($nuevosHorariosSalon, $s);
        }
        #########################################################
        # horarios profes
        #########################################################
	$keysProfes = array_keys($nuevosHorariosProfes);
        foreach($keysProfes as &$id)
        {
                printHorarioProfe($id, $nuevosHorariosProfes[$id]);
        }

	echo "<br>HUECOS: $huecos<br>";
}

######################################################################
# Utilerias para los salones
######################################################################

function intercambiarNumAulaPorID($keysProfes, $salonesIDNum, $horariosProfes)
{
	global $INICIO,$FINAL;
	$nuevosHorariosProfes = array(); #Hora,Dia
        foreach($keysProfes as &$id)
        {
                $horarioNuevo = array();
                $horarioNuevo = $horariosProfes[$id];
		for($i = $INICIO; $i <= $FINAL; $i++)
                {
                        for($j = 1; $j <=5; $j++)
                        {
                                $cadena = $horarioNuevo[$i][$j];
                                if ($cadena != "1" && $cadena != "0")
                                {
                                        list($a, $m, $s) = explode("-",$cadena); #ASIGNADO-MATERIA-SALON
                                        $keySalon = array_search($s, $salonesIDNum);
					$newCadena = "ASIGNADO-".$m."-".$keySalon;
                                        $horarioNuevo[$i][$j] = $newCadena;
                                }
                        }
                }
                $nuevosHorariosProfes[$id] = $horarioNuevo;
        }
	return $nuevosHorariosProfes;
}

function intercambiarIDSSalones($horariosSalon, $salonesIDNum)
{
	$numAulas = array_keys($horariosSalon);
        foreach($numAulas as $numAula)
        {
                $keySalon = array_search($numAula, $salonesIDNum);
                $nuevosHorariosSalon[$keySalon] = $horariosSalon[$numAula];
        }
	return $nuevosHorariosSalon;
}

function removerLabs($semestre, $materias)
{
	 $labs = Laboratorios::$NOMBRES_LABS[$semestre];
	 $nombres = explode(',',$labs);
	foreach($nombres as $n)
	{
		$materias = removerElemento(Laboratorios::$IDS_LABS[$n]['MATERIA'],$materias);
		$materias = removerElemento(Laboratorios::$IDS_LABS[$n]['LAB'],$materias);
	}
	return $materias;
}

#Vamos a contar los q no tienen 0 ni 1
function actualizarHorasTotales($idProfe, $horarios)
{
	global $INICIO,$FINAL;
	$horasProfe = $horarios[$idProfe];
	#$horariosProfes[$respuesta[$i]][$hora][$dia] = "ASIGNADO-".$idLaboratorio."-".$salon;
        #$horariosProfes[$respuesta[$i]][$hora+1][$dia] = "ASIGNADO-".$idLaboratorio."-".$salon;
	$usados = 0;
	for ($j = 1; $j <=5; $j++)
	{
		for($i = $INICIO; $i <= $FINAL; $i++)
		{
			if ($horasProfe[$i][$j] != 0 && $horasProfe[$i][$j] != 1)
			{
				$usados++;
			}	
		}
	}
	return $usados;
}

function actualizarHorarioActualProfesor($keysProfes, $horarios)
{
	DBConn("localhost","siscap2","siscap2","siscap2");
	global $INICIO,$FINAL;
	$dias = array(1=>'lunes',2=>'martes',3=>'miercoles',4=>'jueves',5=>'viernes');
	foreach($keysProfes as $key)
	{
		$horario = $horarios[$key];
		for($i = $INICIO; $i <= $FINAL; $i++)
		{
			$linea = ""; 
			for ($j = 1; $j <=5; $j++)
			{
				if ($horario[$i][$j] != '0' && $horario[$i][$j] != '1')
				{
					$linea = $linea.", ".$dias[$j]." = 0";
				}
			}
			$linea = substr($linea,1); #quitamos la primera ,
			if (strlen($linea) > 1)
			{
				$linea = "UPDATE horario_actual_profesor SET ".$linea." WHERE idProfesor = ".$key." AND idHorario = ".$i;
				$queryProfes=mysql_query($linea);
				/*if (mysql_num_rows($queryProfes) <=  0)
				{
					echo "Error Update horas profesor<br>";
					echo $linea."<br>";
				}*/
			}
		}
	}
	mysql_close();
}

function actualizarAsignaturasGrupos($salonesProfes)
{
	DBConn("localhost","siscap2","siscap2","siscap2");
	$keysProfes = array_keys($salonesProfes);
	foreach($keysProfes as $keyP)
        {
                echo "ID PROFE = $keyP<br>";
                $otroMaterias = array();
                $otroMaterias = $salonesProfes[$keyP];
		$materias =  array_keys($otroMaterias);
		$sql = "";
                foreach($materias as $m)
                {
                        $salones = $salonesProfes[$keyP][$m];
                        echo "Materia = $m Salones = ".$salones." <br>";
			$sql = "UPDATE asignaturas_profesor_actual SET numGrupos = ".$salones." WHERE idProfesor = ".$keyP." AND idAsignatura = ".$m;
			$queryProfes=mysql_query($sql);
			/*if ($queryProfes != 0)
			{
				echo "Error UPDATE Asignaturas Grupos<br>";
				echo "$sql <br>";
			}*/
                }
        }
	mysql_close();
}

?>

