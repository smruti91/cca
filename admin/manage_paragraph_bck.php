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


if(isset($_POST['action']) && $_POST['action'] == "add_para"  ) {
// print_r($_POST['hl_date']);exit();

$para_title=$_POST['para_title'];

$sql_max_id = " SELECT MAX(para_id) AS last_para_id  FROM `cca_paragraph` ";  // fetch last para_id inserted
$result = mysqli_query($mysqli,$sql_max_id); 
$res_max_id = mysqli_fetch_array($result);

$last_para_id = $res_max_id['last_para_id'] +1 ;

$sql="INSERT INTO `cca_paragraph`(`para_id`,`para_head`, `para_category`) VALUES ('$last_para_id','$para_title',0)";
$result_insert = mysqli_query($mysqli, $sql);

}
 

if(isset($_POST['action']) && $_POST['action'] == "add_sub_para"  ) {

   $para_id = $_POST['para_id'];
   $sub_para_title = $_POST['sub_para'];

   $sql_add_subPara = " INSERT INTO `cca_paragraph`(`para_id`,`para_head`, `para_category`) VALUES ('$para_id','$sub_para_title',1) ";

   $result_insert = mysqli_query($mysqli,$sql_add_subPara);
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
						<h2>Manage  <b>Paragraph</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addPara" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add Para</span></a>
						<a href="#addSubPara" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add Sub Para</span></a>
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
						<th>Para Title</th>
						<!-- <th>Title</th>
						<th>location</th>
						<th>Description</th> -->
						<th>Action</th>
                    </tr>
                </thead>
                <tbody>
                	<?php 
                	$result = mysqli_query($mysqli, "SELECT * FROM cca_paragraph ORDER BY id "); 
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
                        <td><?php echo $res['para_head']; ?></td>
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
	<!-- Add Para Modal HTML -->
	<div id="addPara" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="addform"  action="" method="post" name="addform">
					<div class="modal-header">						
						<h4 class="modal-title">Add Parahraph</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
					<div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>					
						
						<div class="form-group">
							<label>Para Title</label>
							<input type="text" class="form-control"  name="para_title" id="para_title"  required >
						</div>
						
								
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="button" class="btn btn-info" value="Save" onclick="save_para()" />
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Add Sub Para Modal HTML -->
	<div id="addSubPara" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="addform"  action="" method="post" name="addform">
					<div class="modal-header">						
						<h4 class="modal-title">Add Parahraph</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
					<div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>					
						
						<div class="form-group">
							<label>Select Para</label>
							<select class="form-control" data-placeholder="Choose a category" tabindex="1" name="para_title" id="para_id">
								<?php
								
								     $sel_para	= mysqli_query($mysqli,"SELECT * FROM `cca_paragraph`");
								 ?>	
								 				
								<option value='0'>Select Para</option>
								
								<?php while($res_para = mysqli_fetch_array($sel_para)) { ?>
								<option value="<?php echo $res_para['para_id']?>"><?php echo $res_para['para_head']?> </option>
								<?php } ?>
							</select>
						</div>	
						<div class="form-group">
							<label>Sub Para</label>
							<input type="text" class="form-control" name="sub_para" id="sub_para" >
						</div>
								
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="button" class="btn btn-info" value="Save" onclick="save_sub_para()" />
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

	function save_para(){
		console.log(123);
		var para_title=$("#para_title").val();
		var sub_para=$("#sub_para").val();
		console.log(para_title);

		
			$.post("manage_paragraph.php",{action: "add_para",para_title: para_title,sub_para: sub_para},function(res){
				console.log(res);
				if(res=="success"){
					$("#dup_msg").show();
					window.location.reload();
				}else{
					$("#dup_msg").hide();
					console.log('something wrong');
				}
			});
		}

		function save_sub_para(){
		console.log(123);
		var para_id = $("#para_id").val();
		
		var sub_para=$("#sub_para").val();
		console.log(para_title);

		
			$.post("manage_paragraph.php",{action: "add_sub_para",para_id: para_id,sub_para: sub_para},function(res){
				console.log(res);
				if(res=="success"){
					$("#dup_msg").show();
					window.location.reload();
				}else{
					$("#dup_msg").hide();
					console.log('something wrong');
				}
			});
		}
	
</script>
</body>
</html>                                		                            