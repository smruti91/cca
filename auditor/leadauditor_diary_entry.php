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
    $userid=$_POST['userid'];
 } 

if(isset($_POST['action']) && ($_POST['action']=='workdtls')){
  $id=$_POST['id'];
  $result = mysqli_query($mysqli, "SELECT * FROM cca_audit_task where id='".$id."' "); 
  $res = mysqli_fetch_array($result);
  print_r($res['work_details']."#".$res['mandays']);exit;
}

// if(isset($_POST['submit'])){

// $team_id=$_POST['team_id'];
// $plan_id=$_POST['plan_id'];
// $datearr=$_POST['datearr'];
// $datearry=explode(',', $datearr);
// foreach ($datearry as $dateval) {
//    $diary_date= date('Y-m-d', $dateval);
//   $emp_id=$userid;
  
//  //print_r($_POST);
//    $objection_issued=$_POST['issued_'.$dateval];
//   $objection_returned=$_POST['returned_'.$dateval];
//   $workorder=$_POST['workdone_'.$dateval];
//   $workdetails=$_POST['textareascnd_'.$dateval];
//   $mandays=$_POST['mandays_'.$dateval];
//   $add_date=date('Y-m-d');
//   $add_ip=$_SERVER['REMOTE_ADDR'];
//   // $issued_data
//   if($workorder!=""){
   
//       $find_diarydate=mysqli_query($mysqli,"select * from `cca_diary` where diary_date='".$diary_date."' and emp_id='".$emp_id."'");
//       if(mysqli_num_rows($find_diarydate)>0){
//              mysqli_query($mysqli,"update `cca_diary` set objection_memo_issued='$objection_issued', objection_memo_returned='$objection_returned',workorder='$workorder',workdetails='$workdetails',mandays='$mandays',add_date='$add_date',add_ip='$add_ip' where diary_date='".$diary_date."' and emp_id='".$emp_id."'"); 
//       }else{
//           $result = mysqli_query($mysqli, "INSERT INTO `cca_diary`(`plan_id`,`team_id`,`emp_id`,`diary_date`, `objection_memo_issued`, `objection_memo_returned`, `workorder`, `workdetails`, `mandays`,`diary_status`,`add_date`,`add_ip`) VALUES ('$plan_id','$team_id','$emp_id','$diary_date','$objection_issued','$objection_returned','$workorder','$workdetails','$mandays',0,'$add_date','$add_ip')");
//       }
//   }
// }
// header("Location: manage_diary.php");
// exit;

// }

if(isset($_POST['action'])){
  $week_start_date=date('Y-m-d',$_POST['week_start_date']);
  $week_end_date=date('Y-m-d',$_POST['week_end_date']);
  $team_id=$_POST['team_id'];
  $pln_id=$_POST['pln_id'];
  $remark_leadauditor=$_POST['remark_leadauditor'];

  $add_date=date('Y-m-d');
  $add_ip=$_SERVER['REMOTE_ADDR'];
  $add_by=$_SESSION['userid'];

// ..........find the records wheather all the members of the team have entered the diary for the week............

$datearray=explode(',', $_POST['datearray']);

//find team members
$findteam=mysqli_query($mysqli,"select emp_id from `cca_team_emp_role` where plan_id='".$pln_id."' and team_id='".$team_id."'");
while($team_emps=mysqli_fetch_array($findteam)){
  foreach ($datearray as $datekey) {
  $diary_entrychecksql=mysqli_query($mysqli,"select * from `cca_diary` where  plan_id='".$pln_id."' and team_id='".$team_id."' and diary_date='".date('Y-m-d',$datekey)."' and emp_id='".$team_emps['emp_id']."'");
  if(mysqli_num_rows($diary_entrychecksql)==0){
    echo "not entered";exit;
  }
 }
}


// ..........find the records wheather all the members of the team have entered the diary for the week............

  $find_lead_auditorremark=mysqli_query($mysqli,"select * from `cca_diary_remark` where team_id='".$team_id."' and week_start_date='".$week_start_date."'");
  if(mysqli_num_rows($find_lead_auditorremark)>0){
      mysqli_query($mysqli,"update `cca_diary_remark` set lead_auditor_remark='".$remark_leadauditor."' where team_id='".$team_id."' and week_start_date='".$week_start_date."'");
  }else{
    mysqli_query($mysqli,"INSERT INTO `cca_diary_remark`(`plan_id`, `team_id`, `week_start_date`, `week_end_date`, `lead_auditor_remark`, `add_by`, `add_date`, `add_ip`) VALUES ('$pln_id','$team_id','$week_start_date','$week_end_date','$remark_leadauditor','$add_by','$add_date','$add_ip')");
  }
  echo "success";exit;
}

