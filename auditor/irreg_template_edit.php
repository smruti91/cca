
<?php

   $sql_para  = " SELECT * FROM cca_para_2b WHERE para_id = '".$_POST['para_id']."' AND mngplan_id = '".$_POST['mangPlan_id']."' AND version = 0 " ; 
             
   $sql_para_res   = mysqli_query($mysqli,$sql_para);
  
        while ($para_row  = mysqli_fetch_assoc($sql_para_res)) {
                  
                  $result[] = $para_row;
                  
                }         
       //print_r($result);
            foreach( $result as $para_row ){
                      //print_r($para_row);
                 ?>

             
                  <div class="after-add-more_<?php echo  $para_row['id'] ?> subdiv">
                    

                   <div class="row">
                   
                   <div class="col-md-3">
                     <label class="control-label">Irregularities noticed </label>
                   </div>

                   <div class="col-md-4" style="float:right;" >

                      <input type="button" class="btn btn-info view_<?php echo $para_row['id'] ?>" id="<?php echo $para_row['id'] ?>" name="view" value="View" onclick="show_div(this.id)">

                      <input type="button" class="btn btn-danger del_<?php echo $para_row['id'] ?>" id="<?php echo $para_row['id'] ?>" name="delete" value="Delete" onclick="del_irreg(this.id)" style=" display: none;">

                      <input type="button" class="btn btn-primary edit_<?php echo $para_row['id'] ?>" id="<?php echo $para_row['id'] ?>" name="edit_assesment" value="Edit" onclick="edit_irreg(this.id)" style=" display: none;">

                      <input type="submit" class="btn btn-primary update_<?php echo $para_row['id'] ?>" id="<?php echo $para_row['id'] ?>" name="update_assesment" value="Update" onclick="update_cmp(this.id)" style=" display: none;">
                      
                   </div>

                    </div>
                   <div class="row"  id="test_<?php echo $para_row['id'] ?>" style="margin: 5px; display: none;">

                     <div class="col-md-12">
                       
                       <div class="form-group  ">
                         <textarea  class="form-control irreg_notice" name="irreg_notice" id="irreg_notice<?php echo $para_row['id'] ?>"><?php echo $para_row['irreg_notice']; ?>  </textarea>
                          <input type="hidden" name="irreg_edit_id[]" value="<?php echo  $para_row['id'] ;?>" >
                       </div>
                     </div>
                     
                      <input type="hidden" name="Update_irreg" value="update" >
                   </div>
                     
                  </div>

                
                  <!-- Delete Modal HTML -->
                                                  <div id="deleteIrregeModal_<?php echo  $para_row['id'] ;?>" class="modal fade">
                                                    <div class="modal-dialog">
                                                      <div class="modal-content">
                                                        <div>
                                                          <div class="modal-header">            
                                                            <h4 class="modal-title">Delete Irregularities noticed </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                          </div>
                                                          <div class="modal-body">          
                                                             <p>To Delete this record clear the Irregularities noticed Field <br> through <b>Edit</b> option</p>
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