                            
           <?php
              $sql_para  = " SELECT * FROM cca_para_c WHERE paragraph_id = '".$_POST['para_id']."' AND mngplan_id = '".$_POST['mangPlan_id']."' AND version = 0 "; 
               $sql_para_res   = mysqli_query($mysqli,$sql_para);
                
                while ($para_row  = mysqli_fetch_assoc($sql_para_res)) {
                  //print_r($para_row);
                  $result[] = $para_row;
                  
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
                            <td> <input type="text" class="form-control" name="amut_outStanding_audit[]" value=" <?php echo $para_row['amnt_outstd_audit']; ?>" required> </td>
                            <td> <input type="text" class="form-control" name="amut_outStanding_cashBook[]" value=" <?php echo $para_row['amnt_outstd_cashBook']; ?>" required> </td>
                            <td> <input type="text" class="form-control" name="difference"> </td>
                            <td> <button class="btn btn-danger" onclick="remove_tr(this)" ><i class="fa fa-trash" aria-hidden="true"></button></td>
                            <!-- <input type="hidden" name="para_edit_id[]" value= -1 > -->
                          </tr>
 
                  <?php
                }

            ?>
                            



