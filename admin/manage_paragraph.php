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
$para_title = $_POST['para_title'];
$page_url   = $_POST['page_url'];

$sql_max_id = " SELECT MAX(para_id) AS last_para_id  FROM `cca_paragraph` ";  // fetch last para_id inserted

$result = mysqli_query($mysqli,$sql_max_id);
$res_max_id = mysqli_fetch_array($result);
$last_para_id = $res_max_id['last_para_id'] +1 ;

$sql="INSERT INTO `cca_paragraph`(`para_id`,`para_head`, `para_category` , `page_url`) VALUES ('$last_para_id','$para_title',0 , '$page_url' )";
$result_insert = mysqli_query($mysqli, $sql);
echo "success";
exit();
}
if(isset($_POST['action']) && $_POST['action'] == "edit_para"  ) {

$para_title =$_POST['para_title'];
$para_id = $_POST['para_id'];
$page_url = $_POST['page_url'];
print_r($_POST);exit;
$update_sql = " UPDATE `cca_paragraph` SET para_head = '". $para_title."' , page_url = '".$page_url."' WHERE id = '".$para_id."' ";
// echo  $update_sql; exit;
$result_updt = mysqli_query($mysqli, $update_sql);
echo "success";
exit();
	}
if(isset($_POST['action']) && $_POST['action'] == "delete"  ) {
//print_r($_POST); exit;
$para_id = $_POST['id'];
$delete_sql = " DELETE FROM `cca_paragraph` WHERE id = '".$para_id."' ";
// echo $delete_sql;
//  exit;
$result_del = mysqli_query($mysqli, $delete_sql);
echo "success";
exit();
}

