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


if(isset($_POST['action']) && ($_POST['action']=='update')) {
	
$id=$_POST['id'];
$ddo_code=mysqli_real_escape_string($mysqli, $_POST['ddo_code']);
$designation=mysqli_real_escape_string($mysqli, $_POST['designation']);
$department=mysqli_real_escape_string($mysqli, $_POST['department']);
$institution_name=mysqli_real_escape_string($mysqli, $_POST['institution_name']);
$institution_short_abr=mysqli_real_escape_string($mysqli, $_POST['institution_short_abr']);
$mandays_party=mysqli_real_escape_string($mysqli, $_POST['mandays_party']);
$mandays_institution=mysqli_real_escape_string($mysqli, $_POST['mandays_institution']);
$institution_category=$_POST['institution_category'];
$sql_updt = 'UPDATE cca_institutions SET ddo_code="'.$ddo_code.'",designation="'.$designation.'",dept_id="'.$department.'",institution_name="'.$institution_name.'",institution_shortabr="'.$institution_short_abr.'",institution_category="'.$institution_category.'",mandays_audit="'.$mandays_party.'",mandays_review="'.$mandays_institution.'"  WHERE id='.$id;
$result_updt = mysqli_query($mysqli, $sql_updt);
echo "success";
exit();

}

if(isset($_POST['action']) && ($_POST['action']=='delete') ){
$id=$_POST['id'];
$result_dlt = mysqli_query($mysqli, "DELETE FROM cca_institutions WHERE id=$id");
echo "success";
exit();
}

