
<?php

  //  include_once("../config.php");
  //  include("../common_functions.php");
  // include "header.php";
  $sql_para  = " SELECT * FROM cca_para_2a WHERE paragraph_id = '".$_POST['para_id']."' AND mngplan_id = '".$_POST['mangPlan_id']."' AND version = 0 "; 
   // echo $sql_para;             
   $sql_para_res   = mysqli_query($mysqli,$sql_para);
   
        while ($para_row  = mysqli_fetch_assoc($sql_para_res)) {
                  //print_r($para_row); 
                  $result[] = $para_row;
                  
                }         
      if(!isset( $result)){
        //echo 123;
          echo "<script> window.location = 'manage_auditreport.php';</script>";
      }
            foreach( $result as $para_row ){
                       $audit_type = $para_row['audit_type'];
                       //print_r( $para_row);
                 ?>

             
                  <div class="after-add-more_<?php echo  $para_row['id'] ?> subdiv">
                     <button type="button" class="close" id="<?php echo  $para_row['id'] ;?>" style="color:red;" aria-label="Close" onClick= "del_cmp(this.id)" ><span aria-hidden="true">&times;</span></button><br>
                  

                    <div class="row">
                      <div class="col-md-6 lbl">
                        <div class="col-md-4">
                          <label for="">Audit Type</label>
                        </div>
                        <div class="col-md-8">
                          <div class="form-group">
                            <select  class="form-control" name="audit_type[]" required>
                              <option>  Audit Type</option>
                              <option  value="IR" <?php if($audit_type=='IR'){ ?> selected="selected" <?php } ?>>IR</option>
                              <option  value="IAR" <?php if($audit_type=='IAR'){ ?> selected="selected" <?php } ?>>IAR</option>
                              <option  value="EAR" <?php if($audit_type=='EAR'){ ?> selected="selected" <?php } ?>>EAR</option>
                            </select>
                          </div>
                        </div>
                      </div>
                     <div class="col-md-6 lbl">
                        <div class="col-md-7">
                          <label for="">Audit Report No.</label>
                        </div>
                        <div class="col-md-5">
                          <input type="text" class="form-control" name="audit_no[]"  id="audit_no" value="<?php echo  $para_row['audit_no'] ;?>">
                        </div>
                      </div>
                   
                    </div>
                    <div class="row">
                       <div class="col-md-6 lbl">
                         <div class="col-md-4">
                           <label for="">Year</label>
                         </div>
                         <div class="col-md-8">
                           <input type="text" class="form-control" name="year[]"  id="year" value="<?php echo  $para_row['year'] ;?>">
                         </div>
                       </div>
                       <div class="col-md-6 lbl">
                         <div class="col-md-7">
                           <label for="">No. of objection paras </label>
                         </div>
                         <div class="col-md-5">
                           <input type="text" class="form-control" name="no_obs_para[]"  id="no_obs_para" value="<?php echo  $para_row['no_objctn_para'] ;?>">
                         </div>
                       </div>
                    </div>
                    <br>

                    <div class="row" style="margin: 5px;">
                      <div class="col-md-6 lbl">
                        <label for="">Audit Observation </label>
                        <div class="form-group">
                          <textarea  class="form-control audit_obs1" name="audit_obs[]" id="audit_obs_<?php echo  $para_row['id'] ?>" style="height:150px" > <?php echo  $para_row['audit_obs'] ;?></textarea>
                        </div>
                      </div>
                      <div class="col-md-6 lbl">
                        <label for="" style="margin-left: 15px;">Complaince </label>
                        <div class="form-group">
                          <textarea  class="form-control complaince1 " name="complaince[]" id="complaince_<?php echo  $para_row['id'] ?>"  style="height:150px"  ><?php echo  $para_row['compliance'] ;?></textarea>
                        </div>
                        <input type="hidden" name="para_edit_id[]" value="<?php echo  $para_row['id'] ;?>" >
                      </div>
                           <input type="hidden" name="Update_complaince" value="Update" >
                    </div>
                   
                  </div>

                
                  <!-- Delete Modal HTML -->
                                                  <div id="deleteComplainceModal_<?php echo  $para_row['id'] ;?>" class="modal fade">
                                                    <div class="modal-dialog">
                                                      <div class="modal-content">
                                                       
                                                          <div class="modal-header">            
                                                            <h4 class="modal-title">Delete Irregularities noticed </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                          </div>
                                                          <div class="modal-body">          
                                                            <p>Are you sure you want to delete this Irregularities noticed?</p>
                                                            <p class="text-warning"><small>This action cannot be undone.</small></p>
                                                          </div>
                                                          <div class="modal-footer">
                                                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                                                            <input type="hidden" value="<?php echo $res['ID']; ?>" class="recordid" />
                                                            <input type="button" class="btn btn-danger btn-dlt" value="Delete" id="<?php echo  $para_row['id']; ?>" onclick="delete_record(this.id)" />
                                                          </div>
                                                       
                                                      </div>
                                                    </div>
                                                  </div>
              
           
                 <?php
            }

 ?>