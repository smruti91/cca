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
if(isset($_POST) && !empty($_POST['action'])   ){
  
$plan = $_POST['plan'];

$dept = $_POST['dept'];

$team = $_POST['party'];

$sel_plan_dup = mysqli_query($mysqli,"SELECT * FROM `cca_team` where plan_id=".$plan." and team_name='".$team."'");
if(mysqli_num_rows($sel_plan_dup)>0){
echo "exists";
exit();
}

$sql ="INSERT INTO `cca_team` (`plan_id`, `Dept_id`, `team_name`,`created_by`,`audit_status`) VALUES ('$plan', '$dept', '$team','$userid','planning')";
$result_insert = mysqli_query($mysqli, $sql);
echo "success";
exit();
}

if(isset($_POST) && !empty($_POST['review'])){
  $team_id=$_POST['team_id'];
  $sql ="UPDATE  `cca_team` SET reviewer_status='pending' where id='".$team_id."'";
  $result_insert = mysqli_query($mysqli, $sql);
  echo "success";
  exit();
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
          
           <form class="form-horizontal" action="" method="post">
                <div class="form-group">
                  <p id="error" style="display:none;color:red;font-weight:bold;margin-left: 100px;text-align: left;"></p>
                  <label class="control-label col-sm-2">Programme for the year:</label>
                  <div class="col-sm-2">
                   
                   <select class="form-control" data-placeholder="Choose a plan" tabindex="1" name="plan" id="plan"  >
              <?php
              
              $sel_plan = mysqli_query($mysqli,"SELECT ID,plan_name FROM `cca_plan` where dept_id=".$dept1);
              echo "SELECT ID,plan_name FROM `cca_plan` where dept_id=".$dept1;


               ?> 
                      
              <option value=''>Select Programme</option>
              
              <?php while($res_plan = mysqli_fetch_array($sel_plan)) { ?>
              <option value="<?php echo $res_plan['ID']?>"  ><?php echo $res_plan['plan_name']?></option>
              <?php } ?>
            </select>
                  </div>
                </div>
                
               <div class="form-group">
                      <label class="control-label col-sm-2">Audit Department:</label>
                      <div class="col-sm-2" >
                        <?php
                           $dept = mysqli_query($mysqli, "SELECT S_descr,F_descr FROM cca_departments where id = ".$dept1);
                  $dept_res = mysqli_fetch_array($dept);?>
                    <input type="text" class="form-control" value="<?php echo $dept_res['F_descr'];?>" readonly="readonly" />
            </div>
                    </div>  
                <div class="form-group">
                  <label class="control-label col-sm-2">Party No:</label>
                  <div class="col-sm-2">
                    <select class="form-control" data-placeholder="Choose a Party" tabindex="1" name="party" id="party"  >
              <?php
              
              $sel_num = mysqli_query($mysqli,"SELECT ID,num FROM `cca_numbers`");
              


               ?> 
                      
              <option value=''>Select Party Number</option>
              
              <?php while($res_num = mysqli_fetch_array($sel_num)) { ?>
              <option value="<?php echo $res_num['num']?>"  ><?php echo $res_num['num']?> </option>
              <?php } ?>
            </select>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-4">
                    <button type="button" class="btncca" onclick="save_team()">Submit</button>
                  </div>
                </div>
              </form>
            <div class="row" style="padding-bottom:50px;">
              <table class="table" id="tableid">
                <thead>
                  <tr>
                    <th style="text-align:center">Sl.No</th>
                    <th style="text-align:center">Programme Year</th>
                    <th style="text-align:center">Audit Department</th>
                    <th style="text-align:center">Party No</th>
                    <th style="text-align:center">Status</th>
                    <th style="text-align:center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 

                          $result = mysqli_query($mysqli, "SELECT * FROM cca_team where Dept_id='".$_SESSION['dept_id']."' ORDER BY id "); 
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
                    <td><span class="btn btn-warning"  data-toggle="modal" data-target="#memberModal<?php echo $res['id'];?>"><?php echo $res['team_name']; ?></span></td>
                    <td>
                      <?php 
 $id_count = mysqli_query($mysqli, "SELECT * FROM `cca_team_emp_role`cr
LEFT JOIN `cca_team` as ct on ct.id = cr.team_id
where ct.id =".$res['id']);
 
                  $dept_res = mysqli_fetch_array($dept);                     
                      $dis_txtrv='';
                       $dis_txt="";
                      if($res['reviewer_status']==NULL){
                        $status='';

                      }
                      if(mysqli_num_rows($id_count)==0){
                        $status='';

                        $dis_txtrv="disabled";
                      }
                      if($res['reviewer_status']=='pending'){
                        $status= "Pending by Reviewer";
                        $dis_txtrv="disabled";
                      } if($res['reviewer_status']=="Approved"){
                        $dis_txt="disabled";
                         $dis_txtrv="disabled";
                          $status= "<font color='green'>Approved by Reviewer</font>";
                      } if($res['reviewer_status']=="Rejected"){
                        $status="<font color='red'>Rejected by Reviewer</font>";
                      } if($res['fa_status']=="Approved"){
                        $dis_txt="disabled";
                         $dis_txtrv="disabled";
                          $status= "<font color='green'>Approved by FA</font>";
                      } if($res['fa_status']=="Rejected"){
                        $dis_txt="";
                         $dis_txtrv="";
                          $status= "<font color='red'>Rejected by FA</font>";
                      }

                      
                     echo $status;
                      ?>
                        
                      </td>
                    <?php
                    ?>
                    <td><button type="button" class="btn btn-primary" onclick="manage_team('<?php echo $res['id'];?>')" <?php echo $dis_txt; ?>>Manage Member</button> || <button type="button" class="btn btn-success" <?php echo $dis_txtrv; ?> onclick="sendtoreviewer('<?php echo $res['id'];?>')">send to Reviewer</button></td>
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
                  </tr>  
                  <script type="text/javascript">
                     function manage_team(id){
                              $('<form action="manage_team.php" method="post"><input type="hidden" name="id" id="id" value="'+id+'"></form>').appendTo('body').submit();
                            }
                  </script>    
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

   function save_team(){
      var plan = $("#plan").val();
      var dept = '<?php echo  $dept1;?>';
      var party = $("#party").val();
      
     if(plan!="" && dept!="" && party!=''){
         $.post("team.php",{action: "add",plan:plan,dept:dept,party:party},
          function(res){
           if(res=='success'){
            window.location.reload();
           }else{
            $("#error").css("display","block");
             $("#error").html("Duplicate Record exists!");
           }
          }
          );

    }else {
      alert('A field is Blank');
    }
  }

  function sendtoreviewer(id){
    var con=confirm("Are you sure to submit the team approval request to Reviewer!!");
    if(con){
      $.post("team.php",{review: 'send_reviewer',team_id: id},function(res){
        if(res=="success"){
          location.reload();
        }
      });
    }
  }
</script>



