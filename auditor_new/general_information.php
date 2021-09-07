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
    border: 3px solid #2daab0;">
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
            <div class="calheader" style="padding-bottom: 50px; *background: url(../images/auditimg.jpg);background-position: center; /* Center the image */
  background-repeat: no-repeat; /* Do not repeat the image */
  background-size: cover;">
              <fieldset>
               
                <table border="1" class="table" style="width: 60%; border: 2px solid #2daab0;">
                  <tbody>
                    <tr>
                      <th>a)</th>
                      <th>Name Of The Institution</th>
                      <th><?php echo $orgname; ?></th>
                    </tr>

                    <tr>
                      <th>b)</th>
                      <th>Year Of Accounts Under Audit</th>
                      <th><?php echo $res_row['plan_name']; ?></th>
                    </tr>

                    <tr>
                      <th>c)</th>
                      <th>Name & Designation Of Authority During the Year Of A/C</th>
                      <th>i) <textarea class="form-control" cols="30" rows="3"></textarea></br>
                        ii) <textarea class="form-control" cols="30" rows="3"></textarea>
                      </th>
                    </tr>

                    <tr>
                      <th>&nbsp;</th>
                      <th>Name & Designation Of Authority At the Time Of Audit</th>
                      <th>i) <textarea class="form-control" cols="30" rows="3"></textarea></br>
                        ii) <textarea class="form-control" cols="30" rows="3"></textarea>
                      </th>
                    </tr>


                    <tr>
                      <th>d)</th>
                      <th>Duration Of Audit</th>
                      <th><?php echo $res_row['audit_start_date']; ?> to <?php echo $res_row['audit_end_date']; ?></th>
                    </tr>

                    <tr>
                      <th>&nbsp;</th>
                      <th>No Of Working Days Consumed</th>
                      <th><input type="text" class="form-control" /></th>
                    </tr>

                    <tr>
                      <th>e)</th>
                      <th>Name Of The Auditors</th>
                      <th>
                        
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
                      </th>
                    </tr>

                    <tr>
                      <th>f)</th>
                      <th>Name Of The Reviewing Officer</th>
                      <th>&nbsp;</th>
                    </tr>

                    <tr>
                      <th>g)</th>
                      <th>Date Of Final Review</th>
                      <th>&nbsp;</th>
                    </tr>

                     <tr>
                      
                      <th style="text-align:center;" colspan="3"><button class="btn btncca">Save</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-danger">Reset</button></th>
                      
                    </tr>

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
} );
</script>



