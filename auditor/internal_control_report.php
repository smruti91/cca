<?php
  $sql_para  = " SELECT * FROM ".$table." WHERE para_id = '".$para_id."' AND mngplan_id = '".$_SESSION['mngplan_id']."' AND add_by =  '".$_SESSION['userid']."' AND version = 0 "; 
  $sql_para_res   = mysqli_query($mysqli,$sql_para);
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

  $result = [
      1 => "Strong",
      2 => "Moderate",
      3 => "Weak"
  ]

 ?>
                   <h3> Part IV. Observations and Recommendations</h3>
                  <p style="font-weight: 500;">A.   Internal Controls Review</p>
                        <table class="table table-striped table-bordered" id="report_tbl">
                            <thead>
                                <tr>
                                    <th style="width:20px" >Sl.No</th>
                                    <th style="width:250px" >Assesment Ascept </th>
                                    <th style="width:400px">Indication of Strong Controls  </th>
                                    <th style="width:400px">Indication of Weak Controls</th>
                                    <th style="width:165px">Assesment Result </th>
                                    <th style="width:150px">Recommendation for improvement</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                   $count =0;
                                    while ($para_row  = mysqli_fetch_assoc($sql_para_res)) {
                                      
                                          $count++;
                    
                                 ?>
                                  <tr>
                                      <td> <?php echo $count; ?></td>
                                      <td> <?php echo $item_lists[$para_row['assmnt_aspt']]; ?></td>
                                      <td> <?php echo $para_row['strong_ctrl'] ?></td>
                                      <td> <?php echo $para_row['weak_ctrl']; ?></td>
                                      <td> <?php echo $result[$para_row['assmnt_result']]; ?></td>
                                      <td> <?php echo $para_row['improvment']; ?></td>
                                      
                                  </tr>

                                 <?php
                                   }
                                  ?>
                                
                            </tbody>
                         </table>
