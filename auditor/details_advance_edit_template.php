                            
           <?php
              $sql_para  = " SELECT * FROM cca_para_3c WHERE paragraph_id = '".$_POST['para_id']."' AND mngplan_id = '".$_POST['mangPlan_id']."' AND version = 0 "; 
               $sql_para_res   = mysqli_query($mysqli,$sql_para);
                
                while ($para_row  = mysqli_fetch_assoc($sql_para_res)) {
                  //print_r($para_row);
                  $result[] = $para_row;
                  
                } 

                if(!isset( $result)){
                         //echo 123;
                          echo "<script> window.location = 'manage_auditreport.php';</script>";
                    }
                
                //print_r($result);
                
                foreach( $result as  $para_row ) {
                   //echo $para_row['advnc_outstd_asOn'];
                  ?>
                         
                       <tr>
                            <td>&nbsp;</td>
                            <td> <input type="text" class="form-control" name="advnc_outstanding_asOn[]" value=" <?php echo $para_row['advnc_outstd_asOn']; ?>" required> </td>
                            <td> <input type="text" class="form-control" name="cashBook[]" value=" <?php echo $para_row['cash_book']; ?>" required> </td>
                            <td> <input type="text" class="form-control" name="amut_outStanding[]" value=" <?php echo $para_row['amnt_outstd']; ?>" required> </td>
                            <td> <input type="text" class="form-control" name="amut_paid_audit_period[]" value=" <?php echo $para_row['amnt_paid_atAudit']; ?>" required> </td>
                            <td> <input type="text" class="form-control" name="amut_adjust[]" value=" <?php echo $para_row['amnt_adjusted']; ?>" required> </td>
                            <td> <input type="text" class="form-control" name="balance_asOn[]" value=" <?php echo $para_row['balance_asOn']; ?>" required> </td>
                            <td> <input type="text" class="form-control amut_outStanding_audit" name="amut_outStanding_audit[]" value=" <?php echo $para_row['amnt_outstd_audit']; ?>" required> </td>
                            <td> <input type="text" class="form-control outStanding_cashBook" name="amut_outStanding_cashBook[]" value=" <?php echo $para_row['amnt_outstd_cashBook']; ?>" required> </td>
                            <td> <input type="text" class="form-control difference" name="difference"> </td>
                            <td> <!-- <button class="btn btn-danger" id="<?php echo  $para_row['id'] ;?>" onclick="remove(this.id)" ><i class="fa fa-trash" aria-hidden="true"></button> -->
                              <a href="javascript:void(0)" class="delete"  id="<?php echo $para_row['id'] ?>" onclick="remove(this.id)" ><i class="fa fa-trash" aria-hidden="true"></i></a>

                               <!-- Delete Modal HTML -->
                                                          <div id="deleteAdvanceModal_<?php echo $para_row['id'] ?>" class="modal fade">
                                                            <div class="modal-dialog">
                                                              <div class="modal-content">
                                                               
                                                                  <div class="modal-header">            
                                                                    <h4 class="modal-title">Delete Details Advance </h4>
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
                            </td>
                            <input type="hidden" name="para_edit_id[]" value=" <?php  echo $para_row['id']; ?> ">
                         
                         </tr>

                          
 
                  <?php
                }

            ?>
                            


