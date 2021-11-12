<?php
  $sql_para  = " SELECT * FROM ".$table." WHERE para_id = '".$para_id."' AND mngplan_id = '".$_SESSION['mngplan_id']."' AND add_by =  '".$_SESSION['userid']."' AND version = 0 "; 
  $sql_para_res   = mysqli_query($mysqli,$sql_para);


 ?>
                
                  <p style="font-weight: 500;">C1. Advance position (individual)</p>
                        <table class="table table-striped table-bordered" id="report_tbl">
                            <thead>
                                <tr>
                                    <th >Sl.No</th>
                                    <th style="width:150px" >Name of the Official </th>
                                    <th style="width:150px">GPF / E-HRMS id  </th>
                                    <th style="width:150px">Scheme/Cash Book</th>
                                    <th style="width:165px">Amount Out standing as on </th>
                                    <th style="width:150px">Amount paid during the audit period</th>
                                    <th style="width:150px">Amount Adjusted during the audit period</th>
                                    <th style="width:160px">Balance as on</th>
                                    <th style="width:167px">Amount outstanding (Audit)</th>
                                    <th style="width:167px">Amount outstanding (Cash Book)</th>
                                    <th>Difference</th>
                                    
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
                                      <td> <?php echo $para_row['offical_name']; ?></td>
                                      <td> <?php echo $para_row['gpf_Ehrms_id'] ?></td>
                                      <td> <?php echo $para_row['scheme_cash_book']; ?></td>
                                      <td> <?php echo $para_row['amnt_outstd']; ?></td>
                                      <td> <?php echo $para_row['amnt_paid_atAudit']; ?></td>
                                      <td> <?php echo $para_row['amnt_adjusted']; ?></td>
                                      <td> <?php echo $para_row['balance_asOn']; ?></td>
                                      <td> <?php echo $para_row['amnt_outstd_audit']; ?></td>
                                      <td> <?php echo $para_row['amnt_outstd_cashBook']; ?></td>
                                      <td> <?php echo $para_row['amnt_outstd_audit']-$para_row['amnt_outstd_cashBook']; ?></td>
                                     
                                  </tr>

                                 <?php
                                   }
                                  ?>
                                
                            </tbody>
                         </table>
