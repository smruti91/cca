<?php
  $sql_para  = " SELECT `audit_type`,`audit_no`,`year`,`no_objctn_para`,`audit_obs`,`compliance` FROM ".$table." WHERE para_id = '".$para_id."' AND mngplan_id = '"         .$_SESSION['mngplan_id']."' AND add_by =  '".$_SESSION['userid']."' AND version = 0 "; 
  $sql_para_res   = mysqli_query($mysqli,$sql_para);


 ?>
                  <h3>Part: II.   Previous Audit Compliance</h3>
                  <p style="font-weight: 500;">A. Previous Audit Observations and Compliance</p>
                        <table class="table table-striped table-bordered" id="report_tbl">
                            <thead>
                                <tr>
                                    <th style="width:20px">Sl.No</th>
                                    <th style="width:95px">Audit Type</th>
                                    <th style="width:105px;text-align: center;">No - Year </th>
                                    <th style="width:185px;">No. of objection paras</th>
                                    <th style="width: 450px;text-align: center;">Audit Observation</th>
                                    <th style="text-align: center;">Compliance</th>
                                    
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
                                      <td> <?php echo $para_row['audit_type']; ?></td>
                                      <td> <?php echo $para_row['audit_no'] .' - ' . $para_row['year']; ?></td>
                                      <td> <?php echo $para_row['no_objctn_para']; ?></td>
                                      <td> <?php echo $para_row['audit_obs']; ?></td>
                                      <td> <?php echo $para_row['compliance']; ?></td>
                                  </tr>

                                 <?php
                                   }
                                  ?>
                                
                            </tbody>
                         </table>