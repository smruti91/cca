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
if(isset($_POST['id'])){
  $id = $_POST['id'];
}

if(isset($_POST['action'] ) && $_POST['action']=='edit'){
$leader = $_POST['leader'];
$id = $_POST['id'];
$sql ="UPDATE `cca_team_emp_role` SET `leader_id` = ".$leader." WHERE team_id=".$id;


$result_insert = mysqli_query($mysqli, $sql);
echo "success";
exit();
}

if(isset($_POST['action'] ) && $_POST['action']=='del'){
$team_id = $_POST['team_id'];
$member_id = $_POST['member_id'];
$id = $_POST['id'];
$sql ="DELETE FROM `cca_team_emp_role`  WHERE team_id='".$team_id."' and emp_id='".$member_id."'";
$result_insert = mysqli_query($mysqli, $sql);
echo "success";
exit();
}

if(isset($_POST) && !empty($_POST['action'])  ){
$plan = $_POST['plan'];
$dept = $_POST['dept'];
$team = $_POST['team'];
$empid = $_POST['epid'];
// if(count($empid)>0){
  $empid=explode(',', $empid);
  foreach ($empid as $key) {
    // echo "select Role_ID from `user_master` where ID='".$key."'";exit;
    $sql_role=mysqli_query($mysqli,"select Role_ID from `cca_users` where ID='".$key."'");
    $role_res = mysqli_fetch_array($sql_role); 
    $role=$role_res['Role_ID'];

    $sql =" INSERT INTO `cca_team_emp_role` ( `plan_id`, `Dept_id`, `team_id`, `role_id`, `emp_id`) VALUES ( '$plan', '$dept', '$team','$role','$key')";
    $result_insert = mysqli_query($mysqli, $sql);
  }
// }

echo "success";
exit();
}
?>

<?php include "header.php"; ?>
<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/bootstrap-duallistbox.css">
 <script src="<?php echo BASE_URL; ?>js/jquery.bootstrap-duallistbox.js"></script>
<style>
div.main{
height:100%;
}
</style>

<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
           <h2>Create Team</h2>
           <hr />
              <?php 

               $result = mysqli_query($mysqli, "SELECT * FROM cca_team where id=".$id);
               $res = mysqli_fetch_array($result);
              $plan_id='';
               if(mysqli_num_rows($result)>0){
                $plan_id=$res['plan_id'];
                $plan_name = mysqli_query($mysqli, "SELECT plan_name FROM cca_plan where id = ".$res['plan_id']);
               $plan_res = mysqli_fetch_array($plan_name);

                $dept = mysqli_query($mysqli, "SELECT S_descr,F_descr FROM cca_departments where id = ".$_SESSION['dept_id']);
                $dept_res = mysqli_fetch_array($dept);
               }
              ?>

              <form class="form-horizontal" action="" method="post">
                    <table>
                      <tr>
                        <th  align="right">Programme for the year</th>
                        <td>:</td>
                        <td><?php echo $plan_res['plan_name']."-".$dept_res['S_descr']; ?></td>
                      </tr>
                      <tr>
                        <th  align="right">Department</th>
                        <td>:</td>
                        <td><?php echo $dept_res['F_descr']; ?></td>
                      </tr>
                      <tr>
                        <th align="right">Team Name</th>
                        <td>:</td>
                        <td><?php echo $res['team_name']; ?></td>
                      </tr>
                    </table>
            
              <div class="form-group">
                <select  multiple="multiple" size="10" name="duallistbox_demo2" id ="duallistbox_demo2"class="demo2" title="team members"  style="width:400px;">
                <?php
                $sel_dept = mysqli_query($mysqli,"SELECT ID,name FROM `cca_users` u  where u.ID not in (select emp_id from `cca_team_emp_role` where plan_id='".$res['plan_id']."') and (Role_ID ='5' or u.Role_ID = '6') AND u.Dept_id=".$res['Dept_id']);
                 ?> 
                <?php while($res_dept = mysqli_fetch_array($sel_dept)) { ?>
                <option value="<?php echo $res_dept['ID']?>"  > (<?php echo $res_dept['name']?>)</option>
                <?php } ?>
              </select>
            </br>
              <button type="button" class="btncca" onclick="save_data()">Submit</button>
            </div>
          </form>
        <table class="table table-striped" id="tableid">
        <thead>
          <tr>
            <th>Sl.No</th>
            <th>Programme Year</th>
            <th>Audit Department</th>
            <th>Party No</th>
            <th>Member</th>
            <th>Action</th>
          </tr>
        </thead>

            <tbody>
          <?php 
              $Member = mysqli_query($mysqli, "SELECT * FROM cca_team_emp_role where team_id=".$id); 

             
              if(mysqli_num_rows($Member)>0){
             
              $count = 0;
                while($member_result = mysqli_fetch_array($Member)) {
                 $count++ ;

                 $employee = mysqli_query($mysqli,"SELECT * FROM `cca_users` WHERE `ID`= '".$member_result['emp_id']."'");
                 $res = mysqli_fetch_array($employee);

                 $plan_name = mysqli_query($mysqli, "SELECT plan_name FROM cca_plan where id = ".$member_result['plan_id']);
                 $planname='';
                if(mysqli_num_rows($plan_name)>0){
                   $plan_res = mysqli_fetch_array($plan_name); 
                    $planname=$plan_res['plan_name'];
                }

                $dept = mysqli_query($mysqli, "SELECT S_descr,F_descr FROM cca_departments where id = ".$member_result['Dept_id']);
                $dept_res = mysqli_fetch_array($dept);

                $team = mysqli_query($mysqli, "SELECT team_name FROM cca_team where id=".$id);
                $team_res = mysqli_fetch_array($team);
            ?>
              <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $planname."-".$dept_res['S_descr']; ?></td>
                <td><?php echo $dept_res['F_descr']; ?></td>
                <td><?php echo $team_res['team_name']; ?></td>
                <td>
               <?php echo $res['Name'];?>
                </td>
                <td>
                  <?php
                    if($member_result['emp_id']==$member_result['leader_id']){ 
                      ?>
                      <button type="button"  class="btn btn-warning">Lead Auditor</button>
                   <?php 
                    }else{
                      ?>
                      <button type="button"  class="btn btn-success" value="Save" onclick="leader_appnt('<?php echo $res['ID'];?>')">Appoint Leader</button>
                   <?php  }
                  ?>
                   <button type="button"  class="btn btn-danger" value="Save" onclick="remove_member('<?php echo $res['ID'];?>','<?php echo $id; ?>')">Remove Member</button>
             </td>
              </tr>
               <?php  }
               } 
               ?>  
            </tbody>
          </table>
          </div>
      </div>
      </div>
        <!-- /.container-fluid -->
    </div></br></br></br>
    <div class="clear:both;"></div>
