<?php
    session_start();
    include("../common_functions.php");
    include_once("../config.php");
    //print_r($_SESSION);
    include "header.php";
      $para_list = [
          
          9  => "cca_para_2a",
          10 => "cca_para_2b",
          13 => "cca_para_3c",
          14 => "cca_para_3c1",
          15 => "cca_para_3d",
          16 => "cca_para_4a",
          5  => "cca_para_5"
      ];
      ?>
     <style>
        div.main{
           height:100%;
        }
        .report{
            border:1px solid black;
            margin:20px;
            padding:20px;
            border-radius: 20px;
            background-color: #f4f6f6;
        }
    </style> 

         <div class="page-wrapper" 
              style="height: 100%;
              width: 90%;
              background-color: #fff;
              padding: 20px 30px;
              "
          >
             
           <div style="text-align:center; padding: 5px;">
               <h2>COMMON AUDIT REPORT </h2>
               <a href="print_audit_report.php">print</a>
           </div>
           <hr>
           <div class="report">
      <?php
      foreach ($para_list as $para_id => $table) {

           
      	   switch ($para_id) {
      	   	case 9:
      	   		include "prev_audit_obs_report.php";
      	   		break;
            case 10:
                include "persistent_irreg_report.php";
                break;
            case 13:
                include "details_advance_report.php";
                break;
            case 14:
                include "advance_position_report.php";
                break;
            case 15:
                include "record_maintenance_report.php";
                break;
            case 16:
                include "internal_control_report.php";
                break;
            case 5:
                include "result_audit_report.php";
                break;
      	   	
      	   
      	   }
      }
 ?>
</div>
  </div>
  </div>
  <?php include "footer.php"; ?>
</body>
</html>