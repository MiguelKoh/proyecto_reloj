<?php
include_once "utileriasHorario.php";
require_once("Laboratorios.php");
/*Funciones varias*/
/*$SEMESTRE = 1;
$TURNO = "MATUTINO";
$INICIO = 1;
$FINAL = 8;*/

function obtenerSalonesPorMateriaPorProfe($idProfe,$m)
{
	DBConn("localhost","siscap2","siscap2","siscap2");  
	#echo
	 $SQL =  "SELECT numGrupos FROM asignaturas_profesor WHERE idProfesor =".$idProfe." AND idAsignatura=".$m."";
	#echo $SQL;
	$query =mysql_query($SQL);
	$rs = mysql_fetch_array($query);
	$numGrupos = $rs['numGrupos'];
	mysql_close();	
	return $numGrupos;
}

function getMateriasSemestre($semestre)
{
        DBConn("localhost","siscap2","siscap2","siscap2");  
		
        $QLMaterias =  "SELECT * FROM asignaturas a inner join materias m ON a.idMateria = m.idMateria WHERE semestres =".$semestre;
        $queryMaterias =mysql_query($QLMaterias);
        $num = mysql_num_rows($queryMaterias);
        $materias = array();
        $i = 0;
        while ($rs = mysql_fetch_array($queryMaterias)) {
            $idMateria =  $rs['idAsignatura']; #echo "<p>".$idMateria."</p>";
            $materias[ $idMateria ] ['nomAbreviatura'] = $rs['nomAbreviatura']; //guardamos nombre de la materia
            $materias[ $idMateria] ['sesiones'] =  $rs['sesiones']; //guardamos las sesiones por materia
            $i++;
       }  mysql_free_result($queryMaterias);
        mysql_close();
        $keys = array_keys($materias);
        
        return $materias;
}


function getProfesoresSem($semestre, $profesIDS)
{
        DBConn("localhost","siscap2","siscap2","siscap2");  
        #Ahora ya tenemos los profes que dan alguna materia en el $semestre, ahora creamos el hash con las materias que dan
        $profesMaterias = array();
        $profesArray = explode(",",$profesIDS);	
        foreach($profesArray as &$id)
        {
		 $QLMaterias = "SELECT ap.idProfesor,ap.idAsignatura from asignaturas_profesor ap INNER JOIN asignaturas a ON ap.idAsignatura=a.idAsignatura WHERE
                    a.semestres=".$semestre. " AND idProfesor = ".$id;
		$queryMaterias = mysql_query($QLMaterias);
                $materias = array();
                while ($rs = mysql_fetch_array($queryMaterias)) {
                        array_push($materias, $rs['idAsignatura']); //guardamos nombre de la materia
                }
		$profesMaterias[$id]['asignaturas'] = $materias;
        }
        mysql_close();
        return $profesMaterias;
}

function calcularHorasProfe($profesoresHash, $turno)
{
	if ($turno == "MATUTINO")
	{
		$horas = "1,2,3,4,5,6,7,8";
	}
	else
	{
		$horas = "9,10,11,12,13,14,15,16";
	}
	
        DBConn("localhost","siscap2","siscap2","siscap2");  

        #Aqui hay dos opciones, parece que pueden o no tener horarioPreferido
        # Primero checamos si el profe aparece en la tabla              
        $profeIDS  = array_keys($profesoresHash);
        foreach($profeIDS as &$id)
        {
                $QLProfes="SELECT * FROM horario_personal_profesor WHERE idProfesor = ".$id;
                $queryProfes=mysql_query($QLProfes);
                if ($num = mysql_num_rows($queryProfes) >  0)
                { #Significa que no tiene horario preferido entonces solo sacamos las horas totales de la tabla
                        $rs = mysql_fetch_array($queryProfes);
                        $profesoresHash[$id]['horasTotales'] = $rs['horasTotales'];
                }
                else
                { #Entonces tiene un horario preferido y hay que calcular sus horasTotales
                        $QLProfes="SELECT idProfesor, SUM( lunes ) as SL, SUM( martes ) as SM, SUM( miercoles ) as SMM, SUM( jueves ) as SJ, SUM( viernes ) as SV
                                        FROM horario_actual_profesor WHERE idProfesor = ".$id." AND idHorario IN (".$horas.") GROUP BY idProfesor";
			
                        $queryProfes=mysql_query($QLProfes);
                        if (mysql_num_rows($queryProfes) >  0)
                        {
                                $rs = mysql_fetch_array($queryProfes);
                                $profesoresHash[$id]['horasTotales'] = $rs['SL'] + $rs['SM'] + $rs['SMM'] + $rs['SJ'] + $rs['SV'];
                        }
                        else
                        { # No tiene horas en la bd, asignamos 0
                                $profesoresHash[$id]['horasTotales'] = 0;
                        }
                }
        }
        mysql_close();
        return $profesoresHash;
}

//////////////////////////////////////
function tieneHorarioBD($idProfe)
{
		$flag = false;
		        DBConn("localhost","siscap2","siscap2","siscap2");  
                #Aqui ya tenemos el id del profe, vamos a buscar su horario en la tabla
                #En este momento vamos a considerar que todos los profesores tienen horarios FAVORITOS!
                $SQLHoras="SELECT * FROM horario_actual_profesor WHERE idProfesor = ".$idProfe;
                $queryHoras = mysql_query($SQLHoras);
                $num = mysql_num_rows($queryHoras);
		$horariosProfes = array();		
                if ($num > 0)
                {
			$flag = true;
		}
		mysql_close();
		return $flag;
}
function getHorarioProfe($idProfe,$horariosProfes)
{
		#Por cada profe vamos a cargar su matriz de horariosi
	        DBConn("localhost","siscap2","siscap2","siscap2");  
                #Aqui ya tenemos el id del profe, vamos a buscar su horario en la tabla
                #En este momento vamos a considerar que todos los profesores tienen horarios FAVORITOS!
                $SQLHoras="SELECT * FROM horario_actual_profesor WHERE idProfesor = ".$idProfe;
                $queryHoras = mysql_query($SQLHoras);
                $num = mysql_num_rows($queryHoras);
		$horariosProfes = array();		
                if ($num > 0)
                {
                        while($rs = mysql_fetch_array($queryHoras))
                        {
                                $horariosProfes[$rs['idHorario']][1] = $rs['lunes'];
                                $horariosProfes[$rs['idHorario']][2] = $rs['martes'];
                                $horariosProfes[$rs['idHorario']][3] = $rs['miercoles'];
                                $horariosProfes[$rs['idHorario']][4] = $rs['jueves'];
                                $horariosProfes[$rs['idHorario']][5] = $rs['viernes'];
			}
                }
                else
                {
                        #Significa que no tiene horario preferido en la bd, se necesita capturar!!
                        //echo "<p> EL PROFE " .$id." NO TIENE HORARIOS CAPTURADOS EN LA BD</p>";
                }
	mysql_close();
	return $horariosProfes;
}

function generarPosibilidades($horariosProfe)
{
	global $INICIO, $FINAL;

	$posibilidades = array(); # Dia x Hora
	for($i = 1; $i <= 5; $i++)
	{
		for($j = $INICIO; $j <= $FINAL; $j++)
		{
			if ($horariosProfe[$j][$i] == 1)
				$posibilidades[$i][$j] = 1;
			else
				$posibilidades[$i][$j] = 0;
		}
	}
	return $posibilidades;
}

