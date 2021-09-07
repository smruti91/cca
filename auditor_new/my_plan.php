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

 if(isset($_POST['radioval'])){
  $date_commencement=$_POST['comdate'];
  $mngplan_id=$_POST['id'];
  $radioval=$_POST['radioval'];

  mysqli_query($mysqli,"update `cca_manageplan` SET actual_audit_startdate='".$date_commencement."', institute_audit_status='Inprogress'  where id='".$mngplan_id."'");
  // print_r($_POST);
  exit;
 } 
?>

<?php include "header.php"; ?>
<style>
/*div.main{
height:100%;
}
*/
</style>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>My Plan</h1>
            <hr>
            <div class="calheader">
               <!--  <table width="50%" border="0" style="background-color: #bff5ea">
                  <tr>
                    <td><b>Plan: abcdef </b></td>
                  </tr>
                  <tr>
                   <td><b>Team: X </b></td>
                </tr>
                </table> -->
                <table class="table table-striped" id="tableid">
                  <thead>
                    <tr>
                      <th>Sl No.</th>
                      <th>Institution</th>
                      <th>Duration Of Audit</th>
                      <th>Party</th>
                      <th>Roll</th>
                      <!-- <th>Individual Duration in the Party</th> -->
                      <th>Institution Audit Status</th>
                      <th>Activity</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $team_id=find_teamid($_SESSION['userid'],$mysqli);
                   // echo $team_id;

                    $i=0;
                    // echo 'SELECT mp.*,ci.ddo_code,ci.designation,tem.team_id,tem.role_id,tem.emp_id FROM  `cca_manageplan` mp, `cca_team_emp_role` tem,`cca_institutions` ci where  mp.team_id=tem.team_id  and  tem.team_id="'.$team_id.'" and mp.org_id=ci.id group by org_id';
                     $result= mysqli_query($mysqli,'SELECT mp.*,ct.fa_party_status,ci.ddo_code,ci.designation,tem.team_id,tem.role_id,tem.emp_id,tem.leader_id FROM  `cca_manageplan` mp, `cca_team_emp_role` tem,`cca_institutions` ci, `cca_team` ct where  mp.team_id=tem.team_id  and  tem.team_id="'.$team_id.'" and tem.team_id=ct.id and ct.fa_party_status="Approved" and mp.org_id=ci.id group by org_id');
                     while($res_row=mysqli_fetch_array($result)){
                      echo 123;
                      $i++;
                      $role=find_role($res_row['role_id'],$mysqli);
                      $team_name=find_teamname($res_row['team_id'],$mysqli);
                      $inst_start=$res_row['audit_start_date'];
                       $inst_end=$res_row['audit_end_date'];
                       if($res_row['actual_audit_endate']==NULL){
                        $actual_audit_endate="";
                       }else{
                        $actual_audit_endate=date('d-m-Y',strtotime($res_row['actual_audit_endate']));
                       }
                       if($res_row['actual_audit_startdate']==NULL){
                        $actual_audit_startdate="";
                       }else{
                        $actual_audit_startdate=date('d-m-Y',strtotime($res_row['actual_audit_startdate']));
                       }
                     ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $res_row['designation']; ?></td>
                      <td>
                        <ul style="list-style: none; text-align: left;">
                          <li><b>As per Plan</b></li>
                          <li>Strat: <?php echo date('d-m-Y',strtotime($inst_start)); ?></li>
                          <li>End: <?php  echo date('d-m-Y',strtotime($inst_end)); ?></li>
                          <li><b>As per Actual</b></li>
                          <li>Strat: <?php echo $actual_audit_startdate; ?></li>
                          <li>End: <?php  echo $actual_audit_endate; ?></li>
                        </ul>
                      </td>
                      <td><?php echo $team_name; ?></td>
                      <td><?php echo $role ;?></td>
                      <td>
                        <?php
                        // print_r($res_row);
                          if($res_row['institute_audit_status']=="Pending"){
                            echo "Pending";echo "</br>";
                            if($res_row['leader_id']==$_SESSION['userid'])
                              {
                               echo "<a  class='text-primary' href='#'  data-toggle='modal' data-target='#changeStatus_".$res_row['id']."'>Change Status</a>";
                              }
                          }else{
                            echo $res_row['institute_audit_status'];echo "</br>";
                            if($res_row['leader_id']==$_SESSION['userid'])
                              {
                               echo "<a  class='text-primary' href='#'  data-toggle='modal' data-target='#changeStatus_".$res_row['id']."'>Change Status</a>";
                              }
                          }
                      ?>

                        <!-- The Modal -->
                          <div class="modal fade" id="changeStatus_<?php echo $res_row['id']; ?>">
                            <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">
                              
                                <!-- Modal Header -->
                                <div class="modal-header">
                                  <h4 class="modal-title">Change Institution Audit Status</h3>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                
                                <!-- Modal body -->
                                <div class="modal-body">
                                  <table border="0">
                                    <tr>
                                      <td colspan="5"><div class="error_div" style="display:none;">Please enter the date of commencement!!</div></td>
                                    </tr>
                                    <tr>
                                      <th>Current Status</th>
                                       <th>:</th>
                                       <td align="left" colspan="5"><span class="badge badge-warning text-warning"><?php echo $res_row['institute_audit_status']; ?></span></td>
                                    </tr>

                                    <?php
                                          if($res_row['actual_audit_startdate']!=NULL){
                                            ?>
                                            <tr>
                                              <th colspan="2">Date of Commencement</th>
                                              <td>:</td>
                                              <td>
                                                <?php echo $res_row['actual_audit_startdate']; ?>
                                                <input type="hidden"  value="<?php echo $res_row['actual_audit_startdate']; ?>" id="actual_auditstart_<?php echo $res_row['id']; ?>">
                                              </td>
                                            </tr>

                                            <?php
                                          }else{?>
                                          
                                    <tr>
                                      <th>Change Status to </th>
                                       <th>:</th>
                                       <td><input type="radio" value="inprogress" name="stchange_prog_<?php echo $res_row['id']; ?>" id="stchange_prog_<?php echo $res_row['id']; ?>"/></td>
                                       <td><b>In Progress</b> &nbsp;</td>
                                       <td>Date of Commencement</td>
                                       <th>:</th>
                                       <td>
                                         <input type="date" id="comm_date<?php echo $res_row['id']; ?>" name="comm_date" />
                                      </td>
                                    </tr>
                                    <?php 
                                      }
                                      ?>
                                     <tr>
                                        <th>Change status to:</th>
                                         <th>&nbsp;</th>
                                         <td><input type="radio"  value="completion" name="stchange_prog_<?php echo $res_row['id']; ?>"></td>
                                         <td><b>Completed</b> &nbsp;</td>
                                         <td>Date of Completion:</td>
                                         <th>:</th>
                                         <td><input type="date" name="comp_date" id="comp_date<?php echo $res_row['id']; ?>" /></td>
                                      </tr>
                                  </table>
                                </div>
                                
                                <!-- Modal footer -->
                                <div class="modal-footer">
                                  <button type="button" class="btn btncca" onclick="change_status('<?php echo $res_row['id']; ?>')">Change Status</button>
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                      </td>
                      <td>
                        <?php 
                           if($res_row['actual_audit_startdate']!=NULL){
                        ?>
                        <!-- <a href="javascript: ccadatapost('manage_diary', {start_date: '<?php echo $res_row['audit_start_date']?>',mngplan: '<?php echo $res_row['id'] ?>'}, 'post');" class="btn btn-warning">Diary</a> -->
                        <?php
                          }
                        ?>
                        <a href="javascript: ccadatapost('manage_auditreport',{mngplan_id: '<?php echo $res_row['id'] ?>',})" class="btn btn-link">Manage Audit Report</a>
                      </td>
                    </tr>
                    <?php 
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

function change_status(inst_id){
var radioval=$("input[type='radio'][name='stchange_prog_"+inst_id+"']:checked").val();
// alert(radioval);
if(radioval=="inprogress"){
  var comdate=$("#comm_date"+inst_id).val();
  
  if(comdate==""){
    $(".error_div").show();
  }else{
    $(".error_div").hide();
    $.post("my_plan.php",{radioval: radioval,comdate: comdate,id: inst_id},function(res){
      // alert(res);
      location.reload();
    });
  }
}
if(radioval=="completion"){
 var compdate=$("#comp_date"+inst_id).val();
 var comdate=$("#actual_auditstart_"+inst_id).val();
  if(compdate==""){
    $(".error_div").html("Please enter the date of Completion!!");
    $(".error_div").show();
  }else{
      var comdatec=new Date(comdate);
      var compdatec=new Date(compdate);
      console.log(comdatec);
      console.log(compdatec);
      if(compdatec>comdatec){
        console.log("completion date insert");
      }else{
         $(".error_div").html("Date of Completion should be greater than Date of Commencement!!");
         $(".error_div").show();
        //alert("Date of Completion should be greater than Date of Commencement!!");
      }
  }
}
}
</script>



