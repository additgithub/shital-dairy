<!-- BEGIN SIDEBAR -->

<div class="page-sidebar" id="main-menu">

    <!-- BEGIN MINI-PROFILE -->

    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">

        <div class="user-info-wrapper">

            <!--  <div class="profile-wrapper">
 
                 <img src="<?php echo DEFAULT_AVATAR; ?>"  alt="" data-src="<?php echo DEFAULT_AVATAR; ?>" data-src-retina="<?php echo DEFAULT_AVATAR; ?>" width="69" height="69" />
 
             </div> -->

            <!-- <div class="user-info">
 
                <div class="greeting">Welcome</div>
 
                <div class="username"><?php echo $this->tank_auth->get_username(); ?></div>
 
                <div class="status">Status<a href="#"><div class="status-icon green"></div>Online</a></div>
 
            </div> -->

        </div>

        <!-- END MINI-PROFILE -->


        <!-- BEGIN SIDEBAR MENU -->

        <!--<p class="menu-title">BROWSE <span class="pull-right"><a href="javascript:;"><i class="fa fa-refresh"></i></a></span></p>-->

        <div class="clearfix"></div>
        <ul>
            <li class="start  <?php echo ($this->uri->segment(1) == "") ? "active" : ''; ?> "> <a href="<?php echo base_url(''); ?>"> <i class="icon-custom-home"></i> <span class="title">Dashboard</span> </a> </li>
            <?php if ($this->tank_auth->get_user_role_id() == '1') { ?>


                <li class="start <?php echo ($this->uri->segment(1) == "items") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('items'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Items</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "customer") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('customer'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Customer</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "wadi") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('wadi'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Wadi</span>
                    </a>
                </li>
                <!-- <li class="start <?php echo ($this->uri->segment(1) == "purchase" && $this->uri->segment(2) == "") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('purchase'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Purchase</span>
                    </a>
                </li> -->
                <li class="start <?php echo ($this->uri->segment(1) == "orders" && $this->uri->segment(2) == "") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('orders'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Orders</span>
                    </a>
                </li>
                <!-- <li class="start <?php echo ($this->uri->segment(1) == "employee") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('employee'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Employee</span>
                    </a>
                </li> -->
                <!-- <li class="start <?php echo ($this->uri->segment(1) == "expense") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('expense'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Expense</span>
                    </a>
                </li> -->
                <li class="start <?php echo ($this->uri->segment(1) == "orders" && $this->uri->segment(2) == "item_summary") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('orders/item_summary'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Item Wise Report</span>
                    </a>
                </li>
                <!-- <li class="start <?php echo ($this->uri->segment(1) == "purchase" && $this->uri->segment(2) == "stock_summary") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('purchase/stock_summary'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Stock Report</span>
                    </a>
                </li> -->
                <!-- <li class="start <?php echo ($this->uri->segment(1) == "purchase" && $this->uri->segment(2) == "current_balance_summary") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('purchase/current_balance_summary'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Current Balance Report</span>
                    </a>
                </li> -->


            <?php } else if ($this->tank_auth->get_user_role_id() == '2') { ?>
                <li class="start <?php echo ($this->uri->segment(1) == "orders") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('orders'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Orders</span>
                    </a>
                </li>
            <?php  } ?>
        </ul>

        <div class="clearfix"></div>

        <!-- END SIDEBAR MENU -->

    </div>

</div>

<a href="#" class="scrollup">Scroll</a>


<!-- END SIDEBAR -->