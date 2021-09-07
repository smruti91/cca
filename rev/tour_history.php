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
  $sql = "SELECT * FROM cca_users WHERE ID = ?";
  $sql_query = generateSQL($sql,array($userid),false,$mysqli);
  $res_query = reset($sql_query);
  
  //print_r($res_query);

?>
<style type="text/css">
  .tour_table{
        background: #fff;
        padding: 20px 25px;
        margin-top: 10px;
        margin-bottom: 70px;
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
.history-panel{
  height: 100px;
  width: 50%;
  color: #fff;
  border:1px solid black;
  border-radius: 20px;
  background-image: url(../images/search-bg.jpg);
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
div.main{
height:100%;
}
</style>

<div id="wrapper">
<?php include "leftpanel.php";?>

 <div id="page-wrapper">
 <div class="container-fluid d-flex">    
 
       <div class="row table-row">
             <div class="col-sm-6 ">
                 <h3 class="t-program">History <b>Tour Program</b></h3>
             </div>
             
             <div class="col-sm-12 ">
                  <div class="history-panel">
                    <form class="search-panel" style="padding-top: 25px;" >
                     
                        <div class="from-group" >
                          <div class="row col-md-5">
                               <label class="col-md-5">From Date</label>
                               <div class="col-md-7">
                                  <input type="text" class="form-control"  name="date" id="from_dt" placeholder="DD/MM/YYY" autocomplete="off" required >
                               </div>
                          </div>
                          
                          <div class="row col-md-5">
                               <label class="col-md-5">To Date</label>
                                 <div class="col-md-7">
                                    <input type="text" class="form-control"  name="date" id="to_dt" placeholder="DD/MM/YYY" autocomplete="off" required >
                                </div>
                          </div>
                           <div class="row col-md-2" style="margin-left: 10px;">
                             <!-- <button class="btn btn-primary" onclick="search();" >Search</button> -->
                             <input type="button" class="btn btn-primary"  onclick="search();" value="Search">
                           </div>
                        </div>
                                                 
                       
                    </form>
                  </div>
             </div>
        </div>
         
        
        <div class="row " >
           <div class="col-md-12 ">
             <div class="tour_table">
               <table class="table table-striped table-bordered" id="tableid">
                 <thead>
       <tr>
                             <th>Sl.No</th>
                             <th>Tour For the month</th>
                             <th>Plan Name</th>
                             <th>Tour Catagory</th>
                             <th>Employee Name</th>
                             <th>Date of Commencement </th>
                             <th>Date of Completion </th>
                             <th>Purpose </th>
                             <th>Distance In KM </th>
                             <th>Remark  </th>
                             <th>Status</th>
                             <th> Comments</th>
                             <th>Reports</th>

       </tr>
    </thead>
    <tbody>
      
    </tbody>
               </table>
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

function search()
{
  var from_dt = document.getElementById('from_dt').value;
  var to_dt   = document.getElementById('to_dt').value;
  var tour_id = <?php echo $res_query['ID'] ?>;
  var user_id = <?php echo $_SESSION['userid'] ?>;
  // $.post('fetch_tour_data.php',{action:'search',from_dt:from_dt,to_dt:to_dt,tour_id:tour_id,user_id:user_id },
    $.ajax({
      url: 'fetch_tour_data.php',
      method:'POST',
      data:{action:'search',from_dt:from_dt,to_dt:to_dt,tour_id:tour_id,user_id:user_id},
      dataType:"json",
      success:function(data){
       // console.log(data);
        tableData(data.data);
        //$('.history_data').html(data);
        $('#tableid').DataTable();
      }
    });
 
 }

 function tableData(data)
 {
  var rows = '';
  //console.log(data);
  $.each(data,function(key,value){
   
    rows = rows + '<tr>';
    rows = rows + '<td class="row-data">'+value.count+'</td>';
    rows = rows + '<td class="row-data">'+value.tour_for_the_month+'</td>';
    rows = rows + '<td class="row-data">'+value.plan_name+'</td>';
    rows = rows + '<td class="row-data">'+value.catagory_name+'</td>';
    rows = rows + '<td class="row-data">'+value.Name+'</td>';
    rows = rows + '<td class="row-data">'+value.dt_commencent+'</td>';
    rows = rows + '<td class="row-data">'+value.dt_completion+'</td>';
    rows = rows + '<td class="row-data">'+value.purpose+'</td>';
    rows = rows + '<td class="row-data">'+value.distance+'</td>';
    rows = rows + '<td class="row-data">'+value.remarks+'</td>';
    rows = rows + '<td class="row-data">'+value.status+'</td>';
    
     switch (value.status)
     {
       case 'accept':
        rows = rows + '<td> <a href='+value.report+' target="_blank"><i class="material-icons" data-toggle="tooltip" title="uploaded report" style="font-size: 3rem; color:#f25149;">picture_as_pdf</i></a></td>';
        break;

        case 'reject':
        rows = rows + '<td>'+value.report+'</td>';
        break;

        case 'cancel':
         rows = rows + '<td>'+value.report+'</td>';
        break;

        case 'draft':
       rows = rows + '<td>'+'Draft Mode'+'</td>';
        break;    
        case 'pending':
       rows = rows + '<td>'+'Pending Mode'+'</td>';
        break;
     }
    // rows = rows + '<td>' + '<a href="report.php"><i class="material-icons" data-toggle="tooltip" title="Report" style="font-size: 3rem; color:#0d79c4;">article</i></a>'+' </td>';

     rows = rows + '<td>'+ '<form method="POST" action="generate-pdf.php">'
                 +'<input type="hidden" name="tour_for_the_month" value="'+value.tour_for_the_month+'" />'
                 +'<input type="hidden" name="plan_name" value="'+value.plan_name+'" />'
                 +'<input type="hidden" name="catagory_name" value="'+value.catagory_name+'" />'
                 +'<input type="hidden" name="Name" value="'+value.Name+'" />'
                 +'<input type="hidden" name="dt_commencent" value="'+value.dt_commencent+'" />'
                 +'<input type="hidden" name="dt_completion" value="'+value.dt_completion+'" />'
                 +'<input type="hidden" name="purpose" value="'+value.purpose+'" />'
                 +'<input type="hidden" name="distance" value="'+value.distance+'" />'
                 +'<input type="hidden" name="remarks" value="'+value.remarks+'" />'
                 +'<button  class="btn btn-primary show">Report</button></form>'
                 +'</td>';
    rows = rows + '</tr>';
   
  });

  $('tbody').html(rows);


 }
  
  
</script>