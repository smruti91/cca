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
$new_team = $_POST['team'];
  $plan_id = $_POST['plan_id'] ;
  $team_id = $_POST['team_id'];
  $date = date('Y-m-d');   
  $inser_team = generateSQL("INSERT INTO cca_team_employee_transaction(plan_id,emp_id,drop_team_id,add_team_id,drop_date, prog_auditor_shq_comment,dept_id,prog_auditor_submit_status) values (?,?,?,?,?,?,?,?)",array($plan_id,$id,$team_id,$new_team,$date,$comments,$dept1,'pending'),true,$mysqli); 
    echo "success";
    exit();
  }
 
}

  ?>
  <?php include "header.php"; ?>
<script type="text/javascript">
	
function new_team(action,empid,team_id,plan_id){

var team = $("#team").val();
var comments=$("#approve_comments_"+empid).val();
if(empid!=''){
  
$.post("resufle_member.php",{action:action,id: empid,comments: comments,team_id:team_id,plan_id:plan_id,team:team},function(res){
  
    if(res=="success"){
      
    location.reload();
      //console.log(res);
    }
  });
}
}
function open_comment(id){
  
  
   $("#approve_"+id).css('display','block');
    $("#reject_"+id).css('display','none');
  
 }
</script>
<div id="wrapper">
	
<?php include "leftpanel.php";?>
<div id="page-wrapper">
<div class="container-fluid text-center">   
<div class="row content">
	<div class="col-sm-12 text-center"> 
            <h1>Reshuffle of Team Member</h1>
            <hr>


<div class="row" style="padding-bottom:50px;">
              <table class="table" id="tableid">


              	<thead>
                  <tr>
                    <th style="text-align:center">Sl.No</th>
                    <th style="text-align:center">Member Name</th>
                    <th style="text-align:center">Current Team</th>
                    <th style="text-align:center">Add to New Team</th>
                    <th style="text-align:center">Action</th>
                  </tr>
                </thead>
                <tbody>
 <?php
 $dept = $_SESSION['dept_id'];
$get_member =  generateSQL("SELECT emp_id,team_id,plan_id  FROM `cca_team_emp_role` WHERE `Dept_id` =?",array($dept),false,$mysqli);
$count = 0;
foreach( $get_member as $row )
{
 
$count++;
$get_team =  generateSQL("SELECT team_name,id  FROM `cca_team` WHERE `Dept_id` =? AND id!=?",array($dept,$row['team_id']),false,$mysqli);

$get_team_name = generateSQL("SELECT team_name  FROM `cca_team` WHERE `Dept_id` =? AND id=?",array($dept,$row['team_id']),false,$mysqli);
$get_team_name = reset($get_team_name);

$get_emp_name = generateSQL("SELECT Name  FROM `cca_users` WHERE  ID= ?",array($row['emp_id']),false,$mysqli);
$get_emp_name = reset($get_emp_name);
  ?>
<tr class="dang" >
<td><?php echo $count; ?></td>
<td ><?php echo $get_emp_name['Name']; ?></td>
<td><?php echo $get_team_name['team_name']; ?></td>
<td ><select name="team" id="team" >
<option value="">Select New Team</option>
<?php
            foreach($get_team as $Queryinst)
{?>

<option   value="<?php echo htmlentities($Queryinst['id']);?>">
	<?php echo htmlentities($Queryinst['team_name']);?>
		
	</option>

<?php }?>
 </select></td>
<td><button class="btn btn-warning" onclick="open_comment('<?php echo $row['emp_id'];?>')">Add To Team</button>
 <div class="approve" id="approve_<?php echo $row['emp_id'];?>" style="display:none;padding-top:5px; padding: 15px;background: lightblue;">
                                    Comments on Approval: <textarea cols="30" rows="5" name="approve_comments" id="approve_comments_<?php echo $row['emp_id'];?>"></textarea> <button class="btn-primary" onclick="new_team('approve','<?php echo htmlentities($row['emp_id']);?>','<?php echo htmlentities($row['team_id']);?>','<?php echo htmlentities($row['plan_id']);?>')">Submit</button>
                                     <button type="button" class="btn-close" onclick="$('#approve_<?php echo $row['emp_id'];?>').hide();">Close</button>
                                  </div></td>



</tr>
<?php } ?>
                </tbody>

    </table>
    </div>          	



</div>
</div>
</div>
</div>
    <div class="clear:both;"></div>	
</div>

<?php include "footer.php"; ?>
    <!-- Bootstrap Date-Picker Plugin -->
<link rel="stylesheet" href="../css/bootstrap-datepicker3.css"/>