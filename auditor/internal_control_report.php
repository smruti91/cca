<?php
  $sql_para  = " SELECT * FROM ".$table." WHERE para_id = '".$para_id."' AND mngplan_id = '".$_SESSION['mngplan_id']."' AND add_by =  '".$_SESSION['userid']."' AND version = 0 "; 
  $sql_para_res   = mysqli_query($mysqli,$sql_para);


 ?>
                   <h3> Part IV. Observations and Recommendations</h3>
                  <p style="font-weight: 500;">A.   Internal Controls Review</p>
                        <table class="table table-striped table-bordered" id="report_tbl">
                            <thead>
                                <tr>
                                    <th >Sl.No</th>
                                    <th style="width:150px" >Assesment Ascept </th>
                                    <th style="width:150px">Indication of Strong Controls  </th>
                                    <th style="width:150px">Indication of Weak Controls</th>
                                    <th style="width:165px">Assesment Result </th>
                                    <th style="width:150px">Recommendation for improvement</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                   $count =0;
                                    while ($para_row  = mysqli_fetch_assoc($sql_para_res)) {
                                 
                                          print_r( $para_row);
                                          $count++;
                    
                                 ?>
                                  <tr>
                                      <td> <?php echo $count; ?></td>
                                      <td> <?php echo $para_row['offical_name']; ?></td>
                                      <td> <?php echo $para_row['gpf_Ehrms_id'] ?></td>
                                      <td> <?php echo $para_row['scheme_cash_book']; ?></td>
                                      <td> <?php echo $para_row['amnt_outstd']; ?></td>
                                      <td> <?php echo $para_row['amnt_paid_atAudit']; ?></td>
                                      
                                  </tr>

                                 <?php
                                   }
                                  ?>
                                
                            </tbody>
                         </table>