function actualizarPosibilidades($posibilidades,$dia)
{
	global $INICIO, $FINAL;
	$posNew = array();
	for($i = 1; $i <= 5; $i++)
	{
		if ($dia != $i)
		{
			for($j = $INICIO; $j <= $FINAL; $j++)
			{
				if ($posibilidades[$i][$j] == 1)
				{	
					$posNew[$i][$j] = 1;
				}
				else
				{
					$posNew[$i][$j] = 0;
				}
			}
		}
		else
		{ #just copy data from $posibilidades
			for($k = 1; $k <= 8; $k++)
			{	
				$posNew[$i] = 0;//$posibilidades[$i];
			}
		}
	}

	return $posNew;
}

function crearDiasDisponibles($posibilidades)
{
	$d = array();
	for($i = 1; $i<=5; $i++ )
	{
		if(count(getPosibilidadesDia($posibilidades,$i)) >0)
			$d[$i] = 1;
		else
			$d[$i] = 0;
	}
	return $d;
}

function getPosibilidadesDia($posibilidades,$dia)
{
	global $INICIO,$FINAL;
 
	$horas = array();
		for($j = $INICIO; $j <= $FINAL; $j++)
		{
			if ($posibilidades[$dia][$j] == 1)
			{	
				array_push($horas,$j);
			}
			else
			{
				#echo "<br>No hay posibilidades para ese dia<br>";
			}
		}
	return $horas;
}
function contarPosibilidades($posibilidades)
{
	global $INICIO,$FINAL;
	#print para verificar q elementos estan en la matriz
	$total = 0;
	for($i = 1; $i <= 5; $i++)
	{
		for($j = $INICIO; $j <= $FINAL; $j++)
		{
			if ($posibilidades[$i][$j] == 1)
			{	
				$total++;
			}
		}
	}
	return $total;
}

/*
	Para checar si las horas disponibles en horasDias pueden ser usadas para una sesion q requiere 2 seguidas
	Es decir, si dentro de horasDias estan 1,3,5 o 7
*/
function checkHoras($horasDia)
{
	global $TURNO;

	$result = false;
	if ($TURNO == "MATUTINO")
	{
		if (in_array(1,$horasDia) && in_array(2,$horasDia)) 
		{
			$result = true;
		}
		else
		if (in_array(3,$horasDia) && in_array(4,$horasDia)) 
		{
			$result = true;
		}
		else
		if (in_array(5,$horasDia) && in_array(6,$horasDia)) 
		{
			$result = true;
		}
		else
		if (in_array(7,$horasDia) && in_array(8,$horasDia)) 
		{
			$result = true;
		}
	}
	else
	{
		if (in_array(9,$horasDia) && in_array(10,$horasDia))
                {
                        $result = true;
                }
                else
                if (in_array(11,$horasDia) && in_array(12,$horasDia))
                {
                        $result = true;
                }
                else
                if (in_array(13,$horasDia) && in_array(14,$horasDia))
                {
                        $result = true;
                }
                else
                if (in_array(15,$horasDia) && in_array(16,$horasDia))
                {
                        $result = true;
                }
	}	
	
	return $result;
}

function getHoraDisponible($horasDia)
		{
	$horasDisponibles = array();
	global $TURNO;
	if ($TURNO == "MATUTINO")
	{
		if (in_array(1,$horasDia) && in_array(2,$horasDia)) 
		{
			array_push($horasDisponibles,1);
		}	
		if (in_array(3,$horasDia) && in_array(4,$horasDia)) 
		{
			array_push($horasDisponibles,3);
		}
		if (in_array(5,$horasDia) && in_array(6,$horasDia)) 
		{
			array_push($horasDisponibles,5);
		}
		if (in_array(7,$horasDia) && in_array(8,$horasDia)) 
		{
			array_push($horasDisponibles,7);
		}
	}
	else
	{
		if (in_array(9,$horasDia) && in_array(10,$horasDia)) 
		{
			array_push($horasDisponibles,9);
		}	
		if (in_array(11,$horasDia) && in_array(12,$horasDia)) 
		{
			array_push($horasDisponibles,11);
		}
		if (in_array(13,$horasDia) && in_array(14,$horasDia)) 
		{
			array_push($horasDisponibles,13);
		}
		if (in_array(15,$horasDia) && in_array(16,$horasDia)) 
		{
			array_push($horasDisponibles,15);
		}

	}
	$result = false;
	if (count($horasDisponibles) >0)
	{
		$aleResultados = generarAleatorio(count($horasDisponibles));
		$result = $horasDisponibles[$aleResultados[0]-1];
	}
	return $result;
}


function generarPosibilidadesCombinadas($horarioProfe, $horarioSalon)
{
        global $INICIO,$FINAL;
	$posibilidades = array(); #Dia x Hora
        for($i = 1; $i <= 5; $i++)
        {
		for($j = $INICIO; $j <= $FINAL; $j++)
                {
                        if ($horarioProfe[$j][$i] == 1 && $horarioSalon[$j][$i] == 1)
                                $posibilidades[$i][$j] = 1;
                        else
                                $posibilidades[$i][$j] = 0;
                }
        }
	return $posibilidades;
}

