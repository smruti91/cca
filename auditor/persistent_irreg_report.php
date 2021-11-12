<?php
  $sql_para  = " SELECT `irreg_notice` FROM ".$table." WHERE para_id = '".$para_id."' AND mngplan_id = '".$_SESSION['mngplan_id']."' AND add_by =  '".$_SESSION['userid']."'          AND version = 0 "; 
  //echo $sql_para;
  $sql_para_res   = mysqli_query($mysqli,$sql_para);


 ?>
                 
                  <p style="font-weight: 500;">B. Persistent Irregularities</p>
                        <table class="table table-striped table-bordered" id="report_tbl">
                            <thead>
                                <tr>
                                    <th style="width:20px">Sl.No</th>
                                    <th >Irregularities noticed</th>
                                    
                                    
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
                                      <td> <?php echo $para_row['irreg_notice']; ?></td>
                                      
                                  </tr>

                                 <?php
                                   }
                                  ?>
                                
                            </tbody>
                         </table>