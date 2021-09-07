<?php 
session_start();
include("../common_functions.php");
include_once("../config.php");

$dept = $_SESSION['dept_id'];
$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
 
 if($userid == -1)
  {
    header('location:../index.php');
    exit;
  }

  
if(isset($_POST['action']) && $_POST['action']=='add'){
$init = $_POST['prog'];


$initiate = date("Y-m-d",strtotime($_POST['initiate']));

$commence = date("Y-m-d", strtotime($_POST['commence']));

$complete = date("Y-m-d", strtotime($_POST['complete']));

$find_record=mysqli_query($mysqli,"select * from `cca_plan` where plan_name='".$init."'");
if(mysqli_num_rows($find_record)>0){
    echo "exists";exit;
}

$sql =" INSERT INTO `cca_plan` ( `plan_name`, `plan_start_date`, `plan_end_date`,  `plan_close_date`,`dept_id`,`status`) VALUES ( '$init', '$initiate', '$commence',  '$complete','$dept','Planning')";
  $result_insert = mysqli_query($mysqli, $sql);
echo "success";
exit();
}

if(isset($_POST['action']) && $_POST['action']=='edit'){
print_r($_POST);
  $find_record=mysqli_query($mysqli,"select * from `cca_plan` where id='".$_POST['id']."'");
  if(mysqli_num_rows($find_record)>0){
     $record_row=mysqli_fetch_row($find_record);
  }
  print_r( $record_row);
  ?>

  <form class="form-horizontal" action="" method="post">
    <div class="form-group">
      <div id="div_errordup" class="error_div" style="display:none;text-align:left;"></div>
      <label class="control-label col-sm-2">Programme for the year:</label>
      <div class="col-sm-2">
       <select class="form-control" id="financialYear" disabled="true"></select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2">Plan Initiation Date:</label>
      <div class="col-sm-2">
        <input class="form-control" id="date_init" name="date" placeholder="DD/MM/YYY" type="text" autocomplete="off"/>
      </div>
    </div>
    <div id="div_errorintd" class="form-group error_div" style="display:none;text-align:left;">Plan Initiation Date can not be blank!</div>
    <div class="form-group">
      <label class="control-label col-sm-2">Date of Commencement of the programme:</label>
      <div class="col-sm-2">
        <input class="form-control" id="date_comm" name="date" placeholder="DD/MM/YYY" type="text" autocomplete="off"/>
      </div>
    </div>
     <div id="div_errorcomnce" class="form-group error_div" style="display:none;text-align:left;">Date of Commencement can not be blank!</div>
    <div class="form-group">
      <label class="control-label col-sm-2">Date of Completion of the programme:</label>
      <div class="col-sm-2">
        <input class="form-control" id="date_comp" name="date" placeholder="DD/MM/YYY" type="text" autocomplete="off"/>
      </div>
    </div>
    <div id="div_errorcomp" class="form-group error_div" style="display:none;text-align:left;">Date of Completion can not be blank!</div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-4">
        <button type="button" class="btn btn-default" onclick="save_plan()">Submit</button>
      </div>
    </div>
  </form>

<?php
exit;
}

?>

