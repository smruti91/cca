<?php
session_start();

include("../common_functions.php");
include_once("../config.php");


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
            <h1>Manage / Resuffling of institution</h1>
            <hr>

            <table class="table table-striped table-bordered" id="tableid">
   				
   				<tr class="Ttext1">
                    <td>Sl No </td>
                    <td>Programme For The Year </td>
					 <td>Date Of Commencement </td>
					<td>Date Of Completion </td>
					<td>Status </td>
                    
                    <td>Action</td>
                  </tr>
<?php 
			$query_plan = generateSQL("SELECT * FROM `cca_plan` WHERE dept_id=?",array($_SESSION['dept_id']),false,$mysqli);
 if(count($query_plan)>0){
 	$n=1;
foreach($query_plan as $QueryPlanRow)
	{
?>
                  <tr class="Ttext1">
                  	<td><?php echo $n++; ?></td>
                  	<td><?php echo $QueryPlanRow['plan_name']; ?></td>
                  	<td><?php echo date('d-m-Y', strtotime($QueryPlanRow['plan_end_date'])); ?></td>
                  	<td><?php echo date('d-m-Y', strtotime($QueryPlanRow['plan_close_date'])); ?></td>
                  	<td><?php echo $QueryPlanRow['status'];?></td>
                  	<?php 
					  if($QueryPlanRow['status']!=='Planning' && $QueryPlanRow['status']!=='Closed')
					  {
						  ?>
                  	
                  <td ><button type="button" class="btn btncca"><a>Reshuffle</a></button></td>
					 <?php  }
   					  else
					  { ?>
                  	 <td ><button type="button" class="btn btncca" onclick="find_team('<?php echo $QueryPlanRow['id'];?>')">Manage</button></td>
                     <?php 
					 }  ?>
                  	
<?PHP } } ?>
                  </tr>



</table>
   <script type="text/javascript">
   	function find_team(id){
   		$('<form action="find_team.php" method="post"><input type="hidden" name="id" id="id" value="'+id+'"></form>').appendTo('body').submit();
   	}


   </script>         
              
               
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