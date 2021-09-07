<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        
            <ul class="nav navbar-nav side-nav">
                <!-- <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-1"><i class="fa fa-fw fa-search"></i> MENU 1 <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="submenu-1" class="collapse">
                        <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 1.1</a></li>
                        <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 1.2</a></li>
                        <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 1.3</a></li>
                    </ul>
                </li> -->
               <!--  <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-2"><i class="fa fa-fw fa-star"></i>  MENU 2 <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="submenu-2" class="collapse">
                        <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 2.1</a></li>
                        <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 2.2</a></li>
                        <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 2.3</a></li>
                    </ul>
                </li> -->
                <li>
                    <a href="my_plan"><i class="fa fa-fw fa-paper-plane-o"></i> My Plan</a>
                </li>
               <!--  <li>
                    <a href="transfer_process"><i class="fa fa-fw fa-user-plus"></i> Transfer Process</a>
                </li> -->

                <?php
                  $program_auditor = mysqli_query($mysqli, "SELECT * FROM cca_mngoffice where program_auditor = ".$_SESSION['userid']." and status=1");
               
                  if(mysqli_num_rows($program_auditor)>0){
                    ?>

                        <li>
                            <a href="team"><i class="fa fa-fw fa-user-plus"></i> Manage Party</a>
                        </li>
                        <li>
                            <a href="manage_plan"><i class="fa fa-fw fa-user-plus"></i> Manage Plan</a>
                        </li>
                        <li>
                            <a href="manage_year"><i class="fa fa-fw fa-user-plus"></i> Manage Financial Year</a>
                        </li>

                        <li>
                        <a href="#" data-toggle="collapse" data-target="#submenu-2"><i class="fa fa-circle-o " aria-hidden="true"></i>Circular Notice <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                        <ul id="submenu-2" class="collapse">
                            <li><a href="circular_notice.php"><i class="fa fa-angle-double-right"></i> Add Circular</a></li>
                            <li><a href="addCircular_subject"><i class="fa fa-angle-double-right"></i> Add Subject</a></li>
                            <li><a href="view_circular"><i class="fa fa-angle-double-right"></i>View Circular </a></li>
                        </ul>
                        </li>

                         <?php
                      }
                    ?>
                    <li>
                        <a href="manage_diary"><i class="fa fa-fw fa fa-question-circle"></i> Diary Management</a>
                    </li>

                      
            </ul>