<?php include "header.php"; ?>
<style>
/*div.main{
height:100%;
}*/
</style>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>Plan Initiation</h1>

            <hr>
            
            <div id="formDiv">
              <form class="form-horizontal" action="" method="post">
              <div class="form-group">
                <div id="div_errordup" class="error_div" style="display:none;text-align:left;"></div>
                <label class="control-label col-sm-2">Programme for the year:</label>
                <div class="col-sm-2">
                 <select class="form-control" id="financialYear" disabled="true"></select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Plan Initiation Date:</label>
                <div class="col-sm-2">
                  <input class="form-control" id="date_init" name="date" placeholder="DD/MM/YYY" type="text" autocomplete="off"/>
                </div>
              </div>
              <div id="div_errorintd" class="form-group error_div" style="display:none;text-align:left;">Plan Initiation Date can not be blank!</div>
              <div class="form-group">
                <label class="control-label col-sm-2">Date of Commencement of the programme:</label>
                <div class="col-sm-2">
                  <input class="form-control" id="date_comm" name="date" placeholder="DD/MM/YYY" type="text" autocomplete="off"/>
                </div>
              </div>
               <div id="div_errorcomnce" class="form-group error_div" style="display:none;text-align:left;">Date of Commencement can not be blank!</div>
              <div class="form-group">
                <label class="control-label col-sm-2">Date of Completion of the programme:</label>
                <div class="col-sm-2">
                  <input class="form-control" id="date_comp" name="date" placeholder="DD/MM/YYY" type="text" autocomplete="off"/>
                </div>
              </div>
              <div id="div_errorcomp" class="form-group error_div" style="display:none;text-align:left;">Date of Completion can not be blank!</div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                  <button type="button" class="btn btn-default" onclick="save_plan()">Submit</button>
                </div>
              </div>
            </form>
          </div>
                <div class="row" style="padding-bottom:50px;">
                    <table class="table table-striped table-bordered" id="tableid">
                    <thead>
                      <tr>
                        <th>Sl.No</th>
                        <th>Programme for the year</th>
                        <th>Plan Initiation date</th>
                        <th>Date of commencement</th>
                        <th>Date of Completion</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                              $result = mysqli_query($mysqli, "SELECT * FROM cca_plan ORDER BY id "); 
                              if(mysqli_num_rows($result)>0){

                      //while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
                      $count = 0;
                      //while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
                      while($res = mysqli_fetch_array($result)) {
                      $count++ ;
                      ?>
                      <tr class='dang'>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $res['plan_name']; ?></td>
                        <td><?php echo date("d-m-Y",strtotime($res['plan_start_date'])); ?></td>
                        <td><?php echo date("d-m-Y",strtotime($res['plan_end_date'])); ?></td>
                        <td><?php echo date("d-m-Y",strtotime($res['plan_close_date'])); ?></td>
                        <td><?php echo $res['status'] ?></td>
                        <td>
                          <button class="btn btn-primary" onclick="edit_plan('<?php echo $res['id']; ?>')">Edit</button>
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

    $(document).ready(function(){
      var dateStart=new Date();
      var date_input=$('input[name="date"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'dd-mm-yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
        startDate: dateStart,
      };
      date_input.datepicker(options);
    })

var currentYear = new Date().getFullYear();

  var next = currentYear+1;
  var year = currentYear  + '-' +  next.toString();
  $('#financialYear').append(new Option(year, year));

</script>
<script >
  function save_plan(){
     var prog = $("#financialYear").val();
     var initiate = $("#date_init").val();
     var commence = $("#date_comm").val();
     var complete = $("#date_comp").val();

     
     if(initiate==""){
      $("#div_errorintd").show();
       $("#div_errorcomp").hide();
        $("#div_errorcomnce").hide();
     }else if(commence==""){
        $("#div_errorcomnce").show();
        $("#div_errorintd").hide();
         $("#div_errorcomp").hide();
     }else if(complete==""){
        $("#div_errorcomp").show();
        $("#div_errorcomnce").hide();
        $("#div_errorintd").hide();
     }else{

     $.post("plan_initiation.php",{action: "add",prog:prog,initiate:initiate,commence:commence,complete :complete},
      function(res){
          // alert(res);
          if(res=='success'){
             window.location.reload();
           }
           if(res=="exists"){
            $(".error_div").hide();
            $("#div_errordup").html("Program for the Year already exists!!");
            $("#div_errordup").show();
           }
          }
      );
     }
   }

     function edit_plan(id){
      $.post("plan_initiation.php",{id:id,action: 'edit'},function(res){
        alert(res);
        $("#formDiv").html(res);
      });
     }
  
</script>