</div>
<?php include "footer.php"; ?>
    <!-- Bootstrap Date-Picker Plugin -->
  <style>
    .bootstrap-duallistbox-container{
      width: 70%;
    left: 0px;
    right: 0;
    margin: auto;
    }
    .moveall,.removeall {
      display:none;
    }
    .btn-outline-secondary{
      width:94% !important;
      background-color: #4be8bf;
      font-weight: bold;
    font-size: 22px;
    }
    .bootstrap-duallistbox-container .info{
      font-size:15px;
      color:green;
      font-weight: bold;
    }

  </style>
<link rel="stylesheet" href="../css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="../js/bootstrap-datepicker.min.js"></script>
<script>
 $(document).ready(function() {
     $('#tableid').DataTable();
  } );

  $('.team_name').on('change', function() {
      $('.team_name').not(this).prop('checked', false);  
  });
 var demo2 = $('.demo2').bootstrapDualListbox({
        nonSelectedListLabel: 'Non-selected',
        selectedListLabel: 'Selected',
        preserveSelectionOnMove: 'moved',
        moveOnSelect: false
        
      });


function save_data() {

var empid= $("#duallistbox_demo2").val();
var plan = '<?php echo $plan_id ?>';
var dept = '<?php echo $_SESSION['dept_id']?>';
var team = '<?php echo $id?>';

if(empid!=''){
empid=empid.join();
$.post("manage_team.php",{action:"add",plan:plan,dept:dept,team:team,epid:empid},
      function(res){
       // alert(res);
        window.location.reload();
        }
      );
    }
  }

function leader_appnt(id){
  if(confirm("Are you sure to Appoint the member as Leader!!")){
    var team = '<?php echo $id ?>';
    $.post("manage_team.php",{action:"edit",leader:id,id:team},
      function(res){
        // alert(res);
        window.location.reload();
      }
      );
  }
}

function remove_member(member_id,team_id){
if(confirm("Are you sure to remove this member!!")){
    $.post("manage_team.php",{action:"del",team_id:team_id,member_id:member_id},
      function(res){
        // alert(res);
        window.location.reload();
      }
      );
  }
}
</script>



