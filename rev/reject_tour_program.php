<?php 
include "header.php";
include("../common_functions.php");
include_once("../config.php");
session_start();
$userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
 
 if($userid == -1)
  {
    header('location:../index.php');
    exit;
  }
  $sql = "SELECT * FROM cca_users WHERE ID = ".$userid;
  $sql_query = mysqli_query($mysqli, $sql);
  $res_query = mysqli_fetch_array($sql_query);
  
  //print_r($res_query);

?>
<style type="text/css">
  .tour_table{
        background: #fff;
        padding: 20px 25px;
        margin: 30px 0;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
  }
  table.table tr th, table.table tr td {
        border-color: #e9e9e9;
        padding: 12px 15px;
        vertical-align: middle;
    }
  
     table.table-striped tbody tr:nth-of-type(odd) {
      background-color: #fcfcfc;
  }
  table.table-striped.table-hover tbody tr:hover {
    background: #f5f5f5;
  }
  
  
    table.table td a {
    font-weight: bold;
    color: #566787;
    display: inline-block;
    text-decoration: none;
    outline: none !important;
  }
  table.table td a:hover {
    color: #2196F3;
  }
  table.table td a.edit {
        color: #FFC107;
    }
    table.table td a.delete {
        color: #F44336;
    }
    table.table td i {
        font-size: 19px;
    }
    table.table .avatar {
    border-radius: 50%;
    vertical-align: middle;
    margin-right: 10px;
  }
  .tour_table{
  overflow-y:scroll;
  height:600px;
}
.table-row{
  height: 50px;
} 
</style>

<div id="wrapper">
<?php include "leftpanel.php";?>
 <div id="page-wrapper">
 <div class="container-fluid d-flex">    
 
       <div class="row table-row">
             <div class="col-sm-6 ">
                 <h3 class="t-program">Reject <b>Tour Program</b></h3>
             </div>
             
            
        </div>
         
        
        <div class="row " >
           <div class="col-sm-12 ">
             <div class="row" style="padding-bottom:10px;">
                   <div class="tour_table">
                    <table class="table table-striped table-bordered" id="tableid">
                    <thead>
                      <tr>
                        <th>Sl.No</th>
                        <th>Tour For the month</th>
                        <th>Plan Name</th>
                        <th>Tour Catagory</th>
                        <th>Employee Name</th>
                        <th>date of Commencement </th>
                        <th>date of Completion </th>
                        <th>Purpose </th>
                        <th>Distance In KM </th>
                        <th>Remark  </th>
                        
                        <th>Reject Comments</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php
                       
                        $sql_reject =("SELECT * FROM  cca_tour_reject r , cca_tour_details td where r.tour_id=td.ID AND r.tour_id IN (SELECT  ID FROM `cca_tour_details`  WHERE emp_id= '".$res_query['ID']."' )");
                      // echo $sql_reject;
                        $result_query = mysqli_query($mysqli, $sql_reject); 

                        if(mysqli_num_rows($result_query)>0)
                        {
                          $count=0;
                          while($res = mysqli_fetch_array($result_query) )
                          {
                            
                              $count++;
                              ?>
                               <tr>
                                  <td><?php echo $count; ?></td>
                                  <td><?php echo date("d-m-Y",strtotime($res['tour_for_the_month']));?></td>
                                  <td><?php echo $res['plan_id'];?></td>
                                  <td><?php 
                                  $cat_sql = "select * from cca_tour_catagory where id = ".$res['t_category'];
                                  $cat_res = mysqli_query($mysqli,$cat_sql);
                                  $row = mysqli_fetch_array($cat_res);
                                   if($row['ID'])
                                     {
                                       echo $row['catagory_name'];

                                     };

                                   ?></td>
                                  <td><?php 
                                     $sql      = "select * from cca_users where ID= ".$_SESSION['userid'];
                                     $sql_res  = mysqli_query($mysqli,$sql);
                                     $user_row = mysqli_fetch_array($sql_res);
                                     if( count($user_row) > 0 )
                                     {
                                       echo $user_row['Name'];
                                     }
                                  ?></td>
                                  <td><?php echo date("d-m-Y",strtotime($res['dt_commencent']));?></td>
                                  <td><?php echo date("d-m-Y",strtotime($res['dt_completion']));?></td>
                                  <td><?php echo $res['purpose'];?></td>
                                  <td><?php echo $res['distance'];?></td>
                                  <td><?php echo $res['remarks']; ?></td>
                                 
                                  <td><?php echo $res['reject_msg']; ?></td>
                                  

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

 </div>
</div>
</div>

  </div>
    <div class="clear:both;"></div>
</div>
<?php include "footer.php"; ?>
<link rel="stylesheet" href="../css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="../js/bootstrap-datepicker.min.js"></script>

<script>
   $(document).ready(function(){
     $('#tableid').DataTable();
      var date_input=$('input[name="date"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'dd-mm-yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
  });
  var yearsLength = 30;
var currentYear = new Date().getFullYear();
for(var i = 0; i < 30; i++){
  var next = currentYear+1;
  var year = currentYear + '-' + next.toString();
  $('#financialYear').append(new Option(year, year));
  currentYear--;
}



</script>