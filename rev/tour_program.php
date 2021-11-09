<?php 
include ("header.php");
include("../common_functions.php");
include_once("../config.php");
 //session_start();
 
$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
 
 if($userid == -1)
  {
    header('location:../index.php');
    exit;
  }
  $sql = "SELECT * FROM cca_users WHERE ID = ".$userid;
  $sql_query = mysqli_query($mysqli, $sql);
  $res_query = mysqli_fetch_array($sql_query);
  
//echo 'select * from cca_plan where dept_id = "'.$res_query['Dept_ID'].'"';
?>
<style type="text/css">

.tour_table{
        background: #fff;
        padding: 20px 25px;
        margin: 30px 0;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
        /* overflow-y: scroll; */
  }
  /* .tour_table{
  overflow-y:scroll;
  height:600px;
} */
.content{
  overflow-y:scroll;
}
 
#alert_msg{
    position:absolute;
    z-index:1400;
    top:2%;
    right:4%;
    margin:40px auto;
    text-align:center;
    display:none;
}
div.main{
height:100%;
}
</style>

<div id="wrapper">
      <?php include "leftpanel.php";?>
    
    
       <div id="page-wrapper" style="padding-bottom:60px">
       <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center">   
         
             <!-- <div class="row table-row"> -->
                   <div style="display:none" id="alert_msg" >
                    
                   </div>
                   <div class="col-md-6 ">
                       <h3 class="t-program">Manage <b>Tour Program</b></h3>
                       
                   </div>
                   <div class="col-md-6 text-right">
                      <a href="#addEmployeeModal" class="btn btn-success mt-2" data-toggle="modal"><i class="fa fa-plus" aria-hidden="true"></i><span>New Tour Program </span></a>
                   </div>
                  
             <!--  </div> -->
               
              
              <div class="row">
                  
                         <div class="tour_table">
                          <table class="table table-striped table-bordered" id="tableid">
                          <thead>
                            <tr>
                              <th style="width: 9%;">Sl.No</th>
                              <th style="width: 9%;">Tour For the month</th>
                              <th style="width: 9%;">Plan Name</th>
                              <th style="width: 9%;">Tour Catagory</th>
                              <th style="width: 9%;">Employee Name</th>
                              <th style="width: 9%;">Date of Commencement </th>
                              <th style="width: 9%;">Date of Completion </th>
                              <th style="width: 9%;">Purpose </th>
                              <th style="width: 9%;">Distance In KM </th>
                              <th style="width: 9%;">Remark  </th>
                              
                              <th style="width: 9%;">Action </th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                               
                               $result = mysqli_query($mysqli, "SELECT * FROM cca_tour_details ORDER BY status DESC "); 
                               if(mysqli_num_rows($result)>0)
                               {
                                 $count=0;
                                 while($res = mysqli_fetch_assoc($result) )
                                 {
                                     //print_r($res);exit;
                                     $count++;
                                     ?>
                                      <tr>
                                         <td><?php echo $count; ?></td>
                                         <td><?php echo date("d-m-Y",strtotime($res['tour_for_the_month']));?></td>
                                         <td><?php echo $res['plan_name'];?></td>
                                         <td><?php
                                          $cat_sql = "select * from cca_tour_catagory where id = ".$res['t_category'];
                                              $cat_res = mysqli_query($mysqli,$cat_sql);
                                              $row = mysqli_fetch_array($cat_res);
                                              //print_r($row);
                                               if($row['ID'])
                                                 {
                                                   echo $row['catagory_name'];

                                                 }
                                          ?></td>
                                         <td><?php echo $res_query['Name'];?></td>
                                         <td><?php echo date("d-m-Y",strtotime($res['dt_commencent']));?></td>
                                         <td><?php echo date("d-m-Y",strtotime($res['dt_completion']));?></td>
                                         <td><?php echo $res['purpose'];?></td>
                                         <td><?php echo $res['distance'];?></td>
                                         <td><?php echo $res['remarks']; ?></td>
                                        
                                         <td>
                                            <?php
                                              //echo "SELECT * FROM cca_tour_details WHERE status='pending' AND ID = '".$res['ID']."' ";
                                              // $sql_pending = mysqli_query($mysqli, "SELECT * FROM cca_tour_details WHERE status='pending' AND ID = '".$res['ID']."' "); 
                                              // $sql_pending_res = mysqli_fetch_assoc($sql_pending);
                                              // print_r($sql_pending_res);

                                              switch ($res['status']) {
                                                case 'pending':
                                                  ?>
                                                    <a href="#sendModal_<?php echo $res['ID']; ?>" data-toggle="modal" class="btn btn-primary" style="color:white;" disabled>Pending At FA</i></a>
                                                  <?php
                                                  break;
                                                 case 'accept':
                                                   include "tour_review_template.php";
                                                  break;
                                                
                                                default:
                                                  ?>
                                                     <a href="#editEmployeeModal_<?php echo $res['ID']; ?>" class="edit" data-toggle="modal" id="<?php echo $res['ID']; ?>" ><i class="material-icons" data-toggle="tooltip" title="Edit" >&#xE254;</i></a>

                                                   <a href="#deleteEmployeeModal_<?php echo $res['ID']; ?>" class="delete"  id="delete_<?php echo $res['ID']; ?>" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>

                                                   <a href="#sendModal_<?php echo $res['ID']; ?>" data-toggle="modal" class="btn btn-success" style="color:white;">Submit to FA</i></a>
                                                  <?
                                                  break;
                                              }
                                              // 

                                             ?>
                                            
                                          
                                          

                                   <!-- Delete Modal HTML -->
                                                  <div id="deleteEmployeeModal_<?php echo $res['ID']; ?>" class="modal fade">
                                                    <div class="modal-dialog">
                                                      <div class="modal-content">
                                                        <form>
                                                          <div class="modal-header">            
                                                            <h4 class="modal-title">Delete Tour</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                          </div>
                                                          <div class="modal-body">          
                                                            <p>Are you sure you want to delete these Tour Record?</p>
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
                                                  <!-- Send Modal Modal HTML -->
                                                   <div id="sendModal_<?php echo $res['ID']; ?>" class="modal fade">
                                                     <div class="modal-dialog">
                                                       <div class="modal-content">
                                                         <form>
                                                           <div class="modal-header">            
                                                             <h4 class="modal-title">Send To FA</h4>
                                                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                           </div>
                                                           <div class="modal-body">          
                                                             <p>Are you sure to Send these Tour Record?</p>
                                                             <p class="text-warning"><small>This action cannot be undone.</small></p>
                                                           </div>
                                                           <div class="modal-footer">
                                                             <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                                                             <input type="hidden" value="<?php echo $res['ID']; ?>" class="recordid" />
                                                             <input type="button" class="btn btn-primary" value="Send" id="<?php echo $res['ID']; ?>" onclick="send_record(this.id)" />
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
  </div>

 
</div>
<!-- Add Modal HTML -->
<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="addform" action="" method="post" name="addform">
					<div class="modal-header text-center">						
						<h4 class="modal-title text-center ">Add Tour Program</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
					<!-- <div id="dup_msg" style="display:none;"><font color="red">Duplicate Record exists!</font></div>		 -->			
						<div class="form-group">
							<label>Tour For The Month</label>
							<input type="text" class="form-control"  name="date" id="t_month" placeholder="DD/MM/YYY" autocomplete="off"  required >
						</div>
						
					
            <div class="form-group">
							<label>Plan Name</label>
              
							<select name="planName" id="planName" class="form-control">
                <option selected> Select Plan Name</option>
                <?php 
                  
                $plan_sql = "SELECT p.org_id,p.plan_id,p.actual_audit_start_date,p.actual_audit_end_date,d.S_descr,i.ddo_code,i.institution_name FROM `cca_manageplan` p , `cca_departments` d , `cca_institutions` i WHERE p.dept_id=d.ID AND p.org_id = i.id AND p.dept_id= '".$res_query['Dept_ID']."'  ";

                 $plan_name = mysqli_query($mysqli,$plan_sql);
                 
                 while($res_plan_name = mysqli_fetch_assoc($plan_name)){
                  
                  ?>
                     
                     <option value="<?php echo $res_plan_name['org_id']; ?>" ><?php  echo $res_plan_name['ddo_code'] ." - ". $res_plan_name['institution_name'] ?></option>
                  <?php
                 }
                ?>
                
              </select>
            </div>
            <div class="form-group">
							<label>Tour Catagory</label>
							<select name="tour_catagory" id="tour_catagory" class="form-control">
                <option selected>Catagory</option>
                <?php
                  $cat_name = mysqli_query($mysqli,'select * from cca_tour_catagory');
                  while($res_cat_name = mysqli_fetch_array($cat_name) ){
                   ?>
                     <option value="<?php echo $res_cat_name['ID']; ?>"><?php echo $res_cat_name['catagory_name']; ?></option>
                   <?php
                  }

                 ?>
                
              </select>
            </div>
            <div class="form-group">
                <label>Year of Accounts:</label>
                
                 <!-- <select class="form-control" id="financialYear" class="accountsYear" ></select> -->
                 <input type="text" class="form-control"  id="financialYear" placeholder="Enter Financial Year" autocomplete="off"  required >
                
              </div>
            	<div class="form-group">
							<label>Actual Date Of Commencement of Audit</label>
							<input type="text" class="form-control"  name="date" id="act_dt_commencement" placeholder="DD/MM/YYY" autocomplete="off"  required >
            </div>
            <div class="form-group">
							<label>Actual Date Of Completion of Audit</label>
							<input type="text" class="form-control"  name="date" id="act_dt_completion" placeholder="DD/MM/YYY" autocomplete="off" required >
            </div>
            <div class="form-group">
							<label> Date Of Commencement of Tour</label>
							<input type="text" class="form-control"  name="date" id="dt_commencement" placeholder="DD/MM/YYY" autocomplete="off" required >
            </div>
            <div class="form-group">
							<label> Date Of Completion of Tour</label>
							<input type="text" class="form-control"  name="date" id="dt_completion" placeholder="DD/MM/YYY" autocomplete="off" required >
            </div>
            <div class="form-group">
							<label>Purpose</label>
              <textarea class="form-control" id="purpose" rows="3"></textarea>
            </div>
            <div class="form-group">
							<label>Destance In KM</label>
							<input type="text" class="form-control" name="distance" id="distance"  required >
            </div>		
            <div class="form-group">
							<label>Remarks</label>
              <textarea class="form-control" id="remark" rows="3"></textarea>
            </div>
             <input type="hidden" name="planId"  id="planId" value="" >
             <input type="hidden" name="tour_id" id="tour_id">
             <input type="hidden" name="dept_id" id="dept_id" value="<?php echo $res_query['Dept_ID']; ?>" >
             <input type="hidden" name="emp_id"  id="emp_id" value="<?php echo $res_query['ID']; ?>" >

					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="button" class="btn btn-info" id="save" value="Save" onclick="add_tour()" />
					</div>
				</form>
			</div>
		</div>
  </div>

  <!-- Edit Modal HTML -->


  
<?php include "footer.php"; ?>
<link rel="stylesheet" href="../css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="../js/bootstrap-datepicker.min.js"></script>

<script>
   $(document).ready(function(){
     $('#tableid').DataTable();
	    var date_input=$('input[name="date"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'dd-mm-yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
      //showMessage();
      
  });
  var yearsLength = 30;
var currentYear = new Date().getFullYear();
for(var i = 0; i < 30; i++){
  var next = currentYear+1;
  var year = currentYear + '-' + next.toString();
  $('#financialYear').append(new Option(year, year));
  currentYear--;
}

 $(document).on('change','#planName',function(){
  var org_id = $('#planName option:selected').val();
  //alert(org_id);
  //var plan_name = $('#planName option:selected').text();
  //alert(plan_name);
  $.ajax({
    url:"fetch_tour_data.php",
    method:"POST",
    data:{action:"planName",org_id:org_id},
    dataType:"json",
    success:function(data){
      //console.log(data);
      $('#act_dt_commencement').val(data.act_dt_commencent);
      $('#act_dt_completion').val(data.act_dt_completion);
      $('#planId').val(data.planId);
      console.log(data);
    }
  })
 });

$(document).on('click','.edit',function(){
   var id = $(this).attr("id");
   //console.log(id);
   $.ajax({
     url:"fetch_tour_data.php",
     method:"POST",
     data:{action:"edit",user_id:id},
     dataType:"json",
     
     success:function(data){
      console.log(data);
      $('#t_month').val(data.tour_for_the_month);
      $('#planName').val(data.plan_name);
      $('#tour_catagory').val(data.purpose_category_id);
      $('#financialYear').val(data.years_of_ac);
      $('#act_dt_commencement').val(data.act_dt_commencent);
      $('#act_dt_completion').val(data.act_dt_completion);
      $('#dt_commencement').val(data.dt_commencent);
      $('#dt_completion').val(data.dt_completion);
      $('#purpose').val(data.purpose);
      $('#distance').val(data.distance);
      $('#remark').val(data.remarks);
      $('#tour_id').val(data.id);
      $('#save').val('Update');
      $('#addEmployeeModal').modal('show');

     }
   });
});

function delete_record(id){
  var $ele = $("#sendModal_"+id).parent().parent();
  $.post('fetch_tour_data.php',{action:'delete',user_id:id },
  function(res){
    console.log(res);
    if(res=='success'){
       $("#deleteEmployeeModal_"+id).hide();
       $(".modal-backdrop").hide();
       $ele.fadeOut().remove();
       sessionStorage.setItem("type", "Error");
       sessionStorage.setItem("message", "Record Deleted Successfully");

        window.location.reload();
    }
  } );
}

function send_record(id)
{
    var $ele = $("#deleteEmployeeModal_"+id).parent().parent();
    $.ajax({
      url:"fetch_tour_data.php",
      method:"POST",
      data:{action:"send",user_id:id},
      success:function(data){
         console.log(data);
         if(data == 'Success')
         {
             sessionStorage.reloadAfterPageLoad = true;
             sessionStorage.message = "Sussefully Send To FA";
             sessionStorage.type = "Success";
             window.location.href = 'tour_program.php';
         }
         else{
             sessionStorage.reloadAfterPageLoad = true;
             sessionStorage.message = data;
             sessionStorage.type = "Error";
         }
      }
    });

}

function add_tour(){
  var tour_for_month      = $('#t_month').val();
  var plan_name           = $('#planName option:selected').html();
  var org_id              = $('#planName option:selected').val();
  var tour_catagory       = $('#tour_catagory option:selected').val();
  var years_of_acc        = $('#financialYear').val();
  var act_dt_commencement = $('#act_dt_commencement').val();
  var act_dt_completion   = $('#act_dt_completion').val();
  var dt_commencement     = $('#dt_commencement').val();
  var dt_completion       = $('#dt_completion').val();
  var purpose             = $('#purpose').val();
  var distance            = $('#distance').val();
  var remark              = $('#remark').val();
  var tour_id             = $('#tour_id').val();
  var dept_id             = $('#dept_id').val();
  var emp_id              = $('#emp_id').val()
  var plan_id             = $('#planId').val();
  //alert(plan_id);
  //console.log(tour_catagory);exit;
  if(plan_name !='' && plan_name !='')
  {
     
    $.post('fetch_tour_data.php',{action:'add',tour_for_month:tour_for_month,plan_name:plan_name,tour_catagory:tour_catagory,years_of_acc:years_of_acc,act_dt_commencement:act_dt_commencement,act_dt_completion:act_dt_completion,dt_commencement:dt_commencement,dt_completion:dt_completion,purpose:purpose,distance:distance,remark:remark,tour_id:tour_id,dept_id:dept_id,emp_id:emp_id,org_id:org_id,plan_id:plan_id},
       function (res){
        console.log(res);
        var element = res.split('#');
        //alert(res.toString());
        console.log(element);
        if(element[0] == 'Success')
        {
           $('#addEmployeeModal').modal('hide');
           $(".modal-backdrop").hide();
           // $("#msg").html("<b>"+element[1]+"</b>");
           sessionStorage.reloadAfterPageLoad = true;
           sessionStorage.message = element[1];
           sessionStorage.type = "Success";
            window.location.href = 'tour_program.php';
        
        }
         else{
             sessionStorage.message = element[1];
             sessionStorage.type = "Error";
         }
       }
      );
  }
}

//upload or edit tour report


$(document).on('submit','.reportForm',function(e){
  
      e.preventDefault();
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $(".progress").show();
                        $(".progress-bar").width(percentComplete + '%');
                        $(".progress-bar").html(percentComplete+'%');
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            url: 'fetch_tour_data.php',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $(".progress-bar").width('0%');
                //$('#uploadStatus').html('<img src="images/loading.gif"/>');
            },
            error:function(){
                $('.uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
            },
            success: function(resp){
              res = resp.split("#");
              console.log(res);
                if(res[0] == 'Success'){
                  console.log("hello");
                    //$('#uploadForm')[0].reset();view_pdf
                    $('.uploadStatus').html('<p style="color:#28A74B;">File has uploaded successfully!</p>');
                    $('.view_report').attr('href',res[1]);
                    $('.uploadBtn').hide();
                    setTimeout(()=>{
                      $(".uploadStatus").hide()
                    },3000);
                    

                }else if(resp == 'err'){
                    $('.uploadStatus').html('<p style="color:#EA4335;">Please select a valid file to upload.</p>');
                }
            }
        });
});

$(document).on('submit','.cancelForm',function(e){
   e.preventDefault();

   $.ajax({

    type: 'POST',
    url: 'fetch_tour_data.php',
    data: new FormData(this),
    contentType: false,
    cache: false,
    processData:false,
    success: function(res){
      console.log(res);
      if(data == 'Success')
      {
        location.reload();
      }
      else
      {
        console.log("data");
      }
    }
   })
});

$(document).on('click','.edit_report',function(){
   var id = $(this).attr("id");
   var distance = $('#distnc').val();
   //console.log(id);
   $.ajax({
    url: "fetch_tour_data.php",
    method: "POST",
    data: {action:"edit_report",user_id:id,distance:distance},
    dataType:"json",
    success: function(data){
      console.log(data);
      console.log(123);
      $('.uploaded_report').show();
      $('.upload_file').hide();
      $('.uploadBtn').hide();
      $('.btn_submit').hide();
      $('.final_review').val(data.remark);
      $('.view_report').attr('href',data.file_path);
      $('.uploaded_report').attr('href',data.file_path);
      //$('.report_file').val(data.file_path);
      $('#myModal_'+id).modal('show');

    }
   })
   //var final_review = document.getElementsByClassName("final_review");
   
});
$(document).on('click','.remove_report',function(e){
  e.preventDefault();
  var id = $(this).attr("id");
  //alert(id);
  $.ajax({
     url:"fetch_tour_data.php",
     method:"POST",
     data:{action:"remove_report",user_id:id},
    
     success:function(data){
      //var res = $.parseJSON(data);
      //console.log(data)
       if(data == "success")
       {
          $('.uploaded_file').hide();
          $('.upload_file').show();
          $('.uploadBtn').show();

       }
        else{
          //console.log(error);
        }

     }
   });
})

function update_report(id){
   var final_review = $('.final_review').val();
   var final_distnc = $('#distnc').val();

   $.ajax({
      method: "POST",
      url: "fetch_tour_data.php",
      data:{action:"update_report",final_review:final_review,final_distnc:final_distnc,user_id:id},

      success:function(data){
        console.log(data)
        if(data == "success")
       {
         location.reload();
       }
        else{
          console.log('error');
        }

      }
   })
}


function submit_report(id)
{

    $.ajax({
     url:"fetch_tour_data.php",
     method:"POST",
     data:{action:"submit_report",user_id:id},
    
     success:function(data){
     //console.log(data);
      location.reload();
      
     }
   });
}
function save_report(id)
{
  location.reload();
  
}

</script>