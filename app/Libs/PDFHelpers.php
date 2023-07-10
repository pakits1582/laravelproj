<?php

namespace App\Libs;

class PDFHelpers
{
    public static function GetMultiCellHeight($pdf, $w, $h, $txt, $border=null, $align='J') {
        // Calculate MultiCell with automatic or explicit line breaks height
        // $border is un-used, but I kept it in the parameters to keep the call
        //   to this function consistent with MultiCell()
        $cw = $pdf->CurrentFont['cw'];
        if($w==0)
            $w = $pdf->w-$pdf->rMargin-$pdf->x;
        $wmax = ($w-2*$pdf->cMargin)*1000/$pdf->FontSize;
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
                if($pdf->ws>0)
                {
                    $pdf->ws = 0;
                    $pdf->_out('0 Tw');
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
                    if($pdf->ws>0)
                    {
                        $pdf->ws = 0;
                        $pdf->_out('0 Tw');
                    }
                    //Increase Height
                    $height += $h;
                }
                else
                {
                    if($align=='J')
                    {
                        $pdf->ws = ($ns>1) ? ($wmax-$ls)/1000*$pdf->FontSize/($ns-1) : 0;
                        $pdf->_out(sprintf('%.3F Tw',$pdf->ws*$pdf->k));
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
        if($pdf->ws>0)
        {
            $pdf->ws = 0;
            $pdf->_out('0 Tw');
        }
        //Increase Height
        $height += $h;

        return $height;
    }
}