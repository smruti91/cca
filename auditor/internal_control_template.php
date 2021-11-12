                 <?php

                      
                      $sql_para  = " SELECT * FROM cca_para_4a WHERE para_id = '".$_SESSION['paraid']."' AND mngplan_id = '".$manageplan_id."' AND version = 0 ";
                      //echo $sql_para;
                      $sql_para_res   = mysqli_query($mysqli,$sql_para);
                      //print_r($sql_para_res);
                       $row_cnt = $sql_para_res->num_rows;
                      
                       $items = array();
                       while ($item_row  = mysqli_fetch_assoc($sql_para_res)) {
                                  // print_r($item_row); 
                                   $items[] = $item_row['assmnt_aspt'];
                                  
                                 }   ;
                      // remove selected item from dropdown
                                       $item_lists = [
                                                       0 => "Select Assessment Aspect",
                                                       1 => "Cash Managemet",
                                                       2 => "Bank Reconciliation",
                                                       3 => "Funds/Grants Management",
                                                       4 => "Asset Managment",
                                                       5 => "Financial Record Management",
                                                       6 => "Budget Managment",
                                                       7 => "Separation of duties",
                                                       8 => "Financial performance review by concerned officials",
                                                       9 => "Access to Gov. Policies & Procedures on Accounts maintaenance and other related matters",
                                                       10 => "Auditee Policies and Procedures"
                                                     ];
                                                     
                                                     //$res = array_diff_key($items,$item_lists);
                                                     $rslt = array_intersect_key( $item_lists , array_flip( $items ) );
                                                     $res = array_diff_key($item_lists,$rslt);
                                                     //print_r($res);
                                                     if(count($res) == 1){
                                                       echo "<p style='color:red'>All Fields are selected</p>";
                                                       $msg = "display:none";
                                                     }

                  ?>   
                  <div class="after-add-more subdiv" id="subdiv" style="<?php echo $msg; ?>">
                    <div class="row">
                      <div class="col-md-12 lbl">
                        
                        <div class="col-md-5">
                          <div class="form-group">
                            <select  class="form-control assessment"  id="asmnt_" name="assessment[]" required>
                                 <?php foreach($res as  $key => $item) {
                                                              
                                       ?>
                                         <option value="<?php echo $key ?>" > <?php echo $item ?></option>
                                                        
                                <?php } ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-2">
                        
                        </div>
                        <div class="col-md-2">
                           <!--  <input type="button" class="btn btn-info" id="" name="view" value="View" onclick="show_div(this.id)"> -->
                           
                        </div>
                      </div>

                    </div>

                   <div class ="field-control" id="test_" style="display:none">
                    <div class="row" style="margin: 5px;">
                      <div class="col-md-6 lbl">
                        <label for="">Indication of Strong Controls  </label>
                        <div class="form-group">
                           <textarea  class="form-control strong" name="strong[]" id="strong_"></textarea>
                        </div>
                      </div>
                      <div class="col-md-6 lbl">
                        <label for="" style="margin-left: 15px;">Indication of Weak Controls </label>
                        <div class="form-group ">
                          <textarea  class="form-control weak" name="weak[]" id="weak"></textarea>
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
                               <input type="checkbox"  name = "checklist[]" value="1">Strong
                             </label>
                             <label class="checkbox-inline">
                               <input type="checkbox"  name = "checklist[]"  value="2">Moderate
                             </label>
                             <label class="checkbox-inline">
                               <input type="checkbox"   name = "checklist[]" value="3">Weak
                             </label>
                        </div>
                      </div>

                      <div class="col-md-6 lbl">
                        <label for="">Recommendation for improvement  </label>
                        <div class="form-group" >
                           <textarea  class="form-control improvement1 txt" name="improvement[]" id="improvement" style="width: 490px;height: 120px;"></textarea>
                        </div>
                      </div>
                      
                    </div>
                      <input type="hidden" name="save_assesment" value="save" >
                     </div>
                   
                  </div>

               