/*
	Esta funcion regresa un array "asignados" , cada elemento del array es una de las sesion (dias)
*/
function asignarHorarios3($idProfe, $horariosProfe, $materiasSem1, $idMateria, $horarioSalon)
{
	$flag = false;
	$posibilidades = generarPosibilidadesCombinadas($horariosProfe, $horarioSalon);
	//printPosibilidades($posibilidades);
	$testTotal = 2*($materiasSem1[$idMateria]['sesiones']);
	$total  = contarPosibilidades($posibilidades);
	if ($total > 0 && $total>=$testTotal)
	{	//Todavia se puede asignar la materia al salon
		$sesiones = $materiasSem1[$idMateria]['sesiones'];
		//Generar HASH para las sesiones
		$sesionesHash = array();
		$sesionesAux = $sesiones;
		while($sesionesAux > 0)
		{
			if ($sesionesAux > .5)
			{
				array_push($sesionesHash,1);
				$sesionesAux = $sesionesAux - 1;
			}
			else
			{
				array_push($sesionesHash,.5);
				$sesionesAux = $sesionesAux - .5;
			}
		}
		#iniciamos el hash de los dias elegidos, para no repetir!
		$dias = array();
		$dias = crearDiasDisponibles($posibilidades);
		#Ya tenemos las sesiones que tenemos que asignar		
		#iniciamos el for para cada sesion, necesitamos encontrar un lugar para TODAS, si falta uno se regresa false!
		$asignados = array();
		$aleDias = generarAleatorio(5);
		for($iSesion = 0; $iSesion < count($sesionesHash) ; $iSesion++)
		{
				#echo "Entro a sesiones = ".$iSesion."<br>";
				#Elegir dia
				$flagDia = false;
				$dias = crearDiasDisponibles($posibilidades);#150212
				while($flagDia == false)
				{
					$dia = -1;
					if (count($aleDias) > 0)
						$iDia = array_shift($aleDias);
					else
					   break;
				 	#echo "<br> iDIA = ".$iDia."<br>";	
					if ($dias[$iDia] == 1)//Si el dia esta disponible
					{
						#El dia esta disponible, ahora hay q checar si tiene horas disponibles
						$dia = $iDia;
						#echo "DIA = ".$dia."<br>";
						$flagHoras = false;
						while($flagHoras == false)
						{
							$horasDia = array();
							$horasDia = getPosibilidadesDia($posibilidades,$dia);
							#echo "Horas DIa ASIGNAR3<br>";
							#print_r($horasDia);
							$hora = -1;
							if (count($horasDia) > 0)
							{ #Todavia tiene horas disponibles en ese dia
							  #Elegir una aleatoria
								$aleaHoras = generarAleatorio(count($horasDia)); #140212 PENDIENTE!!
								$iHora = $aleaHoras[0] - 1;
								if ($sesionesHash[$iSesion] == 1 && checkHoras($horasDia))
								{ #hay q agarrar solamente 1,3,5,7
									#echo "<br>ENTRO AL CHECK Es sesiones de 1<br>";
									$hora = getHoraDisponible($horasDia);//$iH;
									$flagHoras = true;
									$flagDia = true;
									$dias[$iDia] = 0;
									$break;
								}
								else
								{ 
									if ($sesionesHash[$iSesion] == .5)
									{
										#Sesion .5
										#echo "Es sesiones de .5<br>";
										#Esta puede ser cualquier hora
										$hora = $horasDia[$iHora]; #iHora ya esta en base a 0
										$flagHoras = true;
										$flagDia = true;
										$dias[$iDia] = 0;
									}
									else
									{ #CheckHoras == false
										$flagDia = false;
										$flagHoras = true;			
									}
								}
								
							}
							else
							{
						#		echo "<br>ELSE contar horas dia =< 0<br>";
								$flagDia = true;
								$flagHoras = true;
							}

							#Hay q eliminar el dia de las posibilidades
							#Si no tiene horas, entonces lo quitamos del array de posibilidades
							#osea, eliminamos el dia
							$posibilidades = actualizarPosibilidades($posibilidades,$dia);
							//echo "Desp de actualizar posibilidades<br>";;
							//printPosibilidades($posibilidades);
							#hay q sacar dias desp de actualizarPosibilidades
							if (contarPosibilidades($posibilidades)<=0)
							{
							#	echo "<br>IF contarPosibilidades < 0<br>";
								$flagHoras = true; #para salir del ciclo
								$flagDia = true;
							}
						}#fin while flagHoras
						if ($hora != -1)
							$flagDia = true;
					}//Fin dia disponible
				}#Fin flagDia
				if ($dia != -1 && $hora != -1)
				{
					#Se encontro lugar, hay q seguir con el for de sesiones
					#
					//echo "Asignados <br>";					
					array_push($asignados, $dia."-".$hora."-".($sesionesHash[$iSesion]*2)."-".$idMateria);
				}
				else
				{
					#echo "NO SE ASIGNO<br>";
				}
		}#FIN FOR Sesiones!
		if (count($asignados) == count($sesionesHash))
		{
			#Si se asignaron todos
			#actualizarHorarioProfe();
			#echo "<br>IF del asignados == sesionesHash<br>";
			#printAsignados($idProfe,$salon, $idMateria, $asignados);
			return $asignados;
		}
		else
		{
			#echo "<br>ELSE del asignados == sesionesHash<br>";
			$flag = false;
			return $flag;
		}
	}	
	else
	{ //No se pudo asignar :(
		#echo "<br>Total = 0?? --> Total = ".$total."<br>";
		return $flag;
	}
}


/*
	Esta funcion regresa un array "asignados" , cada elemento del array es una de las sesion (dias)
*/
function asignarHorarios2($idProfe, $horariosProfe, $materiasSem1, $idMateria, $salon)
{
	$flag = false;
	$posibilidades = generarPosibilidades($horariosProfe);
	#printPosibilidades($posibilidades);
	$testTotal = 2*($materiasSem1[$idMateria]['sesiones']);
	$total  = contarPosibilidades($posibilidades);
	if ($total > 0 && $total>=$testTotal)
	{	//Todavia se puede asignar la materia al salon
		$sesiones = $materiasSem1[$idMateria]['sesiones'];
		//Generar HASH para las sesiones
		$sesionesHash = array();
		$sesionesAux = $sesiones;
		while($sesionesAux > 0)
		{
			if ($sesionesAux > .5)
			{
				array_push($sesionesHash,1);
				$sesionesAux = $sesionesAux - 1;
			}
			else
			{
				array_push($sesionesHash,.5);
				$sesionesAux = $sesionesAux - .5;
			}
		}
		#iniciamos el hash de los dias elegidos, para no repetir!
		$dias = array();
		$dias = crearDiasDisponibles($posibilidades);
		#Ya tenemos las sesiones que tenemos que asignar		
		#iniciamos el for para cada sesion, necesitamos encontrar un lugar para TODAS, si falta uno se regresa false!
		$asignados = array();
		$aleDias = generarAleatorio(5);
		for($iSesion = 0; $iSesion < count($sesionesHash) ; $iSesion++)
		{
				#echo "Entro a sesiones = ".$iSesion."<br>";
				#Elegir dia
				$flagDia = false;
				$dias = crearDiasDisponibles($posibilidades);#150212
				while($flagDia == false)
				{
					$dia = -1;
					if (count($aleDias) > 0)
						$iDia = array_shift($aleDias);
					else
					   break;
				 	#echo "<br> iDIA = ".$iDia."<br>";	
					if ($dias[$iDia] == 1)//Si el dia esta disponible
					{
						#El dia esta disponible, ahora hay q checar si tiene horas disponibles
						$dia = $iDia;
						#echo "DIA = ".$dia."<br>";
						$flagHoras = false;
						while($flagHoras == false)
						{
							$horasDia = array();
							$horasDia = getPosibilidadesDia($posibilidades,$dia);
							#echo "Horas DIa<br>";
							#print_r($horasDia);
							$hora = -1;
							if (count($horasDia) > 0)
							{ #Todavia tiene horas disponibles en ese dia
							  #Elegir una aleatoria
								$aleaHoras = generarAleatorio(count($horasDia)); #140212 PENDIENTE!!
								$iHora = $aleaHoras[0] - 1;
								if ($sesionesHash[$iSesion] == 1 && checkHoras($horasDia))
								{ #hay q agarrar solamente 1,3,5,7
									#echo "Es sesiones de 1<br>";
									$hora = getHoraDisponible($horasDia);//$iH;
									$flagHoras = true;
									$flagDia = true;
									$dias[$iDia] = 0;
									$break;
								}
								else
								{ 
									if ($sesionesHash[$iSesion] == .5)
									{
										#Sesion .5
										#echo "Es sesiones de .5<br>";
										#Esta puede ser cualquier hora
										$hora = $horasDia[$iHora]; #iHora ya esta en base a 0
										$flagHoras = true;
										$flagDia = true;
										$dias[$iDia] = 0;
									}
									else
									{ #CheckHoras == false
										$flagDia = true;
										$flagHoras = true;			
									}
								}
								
							}
							else
							{
						#		echo "<br>ELSE contar horas dia =< 0<br>";
								$flagDia = true;
								$flagHoras = true;
							}

							#Hay q eliminar el dia de las posibilidades
							#Si no tiene horas, entonces lo quitamos del array de posibilidades
							#osea, eliminamos el dia
							$posibilidades = actualizarPosibilidades($posibilidades,$dia);
							#echo "Desp de actualizar posibilidades<br>";;
							#printPosibilidades($posibilidades);
							#hay q sacar dias desp de actualizarPosibilidades
							if (contarPosibilidades($posibilidades)<=0)
							{
							#	echo "<br>IF contarPosibilidades < 0<br>";
								$flagHoras = true; #para salir del ciclo
								$flagDia = true;
							}
						}#fin while flagHoras
						if ($hora != -1)
							$flagDia = true;
					}//Fin dia disponible
				}#Fin flagDia
				if ($dia != -1 && $hora != -1)
				{
					#Se encontro lugar, hay q seguir con el for de sesiones
					#echo "Asignados <br>";					
					array_push($asignados, $dia."-".$hora."-".($sesionesHash[$iSesion]*2)."-".$idMateria);
				}
				else
				{
					#echo "NO SE ASIGNO<br>";
				}
		}#FIN FOR Sesiones!
		if (count($asignados) == count($sesionesHash))
		{
			#Si se asignaron todos
			#printAsignados($idProfe,$salon, $idMateria, $asignados);
			return $asignados;
		}
		else
		{
			#echo "<br>ELSE del asignados == sesionesHash<br>";
			$flag = false;
			return $flag;
		}
	}	
	else
	{ //No se pudo asignar :(
		#echo "<br>Total = 0?? --> Total = ".$total."<br>";
		return $flag;
	}
}

