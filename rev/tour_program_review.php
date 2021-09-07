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

.modal-body{
  height: 500px;
  width: 500px;
}
.uploaded_file a {
  padding-top: 1px;
}

</style>

<div id="wrapper">
<?php include "leftpanel.php";?>
 <div id="page-wrapper">
 <div class="container-fluid d-flex">    
 
       <div class="row table-row">
             <div class="col-sm-6 ">
                 <h3 class="t-program"><b>Tour Program</b></h3>
             </div>
             
        </div>
         
        
        <div class="row " >
           <div class="col-sm-12 ">
             <div class="row" style="padding-bottom:10px;">
                   <div class="tour_table">
                    <table class="table  table-striped table-bordered" id="tableid">
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
                        
                        <th>Audit Report  </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                         
                         $result = mysqli_query($mysqli, "SELECT * FROM cca_tour_details  where status = 'accept' "); 
                         if(mysqli_num_rows($result)>0)
                         {
                           $count=0;
                           while($res = mysqli_fetch_array($result) )
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

                                           }
                                    ?></td>
                                   <td><?php echo $res_query['Name'];?></td>
                                   <td><?php echo date("d-m-Y",strtotime($res['dt_commencent']));?></td>
                                   <td><?php echo date("d-m-Y",strtotime($res['dt_completion']));?></td>
                                   <td><?php echo $res['purpose'];?></td>
                                   <td><?php echo $res['distance'];?></td>
                                   <td>

                                   <?php
                                        $sql = "SELECT doc.document_name,doc.id as doc_id,td.audit_report FROM cca_tour_details td , cca_tour_document doc WHERE td.ID = doc.document_id AND td.ID = ".$res['ID'];
                                        $query = mysqli_query($mysqli,$sql);
                                        $doc_row = mysqli_fetch_array($query);
                                        //print_r($doc_row);
                                       // echo BASE_URL;
                                        if( empty($doc_row['document_name']) )
                                        {
                                          ?>
                                            <a href="#myModal_<?php echo $res['ID']; ?>" type="button" class="btn btn-primary" data-toggle="modal" id="report" style="color: #fff" >Report <i class="material-icons" data-toggle="tooltip" title="file_upload" >file_upload</i></a>

                                            <a href="#cancelModal_<?php echo $res['ID'];?>" type="button" class="btn btn-danger" data-toggle="modal" style="color:#fff; margin-top: 5px;" >Cancel</a>
                                            
                                            <!-- <a class="waves-effect waves-light btn-small gray" style="color: #fff" >cancel</a> -->
                                          <?php
                                        }
                                        else
                                        {
                                            $doc_path = $doc_row['document_name'];
                                            $file_path = BASE_URL."Auditor/".$doc_path;
                                            $file_img_view  = BASE_URL."images/document_pdf.png";
                                            $file_img_delete = BASE_URL."images/cross.png";
                                            $audit_report = $doc_row['audit_report'];
                                            //echo $audit_report;
                                            if($audit_report==0)
                                            {
                                            ?>
                                             <!--  <a href="<?php echo $file_path?>"><i class="material-icons" data-toggle="tooltip" title="Edit" >&#xE254;</i></a> -->

                                              <a href="#" id="<?php echo $res['ID'] ?>" class="edit_report" ><i class="material-icons" data-toggle="tooltip" title="Edit" style="">&#xE254;</i></a>

                                              <a href="#" class="send"  id="<?php echo $res['ID']; ?>" onclick="submit_report(this.id);" ><i class="material-icons" data-toggle="tooltip" title="Save" style="color: #24a0e3">send</i></a>

                                            <?php
                                          }
                                          else{
                                            ?>
                                              <a href="<?php echo $file_path?>" target="_blank"><i class="material-icons" data-toggle="tooltip" title="picture_as_pdf" style="font-size: 3rem; color:#f25149;" >picture_as_pdf</i></a>
                                            <?php
                                          }
                                        }

                                     
                                      ?>

                                      <!--- Cancel Modal ---->

                                           <div class="modal custom fade" id="cancelModal_<?php echo $res['ID']; ?>">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                           
                                           <div class="modal-header">
                                               <div><b>Reasion For Cancel Tour</b></div>
                                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                 </button>
                                           </div>
                                          <!-- Modal body -->
                                          <div class="modal-body">
                                          
                                          
                                         <form  class="cancelForm" method="post"  enctype="multipart/form-data">

                                          <div class="form-group">
                                              <label> Comments :   </label>
                                              <textarea  class="form-control cancel_comment" name="cancel_comment" rows="3"></textarea>
                                          </div>

                                          <div class="form-group">
                                           <input type="submit" name="submit"  value="Send" class="btn btn-info uploadBtn" />
                                          </div>
                                          
                                      <input type="hidden" name="user_id"  value="<?php echo $res['ID']; ?>">
                                      <input type="hidden" name="action" value="cancel_report" >
                                          
                                         </form>
                                         <!-- <div id="loader-icon" style="display:none;"><img src="loader.gif" /></div> -->
                                        
                                       </div>
                                       <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          
                                       </div>
                                      </div>
                                         


                                        </div>
                                      </div>

                                      <!--- Cancel Modal End ---->
                                    

                             <!-- Upload Modal HTML -->
                                  <div class="modal custom fade" id="myModal_<?php echo $res['ID']; ?>">
                             <div class="modal-dialog">
                               <div class="modal-content">
                                  
                                  <div class="modal-header">
                                      <div><b>Upload Audit Report</b></div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                        </button>
                                  </div>
                                 <!-- Modal body -->
                                 <div class="modal-body">
                                 
                                 
                                <form  class="reportForm" method="post"  enctype="multipart/form-data">
                                  <div class="form-group">
                                  <label> Final Review  </label>
                                   <textarea  class="form-control final_review" name="final_review" rows="3"></textarea>
                                 </div>
                                  <div class="form-group">
                                  <label> Final Distance  </label>
                                  <input type="number" name="distance" class="form-control" id="distance" value="<?php echo $res['distance']; ?>" placeholder="Final Distance" >
                                 </div>
                                 <div class="form-group upload_file">
                                  <label>File Upload</label>
                                  <input type="file" name="tourReport" class="form-control-file report_file"  accept=".jpg, .png , .pdf" />
                                 </div>
                                 <input type="hidden" name="user_id"  value="<?php echo $res['ID']; ?>">
                                 <div>
                                   <a href="" style="display: none;" ></a>
                                 </div>
                                 <div class="progress" style="display: none;">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                 </div>
                                 <div class="form-group uploaded_file ">
                                    <div class="uploaded_report" style="display: none;" >
                                       <label>Uploaded Report :</label>
                                        <a href="" class="uploaded_report" target="_blank"><i class="material-icons" data-toggle="tooltip" title="picture_as_pdf" style="color:#f25149; " >picture_as_pdf</i> Tour Report</a>
                                       
                                          <a href="" class="remove_report" id="<?php echo $res['ID']; ?>" style="padding-left: 5px" ><i class="material-icons" data-toggle="tooltip" title="picture_as_pdf" style="color:#f25149; " >clear</i></a>

                                    </div>
                                    
                                 </div>
                                 
                                 <div class="uploadStatus"> </div>

                                 <div class="form-group">
                                  <input type="submit" name="submit"  value="Upload" class="btn btn-info uploadBtn" />
                                 </div>
                                  <div class="actionBtn">
                                    <a class="btn btn-warning view_report" role="button" target="_blank" style="color: #fff" >Preview Pdf</a>
                                    <button class="btn btn-primary" id="<?php echo $res['ID']; ?>" onclick="submit_report(this.id)" >Submit</button>
                                   
                                  </div>
                                 <input type="hidden" name="document_id"  value="<?php echo $res['ID']; ?>">
                                 <div id="targetLayer" style="display:none;"></div>
                                </form>
                                <!-- <div id="loader-icon" style="display:none;"><img src="loader.gif" /></div> -->
                               
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                 <button type="button" class="btn btn-primary" id="<?php echo $res['ID']; ?>" 
                                  onclick="save_report(this.id)" >Save Report</button>
                              </div>
                             </div>
                                


                               </div>
                             </div>
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

