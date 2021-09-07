<?php 
session_start();
include("../common_functions.php");
include_once("../config.php");

 $userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
$result_user = mysqli_query($mysqli, "SELECT u.Dept_ID, d.S_descr from `cca_users` u ,`cca_departments` d where u.ID='".$userid."' and u.Dept_ID=d.ID"); 
$resuser = mysqli_fetch_array($result_user);
$dept_fa= $resuser ['S_descr'];
 $deptid_fa= $resuser ['Dept_ID'];
 if($userid == -1)
  {
    header('location:../index.php');
    exit;
  }

if(isset($_POST['action']) && ($_POST['action']=='rel')){
	$transfer_id=$_POST['transfer_id'];
	
 $rel_date = date("Y-m-d",strtotime($_POST['rel_date']));
$update_tranfer= mysqli_query($mysqli, "UPDATE `cca_transfer_details` SET `releiving_date`='".$rel_date."' WHERE id='".$transfer_id."'"); 
if($update_tranfer){
  echo "success";exit();
}

}

  if(isset($_POST['action']) && ($_POST['action']=='join')){
  $transfer_id=$_POST['transfer_id'];
  $user_id=$_POST['user_id'];
  $designation=$_POST['designation'];
  $prev_desg=$_POST['prev_desg'];
  $dept_promot=$_POST['dept_promot'];
  
 $join_date = date("Y-m-d",strtotime($_POST['join_date']));
$update_tranfer= mysqli_query($mysqli, "UPDATE `cca_transfer_details` SET `joining_date`='".$join_date."' WHERE id='".$transfer_id."'"); 
if($update_tranfer){
  if($designation==0){
    $designation=$prev_desg;
  }
  mysqli_query($mysqli, "UPDATE `cca_users` SET `Dept_ID`='".$dept_promot."',`Desig_ID`='".$designation."'  WHERE id='".$user_id."'"); 
  echo "success";exit();
}
}

