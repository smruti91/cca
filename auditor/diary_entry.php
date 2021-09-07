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

 

 // if(isset($_POST['mngplan_id'])){
 //   $inst_id=$_POST['inst_id'];
 //   $mngplan_id=$_POST['mngplan_id'];
 //   $mngplandetails_result=mysqli_query($mysqli,"select * from `cca_manageplan` where id='".$mngplan_id."'");
 //   $mngplandetails=mysqli_fetch_array($mngplandetails_result);
 //   $team_name=find_teamname($mngplandetails['team_id'],$mysqli);
 //   $plan_name=find_planname($mngplandetails['plan_id'],$mysqli);
 // }

 if(isset($_POST['plan_id'])){
    $team_name=find_teamname($_POST['team_id'],$mysqli);
    $plan_name=find_planname($_POST['plan_id'],$mysqli);
    $leader_id=$_POST['leader_id'];
 } 

if(isset($_POST['action']) && ($_POST['action']=='workdtls')){
  $id=$_POST['id'];
  $result = mysqli_query($mysqli, "SELECT * FROM cca_audit_task where id='".$id."' "); 
  $res = mysqli_fetch_array($result);
  print_r($res['work_details']."#".$res['mandays']);exit;
}

if(isset($_POST['submit'])){

$team_id=$_POST['team_id'];
$plan_id=$_POST['plan_id'];
$datearr=$_POST['datearr'];
$datearry=explode(',', $datearr);
foreach ($datearry as $dateval) {
   $diary_date= date('Y-m-d', $dateval);
   $emp_id=$_SESSION['userid'];
  
 //print_r($_SESSION);exit;
   $objection_issued=$_POST['issued_'.$dateval];
  $objection_returned=$_POST['returned_'.$dateval];
  $workorder=$_POST['workdone_'.$dateval];
  $workdetails=$_POST['textareascnd_'.$dateval];
  $mandays=$_POST['mandays_'.$dateval];
  $add_date=date('Y-m-d');
  $add_ip=$_SERVER['REMOTE_ADDR'];
  // $issued_data
  if($workorder!=""){
   
   echo "select * from `cca_diary` where diary_date='".$diary_date."' and emp_id='".$emp_id."'";exit;
      $find_diarydate=mysqli_query($mysqli,"select * from `cca_diary` where diary_date='".$diary_date."' and emp_id='".$emp_id."'");
      if(mysqli_num_rows($find_diarydate)>0){
             mysqli_query($mysqli,"update `cca_diary` set objection_memo_issued='$objection_issued', objection_memo_returned='$objection_returned',workorder='$workorder',workdetails='$workdetails',mandays='$mandays',add_date='$add_date',add_ip='$add_ip' where diary_date='".$diary_date."' and emp_id='".$emp_id."'"); 
      }else{
		  echo "INSERT INTO `cca_diary`(`plan_id`,`team_id`,`emp_id`,`diary_date`, `objection_memo_issued`, `objection_memo_returned`, `workorder`, `workdetails`, `mandays`,`diary_status`,`add_date`,`add_ip`) VALUES ('$plan_id','$team_id','$emp_id','$diary_date','$objection_issued','$objection_returned','$workorder','$workdetails','$mandays',0,'$add_date','$add_ip')";exit;
          $result = mysqli_query($mysqli, "INSERT INTO `cca_diary`(`plan_id`,`team_id`,`emp_id`,`diary_date`, `objection_memo_issued`, `objection_memo_returned`, `workorder`, `workdetails`, `mandays`,`diary_status`,`add_date`,`add_ip`) VALUES ('$plan_id','$team_id','$emp_id','$diary_date','$objection_issued','$objection_returned','$workorder','$workdetails','$mandays',0,'$add_date','$add_ip')");
      }
  }
}
header("Location: manage_diary.php");
exit;
}
?>

<?php include "header.php"; ?>
<style>
/*div.main{
min-height:100%;
}*/

fieldset {
  background-color: #eeeeee;
  margin-bottom:50px;
}

