<?php 
session_start();
include("../common_functions.php");
include_once("../config.php");

if(isset($_POST) && !empty($_POST)){
	$order_no=$_POST['order_no'];
	$transfer_from=$_POST['transfer_from'];
	$transfer_to=$_POST['transfer_to'];
	$transfer_status=$_POST['transfer_status'];
	$designation=$_POST['designation'];
  $designation_old=$_POST['designation_old'];
	$user=$_POST['user'];
	$created_date=date("Y-m-d");

$result_dup=mysqli_query($mysqli,"SELECT * from `cca_transfer_details` where  order_no='".$order_no."'");
if(mysqli_num_rows($result_dup)>0){
echo $error="Duplicate Record exists!";exit();
}


	if($_FILES['order_info']['name'] && $_FILES['order_info']['error']==0)
	{
		   $filename = $_FILES['order_info']["tmp_name"];

		   $extension     = end(explode(".",$_FILES['order_info']["name"]));
		   $randString    = (string)date("Y-m-d-H-i-s");
		   $evidence_file = $_POST['user']."_transfer_".$randString.".".$extension;
		   if (!file_exists(BASE_URL."monitor/transferDoc/" .$evidence_file))
		   {
				if($filename!="")
				{
					//echo $evidence_file;
					if(!move_uploaded_file($filename, "transferDoc/" .$evidence_file)){

						echo $error="Not uploaded because of error #".$_FILES["order_info"]["error"];exit();
					}else{
						// echo "INSERT INTO `transfer_details`(`user_id`,`order_no`, `order_file`,`from_dept`, `to_dept`, `transfer_status`, `designation`,`promot_designation`, `created_date`) VALUES ('".$user."','".$order_no."','".$evidence_file."','".$transfer_from."','".$transfer_to."','".$transfer_status."','".$designation_old."','".$designation."','".$created_date."')";exit();
						
					 $query_ins=mysqli_query($mysqli,"INSERT INTO `cca_transfer_details`(`user_id`,`order_no`, `order_file`,`from_dept`, `to_dept`, `transfer_status`, `designation`,`promot_designation`, `created_date`) VALUES ('".$user."','".$order_no."','".$evidence_file."','".$transfer_from."','".$transfer_to."','".$transfer_status."','".$designation_old."','".$designation."','".$created_date."')");
					 if($query_ins){
					 	echo 'success';exit;
					 }
					}
				}
		   }
	}

exit();
  }
?>


<?php

$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
 
 if($userid == -1)
  {
    header('location:../index.php');
    exit;
  }
