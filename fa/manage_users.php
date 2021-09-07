<?php 
session_start();
include("../common_functions.php");
include_once("../config.php");

$dept_id=$_SESSION['dept_id'];
$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
 
 if($userid == -1)
  {
    header('location:../index.php');
    exit;
  }

  
if(isset($_POST) && !empty($_POST['action'])){
$init = $_POST['prog'];


$initiate = date("Y-m-d",strtotime($_POST['initiate']));

$commence = date("Y-m-d", strtotime($_POST['commence']));

$complete = date("Y-m-d", strtotime($_POST['complete']));
$sql =" INSERT INTO `cca_plan` ( `plan_name`, `plan_start_date`, `plan_end_date`,  `plan_close_date`) VALUES ( '$init', '$initiate', '$commence',  '$complete')";
  $result_insert = mysqli_query($mysqli, $sql);
echo "success";
exit();
}
?>

<?php include "header.php"; ?>
<style>
div.main{
height:100%;
}

form-group{
	text-align:left;
}
</style>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>Manage User</h1>
            <hr>
            <div class="col-sm-12">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons"></i> <span>Add New User</span></a>
					</div>
                <div class="row" style="padding-bottom:50px;">

                    <table class="table table-striped table-bordered" id="tableid">
                    <thead>

                      <tr>
                      	<th>

							<!-- <span class="custom-checkbox">
								<input type="checkbox" id="selectAll" style="-webkit-appearance: checkbox;">
								<label for="selectAll"></label>
							</span> -->
						</th>
                        <th>Sl_No</th>
						<th>Designation</th>
						<th>Role</th>
						<th>Department</th>
						<th>Name</th>
						<th>Username</th>
						<th>E-Mail</th>
						<th>Mobile no.</th>
						<th>user_id</th>
						<th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                             $result = mysqli_query($mysqli, "SELECT * FROM cca_users where Dept_ID='".$dept_id."' ORDER BY id "); 
		                	if(mysqli_num_rows($result)>0){

							//while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
							$count = 0;
							//while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
							while($res = mysqli_fetch_array($result)) {
							$count++ ;
							$desg = mysqli_query($mysqli, "SELECT F_descr FROM cca_designations where id = ".$res['Desig_ID']);
							$desg_res = mysqli_fetch_array($desg);
							$role = mysqli_query($mysqli, "SELECT F_descr FROM cca_roles where id = ".$res['Role_ID']);
							$role_res = mysqli_fetch_array($role);
							$dept = mysqli_query($mysqli, "SELECT F_descr FROM cca_departments where id = ".$res['Dept_ID']);
							$dept_res = mysqli_fetch_array($dept);
                      ?>
                      <tr>
	                     	<td>
								<span class="custom-checkbox">
									<input type="checkbox" class="checkbox1" name="options[]" value="<?php echo $res['ID']; ?>">
									<label for="checkbox1"></label>
								</span>
							</td>
	                        <td><?php echo $count; ?></td>
	                       <!-- <td><?php echo $res['Desig_ID']; ?></td>-->
	                       <td><?php echo $desg_res['F_descr']; ?></td>
							<td><?php
							if(is_null($role_res)){
								echo "";
							}else{
								echo $role_res['F_descr'];
							}

							
							  ?></td>
	                        <td><?php echo $dept_res['F_descr']; ?></td>
	                        <td><?php echo $res['Name']; ?></td>
	                        <td><?php echo $res['username']; ?></td>
	                        <td><?php echo $res['e-mail']; ?></td>
	                        <td><?php echo $res['mobile']; ?></td>
	                        <td><?php echo $res['User_ID']; ?></td>
	                        <td>
	                            <a href="#editEmployeeModal_<?php echo $res['ID']; ?>" class="edit" data-toggle="modal" id="edit_<?php echo $res['ID']; ?>" style="display:none"><i class="material-icons" data-toggle="tooltip" title="Edit" >&#xE254;</i></a>
	                            <a href="#deleteEmployeeModal_<?php echo $res['ID']; ?>" class="delete"  id="delete_<?php echo $res['ID']; ?>" style="display:none" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
	                            <!-- Delete Modal HTML -->
									<div id="deleteEmployeeModal_<?php echo $res['ID']; ?>" class="modal fade">
										<div class="modal-dialog">
											<div class="modal-content">
												<form>
													<div class="modal-header">						
														<h4 class="modal-title">Delete User</h4>
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
														<h4 class="modal-title">Edit User</h4>
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													</div>
													<div class="modal-body">					
														<div class="form-group">
															
															

						<label>Designation</label>
								<select class="form-control" data-placeholder="Choose a designation" tabindex="1" name="desgedit_<?php echo $res['ID']; ?>" id="desgedit_<?php echo $res['ID']; ?>"  >
				<?php
				
				$sel_desg	= mysqli_query($mysqli,"SELECT ID,F_descr,S_descr FROM `cca_designations`");
				


				 ?>	
				 				
				<option value='0'>Select Designation </option>
				
				<?php while($res_desg = mysqli_fetch_array($sel_desg)) { ?>
				<option value="<?php echo $res_desg['ID']?>" <?php if($res_desg['ID']== $res['Desig_ID']){ echo "selected";}?> ><?php echo $res_desg['F_descr']?> (<?php echo $res_desg['S_descr']?>)</option>
				<?php } ?>
			</select>									


														</div>
														<div class="form-group">
															<label>Role</label>
								<select class="form-control" data-placeholder="Choose a role" tabindex="1" name="roleedit_<?php echo $res['ID']; ?>" id="roleedit_<?php echo $res['ID']; ?>"  >
				<?php
				
				$sel_role	= mysqli_query($mysqli,"SELECT ID,F_descr,S_descr FROM `cca_roles`");
				


				 ?>	
				 				
				<option value='0'>Select Role </option>
				
				<?php while($res_role = mysqli_fetch_array($sel_role)) { ?>
				<option value="<?php echo $res_role['ID']?>" <?php if($res_role['ID']== $res['Role_ID']){ echo "selected";}?>  ><?php echo $res_role['F_descr']?> (<?php echo $res_role['S_descr']?>)</option>
				<?php } ?>
			</select>
														</div>
														<div class="form-group">
															<label>Department</label>
								<select class="form-control" data-placeholder="Choose a dept" tabindex="1" name="deptedit_<?php echo $res['ID']; ?>" id="deptedit_<?php echo $res['ID']; ?>"  >
				<?php
				
				$sel_dept	= mysqli_query($mysqli,"SELECT ID,F_descr,S_descr FROM `cca_departments`");
				


				 ?>	
				 				
				<option value='0'>Select Department</option>
				
				<?php while($res_dept = mysqli_fetch_array($sel_dept)) { ?>
				<option value="<?php echo $res_dept['ID']?>"<?php if($res_dept['ID']== $res['Dept_ID']){ echo "selected";}?>  ><?php echo $res_dept['F_descr']?> (<?php echo $res_dept['S_descr']?>)</option>
				<?php } ?>
			</select>
														</div>	
							<div class="form-group">
															<label>Name</label>
															<input type="text" class="form-control"  name="nameedit_<?php echo $res['ID']; ?>" id="nameedit_<?php echo $res['ID']; ?>" value="<?php echo $res['Name']; ?>" required>
														</div>							
				<div class="form-group">
															<label>E-Mail</label>
															<input type="text" class="form-control"  name="emailedit_<?php echo $res['ID']; ?>" id="emailedit_<?php echo $res['ID']; ?>" value="<?php echo $res['e-mail']; ?>" required>
														</div>
				<div class="form-group">
															<label>Mobile</label>
															<input type="text" class="form-control"  name="mobileedit_<?php echo $res['ID']; ?>" id="mobileedit_<?php echo $res['ID']; ?>" value="<?php echo $res['mobile']; ?>" required>
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
    </div>
</div>

<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="addform" action="" method="post" name="addform">
					<div class="modal-header">						
						<h4 class="modal-title">Add User</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
					<div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>				
					

					<div class="form-group">
							<label>Designation</label>
							<select class="form-control" data-placeholder="Choose a designation" tabindex="1" name="desg" id="desg"  >
								<?php
								
								$sel_desg	= mysqli_query($mysqli,"SELECT ID,F_descr,S_descr FROM `cca_designations`");
								 ?>	
								 				
								<option value='0'>Select Designation </option>
								
								<?php while($res_desg = mysqli_fetch_array($sel_desg)) { ?>
								<option value="<?php echo $res_desg['ID']?>"  ><?php echo $res_desg['F_descr']?> (<?php echo $res_desg['S_descr']?>)</option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Role</label>
							<select class="form-control" data-placeholder="Choose a role" tabindex="1" name="role" id="role"  >
								<?php
								
								$sel_role	= mysqli_query($mysqli,"SELECT ID,F_descr,S_descr FROM `cca_roles`");
								


								 ?>	
								 				
								<option value='0'>Select Role </option>
								
								<?php while($res_role = mysqli_fetch_array($sel_role)) { ?>
								<option value="<?php echo $res_role['ID']?>"  ><?php echo $res_role['F_descr']?> (<?php echo $res_role['S_descr']?>)</option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Department</label>
							<select class="form-control" data-placeholder="Choose a dept" tabindex="1" name="dept" id="dept"  >
								<?php
								
								$sel_dept	= mysqli_query($mysqli,"SELECT ID,F_descr,S_descr FROM `cca_departments`");

								 ?>	
								 				
								<option value='0'>Select Department</option>
								
								<?php while($res_dept = mysqli_fetch_array($sel_dept)) { ?>
								<option value="<?php echo $res_dept['ID']?>"  ><?php echo $res_dept['F_descr']?> (<?php echo $res_dept['S_descr']?>)</option>
								<?php } ?>
							</select>
						</div>	
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control"  name="name" id="name"  required >
						</div>

						<div class="form-group">
							<label>Username</label>
							<?php 

							$sel_lastid	= mysqli_query($mysqli,"SELECT id FROM `cca_users` ORDER BY id DESC LIMIT 1 ");
							$sel_lastidrow = mysqli_fetch_array($sel_lastid);
							$new_username=$sel_lastidrow['id']+1;
							?>
							<input type="text" class="form-control"  name="username" id="username" readonly="true"  value="<?php echo "CCA_". $new_username; ?>" required >
						</div>

						<div class="form-group">
							<label>E-mail</label>
							<input type="text" class="form-control"  name="email" id="email"  required >
						</div>
						<div class="form-group">
							<label>Mobile</label>
							<input type="text" class="form-control" name="mobile" id="mobile"  required >
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
        <!-- /.container-fluid -->
    </div>
    <div class="clear:both;"></div>
</div>
<?php include "footer.php"; ?>
    <!-- Bootstrap Date-Picker Plugin -->
<link rel="stylesheet" href="../css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="../js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	 dltarr = new Array();
	// Activate tooltip
	$('[data-toggle="tooltip"]').tooltip();
	
	// Select/Deselect checkboxes
	var checkbox = $('table tbody input[type="checkbox"]');
	$("#selectAll").click(function(){
		if(this.checked){
			checkbox.each(function(){
				this.checked = true; 
			 //  	var val=this.value;
				// dltarr.push(val)                     
			});
		} else{
			checkbox.each(function(){
				this.checked = false;                        
			});
		} 
	});
	checkbox.click(function(){
		var rowid=this.value;
		if(!this.checked){
			$("#trow_"+rowid).css('background-color','');
			$("#edit_"+rowid).hide();
			$("#delete_"+rowid).hide();
		}
		if(this.checked){
			
			$(this).parent('span').parent('td').parent('tr').siblings('tr').css('background-color','');
			$(".edit").hide();
			$(".delete").hide();
			$('input.checkbox1').not(this).prop('checked', false);

			$("#trow_"+rowid).css('background-color','#08080838');
			$("#edit_"+rowid).show();
			$("#delete_"+rowid).show();
			
		}
	});
});

$(document).ready(function() {
    $('#tableid').DataTable();
} );

</script>
	
<script>
	function delete_record(id){
		var $ele = $("#deleteEmployeeModal_"+id).parent().parent();
		$.post("user_entry.php",{id: id,action: 'delete'},function(res){
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
		
		var desgedit= $("#desgedit_"+id).val();

		
		var roleedit= $("#roleedit_"+id).val();

		var deptedit= $("#deptedit_"+id).val();

		var nameedit= $("#nameedit_"+id).val();

		var emailedit= $("#emailedit_"+id).val();
		var mobileedit=$("#mobileedit_"+id).val();
		
			$.post("user_entry.php",{action: 'edit',id : id, desg: desgedit, role: roleedit, dept: deptedit, name: nameedit, email: emailedit, mobile: mobileedit},function(res){
				// console.log(res);
						if(res=="success"){

						window.location.reload();
				}
			});
	}

	function save_add(id){
		
		var desg = $("#desg").val();
		var role = $("#role").val();
		var dept = $("#dept").val();
		var name =$("#name").val();
		var email=$("#email").val();
		var mobile=$("#mobile").val();
		var username=$("#username").val();
		
		if(name!="" || email!=""){
			$.post("user_entry.php",{action: "add",desg: desg,role: role,dept : dept,name : name,email : email, mobile :mobile,username: username},function(res){
				console.log(res);
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

