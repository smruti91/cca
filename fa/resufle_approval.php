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
$dept1 = $_SESSION['dept_id'];
if(isset($_POST) && !empty($_POST['action'])){
  $action=$_POST['action'];
  $id=$_POST['id'];
  $comments=$_POST['comments'];
  $date = date('Y-m-d');
  if($action=="approve"){

    $result_insert =  generateSQL("UPDATE `cca_team_employee_transaction` SET `hod_status` = ?, `hod_comment` = ?,`hod_appr_dis_date`=? WHERE `id` = ? " ,array('Approved',$comments,$date,$id),true,$mysqli);
    echo "success";
    exit();
  }
  if($action=="reject"){
    $result_insert = generateSQL("UPDATE `cca_team_employee_transaction` SET `hod_status` = ?, `hod_comment` = ?,`hod_appr_dis_date`=? WHERE `id` = ? " ,array('Rejected',$comments,$date,$id),true,$mysqli);
    echo "success";
    exit();
  }
}


?>

<?php include "header.php"; ?>
<style>
div.main{
height:100%;
}

.people-nearby .google-maps{
  background: #f8f8f8;
  border-radius: 4px;
  border: 1px solid #f1f2f2;
  padding: 20px;
  margin-bottom: 20px;
}

.people-nearby .google-maps .map{
  height: 300px;
  width: 100%;
  border: none;
}

.people-nearby .nearby-user{
  padding: 20px 0;
  border-top: 1px solid #f1f2f2;
  border-bottom: 1px solid #f1f2f2;
  margin-bottom: 20px;
}

img.profile-photo-lg{
  height: 80px;
  width: 80px;
  border-radius: 50%;
}
</style>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h2>Manage Team</h2>
            <div class="row" style="padding-bottom:50px;">
              <table class="table" id="tableid">
                <thead>
                  <tr>
                    <th style="text-align:center">Sl.No</th>
                    <th style="text-align:center">Employee Name</th>
                    <th style="text-align:center">Drop Team</th>
                    <th style="text-align:center">New Team</th>
                    <!-- <th style="text-align:center">Status</th> -->
                    <th style="text-align:center">Auditor Comment</th>
                    <th style="text-align:center">Reviewer Comment</th>
                    <th style="text-align:center">Action</th>
                  </tr>
                </thead>
                <tbody>
                 <?php

$get_member = generateSQL("SELECT *  FROM `cca_team_employee_transaction` WHERE `dept_id` =? and `rev_status`=?",array($dept1,'Approved'),false,$mysqli);
$count = 0;
foreach( $get_member as $row )
{
$count++;
$get_emp_name = generateSQL("SELECT Name  FROM `cca_users` WHERE  ID= ?",array($row['emp_id']),false,$mysqli);
$get_emp_name = reset($get_emp_name);
$old_team =  generateSQL("SELECT team_name  FROM `cca_team` WHERE `dept_id` =? AND id=?",array($dept1,$row['drop_team_id']),false,$mysqli);
$old_team = reset($old_team);
$new_team = generateSQL("SELECT team_name  FROM `cca_team` WHERE `dept_id` =? AND id=?",array($dept1,$row['add_team_id']),false,$mysqli);

$new_team = reset($new_team);

                 ?> 
                 <tr class="dang" >
<td><?php echo $count; ?></td>
<td ><?php echo $get_emp_name['Name']; ?></td>
<td ><?php echo $old_team['team_name']; ?></td>
<td ><?php echo $new_team['team_name']; ?></td>
<td ><?php echo $row['prog_auditor_shq_comment']; ?></td>
<td ><?php echo $row['rev_drop_comment']; ?></td>
<td>
  <?php 
                              if($row['hod_status']!=NULL){
                                if($row['hod_status']=="Approved"){
                                ?>
                                <div class="btn hod_statusbtn-success" disabled>Approved</div>
                                <?php
                              }else{
                                ?>
                                 <div class="btn btn-danger" disabled>Rejected</div>
                             <?php 
                                }
                              }else{
                                ?>
 <button type="button" class="btn btn-success" onclick="open_comment('approve','<?php echo $row['id'];?>')">Approve</button> 

                                 <div class="approve" id="approve_<?php echo $row['id'];?>" style="display:none;padding-top:5px; padding: 15px;background: lightblue;">
                                    Comments on Approval: <textarea cols="30" rows="5" name="approve_comments" id="approve_comments_<?php echo $row['id'];?>"></textarea> <button class="btn-primary" onclick="manage_team('approve','<?php echo $row['id'];?>')">Submit</button>
                                     <button type="button" class="btn-close" onclick="$('#approve_<?php echo $row['id'];?>').hide();">Close</button>
                                  </div>
                                  
                                  || 
                                  <button type="button" class="btn btn-danger"  onclick="open_comment('reject','<?php echo $row['id'];?>')">Reject</button>
                                  <div class="reject" id="reject_<?php echo $row['id'];?>" style="display:none;padding-top:5px; padding: 15px;background: lightblue;">
                                    Comments on Rejection: <textarea cols="30" rows="5" name="rejct_comments" id="rejct_comments_<?php echo $row['id'];?>"></textarea> <button class="btn-primary" onclick="manage_team('reject','<?php echo $row['id'];?>')">Submit</button>
                                     <button type="button" class="btn-close" onclick="$('#reject_<?php echo $row['id'];?>').hide();">Close</button>
                                  </div>
                                 
                              <?php
                            }
                            ?>
</td>
</tr>
                 <?php } ?>
                </tbody>
              </table>
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
    } );

 function manage_team(action,id){
  if(action=="approve"){
    var comments=$("#approve_comments_"+id).val();
  }
  if(action=="reject"){
    var comments=$("#rejct_comments_"+id).val();
  }
  $.post("resufle_approval.php",{action:action,id: id,comments: comments},function(res){
    if(res=="success"){
      location.reload();
    }
  });
 }

 function open_comment(action,id){
  if(action=="approve"){
   $("#approve_"+id).css('display','block');
    $("#reject_"+id).css('display','none');
  }
  if(action=="reject"){
   $("#reject_"+id).css('display','block');
    $("#approve_"+id).css('display','none');
  }
 }
</script>



