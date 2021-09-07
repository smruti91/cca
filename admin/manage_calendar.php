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


if(isset($_POST) && !empty($_POST['dateedit']) ){

$dateedit = date("Y-m-d",strtotime($_POST['dateedit']));
$titleedit=$_POST['titleedit'];
$locationedit=$_POST['locationedit'];
$descriptionedit=$_POST['descriptionedit'];

$id=$_POST['id'];
$sql_updt = 'UPDATE cca_calendar SET hl_date="'.$dateedit.'",hl_title="'.$titleedit.'",hl_locn="'.$locationedit.'",hl_desc="'.$descriptionedit.'" WHERE id='.$id;
$result_updt = mysqli_query($mysqli, $sql_updt);
echo "success";
exit();
}

if(isset($_POST) && !empty($_POST['id'])){
$id=$_POST['id'];
$result_dlt = mysqli_query($mysqli, "DELETE FROM cca_calendar WHERE id=$id");
echo "success";
exit();
}

if(isset($_POST) && !empty($_POST['action'])){
// print_r($_POST['hl_date']);exit();
$hl_date = date("Y-m-d",strtotime($_POST['date']));
$hl_title=$_POST['title'];
$hl_locn=$_POST['location'];
$hl_desc=$_POST['description'];

$f_desc=mysqli_real_escape_string($mysqli, $_POST['f_desc']);

$result_chk_dupl = mysqli_query($mysqli, "select * from `cca_calendar` where hl_date='".$hl_date."'");
if(mysqli_num_rows($result_chk_dupl)>0){
echo "duplicate";exit();
}


$sql="INSERT INTO `cca_calendar`(`hl_date`,`hl_title`, `hl_locn`, `hl_desc`) VALUES ('$hl_date','$hl_title','$hl_locn','$hl_desc')";
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
						<h2>Manage <b>Calendar</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Calendar</span></a>
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
						<th>Date</th>
						<th>Title</th>
						<th>location</th>
						<th>Description</th>
						<td>Action</td>
                    </tr>
                </thead>
                <tbody>
                	<?php 
                	$result = mysqli_query($mysqli, "SELECT * FROM cca_calendar ORDER BY id "); 
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
                        <td><?php echo date("d-m-Y",strtotime($res['hl_date'])); ?></td>
						<td><?php echo $res['hl_title']; ?></td>
                        <td><?php echo $res['hl_locn']; ?></td>
                        <td><?php echo $res['hl_desc']; ?></td>
                        <td>
                            <a href="#editEmployeeModal_<?php echo $res['id']; ?>" class="edit" data-toggle="modal" id="edit_<?php echo $res['id']; ?>" style="display:none"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a href="#deleteEmployeeModal_<?php echo $res['id']; ?>" class="delete" data-toggle="modal" id="delete_<?php echo $res['id']; ?>" style="display:none"><i class="material-icons" data-toggle="tooltip" title="Delete" >&#xE872;</i></a>
                            <!-- Delete Modal HTML -->
								<div id="deleteEmployeeModal_<?php echo $res['id']; ?>" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<form>
												<div class="modal-header">						
													<h4 class="modal-title">Delete Calendar</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">					
													<p>Are you sure you want to delete this Record?</p>
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
													<h4 class="modal-title">Edit Calendar</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">
												<div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>					
													<div class="form-group">
														<label>Date</label>
														<input type="text" class="form-control date"  name="date" value="<?php echo date("d-m-Y",strtotime($res['hl_date'])); ?>"  id="dateedit_<?php echo $res['id']; ?>" required >
													</div>
													<div class="form-group">
														<label>Title</label>
														<input type="text" class="form-control"  name="titleedit_<?php echo $res['id']; ?>" value="<?php echo $res['hl_title']; ?>" id="titleedit_<?php echo $res['id']; ?>"  required >
													</div>
													<div class="form-group">
														<label>Location</label>
														<input type="text" class="form-control" name="locationedit_<?php echo $res['id']; ?>" value="<?php echo $res['hl_locn']; ?>"  id="locationedit_<?php echo $res['id']; ?>"  required >
													</div>
													<div class="form-group">
														<label>Description</label>
														<input type="text" class="form-control" name="descriptionedit_<?php echo $res['id']; ?>" value="<?php echo $res['hl_desc']; ?>"  id="descriptionedit_<?php echo $res['id']; ?>"  required >
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
						<h4 class="modal-title">Add Calendar</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
					<div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>					
						<div class="form-group">
							<label>Date</label>
							<input type="text" class="form-control"  name="date" id="date"  required >
						</div>
						<div class="form-group">
							<label>Title</label>
							<input type="text" class="form-control"  name="title" id="title"  required >
						</div>
						<div class="form-group">
							<label>Location</label>
							<input type="text" class="form-control" name="location" id="location"  required >
						</div>
						<div class="form-group">
							<label>Description</label>
							<input type="text" class="form-control" name="description" id="description"  required >
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
<?php include 'footer.php';?>

<link rel="stylesheet" href="../css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="../js/bootstrap-datepicker.min.js"></script>
<script>

	 $(document).ready(function(){
      var date_input=$('input[name="date"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'dd-mm-yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
       $('.date').datepicker(options);
    })

	


	function delete_record(id){
		var $ele = $("#deleteEmployeeModal_"+id).parent().parent();
		$.post("manage_calendar.php",{id: id,action: 'delete'},function(res){
			if(res=="success"){
				$("#deleteEmployeeModal_"+id).hide();
				 //$("#successmsgid").show();
				 $(".modal-backdrop").hide();
				 $ele.fadeOut().remove();
			}
		});
	}

	$(".close").click(function(){
		$(".alert-success").hide();
	});

	function save_update(id){
		var dateedit=$("#dateedit_"+id).val();
		var titleedit=$("#titleedit_"+id).val();
		var locationedit=$("#locationedit_"+id).val();
		var descriptionedit=$("#descriptionedit_"+id).val();
		if(dateedit!=""){
			$.post("manage_calendar.php",{id: id,dateedit: dateedit,titleedit: titleedit,locationedit: locationedit,descriptionedit:descriptionedit},function(res){
				console.log(res);
				if(res=="success"){
					window.location.reload();
				}
			});
		}
	}

	function save_add(){
		var date=$("#date").val();
		var title=$("#title").val();
		var location=$("#location").val();
		var description=$("#description").val();

		if(date!="" || title!=""){
			$.post("manage_calendar.php",{action: "add",date: date,title: title,location: location,description: description},function(res){
				//console.log(res);
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
</body>
</html>                                		                            