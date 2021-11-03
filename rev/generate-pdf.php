<?php
include_once('../tcpdf/tcpdf.php');
   // print_r($_POST);
  //  echo $_POST['tour_for_the_month'];
//exit;
//Unescape the string values in the JSON array
$tableData = stripcslashes($_POST['tbldata']);

//Decode the JSON array
$tableData = json_decode($tableData,TRUE);

//now $tableData can be accessed like a PHP array
//echo $tableData[1]['month'];
//exit;
//print_r($tableData);
$tbl ='';


foreach ($tableData as $row) {

     //echo($row->month);

     $tbl  .='
     <tr>
       <td >'.$row['count'].'</td>
       <td >'.$row['month'].'</td>
       <td >'.$row['year_of_account'].'</td>
       <td >'.$row['catagory_name'].'</td>
       <td >'.$row['plan_name'].'</td>
       <td >'.$row['dt_commencent'].'</td>
       <td >'.$row['dt_completion'].'</td>
       <td >'.$row['purpose'].'</td>
       <td >'.$row['distance'].'</td>
       <td >'.$row['remarks'].'</td>
       
     </tr> ';

    
}

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
<h3> CCA-Tour Report </h3>
<br><br>
</hr>
<table cellspacing="0" cellpadding="1" border="0.7" >
<tr>
         <th width="25" align="center" >Sl.No</th>
         <th width="40" align="center" >Tour For The Month</th>
         <th width="40" align="center" >Programme For The Year</th>
         <th width="40" align="center" >Tour Catagory</th>
         <th width="70" align="center" >Plan Name</th>
         <th width="50" align="center" >Date of Commencement of Tour </th>
         <th width="50" align="center" >Date of Complition of Tour</th>
         <th width="100" align="center" >Purpose</th>
         <th width="30" align="center" >Distance Covered</th>
         <th width="80" align="center" >Remarks</th>
 </tr>
';
$content .= $tbl;
$content .= '</table>';
//echo $content;exit;
$obj_pdf->writeHTML($content,true, false, false, false, '');

//$obj_pdf->Output(__DIR__ .'/pdf/tour_report.pdf', 'F');  //save pdf
$obj_pdf->Output('tour_report.pdf', 'D');
$obj_pdf->Output('tour_report.pdf', 'I');

?>
