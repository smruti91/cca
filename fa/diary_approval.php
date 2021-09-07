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

  $dept_id=$_SESSION['dept_id'];

?>

<?php include "header.php"; ?>
<style>
/*div.main{
height:100%;
}*/

fieldset {
  background-color: #eeeeee;
}

legend {
    background-color: #0b77c4;
    color: white;
    padding: 5px 10px;
    width: 166px;
    /* float: left; */
    margin-left: 30px;
}
.calheader{
    width: 100%;
    background-color: #93ead2;
    margin:5px;
}
.drlink {
  color: #0a75c6;
}
</style>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>Manage Diary</h1>
            <hr>
            <div class="calheader">
            </div>
             <fieldset>
              <legend>Diary Details:</legend>
              <table class="table table-striped table-bordered dataTable no-footer" style="padding-bottom: 50px;">
                <tr>
                  <th align="center">sl no</th>
                  <th align="center">Plan Name</th>
                  <th align="center">Team Name</th>
                  <th align="center">Week days</th>
                  <th align="center">Lead Auditor</th>
                  <th>Action</th>
                </tr>
                
                <?php
                  $diary_rmrk_result=mysqli_query($mysqli,"SELECT * FROM `cca_diary_remark` rmk, `cca_users` u where rmk.add_by=u.ID and u.Dept_ID='".$dept_id."'");
                  if(mysqli_num_rows($diary_rmrk_result)>0){
                    $i=0;
                    while ($rmrk_row=mysqli_fetch_array($diary_rmrk_result)) { 
                      $frm_date=strtotime($rmrk_row['week_start_date']);
                      $to_date=strtotime($rmrk_row['week_end_date']);
                      $i++;
                      ?>
                      <tr>
                      <td style="text-align:left;"><?php echo $i; ?></td>
                       <td style="text-align:left;"><?php echo $rmrk_row['plan_id']; ?></td>
                        <td style="text-align:left;"><?php echo $rmrk_row['team_id']; ?></td>
                         <td style="text-align:left;"><a class="drlink" href="javascript: ccadatapost('diary_details', {fromdate: '<?php echo $frm_date; ?>',todate: '<?php echo $to_date; ?>',plan_id: '<?php echo $rmrk_row['plan_id']?>',team_id: '<?php echo $rmrk_row['team_id'];?>',leader_id: '<?php echo $rmrk_row['add_by'];?>'}, 'post');"><?php echo $rmrk_row['week_start_date']; ?> - <?php echo $rmrk_row['week_end_date']; ?></a>
                         </td>
                          <td style="text-align:left;"><?php echo $rmrk_row['add_by']; ?></td>
                          <td style="text-align:left;">
                            <font color="Green"><?php echo $rmrk_row['fa_status']; ?> by FA</font>
                          </td>

                          <td>
                            <a class="drlink" href="javascript: ccadatapost('diary_entry_report', {fromdate: '1617141600',todate: '1617400800',plan_id: '1',team_id: '1',leader_id: '17'}, 'post');">Diary Report </a>
                          </td>
                      </tr>
                  <?php  }
                  }
                ?>
              </table>
             </fieldset>
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
      var date_input=$('input[name="date"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'dd-mm-yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
    })

var yearsLength = 30;
var currentYear = new Date().getFullYear();
for(var i = 0; i < 30; i++){
  var next = currentYear+1;
  var year = currentYear + '-' + next.toString();
  $('#financialYear').append(new Option(year, year));
  currentYear--;
}

</script>