$(document).on('submit','.reportForm',function(e){
  
      e.preventDefault();
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $(".progress").show();
                        $(".progress-bar").width(percentComplete + '%');
                        $(".progress-bar").html(percentComplete+'%');
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            url: 'fetch_tour_data.php',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $(".progress-bar").width('0%');
                //$('#uploadStatus').html('<img src="images/loading.gif"/>');
            },
            error:function(){
                $('.uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
            },
            success: function(resp){
              res = resp.split("#");
              console.log(res);
                if(res[0] == 'Success'){
                  console.log("hello");
                    //$('#uploadForm')[0].reset();view_pdf
                    $('.uploadStatus').html('<p style="color:#28A74B;">File has uploaded successfully!</p>');
                    $('.view_report').attr('href',res[1]);
                    $('.uploadBtn').hide();
                    setTimeout(()=>{
                      $(".uploadStatus").hide()
                    },3000);
                    

                }else if(resp == 'err'){
                    $('.uploadStatus').html('<p style="color:#EA4335;">Please select a valid file to upload.</p>');
                }
            }
        });
});

$(document).on('submit','.cancelForm',function(e){
   e.preventDefault();

   $.ajax({

    type: 'POST',
    url: 'fetch_tour_data.php',
    data: new FormData(this),
    contentType: false,
    cache: false,
    processData:false,
    success: function(res){
      console.log(res);
      if(data == 'Success')
      {
        location.reload();
      }
      else
      {
        console.log("data");
      }
    }
   })
});

$(document).on('click','.edit_report',function(){
   var id = $(this).attr("id");
   //console.log(id);
   $.ajax({
    url: "fetch_tour_data.php",
    method: "POST",
    data: {action:"edit_report",user_id:id},
    dataType:"json",
    success: function(data){
      //console.log(data.file_path);
      $('.uploaded_report').show();
      $('.upload_file').hide();
      $('.uploadBtn').hide();
      $('.final_review').val(data.remark);
      $('.uploaded_report').attr('href',data.file_path);
      //$('.report_file').val(data.file_path);
      $('#myModal_'+id).modal('show');

    }
   })
   //var final_review = document.getElementsByClassName("final_review");
   
});
$(document).on('click','.remove_report',function(e){
  e.preventDefault();
  var id = $(this).attr("id");
  //alert(id);
  $.ajax({
     url:"fetch_tour_data.php",
     method:"POST",
     data:{action:"remove_report",user_id:id},
    
     success:function(data){
      //var res = $.parseJSON(data);
      console.log(data)
       if(data == "success")
       {
          $('.uploaded_file').hide();
          $('.upload_file').show();
          $('.uploadBtn').show();

       }
        else{
          //console.log(error);
        }

     }
   });
})


function submit_report(id)
{
    $.ajax({
     url:"fetch_tour_data.php",
     method:"POST",
     data:{action:"submit_report",user_id:id},
    
     success:function(data){
     
      location.reload();
      
     }
   });
}
function save_report(id)
{
  location.reload();
  
}


</script>