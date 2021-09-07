<?php 
session_start();
include_once("../config.php");

include("../common_functions.php");


$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
 
 if($userid == -1)
  {
    header('location:../index.php');
    exit;
  }


  if(isset($_POST) && !empty($_POST['action'])){
    $sender_userid=$_POST['sender'];
    $reciever_userid=$_POST['rec'];
    $message=$_POST['msg'];
    $sql_ins="INSERT INTO `chat`(`sender_userid`, `reciever_userid`, `message`,`status`) VALUES ('$sender_userid','$reciever_userid','$message',0)";
    mysqli_query($mysqli,$sql_ins);
    echo 'success';exit;
  }

  if(isset($_POST) && !empty($_POST['find_chat'])){
    $sender=$_SESSION['userid'];
    $rec=$_POST['rec'];
   // echo "select * from `chat` where (sender_userid='".$sender."' and reciever_userid='".$rec."') or (reciever_userid='".$sender."' and sender_userid='".$rec."')";
   $res_chat= mysqli_query($mysqli,"select * from `chat` where (sender_userid='".$sender."' and reciever_userid='".$rec."') or (reciever_userid='".$sender."' and sender_userid='".$rec."')");

   if(mysqli_num_rows($res_chat)>0){
   while($res_chatr=mysqli_fetch_array($res_chat)){
    if( $res_chatr['sender_userid']==$_SESSION['userid']){
      ?>
       <div class="msg-right"><?php echo $res_chatr['message']; ?></div><div class="msg_push"></div>
    <?php }else{?>
      <div class="msg-left"><?php echo $res_chatr['message']; ?></div><div class="msg_push"></div>
    <?php }
    ?>
   
   <?php }
   
  }else{?>
    <div class="msg_push"></div>
  <?php }
   exit;
}


  if(isset($_POST) && !empty($_POST['search_userac'])){
    $search_text=$_POST['search_text'];
    $sql_sel="SELECT *  FROM `cca_users` WHERE `Name` LIKE '%".$search_text."%' or `username` LIKE '%".$search_text."%'  ORDER BY `username`  ASC";
    $res_sel=mysqli_query($mysqli,$sql_sel);
    while($sel_row=mysqli_fetch_array($res_sel)){
       ?>
      <div id="sidebar-user-box" class="<?php echo $sel_row['ID'];?>" >
      <img src="../images/chat-pic.png" />
      <span id="slider-username" class="<?php echo $_SESSION['userid'];?>"><?php echo $sel_row['Name'];?> </span>
      </div>
   <?php  }
   exit;
  }

?>
<style>
.unrd_msg{
  float: right;
    border-radius: 17%;
    padding: 0 2px 0px 1px;
    background: #f5bbbb;
}
</style>
<?php include ("header.php"); ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facebook like chat popup layout</title>
  <link href="../css/chat_style.css" rel="stylesheet">
	<script src="jquery-1.10.1.min.js"></script>
	<script src="../js/chat_script.js"></script>
  <style>
    .open-button{display:none;}
  </style>
<body>
<div id="chat-sidebar">
  <input type="text" class="form-control" name="search_input" id="search_user" placeholder="search user" onkeyup="search_user()" />

  <div class="chat_container">
    <?php
      $sql_users="select * from `cca_users`";
      $result_users=mysqli_query($mysqli,$sql_users);
      while($user_row=mysqli_fetch_array($result_users)){
      ?>
      <div id="sidebar-user-box" class="<?php echo $user_row['ID'];?>" >
      <img src="../images/chat-pic.png" />
      <span id="slider-username" class="<?php echo $_SESSION['userid'];?>"><?php echo $user_row['Name'];?> </span>

      <?php 
           $sql_read="select * from `chat` where status='0' and sender_userid='".$user_row['ID']."' and reciever_userid='".$_SESSION['userid']."'";
          $res_read=mysqli_query($mysqli,$sql_read);
          $rows=mysqli_num_rows($res_read);
          if($rows>0){?>
            <div class="unrd_msg"><?php echo $rows ;?></div>
         <?php }
      ?>
      
      </div> 
    <?php }?>
  </div>

</div>
<?php include "footer.php"; ?>

<script>
  function search_user(){
    var search_text=$("#search_user").val();
    $.post("chat_index.php",{search_text: search_text,search_userac: 'search_user'},function(res){
      // alert(res);
      $(".chat_container").html(res);
    });
  }
</script>