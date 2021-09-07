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
    <div class="col-sm-3 actext"> 
     <div class="dash_div" style=""><a href="my_plan">My Plan</a></div>
    </div>

    <!-- <div class="col-sm-3 actext">
      <div class="dash_div"><a href="#">My Plan</a></div>
    </div> -->

    <div class="col-sm-3 actext">
     <div  class="dash_div"><a href="manage_diary">Diary Management</a></div>
    </div>
    <?php

      $program_auditor = mysqli_query($mysqli, "SELECT * FROM cca_mngoffice where program_auditor = ".$_SESSION['userid']." and status=1");
   
      if(mysqli_num_rows($program_auditor)>0){
        ?>
            <div class="col-sm-3 actext">
             <div class="dash_div"><a href="team">Manage Party</a></div>
            </div>

             <div class="col-sm-3 actext">
              <div class="dash_div"><a href="manage_plan">Manage Plan</a></div>
             </div>
			 <div class="col-sm-3 actext">
              <div class="dash_div"><a href="resufle_member">Reshuffle Member</a></div>
             </div>
			 <div class="col-sm-3 actext">
              <div class="dash_div"><a href="resufle_Institution">Reshuffle Institution</a></div>
             </div>

             <div class="col-sm-3 actext">
              <div class="dash_div"><a href="manage_year">Manage Financial Year</a></div>
            </div>

            <div class="col-sm-3 actext">
             <div  class="dash_div"><a href="circular_notice.php">Manage Circular/Notice</a></div>
            </div>
        <?php
      }else{?>
        <div class="col-sm-3 actext">
             <div  class="dash_div"><a href="view_circular">Circular/Notice</a></div>
            </div>
      <?php }
    ?>
    </div>
  </div>
</div>
</div>
</div>

<?php include "footer.php"; ?>

