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
            // Title
            /*$image1 = '<img src="images/slc.JPEG" width="100" height="100" />';
            $this->Cell( 40, 40, $this->Image($image1, $this->GetX(), $this->GetY(), 33.78), 0, 0, 'L', false );*/
            // $this->Cell( 15, 15, , 1, 0, 'L');
            $this->Image($GLOBALS['imgurl'], $this->GetX(), $this->GetY(), 15,15);

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
            //$this->Cell(10,5,'NO.',1,0,'C');
            $this->setDrawColor(189, 195, 199);
            $this->Cell(10,5,'No.',1,0,'C');
            $this->Cell(30,5,'ID Number',1,0,'C');
            $this->Cell(100.8,5,'Name',1,0,'C');
            $this->Cell(35,5,'Course',1,0,'C');
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

        function GetMultiCellHeight($w, $h, $txt, $border = null, $align = 'J')
        {
            return PDFHelper::GetMultiCellHeight($this, $w, $h, $txt, $border, $align);
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
        // if($data['masterlist']){
        // 	$x = 1;
        // 	$totalstudents = 0;
        // 	$totalmale = 0;
        // 	$totalfemale = 0;
        // 	foreach($data['masterlist'] as $k => $r){
        // 		$pdf->SetFont('Century Gothic','',9); 
        //         $pdf->setDrawColor(189, 195, 199);
        //         $pdf->Cell(10,5,$x,1,0,'C');
        // 		$pdf->Cell(30,5,$r->idno,1,0,'C');
        // 		$name = $r->lname.', '.$r->fname.' '.$r->mname.' '.$r->suffix;
        // 		$pdf->Cell(100.8,5,iconv("UTF-8", "ISO-8859-1", $name),1,0,'L');
        // 		$pdf->Cell(35,5,$r->code,1,0,'L');
        // 		$pdf->Cell(10,5,$r->year,1,0,'C');
        // 		$pdf->Cell(10,5,$r->enrolledunits,1,0,'C');
        // 		$pdf->Ln();
        // 		if($r->sex == 1){
        // 			$totalmale++;
        // 		}else{
        // 			$totalfemale++;
        // 		}
        // 		$totalstudents++;
        // 		$x++;				
        // 	}
        // 	$pdf->Cell(0,5, '********** Nothing follows (Male: '.$totalmale.', Female: '.$totalfemale.', TOTAL: '.$totalstudents.')***********',1,0,'C');
        // }else{
        // 	$pdf->Cell(0,5, 'No records to be displayed',1,0,'C');
        // }

    $pdf->SetTitle('Print Master List');
    $pdf->Output();
    exit;
?>
