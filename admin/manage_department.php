<?php

include_once("../config.php");
session_start();
// print_r($_SESSION);exit;
$userid = (($_SESSION['userid'] && $_SESSION['userid']!='')?$_SESSION['userid']:-1);


if($userid == -1)
  {
    header('location: index.php');
    exit;
  }


if(isset($_POST) && !empty($_POST['s_descedit']) ){
$s_descedit=$_POST['s_descedit'];
$f_desc=mysqli_real_escape_string($mysqli, $_POST['f_desc']);
$user_id=$_POST['user_id'];
$id=$_POST['id'];
$sql_updt = 'UPDATE cca_departments SET S_descr="'.$s_descedit.'",F_descr="'.$f_desc.'",User_ID="'.$user_id.'" WHERE id='.$id;
$result_updt = mysqli_query($mysqli, $sql_updt);
echo "success";
exit();
}

if(isset($_POST) && !empty($_POST['id'])){
$id=$_POST['id'];
$result_dlt = mysqli_query($mysqli, "DELETE FROM cca_departments WHERE id=$id");
echo "success";
exit();
}

if(isset($_POST) && !empty($_POST['action'])){
$s_desc=$_POST['s_desc'];
$f_desc=mysqli_real_escape_string($mysqli, $_POST['f_desc']);
$user_id=$_POST['user_id'];
$result_chk_dupl = mysqli_query($mysqli, "select * from `cca_departments` where S_descr='".$s_desc."'");
if(mysqli_num_rows($result_chk_dupl)>0){
echo "duplicate";exit();
}


$sql="INSERT INTO `cca_departments`(`S_descr`, `F_descr`,`User_ID`) VALUES ('$s_desc','$f_desc','$user_id')";
$result_insert = mysqli_query($mysqli, $sql);
echo "success";
exit();
}


if(isset($_POST['ids']) && $_POST['ids']!=""){
$id=$_POST['ids'];
$result_dlt = mysqli_query($mysqli, "DELETE FROM cca_departments WHERE id IN (".$id.")");
echo "success";
exit();
}

