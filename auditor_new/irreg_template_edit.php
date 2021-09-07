
<?php

   $sql_para  = " SELECT * FROM cca_persistent_irreg WHERE paragraph_id = '".$_POST['para_id']."' AND managePlan_id = '".$_POST['mangPlan_id']."' AND trans_no = 0 " ; 
    //echo $sql_para;             
   $sql_para_res   = mysqli_query($mysqli,$sql_para);
  
        while ($para_row  = mysqli_fetch_assoc($sql_para_res)) {
                  
                  $result[] = $para_row;
                  
                }         
       //print_r($result);
            foreach( $result as $para_row ){
                      //print_r($para_row);
                 ?>

             
                  <div class="after-add-more_<?php echo  $para_row['id'] ?> subdiv">
                     <button type="button" class="close" id="<?php echo  $para_row['id'] ;?>" style="color:red;" aria-label="Close" onClick= "del_cmp(this.id)" ><span aria-hidden="true">&times;</span></button><br>
                  

                   <div class="col-md-3">
                     <label class="control-label">Irregularities noticed </label>
                   </div>
                    
                   <div class="row" style="margin: 5px;">

                     <div class="col-md-12">
                       
                       <div class="form-group  ">
                         <textarea  class="form-control irreg_notice" name="irreg_notice[]" id="audit_obs"><?php echo $para_row['irreg_notice']; ?>  </textarea>
                          <input type="hidden" name="irreg_edit_id[]" value="<?php echo  $para_row['id'] ;?>" >
                       </div>
                     </div>
                     
                     
                   </div>
                     
                  </div>

                
                  <!-- Delete Modal HTML -->
                                                  <div id="deleteComplainceModal_<?php echo  $para_row['id'] ;?>" class="modal fade">
                                                    <div class="modal-dialog">
                                                      <div class="modal-content">
                                                        <div>
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
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
              
           
                 <?php
            }

 ?>