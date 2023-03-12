<?php
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
    require('includes/fpdf.php');
    function convertirDia($diaCon) {
    if($diaCon=="Lun")
        $diaD=1;
    if($diaCon=="Mar")
     $diaD=2;
    if($diaCon=="Mie")
     $diaD=3;
    if($diaCon=="Jue")
     $diaD=4;
    if($diaCon=="Vie")
     $diaD=5;
    if($diaCon=="Sab")
     $diaD=6;
    if($diaCon=="Dom")
     $diaD=7;

 return $diaD;

}

    setlocale(LC_ALL,'esp');
    $fechaini = "16/02/2020";    //CAMBIAR DE ACUERDO AL PERIODO
    $fechafin = "29/02/2020";    //CAMBIAR DE ACUERDO AL PERIODO
    /*$BuscoPeriodo="SELECT idperiodo FROM periodos WHERE fecha_inicio='".$fechaini."' 
    AND fecha_fin='".$fechafin."'";
    $queryBusca=mysqli_query($cn,$BuscoPeriodo);
    $busca=mysqli_fetch_array($queryBusca);*/
    //$id_periodo=$busca['idperiodo'];  
    $id_periodo=188;    
    error_reporting (E_ALL ^ E_NOTICE);
    $salto_linea = 0;
    $veces = 1;

    
       

class PDF extends FPDF
{
	//Cabecera de página
	function Header()
	{
		$this->Ln(10);
	}

	//Pie de página
	function Footer()
	{
		//Posición: a 1,5 cm del final
		$this->SetY(-15);

	}

}
//Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
//Image($file,$x,$y,$w=0,$h=0,$type='',$link='')
//MultiCell($w,$h,$txt,$border=0,$align='J',$fill=0)
//Creación del objeto de la clase heredada