?>
<?php include 'header.php'; ?>
<?php include 'leftpanel.php'; ?>

    <div class="main container">
        <div class="table-wrapper" id="table-wrapperid">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
						<h2>Manage <b>Departments</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Department</span></a>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover" id="tableid">

            	  <div class="alert alert-success alert-dismissible" id="successmsgid" style="display:none;">
				    <a href="#" class="close"  aria-label="close">&times;</a>
				    <strong>Success!</strong> This alert box could indicate a successful or positive action.
				  </div>

                <thead>
                    <tr>
						<th>
							<span class="custom-checkbox">
								<!-- <input type="checkbox" id="selectAll">
								<label for="selectAll"></label> -->
							</span>
						</th>
                        <th>Sl_No</th>
						<th>S_descr</th>
						<th>F_descr</th>
						<th>user_id</th>
						<th>Action</th>
                    </tr>
                </thead>
                <tbody>
                	<?php 
                	$result = mysqli_query($mysqli, "SELECT * FROM cca_departments ORDER BY id "); 
                	if(mysqli_num_rows($result)>0){

					//while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
					$count = 0;
					//while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
					while($res = mysqli_fetch_array($result)) {
					$count++ ;
					?>
                    <tr id="trow_<?php echo $res['ID'];?>">
						<td>
							<span class="custom-checkbox">
								<input type="checkbox" class="checkbox1" name="options[]" value="<?php echo $res['ID']; ?>">
								<label for="checkbox1"></label>
							</span>
						</td>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $res['S_descr']; ?></td>
						<td><?php echo $res['F_descr']; ?></td>
                        <td><?php echo $res['User_ID']; ?></td>
                        <td>
                            <a href="#editEmployeeModal_<?php echo $res['ID']; ?>" class="edit" data-toggle="modal" id="edit_<?php echo $res['ID']; ?>" style="display:none"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a href="#deleteEmployeeModal_<?php echo $res['ID']; ?>" class="delete" data-toggle="modal" id="delete_<?php echo $res['ID']; ?>" style="display:none"><i class="material-icons" data-toggle="tooltip" title="Delete" >&#xE872;</i></a>
                            <!-- Delete Modal HTML -->
								<div id="deleteEmployeeModal_<?php echo $res['ID']; ?>" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<form>
												<div class="modal-header">						
													<h4 class="modal-title">Delete Department</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">					
													<p>Are you sure you want to delete these Records?</p>
													<p class="text-warning"><small>This action cannot be undone.</small></p>
												</div>
												<div class="modal-footer">
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
													<input type="hidden" value="<?php echo $res['ID']; ?>" class="recordid" />
													<input type="button" class="btn btn-danger btn-dlt" value="Delete" id="<?php echo $res['ID']; ?>" onclick="delete_record(this.id)" />
												</div>
											</form>
										</div>
									</div>
								</div>

								<!-- Edit Modal HTML -->
								<div id="editEmployeeModal_<?php echo $res['ID']; ?>" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<form id="editform_<?php echo $res['ID']; ?>" action="" method="post" name="editform_<?php echo $res['ID']; ?>">
												<div class="modal-header">						
													<h4 class="modal-title">Edit Department</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">					
													<div class="form-group">
														<label>Short Description</label>
														<input type="text" class="form-control"  name="s_descedit_<?php echo $res['ID']; ?>" id="s_descedit_<?php echo $res['ID']; ?>" value="<?php echo $res['S_descr']; ?>" required>
													</div>
													<div class="form-group">
														<label>Full Description</label>
														<input type="text" class="form-control"  name="f_desc_<?php echo $res['ID']; ?>" id="f_desc_<?php echo $res['ID']; ?>" value="<?php echo $res['F_descr']; ?>" required>
													</div>
													<div class="form-group">
														<label>User Id</label>
														<input type="text" class="form-control" name="user_id_<?php echo $res['ID']; ?>" id="user_id_<?php echo $res['ID']; ?>" value="<?php echo $res['User_ID']; ?>"  required>
													</div>			
												</div>
												<div class="modal-footer">
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
													<input type="button" class="btn btn-info" value="Save" onclick="save_update('<?php echo $res['ID']; ?>')" />
												</div>
											</form>
										</div>
									</div>
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
	<!-- Edit Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="addform" action="" method="post" name="addform">
					<div class="modal-header">						
						<h4 class="modal-title">Add Department</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
					<div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>					
						<div class="form-group">
							<label>Short Description</label>
							<input type="text" class="form-control"  name="s_desc" id="s_desc"  required >
						</div>
						<div class="form-group">
							<label>Full Description</label>
							<input type="text" class="form-control"  name="f_desc" id="f_desc"  required >
						</div>
						<div class="form-group">
							<label>User Id</label>
							<input type="text" class="form-control" name="user_id" id="user_id"  required >
						</div>			
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="button" class="btn btn-info" value="Save" onclick="save_add('<?php echo $res['ID']; ?>')" />
					</div>
				</form>
			</div>
		</div>

	</div>
<script>
	function delete_record(id){
		var $ele = $("#deleteEmployeeModal_"+id).parent().parent();
		$.post("manage_department.php",{id: id,action: 'delete'},function(res){
			if(res=="success"){
				$("#deleteEmployeeModal_"+id).hide();
				 $("#successmsgid").show();
				 $(".modal-backdrop").hide();
				 $ele.fadeOut().remove();
			}
		});
	}

	$(".close").click(function(){
		$(".alert-success").hide();
	});

	function save_update(id){
		var s_descedit=$("#s_descedit_"+id).val();
		var f_desc=$("#f_desc_"+id).val();
		var user_id=$("#user_id_"+id).val();
		if(s_descedit!=""  || f_desc!=""){
			$.post("manage_department.php",{id: id,s_descedit: s_descedit,f_desc: f_desc,user_id: user_id},function(res){
				console.log(res);
				if(res=="success"){
					window.location.reload();
				}
			});
		}
	}

	function save_add(id){
		var s_desc=$("#s_desc").val();
		var f_desc=$("#f_desc").val();
		var user_id=$("#user_id").val();
		if(s_desc!="" || f_desc!=""){
			$.post("manage_department.php",{action: "add",s_desc: s_desc,f_desc: f_desc,user_id: user_id},function(res){
				if(res=="duplicate"){
					$("#dup_msg").show();
				}else{
					$("#dup_msg").hide();
					window.location.reload();
				}
			});
		}
	}

	
  
</script>

<?php include 'footer.php';?>

</body>
</html>                                		                            