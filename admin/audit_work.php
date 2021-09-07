<?php
include_once("../config.php");

if(isset($_POST['action'] ) && $_POST['action']=='edit'){
$id=$_POST['id'];
$work_name = $_POST['work_name'];
$work_details = $_POST['work_details'];
$insti_category = $_POST['insti_category'];
$sql_updt = 'UPDATE `cca_audit_task` SET `work_name`="'.$work_name.'",`work_details`="'.$work_details.'",`institution_category`="'.$insti_category.'"  WHERE `id`='.$id;
$result_updt = mysqli_query($mysqli, $sql_updt);
echo "success";
exit();
}

if(isset($_POST) && !empty($_POST['id'])){
$id=$_POST['id'];
$result_dlt = mysqli_query($mysqli, "DELETE FROM cca_users WHERE id=$id");
echo "success";
exit();
}

if(isset($_POST) && !empty($_POST['action'])){
//$s_desc=$_POST['s_desc'];
//$f_desc=mysqli_real_escape_string($mysqli, $_POST['f_desc']);
// $user_id='admin';
$work_name = $_POST['work_name'];
$work_details = $_POST['work_details'];
$insti_category = $_POST['insti_category'];

$sql="INSERT INTO `cca_audit_task`(`work_name`, `work_details`,`institution_category`) VALUES ('$work_name','$work_details','$insti_category')";
$result_insert = mysqli_query($mysqli, $sql);
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
						<h2>Manage <b>Users</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New User</span></a>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover" id="tableid">

            	  <div class="alert alert-danger alert-dismissible" id="successmsgid" style="display:none;">
				    <a href="#" class="close"  aria-label="close">&times;</a>
				    <strong>Success!</strong> Record Deleted Successfully.
				  </div>

                <thead>
                    <tr>
						<th>
							<span class="custom-checkbox">
								<input type="checkbox" id="selectAll">
								<label for="selectAll"></label>
							</span>
						</th>
                        <th>Sl_No</th>
						<th>Work Name</th>
						<th>Work Details</th>
						<th>Institution Category</th>
						<th>Action</th>
                    </tr>
                </thead>
                <tbody>
                	<?php 
                	$result = mysqli_query($mysqli, "SELECT * FROM cca_audit_task ORDER BY id "); 
                	if(mysqli_num_rows($result)>0){

					//while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
					$count = 0;
					//while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
					while($res = mysqli_fetch_array($result)) {
					$count++ ;
					
					?>
                    <tr id="trow_<?php echo $res['id'];?>">
						<td>
							<span class="custom-checkbox">
								<input type="checkbox" class="checkbox1" name="options[]" value="<?php echo $res['id']; ?>">
								<label for="checkbox1"></label>
							</span>
						</td>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $res['work_name']; ?></td>
						<td><?php echo $res['work_details']; ?></td>
                        <td><?php echo $res['institution_category']; ?></td>
                        <td>
                            <a href="#editEmployeeModal_<?php echo $res['id']; ?>" class="edit" data-toggle="modal" id="edit_<?php echo $res['id']; ?>" style="display:none"><i class="material-icons" data-toggle="tooltip" title="Edit" >&#xE254;</i></a>
                            <a href="#deleteEmployeeModal_<?php echo $res['id']; ?>" class="delete"  id="delete_<?php echo $res['id']; ?>" style="display:none" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                            <!-- Delete Modal HTML -->
								<div id="deleteEmployeeModal_<?php echo $res['id']; ?>" class="modal fade">
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
													<input type="hidden" value="<?php echo $res['id']; ?>" class="recordid" />
													<input type="button" class="btn btn-danger btn-dlt" value="Delete" id="<?php echo $res['id']; ?>" onclick="delete_record(this.id)" />
												</div>
											</form>
										</div>
									</div>
								</div>

								<!-- Edit Modal HTML -->
								<div id="editEmployeeModal_<?php echo $res['id']; ?>" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<form id="editform_<?php echo $res['id']; ?>" action="" method="post" name="editform_<?php echo $res['id']; ?>">
												<div class="modal-header">						
													<h4 class="modal-title">Edit Audit Work</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">					
													<div class="form-group">
														<label>Work Name</label>
														<textarea name="work_name" rows="4"  id="work_name_<?php echo $res['id']; ?>" cols="45"><?php echo $res['work_name']; ?></textarea>	
													</div>
													<div class="form-group">
														<label>Work Details</label>
														<textarea  name="work_details" rows="4" cols="45" id="work_details_<?php echo $res['id']; ?>" ><?php echo $res['work_details']; ?></textarea>
													</div>
													<div class="form-group">
														<label>Institution Category</label>
														<select class="form-control" data-placeholder="Choose a category" tabindex="1" name="insti_categoryedit_<?php echo $res['id']; ?>" id="insti_category_<?php echo $res['id']; ?>"  >
															<?php
															
															$sel_category	= mysqli_query($mysqli,"SELECT * FROM `institution_category`");
															 ?>	
															 				
															<option value='0'>Select Category</option>
															
															<?php while($res_category = mysqli_fetch_array($sel_category)) { ?>
															<option value="<?php echo $res_category['ID']?>"<?php if($res_category['ID']== $res['institution_category']){ echo "selected";}?>  ><?php echo $res_category['Category']?> </option>
															<?php } ?>
														</select>
													</div>	
																								
												</div>
												<div class="modal-footer">
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
													<input type="button" class="btn btn-info" value="Save" onclick="save_update('<?php echo $res['id']; ?>')" />
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
						<h4 class="modal-title">Add Audit Work</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Work Name</label>
							<textarea name="work_name" rows="4"  id="work_name" cols="45"></textarea>	
						</div>
						<div class="form-group">
							<label>Work Details</label>
							<textarea  name="work_details" rows="4" cols="45" id="work_details"></textarea>
						</div>
						<div class="form-group">
							<label>Institution Category</label>
							<select class="form-control" data-placeholder="Choose a category" tabindex="1" name="insti_category" id="insti_category">
								<?php
								
								$sel_category	= mysqli_query($mysqli,"SELECT * FROM `institution_category`");
								 ?>	
								 				
								<option value='0'>Select Category</option>
								
								<?php while($res_category = mysqli_fetch_array($sel_category)) { ?>
								<option value="<?php echo $res_category['ID']?>"><?php echo $res_category['Category']?> </option>
								<?php } ?>
							</select>
						</div>	
																	
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="button" class="btn btn-info" value="Save" onclick="save_add()" />
					</div>
				</form>
			</div>
		</div>
	</div>
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
		var work_name = $("#work_name_"+id).val();
		var work_details = $("#work_details_"+id).val();
		var insti_category = $("#insti_category_"+id).val();
		
			$.post("audit_work.php",{action: 'edit',id : id, work_name: work_name, work_details: work_details, insti_category: insti_category},function(res){
				// console.log(res);
						if(res=="success"){

						window.location.reload();
				}
			});
	}

	function save_add(id){
		
		var work_name = $("#work_name").val();
		var work_details = $("#work_details").val();
		var insti_category = $("#insti_category").val();
		
		
			$.post("audit_work.php",{action: "add",work_name: work_name,work_details: work_details,insti_category : insti_category},function(res){
				console.log(res);
				if(res=="duplicate"){
					$("#dup_msg").show();
				}else{
					$("#dup_msg").hide();
					window.location.reload();
				}
			});
		}
	
  
</script>
<?php include 'footer.php';?>
</body>
</html>                                		                            