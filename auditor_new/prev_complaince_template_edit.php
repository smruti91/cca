
<?php

  //  include_once("../config.php");
  //  include("../common_functions.php");
  // include "header.php";
   $sql_para  = " SELECT * FROM cca_prev_audit_obs WHERE paragraph_id = '".$_POST['para_id']."' AND managePlan_id = '".$_POST['mangPlan_id']."' AND trans_no = 0 " ; 
    //echo $sql_para;             
   $sql_para_res   = mysqli_query($mysqli,$sql_para);
  
        while ($para_row  = mysqli_fetch_assoc($sql_para_res)) {
                  
                  $result[] = $para_row;
                  
                }         
       //print_r($result);
            foreach( $result as $para_row ){
                       $audit_type = $para_row['audit_type'];
                 ?>

             
                  <div class="after-add-more_<?php echo  $para_row['id'] ?> subdiv">
                     <button type="button" class="close" id="<?php echo  $para_row['id'] ;?>" style="color:red;" aria-label="Close" onClick= "del_cmp(this.id)" ><span aria-hidden="true">&times;</span></button><br>
                    <!--  <a style="color:red;" aria-label="Close" href="deleteComplainceModal_<?php echo  $para_row['id'] ;?>" ><span aria-hidden="true">&times;</span></a><br>
                      <a href="#deleteEmployeeModal_<?php echo $res['ID']; ?>" class="delete"  id="delete_<?php echo $res['ID']; ?>" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a> -->

                    <div class="row">
                      <div class="col-md-4">
                        <div class="col-md-4">
                          <label for="">Audit Type</label>
                        </div>
                        <div class="col-md-8">
                          <div class="form-group">
                            <select  class="form-control" name="audit_type[]" >
                              <option>  Audit Type</option>
                              <option  value="IR" <?php if($audit_type=='IR'){ ?> selected="selected" <?php } ?>>IR</option>
                              <option  value="IAR" <?php if($audit_type=='IAR'){ ?> selected="selected" <?php } ?>>IAR</option>
                              <option  value="EAR" <?php if($audit_type=='EAR'){ ?> selected="selected" <?php } ?>>EAR</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="col-md-4">
                          <label for="">Year</label>
                        </div>
                        <div class="col-md-8">
                          <input type="text" class="form-control" name="year[]"  id="year" value="<?php echo  $para_row['year'] ;?>" >
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="col-md-7">
                          <label for="">No. of objection paras </label>
                        </div>
                        <div class="col-md-5">
                          <input type="text" class="form-control" name="no_obs_para[]"  id="no_obs_para" value="<?php echo  $para_row['no_objctn_para'] ;?>" >
                        </div>
                      </div>
                    </div>
                    <div class="row" style="margin: 5px;">
                      <div class="col-md-6">
                        <label for="">Audit Observation </label>
                        <div class="form-group">
                          <textarea  class="form-control audit_obs1" name="audit_obs[]" id="audit_obs" row="5" style="height:150px" > <?php echo  $para_row['audit_obs'] ;?></textarea>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="">Complaince </label>
                        <div class="form-group">
                          <textarea  class="form-control complaince1 " name="complaince[]" id="complaince"  style="height:150px"  ><?php echo  $para_row['compliance'] ;?></textarea>
                        </div>
                        <input type="hidden" name="para_edit_id[]" value="<?php echo  $para_row['id'] ;?>" >
                      </div>
                          
                    </div>
                   
                  </div>

                
                  <!-- Delete Modal HTML -->
                                                  <div id="deleteComplainceModal_<?php echo  $para_row['id'] ;?>" class="modal fade">
                                                    <div class="modal-dialog">
                                                      <div class="modal-content">
                                                        <form>
                                                          <div class="modal-header">            
                                                            <h4 class="modal-title">Delete Previous Complaince </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                          </div>
                                                          <div class="modal-body">          
                                                            <p>Are you sure you want to delete this Complaince?</p>
                                                            <p class="text-warning"><small>This action cannot be undone.</small></p>
                                                          </div>
                                                          <div class="modal-footer">
                                                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                                                            <input type="hidden" value="<?php echo $res['ID']; ?>" class="recordid" />
                                                            <input type="button" class="btn btn-danger btn-dlt" value="Delete" id="<?php echo  $para_row['id']; ?>" onclick="delete_record(this.id)" />
                                                          </div>
                                                        </form>
                                                      </div>
                                                    </div>
                                                  </div>
              
           
                 <?php
            }

 ?>