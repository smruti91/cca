<?php

   $sql_para  = " SELECT * FROM cca_para_4a WHERE para_id = '".$_POST['para_id']."' AND mngplan_id = '".$_POST['mangPlan_id']."' AND version = 0 ";

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
           $assment = $para_row['assmnt_aspt'];
       	?>
       	   <div class="after-add-more subdiv" id="subdiv_<?php echo $para_row['id'] ?>">
                    <div class="row">
                      <div class="col-md-12 lbl">
                        
                        <div class="col-md-5">
                          <div class="form-group">
                            <select  class="form-control assessment"  id="asmnt_" name="assessment[]" required>
                              <option value="0"  >  Select Assessment Aspect</option>
                              <option value="1" <?php if($assment == 1){ ?> selected="selected" <?php } ?> >Cash Managemet</option>
                              <option value="2" <?php if($assment == 2){ ?> selected = "selected" <?php } ?>>Bank Reconciliations</option>
                              <option value="3" <?php if($assment == 3){ ?> selected = "selected" <?php } ?>>Funds/Grants Management</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-2">
                        
                        </div>
                        <div class="col-md-2" style="float:right;" >
                            <input type="button" class="btn btn-info" id="<?php echo $para_row['id'] ?>" name="view" value="View" onclick="show_div(this.id)">
                          <input type="button" class="btn btn-danger" id="<?php echo $para_row['id'] ?>" name="delete" value="Delete" onclick="del_asmnt(this.id)">
                           
                        </div>
                      </div>

                    </div>

                   <div class ="field-control" id="test_<?php echo $para_row['id'] ?>" style="display:none">
                    <div class="row" style="margin: 5px;">
                      <div class="col-md-6 lbl">
                        <label for="">Indication of Strong Controls  </label>
                        <div class="form-group">
                           <textarea  class="form-control strong" name="strong[]" id="strong_<?php echo $para_row['id'] ?>"> <?php echo $para_row['strong_ctrl'] ?> </textarea>
                        </div>
                      </div>
                      <div class="col-md-6 lbl">
                        <label for="" style="margin-left: 15px;">Indication of Weak Controls </label>
                        <div class="form-group ">
                          <textarea  class="form-control weak" name="weak[]" id="weak_<?php echo $para_row['id'] ?>"><?php echo $para_row['weak_ctrl'] ?></textarea>
                        </div>
                      </div>
                      
                    </div>

                    <div class="row" >
                      <div class="col-md-6 lbl" style="margin-top: 35px;">
                        <div class="col-md-4">
                           <label for="">Assessment Result </label>
                        </div>
                        
                        <div class=" col-md-8 form-group ">
                             <label class="checkbox-inline ">
                               <input type="checkbox"  name = "checklist[]" value="1" <?php if($para_row['assmnt_result']==1){ echo " checked=\"checked\"";  } ?> >Strong
                             </label>
                             <label class="checkbox-inline">
                               <input type="checkbox"  name = "checklist[]"  value="2"  <?php if($para_row['assmnt_result']==2){ echo " checked=\"checked\"";  } ?> >Moderate
                             </label>
                             <label class="checkbox-inline">
                               <input type="checkbox"   name = "checklist[]" value="3"  <?php if($para_row['assmnt_result']==3){ echo " checked=\"checked\"";  } ?> >Weak
                             </label>
                        </div>
                      </div>

                      <div class="col-md-6 lbl">
                        <label for="">Recommendation for improvement  </label>
                        <div class="form-group" >
                           <textarea  class="form-control improvement1 txt" name="improvement[]" id="improvement" style="width: 490px;height: 120px;">
                           	<?php echo $para_row['improvment'] ?>
                           </textarea>
                        </div>
                      </div>
                      
                    </div>
                     <input type="hidden" name="para_edit_id[]" value="<?php echo  $para_row['id'] ;?>" >
                      <input type="hidden" name="update_assesment" value="update" >
                     </div>
                   
                  </div>

                   <!-- Delete Modal HTML -->
                                                  <div id="deleteComplainceModal_<?php echo  $para_row['id'] ;?>" class="modal fade">
                                                    <div class="modal-dialog">
                                                      <div class="modal-content">
                                                       
                                                          <div class="modal-header">            
                                                            <h4 class="modal-title">Delete Assesment Aspect </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                          </div>
                                                          <div class="modal-body">          
                                                            <p>Are you sure you want to delete this Assesment Aspect?</p>
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