legend {
    background-color: #0b77c4;
    color: white;
    padding: 5px 10px;
   
    /* float: left; */
    
}
.calheader{
    width: 100%;
    background-color: #93ead2;
    margin:5px;
}
.drlink {
  color: #0a75c6;
}
.drtable input{
width:147px;
}
.diary_header{
  background-color: #f39f86;
  background-image: linear-gradient(315deg, #f39f86 0%, #f9d976 74%);
  font-weight:bold;
}
.diary-page{
  background-color: #aee1f9;
  background-image: linear-gradient(315deg, #aee1f9 0%, #f6ebe6 74%);
}

</style>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>Diary</h1>
            <hr>
            <div class="calheader">
                <table width="50%" border="0" style="background-color: #bff5ea">
                  <tr>
                    <td><b>Plan: <?php echo $plan_name; ?> </b></td>
                  </tr>
                  <tr>
                   <td><b>Team: <?php  echo $team_name; ?> </b></td>
                </tr>
                </table>
            </div>
             <fieldset>
              <legend>Weekly Diary Entry for <?php echo date("d-m-Y",$_POST['fromdate']);?> to <?php echo date("d-m-Y",$_POST['todate']);?></legend>
              <?php 
                  $dates = array();
                  $format = 'd-m-Y';
                  $current = $_POST['fromdate'];
                  $date2 = $_POST['todate'];
                  $stepVal = '+1 day';
                  while( $current <= $date2 ) {
                     $dates[] = date($format, $current);
                     $current = strtotime($stepVal, $current);
                  }
                 // print_r( $dates);exit();
              ?>
               

               <?php 
               	 $sel_farmrk=mysqli_query($mysqli,"select * from `cca_diary_remark` where week_start_date='".date("Y-m-d",$_POST['fromdate'])."' and team_id='".$_POST['team_id']."' and plan_id='".$_POST['plan_id']."' and fa_status='Approved'");
               	 if(mysqli_num_rows($sel_farmrk) > 0){
               	 	//$sel_farmrk_row=mysqli_fetch_array($sel_farmrk);
               	 	//print_r($sel_farmrk_row);exit;
               	 	// if($sel_farmrk_row['fa_status']=="Approved"){
               	 			echo "<font color='#f74915'><b>FA has approved the Dairy for this week. Please check the report. </b></font>";
               	 			echo "</br>";
               	 	}else{
               ?>
               <style>
	               div.main{
					 height:100%;
					}
           		</style>
                  <div class="datecontainer">
                    <form method="post" name="dairy_form" action="">
                    <?php 
                    $datarray=array();
                      for ($i=0;$i<count($dates);$i++){
                        $unixTimestamp=strtotime($dates[$i]);
                        $dayOfWeek = date("l", $unixTimestamp);
                        if($unixTimestamp<=strtotime(date('Y-m-d')) && $dayOfWeek!='Sunday'){
                        array_push($datarray, $unixTimestamp);

                        $diary_date=date('Y-m-d',$unixTimestamp);
                        $find_diarydate=mysqli_query($mysqli,"select * from `cca_diary` where diary_date='".$diary_date."' and emp_id='".$_SESSION['userid']."'");
                        $diarydate_result=mysqli_fetch_array($find_diarydate);
                        if(mysqli_num_rows($find_diarydate)>0){
                        $objection_memo_issued=$diarydate_result['objection_memo_issued'];
                        $objection_memo_returned=$diarydate_result['objection_memo_returned'];
                        $workorder=$diarydate_result['workorder'];
                        $workdetails=$diarydate_result['workdetails'];
                        $mandays=$diarydate_result['mandays'];
                        }else{
                          $objection_memo_issued="";
                          $objection_memo_returned="";
                          $workorder="";
                          $workdetails="";
                          $mandays="";
                        }
                    ?>
                       <table style="float:left;font-size:14px;width: 48%; margin:1%;" class="table table-bordered drtable">
                        <tr>
                          <td class="diary_header">Date: </td>
                          <th align="left" class="diary_header"><?php echo $dates[$i]; ?> (<?php echo $dayOfWeek; ?>)</th>
                        </tr>
                        <tr>
                          <td class="diary_header">Audit Objection Pages:</td>
                          <td class="diary_page" style="">Issued:<input type="text" name="issued_<?php echo $unixTimestamp; ?>" value="<?php echo $objection_memo_issued;?>" /> Returned: <input type="text" name="returned_<?php echo $unixTimestamp; ?>"  value="<?php echo $objection_memo_returned; ?>"/></td>
                        </tr>
                        <tr>

                          <?php
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
                          ?>
                          <td class="diary_header">Institute:</td>
                          <td class="diary_page"><?php echo $org_name; ?></td>
                        </tr>
                       
                        <tr>
                          <td class="diary_header">Auditor</td>
                          <td class="diary_page">
                              <table>
                              <tr>
                                <td>Work Done:</td>
                                <td>
                                   <?php 

                                $result = mysqli_query($mysqli, "SELECT * FROM cca_audit_task ORDER BY id "); 
                                        
                              ?>
                                 <select style="width:340px" name="workdone_<?php echo $unixTimestamp; ?>" onchange="findworkDetailscnd(this.value,'<?php echo $unixTimestamp; ?>')">
                                    <option value="">--Select one--</option>
                                    <?php 
                                    if(mysqli_num_rows($result)>0){
                                      while($res = mysqli_fetch_array($result)) {?>

                                            <option value="<?php echo $res['id']; ?>" <?php if($res['id']==$workorder){echo "selected='selected'";} ?>><?php echo $res['work_name']; ?></option>
                                   <?php  }
                                  }

                                  ?>
                                  </select>
                              </td>
                              </tr>
                              <tr>
                                <td>Work Details:</td>
                                <td><textarea cols="45" rows="5"  name="textareascnd_<?php echo $unixTimestamp; ?>" id="textareascnd_<?php echo $unixTimestamp; ?>"><?php echo $workdetails; ?></textarea></td>
                              </tr> 
                               <tr>
                                <td>Mandays:</td>
                                <td>
                                 <input type="text" value="<?php echo $mandays; ?>" name="mandays_<?php echo $unixTimestamp; ?>" id="mandays_<?php echo $unixTimestamp; ?>"/>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <?php 
                          }
                        }
                         $datarraye=implode(',', $datarray);
                      ?>
                       <input type="hidden" value="<?php echo $datarraye;?>" name="datearr" />
                       <input type="hidden" value="" name="mangplanid" />
                       <table width="60%">
                        <?php
                          if($leader_id==$_SESSION['userid']){
                        ?>
                          <!-- <tr>
                              <td colspan="2"><b>Remark by Lead Auditor:</b></td>
                              <td colspan="2"><textarea cols="50" rows="10" name="remark_leadauditor"></textarea></td>
                          </tr> -->
                          <?php 
                            }
                          ?>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="4">
                              <?php
                                $sel_leadauditorrmrk=mysqli_query($mysqli,"select * from `cca_diary_remark` where week_start_date='".date("Y-m-d",$_POST['fromdate'])."' and team_id='".$_POST['team_id']."' and plan_id='".$_POST['plan_id']."'");
                                if(mysqli_num_rows($sel_leadauditorrmrk) >0){

                                }else{ ?>
                                  <button type="submit" name="submit" class="btncca" onclick="javascript:return confirm('Are you sure to Save this ?');">Save Dairy</button>
                                <?php }
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                      </table>
                      <input type="hidden" name="team_id" value="<?php echo $_POST['team_id']; ?>" />
                      <input type="hidden" name="plan_id" value="<?php echo $_POST['plan_id']; ?>" />
                    </form>
                  </div>
                  <?php
	                  	}
	               	 
                  ?>
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
function findworkDetails(workid,date){
$.post('diary_entry.php',{id: workid,action: 'workdtls'},function(res){
  $("#textarea_"+date).val(res);
});
}

function findworkDetailscnd(workid,date){
$.post('diary_entry.php',{id: workid,action: 'workdtls'},function(res){
  var res_new=res.split('#');
  $("#textareascnd_"+date).val(res_new[0]);
  $('#mandays_'+date).val(res_new[1]);
});
}
</script>