include "header.php";
?>
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
            <h1>Transfer Employee</h1>
            <hr>
                <div class="row" style="padding-bottom:50px;">
                    <table class="table table-striped table-bordered" id="tableid">
                    <thead>
                      <tr>
                        <th>Sl.No</th>
                        <th>Name Of the Employee</th>
                        <th>Designation</th>
                        <th>Audit Department</th>
                        <th>Phone Number</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $result = mysqli_query($mysqli, "SELECT u.ID,u.Desig_ID,u.Dept_ID, u.Name,dg.F_descr,dt.S_descr,u.mobile FROM `cca_users` u,`cca_designations` dg, `cca_departments` dt where u.Desig_ID=dg.id and u.Dept_ID=dt.ID"); 
                              if(mysqli_num_rows($result)>0){

                      //while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
                      $count = 0;
                      //while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
                      while($res = mysqli_fetch_array($result)) {
                      $count++ ;
                      ?>
                      <tr class='dang'>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $res['Name']; ?></td>
                        <td><?php echo $res['F_descr']; ?></td>
                        <td><?php echo $res['S_descr']; ?></td>
                        <td><?php echo $res['mobile']; ?></td>
                        <td>
                          <?php
                            $result_transferpending = mysqli_query($mysqli, "SELECT * FROM `cca_transfer_details`  where user_id='".$res['ID']."' and (releiving_date is NULL or joining_date is NULL) order by id desc"); 
                            if(mysqli_num_rows($result_transferpending)>0){
                              $res_transfer = mysqli_fetch_array($result_transferpending);
                              if($res_transfer['releiving_date']==NULL){?>
                                <button class="rel_btn btn btn-danger" data-toggle="modal" data-target="#relieve_modal<?php echo $res_transfer['id'] ?>">Relieve Pending</button>
                             <?php }
                              else{?>
                             <button  class="rel_btn btn btn-warning"  data-toggle="modal" data-target="#relieve_modal<?php echo $res_transfer['id'] ?>">Joining Pending</button>
                             <?php  }?>
                             <div id="relieve_modal<?php echo $res_transfer['id'] ?>" class="modal fade">
                                 <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                      <h4 class="modal-title">Transfer Details</h4>
                                    </div>
                                    <table  class="table table-bordered" width="300px">
                                      <tr>
                                        <th>Order no:</th>
                                        <td><?php echo $res_transfer['order_no'];?></td>
                                      </tr>
                                      <tr>
                                        <th>Order Date: </th>
                                        <td><?php echo $res_transfer['created_date'];?></td>
                                      </tr>
                                      <tr>
                                        <th>Transfer order:</th>
                                        <td><a href="transferDoc/<?php echo $res_transfer['order_file'];?>" target="_blank"><img src="../images/pdf.png"></a></td>
                                      </tr>
                                      <tr>
                                        <th>Relieving Date:</th>
                                        <td><?php echo $res_transfer['releiving_date'];?></td>
                                      </tr>
                                      <tr>
                                        <th>Joining Date:</th>
                                        <td><?php echo $res_transfer['joining_date'];?></td>
                                      </tr>
                                    </table>
                                   
                                  </div>
                                </div>
                              </div>
                              
                              
                            <?php }else{ ?>

                                <a href="#TransferModal"  class="TransferModal_btn btn btn-success"><i class="material-icons">&#xE147;</i> <span>Transfer</span></a>
                                


                            <?php }
                          ?>
                          
                          <input id="userid" type="hidden" value="<?php echo $res['ID']; ?>" />
                          <input id="transfer_from_val" type="hidden" value="<?php echo $res['Dept_ID']; ?>" />
                          <input id="designation_prev" type="hidden" value="<?php echo $res['Desig_ID']; ?>" />
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
      <form id="transfer_form" action="" method="post" name="addform">
        <div class="modal-header">            
          <h4 class="modal-title">Transfer Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
        <div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>  

        <div class="form-group">
            <label>Transfer Order No.</label>
            <input type="text" class="form-control" name="order_no" id="orderno"  required >
          </div> 

          
          <div class="form-group">
            <label>Transfer To</label>
            <select  class="form-control" name="transfer_to">
            <?php 
                  $result = mysqli_query($mysqli, "SELECT * FROM cca_departments ORDER BY id "); 
                  if(mysqli_num_rows($result)>0){
                    while($resdept = mysqli_fetch_array($result)) {
                    ?>
                    <option value="<?php echo $resdept['ID'];?>"> <?php echo $resdept['F_descr'];?> (<?php echo $resdept['S_descr'];?>)</option>
                  <?php 
                  }
                }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>Transfer Status</label>
            <!-- <input type="text" class="form-control" name="transfer_status" id="transfer_status"  required > -->
            <select class="form-control" name="transfer_status" id="transfer_status">
              <option value="GeneralTransfer">General Transfer</option>
              <option value="Deployment">Deployment</option>
              <option value="Deputation">Deputation</option>
            </select>
          </div>  
           <div class="form-group">
            <label>Designation (In case of promotion)</label>

            <select  class="form-control" name="designation">
              <option value="0">Not Applicable</option>
            <?php 
                  $result = mysqli_query($mysqli, "SELECT * FROM cca_designations ORDER BY id "); 
                  if(mysqli_num_rows($result)>0){
                    while($resdept = mysqli_fetch_array($result)) {
                    ?>
                    <option value="<?php echo $resdept['ID'];?>"> <?php echo $resdept['F_descr'];?> (<?php echo $resdept['S_descr'];?>)</option>
                  <?php 
                  }
                }
              ?>
            </select>

          </div>  
          <div class="form-group">
            <label>Order Info</label>
            <input type="file" class="form-control" name="order_info" id="attached_filesid"  required >
          </div>     
        </div>
        <div class="modal-footer">
        	<input class="user_id" name="user" type="hidden" />
          <input class="designationold" name="designation_old" type="hidden" />
           <input type="hidden" name="transfer_from" class="transfer_fromc" />
          <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
          <input type="submit" class="btn btn-info" value="Save" />
        </div>
      </form>
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

} );

$(".TransferModal_btn").click(function(){
    var user_id=$(this).siblings('#userid').val();
    var desg_id=$(this).siblings('#designation_prev').val();
    var transfer_from=$(this).siblings('#transfer_from_val').val();

    //alert(user_id);
    $(".user_id").val(user_id);
    $(".transfer_fromc").val(transfer_from);
    $(".designationold").val(desg_id);

    $("#TransferModal").modal();
  });






    $(document).on("submit", 'form#transfer_form', function(event) {
      //alert(buttonpressed);
          var form_data = new FormData($(this)[0]);  
     
          
        
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
              }else{
                alert(php_script_response);
              }
            
            setTimeout(function(){
            $('.msgdivsuccess').hide();
          }, 5000);
          }
       });
  

     return false;
});

</script>


