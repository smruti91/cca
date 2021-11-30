<?php
session_start();
include("../common_functions.php");
include_once("../config.php");
$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);

if($userid == -1)
{
header('location:../index.php');
exit;
}
?>
<?php include "header.php"; ?>
<?php
$manageplansql= mysqli_query($mysqli,"select plan_name,org_id,team_id,p.dept_id ,d.F_descr from cca_manageplan_old m,cca_plan p, cca_departments d where m.plan_id=p.id and  m.dept_id=d.ID");

$res_row=mysqli_fetch_array($manageplansql);
$orgname= find_institutionname($res_row['org_id'],$mysqli);
$team_name=find_teamname($res_row['team_id'],$mysqli);
//print_r($_SESSION);

?>
<style>
div.main{
height:100%;
}
#alert_msg{
/* position:absolute;*/
z-index:1400;
top:2%;
right:4%;
margin:10px auto;
text-align:center;
display:none;
}
</style>
<div id="wrapper">
  <?php include "leftpanel.php";?>
  <div id="page-wrapper">
    <div class="container-fluid text-center">
      <div style="" id="alert_msg" ></div>
      <div class="row content">
        <div class="col-sm-12 text-center">
          <div class="bckbtn" onclick="history.back(-1)"><img src="../images/backb.png" /><b>Back</b></div>
          <h1>Annual Audit Program of <?php echo $res_row['F_descr']; ?></h1>
          <!--  <div class="bckbtn" style="float:right;margin-top: -50px;"><img src="../images/report_icon3.png" /><a href="audit_report.php">Report</a></div> -->
          
          <hr>
          <div style="width: 100%;background-color: #42c19f2e; padding: 12px;border: 3px solid #2daab0;">
            <div style="width:33%;float:left;">
              Plan Name: <?php echo $res_row['plan_name']; ?></br>
              Party Name: <?php echo $team_name; ?> </br>
              
            </div>
            <div style="width:33%;float:right;">
              <!--   <b><?php echo $orgname; ?></b> -  General Audit -->
              <?php
              $teamsql= mysqli_query($mysqli,"select u.id,em.leader_id,u.Name from cca_users u,cca_team_emp_role em where u.id=em.emp_id and em.team_id='".$res_row['team_id']."'");
              while($team_row=mysqli_fetch_array($teamsql)){
              echo $team_row['Name'];
              if($team_row['id']==$team_row['leader_id']){
              echo "<b> (Lead Auditor)</b>";
              }else{
              echo "<b> (Auditor)</b>";
              }
              echo "<br>";
              }
              ?>
            </div>
            <div style="clear:both">
              
            </div>
          </div>
          <div class="calheader" style="padding-bottom: 50px; *background: url(../images/auditimg.jpg);background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Do not repeat the image */
            background-size: cover;">
            <fieldset>
              <table border="1" class="table" style="margin-top: 30px; border: 2px solid #2daab0;" id="report">
                <thead>
                  <tr>
                    <th  style="text-align: center;">Sl No</th>
                    <th  style="text-align: center;"> Name Of The Organisation</th>
                    <th  style="text-align: center;">Year of Accounts</th>
                    <th  style="text-align: center;">Time Period  from / to (Audit)</th>
                    <th  style="text-align: center;">Time Period  from / to (Review) </th>
                    <th  style="text-align: center;">Name of the reviewing officer</th>
                    <th  style="text-align: center;">Remark</th>
                  </tr>
                  
                </thead>
                <tbody>
                  <?php
                  $cnt = 0;
                  $report_sql = "SELECT p.org_id,p.team_id,i.institution_name ,p.audit_start_date,p.audit_end_date,p.review_start_date,p.review_end_date,p.reviwer_name,p.remarks FROM `cca_manageplan_old` p , `cca_institutions` i   WHERE p.org_id = i.id AND p.dept_id = '".$_SESSION['dept_id']."' ";
                  //echo $report_sql;
                  $report_sql_res = mysqli_query($mysqli,$report_sql);
                  while($report_row = mysqli_fetch_assoc($report_sql_res)){
                  $cnt++;
                  
                  ?>
                  <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><?php echo $report_row['institution_name']; ?></td>
                    <td>
                      <?php  $year_sql =  mysqli_query($mysqli,  "SELECT pending_year FROM `cca_pendingyear` WHERE org_id = '".$report_row['org_id']."'");
                      
                      
                      while($year_sql_row = mysqli_fetch_assoc($year_sql))
                      {
                      echo $year_sql_row['pending_year']; echo "<br>";
                      }
                      ?>
                      
                    </td>
                    <td>
                      <?php
                      $startdate = strtotime($report_row['audit_start_date']);
                      echo date("m/d/Y", $startdate);
                      echo " - ";
                      $enddate = strtotime($report_row['audit_end_date']);
                      echo date("m/d/Y", $enddate);
                      ?>
                      
                    </td>
                    
                    <td>
                      <?php
                      $review_start_date =  strtotime($report_row['review_start_date']);
                      echo $review_start_date == "-62169987208 " ? "-" :  date("m/d/Y", $review_start_date);
                      $review_end_date =  strtotime($report_row['review_end_date']);
                      echo $review_end_date == "-62169987208 " ? "-" :  date("m/d/Y", $review_end_date);
                      ?>
                      
                    </td>
                    
                    <td><?php echo $report_row['reviwer_name']; ?></td>
                    <td><?php
                      $reamark_sql = mysqli_query($mysqli,"SELECT reviewer_party_comments FROM `cca_team` WHERE id = '".$report_row['team_id']."' ");
                      $reamark = $reamark_sql->fetch_assoc();
                      echo $reamark['reviewer_party_comments'];
                    ?></td>
                    
                  </tr>
                  
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </fieldset>
            <div>
              <button class="btn btn-danger" name="<?php echo $res_row['F_descr']; ?>"  onclick="print_report(this.name)">Print</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <div class="clear:both;"></div>
</div>
<?php include "footer.php"; ?>
<!-- Bootstrap Date-Picker Plugin -->
<link rel="stylesheet" href="../css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="../js/bootstrap-datepicker.min.js"></script>
<script>
$(document).ready(function() {
$('#tableid').DataTable();

showMessage();
closeAlertBox();
} );

function print_report(inst) {
//Get the HTML of div
var divElements = document.getElementById('report').innerHTML;

const body = document.body

var oldPage = document.body.innerHTML;

//Reset the page's HTML with div's HTML only
document.body.innerHTML =
`<h2>Annual Audit Program of ${inst} </h2> <table border=1>` +
divElements + "</table>";
//Print Page
window.print();
//Restore orignal HTML
document.body.innerHTML = oldPage;

}
</script>