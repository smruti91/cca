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


  // ======================Code to insert the institution wise pending year==============================

// $institution_sql=mysqli_query($mysqli,"SELECT * FROM `cca_institutions` ORDER BY `ddo_code` ASC");
// while($institution_details=mysqli_fetch_array($institution_sql)){
// //print_r($institution_details);
// $org_id=$institution_details['id'];
// $dept_id=$institution_details['dept_id'];
// $mandays_audit=$institution_details['mandays_audit'];
// $mandays_review=$institution_details['mandays_review'];

//  $currentYear=Date('Y')-1;
//   for($i=0;$i<5;$i++){
//    $currentYear1=$currentYear-$i;
//    $showyear=($currentYear1)."-".($currentYear1+1);
//     $pending_yearsql="INSERT INTO `cca_pendingyear`(`org_id`, `pending_year`, `show_year`, `dept_id`, `mandays_audit`, `mandays_review`) VALUES ('$org_id','$currentYear1','$showyear','$dept_id',$mandays_audit,$mandays_review)";
//    mysqli_query($mysqli,$pending_yearsql);
//   }
// } 

// =======================================================================================================



  if(isset($_POST['submit'])){
    $duperror=0;
     $financial_year=$_POST['Financial_year'];
     $dept_id=$_SESSION['dept_id'];
     $created_by=$_SESSION['userid'];
     $institution=$_POST['institution'];

     foreach ($financial_year as $year) {
       $sql_year=mysqli_query($mysqli,"UPDATE `cca_pendingyear` SET status='2' WHERE org_id='".$institution."' and pending_year='".$year."'");
     }
  }

  if(isset($_POST['action']) && $_POST['action']=="delete"){
    $id=$_POST['del_id'];
    $sql_year=mysqli_query($mysqli,"Delete from `cca_audited_year` where id='".$id."'");
    if($sql_year){
      echo 'success';exit;
     }
  }
?>

<?php include "header.php"; ?>
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
           <h2>Manage Financial Year</h2>
           <hr/>
              <div class="p-2">
               <h4 style="color:green"> * Save the Financial Years which are already audited.</h4>

               <?php
                if(isset($duperror) && ($duperror==1)){?>
                   <span style="color:red"><b>Dupplicate Record exists!!</b></span> 
                <?php }

               ?>
                <form onsubmit="return handleData()" class="form-horizontal" method="post" action="manage_year.php">
                  <div class="form-group">
                      <label class="control-label col-sm-2">Audit Institution:</label>
                      <div class="col-sm-8">
                          <?php
                            echo "SELECT * FROM cca_institutions where dept_id = '".$_SESSION['dept_id']."'";
                             $org = mysqli_query($mysqli, "SELECT * FROM cca_institutions where dept_id = '".$_SESSION['dept_id']."'");
                     ?>
                     <select class="form-control" data-placeholder="Choose a Party" tabindex="1" name="institution" id="institution">
                      <?php 
                        while($org_res = mysqli_fetch_array($org)){?>
                           <option value='<?php echo $org_res['id'];?>' ><?php echo $org_res['institution_name']."(".$org_res['ddo_code'].")"; ?></option>
                        <?php }
                      ?>
                     </select>
                      </div>
                    </div> 
                  <legend>
                  <?php
                  $year = date("Y"); 
                  for($i=0;$i<7;$i++){?>
                    <div class="form-group">
                          <input name="Financial_year[]" id="checkbox2" type="checkbox" value='<?php echo ($year-$i)?>'>
                          <label for="checkbox2"> <?php echo ($year-$i) ."-". ($year-$i+1);?></label>
                    </div>
                  <?php }
                  ?></br>
                  </legend>
                  <div>
                  <button type="submit" class="btncca" name="submit" value="Submit">Save Year</button>
                  </div>
                </form></br>
              </div>
         

          <table class="table" id="tableid">
            <thead>
              <th>Sl no</th>
              <th>Institution</th>
              <th>Audited Financial Years</th>
              <th>Action</th>
            </thead>
            <tbody>
            <?php
            
            $audited_years="select ay.*,i.institution_name,i.ddo_code from `cca_pendingyear` ay,`cca_institutions` i where ay.dept_id='".$_SESSION['dept_id']."' and ay.org_id=i.id and ay.status='2'";
            $audityear_res=mysqli_query($mysqli,$audited_years);
            $i=0;
            while($res=mysqli_fetch_array($audityear_res)){
              $i++;
              ?>
              <tr>
                <td><?php echo $i ?></td>
                <td><?php echo $res['institution_name']."(".$res['ddo_code'].")"; ?></td>
                <td><?php echo $res['show_year']; ?></td>
                <td><button class="btn btn-danger" onclick="delete_audited_year('<?php echo $res['id'];?>')">Delete</button></td>
              </tr>
           <?php }
            ?>
          </tbody>
          </table>
           </div>
      </div>
      </div>
        <!-- /.container-fluid -->
    </div></br></br></br>
    <div class="clear:both;"></div>
</div>
<?php include "footer.php"; ?>

<script>
$(document).ready(function() {
   $('#tableid').DataTable();
} );
function delete_audited_year(id){
if(confirm("Are you Sure to Delete this record!!")){
    $.post("manage_year",{del_id: id,action: 'delete'},function(res){
      //alert(res);
      if(res=="success"){
        window.location.reload();
      }
  });

  }
}
</script>



