<?php 
session_start();
include("../common_functions.php");
include_once("../config.php");

$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
 
 if($userid == -1)
  {
    header('location:../index.php');
    exit;
  }

?>

<?php include "header.php"; ?>
<?php
if (isset($_POST['mngplan_id'])){
	$_SESSION['mngplan_id']=$_POST['mngplan_id'];
	}
  $manageplan_id=$_SESSION['mngplan_id'];
  $manageplansql= mysqli_query($mysqli,"select plan_name,org_id,team_id from cca_manageplan m,cca_plan p where m.plan_id=p.id and m.id='".$manageplan_id."'"); 
  $res_row=mysqli_fetch_array($manageplansql);

  $orgname= find_institutionname($res_row['org_id'],$mysqli);
  $team_name=find_teamname($res_row['team_id'],$mysqli);

?>
<style>
div.main{
height:100%;
}
#alert_msg{
   /* position:absolute;*/
    z-index:1400;
    top:2%;
    right:4%;
    margin:10px auto;
    text-align:center;
    display:none;
}

</style>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center"> 
        <div style="" id="alert_msg" ></div>		
        <div class="row content">
          <div class="col-sm-12 text-center"> 
          	<div class="bckbtn" onclick="history.back(-1)"><img src="../images/backb.png" /><b>Back</b></div>
            <h1>Manage Audit Report</h1>
            <hr>
            <div style="width: 100%;background-color: #42c19f2e; padding: 12px;border: 3px solid #2daab0;">
              <div style="width:33%;float:left;">
                Plan Name: <?php echo $res_row['plan_name']; ?></br>
                Party Name: <?php echo $team_name; ?> </br>

                <?php 
                  $teamsql= mysqli_query($mysqli,"select u.id,em.leader_id,u.Name from cca_users u,cca_team_emp_role em where u.id=em.emp_id and em.team_id='".$res_row['team_id']."'"); 
                  while($team_row=mysqli_fetch_array($teamsql)){
                    echo $team_row['Name'];
                      if($team_row['id']==$team_row['leader_id']){
                          echo "<b> (Lead Auditor)</b>";
                      }else{
                        echo "<b> (Auditor)</b>";
                      }

                    echo "<br>";
                  }


                ?>
              </div>
              <div style="width:33%;float:left;">
                <b><?php echo $orgname; ?></b> -  General Audit
              </div>

               <div style="clear:both">
                
              </div>

            </div>
            <div class="calheader" style="padding-bottom: 50px; *background: url(../images/auditimg.jpg);background-position: center; /* Center the image */
  background-repeat: no-repeat; /* Do not repeat the image */
  background-size: cover;">
              <fieldset>
                <h4>List of Paras</h5>
                <table border="1" class="table" style="width: 60%; border: 2px solid #2daab0;">
                  <thead>
                    <tr>
                      <th>para Heading</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                      $result= mysqli_query($mysqli,'SELECT * from `cca_paragraph` where para_category=0 ');
                     while($res_row=mysqli_fetch_array($result)){
                    ?>
                    <tr>
                      <td style="text-align:left;background-color: #e6e6e5;"><b><?php echo $res_row['para_head']; ?></b></td>
                      <td style="background-color: #e6e6e5;">&nbsp;</td>
                      <?php
                        if($res_row['id']!='2' && $res_row['id']!='4'){
                        	$paraid=$res_row['id'];
                        	//echo "SHOW TABLES LIKE 'cca_para".$res_row['id']."'";
                        	if ($result_exists = $mysqli->query("SHOW TABLES LIKE 'cca_para".$res_row['id']."'")) {
									    if($result_exists->num_rows == 1) {
									       $sql_para1  = mysqli_query($mysqli,"SELECT * FROM `"."cca_para".$paraid."` WHERE para_id = '".$paraid."'  AND mngplan_id = '".$manageplan_id."' and add_by='".$_SESSION['userid']."' ");
									        // echo "SELECT * FROM `"."cca_para".$paraid."` WHERE para_id = '".$paraid."'  AND mngplan_id = '".$manageplan_id."' ";
                        	 				$res_rows=mysqli_fetch_array($sql_para1); 
									   
									// else {
									//     echo "Table does not exist";
									// }
									                        	 

                        	// print_r($res_rows);
                        	 if(!empty($res_rows)){
                        	 	?>
                        	 	<td style="background-color: #e6e6e5;"><a href="javascript: ccadatapost('<?php echo $res_row['page_url'];?>',{para_id:'<?php echo $res_row['id'];?>'});" class="btn btn-primary">Edit</a></td>

                        	 <?php }else{
                        	 	?>
                        	 		<td style="background-color: #e6e6e5;"><a href="javascript: ccadatapost('<?php echo $res_row['page_url'];?>',{para_id:'<?php echo $res_row['id'];?>'});" class="btn btn-warning">Add</a></td>
                        	 	<?php
                        	 }
                        	  }else{ ?>
								<td style="background-color: #e6e6e5;"><a href="javascript: ccadatapost('<?php echo $res_row['page_url'];?>',{para_id:'<?php echo $res_row['id'];?>'});" class="btn btn-warning">Add</a></td>
							<?php }
							}
                      ?>
                      <?php 
                        }else{?>
                            <td style="background-color: #e6e6e5;">&nbsp;</td>
                       <?php }
                      ?>
                    </tr>
                    <?php
                       $result_subhead= mysqli_query($mysqli,'SELECT * from `cca_paragraph` where para_category=1 and para_id="'.$res_row['para_id'].'"'); 
                       while($res_rowsubhead=mysqli_fetch_array($result_subhead)){ ?>

                          <tr>
                            <td style="text-align:left;"><?php echo $res_rowsubhead['para_head']; ?></td>
                            <td> &nbsp;</td>
                            <?php
							                 if($res_rowsubhead['id'] == 9){
                                   $table = 'cca_para_2a';
                                }
                                if($res_rowsubhead['id'] == 10){
                                   $table = 'cca_para_2b';
                                }
                                if($res_rowsubhead['id'] == 13){
                                   $table = 'cca_para_3c';
                                }
                                if($res_rowsubhead['id'] == 14){
                                   $table = 'cca_para_3c1';
                                }
                                if($res_rowsubhead['id'] == 15){
                                   $table = 'cca_para_3d';
                                }
                                
                         $sql_para  = "SELECT * FROM ".$table." WHERE paragraph_id = '".$res_rowsubhead['id']."'  AND mngplan_id = '".$manageplan_id."' " ; 
                         
                          $sql_para_res   = mysqli_query($mysqli,$sql_para) ;
                          $para_row       = mysqli_fetch_array($sql_para_res);
                        
                              if($para_row != ''){
                                ?>
                                 <td><a href="javascript: ccadatapost('<?php echo $res_rowsubhead['page_url'];?>',{para_id:'<?php echo $para_row['paragraph_id'];?>' , mangPlan_id: '<?php echo $para_row['mngplan_id']  ; ?>' , edit_id: 1 });" class="btn btn-info">Edit</a></td>
                                <?php
                              }else{
                                ?>
                                 <td><a href="javascript: ccadatapost('<?php echo $res_rowsubhead['page_url'];?>',{para_id:'<?php echo $res_rowsubhead['id'];?>' , edit_id: 0});" class="btn btn-warning">Add</a></td>
                                <?php
                              }

                             ?>
                           
                          </tr>

                      <?php }
                      }
                    ?>
                  </tbody>
                </table>
              </fieldset>
            </div>
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
<script>
 $(document).ready(function() {
   $('#tableid').DataTable();
   
   showMessage();

} );


</script>



