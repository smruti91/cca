<?php
session_start();
include "header.php";
include("../common_functions.php");
include_once("../config.php");

$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);

$result_user = mysqli_query($mysqli, "SELECT Dept_ID from `cca_users` where ID='".$userid."' ");
$resuser = mysqli_fetch_array($result_user);

$deptid_fa= $resuser ['Dept_ID'];
if($userid == -1)
{
header('location:../index.php');
exit;
}


?>
<style>
/*div.main{
height:100%;
}*/
.search-panel{
width: 90%;
height: 65px;
margin-bottom: 20px;
padding: 10px;
}
#alert_msg{
    position:absolute;
    z-index:1400;
    top:2%;
    right:4%;
    margin:40px auto;
    text-align:center;
    display:none;
}
</style>
<div id="wrapper">
  <?php include "leftpanel.php";?>
  <div id="page-wrapper">
    <div class="container-fluid text-center">
      <div class="row content">
        <div class="col-sm-12 text-center">
          <h1>Add Circular Subject</h1>
          <hr>
          <div  id="alert_msg" ></div>
          <div class="search-panel">
            
            <form  class="form-inline" id="addform"  method="post">
              
              <div class="form-group" >
                <label>Subjects :</label>
                <input type="text" class="form-control" name="subject" id="subject" required >
              </div>
              <input type="hidden" name="sub_edit_id" id="sub_id" value="">
              <button type="button" class="btn btn-info add_sub"    id="circular_btn" >ADD</button>
              
            </form>
            
          </div>
          <hr>
          
          <div class="row " >
            <div class="col-md-12 ">
              <div class="row" style="padding-bottom:10px;">
                <div class="tour_table">
                  <table class="table table-striped table-bordered" id="tableid">
                    <thead>
                      <tr>
                        <th>Sl.No</th>
                        <th>Subject Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      
                      $result = mysqli_query($mysqli, "SELECT * FROM cca_circular_subject WHERE status=1  ");
                      if(mysqli_num_rows($result)>0)
                      {
                      $count=0;
                      while($res = mysqli_fetch_array($result) )
                      {
                      
                      $count++;
                      ?>
                      <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $res['sub_name'];?></td>
                        <td>
                          <input type="button" name="edit" class="btn btn-warning edit" id="<?php echo $res['sub_id']; ?>" value="Edit">
                          <input type="button" name="send" class="btn btn-danger delete" id="<?php echo $res['sub_id']; ?>" value="Delete">
                         <!-- 
                          -->
                          
                        </td>
                      </tr>
                      <?php
                      }
                      }
                      
                      ?>
                      
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- SubjectModal Modal HTML -->
          
          
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


if ( sessionStorage.type=="Success" ) {
       $('#alert_msg').show();

        $("#alert_msg").addClass("alert alert-success").html(sessionStorage.message);
        closeAlertBox();
          //sessionStorage.reloadAfterPageLoad = false;
        sessionStorage.removeItem("message");
        sessionStorage.removeItem("type");
  }
if(sessionStorage.type=="Error")
{
   $('#alert_msg').show();

        $("#alert_msg").addClass("alert alert-danger").html(sessionStorage.message);
        closeAlertBox();

        sessionStorage.removeItem("message");
        sessionStorage.removeItem("type");
}
});

function closeAlertBox(){
window.setTimeout(function () {
  $("#alert_msg").fadeOut(300)
}, 3000);
} 

$(document).on('click','.edit',function(e){
e.preventDefault();
var id = $(this).attr("id");
$.ajax({
type:'POST',
url:'ajax_circular.php',
data: {action:'edit_sub',sub_id:id},
dataType:"json",
success:function(data){
console.log(data);

$('#subject').val(data.sub_name);

$('#sub_id').val(id);
$('#circular_btn').text('Update');
}
})

});

$(document).on('click','.delete',function(e){
e.preventDefault();
var id = $(this).attr("id");
$.ajax({
type:'POST',
url:'ajax_circular.php',
data: {action:'delete_sub',sub_id:id},

success:function(data){
console.log(data);
     var element = data.split("#");
      if(element[0]=="Success")
      {
            sessionStorage.reloadAfterPageLoad = true;
            sessionStorage.message = element[1];
            sessionStorage.type = element[0];
      } 
      else
      {
             sessionStorage.reloadAfterPageLoad = true;
             sessionStorage.message = element[1];
             sessionStorage.type = element[0];
      }
   
   location.reload();
}
});
});

$(document).on('click','.add_sub',function(e){
e.preventDefault();
 
  var sub_name = $('#subject').val();
  var sub_id   = $('#sub_id').val();
  var status  =  1;

$.ajax({
type:'POST',
url:'ajax_circular.php',
data: {action:'add_sub',sub_name:sub_name,status:status,sub_id:sub_id},

success:function(data){
console.log(data);
  
    var element = data.split("#");
     if(element[0]=="Success")
     {
           sessionStorage.reloadAfterPageLoad = true;
           sessionStorage.message = element[1];
           sessionStorage.type = element[0];
     } 
     else
     {
            sessionStorage.reloadAfterPageLoad = true;
            sessionStorage.message = element[1];
            sessionStorage.type = element[0];
     }
  
  location.reload();
}
})

});

</script>