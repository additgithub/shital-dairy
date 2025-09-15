<div class="content">
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Dashboard</h4>
                </div>
                <div class="grid-body">
                    <?php if ($this->tank_auth->get_user_role_id() == 1 || $this->tank_auth->get_user_role_id() == 2) { ?>
                        <div class="row">
                            <?php if ($this->tank_auth->get_user_role_id() == 1){

                            ?>
                            
                            <div class="col-sm-3 custom_box">
                                <div class="card mb-4 bg-primary text-white">
                                    <a href="<?php echo base_url('items') ?>" style="color:white;">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="Icons display-4">
                                                    <i class="fa fa-list-alt"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-white small">Total Items</div>
                                                    <div class="text-large"><?php echo $total_items; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                            
                            <div class="col-sm-3 custom_box">
                                <div class="card mb-4 bg-success text-white">
                                    <a href="<?php echo base_url('orders') ?>" style="color:white;">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="Icons display-4">
                                                    <i class="fa fa-users" aria-hidden="true"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-white small">Total Orders</div>
                                                    <div class="text-large"><?php echo $total_orders; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                          
                        </div>
                      
                    <?php } 
                    ?>
                     

                </div>
            </div>
        </div>
    </div>


</div>