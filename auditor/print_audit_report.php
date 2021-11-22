<?php
include_once('../tcpdf/tcpdf.php');
   
$tbl ='';




 //echo $tbl;exit;

// create new PDF document

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
       
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, 'CCA-Tour Report', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}


$obj_pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);

$obj_pdf->SetTitle("CCA - Report");

$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('helvetica');
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 6);
$obj_pdf->AddPage();
$content = '';
$content .= ' 
<h3 style= "text-align:center;"> COMMON AUDIT REPORT FORMAT (CAF)
Common Cadre Audit, Odisha
 </h3>
<br><br>
</hr>';
//$content .= include "audit_report.php";
 

//echo $content;exit;
$obj_pdf->writeHTML($content,true, false, false, false, '');

//$obj_pdf->Output(__DIR__ .'/pdf/tour_report.pdf', 'F');  //save pdf
$obj_pdf->Output('tour_report.pdf', 'D');
$obj_pdf->Output('tour_report.pdf', 'I');

?>
