<?php 
session_start();
include "header.php";
include("../common_functions.php");

$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
 
 if($userid == -1)
  {
	  header('location:../index.php');
	  exit;
  }
?>
<style>
	.actext img{
		height:100%;
		width:100%;
		border-radius: 70%;
	}
</style>

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
      <p><a href="transfer_employee.php">Transfer Employee</a></p>
      <img src="../images/transfer.jpeg" class="img-responsive" style="width:100%" alt="Image">
    </div>
    <!-- <div class="col-sm-3 actext"> 
      <p><a href="#">Activity1</a></p>
      <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
    </div>
    <div class="col-sm-3 actext"> 
      <p><a href="#">Activity1</a></p>
      <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
    </div>
    <div class="col-sm-3 actext">
      <p><a href="#">Activity1</a></p>
      <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
    </div> -->
  </div>
</div>
<br>

<!-- <div class="container-fluid bg-3 text-center">    
  <div class="row">
    <div class="col-sm-3 actext">
      <p>Activity1</p>
      <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
    </div>
    <div class="col-sm-3 actext"> 
      <p>Activity1</p>
      <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
    </div>
    <div class="col-sm-3 actext"> 
      <p>Activity1</p>
      <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
    </div>
    <div class="col-sm-3 actext">
      <p>Activity1</p>
      <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
    </div>
  </div>
</div><br><br>  --> 

   </div>
</div>

<?php include "footer.php"; ?>

