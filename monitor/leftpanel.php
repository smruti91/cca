<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        
            <ul class="nav navbar-nav side-nav">
                <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-1"><i class="fa fa-user-plus"></i>Transfer Process <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="submenu-1" class="collapse">
                        <li><a href="<?php echo BASE_URL; ?>monitor/transfer_employee"><i class="fa fa-angle-double-right"></i> Transfer Employee</a></li>
                        <li><a href="<?php echo BASE_URL; ?>monitor/transfer_history"><i class="fa fa-angle-double-right"></i> Transfer History</a></li>
                        
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-2"><i class="fa fa-fw fa-star"></i>  MENU 2 <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="submenu-2" class="collapse">
                        <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 2.1</a></li>
                        <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 2.2</a></li>
                        <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 2.3</a></li>
                    </ul>
                </li>
                <!-- <li>
                    <a href="transfer_employee.php"><i class="fa fa-fw fa-user-plus"></i>  Transfer Employee</a>
                </li> -->
                <li>
                    <a href="sugerencias"><i class="fa fa-fw fa-paper-plane-o"></i> MENU 4</a>
                </li>
                <li>
                    <a href="faq"><i class="fa fa-fw fa fa-question-circle"></i> MENU 5</a>
                </li>
            </ul>

            <script>
                $(function(){
                    var hash = window.location.href;
                    url = $(location).attr('href'),
                    parts = url.split("/"),
                    pagehash = parts[parts.length-1];
                    
                    
                    
                    if(hash){
                        $('a[href="' + hash +'"]').parent('li').addClass('active');
                        $('a[href="' + hash +'"]').parent('li').parent("ul").show();
                    }
                });

            </script>