if(isset($_POST['action']) && $_POST['action'] == "add_sub_para"  ) {
$para_id = $_POST['para_id'];
$sub_para_title = $_POST['sub_para'];
$sql_add_subPara = " INSERT INTO `cca_paragraph`(`para_id`,`para_head`, `para_category`) VALUES ('$para_id','$sub_para_title',1) ";
$result_insert = mysqli_query($mysqli,$sql_add_subPara);
echo "success";
exit();
}
if(isset($_POST['action']) && $_POST['action'] == "sub_para_edit"  ) {
	$sub_para_id = $_POST['sub_para_id'];
	$edit_sql = " select * from `cca_paragraph` where id = ".$sub_para_id ;
	$edit_res = mysqli_query($mysqli ,$edit_sql );
	$row = mysqli_fetch_assoc($edit_res);
	$para_sql = " select * from `cca_paragraph` where para_id = '".$row['para_id']."' and para_category = 0 " ;
	$res_para = mysqli_query($mysqli,$para_sql);
	$para_row =  mysqli_fetch_assoc($res_para);
	$data['sub_para_head'] = $row['para_head'];
	$data['para_head']     = $para_row['para_head'];
	$data['para_id']       = $para_row['para_id'];
	$data['sub_para_id']   = $row['id'];
echo json_encode($data);
exit;
	}
	
	if(isset($_POST['action']) && $_POST['action'] == "update_sub_para"  ) {

$para_id = $_POST['para_id'];
$sub_para_head = $_POST['sub_para'];
$id = $_POST['edit_id'];
$update_sql = " UPDATE `cca_paragraph` SET para_head = '". $sub_para_head."' , para_id = '".$para_id."' WHERE id = '".$id."' ";
// echo  $update_sql; exit;
$result_updt = mysqli_query($mysqli, $update_sql);
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
		
		<div class="row para_content">
			
			<div class="col-sm-1">
				Sl.No
			</div>
			<div class="col-sm-5">
				Para Title
			</div>
			<div class="col-sm-3">
				Page Url
			</div>
			<div class="col-sm-2">
				Action
			</div>
			<div class="col-sm-1">
				expand
			</div>
		</div>
		<?php
		$count = 0;
		$sql = "SELECT * FROM `cca_paragraph` WHERE para_category = 0 ";
		$sel_para = mysqli_query($mysqli, $sql );
		while ( $para_row = mysqli_fetch_array($sel_para)) {
		$count++;
		//print_r($para_row);
		?>
		<div class="row para_content_row">
			<div class="col-sm-1"> <?php echo $count ?></div>
			<div class="col-sm-5"> <?php echo $para_row['para_head'] ?> </div>
			<div class="col-sm-3"> <?php echo $para_row['page_url'] ?>  </div>
			<div class="col-sm-2">
				
				<a href="#editParaModal_<?php echo $para_row['id']; ?>" class="edit" data-toggle="modal" style="color:#bf4516;" ><i class="material-icons" data-toggle="tooltip" title="Edit" >&#xE254;</i></a>
				<a href="#deleteParaModal_<?php echo $para_row['id']; ?>" class="delete"  data-toggle="modal" style="color:#a5161d;"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
				<!--- Edit para model --->
				
				<div id="editParaModal_<?php echo $para_row['id']; ?>" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<form id="addform"  action="" method="post" name="addform">
								<div class="modal-header">
									<h4 class="modal-title" style="color: black;">Edit Para</h4>
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								</div>
								<div class="modal-body">
									<div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>
									
									<div class="form-group">
										<label style="color:black" >Para Title</label>
										<input type="text" class="form-control"  name="para_title" id="para_title_<?php echo $para_row['id']; ?>" value=" <?php echo $para_row['para_head'] ?>"  required >
									</div>
									<div class="form-group">
										<label style="color:black" >Page Url</label>
										<input type="text" class="form-control"  name="para_url" id="para_url_<?php echo $para_row['id']; ?>" value=" <?php echo $para_row['page_url'] ?>"  required >
									</div>
									
									
								</div>
								<div class="modal-footer">
									<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
									<input type="button" class="btn btn-info" id="<?php echo $para_row['id']; ?>" value="Update" onclick="edit_para(this.id)" />
								</div>
							</form>
						</div>
					</div>
				</div>
				<!--- end edit para model --->
				<!-- Delete Modal HTML -->
				<div id="deleteParaModal_<?php echo $para_row['id']; ?>" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<form>
								<div class="modal-header">
									<h4 class="modal-title" style="color:black;" >Delete Para</h4>
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								</div>
								<div class="modal-body">
									<p>Are you sure you want to delete these Records?</p>
									<p class="text-warning"><small>This action cannot be undone.</small></p>
								</div>
								<div class="modal-footer">
									<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
									<input type="hidden" value="<?php echo $res['id']; ?>" class="recordid" />
									<input type="button" class="btn btn-danger btn-dlt" value="Delete" id="<?php echo $para_row['id']; ?>" onclick="delete_record(this.id)" />
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-1">
				<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#sub_para_content<?php echo $para_row['para_id'] ?>" aria-expanded="true" aria-controls="collapseOne">
				<i class="fa fa-level-down fa-5" aria-hidden="true" style="color:#fff"></i>
				</button>
			</div>
		</div>
		
		
		<div class="row" >
			<div class="collapse multi-collapse" id="sub_para_content<?php echo $para_row['para_id'] ?>" >
				<?php
				$sub_para_sql = " SELECT * FROM `cca_paragraph` WHERE para_category = 1  AND para_id = '".$para_row['para_id']."' ";
					$cnt1 = 0.1;
				$sel_sub_para = mysqli_query( $mysqli, $sub_para_sql);
				while ($sub_para_row = mysqli_fetch_array($sel_sub_para) ) {
				
		      $cnt  =  $count + $cnt1;
				  $cnt1 = $cnt1+0.1;
				//print_r($sub_para_row);
				?>
				  		<div class="sub_para_item" >
				    <div class="col-sm-1">
				      <?php echo $cnt ?>
				    </div>
				    <div class="col-sm-5">
				      <?php echo $sub_para_row['para_head'] ; ?>
				    </div>
				    <div class="col-sm-3">
				    	 <?php echo $sub_para_row['page_url'] ?> 
				    </div>
				    <div class="col-sm-2">
				    	       <a href="#editSubParaModal_<?php echo $sub_para_row['id']; ?>" class="edit" id = "<?php echo $sub_para_row['id']; ?>" data-toggle="modal" style="color:#bf4516;" ><i class="material-icons" data-toggle="tooltip" title="Edit" >&#xE254;</i></a>

				    	       <a href="#deleteParaModal_<?php echo $sub_para_row['id']; ?>" class="delete"  data-toggle="modal" style="color:#a5161d;"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
				    	       
				    	        <!-- Delete Modal HTML -->
				    									<div id="deleteParaModal_<?php echo $sub_para_row['id']; ?>" class="modal fade">
				    										<div class="modal-dialog">
				    											<div class="modal-content">
				    												<form>
				    													<div class="modal-header">						
				    														<h4 class="modal-title" style="color:black;" >Delete Para</h4>
				    														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				    													</div>
				    													<div class="modal-body">					
				    														<p>Are you sure you want to delete these Records?</p>
				    														<p class="text-warning"><small>This action cannot be undone.</small></p>
				    													</div>
				    													<div class="modal-footer">
				    														<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
				    														<input type="hidden" value="<?php echo $sub_para_row['id']; ?>" class="recordid" />
				    														<input type="button" class="btn btn-danger btn-dlt" value="Delete" id="<?php echo $sub_para_row['id']; ?>" onclick="delete_record(this.id)" />
				    													</div>
				    												</form>
				    											</div>
				    										</div>
				    									</div>
       
				    </div>
				    <div class="col-sm-1">
				     
				    </div>
				   </div>
				 <div style="clear:both"></div>
				<?php
				}
				?>
			</div>
		</div>
		
		
		<?php
		}
		?>
		
		
		
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
					<div class="form-group">
						<label>Page Url</label>
						<input type="text" class="form-control"  name="page_url" id="page_url"  required >
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
					<input type="hidden" name="sub_para_id" id="sub_para_id" value="" />
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<input type="button" class="btn btn-info" id="Save" value="Save" onclick="save_sub_para()" />
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
console.log(id);
		var $ele = $("#deleteParaModal_"+id).parent().parent();
		$.post("manage_paragraph.php",{id: id,action: 'delete'},function(res){
			console.log(res);
			if(res=="success"){
				$("#deleteParaModal_"+id).hide();
				//$("#successmsgid").show();
				$(".modal-backdrop").hide();
				$ele.fadeOut().remove();
			}
		});
	}
	$(".close").click(function(){
		$(".alert-success").hide();
	});
	function edit_para(id){
         var para_title=$("#para_title_"+id).val();
         var page_url=$("#page_url"+id).val();
         console.log(page_url);
         
         $.post("manage_paragraph.php",{action: "edit_para",para_title: para_title,para_id: id,page_url:page_url},function(res){
				console.log(res);
				if(res=="success"){
					
					window.location.reload();
				}else{
					$("#dup_msg").hide();
					console.log('something wrong');
				}
			});
	}
	function save_para(){
		
		var para_title=$("#para_title").val();
		var page_url=$("#page_url").val();

		console.log(para_title);
		
			$.post("manage_paragraph.php",{action: "add_para",para_title: para_title,page_url: page_url},function(res){
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
		
		var para_id = $("#para_id").val();
		
		var sub_para=$("#sub_para").val();
		var edit_id = $("#sub_para_id").val();
		if(edit_id != ''){
			action = "update_sub_para";
		}
		else{
			action = "add_sub_para";
		}
		console.log(action);
		
			$.post("manage_paragraph.php",{action: action,para_id: para_id,sub_para: sub_para,edit_id:edit_id},function(res){
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
		$(document).on('click','.edit',function(){
var id = $(this).attr("id");
//console.log(id);
$.ajax({
url:"manage_paragraph.php",
method:"POST",
data:{action:"sub_para_edit",sub_para_id:id},
dataType:"json",

success:function(data){
console.log(data);
$("#para_id").val(data.para_id);
$("#sub_para").val(data.sub_para_head);
$('#sub_para_id').val(data.sub_para_id);
$('#Save').val('Update');
$('#addSubPara').modal('show');
}
});
});
	
</script>
</body>
</html>