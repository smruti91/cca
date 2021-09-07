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

  
if(isset($_POST['start_date'])){
  $startdate=$_POST['start_date'];
  $mngplanid=$_POST['mngplan'];
  $mngplandetails_result=mysqli_query($mysqli,"select * from `cca_manageplan` where id='".$mngplanid."'");
  $mngplandetails=mysqli_fetch_array($mngplandetails_result);
  $org_id=$mngplandetails['org_id'];
}

$plan_result=mysqli_query($mysqli,"select plan_start_date from `cca_plan` where status='Planning' and dept_id='".$_SESSION['dept_id']."'");
  $plan_details=mysqli_fetch_array($plan_result);

?>

<?php include "header.php"; ?>
<style>
/*div.main{
height:100%;
}*/

fieldset {
  background-color: #eeeeee;
}

legend {
    background-color: #0b77c4;
    color: white;
    padding: 5px 10px;
    width: 166px;
    /* float: left; */
    margin-left: 30px;
}
.calheader{
    width: 100%;
    background-color: #93ead2;
    margin:5px;
}
.drlink {
  color: #0a75c6;
}
</style>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>Manage Diary</h1>
            <hr>
            <div class="calheader">
                <table width="50%" border="0" style="background-color: #bff5ea">

                  <?php 

                    $result=mysqli_query($mysqli,"select em.plan_id,em.team_id,em.leader_id,cp.plan_name,ct.team_name from `cca_team_emp_role` em,`cca_plan` cp,`cca_team` ct  where emp_id='".$_SESSION['userid']."' and cp.status='Planning' and ct.id=em.team_id and cp.dept_id='".$_SESSION['dept_id']."'");


                    $row_result=mysqli_fetch_array($result);
                    $plan_id=$row_result['plan_id'];
                    $team_id=$row_result['team_id'];
                    $leader_id=$row_result['leader_id'];
                    //print_r($row_result);
                  ?>
                  <tr>
                    <td><b>Plan: <?php echo $row_result['plan_name']?> </b></td>
                  </tr>
                  <tr>
                   <td><b>Team: <?php echo $row_result['team_name'];?> </b></td>
                </tr>
                </table>
            </div>
             <fieldset>
              <legend>Diary Details:</legend>
              <table class="table table-striped table-bordered dataTable no-footer" style="padding-bottom: 50px;">
                <tr>
                  <th align="center">sl no</th>
                  <th align="center">Weeks</th>
                  <th align="center">Manage</th>
                  <th align="center">Reports</th>
                </tr>
                
                <?php 

                 $time=strtotime($plan_details['plan_start_date']);
                   $day=date("d",$time);
                   $mon=strtolower(date("M",$time));
                   $year=date("Y",$time);

               $montharr=['jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dec'];
               
                $mva=array_search($mon,$montharr,true);

               $cnt=1;
                for($m=$mva;$m<count($montharr);$m++){

                  $month=$montharr[$m];
                  $textdt=$day." ".$month." ".$year;
                  $dt= strtotime($textdt);
                  $currdt=$dt;
                  $nextmonth=strtotime($textdt."+1 month");
                  $i=0;

                  do 
                  {
					  
                      $weekday= date("w",$currdt);
                      $nextday=7-$weekday;
                      $endday=abs($weekday-6);
                      $startarr[$i]=$currdt;
                      $endarr[$i]=strtotime(date("Y-m-d",$currdt)."+$endday day");
                      $currdt=strtotime(date("Y-m-d",$endarr[$i])."+1 day");
                    
                     if(strtotime(date("d-m-Y",$startarr[$i]))< strtotime(date("d-m-Y"))){
						 
						 
                      ?>
                      <tr>
                      <td><?php echo $cnt; ?></td>
                      <?php

                      echo "<td>". date("d-m-Y",$startarr[$i])." - ". date("d-m-Y",$endarr[$i])."</td>";
                      ?>
                      <td>
                        <a class="drlink" href="javascript: ccadatapost('diary_entry', {fromdate: '<?php echo $startarr[$i] ?>',todate: '<?php echo $endarr[$i] ?>',plan_id: '<?php echo $plan_id; ?>',team_id: '<?php  echo $team_id; ?>',leader_id: '<?php echo $leader_id;?>'}, 'post');">my Diary</a>

                        <?php
                            if($leader_id==$_SESSION['userid']){
                              ?>
                              <table>
                                <tr>
                                  <th colspan="2"><a class="drlink" href="javascript: ccadatapost('leadauditor_diary_entry', {fromdate: '<?php echo $startarr[$i] ?>',todate: '<?php echo $endarr[$i] ?>',plan_id: '<?php echo $plan_id; ?>',team_id: '<?php  echo $team_id; ?>',leader_id: '<?php echo $leader_id;?>',userid:'<?php echo $userid;?>'}, 'post');"">Team Member's Diary (Approved/submitted/ )</a></td>
                                </tr>
                              </table>

                           <?php  }
                        ?>

                      </td>
                      <td><a class="drlink" href="javascript: ccadatapost('diary_entry_report', {fromdate: '<?php echo $startarr[$i] ?>',todate: '<?php echo $endarr[$i] ?>',plan_id: '<?php echo $plan_id; ?>',team_id: '<?php  echo $team_id; ?>',leader_id: '<?php echo $leader_id;?>'}, 'post');">Diary Report </a> &nbsp;  || &nbsp;  <a class="drlink" href="#"> Backpage Diary Reports</a></td>
                      </tr>
                      <?php
                         }
                       $i++;
                       $cnt++;
                               
                  }while($endarr[$i-1]<$nextmonth);
                  }
                  ?>

              </table>
             </fieldset>

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
} );

    $(document).ready(function(){
      var date_input=$('input[name="date"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'dd-mm-yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
    })

var yearsLength = 30;
var currentYear = new Date().getFullYear();
for(var i = 0; i < 30; i++){
  var next = currentYear+1;
  var year = currentYear + '-' + next.toString();
  $('#financialYear').append(new Option(year, year));
  currentYear--;
}

</script>



