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
    
   //echo $userid ;
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
            <h1>Circular Notice</h1>
              <div  id="alert_msg" ></div>
            <hr>
                   
               <div class="search-panel">
                
                   <form class="form-inline Circular_form" enctype="multipart/form-data" method="POST">
                     <div class="form-group">
                       <label>Subject:</label>
                       <!-- <input type="text" class="form-control" id="reviewer_name" style="width: 350px;"> -->
                       <select name="circular_subject" id="circular_subject" class="form-control" style="width: 200px;" required>
                          <option selected> Select Subject</option>
                             <?php
                                $sql = "SELECT * FROM `cca_circular_subject` WHERE status = 1 ";
                                $result = mysqli_query($mysqli,$sql);

                                while($row = mysqli_fetch_assoc($result))
                                {
                                  ?>
                                    <option value="<?php echo $row['sub_id']; ?>" ><?php  echo $row['sub_name']; ?></option>
                                  <?php
                                }
                              ?>
                          </select>
                     </div>
                     <div class="form-group circular_notice" >
                       <label style="margin-left: 25px;">Circular / Notice:</label>
                       <input type="file" class="form-control" name="circular_file" id="circular_file" style="width: 200px;" >
                     </div>

                     <div class="form-group circular_notice_edit" style="display: none;">
                       <label style="margin-left: 25px;">Uploded Circular / Notice:</label>

                       <a href="" class="uploaded_report" target="_blank" style="color: #191616"><i class="material-icons" data-toggle="tooltip" title="Circular Notice" style="color:#f25149; padding-left: 10px;" >picture_as_pdf</i>  Circular/Notice </a>
                       
                      <a href="" class="remove_report"  style="padding-left: 5px" ><i class="material-icons" data-toggle="tooltip" title="Remove Notice" style="color:#f25149; " >clear</i></a>

                     
                     </div>

                     <div class="form-group">
                       <label style="margin-left: 25px;">Circular / Date:</label>
                       <input type="date" class="form-control" name="circular_date" id="circular_date" required>
                     </div>
                     <input type="hidden" name="notice_edit_id" id="notice_id" value="">
                     <input type="hidden" name="user_id" id="user_id" value="<?php echo $userid; ?>">
                     <button type="submit" class="btn btn-default" id="circular_btn" style="margin-left: 25px;" value="" >Upload</button>
                   </form>
                 
               </div>
               <hr>
                
            <!-- Circular Notice  HTML -->
               <div class="row " >
                  <div class="col-md-12 ">
                    <div class="row" style="padding-bottom:10px;">
                     
                          <div class="tour_table">
                           <table class="table table-striped table-bordered" id="tableid">
                           <thead>
                             <tr>
                               <th>Sl.No</th>
                               <th>Subject Name</th>
                               <th>Circular Notice</th>
                               <th>Circular Date</th>
                               <th>Action</th>

                             </tr>
                           </thead>
                           <tbody>
                             <?php
                                $sql ="SELECT ntc.notice_id,sub.sub_name,ntc.doc_name,ntc.circular_date FROM `cca_circularnotice` ntc , `cca_circular_subject` sub WHERE ntc.sub_id = sub.sub_id AND user_id = '".$userid."' AND ntc.status =0";

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
                                          
                                          <td>
                                          <input type="button" name="edit" class="btn btn-warning edit" id="<?php echo $res['notice_id']; ?>" value="Edit">
                                          <input type="button" name="send" class="btn btn-danger delete" id="<?php echo $res['notice_id']; ?>" value="Delete">
                                          <input type="button" name="send" class="btn btn-primary send" id="<?php echo $res['notice_id']; ?>" value="Send">
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

      $(document).on('click','.edit',function(e){
         e.preventDefault();

          var id = $(this).attr("id");
         $.ajax({
            type:'POST',
            url:'ajax_circular.php',
            data: {action:'edit',notice_id:id},
            dataType:"json",
            success:function(data){
               console.log(data);

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
                    sessionStorage.reloadAfterPageLoad = true;
                    sessionStorage.message = element[1];
                    sessionStorage.type = element[0];

                    location.reload();
               }
               else
               {
                     sessionStorage.reloadAfterPageLoad = true;
                     sessionStorage.message = element[1];
                     sessionStorage.type = element[0];

                     location.reload();
               }

            }
         })
         
         

      });

      //delete circular 
      $(document).on('click','.delete',function(e){
         e.preventDefault();
         var id = $(this).attr("id");
      $.ajax({
         type:'POST',
         url:'ajax_circular.php',
         data: {action:'delete_report',notice_id:id},

         success:function(data){
         console.log(data);
          var element = data.split("#");
         if(element[0]=="Success")
           {
               sessionStorage.reloadAfterPageLoad = true;
               sessionStorage.message = element[1];
               sessionStorage.type = element[0];

              location.reload();
           }
         else
          {
               sessionStorage.reloadAfterPageLoad = true;
               sessionStorage.message = element[1];
               sessionStorage.type = element[0];
               console.log(data);
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

                 if(element[0]=="Success")
                 {
                   console.log(element[1]);
                    $('.circular_notice_edit').hide();
                    $('.circular_notice').show();
                    sessionStorage.reloadAfterPageLoad = true;
                    sessionStorage.message = element[1];
                    sessionStorage.type = element[0];
                   
                     
                 }
                 else
                 {
                  console.log(element[1]);
                  sessionStorage.reloadAfterPageLoad = true;
                  sessionStorage.message = element[1];
                  sessionStorage.type = element[0];
                 }

              }
           })
         

      });

      
    </script>