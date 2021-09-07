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
 <link rel="stylesheet" href="../css/roboto.css">
 <link rel="stylesheet" href="../css/icon.css">
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
#message{
      background: #3fc3a1;
      color: #fff;
      margin: 10px 200px;
      border-radius: 5px;
}
</style>

<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
            <h1> View Circular / Notice</h1>

           <hr>
                
            <!-- Circular Notice  HTML -->
               <div class="row " >
                  <div class="col-md-12 ">
                    <div class="row" style="padding-bottom:10px;">
                      <div id="message" ></p></div>
                          <div class="tour_table">
                           <table class="table table-striped table-bordered" id="tableid">
                           <thead>
                             <tr>
                               <th>Sl.No</th>
                               <th>Subject Name</th>
                               <th>Circular Notice</th>
                               <th>Circular Date</th>
                               

                             </tr>
                           </thead>
                           <tbody>
                             <?php
                                $sql ="SELECT ntc.notice_id,sub.sub_name,ntc.doc_name,ntc.circular_date FROM `cca_circularnotice` ntc , `cca_circular_subject` sub WHERE ntc.sub_id = sub.sub_id  AND ntc.status =1";
                              
                                $result = mysqli_query($mysqli, $sql); 
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
                                            <?php 
                                              
                                               $doc_path = $res['doc_name'];
                                               $file_path = BASE_URL."circular/".$doc_path;

                                            ?>
                                                 <a href="<?php echo $file_path?>" target="_blank"><i class="material-icons" data-toggle="tooltip" title="picture_as_pdf" style="font-size: 3rem; color:#f25149;" >picture_as_pdf</i></a>
                                            </td>
                                          <td><?php echo $res['circular_date'];?></td>
                                        
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

      $(document).on('submit','.Circular_form',function(e){
         e.preventDefault();
         
         $.ajax({

             type: 'POST',
             url: 'ajax_circular.php',
             data: new FormData(this),
             contentType: false,
             cache: false,
             processData:false,
             success:function(data){
              console.log(data);
              location.reload();
             }
         });

      });

      $(document).on('click','.edit',function(e){
         e.preventDefault();

          var id = $(this).attr("id");
         $.ajax({
            type:'POST',
            url:'ajax_circular.php',
            data: {action:'edit',notice_id:id},
            dataType:"json",
            success:function(data){
              // console.log(data);

               $('.circular_notice').hide();
               $('.circular_notice_edit').show();
               $('#circular_subject').val(data.sub);
               $('.uploaded_report').attr('href',data.file_path);
               $('#circular_date').val(data.circular_date);
               //$('.btn_remove_report').attr('id', data.notice_id);
               $('.remove_report').attr('id',data.notice_id);
               $('#notice_id').val(id);
               $('#circular_btn').text('Update');

            }
         })
         
         

      });

      $(document).on('click','.send',function(e){
         e.preventDefault();

          var id = $(this).attr("id");

         $.ajax({
            type:'POST',
            url:'ajax_circular.php',
            data: {action:'send',notice_id:id},
            
            success:function(data){
               //console.log(data);
               var element = data.split("#");

               if(element[0]=="Success")
               {
                 //console.log(element[1]);
                 $('#message').html("<p>"+element[1]+"</p>");

                 
               }
               else
               {
                 //console.log(element[1]);
                $('#message').html("<p>"+element[1]+"</p>");
               }

            }
         })
         
         

      });

      $(document).on('click','.remove_report',function(e){
         e.preventDefault();

          var id = $(this).attr("id");
          
           $.ajax({
              type:'POST',
              url:'ajax_circular.php',
              data: {action:'remove_report',notice_id:id},
              
              success:function(data){
                 console.log(data);
                var element = data.split("#");

                 if(element[0]=="success")
                 {
                   console.log(element[1]);
                    $('.circular_notice_edit').hide();
                    $('.circular_notice').show();
                   
                    


                 }
                 else
                 {
                  console.log(element[1]);
                 }

              }
           })
         

      });

      
    </script>