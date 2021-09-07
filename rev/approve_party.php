<?php
session_start();

include("../common_functions.php");
include_once("../config.php");
$Dept_id = $_SESSION['dept_id'];

if(isset($_POST['action']) && ($_POST['action']=='approve')){

  $review_team_id = $_POST['review_id'];
  $comments=$_POST['comments'];
  $send_review = generateSQL("UPDATE `cca_team` SET `reviewer_party_status` = ?,`reviewer_party_comments`=? WHERE CONCAT(`cca_team`.`id`) =?",array('Approved',$comments,$review_team_id),true,$mysqli );
}

if(isset($_POST['action']) && ($_POST['action']=='reject')){

  $review_team_id = $_POST['review_id'];
  $comments=$_POST['comments'];
  $send_review = generateSQL("UPDATE `cca_team` SET `reviewer_party_status` =?,`reviewer_party_comments`=? WHERE CONCAT(`cca_team`.`id`) =?",array('reject',$comments,$review_team_id),true,$mysqli );
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
<script type="text/javascript">
  function send_review(action,id){
              

              if(action=="approve"){ 
         var comments=$("#approve_comments_"+id).val();  
$.post("<?php echo BASE_URL?>rev/approve_party.php",{action:action,review_id:id,comments: comments},

      function(res){
     //console.log(res);  
window.location.reload();
      }
      );
}
}


             function reject_review(action,id){
              if(action=="reject"){ 
           var comments=$("#rejct_comments_"+id).val();
$.post("<?php echo BASE_URL?>rev/approve_party.php",{action:action,review_id:id,comments: comments},

      function(res){
     //console.log(res);  
window.location.reload();

      }
      );

}
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
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>Audit Institution Approval</h1>
            <hr>
            <table class="table table-striped table-bordered" id="tableid">
              
              <tr class="Ttext1">
                    <td>Sl No </td>
                    <td>Programme For The Year </td>
                    <td>Team Number </td>
                    <td>Institution </td>
                    <td>Action</td>


<?php

$find_team = generateSQL("SELECT * FROM `cca_team` WHERE Dept_id=? ",array($Dept_id),false,$mysqli);

if(count($find_team)>0){
  $n=1;
foreach($find_team as $review_team)
  {
    
    $plan_id = $review_team['plan_id'];
    $plan = generateSQL("SELECT plan_name  FROM `cca_plan` WHERE `id` =?",array($plan_id),false,$mysqli);
    $plan_name = reset($plan);


?>

                  </tr>
                  <tr class="Ttext1">
                   <td><?php echo $n++; ?> </td>
                    <td><?php echo $plan_name['plan_name']; ?> </td>
           <td><span class="btn btn-warning"  data-toggle="modal" data-target="#memberModal<?php echo $review_team['id'];?>"><?php echo $review_team['team_name']; ?></span> </td>


           <div class="modal fade" id="memberModal<?php echo $review_team['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                $team_id=$review_team['id'];

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
                      </div></div>
          <td><button type="button" class="btn btn-primary btn-block btn-lg" data-toggle="modal" data-target="#instituteModal<?php echo $review_team['id'];?>">
          View Institution</button></td>

          
          <td>
                            <?php 
                              if($review_team['reviewer_party_status']!=NULL && $review_team['reviewer_party_status']!='pending'){
                                
                                if($review_team['reviewer_party_status']=="Approved" &&  $review_team['fa_party_status']==''){
                                ?>
                                <div class="btn btn-success" disabled>Submitted to FA</div>
                                  <?php 
                                }
                                  else if($review_team['fa_party_status']=="Approved"){
                                ?>

                                 <div class="btn btn-success" disabled>Approved By FA</div>
                              
                             
                                <?php
                              }
                             
                                 else if($review_team['reviewer_party_status']=="Rejected"){
                                ?>
                                 <div class="btn btn-danger" disabled>Rejected</div>
                              
                             <?php 
                                }
                              }
                              else{
                                ?>
                                 <button type="button" class="btn btn-success" onclick="open_comment('approve','<?php echo $review_team['id'];?>')">Approve</button> 
                                  <div class="approve" id="approve_<?php echo $review_team['id'];?>" style="display:none;padding-top:5px; padding: 15px;background: lightblue;">
                                    Comments on Approval: <textarea cols="30" rows="5" name="approve_comments" id="approve_comments_<?php echo $review_team['id'];?>"></textarea> <button class="btn-primary" onclick="send_review('approve','<?php echo $review_team['id'];?>')">Submit</button>
                                     <button type="button" class="btn-close" onclick="$('#approve_<?php echo $review_team['id'];?>').hide();">Close</button>
                                  </div>
                                  || 
                                  <button type="button" class="btn btn-danger"  onclick="open_comment('reject','<?php echo $review_team['id'];?>')">Reject</button>
                                  <div class="reject" id="reject_<?php echo $review_team['id'];?>" style="display:none;padding-top:5px; padding: 15px;background: lightblue;">
                                    Comments on Rejection: <textarea cols="30" rows="5" name="rejct_comments" id="rejct_comments_<?php echo $review_team['id'];?>"></textarea> <button class="btn-primary" onclick="reject_review('reject','<?php echo $review_team['id'];?>')">Submit</button>
                                     <button type="button" class="btn-close" onclick="$('#reject_<?php echo $review_team['id'];?>').hide();">Close</button>
                                  </div>
                                 
                              <?php
                            }
                            ?>
                           
                          </td>
         
                  </tr>
                <?php }
              }
              ?>
            </table>
           
              
               
          </div>
    </div>
</div>

<div class="modal fade" id="instituteModal<?php echo $review_team['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h3 class="modal-title" id="exampleModalLabel">Institution</h3>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                             <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                    
                    <tr class="Ttext1">
                       <td width="30" height="46" align="center">Sl No </td>
                          <td width="52" align="center">Audit Department</td>
                          <td width="94" align="center">Institution</td>
                          <td width="48" align="center">Days Alloted </td>
                          <td width="101" align="center">Date of Commencement</td>
                          <td width="84" align="center">Date of Completion</td>
                          
                          <td width="71" align="center">Pending Year Of Accounts </td>
                          
                           
                           


                    </tr>
            <?php 
            $team = $review_team['id'];
            $sl=1;
            $QueryManagePlan =  generateSQL("SELECT cp.id,cp.show_year,cm.dept_id,cp.org_id,cp.mandays_audit,cm.audit_start_date,cm.audit_end_date,cm.assign_days FROM `cca_pendingyear` cp LEFT JOIN  cca_manageplan cm on cm.org_id = cp.org_id WHERE cm.team_id =? AND cp.status=? group by cp.org_id,cp.show_year ORDER By cm.id",array($team,'1'),false,$mysqli);

  foreach ($QueryManagePlan as  $values) {
    
    $Department = generateSQL("SELECT F_descr  FROM `cca_departments` WHERE `ID` =?",array($values['dept_id']),false,$mysqli);        
    $Department = reset($Department);
    $institution = generateSQL("SELECT ddo_code  FROM `cca_institutions` WHERE `id` =?",array($values['org_id']),false,$mysqli);        
    $institution = reset($institution);

    

            ?>        
                      <tr>
                       
                        <td><?php echo $sl++?></td>
                        <td><?php echo $Department['F_descr']; ?></td>
                        <td><?php echo $institution['ddo_code']; ?></td>
                        <td><?php echo $values['assign_days']; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($values['audit_start_date'])); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($values['audit_end_date'])); ?></td>
                        
                        <td><?php echo $values['show_year']; ?></td>
                       
                        
                      </tr>
                      <?php } ?>
                      
                  </table>
                                  <div class="row">
  
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div></div>
        <!-- /.container-fluid -->
    </div>
    <div class="clear:both;"></div>
</div>
<?php include "footer.php"; ?>
    <!-- Bootstrap Date-Picker Plugin -->
<link rel="stylesheet" href="../css/bootstrap-datepicker3.css"/>