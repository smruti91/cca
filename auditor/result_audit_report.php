<?php
  $sql_para  = " SELECT `audit_result` FROM ".$table." WHERE para_id = '".$para_id."' AND mngplan_id = '".$_SESSION['mngplan_id']."' AND add_by =  '".$_SESSION['userid']."'          AND version = 0 "; 
    $sql_para_res   = mysqli_query($mysqli,$sql_para);


 ?>
                 
                  <p style="font-weight: 500;">B. Audit Result</p>
                        <table class="table table-striped table-bordered" id="report_tbl">
                            <thead>
                                <tr>
                                    <th style="width:20px">Sl.No</th>
                                    <th >Audit Result</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                   $count =0;
                                    while ($para_row  = mysqli_fetch_assoc($sql_para_res)) {
                                 
                                           //print_r( $para_row);
                                          $count++;
                    
                                 ?>
                                  <tr>
                                      <td> <?php echo $count; ?></td>
                                      <td> <?php echo $para_row['audit_result']; ?></td>
                                      
                                  </tr>

                                 <?php
                                   }
                                  ?>
                                
                            </tbody>
                         </table>