$pdf=new PDF('P','mm','A4');
$pdf->AliasNbPages();
 $pdf->AddPage();

   $sente = "Select e.idEmp,e.Nombre,d.Nombre as departamento,e.idTipo, t.Descripcion as tipoEmp from empleados_privilegios ep
   INNER JOIN empleado e ON ep.idEmp=e.idEmp
   INNER JOIN departamento d ON d.idDepto=e.idDepto 
   INNER JOIN tipoempleado t ON t.idTipo=e.idTipo WHERE ep.checa=1 ORDER BY e.idTipo, e.Nombre";
    $result = mysqli_query($cn,$sente); 
    $verifica=mysqli_num_rows($result);    
    while ($row = mysqli_fetch_array($result)) {
        $tot_min_descuento=0;      
        

    	$clave  = $row['idEmp'];
        $nombre =$row['Nombre'];
        
        $departamento = utf8_encode($row['departamento']);
        $tipoEmp = $row['tipoEmp'];
        $idTipoE= $row['idTipo'];        
        $encabezado = $departamento . " - " . $clave . " - " . $nombre  ;    
        $clave = $row['idEmp'];
        //$nombre =  $row['nombre'];
        $departamento = utf8_encode($row['departamento']);
        $encabezado = " [" . $clave  . "] ".$nombre ." [" . $departamento."]" ;
    	/***********************si tiene caso mostrar su descuento*****/
   
 
    $pdf->SetLineWidth(.5);
    $pdf->Rect(8, 15, 193, 25, 'D');    
	$t=0;
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,5,"",0,0,'C');
	$pdf->Cell(150,5,"ESCUELA PREPARATORIA DOS - UADY",0,1,'C');
	$pdf->Cell(15,5,"",0,0,'C');
	$pdf->Cell(150,5,"REPORTE DE RETARDOS",0,1,'C');
	$pdf->Cell(5,5,"",0,0,'C');
	$pdf->Cell(160,5,$encabezado,0,1,'L');
	$pdf->Cell(5,5,"",0,0,'C');
	$pdf->Cell(16,5,"Periodo:",0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(50,5,$fechaini." al ".$fechafin,0,0,'L');
	$pdf->Cell(80,5,"  Tipo de Empleado: ".$tipoEmp,0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(40,5,"  Impreso: ".date(d."/".m."/".Y),0,1,'L');	
	$pdf->SetLineWidth(.5);
	//$pdf->Rect(8, 40, 193, 87, 'D');	  
	$pdf->SetFont('Arial','B',10);

	$pdf->Cell(35,5,"Fecha",0,0,'C');
	$pdf->Cell(20,5,"Horario",0,0,'C');
	$pdf->Cell(20,5,"Entrada",0,0,'C');
	$pdf->Cell(20,5,"Salida",0,0,'C');
	$pdf->Cell(20,5,"Permiso",0,0,'C');
	$pdf->Cell(20,5,"Ausente",0,0,'C');
	$pdf->Cell(30,5,"Hrs. Trabajadas",0,0,'C');
	$pdf->Cell(30,5,"Observac.",0,1,'C');
	$sum=0;
    $mint=0;
    $minsa=0;
    $trabajo=0;
    $tot_min_descuentoF=0;
            $tot_min_descuento = 0;
                $tot_min_descuento_formato=0;
	for($i=16;$i<=29;$i++){              //CAMBIAR DE ACUERDO AL PERIODO
        if($i!=24 && $i!=25){
            if($i<10)
                $i="0".$i;
        $fechaDia=$i."/02/2020";
         $minDescuento = 0;
         $numr=0;

                    $separar =  explode('/',$fechaDia);
                    $anio = $separar[2];
                    $mes = $separar[1];
                    $dia = $separar[0];     

                    $dia_semana = diaSemana($anio,$mes,$dia); 
                    $nombre_dia = nombre_dia ($dia_semana);
                    $diaSemV=convertirDia($nombre_dia);                

                //VERIFICAMOS SI ES SU HORARIO EL TEORICO
                $SQLhorarioWebb="SELECT horario,hora_ini,hora_fin FROM horarios_semestre 
                WHERE idEmp='".$row['idEmp']."' AND id_dia='".$diaSemV."' 
                AND id_semestre=16";//AND horario='".$horarioV."'
                //echo $SQLhorarioWebb."<br>";
                $queryDF=mysqli_query($cn,$SQLhorarioWebb);
               
                $numero=mysqli_num_rows($queryDF);
                $horarioSem =  $hogar['horario'];
                if($numero>0){
                     while($hogar=mysqli_fetch_array($queryDF)){
                    //sacolas horas qe debe trabaj
                     $horasemI =  explode(':',$hogar['hora_ini']);
                     $horasemF =  explode(':',$hogar['hora_fin']);
                    $horasee=$horasemI[0];//hora
                    $horaseee=$horasemI[1];//minutos
                    $minen=($horasee*60)+$horaseee;
                    //hora fin
                    $horass=$horasemF[0];//hora
                    $horasss=$horasemF[1];//minutos
                    $minsa=($horass*60)+$horasss;

                    $trabajo=$minsa-$minen;
                   //echo $trabajo."<br>";


                    $mint+=$trabajo;
               }
        $sente1 = "SELECT Distinct(fecha),horaini,horafin FROM checadas_nuevo WHERE idEmp=" . $row['idEmp']." AND idPeriodo='".$id_periodo."' AND fecha='".$fechaDia."' ORDER BY fecha ASC";
       $result1 = mysqli_query($cn,$sente1) ;
      $numr=mysqli_num_rows($result1);
     
                               
         if($numr>0){         
             
               
            while ($row1 = mysqli_fetch_array($result1)) { //sacamos las fechas            	
                	$separar =  explode('/',$row1['fecha']);
		            $anio = $separar[2];
		            $mes = $separar[1];
		            $dia = $separar[0];     

                    $dia_semana = diaSemana($anio,$mes,$dia); 
                    $nombre_dia = nombre_dia ($dia_semana);
                    $diaSemV=convertirDia($nombre_dia);                

                //VERIFICAMOS SI ES SU HORARIO EL TEORICO
                $SQLhorarioWeb="SELECT horario,hora_ini,hora_fin FROM horarios_semestre 
                WHERE idEmp='".$row['idEmp']."' AND id_dia='".$diaSemV."' 
                AND id_semestre=16";//AND horario='".$horarioV."'
                $queryD=mysqli_query($cn,$SQLhorarioWeb);
                $hog=mysqli_fetch_array($queryD);
                $horasemI =  explode(':',$hog['hora_ini']);
                $horasemF =  explode(':',$hog['hora_fin']);
                //hora inicio
                $horasi=$horasemI[0];//hora
                $horasif=$horasemI[1];//minutos
                //hora fin
                $horasf=$horasemF[0];//hora
                $horasff=$horasemF[1];//minutos

                	/*$SQLeventos="SELECT tipoempleado FROM tcalendario 
                	WHERE fecha='".$row1['fecha']."' AND fechaInitipoempleado=".$idTipoE;*/
                    
                	$horario = $hog['horario'];
                    $fecha = $row1['fecha'];
                    /*$Entrada = "SELECT fecha,horaini,horafin,idPeriodo FROM checadas_nuevo where idEmp=" . $row['idEmp']." and idPeriodo=138 AND fecha='".$row1['fecha']."'";*/
                    //$entra= mysqli_query($cn,$Entrada);      
                    //$entradaV = mysqli_fetch_array($entra);

                    
                    

                    $horaci =  explode(':',$row1['horaini']);
                    $horascf =  explode(':',$row1['horafin']);
                    //hora inicio
                    $horascii=$horaci[0];//hora
                    $horascif=$horaci[1];//minutos
                    //hora fin
                    $horassf=$horascf[0];//hora
                    $horasxf=$horascf[1];//minutos
                    //$horasi."-".$horascii."--".$horasif."-".$horascif
                    $minDes=0;
                    $minDesS=0;
                    $mindescuento=0;
                                        
                                
                                $enE=($horascii*60)+$horascif;
                                $enSE=($horasi*60)+$horasif;
                                                      
                                $enSS=($horassf*60)+$horasxf;
                                $enSES=($horasf*60)+$horasff;                                
                                $minDesS=($enSS-$enE);
                                $minDescuento = $minDes+$minDesS;
                                $tot_min_descuento += $minDescuento;
                                    
                              
                   
                    //$minDescuento = $row1['mindescuento'];
                    //$tot_min_descuento += $minDescuento;
					//$observaciones = $row1['observaciones'];                
                    
                    //Pasa minutos a formato hh:mm
                    $checadaIni1 = $row1['horaini'];
                    $checadaFin1 = $row1['horafin'];
                    
                    $permiso_formato = "";
                    $observaciones="";
                    $minDescuento_formato = convierte_mins_a_horas($minDescuento,"N");
                    $tot_min_descuento_formato = convierte_mins_a_horas($tot_min_descuento,"N");
                    //se agrega para cambiar formato de fecha 
                   $pdf->SetFont('Arial','',10);
                    //list($dia, $mes, $año) = explode('/', cambiafnormal($fecha)); 
                    //$fechaMuestra=$dia."-".mes($mes)."-".$año; 
					//$pdf->Cell(5,5,"",0,0,'C');
					$separar =  explode('/',$row1['fecha']);

		            $anio = $separar[2];
		            $mes = $separar[1];
		            $dia = $separar[0];
                   
					$pdf->Cell(35,5,saber_dia($anio."-".$mes."-".$dia).", ". $row1['fecha'],0,0,'L');
					$pdf->Cell(20,5,$horario,0,0,'C');
					$pdf->Cell(20,5,$checadaIni1,0,0,'C');
					$pdf->Cell(20,5,$checadaFin1,0,0,'C');
					$pdf->Cell(20,5,$permiso_formato,0,0,'C');
					$pdf->Cell(20,5,$ausente,0,0,'C');
					$pdf->Cell(30,5,$minDescuento_formato ,0,0,'C');
					$pdf->Cell(20,5,$observaciones,0,1,'C');                   
                    

                     $minDescuento=0;
                  
				}//fin del if evento
                $SQLeventos="SELECT idPermisos,fechaIni,tipo,minutosDiarios FROM permisos 
                    WHERE fechaIni='".$fechaDia."' AND idEmp=".$row['idEmp']."";
                    $querySQL=mysqli_query($cn,$SQLeventos);
                    $existeEvento=mysqli_num_rows($querySQL);
                 
                    if($existeEvento>0){
                    $fechPermiso=mysqli_fetch_array($querySQL);
                        //$sum=$sum+5;
                    $horario = $hog['horario'];
                    $fecha = $fechPermiso['fechaIni'];
                    $Permiso=$fechPermiso['minutosDiarios'];
                    $observaciones=$fechPermiso['tipo'];

                    $minDescuento = $minDes+$Permiso;
                    $tot_min_descuento += $minDescuento;

                    $permiso_formato = convierte_mins_a_horas($Permiso,"N");
                    $minDescuento_formato = convierte_mins_a_horas($minDescuento,"N");
                    $tot_min_descuento_formato = convierte_mins_a_horas($tot_min_descuento,"N");
                    //se agrega para cambiar formato de fecha 
                   $pdf->SetFont('Arial','',10);

                    $separar =  explode('/',$fecha);

                    $anio = $separar[2];
                    $mes = $separar[1];
                    $dia = $separar[0];
                    $checadaIni1="";
                    $checadaFin1="";
                   
                    $pdf->Cell(35,5,saber_dia($anio."-".$mes."-".$dia).", ". $fechPermiso['fechaIni'],0,0,'L');
                    $pdf->Cell(20,5,$horarioSem ,0,0,'C');
                    $pdf->Cell(20,5,$checadaIni1,0,0,'C');
                    $pdf->Cell(20,5,$checadaFin1,0,0,'C');
                    $pdf->Cell(20,5,$permiso_formato,0,0,'C');
                    $pdf->Cell(20,5,$ausente,0,0,'C');
                    $pdf->Cell(30,5,$minDescuento_formato ,0,0,'C');
                    $pdf->Cell(20,5,$observaciones,0,1,'C');

                    }

	  // # lineas del encabezado y titulaes
	 
    
}else{
                    $existeEvento=0;
                    $SQLeventos="SELECT idPermisos,fechaIni,tipo,minutosDiarios FROM permisos 
                    WHERE fechaIni='".$fechaDia."' AND idEmp=".$row['idEmp']."";
                    $querySQL=mysqli_query($cn,$SQLeventos);
                    $existeEvento=mysqli_num_rows($querySQL);
                 
                    if($existeEvento>0){
                    $fechPermiso=mysqli_fetch_array($querySQL);
                        //$sum=$sum+5;
                    $horario = $hog['horario'];
                    $fecha = $fechPermiso['fechaIni'];
                    $Permiso=$fechPermiso['minutosDiarios'];
                    $observaciones=$fechPermiso['tipo'];

                    $minDescuento = $minDes+$Permiso;
                    $tot_min_descuento += $minDescuento;

                    $permiso_formato = convierte_mins_a_horas($Permiso,"N");
                    $minDescuento_formato = convierte_mins_a_horas($minDescuento,"N");
                    $tot_min_descuento_formato = convierte_mins_a_horas($tot_min_descuento,"N");
                    //se agrega para cambiar formato de fecha 
                   $pdf->SetFont('Arial','',10);

                    $separar =  explode('/',$fecha);

                    $anio = $separar[2];
                    $mes = $separar[1];
                    $dia = $separar[0];
                    $checadaIni1="";
                    $checadaFin1="";
                   
                    $pdf->Cell(35,5,saber_dia($anio."-".$mes."-".$dia).", ". $fechPermiso['fechaIni'],0,0,'L');
                    $pdf->Cell(20,5,$horarioSem ,0,0,'C');
                    $pdf->Cell(20,5,$checadaIni1,0,0,'C');
                    $pdf->Cell(20,5,$checadaFin1,0,0,'C');
                    $pdf->Cell(20,5,$permiso_formato,0,0,'C');
                    $pdf->Cell(20,5,$ausente,0,0,'C');
                    $pdf->Cell(30,5,$minDescuento_formato ,0,0,'C');
                    $pdf->Cell(20,5,$observaciones,0,1,'C');
                    }
    }
    if($numr==0 && $existeEvento==0){

                    $separar =  explode('/',$fechaDia);

                    $anio = $separar[2];
                    $mes = $separar[1];
                    $dia = $separar[0];
                    $dia_semana = diaSemana($anio,$mes,$dia); 
                    $nombre_dia = nombre_dia ($dia_semana);
                    $diaSemV=convertirDia($nombre_dia);   
        $pdf->Cell(35,5,$nombre_dia.", ". $fechaDia,0,0,'L');
                    $pdf->Cell(20,5,$horarioSem ,0,0,'C');
                    $pdf->Cell(20,5,"",0,0,'C');
                    $pdf->Cell(20,5,"",0,0,'C');
                    $pdf->Cell(20,5,"",0,0,'C');
                    $pdf->Cell(20,5,"SI",0,0,'C');
                    $pdf->Cell(30,5,"" ,0,0,'C');
                    $pdf->Cell(20,5,"",0,1,'C');
            }
    }
}
 }           //deben ser 75 maximo
	//echo $numbaja."<br>";
                $pdf->Ln(60);
                $trab=0;
                if($tot_min_descuento<$mint)
                   $trab=$mint-$tot_min_descuento;

                $pdf->Cell(5,5,"",0,0,'C');     
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(80,5,"Total de horas a cubrir esta quincena:       ".convierte_mins_a_horas($mint,"N"),0,1,'R');
                $pdf->Cell(5,5,"",0,0,'C');     
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(80,5,"Total de horas trabajadas:       ".$tot_min_descuento_formato,0,1,'R');
                 $pdf->Cell(5,5,"",0,0,'C'); 
			    $pdf->Cell(80,5,"Total de horas a descontar:       ".convierte_mins_a_horas($trab,"N"),0,1,'R');
			    //$pdf->SetLineWidth(5);
				//$pdf->Rect(8, 130, 193, 30, 'D');	
                $pdf->Ln(10);			 
				$pdf->Cell(5,5,"",0,0,'C');
				$pdf->Cell(170,5,"Firma del empleado:" ,0,1,'L');
				
                $tot_min_descuento_formato=0;
                 
                  $pdf->AddPage();

                  
			//de mostrar descuento
           }       


$pdf->Output();?>