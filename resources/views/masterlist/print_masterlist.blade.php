<?php
    $GLOBALS['schoolname'] = ($configuration) ? $configuration->name : '';
    $GLOBALS['address']    = ($configuration) ? $configuration->address : '';
    $GLOBALS['display']    = ($display) ? $display : '';
    $GLOBALS['imgurl']     = 'images/'.$configuration->logo;
    $GLOBALS['periodname'] = ($period) ? $period->name : '';

    class PDF extends FPDF{

        // Page header
        function Header(){
            $this->AddFont('Century Gothic','B','gothicb.php');
            $this->AddFont('Century Gothic','','century_gothic.php');
            $this->SetFont('Century Gothic','B',13);

            $this->Image($GLOBALS['imgurl'], $this->GetX(), $this->GetY(), 20,20);

            $this->Cell(0,5, $GLOBALS['schoolname'],0,0,'C');
            $this->Ln(5);
            
            $this->SetFont('Century Gothic','B',9);
            $this->Cell(0,5,$GLOBALS['address'],0,0,'C');
            $this->Ln(5);
            
            $this->SetFont('Century Gothic','B',12);
            $this->Cell(0,5,'Master List ('.$GLOBALS['display'].')',0,0,'C');
            $this->Ln(5);
            $this->SetFont('Century Gothic','B',10);
            $this->Cell(0,5,$GLOBALS['periodname'],0,0,'C');
            $this->Ln(10);

            $this->SetFont('Century Gothic','B',10);
            $this->setDrawColor(189, 195, 199);
            $this->Cell(10,5,'No.',1,0,'C');
            $this->Cell(30,5,'ID Number',1,0,'C');
            $this->Cell(100.8,5,'Name',1,0,'C');
            $this->Cell(35,5,'Program',1,0,'C');
            $this->Cell(10,5,'Year',1,0,'C');
            $this->Cell(10,5,'Units',1,0,'C');
            $this->Ln();
        }

        // Page footer
        function Footer(){
            // Position at 1.5 cm from bottom
            $this->SetY(-10);
            $this->Cell(0,6,'Page '.$this->PageNo().'/{nb}',0,0,'R');
        }

        function WordWrap(&$text, $maxwidth){
            $text = trim($text);
            if ($text==='')
                return 0;
            $space = $this->GetStringWidth(' ');
            $lines = explode("\n", $text);
            $text = '';
            $count = 0;

            foreach ($lines as $line){
                $words = preg_split('/ +/', $line);
                $width = 0;

                foreach ($words as $word){
                    $wordwidth = $this->GetStringWidth($word);
                    if ($wordwidth > $maxwidth){
                        // Word is too long, we cut it
                        for($i=0; $i<strlen($word); $i++){
                            $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                            if($width + $wordwidth <= $maxwidth){
                                $width += $wordwidth;
                                $text .= substr($word, $i, 1);
                            }else{
                                $width = $wordwidth;
                                $text = rtrim($text)."\n".substr($word, $i, 1);
                                $count++;
                            }
                        }
                    }elseif($width + $wordwidth <= $maxwidth){
                        $width += $wordwidth + $space;
                        $text .= $word.' ';
                    }else{
                        $width = $wordwidth + $space;
                        $text = rtrim($text)."\n".$word.' ';
                        $count++;
                    }
                }
                $text = rtrim($text)."\n";
                $count++;
            }
            $text = rtrim($text);
            return $count;
        }

        function GetMultiCellHeight($w, $h, $txt, $border=null, $align='J') {
            // Calculate MultiCell with automatic or explicit line breaks height
            // $border is un-used, but I kept it in the parameters to keep the call
            //   to this function consistent with MultiCell()
            $cw = &$this->CurrentFont['cw'];
            if($w==0)
                $w = $this->w-$this->rMargin-$this->x;
            $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
            $s = str_replace("\r",'',$txt);
            $nb = strlen($s);
            if($nb>0 && $s[$nb-1]=="\n")
                $nb--;
            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $ns = 0;
            $height = 0;
            while($i<$nb)
            {
                // Get next character
                $c = $s[$i];
                if($c=="\n")
                {
                    // Explicit line break
                    if($this->ws>0)
                    {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    //Increase Height
                    $height += $h;
                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $ns = 0;
                    continue;
                }
                if($c==' ')
                {
                    $sep = $i;
                    $ls = $l;
                    $ns++;
                }
                $l += $cw[$c];
                if($l>$wmax)
                {
                    // Automatic line break
                    if($sep==-1)
                    {
                        if($i==$j)
                            $i++;
                        if($this->ws>0)
                        {
                            $this->ws = 0;
                            $this->_out('0 Tw');
                        }
                        //Increase Height
                        $height += $h;
                    }
                    else
                    {
                        if($align=='J')
                        {
                            $this->ws = ($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                            $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                        }
                        //Increase Height
                        $height += $h;
                        $i = $sep+1;
                    }
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $ns = 0;
                }
                else
                    $i++;
            }
            // Last chunk
            if($this->ws>0)
            {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            //Increase Height
            $height += $h;

            return $height;
        }
    }

    // Instanciation of inherited class
    $pdf = new PDF('P','mm',array(215.9,330.2));
    //$pdf = new PDF('L','mm',array(215.9,279.4));
    $pdf->SetTopMargin(15);
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->AddFont('Century Gothic','B','gothicb.php');
    $pdf->AddFont('Century Gothic','','century_gothic.php');

        $h1 = $pdf->GetMultiCellHeight(25, 5, $period->name, $border=null, $align='J');
        if($masterlist)
        {
        	$x = 1;
        	$totalstudents = 0;
        	$totalmale = 0;
        	$totalfemale = 0;

        	foreach($masterlist as $k => $r)
            {
        		$pdf->SetFont('Century Gothic','',9); 
                $pdf->setDrawColor(189, 195, 199);
                $pdf->Cell(10,5,$x,1,0,'C');
        		$pdf->Cell(30,5,$r->student->user->idno,1,0,'C');
        		$pdf->Cell(100.8,5,iconv("UTF-8", "ISO-8859-1//TRANSLIT", $r->student->name),1,0,'L');
        		$pdf->Cell(35,5,$r->program->code,1,0,'L');
        		$pdf->Cell(10,5,$r->year_level,1,0,'C');
        		$pdf->Cell(10,5,$r->enrolled_units,1,0,'C');
        		$pdf->Ln();

        		if($r->student->sex == 1)
                {
        			$totalmale++;
        		}else{
        			$totalfemale++;
        		}

        		$totalstudents++;
        		$x++;				
        	}
        	$pdf->Cell(0,5, '********** Nothing follows (Male: '.$totalmale.', Female: '.$totalfemale.', TOTAL: '.$totalstudents.')***********',1,0,'C');
        }else{
        	$pdf->Cell(0,5, 'No records to be displayed',1,0,'C');
        }

    $pdf->SetTitle('Print Master List');
    $pdf->Output();
    exit;
?>
