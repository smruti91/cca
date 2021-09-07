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
            <h1>Tour Report</h1>
            <hr>
                <div class="row" style="padding-bottom:50px;">
                    <table class="table table-striped table-bordered" id="tableid">
                    <thead style="background-color: #185a9d;color: white;">
                      <tr>
                        <th>Sl.No</th>
                        <th>Tour For The Month</th>
                        <th>Plan Name</th>
                        <th>Tour Catagory</th>
                        <th>Final Remark</th>
                        <th>Report</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                     <?php
                       
                        // $sql = "SELECT td.ID,td.dept_id,td.tour_for_the_month,td.plan_id,td.t_category,td.year_of_account,td.act_dt_commencent,td.act_dt_completion,td.purpose,td.distance,td.remarks,tc.catagory_name FROM cca_tour_details td ,cca_tour_catagory tc WHERE td.t_category = tc.id AND ( td.status = 'accept' AND td.dept_id= '".$deptid_fa."' )";
                        $sql = "SELECT d.plan_id,d.tour_for_the_month,d.catagory_name,ctd.final_remark,ctd.document_name FROM ( SELECT td.plan_id,td.dept_id,td.tour_for_the_month,td.t_category,td.year_of_account,td.act_dt_commencent,td.act_dt_completion,td.purpose,td.distance,td.remarks,td.audit_report,tc.catagory_name FROM cca_tour_details td ,cca_tour_catagory tc WHERE td.t_category = tc.id AND ( td.status = 'accept' AND td.audit_report != '' AND td.dept_id= '".$deptid_fa."' ) ) AS d , cca_tour_document ctd WHERE d.audit_report = ctd.id";
                        //echo $sql;
                        $result = mysqli_query($mysqli,$sql);
                        
                        
                        if(mysqli_num_rows($result)> 0)
                        {
                        	$count=0;

                        	while ($row = mysqli_fetch_array($result)) {
                        		$count++;
                                  //print_r($row);
                        		?>
                                  <tr>
                                  	<td> <?php echo $count; ?></td>
                                  	<td><?php echo $row['tour_for_the_month'] ?></td>
                                  	<td><?php echo $row['plan_id'] ?></td>
                                  	<td><?php echo $row['catagory_name'] ?></td>
                                    <td><?php echo $row['final_remark'] ?></td>
                                    <td>
                                      
                                        <a href="<?php echo BASE_URL.'Auditor/'.$row['document_name']?>" target="_blank"><i class="material-icons" data-toggle="tooltip" title="picture_as_pdf" style="font-size: 3rem; color:#f25149;" >picture_as_pdf</i></a>
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

    
    </script>