function actualizarHorarioProfe($idProfe,$horarioProfe, $asignado,$salon)
{
	foreach($asignado as &$a)
	{
		list($dia,$hora,$duracion,$idMateria) = explode("-",$a);
		if ($duracion == 1)
			$horarioProfe[$hora][$dia] = "ASIGNADO-".$idMateria."-".$salon;
		else
		{
			$horarioProfe[$hora][$dia] = "ASIGNADO-".$idMateria."-".$salon;
			$horarioProfe[$hora+1][$dia] = "ASIGNADO-".$idMateria."-".$salon;
		}
	}
	return $horarioProfe;
}
#Recibe un array con los ids de los salones para ese semestre
function inicializarHorariosSalon($salonesSemestre)
{
	global $INICIO,$FINAL;
	foreach($salonesSemestre as $salon)
        {
		#Voy a poner las 8 horas pero hay q recordar q una sesion son 2 horas
		for($j = $INICIO; $j <= $FINAL; $j++)
                {
                        for($k = 1; $k <= 5; $k++)
                        {
                                 $horarioSalon[$salon][$j][$k] = 1;
                        }
                }
        }
	return $horarioSalon;
}
						
function validarSalon($salonID,$horariosSalon,$asignado)
{
	$flag = false;
	foreach($asignado as &$a)
	{
		list($dia,$hora,$duracion,$idMateria) = explode("-",$a);
		if ($duracion == 2)
		{
			if ($horariosSalon[$hora][$dia] == 1 && $horariosSalon[$hora+1][$dia]==1)
			{
				$flag = true;
			}
			else
			{
				$flag = false;
				break;
			}
		}
		else
		{ #.5
			if ($horariosSalon[$hora][$dia] == 1 )
			{
				$flag = true;
			}
			else
			{
				$flag = false;
				break;
			}

		}
	}
	return $flag;
}


function actualizarHorarioSalon($idProfe, $asignado, $horariosSalon)
{
	foreach($asignado as &$a)
        {
                list($dia,$hora,$duracion,$idMateria) = explode("-",$a);
                if ($duracion == 2)
                {
                        $horariosSalon[$hora][$dia] = $idProfe."-".$idMateria ;
			$horariosSalon[$hora+1][$dia]= $idProfe."-".$idMateria;
                }
                else
                { #.5
                        $horariosSalon[$hora][$dia] = $idProfe."-".$idMateria;
                }
        }
	return $horariosSalon;
}

//////////////////////////////////////

function generarSalonesMateria($todosSalones, $numSalones)
{
	$nuevo = array();
	$salonesAleatorios = generarAleatorio(count($todosSalones));
	$nuevo = array_slice($salonesAleatorios, 0, $numSalones);
	return $nuevo;
}

function actualizarPosibilidadesHora($posibilidades,$dia,$hora)
{
	$posNew = $posibilidades;
	$posNew[$dia][$hora] = 0;
	return $posNew;
}

