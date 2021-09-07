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
            <h1>Tour Cancel</h1>
            <hr>
                <div class="row" style="padding-bottom:50px;">
                    <table class="table table-striped table-bordered" id="tableid">
                    <thead style="background-color: #185a9d;color: white;">
                      <tr>
                        <th>Sl.No</th>
                        <th>Tour For The Month</th>
                        <th>Plan Name</th>
                        <th>Tour Catagory</th>
                        <th>Cancel Comments</th>
                        <th>Report</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                     <?php
                       
                        $sql = "SELECT td.plan_id,td.dept_id,td.tour_for_the_month,tc.catagory_name,td.year_of_account,td.act_dt_commencent,td.act_dt_completion,td.purpose,td.distance,td.remarks,td.cancel_comment,tc.catagory_name FROM cca_tour_details td ,cca_tour_catagory tc WHERE td.t_category = tc.id AND td.status = 'cancel' ";
                       // echo $sql;
                        $result = mysqli_query($mysqli,$sql);
                        
                        if(mysqli_num_rows($result)> 0)
                        {
                        	$count=0;

                        	while ($row = mysqli_fetch_array($result)) {
                        		$count++;
                               
                        		?>
                                  <tr>
                                  	<td> <?php echo $count; ?></td>
                                  	<td><?php echo $row['tour_for_the_month'] ?></td>
                                  	<td><?php echo $row['plan_id'] ?></td>
                                  	<td><?php echo $row['catagory_name'] ?></td>
                                    <td><?php echo $row['cancel_comment'] ?></td>
                                    <td> </td>
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
        <div class="clear:both;"></div>
    </div>
    <?php include "footer.php"; ?>

    <script type="text/javascript">
    	$(document).ready(function(){
            $('#tableid').DataTable();
    	});

    
    </script>