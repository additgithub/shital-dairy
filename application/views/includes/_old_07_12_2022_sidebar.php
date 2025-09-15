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
            <?php if ($this->tank_auth->get_user_role_id() == '1') { ?>
                <li class="start  <?php echo ($this->uri->segment(1) == "") ? "active" : ''; ?> "> <a href="<?php echo base_url(''); ?>"> <i class="icon-custom-home"></i> <span class="title">Dashboard</span> </a> </li>
                <li class="start <?php echo ($this->uri->segment(1) == "make" || $this->uri->segment(1) == "model" || $this->uri->segment(1) == "body-type" || $this->uri->segment(1) == "fuel-type" || $this->uri->segment(1) == "transmission-type"  || $this->uri->segment(1) == "owner" || $this->uri->segment(1) == "price-range" || $this->uri->segment(1) == "km-range" || $this->uri->segment(1) == "car-age" || $this->uri->segment(1) == "state-registration" || $this->uri->segment(1) == "vehicle-type" || $this->uri->segment(1) == "city" || $this->uri->segment(1) == "model")  ? "active open" : ''; ?>
                    "> <a href="javascript:;"> <i class="icon-custom-home"></i> <span class="title">Masters</span> <span class="arrow"></span> </a>
                    <ul class="sub-menu">
                        <li class="start <?php echo ($this->uri->segment(1) == "vehicle-type") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('vehicle-type'); ?>">
                                <i class="fa fa-bus" aria-hidden="true"></i>
                                <span class="title">Vehicle-Type</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "make") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('make'); ?>">
                                <i class="fa fa-truck" aria-hidden="true"></i>
                                <span class="title">Make</span>
                            </a>
                        </li>
                                               <li class="start <?php echo ($this->uri->segment(1) == "model") ? "active" : ''; ?>"> 
                            <a href="<?php echo base_url('model'); ?>"> 
                                <i class="fa fa-automobile" aria-hidden="true"></i>
                                <span class="title">Model</span> 
                            </a> 
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "body-type") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('body-type'); ?>">
                                <i class="fa fa-bus" aria-hidden="true"></i>
                                <span class="title">Body Type</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "fuel-type") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('fuel-type'); ?>">
                                <i class="fa fa-cab" aria-hidden="true"></i>
                                <span class="title">Fuel Type</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "transmission-type") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('transmission-type'); ?>">
                                <i class="fa fa-support" aria-hidden="true"></i>
                                <span class="title">Transmission Type</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "owner") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('owner'); ?>">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span class="title">Owner Type</span>
                            </a>
                        </li>
                        <!--                        <li class="start <?php echo ($this->uri->segment(1) == "price-range") ? "active" : ''; ?>"> 
                            <a href="<?php echo base_url('price-range'); ?>"> 
                                <i class="fa fa-money" aria-hidden="true"></i>
                                <span class="title">Budget</span> 
                            </a> 
                        </li>-->
                        <li class="start <?php echo ($this->uri->segment(1) == "km-range") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('km-range'); ?>">
                                <i class="fa fa-road" aria-hidden="true"></i>
                                <span class="title">K.M. Driven</span>
                            </a>
                        </li>
                        <!--                        <li class="start <?php echo ($this->uri->segment(1) == "car-age") ? "active" : ''; ?>"> 
                            <a href="<?php echo base_url('car-age'); ?>"> 
                                <i class="fa fa-car" aria-hidden="true"></i>
                                <span class="title">Car Age</span> 
                            </a> 
                        </li>-->
                        <li class="start <?php echo ($this->uri->segment(1) == "state-registration") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('state-registration'); ?>">
                                <i class="fa fa-folder" aria-hidden="true"></i>
                                <span class="title">Registration State</span>
                            </a>
                        </li>

                        <li class="start <?php echo ($this->uri->segment(1) == "city") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('city'); ?>">
                                <i class="fa fa-folder" aria-hidden="true"></i>
                                <span class="title">City</span>
                            </a>
                        </li>
                    </ul>
                    <!--                        <li class="start <?php echo ($this->uri->segment(1) == "car") ? "active" : ''; ?>"> 
                            <a href="<?php echo base_url('car'); ?>"> 
                                <i class="fa fa-car" aria-hidden="true"></i>
                                <span class="title">Car</span> 
                            </a> 
                        </li>-->
            
                <li class="start <?php echo ($this->uri->segment(1) == "client") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('client'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Client</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "sales-representative") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('sales-representative'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">SR Code Creation</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "employee") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('employee'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Employee Creation</span>
                    </a>
                </li>
                <!-- <li class="start <?php echo ($this->uri->segment(1) == "referal-code") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('referal-code'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Referal Code</span>
                    </a>
                </li> -->
                <li class="start <?php echo ($this->uri->segment(1) == "Yard_referal_code" || $this->uri->segment(1) == "Sale_represatative_referal_code"  || $this->uri->segment(1) == "dealer_referal_code" || $this->uri->segment(1) == "employee_referal_code")  ? "active open" : ''; ?>"> 
                <a href="javascript:;"> <i class="icon-custom-home"></i> <span class="title">Referal Code</span> <span class="arrow"></span> </a>
                    <ul class="sub-menu">
                        
                        <li class="start <?php echo ($this->uri->segment(1) == "Yard_referal_code") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('Yard_referal_code'); ?>">
                                <i class="fa fa-image" aria-hidden="true"></i>
                                <span class="title">Yard Referal Code</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "Sale_represatative_referal_code") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('Sale_represatative_referal_code'); ?>">
                                <i class="fa fa-image" aria-hidden="true"></i>
                                <span class="title">SR Referal Code</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "dealer_referal_code") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('dealer_referal_code'); ?>">
                                <i class="fa fa-image" aria-hidden="true"></i>
                                <span class="title">Dealer Referal Code</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "employee_referal_code") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('employee_referal_code'); ?>">
                                <i class="fa fa-image" aria-hidden="true"></i>
                                <span class="title">Employee Referal Code</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "bank_auction_list") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('bank_auction_list'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Bank Auction List</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "auction") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('auction'); ?>">
                        <i class="fa fa-car" aria-hidden="true"></i>
                        <span class="title">All Event</span>
                    </a>
                </li>

                <li class="start <?php echo ($this->uri->segment(1) == "live-auction-monitor" || $this->uri->segment(1) == "auction-monitor")  ? "active open" : ''; ?>"> 
                <a href="javascript:;"> <i class="icon-custom-home"></i> <span class="title">Auction Monitor</span> <span class="arrow"></span> </a>
                    <ul class="sub-menu">
                        <li class="start <?php echo ($this->uri->segment(1) == "live-auction-monitor") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('live-auction-monitor'); ?>">
                                <i class="fa fa-car" aria-hidden="true"></i>
                                <span class="title">Live Event Overview</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "auction-monitor") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('auction-monitor'); ?>">
                                <i class="fa fa-car" aria-hidden="true"></i>
                                <span class="title">Event Monitor</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="start <?php echo ($this->uri->segment(1) == "car_images" || $this->uri->segment(1) == "NOC"  || $this->uri->segment(1) == "NOC_handover")  ? "active open" : ''; ?>"> 
                <a href="javascript:;"> <i class="icon-custom-home"></i> <span class="title">Uploads</span> <span class="arrow"></span> </a>
                    <ul class="sub-menu">
                        
                        <li class="start <?php echo ($this->uri->segment(1) == "car_images") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('car_images'); ?>">
                                <i class="fa fa-image" aria-hidden="true"></i>
                                <span class="title">Car Images</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "NOC" && $this->uri->segment(2) == "") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('NOC'); ?>">
                                <i class="fa fa-image" aria-hidden="true"></i>
                                <span class="title">Bulk NOC</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(2) == "noc_car") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('NOC/noc_car'); ?>">
                                <i class="fa fa-image" aria-hidden="true"></i>
                                <span class="title">Single NOC</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "NOC_handover" && $this->uri->segment(2) == "") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('NOC_handover'); ?>">
                                <i class="fa fa-image" aria-hidden="true"></i>
                                <span class="title">Bulk NOC Handover</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "NOC_handover" && $this->uri->segment(2) == "car") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('NOC_handover/car'); ?>">
                                <i class="fa fa-image" aria-hidden="true"></i>
                                <span class="title">Single NOC Handover</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                <!-- <li class="start <?php echo ($this->uri->segment(1) == "car_images") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('car_images'); ?>">
                        <i class="fa fa-image" aria-hidden="true"></i>
                        <span class="title">Car Images</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "NOC") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('NOC'); ?>">
                        <i class="fa fa-image" aria-hidden="true"></i>
                        <span class="title">NOC</span>
                    </a>
                </li> -->
                <li class="start <?php echo ($this->uri->segment(1) == "rsd") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('rsd'); ?>">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        <span class="title">RSD</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "setting") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('setting'); ?>">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        <span class="title">Setting</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "withdraw-req") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('withdraw-req'); ?>">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        <span class="title">Withdraw Req</span>
                    </a>
                </li>

                <!--                <li class="start <?php echo ($this->uri->segment(1) == "dealer-car-list") ? "active" : ''; ?>"> 
                    <a href="<?php echo base_url('dealer-car-list'); ?>"> 
                        <i class="fa fa-car" aria-hidden="true"></i>
                        <span class="title">Dealer's Car</span> 
                    </a> 
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "banner") ? "active" : ''; ?>"> 
                    <a href="<?php echo base_url('banner'); ?>"> 
                        <i class="fa fa-image"></i>
                        <span class="title">Banner</span> 
                    </a> 
                </li>
