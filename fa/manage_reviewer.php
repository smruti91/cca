<?php 
session_start();
include("../common_functions.php");
include_once("../config.php");

$dept = $_SESSION['dept_id'];
$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
 
 if($userid == -1)
  {
    header('location:../index.php');
    exit;
  }

  
if(isset($_POST['action']) && ($_POST['action']=='add')){
    $plan = $_POST['plan'];
    $revwer = $_POST['revw'];
    $program_auditor = $_POST['program_auditor'];
     $active_date = Date('Y-m-d',strtotime($_POST['date_activation']));

  
  $result = mysqli_query($mysqli, "SELECT * FROM cca_plan WHERE id=".$plan); 
if(mysqli_num_rows($result)>0){
while($res = mysqli_fetch_array($result)) {

$start = $res['plan_start_date'];
$end = $res['plan_close_date'];

$dup_check=mysqli_query($mysqli,"select * from `cca_mngoffice` where plan_id='".$plan."' and status=1");

if(mysqli_num_rows($dup_check)>0){
echo "exists";exit;
}
else{
	
$sql =" INSERT INTO `cca_mngoffice` ( `plan_id`, `plan_start_date`, `plan_end_date`, `emp_id`,`program_auditor`,`dept_id`,`active_date`,`status`) VALUES ( '$plan', '$start', '$end',  '$revwer','$program_auditor','$dept','$active_date',1)";
$result_insert = mysqli_query($mysqli, $sql);  
echo "success";
exit();
}

  }
}



//$sql =" INSERT INTO `cca_plan` ( `plan_name`, `plan_start_date`, `plan_end_date`,  `plan_close_date`,`dept_id`,`status`) VALUES ( '$init', '$initiate', '$commence',  '$complete','$dept','Planning')";
  //$result_insert = mysqli_query($mysqli, $sql);


}

if(isset($_POST['action']) && $_POST['action']=="update_status"){
$inactive_date=Date('Y-m-d',strtotime($_POST['inac_date']));
$id=$_POST['id'];
//echo "update `cca_mngoffice` SET status=1,inactive_date='".$inactive_date."' where id='".$id."'";

mysqli_query($mysqli,"update `cca_mngoffice` SET status=0,inactive_date='".$inactive_date."' where id='".$id."'" );
exit;
}
?>

<?php include "header.php"; ?>

