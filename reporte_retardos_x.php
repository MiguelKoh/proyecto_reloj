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
function descuento($idEmp,$idPeriodo,$horaini,$horafin,$idTipoE) {
    //valido el tipo de usuario
    if($idTipoE==1){// es profesor

    }
    if($idTipoE==2){// técnico
        
    }
    if($idTipoE==3){//administrativo
        
    }



 return $diaD;

}
    setlocale(LC_ALL,'esp');
    $fechaini = "16/01/2020";  //CAMBIAR FECHA DEL PERÍODO
    $fechafin = "31/01/2020";  //CAMBIAR FECHA DEL PERÍODO

    /*$BuscoPeriodo="SELECT idperiodo FROM periodos WHERE fecha_inicio='".$fechaini."' 
    AND fecha_fin='".$fechafin."'";
    $queryBusca=mysqli_query($cn,$BuscoPeriodo);
    $busca=mysqli_fetch_array($queryBusca);*/
    //$id_periodo=$busca['idperiodo'];  
    $id_periodo=186;     
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

   $sente = "Select DISTINCT(e.idEmp),e.Nombre,d.Nombre as departamento,e.idTipo, t.Descripcion as tipoEmp from empleado e
INNER JOIN departamento d ON d.idDepto=e.idDepto
INNER JOIN tipoempleado t ON t.idTipo=e.idTipo
INNER JOIN horarios_semestre hs ON hs.idEmp=e.idEmp
WHERE e.estatus is null AND e.idTipo=1 AND hs.id_semestre=16 ORDER BY e.idTipo, e.Nombre";
    $result = mysqli_query($cn,$sente); 
    $verifica=mysqli_num_rows($result);    
    while ($row = mysqli_fetch_array($result)) {
        $tot_min_descuento=0;
        

        
        $SQLadmin="SELECT cantidad FROM descuento_administrativos 
        WHERE idEmp=".$row['idEmp']." AND idPeriodo=179";
        $que=mysqli_query($cn,$SQLadmin);
        $numP=mysqli_fetch_array($que);
        $Ocupado=0;
        $Ocupado=$numP['cantidad'];
        $nuevo=0;
        $cantidad=$Ocupado*15;
                if($cantidad>0 && $cantidad<90){
                    $texto="En la da. de agosto utilizaste ".$cantidad." de los 90 minutos permitidos al mes.";
                  }else{
                     if($cantidad>0 && $cantidad>=90){
                    $texto="En la 2da. quincena de agosto agotaste los 90 minutos permitidos al mes.";
                    }
                  }
      

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
	$pdf->Rect(8, 40, 193, 75, 'D');	  
	$pdf->SetFont('Arial','B',10);

	$pdf->Cell(35,5,"Fecha",0,0,'C');
	$pdf->Cell(20,5,"Horario",0,0,'C');
	$pdf->Cell(20,5,"Entrada",0,0,'C');
	$pdf->Cell(20,5,"Salida",0,0,'C');
	$pdf->Cell(20,5,"Permiso",0,0,'C');
	$pdf->Cell(20,5,"Ausente",0,0,'C');
	$pdf->Cell(20,5,"Descuento",0,0,'C');
	$pdf->Cell(30,5,"Observaciones",0,1,'C');
	$sum=0;
        $tot_min_descuento = 0;
                $tot_min_descuento_formato=0;
	 $sente1 = "SELECT DISTINCT(fecha),horaini,horafin FROM checadas_nuevo WHERE idEmp=" . $row['idEmp']." AND idPeriodo='".$id_periodo."'  GROUP BY fecha ORDER BY fecha ASC";
     $result1 = mysqli_query($cn,$sente1) ;
     $numr=mysqli_num_rows($result1);
             
                $quita=5*$numr;
                $numbaja=65-$quita;                 
                $tot_min_descuento = 0;
                               
            while ($row1 = mysqli_fetch_array($result1)) { //sacamos las fechas 
                    $separar =  explode('/',$row1['fecha']);
                    $anio = $separar[2];
                    $mes = $separar[1];
                    $dia = $separar[0];     

               $SQLeventos="SELECT tipoempleado FROM tcalendario 
                WHERE fecha='".$anio."-".$mes."-".$dia."'";
                $querySQL=mysqli_query($cn,$SQLeventos);
                $existeEvento=mysqli_num_rows($querySQL);
                if($existeEvento>0){
                    $sum=$sum+5;
                }else{

                	/*$separar =  explode('/',$row1['fecha']);
		            $anio = $separar[2];
		            $mes = $separar[1];
		            $dia = $separar[0]; */    

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
                	WHERE fecha='".$row1['fecha']."' AND tipoempleado=".$idTipoE;*/
                    $SQLeventos="SELECT idPermisos FROM permisos 
                    WHERE fechaIni='".$row1['fecha']."' AND idEmp=".$row['idEmp']."";
                	$querySQL=mysqli_query($cn,$SQLeventos);
                	$existeEvento=mysqli_num_rows($querySQL);
                	if($existeEvento>0){
                		$sum=$sum+5;
					}else{
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
                    if($horascii<=$horasi && $horascif<=$horasif){                 
                       $ausente = "";
                    }else{
                        //tipo==1
                        if($idTipoE==1){//administrativos
                                
                                $enE=($horascii*60)+$horascif;
                                $enSE=($horasi*60)+$horasif;
                                if(($enE-$enSE)>5){                                   
                                    if(($nuevo+$Ocupado)<6){
                                        $minDes=($enE-$enSE)-5;
                                        //echo $minDes;
                                        if($minDes>=20 && $minDes<=60){
                                            $minDes=60;
                                        }
                                        //$nuevo++;
                                }else{
                                     $minDes=($enE-$enSE);
                                }   
                                    
                                    $minDescuento = $minDes;
                                    $tot_min_descuento += $minDescuento;
                                    
                                }
                        }
                    }

                
                if($horassf>=$horasf && $horasxf>=$horasff){                 
                       $ausente = "";
                    }else{
                        //tipo==1
                        if($idTipoE==1){//administrativos
                                
                                    $enSS=($horassf*60)+$horasxf;
                                    $enSES=($horasf*60)+$horasff;                                
                                    $minDesS=($enSES-$enSS);
                                    if($minDesS>0){
                                        $minDescuento = $minDes+$minDesS;
                                        $tot_min_descuento += $minDescuento;
                                    }
                                    //$minDescuento = $minDes+$minDesS;
                                   // $tot_min_descuento += $minDescuento;
                                    
                              
                        }
                    }
                    //$minDescuento = $row1['mindescuento'];
                    //$tot_min_descuento += $minDescuento;
					//$observaciones = $row1['observaciones'];                
                    
                    //Pasa minutos a formato hh:mm
                    $checadaIni1 = $row1['horaini'];
                    $checadaFin1 = $row1['horafin'];
                    
                    //$permiso_formato = convierte_mins_a_horas($permiso,"");
                    $minDescuento_formato = convierte_mins_a_horas($minDescuento,"");
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
                    if($minDescuento>0){
					$pdf->Cell(35,5,saber_dia($anio."-".$mes."-".$dia).", ". $row1['fecha'],0,0,'L');
					$pdf->Cell(20,5,$horario,0,0,'C');
					$pdf->Cell(20,5,$checadaIni1,0,0,'C');
					$pdf->Cell(20,5,$checadaFin1,0,0,'C');
					$pdf->Cell(20,5,$permiso_formato,0,0,'C');
					$pdf->Cell(20,5,$ausente,0,0,'C');
					$pdf->Cell(20,5,$minDescuento_formato ,0,0,'C');
					$pdf->Cell(20,5,$observaciones,0,1,'C');                   
                    }else{
                        $sum=$sum+5;
                    }

                     $minDescuento=0;
                  
				}//fin del if evento
            }
	  // # lineas del encabezado y titulaes
	 }
            //deben ser 75 maximo
	//echo $numbaja."<br>";
                $pdf->Ln($numbaja+$sum);
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(5,5,"",0,0,'C');		
	              
			      $pdf->SetFont('Arial','',8);
                  if($idTipoE==3 && $cantidad>0){
			      $pdf->Cell(90,5,$texto,0,0,'L');  
              }else{
			      $pdf->Cell(90,5,'',0,0,'L'); } 
			     $pdf->SetFont('Arial','B',10);
			   $pdf->Cell(90,5,"Total de horas a descontar:       ".$tot_min_descuento_formato,0,1,'R');
			    $pdf->SetLineWidth(.5);
				$pdf->Rect(8, 110, 193, 30, 'D');				 
				$pdf->Cell(5,5,"",0,0,'C');
				$pdf->Cell(170,5,"Firma del empleado:" ,0,1,'L');
				$pdf->Ln(40);
              $tot_min_descuento_formato=0;
              $minDescuento=0;
                 
                  $pdf->AddPage();
			}//de mostrar descuento



$pdf->Output();?>