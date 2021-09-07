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
  if($action=="approve"){

    $result_insert = mysqli_query($mysqli, "update `cca_team` SET reviewer_status='Approved', reviewer_comments='".$comments."'  where id='".$id."' ");
    echo "success";
    exit();
  }
  if($action=="reject"){
    $result_insert = mysqli_query($mysqli, "update `cca_team` SET reviewer_status='Rejected' , reviewer_comments='".$comments."'  where id='".$id."' ");
    echo "success";
    exit();
  }
}

?>

<?php include "header.php"; ?>
<style>
/*div.main{
height:100%;
}*/

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
                    <th style="text-align:center">Programme Year</th>
                    <th style="text-align:center">Audit Department</th>
                    <th style="text-align:center">Party No</th>
                    <!-- <th style="text-align:center">Status</th> -->
                    <th style="text-align:center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $result = mysqli_query($mysqli, "SELECT t.*,tr.status FROM `cca_team` t left join `cca_team_emp_role` tr on t.id=tr.team_id join `cca_mngoffice` mo on t.plan_id=mo.plan_id where tr.id is NOT NULL and  t.Dept_id='".$_SESSION['dept_id']."' and mo.emp_id='".$_SESSION['userid']."' group by team_id  ORDER BY t.id "); 

                 


                  if(mysqli_num_rows($result)>0){

                  //while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
                  $count = 0;
                  
                  while($res = mysqli_fetch_array($result)) {
                  $count++ ;
                  $plan_name = mysqli_query($mysqli, "SELECT plan_name FROM cca_plan where id = ".$res['plan_id']);
                  $plan_res = mysqli_fetch_array($plan_name);

                  $dept = mysqli_query($mysqli, "SELECT S_descr,F_descr FROM cca_departments where id = ".$res['Dept_id']);
                  $dept_res = mysqli_fetch_array($dept);

                  ?>
                        <tr class="dang" >
                          <td><?php echo $count; ?></td>
                          <td id="desgedit_<?php $res['plan_id'];?>" name="desgedit_<?php $res['plan_id'];?>" value="<?php echo $res['plan_id']?>"><?php echo $plan_res['plan_name']."-".$dept_res['S_descr']; ?></td>
                          <td><?php echo $dept_res['F_descr']; ?></td>
                          <td><span class="btn btn-warning"  data-toggle="modal" data-target="#memberModal<?php echo $res['id'];?>"><?php echo $res['team_name']; ?></td>
                         <!--  <td><?php //echo $res['audit_status']; ?></td> -->
                          <td>
                            <?php 
                              if($res['reviewer_status']!=NULL && $res['reviewer_status']!='pending'){
                                if($res['reviewer_status']=="Approved"){
                                ?>
                                <div class="btn btn-success" disabled>Submitted to FA</div>
                                <?php
                              } else if($res['reviewer_status']=="Rejected"){
                                ?>
                                 <div class="btn btn-danger" disabled>Rejected</div>
                              
                             <?php 
                                }
                              }else{
                                ?>
                                 <button type="button" class="btn btn-success" onclick="open_comment('approve','<?php echo $res['id'];?>')">Approve</button> 
                                  <div class="approve" id="approve_<?php echo $res['id'];?>" style="display:none;padding-top:5px; padding: 15px;background: lightblue;">
                                    Comments on Approval: <textarea cols="30" rows="5" name="approve_comments" id="approve_comments_<?php echo $res['id'];?>"></textarea> <button class="btn-primary" onclick="manage_team('approve','<?php echo $res['id'];?>')">Submit</button>
                                     <button type="button" class="btn-close" onclick="$('#approve_<?php echo $res['id'];?>').hide();">Close</button>
                                  </div>
                                  || 
                                  <button type="button" class="btn btn-danger"  onclick="open_comment('reject','<?php echo $res['id'];?>')">Reject</button>
                                  <div class="reject" id="reject_<?php echo $res['id'];?>" style="display:none;padding-top:5px; padding: 15px;background: lightblue;">
                                    Comments on Rejection: <textarea cols="30" rows="5" name="rejct_comments" id="rejct_comments_<?php echo $res['id'];?>"></textarea> <button class="btn-primary" onclick="manage_team('reject','<?php echo $res['id'];?>')">Submit</button>
                                     <button type="button" class="btn-close" onclick="$('#reject_<?php echo $res['id'];?>').hide();">Close</button>
                                  </div>
                                 
                              <?php
                            }
                            ?>
                           
                          </td>
                          <!-- Modal -->
                            <div class="modal fade" id="memberModal<?php echo $res['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h3 class="modal-title" id="exampleModalLabel">Team Members</h3>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <?php
                                $team_id=$res['id'];

                                $sql_lead=mysqli_query($mysqli,"select leader_id from `cca_team_emp_role` where team_id='".$team_id."' order by id desc");
                                $sql_leadresult=mysqli_fetch_row($sql_lead);

                                $sql="select * from `cca_users` where ID in (select emp_id from `cca_team_emp_role` where team_id='".$team_id."')";
                                $user_res = mysqli_query($mysqli, $sql);
                                 ?>
                                   <?php
                                   while($user_results=mysqli_fetch_array($user_res)){
                                      ?>
                                      <div class="nearby-user">
                                        <div class="row">
                                          <div class="col-md-2 col-sm-2">
                                            <img src="../images/profile_pic.png" alt="user" class="profile-photo-lg">
                                          </div>
                                          <div class="col-md-4 col-sm-7">
                                            <p><b><?php
                                             echo $user_results['Name'];
                                             if($user_results['ID']==$sql_leadresult[0]){
                                                echo "(Lead Auditor)";
                                             }else{
                                              echo "(Auditor)";
                                             }
                                             ?></b></p>
                                            <p><?php echo $user_results['username']; ?></p>
                                          </div>
                                        </div>
                                      </div>
                                     <!-- <div><img src="../images/profile_pic.png" width="10%"/><?php echo $user_results['Name'];?></div> -->
                                   <?php
                                    }
                                   ?>
                                  <div class="row">
  
                                  </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                            </div>
                        </tr> 
                        <?php

                          }
                      }
                         ?>
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
  $.post("team_approval.php",{action:action,id: id,comments: comments},function(res){
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