// ...........send to FA......

if(isset($_POST['action_send'])){
  $id=$_POST['remark_id'];
   
  if($update_fastatus=mysqli_query($mysqli,"update `cca_diary_remark` set fa_status='pending' where id='".$id."'")){
    echo 'success';exit;
  }
}

?>

<?php include "header.php"; ?>
<style>
div.main{
height:100%;
}

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
              


                 $team_res=mysqli_query($mysqli,"select cu.Name,ct.emp_id as userid from `cca_team_emp_role` ct,`cca_users` cu where ct.team_id='".$_POST['team_id']."' and ct.emp_id=cu.ID");
                  if(mysqli_num_rows($team_res)>0){
                    while($teamres_rows=mysqli_fetch_array($team_res)){
                      $userid=$teamres_rows['userid'];
                ?>

                <table width="100%">
                  <tr>
                    <td><h3><?php echo $teamres_rows['Name']; ?></h3></td>
                  </tr>
                </table>
              
               <hr>
                  <div class="datecontainer">
                    <?php 
                    $datarray=array();
                      for ($i=0;$i<count($dates);$i++){
                        $unixTimestamp=strtotime($dates[$i]);
                        $dayOfWeek = date("l", $unixTimestamp);
                        if($unixTimestamp<=strtotime(date('Y-m-d')) && $dayOfWeek!='Sunday'){
                        array_push($datarray, $unixTimestamp);

                        $diary_date=date('Y-m-d',$unixTimestamp);
                        $find_diarydate=mysqli_query($mysqli,"select * from `cca_diary` where diary_date='".$diary_date."' and emp_id='".$userid."'");
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
                          <td class="diary_page" style="">Issued:<input type="text" name="issued_<?php echo $unixTimestamp; ?>" value="<?php echo $objection_memo_issued;?>"  readonly disabled="disabled" /> Returned: <input type="text" name="returned_<?php echo $unixTimestamp; ?>"  value="<?php echo $objection_memo_returned; ?>" readonly disabled="disabled"/></td>
                        </tr>
                        <tr>

                          <?php
                          $d1=date('Y-m-d',$unixTimestamp);
                          $findmngplan=mysqli_query($mysqli,"select * from `cca_manageplan` where actual_audit_startdate >='".$d1."' and institute_audit_status='Inprogress'");
                          if(mysqli_num_rows($findmngplan)>0){
                            $findmngplan_details=mysqli_fetch_array($findmngplan);
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
                                 <select style="width:340px" name="workdone_<?php echo $unixTimestamp; ?>" onchange="findworkDetailscnd(this.value,'<?php echo $unixTimestamp; ?>')" readonly disabled="disabled">
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
                                <td><textarea  readonly cols="45" rows="5"  name="textareascnd_<?php echo $unixTimestamp; ?>" id="textareascnd_<?php echo $unixTimestamp; ?>" disabled="disabled"><?php echo $workdetails; ?></textarea></td>
                              </tr> 
                               <tr>
                                <td>Mandays:</td>
                                <td>
                                 <input type="text" readonly value="<?php echo $mandays; ?>" name="mandays_<?php echo $unixTimestamp; ?>" id="mandays_<?php echo $unixTimestamp; ?>" disabled="disabled"/>
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
                  
                  </div>
                  <?php
                      }
                    }
                  ?>
                      <input type="hidden" id="datearray" value="<?php echo $datarraye?>" />
                      <input type="hidden" id="team_id" value="<?php echo $_POST['team_id']; ?>" />
                      <input type="hidden" id="plan_id" value="<?php echo $_POST['plan_id']; ?>" />
                      <input type="hidden" id="week_start_date" value="<?php echo $_POST['fromdate'];?>" />
                      <input type="hidden" id="week_end_date" value="<?php echo $_POST['todate'];?>" />
                  <table width="60%">
                        <?php
                          if($leader_id==$_SESSION['userid']){

                            $find_lead_auditorremark=mysqli_query($mysqli,"select * from `cca_diary_remark` where team_id='".$_POST['team_id']."' and week_start_date='".date('Y-m-d',$_POST['fromdate'])."' and lead_auditor_remark <>''");

                            if(mysqli_num_rows($find_lead_auditorremark)>0){
                               $find_lead_auditorremark_row=mysqli_fetch_row($find_lead_auditorremark);
                               $lead_auditor_remark=$find_lead_auditorremark_row[5];
                               //print_r($find_lead_auditorremark_row);
                            }else{
                              $lead_auditor_remark='';
                            }
                        ?>
                          <tr>
                              <td colspan="2"><b>Remark by Lead Auditor:</b></td>
                              <td colspan="2"><textarea cols="50" rows="10" id="remark_leadauditor"><?php echo  $lead_auditor_remark; ?></textarea></td>
                          </tr>
                          <?php 
                            }
                          ?>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="4"><div class="errordiv" style="display: none; color:red;"></div></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="4">

                               <?php 
                                 if(mysqli_num_rows($find_lead_auditorremark)>0){
                                   if($find_lead_auditorremark_row[7]==NULL){
                                    ?>
                              <button type="button" name="submit" class="btn btn-primary" onclick="save_remark()">Save Remarks</button>
                              <?php
                                   }
                                 }else{
                                  ?>
                                  <button type="button" name="submit" class="btn btn-primary" onclick="save_remark()">Save Remarks</button>
                                <?php }
                              ?>

                              &nbsp;&nbsp;
                               &nbsp;

                               <?php 
                                if(mysqli_num_rows($find_lead_auditorremark)>0){
                                  if($find_lead_auditorremark_row[7]!=NULL){
                                    echo "<button class='btn btn-warning disabled'>Sent to FA</button>";
                                  }else{
                                ?>
                               <button type="button" name="submit" class="btn btn-danger" onclick="sendto_fa('<?php echo $find_lead_auditorremark_row[0];?>')">Send to FA</button>
                               <?php
                                    }}else{
                                  ?>
                                  <button type="button" name="submit" class="btn btn-success" disabled onclick="sendto_fa()">Send to FA</button>
                                <?php }
                               ?>
                            </td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
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

<script>
function save_remark(){
  var cnf=confirm("Are you sure to save the remarks!!");
  if(cnf){
    var week_start_date=$("#week_start_date").val();
    var week_end_date=$("#week_end_date").val();
    var team_id=$("#team_id").val();
    var plan_id=$("#plan_id").val();
    var remark_leadauditor=$("#remark_leadauditor").val();
    var datearray=$("#datearray").val();
    alert(datearray);

    $.post("leadauditor_diary_entry.php",{datearray: datearray,action: 'save_rmrk',week_start_date: week_start_date,week_end_date: week_end_date,team_id: team_id,pln_id: plan_id,remark_leadauditor: remark_leadauditor},function(res){
      alert(res);
      if(res=='not entered'){
        $(".errordiv").html("All the members of the team have not entered the Diary for this week!");
        $(".errordiv").show();
      }else{
        window.location.href='manage_diary';
      }
    });
  }
}
function sendto_fa(id){

 var lead_auditor_remark=$("#remark_leadauditor").html();
 if(lead_auditor_remark==""){
  $(".errordiv").html("Please save the remarks before send it to FA!!");
  $(".errordiv").show();
 }else{

  $.post('leadauditor_diary_entry.php',{action_send: 'sendtofa',remark_id: id},function(res){
    if(res=="success"){
      location.reload();
    }
  });
 }
}
</script>