-->
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "home-cms" || $this->uri->segment(1) == "aboutus-cms" || $this->uri->segment(1) == "cms" || $this->uri->segment(1) == "home-feature" || $this->uri->segment(1) == "faq") ? "active open" : ''; ?>
                    "> <a href="javascript:;"> <i class="fa fa-folder"></i> <span class="title">CMS</span> <span class="arrow"></span> </a>
                    <ul class="sub-menu">
                        <li class="start <?php echo ($this->uri->segment(1) == "home-cms") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('home-cms'); ?>">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span class="title">Home CMS</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "home-feature") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('home-feature'); ?>">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span class="title">Home Feature</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "cms") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('cms'); ?>">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span class="title">CMS</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "aboutus-cms") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('aboutus-cms'); ?>">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span class="title">AboutUs CMS</span>
                            </a>
                        </li>
                        <!--
                        <li class="start <?php echo ($this->uri->segment(1) == "advantages-cms") ? "active" : ''; ?>"> 
                            <a href="<?php echo base_url('advantages-cms'); ?>"> 
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span class="title">Advantages CMS</span> 
                            </a> 
                        </li>-->
                        <li class="start <?php echo ($this->uri->segment(1) == "faq") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('faq'); ?>">
                                <i class="fa fa-question" aria-hidden="true"></i>
                                <span class="title">FAQ'S</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "user" || $this->uri->segment(1) == "contact" || $this->uri->segment(1) == "newsletter" || $this->uri->segment(1) == "feedback" || $this->uri->segment(1) == "car-loan-request" || $this->uri->segment(1) == "car-inquiry" || $this->uri->segment(1) == "custom-requirement" || $this->uri->segment(1) == "partner-request" || $this->uri->segment(1) == "sell-car-request" || $this->uri->segment(1) == "lead" || $this->uri->segment(1) == "auction-bulk-fulfillment") ? "active open" : ''; ?>">
                    <a href="javascript:;"> <i class="icon-custom-home"></i> <span class="title">List</span> <span class="arrow"></span> </a>
                    <ul class="sub-menu">

                        <li class="start <?php echo ($this->uri->segment(1) == "user") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('user'); ?>">
                                <i class="fa fa-list"></i>
                                <span class="title">Buyer</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "user-buying-limit") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('user-buying-limit'); ?>">
                                <i class="fa fa-list"></i>
                                <span class="title">Alot Buying Limit</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "auction-bulk-fulfillment") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('auction-bulk-fulfillment'); ?>">
                                <i class="fa fa-list"></i>
                                <span class="title">Auction Bulk Fulfillment</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "contact") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('contact'); ?>">
                                <i class="fa fa-list"></i>
                                <span class="title">Contact US</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "lead") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('lead'); ?>">
                                <i class="fa fa-list"></i>
                                <span class="title">Lead</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="start <?php echo ($this->uri->segment(1) == "commission-report" || $this->uri->segment(1) == "monthly-invoice" || $this->uri->segment(1) == "auction-closure" || $this->uri->segment(1) == "client-report" || $this->uri->segment(1) == "auction-report" || $this->uri->segment(1) == "invoice-report" || $this->uri->segment(1) == "buyer-transaction-report" || $this->uri->segment(1) == "rsd-transaction-report" || $this->uri->segment(1) == "subscription-report") ? "active open" : ''; ?>">
                    <a href="javascript:;"> <i class="icon-custom-home"></i> <span class="title">Report</span> <span class="arrow"></span> </a>
                    <ul class="sub-menu">

                        <li class="start <?php echo ($this->uri->segment(1) == "commission-report") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('commission-report'); ?>">
                                <i class="fa fa-inbox"></i>
                                <span class="title">Commission Report</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "auction-closure") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('auction-closure'); ?>">
                                <i class="fa fa-inbox"></i>
                                <span class="title">Auction Closure</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "monthly-invoice") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('monthly-invoice'); ?>">
                                <i class="fa fa-inbox"></i>
                                <span class="title">Monthly Invoice</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "client-report") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('client-report'); ?>">
                                <i class="fa fa-inbox"></i>
                                <span class="title">Client Report</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "auction-report") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('auction-report'); ?>">
                                <i class="fa fa-inbox"></i>
                                <span class="title">Auction Report</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "invoice-report") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('invoice-report'); ?>">
                                <i class="fa fa-inbox"></i>
                                <span class="title">Invoice Report</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "transaction-report") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('transaction-report'); ?>">
                                <i class="fa fa-inbox"></i>
                                <span class="title">Transaction Report</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "buyer-transaction-report") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('buyer-transaction-report'); ?>">
                                <i class="fa fa-inbox"></i>
                                <span class="title">Buyer Transaction Report</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "rsd-transaction-report") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('rsd-transaction-report'); ?>">
                                <i class="fa fa-inbox"></i>
                                <span class="title">RSD Transaction Report</span>
                            </a>
                        </li>
                        <li class="start <?php echo ($this->uri->segment(1) == "subscription-report") ? "active" : ''; ?>">
                            <a href="<?php echo base_url('subscription-report'); ?>">
                                <i class="fa fa-inbox"></i>
                                <span class="title">Registration Fees Report</span>
                            </a>
                        </li>
                       

                    </ul>
                </li>


                <!-- <li class="start <?php echo ($this->uri->segment(1) == "role") ? "active" : ''; ?>"> 
                <a href="<?php echo base_url('role'); ?>"> 
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <span class="title">Role</span> 
                </a> 
                </li> -->
            <?php } else if ($this->tank_auth->get_user_role_id() == '3') { ?>
                <li class="start <?php echo ($this->uri->segment(1) == "") ? "active" : ''; ?>"> <a href="<?php echo base_url(); ?>"> <i class="icon-custom-home"></i> <span class="title">Dashboard</span> </a> </li>
                <li class="start <?php echo ($this->uri->segment(1) == "client") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('client/view_detail'); ?>">
                        <i class="fa fa-user"></i>
                        <span class="title">Bank Profile</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "aggrement") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('aggrement'); ?>">
                        <i class="fa fa-image" aria-hidden="true"></i>
                        <span class="title">Aggrement</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "auction") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('auction'); ?>">
                        <i class="fa fa-car" aria-hidden="true"></i>
                        <span class="title">Auction</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "car_images") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('car_images'); ?>">
                        <i class="fa fa-image" aria-hidden="true"></i>
                        <span class="title">Car Images</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "NOC") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('NOC'); ?>">
                        <i class="fa fa-image" aria-hidden="true"></i>
                        <span class="title">NOC</span>
                    </a>
                </li>
                <li class="start <?php echo ($this->uri->segment(1) == "monthly-invoice") ? "active" : ''; ?>">
                    <a href="<?php echo base_url('monthly-invoice'); ?>">
                        <i class="fa fa-inbox"></i>
                        <span class="title">Monthly Invoice</span>
                    </a>
                </li>
                <!--<li class="start <?php echo ($this->uri->segment(1) == "sell-car-request") ? "active" : ''; ?>"> <a href="<?php echo base_url('sell-car-request'); ?>"> <i class="fa fa-list"></i> <span class="title">Sell Car Request</span> </a> </li>-->
            <?php } ?>
        </ul>

        <div class="clearfix"></div>

        <!-- END SIDEBAR MENU -->

    </div>

</div>

<a href="#" class="scrollup">Scroll</a>



<!-- END SIDEBAR -->