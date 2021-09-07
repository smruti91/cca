<!DOCTYPE html>
<html>
<head>
  <title>CCA</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <link href="../css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link href="../css/default.css" rel="stylesheet" id="bootstrap-css">
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <script src="../js/jquery.dataTables.min.js"></script>
  <script src="../js/dataTables.bootstrap4.min.js"></script>
  <script src="../js/common.js"></script>

<link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">
</head>
<script>

// ================Function for Dynamic date===================

function date_time(id)
{
        date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
        d = date.getDate();
        day = date.getDay();
        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();
    var ampm = " PM "
  if (h < 12){
    ampm = " AM ";
  }
  if (h > 12){
    h -= 12;
  }
    if(h<10)
    {
            h = "0"+h;
    }
    m = date.getMinutes();
    if(m<10)
    {
            m = "0"+m;
    }
    s = date.getSeconds();
    if(s<10)
    {
            s = "0"+s;
    }
    result = ''+days[day]+' '+months[month]+' '+d+' '+year+' '+h+':'+m+':'+s + ampm;
    document.getElementById(id).innerHTML = result;
    //setTimeout('date_time("'+id+'");','1000');
    setTimeout(function() { date_time(id); },'1000');
    return true;
}
</script>
<body>
<div class="main">
  <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="dashboard.php">Common Cadre Audit</a>
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
        
          if(isset($_SESSION['userid'])){?>
          <li><a>You are Logged in as,  <span style="color: #ecdc10d1;"><?php echo $_SESSION['designation'];?></span></a> </li>
          <li><a href="password_change"><span class="glyphicon glyphicon-log-in"></span>Change Password </a></li>
          <li><a href="../index.php"><span class="glyphicon glyphicon-log-in"></span>Logout </a></li>
         <?php }
        ?>
      </ul>
    </div>
  </div>
</nav>