/*
	REgresa un array  con el número de grupos q el profe tiene para esa materia, el key del array es el id del profe
*/
function getProfesoresImpartenMateria($listaProfes, $idAsignatura)
{
	$profes = array();
	$grupos = array();
	DBConn("localhost","siscap2","siscap2","siscap2");
        $SQL =  "SELECT idProfesor,numGrupos FROM asignaturas_profesor WHERE idAsignatura=".$idAsignatura."";
        $query =mysql_query($SQL);

	while ($rs = mysql_fetch_array($query)) 
	{
        	array_push($profes, $rs['idProfesor']);
		$grupos[$rs['idProfesor']] = $rs['numGrupos'];
       	}

        mysql_close();
	$gruposDisponibles = array();	
	foreach($listaProfes as $idProfe)
	{
		if (in_array($idProfe, $profes))
		{
			$gruposDisponibles[$idProfe] = $grupos[$idProfe];
		}
	}
	
        return $gruposDisponibles;	
}
/*
	HorariosProfes son los horarios de los profesores que imparten idAsignatura
	Horarios Salon son los horarios de todos los salones
	idAsignatura es el id de lab o cómputo, segun corresponda
	numProfes el número de profes que se tienen que asignar por salón
*/
function asignarLaboratorios(&$horariosProfes, &$horariosSalon, &$gruposPorProfe, $idAsignatura, $idLaboratorio, $numProfes)
{
	$keysSalones = array_keys($horariosSalon);
	$asignado = false;
	foreach($keysSalones as $salon)
	{	//Va a regresar una cadena donde los $numProfes primeros elementos separados por "-" son los ids de los profes y los sig
		//son dia y hora
		$asignado = asignarLaboratorio($horariosProfes, $horariosSalon[$salon], $gruposPorProfe, $idAsignatura, $idLaboratorio, $numProfes);
		if ($asignado != false)
		{
			//Hay que actualizar los horarios de los profes y del salon
			$respuesta = array();
			$respuesta = explode("-", $asignado);
			$total = count($respuesta) -2;
			$profes = "";
			$hora = $respuesta[ count($respuesta) - 1];
			$dia = $respuesta[ count($respuesta) - 2];
			for($i = 0; $i < $total; $i++)
			{
				$profes = $profes."-".$respuesta[$i];
				$horariosProfes[$respuesta[$i]][$hora][$dia] = "ASIGNADO-".$idLaboratorio."-".$salon;
                        	$horariosProfes[$respuesta[$i]][$hora+1][$dia] = "ASIGNADO-".$idLaboratorio."-".$salon;
			}
			$profes = substr($profes, 1);
			$horariosSalon[$salon][$hora][$dia] = $profes."-".$idLaboratorio;
                        $horariosSalon[$salon][$hora+1][$dia]= $profes."-".$idLaboratorio;
			###########################################################################
			# Aqui hay q asignar al profe de la materia , en respuesta[0]
			##########################################################################
			if ($idAsignatura != $idLaboratorio)
				asignarMateriaDeLab($respuesta[0], $horariosProfes[$respuesta[0]], $idAsignatura, $salon, $horariosSalon[$salon]);
			##########################################################################
		}
	}
}
/*
	Se recibe solamente el array de horario con profes que dan laboratorio
	El laboratorio es solamente una sesión
	$gruposProfe tiene dos elementos un array con los id de los profes que dan la materia (key id materia)
	El segundo elemento el array con los ids de los profes que dan el laboratorio (key id laboratorio)
*/
function getProfesComputo(&$horariosProfes, &$gruposPorProfe, $hora, $dia, $numProfes)
{
	$disponiblesMateria = array();
	$result = array();
	
	$idMateria = key($gruposPorProfe);
	$keysProfesMateria = array_keys($gruposPorProfe[$idMateria]);//array_keys($horariosProfes);
	#echo "HORA = $hora DIA = $dia <br>";
	$sum = 0;
	foreach($keysProfesMateria as $idProfe)
	{	//Se usa el idLab pqq estamos asignando esa materia
		if ($gruposPorProfe[$idMateria][$idProfe] >0 && $horariosProfes[$idProfe][$hora][$dia] == 1 && $horariosProfes[$idProfe][$hora][$dia] == 1 )
		{
			#echo "###Si estuvo disponible el profe $idProfe<br>";
			array_push($disponiblesMateria, $idProfe);			
		}
		$sum += $gruposPorProfe[$idMateria][$idProfe];
		if ($gruposPorProfe[$idMateria][$idProfe] > 0)
		{
			#echo "GRUPOS DISPONIBLE COMPUTO = ".$gruposPorProfe[$idMateria][$idProfe]."<br>";
			#printHorarioProfe($idProfe, $horariosProfes[$idProfe]);
		}
	}
	#echo "DisponiblesMateria ".count($disponiblesMateria)."<br>";
	if (count($disponiblesMateria)>=2)#num profes computo
	{
		$ale = generarAleatorio(count($disponiblesMateria));
		$i = 0; $j =0;
		while($i < 2 && $j < count($ale))
		{
			$id = $disponiblesMateria[ $ale[$j++] - 1];
			array_push($result, $id);
			$gruposPorProfe[$idMateria][$id]--;
			$i++;
		}
	}
	else
	{
		#echo "###NO hay disponibles MATERIA SUMA = $sum <br>";
		return false;
	}
	if (count($result) == 2)
	{
		return $result;
	}
	else
	{
		#echo "###NO hay disponibles SUMA = $sum <br>";
		return false;
	}
}
/*
	Se recibe solamente el array de horario con profes que dan laboratorio
	El laboratorio es solamente una sesión
	$gruposProfe tiene dos elementos un array con los id de los profes que dan la materia (key id materia)
	El segundo elemento el array con los ids de los profes que dan el laboratorio (key id laboratorio)
*/
function getProfesLaboratorio(&$horariosProfes, &$gruposPorProfe, $hora, $dia, $numProfes)
{
	$disponiblesMateria = array();
	$disponiblesLab = array();
	$result = array();
	
	list($idMateria, $idLab) = array_keys($gruposPorProfe);
	$keysProfesMateria = array_keys($gruposPorProfe[$idMateria]);//array_keys($horariosProfes);
	$keysProfesLab = array_keys($gruposPorProfe[$idLab]);//array_keys($horariosProfes);
	
	#echo "HORA = $hora DIA = $dia <br>";
	$sum = 0;
	foreach($keysProfesMateria as $idProfe)
	{	//Se usa el idLab pqq estamos asignando esa materia
		if ($gruposPorProfe[$idMateria][$idProfe] >0 
			&& $gruposPorProfe[$idLab][$idProfe] >0	&& $horariosProfes[$idProfe][$hora][$dia] == 1 && $horariosProfes[$idProfe][$hora][$dia] == 1 )
		{
			#echo "###Si estuvo disponible el profe $idProfe<br>";
			#echo "ID PROFE = ".$idProfe." GRUPOS DISPONIBLE LAB = ".$gruposPorProfe[$idMateria][$idProfe]."<br>";
			array_push($disponiblesMateria, $idProfe);			
		}
	}
	#echo "DisponiblesMateria ".count($disponiblesMateria)."<br>";
	if (count($disponiblesMateria)>=1)
	{
		$ale = generarAleatorio(count($disponiblesMateria));
		$id = $disponiblesMateria[ $ale[0] - 1];
		array_push($result, $id);
		$gruposPorProfe[$idLab][$id]--;
		$gruposPorProfe[$idMateria][$id]--;
	}
	else
	{
		#echo "###NO hay disponibles MATERIA SUMA = $sum <br>";
		return false;
	}

	//Aqui ya agregamos el profe que da la materia
	foreach($keysProfesLab as $idProfe)
	{
		if ($gruposPorProfe[$idLab][$idProfe] >0 && $horariosProfes[$idProfe][$hora][$dia] == 1 && $horariosProfes[$idProfe][$hora][$dia] == 1 )
		{
			#echo "###Si estuvo disponible el profe $idProfe<br>";
			#echo "ID PROFE LAB = ".$idProfe." GRUPOS DISPONIBLE = ".$gruposPorProfe[$idLab][$idProfe]."<br>";
			array_push($disponiblesLab, $idProfe);			
		}
	}

	if (count($disponiblesLab)>=2)
	{
		$ale = generarAleatorio(count($disponiblesLab));
		$i = 0; $j =0;
		while($i < 2 && $j < count($ale))
		{
			$id = $disponiblesLab[ $ale[$j++] - 1];
			if ($id != $result[0])
			{
				array_push($result, $id);
				$gruposPorProfe[$idLab][$id]--;
				$i++;
			}
		}
	}
	if (count($result) == 3)
	{
		return $result;
	}
	else
	{
		#echo "###NO hay disponibles SUMA = $sum <br>";
		$gruposPorProfe[$idLab][$result[0]]++;
		$gruposPorProfe[$idMateria][$result[0]]++;
		for($i = 1; $i < count($result) - 1; $i++)
		{
			$gruposPorProfe[$idLab][$result[$i]]++;
		}
		return false;
	}
}
/*
	$horariosProfes solo tiene los horarios de los profes que dan laboratorio y tienen horas disponibles todavia
*/
function asignarLaboratorio(&$horariosProfes, &$horarioSalon, &$gruposPorProfe, $idAsignatura, $idLaboratorio, $numProfes)
{
	$keysSalones = array_keys($horarioSalon);
	$asignado = false;
	$posibilidades = generarPosibilidades($horarioSalon);
	$total = contarPosibilidades($posibilidades);
	if ($total <=0)
		return $asignado;
	//Hay posibilidades en el salón
	$dias = array();
	$dias = crearDiasDisponibles($posibilidades);
	#Ya tenemos las sesiones que tenemos que asignar		
	#iniciamos el for para cada sesion, necesitamos encontrar un lugar para TODAS, si falta uno se regresa false!
	$profesAsignados = array();
	$aleDias = generarAleatorio(5);
	while($asignado == false && count($dias) && count($aleDias))
	{
		$iDia = array_shift($aleDias);
		if (count($dias) > 0 && $dias[$iDia] == 1)//Si el dia esta disponible
		{
			#El dia esta disponible, ahora hay q checar si tiene horas disponibles
			$dia = $iDia;
			$flagHoras = false;
			while($flagHoras == false)
			{
				$horasDia = array();
				$horasDia = getPosibilidadesDia($posibilidades,$dia);
				if (count($horasDia) >= 2) #TEST
				{ #Todavia tiene horas disponibles en ese dia
				  #Elegir una aleatoria
					$aleaHoras = generarAleatorio(count($horasDia)); 
					$iHora = $aleaHoras[0] - 1;
					#echo "HORAS DIA <br>";
					#print_r($horasDia);
					#echo "<br>";
					$hora = getHoraDisponible($horasDia);
				  	if ($hora != false)
					{
						#Ahora hay que obtener los 3 profes que tengan esa hora disponible
						#echo "Si hay Hora HORA = $hora DIA = $dia<br>";
						if ($idAsignatura == $idLaboratorio)
						{
							$profesAsignados = getProfesComputo($horariosProfes, $gruposPorProfe ,$hora,$dia, $numProfes);
						}
						else
						{
							$profesAsignados = getProfesLaboratorio($horariosProfes, $gruposPorProfe ,$hora,$dia, $numProfes);
						}
						if ($profesAsignados != false)
						{ //Aqui terminamos
							$flagHoras = true;
							$asignado = "";
							for($iProfes = 0; $iProfes < $numProfes; $iProfes++)
							{
								$asignado = $asignado."-".$profesAsignados[$iProfes];
							}
							$asignado = $asignado."-".$dia."-".$hora;
							$asignado = substr($asignado, 1);
						}//FIN IF profesAsignados !=false
						else
						{ //No hubo los 3 profes en esa hora, entonces removemos la hora
							$posibilidades[$dia][$hora] = 0;	
							$posibilidades[$dia][$hora+1] = 0;
							if (count(getPosibilidadesDia($posibilidades,$dia)) == 0)
								$flagHoras = true;	
						}//FIN ELSE profesAsignados == false
					}
					else //ELSE HORA = false
					{
						#echo "ActualizarPosibilidades ELSE HORA FALSE dia = $dia <br>";
						#printPosibilidades($posibilidades);
						$posibilidades = actualizarPosibilidades($posibilidades,$dia);
						$flagHoras = true;						
					}
				}//FIN IF Count(horasDia)
				else
				{ #hay que quitar el dia pq ya no tiene horas
					#echo "hay que quitar el dia pq ya no tiene horas<br>";
					$posibilidades = actualizarPosibilidades($posibilidades,$dia);
					//if (contarPosibilidades($posibilidades) == 0)
					//{
						$flagHoras = true;
					//}
				}//FIN ELSE Count(horasDia)
			} //FIN WHILE flagHoras
		}//FIN IF diaDisponible 
		else
		{	#Hay q eliminar el dia de las posibilidades
			#Si no tiene horas, entonces lo quitamos del array de posibilidades
			#osea, eliminamos el dia
			#echo "ELSE actualizarPosibilidades<br>";
			$posibilidades = actualizarPosibilidades($posibilidades,$dia);
		} //FIN ELSE diaDisponible
		$dias = crearDiasDisponibles($posibilidades);
	}#FIN WHILE asignado && count(dias)
	return $asignado;
}