?>
<?php include "header.php"; ?>
<style>
/*div.main{
height:100%;
}*/
</style>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>Transfer Process</h1>
            <hr>
                <div class="row" style="padding-bottom:50px;">
                    <table class="table table-striped table-bordered" id="tableid">
                    <thead style="background-color: #185a9d;color: white;">
                      <tr>
                        <th>Sl.No</th>
                        <th>Name Of the Employee</th>
                        <th>Designation</th>
                        <th>Transfer Type</th>
                        <th>From Department</th>
                        <th>To Department</th>
                        <th>New Designation</th>
                        <th>Order Date</th>
                        <th>Date of Releive</th>
                        <th>Action</th>
                        <th>Date of Join</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                    
                    // echo "SELECT u.ID,u.name,u.Desig_ID,td.order_no,td.id,td.transfer_status,td.designation,td.promot_designation,td.to_dept,td.releiving_date,td.joining_date,td.created_date,dr.S_descr as from_dept,(select `S_descr` from `cca_designations` where ID=td.designation) as designation_prev,(select `S_descr` from `cca_designations` where ID=td.promot_designation) as designation_post, (select S_descr from `cca_departments` where ID=td.to_dept) as to_deptm FROM `cca_transfer_details` td,`cca_users` u,`cca_departments` dr  where u.id=td.user_id and (td.from_dept='".$deptid_fa."' or td.to_dept='".$deptid_fa."' ) and dr.ID=from_dept";

                    $result = mysqli_query($mysqli, "SELECT u.ID,u.name,u.Desig_ID,td.order_no,td.id,td.transfer_status,td.designation,td.promot_designation,td.to_dept,td.releiving_date,td.joining_date,td.created_date,dr.S_descr as from_dept,(select `S_descr` from `cca_designations` where ID=td.designation) as designation_prev,(select `S_descr` from `cca_designations` where ID=td.promot_designation) as designation_post, (select S_descr from `cca_departments` where ID=td.to_dept) as to_deptm FROM `cca_transfer_details` td,`cca_users` u,`cca_departments` dr  where u.id=td.user_id and (td.from_dept='".$deptid_fa."' or td.to_dept='".$deptid_fa."' ) and dr.ID=from_dept"); 

                              if(mysqli_num_rows($result)>0){

                      //while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
                      $count = 0;
                      //while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
                      while($res = mysqli_fetch_array($result)) {
                      $count++ ;
                      ?>
                      <tr class='dang'>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $res['name']; ?></td>
                        <td><?php echo $res['designation_prev']; ?></td>
                        <td><?php echo $res['transfer_status']; ?></td>
                        <td><?php echo $res['from_dept']; ?></td>
                        <td><?php echo $res['to_deptm']; ?></td>
                        <td><?php echo $res['designation_post']; ?></td>
                        <td><?php echo $res['created_date']; ?></td>
                        <td>
                          <?php 
                          if(($res['from_dept']==$dept_fa) && ($res['releiving_date']==NULL)){?>
                            <input type="text" name="date" class="form-control trans_date" id="rel_date<?php echo $res['id'];?>"/>
                          <?php }else{
                            echo $res['releiving_date'];
                          }
                          ?>
                          </td>
                        <td>
                        <?php
                          if(($res['from_dept']==$dept_fa) && ($res['releiving_date']==NULL)){?>
                            <button class="btn btn-danger" onclick="relv_dept('<?php echo $res['id'];?>')">Relieve</button>
                          <?php }
                          if(($res['to_deptm']==$dept_fa) && ($res['joining_date']==NULL) && ($res['releiving_date']!=NULL)){?>
                            <button class="btn btn-success" onclick="join_dept('<?php echo $res['id'];?>','<?php echo $res['ID']; ?>','<?php echo $res['Desig_ID']; ?>','<?php echo $res['promot_designation']; ?>','<?php echo $res['to_dept']; ?>')"> Join </button>
                          <?php }
                        ?>
                        </td>
                        <td>
                          <?php 
                          if(($res['to_deptm']==$dept_fa) && ($res['joining_date']==NULL) && ($res['releiving_date']!=NULL)){?>
                            <input type="text" name="date" class="form-control trans_date" id="join_date<?php echo $res['id'];?>"/>
                          <?php }else{
                            echo $res['joining_date'];
                          }
                          ?>
                            
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
 <div id="TransferModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      asdfsfsdgfdsgfdg
    </div>
  </div>
</div>
        <!-- /.container-fluid -->
    </div>
    <div class="clear:both;"></div>
</div>
<?php include "footer.php"; ?>

<link rel="stylesheet" href="../css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="../js/bootstrap-datepicker.min.js"></script>

<script>
  $(document).ready(function() {
   $('#tableid').DataTable();

   $(".TransferModal_btn").click(function(){
    var user_id=$(this).siblings('input').val();
    alert(user_id);
    $(".user_id").val(user_id);
    $("#TransferModal").modal();
  });



$('.trans_date').each(function(){
    $(this).datepicker("setDate",new Date());
});
 
} );



    $(document).on("submit", 'form#transfer_form', function(event) {
      //alert(buttonpressed);
      var form_data = new FormData($(this)[0]);  
     
    console.log(form_data);
        
          var file_data = $('#attached_filesid').prop('files')[0];
         var filecheck=$("#attached_filesid").val();
         if(filecheck!="")
         {
          
          //form_data.append('file', file_data);
         }
        
      

       // form_data.append('reply', buttonpressed);
       // var para_cnt=$("#ir_cnt_"+document_id).html();
       // para_cnt=parseInt(para_cnt);
       // var new_para_cnt=para_cnt-1;
                                 
      $.ajax({
          url: '<?php echo BASE_URL?>monitor/transfer_employee.php', // point to server-side PHP script 
          dataType: 'text',  // what to expect back from the PHP script, if anything
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,                         
          type: 'post',
          success: function(php_script_response){
               console.log(php_script_response);
              if(php_script_response=="success")
              {
                location.href="transfer_employee";
              }
            
            setTimeout(function(){
            $('.msgdivsuccess').hide();
          }, 5000);
          }
       });
  

     return false;
});


function relv_dept(transfer_id){
var rel_date=$("#rel_date"+transfer_id).val();
$.post("transfer_process.php",{transfer_id:transfer_id,rel_date:rel_date,action:'rel'},function(res){
console.log(res);
if(res=="success"){
location.reload();
}
});
}

function join_dept(transfer_id,user_id,prev_desg,designation,dept_promot){
  var join_date=$("#join_date"+transfer_id).val();
  $.post("transfer_process.php",{transfer_id:transfer_id,join_date:join_date,action:'join',user_id:user_id,prev_desg: prev_desg,designation: designation,dept_promot: dept_promot},function(res){
  console.log(res);
  if(res=="success"){
  location.reload();
  }
  });
}
</script>


