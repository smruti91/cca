<?php 
session_start();
include_once("../config.php");
include "header.php";
include("../common_functions.php");

$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
 
 if($userid == -1)
  {
    header('location:../index.php');
    exit;
  }
?>

<div class="container" style="top:30%;">
 <!-- <div class="jumbotron">
  <div class="container text-center">
    <h1>My Portfolio</h1>      
    <p>Some text that represents "Me"...</p>
  </div>
</div> -->
<div class="container-fluid" style="text-align: center;
  width: 50%;
  color: #FFF;
  font-size: 19px;
  font-weight:bold;
  padding:50px;"> <span id="date_time"></span>
  <script type="text/javascript">
  window.onload = date_time('date_time');
  </script>
</div>
<div class="container-fluid bg-3 text-center">    


 
  <!-- <h3>Some of my Work</h3><br> -->
  <div class="row">
   
   
    <?php

      $program_auditor = mysqli_query($mysqli, "SELECT * FROM cca_mngoffice where emp_id = ".$_SESSION['userid']." and  status=1");
   
      if(mysqli_num_rows($program_auditor)>0){
        ?>
             <div class="col-sm-3 actext">
              <div class="dash_div"><a href="team_approval">Manage Team</a></div>
            </div>
            <!--  <div class="col-sm-3 actext">
              <div class="dash_div"><a href="plan_approval">Manage Plan</a></div>
            </div> -->

            <div class="col-sm-3 actext"> 
             <div class="dash_div" style=""><a href="approve_party">Party Approval</a></div>
            </div>
		<div class="col-sm-3 actext">
     <div  class="dash_div"><a href="resufle_approval">Resuffle Member Approval</a></div>
    </div>
    <div class="col-sm-3 actext">
     <div  class="dash_div"><a href="resufle_inst_approval">Resuffle Institution Approval</a></div>
    </div>
        <?php
      }
    ?>

     <div class="col-sm-3 actext">
     <div class="dash_div"><a href="tour_program">Manage Tour</a></div>
    </div>

    <!--<div class="col-sm-3 actext">
     <div  class="dash_div"><a href="#">Diary Management</a></div>
    </div>

    <div class="col-sm-3 actext">
     <div class="dash_div"><a href="#">Diary Management</a></div>
    </div>

    <div class="col-sm-3 actext">
     <div class="dash_div"><a href="#">Diary Management</a></div>
    </div>

    <div class="col-sm-3 actext">
     <div class="dash_div"><a href="#">Diary Management</a></div> -->
    </div>
   <!--  <div class="col-sm-3 actext"> 
      <p><a href="transfer_process">ransfer Process</a></p>
      <img src="https://placehold.itT/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
    </div> -->

  </div>
</div>
<br>
   </div>
</div>

<?php include "footer.php"; ?>