function crearHashSalonesIDS($listaSalonesIDS)
{
	DBConn("localhost","siscap2","siscap2","siscap2");
	$hashSalonIDNumSalon = array();
	foreach($listaSalonesIDS as $id)
	{
		$SQL =  "SELECT numAula FROM aulas WHERE idAula=".$id."";
        	$query =mysql_query($SQL);
		$rs = mysql_fetch_array($query); 
		$hashSalonIDNumSalon[$id] = $rs['numAula'];
	}
        mysql_close();
	return $hashSalonIDNumSalon;
}
/*
	$nombreLab = key para acceder a los ids en la clase Laboratorios
*/
function actualizarLaboratorios($nombreLab, $numProfes, $keysProfes, &$horariosProfes, &$horariosSalon)
{
	$idMateria = Laboratorios::$IDS_LABS[$nombreLab]['MATERIA'];
	$idLab = Laboratorios::$IDS_LABS[$nombreLab]['LAB'];
	$gruposPorProfe = array(); //El primer elemento es el array con los profes que dan la materia y el segundo los que dan el lab
	$gruposPorProfe[$idMateria] = getProfesoresImpartenMateria($keysProfes, $idMateria);//Regresa un array donde el key es el id del profe y el value el # grupos para IDMat
	$gruposPorProfe[$idLab] = getProfesoresImpartenMateria($keysProfes, $idLab);
	$profesLabs = array_keys($gruposPorProfe[$idLab]); //Ya contiene a los que dan la materia 
	$profesMateria = array_keys($gruposPorProfe[$idMateria]);
	$horariosProfesLabs = array();
	foreach($profesLabs as $idPL)
	{
		$horariosProfesLabs[$idPL] = $horariosProfes[$idPL];
		#printHorarioProfe($idPL, $horariosProfes[$idPL]);
		#echo "ID PROFE LAB = $idPL<br>";
	}
	foreach($profesMateria as $idM)
	{
		$horariosProfesLabs[$idM] = $horariosProfes[$idM];
		#printHorarioProfe($idPL, $horariosProfes[$idPL]);
		#echo "ID PROFE LAB = $idPL<br>";
	}

	asignarLaboratorios($horariosProfesLabs, $horariosSalon, $gruposPorProfe, $idMateria, $idLab, $numProfes);//regresa array profes, array salones
	foreach($profesLabs as $idPL)
	{
		$horariosProfes[$idPL] = $horariosProfesLabs[$idPL];
	}
	foreach($profesMateria as $idM)
	{
		$horariosProfes[$idM] = $horariosProfesLabs[$idM];
	}

	return $gruposPorProfe;	
}

function iniciar($semestre, $profesLista, $salonesLista)
{
	$final = array();
	$result = array();
	$MIN = 9999;
	for($i = 0; $i < 10; $i++)
	{
		$result = execute($semestre, $profesLista, $salonesLista);
		if ($result[2] < $MIN)
		{
			$final[0] = $result[0]; #Profes
			$final[1] = $result[1]; #Salones
			$MIN = $result[2];			
			$final[2] = $result[3];
		}
		$result = array();	
	}
	echo "MIN = $MIN<br>";
	actualizarAsignaturasGrupos($final[2]);
	actualizarHorarioActualProfesor(array_keys($final[0]), $final[0]);
	return $final;
}


function asignarMateriaDeLab($idProfe, &$horariosProfe, $idAsignatura, $salonID, &$horariosSalon)
{
//echo "<p>PROFE ID = ".$idProfe." MATERIA = ".$idAsignatura." SALON = ".$salonID." SESIONES = ".$sesiones."</p>";
	#####################################################################
	#Antes de elegir un salon, tenemos que ver si el profe tiene un horario disponible para dar la clase
	$asignado = false;
	$flagAsignado = false;
	global $SEMESTRE;
	$materiasSem1 = getMateriasSemestre($SEMESTRE);
	$asignado = asignarHorarios3($idProfe, $horariosProfe, $materiasSem1, $idAsignatura, $horariosSalon);
	//$asignado = asignarHorarios2($idProfe,$horariosProfes[$idProfe],$materiasSem1, $idAsignatura, 1);
	if ($asignado == false)
	{
		#Quitar materia, ya no tiene horas para darla
		#echo "asignarMateriaDeLab => No hay horario<br>";
	}
	else
	{
		#echo "asignarMateriaDeLab => ASIGNADO = $asignado<br>";
		#$HT  = $HT - ($materiasSem1[$idAsignatura] ['sesiones'] * 2);
		$horariosProfe = actualizarHorarioProfe($idProfe,$horariosProfe, $asignado,$salonID);
		#echo "DESP de llamar ACTUALIZAR  HT = ".$HT."<br>";
		$flagAsignado = true;
		#printHorarioProfe($idProfe, $horariosProfes[$idProfe]);
		$horariosSalon = actualizarHorarioSalon($idProfe, $asignado, $horariosSalon);
		#$totalSalonesMateriaProfe[$idProfe][$idAsignatura]--;
		#$auxMS = $materiasSalones[$idAsignatura];
		#$materiasSalones[$idAsignatura] = removerElemento($salonID2, $auxMS);#
		/*echo "<br>#### SALONES ACTUALIZADOS => SALON ID = $salonID2 <br>";
		print_r($materiasSalones[$idAsignatura]);
		echo "<br>----------------------------<br>";*/
		#$salones = $totalSalonesMateriaProfe[$idProfe][$idAsignatura];
		//echo "Ejecuta el break del foreach salones";
	}
}

