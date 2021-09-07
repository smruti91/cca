<?php
session_start();
include("../common_functions.php");
include_once("../config.php");
//============================================================+
// File name   : example_006.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 006 for TCPDF class
//               WriteHTML and RTL support
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */

$name=find_username($_SESSION['userid'],$mysqli);
// Include the main TCPDF library (search for installation path).
include_once('../tcpdf/tcpdf.php');

// class MYPDF extends TCPDF {

//     //Page header
//     public function Header() {
//         // Logo
//         $image_file = K_PATH_IMAGES.'tcpdf_logo.jpg';
//         //$this->Image($image_file, 20, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//         // Set font
//         $this->SetFont('helvetica', 'B', 20);
//         // Title
//        //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'L', 'M');

//        $this->writeHTML("<div style='font-color: red;'>Common Cadre Audit<h5>'".$name."'</h5></div><br><hr/>");
//     }

 
// }




// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
// $pdf->SetCreator(PDF_CREATOR);
 $pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Weekly Dairy Report');
// $pdf->SetSubject('TCPDF Tutorial');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Weekly Dairy Report (from '.Date('d-m-y',$_POST['fromdate']).' to '.Date('d-m-y',$_POST['todate']).')', $name);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content


// -----------------------------------------------------------------------------


 $dates = array();
                  $format = 'd-m-Y';
                  $current = $_POST['fromdate'];
                  $date2 = $_POST['todate'];
                  $stepVal = '+1 day';
                  while( $current <= $date2 ) {
                     $dates[] = date($format, $current);
                     $current = strtotime($stepVal, $current);
                  }
// Table with rowspans and THEAD
$tbl = '
<table border="1"  width="650px" cellpadding="2">
<thead>
 <tr>
  <td width="180" align="center"><b>Date</b></td>
  <td  width="470" align="center"><b>Data</b></td>
  
 </tr>
 
</thead>'

 
;
//echo $tbl;
$tbl1='';
for ($i=0;$i<count($dates);$i++){
$unixTimestamp=strtotime($dates[$i]);
$dayOfWeek = date("l", $unixTimestamp);
if($unixTimestamp<=strtotime(date('Y-m-d')) && $dayOfWeek!='Sunday'){

	 $diary_date=date('Y-m-d',$unixTimestamp);
	 //echo "select * from `cca_diary` where diary_date='".$diary_date."' and emp_id='".$_SESSION['userid']."'";
                        $find_diarydate=mysqli_query($mysqli,"select * from `cca_diary` where diary_date='".$diary_date."' and emp_id='".$_SESSION['userid']."'");
                        $diarydate_result=mysqli_fetch_array($find_diarydate);

                        //echo "select * from `cca_diary` where diary_date='".$diary_date."' and emp_id='".$_SESSION['userid']."'";exit;

                        if(mysqli_num_rows($find_diarydate)>0){
                        $objection_memo_issued=$diarydate_result['objection_memo_issued'];
                        $objection_memo_returned=$diarydate_result['objection_memo_returned'];
                        $workorder=$diarydate_result['workorder'];

                         $result_workorder = mysqli_query($mysqli, "SELECT * FROM cca_audit_task  where id='".$workorder."' "); 
                         $result_workorder_details=mysqli_fetch_array($result_workorder);
                         $result_workordername=$result_workorder_details['work_name'];

                        $workdetails=$diarydate_result['workdetails'];
                        $mandays=$diarydate_result['mandays'];
                        }else{
                          $objection_memo_issued="";
                          $objection_memo_returned="";
                          $workorder="";
                          $workdetails="";
                          $mandays="";
						   $result_workordername='';
                        }

                       
                          $d1=date('Y-m-d',$unixTimestamp);
                          $findmngplan=mysqli_query($mysqli,"select * from `cca_manageplan` where actual_audit_startdate >='".$d1."' and institute_audit_status='Inprogress'");
                          $findmngplan_details=mysqli_fetch_array($findmngplan);

                          if(mysqli_num_rows($findmngplan)>0){
                            if($findmngplan_details['org_id']!=NULL){
                              $org_name=find_institutionname($findmngplan_details['org_id'],$mysqli);
                              }else{
                                $org_name="";
                              }
                          }else{
                            $org_name="";
                          }
                        

$tbl1 .='
<tr>

  <th width="180" align="center" style=""><b>'.$dates[$i] .'('.$dayOfWeek.')</b></th>
  <td width="470"><table style="float:left;font-size:14px;width: 100%;" border="1" class="table table-bordered drtable">
                        <tbody>
                        <tr>
                          <td width="20%" class="diary_header">Audit Objection Pages:</td>
                          <td width="80%" class="diary_page" style="">Issued:'.$objection_memo_issued.' Returned: '.$objection_memo_returned.'</td>
                        </tr>
                        <tr>

                           <td class="diary_header">Institute:</td>
                          <td class="diary_page">'.$org_name.'</td>
                        </tr>
                       
                        <tr>
                          <td class="diary_header">Auditor</td>
                          <td class="diary_page">
                              <table style="float:left;font-size:14px;width: 100%;" border="0">
                              <tbody>
                              <tr>
                                <td width="20%">Work Done</td>
                                <td width="2%">:</td>
                                <td width="79%">'.$result_workordername.'</td>
                              </tr>
                              <tr>
                                <td width="20%">Work Details</td>
                                <td width="2%">:</td>
                                <td width="79%">'.$workdetails.'</td>
                              </tr> 
                               <tr>
                                <td width="20%" style="text-align: left;"> Mandays</td>
                                <td width="2%">:</td>
                                <td width="79%">
                                 '.$mandays.'
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table></td>
  
 </tr>';
//$tbl1=$tbl1.$tbl1 ;
}
}


 $tbl3='
 
</table>';

// echo $tbl.$tbl1.$tbl3;
$pdf->writeHTML($tbl.$tbl1.$tbl3, true, false, false, false, '');
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('weekly_dairy_report_'.$_SESSION['userid'].'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
