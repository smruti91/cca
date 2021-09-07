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
if (isset($_SESSION['mngplan_id'])){
  $manageplan_id=$_SESSION['mngplan_id'];
  $manageplansql= mysqli_query($mysqli,"select plan_name,org_id,team_id,audit_start_date,audit_end_date from cca_manageplan m,cca_plan p where m.plan_id=p.id and m.id='".$manageplan_id."'"); 
  $res_row=mysqli_fetch_array($manageplansql);

  $orgname= find_institutionname($res_row['org_id'],$mysqli);
  $team_name=find_teamname($res_row['team_id'],$mysqli);
  $_SESSION['paraid']=$_POST['para_id'];

}

?>
<style>
div.main{
height:100%;
}

</style>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>Manage Audit Report</h1>
            <hr>
            <div style="    width: 100%;
                 background-color: #42c19f2e;
                 padding: 5px;
                 border: 3px solid #2daab0;
                

              ">
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

               <div style="clear:both;background-color: #ffb75b;">
                <h3><?php echo findpara_head($_SESSION['paraid'],$mysqli);?></h3>
              </div>

            </div>
             <div class=" main-para" 
                 style="border: 1px solid black;
                 width: 85%;
                 height: 300px;
                 margin: 10px auto;
                 padding-top: 20px;
                 ">
                 <form action="">
                                      
                 <div class="row">
                      <div class="col-md-4">
                          <div class="col-md-4">
                             <label for="">Audit Type</label>
                          </div>
                          <div class="col-md-8">
                          <div class="form-group">
                              <select  class="form-control">
                                <option>  Audit Type</option>
                                <option>IR</option>
                                <option>IAR</option>
                                <option>EAR</option>
                              </select>
                          </div>
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="col-md-4">
                             <label for="">Year</label>
                          </div>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="year"  id="year" placeholder="Year of accounts" >
                          </div>
                      </div>
                      <div class="col-md-5">
                          <div class="col-md-7">
                             <label for="">No. of objection paras </label>
                          </div>
                          <div class="col-md-5">
                              <input type="text" class="form-control" name="no_obs_para"  id="no_obs_para"  >
                          </div>
                      </div>
                 </div> 
                 <div class="row">
                   <div class="col-md-6">
                     <label for="">Audit Observation </label>
                     <div class="form-group">
                          <textarea  class="form-control" ></textarea>
                        </div>
                   </div>
                   <div class="col-md-6"></div>
                 </div>
                 </form>

                 
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
} );
</script>



