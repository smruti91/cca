<?php
 
  include("../common_functions.php");
  include_once("../config.php");
    session_start();
   $userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
   
  $result_user = mysqli_query($mysqli, "SELECT Dept_ID from `cca_users` where ID='".$userid."' "); 
  $resuser = mysqli_fetch_array($result_user);
  
   $deptid_fa= $resuser ['Dept_ID'];
   if($userid == -1)
    {
      header('location:../index.php');
      exit;
    }
    
    if(isset($_POST['action']) && $_POST['action']=='accept' )
    {
    	$id = $_POST['user_id'];
        if($id !='')
        {
        	$sql = "UPDATE cca_tour_details 
        	        SET 
                    status = 'accept'
                    WHERE ID = ".$id;
                    //echo $sql;
            $query = mysqli_query($mysqli,$sql);
            if($query)
            {
            	 echo "success";
    	         exit;
            }
            else
            {
            	echo("Error description: " . $mysqli -> error);exit;
            }
           
        }
    	
    }

    if(isset($_POST['action']) && $_POST['action'] == 'reject')
    {
         $user_id = $_POST['user_id'];
         $dept_id = $_POST['dept_id'];
         $tour_id = $_POST['tour_id'];
         $reject_msg = $_POST['reject_msg'];

         $status_sql = "UPDATE cca_tour_details 
                          SET 
                            status = 'reject'
                          WHERE ID = ".$tour_id;
                          //echo $status_sql ; exit;
        $status_query = mysqli_query($mysqli,$status_sql);

        $reject_sql = " INSERT INTO cca_tour_reject (user_id,tour_id,dept_id,reject_msg) VALUES ('$user_id','$tour_id','$dept_id','$reject_msg') ";
        //echo $reject_sql;exit;
        $reject_query = mysqli_query($mysqli,$reject_sql);
        if($reject_query)
            {
               echo "success";
               exit;
            }
            else
            {
              echo("Error description: " . $mysqli -> error);exit;
            }

    }

     include "header.php";
 ?>
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
            <h1>Review Tour</h1>
            <hr>
                <div class="row" style="padding-bottom:50px;">
                    <table class="table table-striped table-bordered" id="tableid">
                    <thead style="background-color: #185a9d;color: white;">
                      <tr>
                        <th>Sl.No</th>
                        <th>Tour For The Month</th>
                        <th>Plan Name</th>
                        <th>Tour Catagory</th>
                        <th>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                     <?php
                       
                        $sql = "SELECT td.ID,td.dept_id,td.tour_for_the_month,td.plan_id,td.t_category,td.year_of_account,td.act_dt_commencent,td.act_dt_completion,td.dt_commencent,td.dt_completion,td.purpose,td.distance,td.remarks,td.status,tc.catagory_name FROM cca_tour_details td ,cca_tour_catagory tc WHERE td.t_category = tc.id AND ( td.status = 'pending' AND td.dept_id= '".$deptid_fa."' )";
                       // echo $sql;
                        $result = mysqli_query($mysqli,$sql);

                        if(mysqli_num_rows($result)> 0)
                        {
                        	$count=0;

                        	while ($row = mysqli_fetch_array($result)) {
                        		$count++;
                                 // print_r($row);
                        		?>
                                  <tr>
                                  	<td> <?php echo $count; ?></td>
                                  	<td><?php echo $row['tour_for_the_month'] ?></td>
                                  	<td><?php echo $row['plan_id'] ?></td>
                                  	<td><?php echo $row['catagory_name'] ?></td>
                                    <td> 
                                    	
                                    	<a href="#tourDetailsModal_<?php echo $row['ID']; ?>" type="button" class="view btn btn-primary" data-toggle="modal" id="<?php  ?>" >view</a>
                                    	<!-- Delete Modal HTML -->
                                            <div id="tourDetailsModal_<?php echo $row['ID']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog modal-lg modal-dialog-centered " role="document">
                                                <div class="modal-content">
                                                  <form>
                                                    <div class="modal-header">            
                                                      <h4 class="modal-title">Tour Details</h4>
                                                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    </div>
                                                    <div class="modal-body">          
                                                      <div class="container-fluid">
                                                      	<div class="tour-data-modal">
                                                      	 <div class="row">
                                                      	       
                                                      	  <label class="col-md-4 text-left">Tour For The Month </label>
                                                      	  
                                                      	  <div class="col-md-4 ml-auto"><?php echo $row['tour_for_the_month'] ?></div>
                                                      	</div><br>
                                                      	<div class="row">
                                                      	      
                                                      	  <label class="col-md-4 text-left">Plan Name :</label>
                                                      	       <div class="col-md-4 ml-auto"><?php echo $row['plan_id'] ?></div>
                                                      	</div><br>
                                                      	<div class="row">
                                                          <label class="col-md-4 text-left">Catagory Name :</label>
                                                      	      
                                                      	       <div class="col-md-4 ml-auto"><?php echo $row['catagory_name'] ?></div>
                                                      	</div><br>
                                                      	<div class="row">
                                                            <label class="col-md-4 text-left">Year of Accounts :</label>
                                                      	      
                                                      	       <div class="col-md-4 ml-auto"><?php echo $row['year_of_account'] ?></div>
                                                      	</div><br>
                                                      	<div class="row">
                                                      	<label class="col-md-4 text-left">Date of Commencent :</label>
                                                      	       
                                                      	       <div class="col-md-4 ml-auto"><?php echo $row['act_dt_commencent'] ?></div>
                                                      	</div><br>
                                                      	<div class="row">
                                                      	<label class="col-md-4 text-left">Date of Completion :</label>
                                                      	      
                                                      	       <div class="col-md-4 ml-auto"><?php echo $row['act_dt_completion'] ?></div>
                                                      	</div><br>
                                                      	<div class="row">
                                                      		<label class="col-md-4 text-left">Tour Purpose :</label>
                                                      	      
                                                      	       <div class="col-md-4 ml-auto"><?php echo $row['purpose'] ?></div>
                                                      	</div><br>
                                                      	<div class="row">
                                                      		<label class="col-md-4 text-left">Distance :</label>
                                                      	       
                                                      	       <div class="col-md-4 ml-auto"><?php echo $row['distance'] ?></div>
                                                      	</div><br>
                                                      	<div class="row">
                                                      		<label class="col-md-4 text-left">Remark :</label>
                                                      	       
                                                      	       <div class="col-md-4 ml-auto"><?php echo $row['remarks'] ?></div>
                                                      	</div>
                                                      	
                                                      </div>
                                                      </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                      <input type="button" class="btn btn-default" id="<?php echo $row['ID']; ?>" onclick="accept_tour(this.id)" value="Accept">
                                                      <input type="hidden" value="<?php echo $row['ID']; ?>" class="recordid" />
                                                      <input type="button" class="btn btn-danger btn-dlt" value="Reject" id="<?php echo $row['ID']; ?>" onclick="reject_record(this.id)" />
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
            <!-- Reject Modal HTML -->
                           <div id="rejectModal" class="modal fade">
                             <div class="modal-dialog">
                               <div class="modal-content">
                                 <form>
                                   <div class="modal-header">            
                                     <h4 class="modal-title">Reject Tour</h4>
                                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                   </div>
                                   <div class="modal-body">          
                                     <textarea class="form-control is-invalid" id="reject_msg" placeholder="Commencents for Rejection"></textarea>
                                   </div>
                                   <div class="modal-footer">
                                     <!-- <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">-->
                                     <input type="hidden" value="" id="reject_id" class="recordid" /> 
                                     <input type="button" class="btn btn-danger btn-dlt" value="Submit" id="" onclick="reject_tour()" />
                                   </div>
                                 </form>
                               </div>
                             </div>
                           </div>
          </div>
    </div>
            <!-- /.container-fluid -->
        </div>
    </div>
        <div class="clear:both;"></div>
    </div>
    <?php include "footer.php"; ?>

    <script type="text/javascript">
    	$(document).ready(function(){
            $('#tableid').DataTable();
    	});

    function accept_tour(id)
    	{
    		
    		//var $ele = $("#tourDetailsModal_"+id).parent().parent();
    		$.post('tour_review.php',{action:'accept',user_id:id },
    		function(res){
    			console.log(res);
    		  if(res=='success'){
    		     $("#tourDetailsModal_"+id).hide();
    		     $(".modal-backdrop").hide();
    		     // $ele.fadeOut().remove();
             window.location.reload();
    		      
    		  }
    		});
    	}
      function reject_record(id)
      {
        $('#rejectModal').modal('show');
        $('#reject_id').val(id);
        $("#tourDetailsModal_"+id).hide();
        //console.log(id);
      }
      function reject_tour()
      {
        var tour_id = $('#reject_id').val();
        var reject_msg = $('#reject_msg').val();
        var user_id = "<?php echo $userid ?>";
        var dept_id = "<?php echo $deptid_fa ?>";
        
        $.ajax({
           url: "tour_review.php",
           method: "POST",
           data: {action:"reject",tour_id:tour_id,user_id:user_id,dept_id:dept_id,reject_msg:reject_msg},
           success: function(res){
             console.log(res);
              $('#rejectModal').hide();
              $(".modal-backdrop").hide();
              window.location.reload();
           }
        });
      }
    </script>