if(isset($_POST['action']) && !empty($_POST['action']=='add')){


$ddo_code=mysqli_real_escape_string($mysqli, $_POST['ddo_code']);
$designation=mysqli_real_escape_string($mysqli, $_POST['designation']);
$department=mysqli_real_escape_string($mysqli, $_POST['department']);
$institution_name=mysqli_real_escape_string($mysqli, $_POST['institution_name']);
$institution_short_abr=mysqli_real_escape_string($mysqli, $_POST['institution_short_abr']);
$mandays_party=mysqli_real_escape_string($mysqli, $_POST['mandays_party']);
$mandays_institution=mysqli_real_escape_string($mysqli, $_POST['mandays_institution']);
$institution_category=$_POST['institution_category'];
$result_chk_dupl = mysqli_query($mysqli, "select * from `cca_institutions` where ddo_code='".$ddo_code."'");

if(mysqli_num_rows($result_chk_dupl)>0){
echo "duplicate";exit();
}


  $sql="INSERT INTO `cca_institutions`(`ddo_code`, `designation`, `dept_id`, `institution_name`, `institution_shortabr`,`institution_category`, `mandays_audit`, `mandays_review`) VALUES ('$ddo_code','$designation','$department','$institution_name','$institution_short_abr','$institution_category','$mandays_party','$mandays_institution')";


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
						<h2>Manage <b>Institutions</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Institution</span></a>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover" id="tableid">

            	  <div class="alert alert-success alert-dismissible" id="successmsgid" style="display:none;">
				    <a href="#" class="close"  aria-label="close">&times;</a>
				    Record Deleted Successfully!!!
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
						<th>DDO Code</th>
						<th>Designation</th>
						<th>Department</th>
						<th>Institution Name</th>
						<th>institution_shortabr</th>
						<th>Institution Category</th>
						<th>mandays_Audit</th>
						<th>mandays_Review</th>
						<th>Action</th>
                    </tr>
                </thead>
                <tbody>
                	<?php 
                	$result = mysqli_query($mysqli, "SELECT * FROM cca_institutions ORDER BY id desc"); 
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
                        <td><?php echo $res['ddo_code']; ?></td>
						<td><?php echo $res['designation']; ?></td>
                        <td>
                        	<?php 
                        	$dept = mysqli_query($mysqli, "SELECT F_descr FROM cca_departments where id = ".$res['dept_id']);
							$dept_res = mysqli_fetch_array($dept);
							echo htmlentities($dept_res['F_descr']); 
                        	?>
                        		
                        	</td>
	                        <td><?php echo htmlentities($res['institution_name']); ?></td>
	                        <td><?php echo htmlentities($res['institution_shortabr']); ?></td>
	                        <td>
                        	
                        	<?php 
                        	$institution_cat = mysqli_query($mysqli, "SELECT Category FROM institution_category where id = ".$res['institution_category']);
							$institution_cat_res = mysqli_fetch_array($institution_cat);
							echo htmlentities($institution_cat_res['Category']); 
                        	?>
                        </td>
                        <td><?php echo htmlentities($res['mandays_audit']); ?></td>
                        <td><?php echo htmlentities($res['mandays_review']); ?></td>
                        <td>
                            <a href="#editEmployeeModal_<?php echo $res['id']; ?>" class="edit" data-toggle="modal" id="edit_<?php echo $res['id']; ?>" style="display:none"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a href="#deleteEmployeeModal_<?php echo $res['id']; ?>" class="delete" data-toggle="modal" id="delete_<?php echo $res['id']; ?>" style="display:none"><i class="material-icons" data-toggle="tooltip" title="Delete" >&#xE872;</i></a>
                            <!-- Delete Modal HTML -->
								<div id="deleteEmployeeModal_<?php echo $res['id']; ?>" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<form>
												<div class="modal-header">						
													<h4 class="modal-title">Delete Institution</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">					
													<p>Are you sure you want to delete these Records?</p>
													<p class="text-warning"><small>This action cannot be undone.</small></p>
												</div>
												<div class="modal-footer">
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
													<input type="hidden" value="<?php echo htmlentities($res['id']); ?>" class="recordid" />
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
													<h4 class="modal-title">Edit Institution</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">					
													<div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>					
														<div class="form-group">
															<label>DDO Code</label>
															<input type="text" class="form-control"  name="ddo_code" id="ddo_code<?php echo $res['id']; ?>" value="<?php echo $res['ddo_code']; ?>"  required >
														</div>
														<div class="form-group">
															<label>Designation</label>
															<input type="text" class="form-control"  name="ddo_code" id="designation<?php echo $res['id']; ?>" value="<?php echo $res['designation']; ?>"  required >
														</div>
														<div class="form-group">
															<label>Department</label>
															<select class="form-control" data-placeholder="Choose a dept" tabindex="1" name="department" id="department<?php echo $res['id']; ?>"  >
																<?php
																$sel_dept	= mysqli_query($mysqli,"SELECT ID,F_descr,S_descr FROM `cca_departments`");
																 ?>				
																<option value='0'>Select Department</option>
																<?php while($res_dept = mysqli_fetch_array($sel_dept)) { ?>
																<option <?php if($res_dept['ID']==$res['dept_id']) { echo "selected='selected'"; } ?> value="<?php echo $res_dept['ID']?>"  ><?php echo $res_dept['F_descr']?> (<?php echo $res_dept['S_descr']?>)</option>
																<?php } ?>
															</select>
														</div>
														<div class="form-group">
															<label>Institution Name</label>
															<input type="text" class="form-control" name="institution_name" id="institution_name<?php echo $res['id']; ?>" value="<?php echo $res['institution_name']; ?>"  required />
														</div>
														<div class="form-group">
															<label>Institution Short_abr</label>
															<input type="text" class="form-control" name="institution_short_abr" id="institution_short_abr<?php echo $res['id']; ?>"   value="<?php echo $res['institution_shortabr']; ?>"  required />
														</div>
														<div class="form-group">
															<label>Institution Category</label>
															<select class="form-control" data-placeholder="Choose a dept" tabindex="1" name="category" id="category<?php echo $res['id']; ?>"  >
																<?php
																
																$sel_category	= mysqli_query($mysqli,"SELECT Category,id FROM `institution_category`");
																 ?>				
																<option value='0'>Select Category</option>
																<?php while($res_category = mysqli_fetch_array($sel_category)) { ?>
																<option value="<?php echo $res_category['id']?>"><?php echo $res_category['Category']?> </option>
																<?php } ?>
															</select>
														</div>		
														<div class="form-group">
															<label>Audit Mandays</label>
															<input type="text" class="form-control" name="mandays_party" id="mandays_party<?php echo $res['id']; ?>" value="<?php echo $res['mandays_audit']; ?>"  required />
														</div>	
														<div class="form-group">
															<label>Review Mandays</label>
															<input type="text" class="form-control" name="mandays_institution" id="mandays_institution<?php echo $res['id']; ?>" value="<?php echo htmlentities($res['mandays_review']); ?>"  required />
														</div>			
												</div>
												<div class="modal-footer">
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
													<input type="button" class="btn btn-info" value="Save" onclick="save_update('<?php echo htmlentities($res['id']); ?>')" />
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
						<h4 class="modal-title">Add Institution</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
					<div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>					
						<div class="form-group">
							<label>DDO Code</label>
							<input type="text" class="form-control"  name="s_desc" id="ddo_code"  required >
						</div>
						<div class="form-group">
							<label>Designation</label>
							<input type="text" class="form-control"  name="designation" id="designation"  required />
						</div>
						<div class="form-group">
							<label>Department</label>
							<select class="form-control" data-placeholder="Choose a dept" tabindex="1" name="department" id="department"  >
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
							<label>Institution Name</label>
							<input type="text" class="form-control" name="institution_name" id="institution_name"  required />
						</div>
						<div class="form-group">
							<label>Institution Short_abr</label>
							<input type="text" class="form-control" name="institution_short_abr" id="institution_short_abr"  required />
						</div>
						<div class="form-group">
							<label>Institution Category</label>
							<select class="form-control" data-placeholder="Choose a category" tabindex="1" name="category" id="category"  >
								<?php
								
								$sel_category	= mysqli_query($mysqli,"SELECT Category,id FROM `institution_category`");
								 ?>				
								<option value='0'>Select Category</option>
								<?php while($res_category = mysqli_fetch_array($sel_category)) { ?>
								<option value="<?php echo $res_category['id']?>"><?php echo $res_category['Category']?> </option>
								<?php } ?>
							</select>
						</div>	
						<div class="form-group">
							<label>Audit Mandays</label>
							<input type="text" class="form-control" name="mandays_party" id="mandays_party"  required />
						</div>	
						<div class="form-group">
							<label>Review Mandays</label>
							<input type="text" class="form-control" name="mandays_institution" id="mandays_institution"  required />
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
<script>
	function delete_record(id){
		var $ele = $("#deleteEmployeeModal_"+id).parent().parent();
		$.post("manage_institution.php",{id: id,action: 'delete'},function(res){
			if(res=="success"){
				$("#deleteEmployeeModal_"+id).hide();
				
				 $("#successmsgid").show();
				 $(".modal-backdrop").hide();
				 //$ele.fadeOut().remove();
			}
		});
	}

	$(".close").click(function(){
		$(".alert-success").hide();
	});

	function save_update(id){
		var ddo_code=$("#ddo_code"+id).val();
		var designation=$("#designation"+id).val();
		var department=$("#department"+id).val();
		var institution_name=$("#institution_name"+id).val();
		var institution_short_abr=$("#institution_short_abr"+id).val();
		var mandays_party=$("#mandays_party"+id).val();
		var mandays_institution=$("#mandays_institution"+id).val();
		var institution_category=$("#category"+id).val();


		if(ddo_code!=""){
			$.post("manage_institution.php",{id: id,ddo_code: ddo_code,designation: designation,department:department,institution_name: institution_name,institution_category: institution_category,institution_short_abr: institution_short_abr,mandays_party: mandays_party,mandays_institution: mandays_institution,action: 'update'},function(res){
				console.log(res);
				if(res=="success"){
					window.location.reload();
				}
			});
		}
	}

	function save_add(){
		var ddo_code=$("#ddo_code").val();
		var designation=$("#designation").val();
		var department=$("#department").val();
		var institution_name=$("#institution_name").val();
		var institution_short_abr=$("#institution_short_abr").val();
		var mandays_party=$("#mandays_party").val();
		var mandays_institution=$("#mandays_institution").val();
		var institution_category=$("#category").val();

		if(ddo_code!="" || ddo_code!=""){
			
			$.post("manage_institution.php",{ddo_code: ddo_code,designation: designation,department:department,institution_name: institution_name,institution_category: institution_category,institution_short_abr: institution_short_abr,mandays_party: mandays_party,mandays_institution: mandays_institution,action: 'add'},function(res){
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