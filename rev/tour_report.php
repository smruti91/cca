<?php
  include "header.php"; 
  include("../common_functions.php");
  include_once("../config.php");
  //session_start();

   
   $userid = (($_SESSION['userid'] && $_SESSION['userid']>0)?$_SESSION['userid']:-1);
   
  $result_user = generateSQL( "SELECT Dept_ID from `cca_users` where ID=? ",array($userid),false,$mysqli); 
  $resuser = reset($result_user);
  
   $deptid_fa= $resuser ['Dept_ID'];
   if($userid == -1)
    {
      header('location:../index.php');
      exit;
    }
    
    
 ?>
 <style>
div.main{
height:100%;

}
.search-panel{
    width: 90%;
    height: 65px;
    margin-bottom: 20px;
    padding: 10px;
}
</style>

<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1>Tour Report</h1>
            <hr>
               <div class="search-panel">
                
                   <form class="form-inline">
                     <div class="form-group">
                       <label>Name of The Reviewer Officer:</label>
                       <!-- <input type="text" class="form-control" id="reviewer_name" style="width: 350px;"> -->
                       <select name="reviewer" id="reviewer" class="form-control" required>
                          <option selected> Select Reviewer Officer</option>
                             <?php
                                $sql = "SELECT u.ID ,u.Name FROM `cca_tour_details` t , `cca_users` u  WHERE t.user_id = u.ID AND u.Role_ID=? AND t.dept_id = ? GROUP BY u.ID";
                                $result = generateSQL($sql,array(4,5),false,$mysqli);

                                //while($row = mysqli_fetch_assoc($result))
                                  foreach( $result  as $row)
                                {
                                  ?>
                                    <option value="<?php echo $row['ID']; ?>" ><?php  echo $row['Name']; ?></option>
                                  <?php
                                }
                              ?>
                          </select>
                     </div>
                     <div class="form-group">
                       <label style="margin-left: 25px;">Tour For The Month:</label>
                       <input type="date" class="form-control" id="month" required>
                     </div>
                     
                     <button type="button" class="btn btn-default" style="margin-left: 25px;" onclick="tour_report()" >Search</button>
                   </form>
                 
               </div>
               <hr>
                <div class="row" style="padding-bottom:50px;">
                 <!--  <button type="button" class="btn btn-danger" id="print_report" style="margin-left: 25px; display: none;" onclick="print_report()" >Print</button> -->
                  <form id="print_tbl" action="generate-pdf.php" method="POST">
                      <table class="table table-striped table-bordered" id="tableid">
                      <thead style="background-color: #185a9d;color: white;">
                        <tr>
                          <th>Sl.No</th>
                          <th>Tour For The Month</th>
                          <th>Programme For The Year</th>
                          <th>Tour Catagory</th>
                          <th>Name of The Employee</th>
                          <th>Date of Commencement of Tour </th>
                          <th>Date of Complition of Tour</th>
                          <th>Purpose</th>
                          <th>Distance Covered</th>
                          <th>Remarks</th>

                        </tr>
                      </thead>
                      <tbody>
                      
                      </tbody>
                    </table>
                   <button class="btn btn-danger" type="submit">pdf-report</button>
                   <input type="hidden" id="tbldata" name="tbldata" value="">
                  </form>
                    
            </div>
            <!-- Reject Modal HTML -->
                           
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
    	});
      
      function tour_report(){
       var reviewer_id   =  $('#reviewer').val();
       var tour_month    =  $('#month').val();
        
      $.ajax({
        type: 'POST',
        url : 'fetch_tour_data.php',
        data:{action:'report_search',reviewer_id:reviewer_id,tour_month:tour_month},
        dataType:"json",
        success:function(data)
        {
          //console.log(data);
          tableData(data.data);
        }
      })

      }

      function tableData(data)
      {
       var rows = '';
       var tbl_data ;
       //console.log(data);
       $.each(data,function(key,value){
        
         rows = rows + '<tr>';
         rows = rows + '<td class="row-data" name="count">'+value.count+'</td>';
         rows = rows + '<td class="row-data" name="month">'+value.month+'-'+value.year+'</td>';
         rows = rows + '<td class="row-data">'+value.year_of_account+'</td>';
         rows = rows + '<td class="row-data">'+value.catagory_name+'</td>';
         rows = rows + '<td class="row-data">'+value.plan_name+'</td>';
         rows = rows + '<td class="row-data">'+value.dt_commencent+'</td>';
         rows = rows + '<td class="row-data">'+value.dt_completion+'</td>';
         rows = rows + '<td class="row-data">'+value.purpose+'</td>';
         rows = rows + '<td class="row-data">'+value.distance+'</td>';
         rows = rows + '<td class="row-data">'+value.remarks+'</td>';
         
         rows = rows + '</tr>';
        
       });

        tbl_data = JSON.stringify(data);

       $('tbody').html(rows);
       $('#tbldata').val(tbl_data);
       $('#print_report').show();


      }



      // function print_report(){
      //    //var table = $("#print_tbl").serializeArray();
     
      //    var TableData;
      //      TableData = storeTblValues();
      //      TableData = JSON.stringify(TableData);
      //      //console.log(TableData);

      //      $.ajax({
      //        type: 'POST',
      //        url : 'generate-pdf.php',
      //        data:  "pTableData=" + TableData,
      //       //dataType: "html",
      //        success:function(data)
      //        {
      //          console.log(data);
      //          //tableData(data.data);
      //          //window.location.assign('pdf');
      //          //window.open(data);
      //        }
      //      })
      // }


      
      function storeTblValues()
      {
        var TableData = new Array();
        $('#tableid tr').each(function(row, tr){
                TableData[row]={
                    "Sl No" : $(tr).find('td:eq(0)').text()
                    , "month" :$(tr).find('td:eq(1)').text()
                    , "year_of_account" : $(tr).find('td:eq(2)').text()
                    , "catagory_name" : $(tr).find('td:eq(3)').text()
                    , "plan_name" : $(tr).find('td:eq(4)').text()
                    , "dt_commencent" : $(tr).find('td:eq(5)').text()
                    , "dt_completion" : $(tr).find('td:eq(6)').text()
                    , "purpose" : $(tr).find('td:eq(7)').text()
                    , "distance" : $(tr).find('td:eq(8)').text()
                    , "remarks" : $(tr).find('td:eq(9)').text()

                }    
            }); 
           TableData.shift();  // first row will be empty - so remove
            return TableData;
      }
    
    </script>