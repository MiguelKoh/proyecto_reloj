<?php
require('fpdf.php');

class PDFTable extends FPDF
{
    var $widths;
    var $aligns;
    var $headerURL = '';

    function SetHeaderURL($newHeaderURL)
    {
        $this->headerURL = $newHeaderURL;
    }

    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        $this->aligns = $a;
    }

    function Row($data)
    {
        //Calculate the height of the row
        $nb = 0;

        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;

        //Issue a page break first if needed
        $this->CheckPageBreak($h);

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $aa = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';

            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();

            //Draw the border
            $this->Rect($x, $y, $w, $h);

            //Print the text
            if ($i == 2) {
                $this->MultiCell($w, 5, utf8_decode($data[$i]), 0, $aa);
            } else {
                $this->MultiCell($w, 5, utf8_decode($data[$i]), 0, $a);
            }

            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }

        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        $cw =& $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function Header()
    {
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(100, -10, utf8_decode('Sistema de Control de Cargas Académicas de la Preparatoria 2 UADY'), 0, 0, 'C');
        $this->Cell(0, -10, $this->headerURL, 0, 0, 'C');
        $ruta = rutaimagenes("images");
        $this->Image($ruta . "lineas1.jpg", 18, 15, 130);
        $this->Image($ruta . "membrete1.jpg", 140, 8, 55);
        $this->SetFont('Arial', 'B', 8);
        $this->Ln(5);
        $this->Ln(22);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Cell(0, 5, date("d/m/y H:i:s"), 0, 0, 'C');
    }
}