function execute($semestre, $profesLista, $salonesLista)
//function iniciar($semestre, $profesLista, $salonesLista)
{
	#echo "SEMESTRE $semestre<br>";
	global $SEMESTRE, $TURNO, $INICIO, $FINAL;
	$SEMESTRE = $semestre;
	switch($semestre)
	{
		case 1 : $TURNO = "MATUTINO"; break;
		case 3 : $TURNO = "VESPERTINO"; $INICIO = 9; $FINAL = 16; break;
		default : $TURNO = "MATUTINO"; break;
	}
	############################
	//Hacemos el split de la lista de ID's
	$profesoresSemestre =  explode(",",$profesLista);	
	$salonesSemestre = explode(",",$salonesLista);
	####################################################
	#Crear hash con los ids y num de salon
	$salonesIDNum = crearHashSalonesIDS($salonesSemestre);
	####################################################
	DBConn("localhost","siscap2","siscap2","siscap2");  
      	$materiasSem1 = getMateriasSemestre($semestre);
        $materiasSalones = array();
        $idsMaterias = array_keys($materiasSem1);
        foreach($idsMaterias as &$idMateria)
        {
                $salonesAleatorios = generarAleatorio(count($salonesSemestre));
                $materiasSalones[$idMateria] = $salonesAleatorios;
        }
	$profesoresSem1 = getProfesoresSem($semestre,$profesLista); #key es el Id de los profes que dan materias de ese $semestre y [asignaturas] es un array q tiene los ids de las asignaturas del semestre
        #Ahora necesitamos asignairles sus horas totales
        $profesoresMateriasHash = calcularHorasProfe($profesoresSem1, $TURNO);
        #Ahora vamos a asignar profesor-materia-salon (no horarios)
        $keysProfes = array_keys($profesoresMateriasHash);
        $horariosProfes = array();
	$horariosSalon = inicializarHorariosSalon($salonesAleatorios); #idSalon(1-20),hora(1-8),dia(1-5)
	$totalSalonesMateriaProfe = array(); 
	$horasDisponiblesProfe = array();
	###########################################################
	//iniciar Horarios Profes
	
	foreach($keysProfes as &$idProfe)
	{
		if (tieneHorarioBD($idProfe))
		{	
			$horariosProfes[$idProfe] = array();
			$horariosProfes[$idProfe] = getHorarioProfe($idProfe, $horariosProfes[$idProfe]);
		}
		else
		{
			#echo "PROFE ID = ".$idProfe." NO TIENE HORARIO<br>";
			continue;
		}
	}
	###########################################################

	###########################################################
	# Asignar laboratorios
	###########################################################
	//Get Horarios Profes que imparten labs
	$gruposPorProfeLab = array();
	switch($semestre)
	{
		case 1:
			$gruposPorProfeLab = actualizarLaboratorios("QUI1", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[71] = array();//Lab Quimica
			#echo "ASIGNAR COMPUTO<br>"; $materiasSalones[82] = array();			
			$salonesAsignados[71] = $gruposPorProfeLab[71];
			$salonesAsignados[82] = $gruposPorProfeLab[82];
			$gruposPorProfeLab = actualizarLaboratorios("COM1", 2, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[9] = array();//Computo 1
			$salonesAsignados[9] = $gruposPorProfeLab[9];
			//Deporte $materiasSalones[]
			break;
		case 2:
			actualizarLaboratorios("QUI2", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[72] = array();//Lab Quimica
			//actualizarLaboratorios("19", 2, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[19] = array();//Computo 2
			$materiasSalones[17] = array();;//Deporte
			break;
		case 3:
			$gruposPorProfeLab = actualizarLaboratorios("FIS1", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[74] = array();//
			$salonesAsignados[25] = $gruposPorProfeLab[25];
			$salonesAsignados[74] = $gruposPorProfeLab[74];
			$gruposPorProfeLab = actualizarLaboratorios("BIO1", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[73] = array();
			$salonesAsignados[2] = $gruposPorProfeLab[2];
			$salonesAsignados[73] = $gruposPorProfeLab[73];

			$materiasSalones[26] = array();;//Deporte
			break;
		case 4:
			actualizarLaboratorios("76", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[76] = array();//
			actualizarLaboratorios("75", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[75] = array();//
			$materiasSalones[35] = array();;//Deporte
			break;
		case 5:
			actualizarLaboratorios("79", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[79] = array();//
			actualizarLaboratorios("80", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[80] = array();//
			actualizarLaboratorios("77", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[77] = array();//
			$materiasSalones[42] = array();;//Deporte
			break;
		case 6:
			actualizarLaboratorios("81", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[81] = array();//
			actualizarLaboratorios("78", 3, $keysProfes, $horariosProfes, $horariosSalon); $materiasSalones[78] = array();//
			$materiasSalones[48] = array();;//Deporte
			break;
	}
	############################################################
	# Actualizar salonesMateria con los labs y materias q ya se asignaron
	#$materiasSalones = actualizarSalonesAsignados($horariosProfes);
	###########################################################
       foreach($keysProfes as &$idProfe)
        {	
		#echo "<br>--------------START------------<br>ID profe = ".$idProfe."<br>";
		if( $profesoresMateriasHash[$idProfe]['horasTotales'] == 0)
			continue;
		if (!tieneHorarioBD($idProfe))
		{	
			#echo "PROFE ID = ".$idProfe." NO TIENE HORARIO<br>";
			continue;
		}
		######################################
                $HT  = $profesoresMateriasHash[$idProfe]['horasTotales'];// - contarHorasLabs($idProfe, $horariosProfes);
                $HT = $HT - actualizarHorasTotales($idProfe, $horariosProfes);
		$materias = array();
                $materias = $profesoresMateriasHash[$idProfe]['asignaturas']; #solo tiene un array de idsAsignaturas
		$materias = removerLabs($semestre, $materias);
		foreach($materias as &$m)
		 {
			$totalSalonesMateriaProfe[$idProfe][$m] = obtenerSalonesPorMateriaPorProfe($idProfe,$m);
			#$totalSalonesMateriaProfe[$idProfe][$m] = actualizar
		 }
		while($HT > 0 && count($materias) > 0)
                {
			if (count($materias) > 0)
                        {
                                $aleaMaterias = array();
                                $t = count($materias);
                                $idAsignatura = -1;
                                #Para elegir una materia al azar elegimos la primera (el id)
                                if (count($materias) == 1)
                                        $idAsignatura = $materias[0];
                                else
                                {
                                        $aleaMaterias = generarAleatorio($t);
                                        $idAsignatura = $materias[$aleaMaterias[0]-1];
                                }
                                #Checamos si el profe tiene horas disponibles para las sesiones de esa materia
                                #echo "<p> Sesiones asignatura : ".($materiasSem1[$idAsignatura]['sesiones'] * 2)." HT PROFE  = ".$HT." </p>"; 
				#Antes de asignar la materia hay que checar que tenga horas en las totales, pero tambien en los slots
				#pq dependiendo del número de sesiones hay que checar q haya en distintos dias.. :S
				$sesiones = $materiasSem1[$idAsignatura]['sesiones'];
				if ( ($materiasSem1[$idAsignatura] ['sesiones'] * 2) <= $HT)
                                {
	                        	$salones = $totalSalonesMateriaProfe[$idProfe][$idAsignatura];
					#print "<p>Salones = ".$salones."</p>";
					#140212 salonesArray va a cortar el arrayAleatorio de salonesSemestre hasta el número de salones que tiene
					#asignados el profe para esa materia
					if ($salones > 0)
                                        { #Todavia hay salones disponibles, tomamos el primero
						$flagAsignado = false;
						$salonesArray = $materiasSalones[$idAsignatura];
						$disponible = false;
                        		foreach($salonesArray as &$salonID)
						{
							//echo "<p>PROFE ID = ".$idProfe." MATERIA = ".$idAsignatura." SALON = ".$salonID." SESIONES = ".$sesiones."</p>";
							#####################################################################
							#Antes de elegir un salon, tenemos que ver si el profe tiene un horario disponible para dar la clase
							$asignado = asignarHorarios2($idProfe,$horariosProfes[$idProfe],$materiasSem1, $idAsignatura, 1);
							if ($asignado == false)
							{
								#Quitar materia, ya no tiene horas para darla
								$materias = removerElemento($idAsignatura, $materias);
								continue;
							}
							#########################################################################################
							#En este punto ya tenemos las horas para la materia, hay que ver si hay un salon disponible
							# se eligio el salon, ahora hay q ver si, el salon tiene el horario disponible 
							#########################################################################################
							#echo "Checo a validarSalon<br>";
							$disponible = validarSalon($salonID,$horariosSalon[$salonID],$asignado); 
							if ($disponible == true)
							{
								$HT  = $HT - ($materiasSem1[$idAsignatura] ['sesiones'] * 2);
								$horariosProfes[$idProfe] = actualizarHorarioProfe($idProfe,$horariosProfes[$idProfe], $asignado,$salonID);
								$flagAsignado = true;
								$horariosSalon[$salonID] = actualizarHorarioSalon($idProfe, $asignado, $horariosSalon[$salonID]);
								$totalSalonesMateriaProfe[$idProfe][$idAsignatura]--;
								$materiasSalones[$idAsignatura] = removerElemento($salonID,$salonesArray);
								#echo "Ejecuta el break del foreach salones";
								break; #Salir del foreach
							}
							else
							{
								#echo "<br>SALON NO DISPONIBLE???? idMateria = $idAsignatura<br>";
							}
						}  #fin foreach
						#hay que hacer algo si no se asigna?
						#140212 Pendiente!!!
						if ($flagAsignado == false)
						{
							$salonesArray2 = $materiasSalones[$idAsignatura];
							$x = count($salonesArray);	
                        				foreach($salonesArray2 as &$salonID2)
							{								
								
								if ( ($materiasSem1[$idAsignatura] ['sesiones'] * 2) > $HT || $salones == 0)
                                				{
									break;
								}
								$asignado = asignarHorarios3($idProfe,$horariosProfes[$idProfe],$materiasSem1,$idAsignatura,$horariosSalon[$salonID2]);
								
								if ($asignado != false)
								{
									$HT  = $HT - ($materiasSem1[$idAsignatura] ['sesiones'] * 2);
								$horariosProfes[$idProfe] = actualizarHorarioProfe($idProfe,$horariosProfes[$idProfe], $asignado,$salonID2);
								#echo "DESP de llamar ACTUALIZAR  HT = ".$HT."<br>";
								$flagAsignado = true;
								#printHorarioProfe($idProfe, $horariosProfes[$idProfe]);
								$horariosSalon[$salonID2] = actualizarHorarioSalon($idProfe, $asignado, $horariosSalon[$salonID2]);
								$totalSalonesMateriaProfe[$idProfe][$idAsignatura]--;
								$auxMS = $materiasSalones[$idAsignatura];
								$materiasSalones[$idAsignatura] = removerElemento($salonID2, $auxMS);#
								/*echo "<br>#### SALONES ACTUALIZADOS => SALON ID = $salonID2 <br>";
								print_r($materiasSalones[$idAsignatura]);
								echo "<br>----------------------------<br>";*/
								$salones = $totalSalonesMateriaProfe[$idProfe][$idAsignatura];
								//echo "Ejecuta el break del foreach salones";
								}
								else
								{
									/*echo "No funciono asignar3i ID PROFE = $idProfe ID SALON = $salonID2<br>";
									printHorarioProfe($idProfe, $horariosProfes[$idProfe]);
									printHorarioSalon($horariosSalon, $salonID2);
									echo "<br>--NEXT SALON--<br>";*/
								}
							}
							#echo "------------END-----------------";
							$flagAsignado = true;
                                                	$materias = removerElemento($idAsignatura, $materias);
						}
					} //Fin hay salones
					else
					{       #No hay salones disponibles, para esa materia, la eliminamos de la lista
                                                $materias = removerElemento($idAsignatura, $materias);
					}
				}	
				else	####################################################################
				{	#quitar materia pq ya no le alcanzan las horas al profe
                                        $materias = removerElemento($idAsignatura, $materias);
				}	
                        } //FIN IF materias disponibles
                        else
                        {
                                #Ya no hay materias
                               #echo "<p> SE asignaron todas las materias </p>";
                        }
                } #fin WHILE HT > 0
	$horasDisponiblesProfe[$idProfe] = $HT;
        }# FIN FOREACH PROFEID

	#####################################################################
	# Imprimir los grupos que faltan por asignar por profe	
	#####################################################################
	echo "GRUPOS QUE FALTAN POR ASIGNAR<br>";
	#Agregar los laboratorios y materias
	$labs = array_keys($salonesAsignados);
	foreach($labs as $l)
	{
		$profesSalones = $salonesAsignados[$l];
		$keysP = array_keys($profesSalones);
		foreach($keysP as $p)
		{
			$totalSalonesMateriaProfe[$p][$l] = $profesSalones[$p];
		}	
	}
	/*foreach($keysProfes as $keyP)
	{
		echo "ID PROFE = $keyP<br>";
		$otroMaterias = array();
		$otroMaterias = $profesoresMateriasHash[$keyP]['asignaturas']; 
		foreach($otroMaterias as $m)
		{
			$salones = $totalSalonesMateriaProfe[$keyP][$m];
			echo "Materia = $m Salones = ".$salones." <br>";
		}
	}*/
	#####################################################################
	
	#####################################################################
	# Regresar los horarios de salon con los idsSalon no numAula	
	#####################################################################
	$nuevosHorariosSalon = intercambiarIDSSalones($horariosSalon, $salonesIDNum);
	#####################################################################
	# Cambiar los horariosProfesor para que en lugar de tener el numaula tengan
	# el idAula
	#####################################################################
	$nuevosHorariosProfes = intercambiarNumAulaPorID($keysProfes, $salonesIDNum, $horariosProfes); #Hora,Dia
	#####################################################################
        $huecos = calcularHuecos($idsMaterias, $materiasSalones);
	
	$resultados = array();
	$resultados[0] = $nuevosHorariosProfes;
	$resultados[1] = $nuevosHorariosSalon;
	$resultados[2] = $huecos;
	$resultados[3] = $totalSalonesMateriaProfe;
	return $resultados;
}

?>
