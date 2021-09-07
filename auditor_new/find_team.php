<?php
session_start();

include("../common_functions.php");
include_once("../config.php");

    $plan_id = $_POST['id'];
      $findteam = generateSQL("SELECT * FROM `cca_team` WHERE `plan_id`=? AND fa_status=? AND reviewer_party_status!=?",array($plan_id,'Approved','Review'),false,$mysqli);
     
      $findplan = generateSQL("SELECT * FROM `cca_plan` WHERE `id` =?",array($plan_id),false,$mysqli);
      $findplan = reset($findplan);
      
?>

<?php include "header.php"; ?>
<div id="wrapper">
        <?php include "leftpanel.php";?>
        <div id="page-wrapper">	
        <div class="container-fluid text-center">    
        <div class="row content">
          <div class="col-sm-12 text-center"> 
          	<h1>Select Team</h1>
            <hr>
            <form class="form-horizontal" action="" method="post">
                <div class="form-group">
                <label class="control-label col-sm-2">Programme for the year:</label>
                <div class="col-sm-2">
                 <input type="disable" value="<?php echo $findplan['plan_name'];?>">
                </div>
              </div>

              <div class="form-group">
                  <label class="control-label col-sm-2">Party No:</label>
                  <div class="col-sm-2">
                    <select class="form-control" data-placeholder="Choose a Party" tabindex="1" name="party" id="party"  >
              
                      
              <option value=''>Select Party Number</option>
              
              <?php
                        foreach($findteam as $Queryteam)
            {?>

            <option value='<?php echo htmlentities($Queryteam['id']);?>'><?php echo htmlentities($Queryteam['team_name']);?></option>

            <?php }?>
            </select>
                  </div>
                </div>

                    <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                  <button type="button" class="btn btn-default" onclick="add_inst('<?php echo $plan_id; ?>')">Add Institution</button>
                </div>
              </div>
            </form>
            <script type="text/javascript">
               function add_inst(plan){
                var team = $("#party").val();
                $('<form action="add_organisation.php" method="post"><input type="hidden" name="plan" id="plan" value="'+plan+'"><input type="hidden" name="team" id="team" value="'+team+'"></form>').appendTo('body').submit();
         

               } 
            </script>

          	</div>
    </div>
</div>
        </div>
        <div class="clear:both;"></div>
        </div>
        <?php include "footer.php"; ?>