<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h3>Manage Program Section Auditor/Reviewer</h3>

            <hr>
            
              <form class="form-horizontal" action="" method="post">
                <div id="div_errordup" class="form-group error_div" style="display:none;text-align:left;margin-left:5%;"></div>
              <div class="form-group">
                <label class="control-label col-sm-2">Select Plan:</label>
                <div class="col-sm-2">
                 <select class="form-control" data-placeholder="Choose a plan" tabindex="1" name="plan" id="plan"  >
                  <?php
                  echo "SELECT * FROM `cca_plan` where dept_id=".$dept;
                  $sel_plan = mysqli_query($mysqli,"SELECT * FROM `cca_plan` where dept_id=".$dept);
                   ?> 
              
      <option value=''>Select Plan</option>
      
      <?php while($res_plan = mysqli_fetch_array($sel_plan)) { ?>
      <option value="<?php echo $res_plan['id']?>"  ><?php echo $res_plan['plan_name']?></option>
      <?php } ?>
    </select>
                </div>
              </div>
              <div id="div_errorplan" class="form-group error_div" style="display:none;text-align:left;">Plan can not be blank!</div>
              <div class="form-group">
                <label class="control-label col-sm-2">Select Reviewer:</label>
                <div class="col-sm-2">
                  <select class="form-control" data-placeholder="Choose a Reviewer" tabindex="1" name="revw" id="revw"  >
                    <?php
                    
                    $sel_revw = mysqli_query($mysqli,"SELECT ID,Name FROM `cca_users` where (Role_id ='5' or Role_id ='4') AND dept_id=".$dept);
                   


                     ?> 
                            
                    <option value=''>Select Program Reviewer</option>
                    
                    <?php while($res_revw = mysqli_fetch_array($sel_revw)) { ?>
                    <option value="<?php echo $res_revw['ID']?>"  ><?php echo $res_revw['Name']?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

               <div class="form-group">
                <label class="control-label col-sm-2">Select Program section Auditor/AAo:</label>
                <div class="col-sm-2">
                  <select class="form-control" data-placeholder="Choose a program section Auditor" tabindex="1" name="program_auditor" id="program_auditor"  >
                    <?php
                    
                    $sel_revw = mysqli_query($mysqli,"SELECT ID,Name FROM `cca_users` where Role_id ='8' AND dept_id=".$dept);
                   


                     ?> 
                            
                    <option value=''>Select Program Section Auditor</option>
                    
                    <?php while($res_revw = mysqli_fetch_array($sel_revw)) { ?>
                    <option value="<?php echo $res_revw['ID']?>"  ><?php echo $res_revw['Name']?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div id="div_errorrevw" class="form-group error_div" style="display:none;text-align:left;">Reviewer can not be blank!</div>

              <div class="form-group">
                <label class="control-label col-sm-2">Date of Activation:</label>
                <div class="col-sm-2">
                  <input class="form-control" value="<?php echo date('Y-m-d'); ?>" type="date" name="date_activation" id="date_activation" />
                </div>
              </div>


              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                  <button type="button" class="btn btn-default" onclick="save_reviewer()">Submit</button>
                </div>
              </div>
            </form>

                <div class="row" style="padding-bottom:50px;">
                    <table class="table table-striped table-bordered" id="tableid">
                    <thead>
                      <tr>
                        <th>Sl.No</th>
                        <th>Plan Name</th>
                        <th>Program section Auditor</th>
                         <th>Reviewer</th>
                          <th>Time Period</th>
                          <th>Status</th>
                          <th>Date Of Activation</th>
                           <th>Date Of Inactivation</th>
                         <!--  <th>Add Section</th> -->
                          <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                      $result = mysqli_query($mysqli, "SELECT * FROM cca_mngoffice ORDER BY status desc"); 
                      if(mysqli_num_rows($result)>0){

                      //while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
                      $count = 0;
                      //while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
                      while($res = mysqli_fetch_array($result)) {
                      $count++ ;
                      
                      if($res['program_auditor']!=NULL){
                         $program_section = mysqli_query($mysqli,"SELECT ID,Name FROM `cca_users` where ID=".$res['program_auditor']);
                       $res_program_sect = mysqli_fetch_array($program_section);
                       if(mysqli_num_rows($program_section)>0){ $program_auditor= $res_program_sect['Name'];}else{
                        $program_auditor='N/A';
                       }
                      }else{
                        $program_auditor='N/A';
                       }

                      if($res['emp_id']!=NULL){
                       $program_review = mysqli_query($mysqli,"SELECT ID,Name FROM `cca_users` where ID=".$res['emp_id']);
                       $res_program_rvw = mysqli_fetch_array($program_review);
                       if(mysqli_num_rows($program_review)>0){ $program_reviewer= $res_program_rvw['Name'];}else{
                        $program_reviewer='N/A';
                       }
                      }else{
                        $program_reviewer='N/A';
                       }
                      
                       ?>
                      <tr class='dang'>
                        <td><?php echo $count; ?></td>
                        <td><?php  echo find_planname($res['plan_id'],$mysqli); ?></td>
                        <td><?php  echo $program_auditor; ?></td>
                        <td><?php  echo $program_reviewer; ?></td>
                        <td><?php echo "From ". date("d-m-Y",strtotime($res['plan_start_date']))." To ".date("d-m-Y",strtotime($res['plan_end_date']))?></td>
                        <td><?php 
                        if($res['status']==1){
                          echo "active";

                        }else{
                          echo "Inactive";
                        }
                        ?></td>
                        <td><?php echo Date('d-m-Y',strtotime($res['active_date'])); ?></td>
                         <td><?php if($res['inactive_date']!=NULL){ echo Date('d-m-Y',strtotime($res['inactive_date']));} ?></td>
                        <!-- <td></td> -->
                        <td>
                          <?php
                            if($res['status']==1){?>
                              <input type="radio" name="status"  id="chkYes" onclick="ShowHideDiv()"/> Inactive
                           <?php  }
                          ?>
                          <div id="dvtext" style="display: none">
                            <input type="date"  name="inactive_date" id="inac_date_<?php echo $res['id']; ?>" value="<?php echo date('Y-m-d'); ?>" /></br></br>
                            <button  class="btn-primary"name="update status" onclick="update_status('<?php echo $res['id']; ?>','<?php echo $res['plan_start_date'] ?>')">Update Status</button>
                          </div>
                        </td>
                       
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
  function save_reviewer(){
     var revw = $("#revw").val();
     var plan = $("#plan").val();
     var program_auditor=$('#program_auditor').val();
     var date_activation=$("#date_activation").val();
    
    if(plan=="" & revw==""){
      $("#div_errorrevw").show();
      $("#div_errorplan").show();
     }
    else if(revw==""){
      $("#div_errorrevw").show();
       $("#div_errorplan").hide();
        
     }else if(plan==""){
        $("#div_errorplan").show();
        $("#div_errorrevw").hide();
     }
    
     else{
     $.post("manage_reviewer.php",{action: "add",revw:revw,plan:plan,program_auditor: program_auditor,date_activation: date_activation},
      function(res){
          //console.log(res);
          if(res=="exists"){
            $("#div_errordup").show();
            $("#div_errordup").html("There exists an active office for the same plan!");
          }else{
          	 $("#div_errordup").hide();
            window.location.reload();
          }
        }
      );
     }
  }

  function ShowHideDiv(){
     var chkYes = document.getElementById("chkYes");
     var dvtext = document.getElementById("dvtext");
    dvtext.style.display = chkYes.checked ? "block" : "none";
  }
  function update_status(id,active_date){
    var con= confirm("Are you sure to inactivate the office!");
    if(con){
      alert(active_date);
      var inac_date=$("#inac_date_"+id).val();
      alert(inac_date);
      // if(active_date.getTime()<inac_date.getTime()){
           $.post("manage_reviewer",{id: id,action: 'update_status',inac_date: inac_date},function(res){
            location.reload();
          });
      // }else{
      //    alert("Date of Inactivation should be greater than date of activation!");
      // }
    }
  }

</script>

