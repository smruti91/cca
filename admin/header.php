
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Department</title>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>cca/css/roboto.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>cca/css/icon.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>cca/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>cca/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>cca/css/cca-dashboard.css">

<script src="<?php echo BASE_URL; ?>cca/js/jquery.min.js"></script>
<script src="<?php echo BASE_URL; ?>cca/js/bootstrap.min.js"></script>
<script src="<?php echo BASE_URL; ?>cca/js/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>cca/js/dataTables.bootstrap4.min.js"></script>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>cca/css/dataTables.bootstrap4.min.css">


<style type="text/css">
   
</style>
<script type="text/javascript">
$(document).ready(function(){
  
  // Activate tooltip
  $('[data-toggle="tooltip"]').tooltip();
  
  // Select/Deselect checkboxes
  var checkbox = $('table tbody input[type="checkbox"]');
  checkbox.click(function(){
    
    var rowid=this.value;
    if(!this.checked){
      $("#trow_"+rowid).css('background-color','');
      $("#edit_"+rowid).hide();
      $("#delete_"+rowid).hide();
    }
    if(this.checked){
      
      $(this).parent('span').parent('td').parent('tr').siblings('tr').css('background-color','');
      $(".edit").hide();
      $(".delete").hide();
      $('input.checkbox1').not(this).prop('checked', false);

      $("#trow_"+rowid).css('background-color','#08080838');
      $("#edit_"+rowid).show();
      $("#delete_"+rowid).show();
      
    }
  });
});


$(document).ready(function() {
    $('#tableid').DataTable();
} );

</script>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand logo" href="#">Common Cadre Audit</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <!-- <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Gallery</a></li>
        <li><a href="#">Contact</a></li>
      </ul> -->
      <ul class="nav navbar-nav navbar-right">
         <?php
        
          //if(isset($_SESSION['userid'])){?>
          <li><a>You are Logged in as,  <span style="color: #ecdc10d1;">Admin user</span></a> </li>
          <li><a href="index.php"><span class="glyphicon glyphicon-log-in"></span>Logout </a></li>
         <?php //}
        ?>
      </ul>
    </div>
  </div>
</nav>