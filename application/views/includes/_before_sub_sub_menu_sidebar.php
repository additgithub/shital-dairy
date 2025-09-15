<div class="page-sidebar" id="main-menu">
    <!-- BEGIN MINI-PROFILE -->
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
        <!-- BEGIN SIDEBAR MENU -->

        <ul>
            <li class="start <?php echo ($this->uri->segment(1) == "") ? "active" : ''; ?>"><a href="<?php echo base_url(); ?>"><i class="icon-custom-home"></i><span>Dashboard</span></a></li>
            <?php
           
            //                echo $_SERVER['path']; 
            //                die;
            //    echo '<pre>';
            //    print_r($nav_menu);
            //    die;
            //            echo $this->uri->segment(1); die;
            $method_nm = ($this->uri->segment(2) == "") ? "index" : $this->uri->segment(2);
            $user_role = $this->tank_auth->get_user_role_id();
            $user_id = $this->tank_auth->get_user_id();
            $menu_open_seq = 1;
            if ($user_role == 1) {
                $nav_menu = $this->Navmenu->displayMenu();
                foreach ($nav_menu as $nav_menu_array) {
                    //                print_r($nav_menu_array);
                    //                die;
                    $mainMenuRole = (isset($nav_menu_array["menu_role"]) && $nav_menu_array["menu_role"] != "") ? explode(",", $nav_menu_array["menu_role"]) : array();

                    if (in_array("$user_role", $mainMenuRole)) {
                        $class_nm = $nav_menu_array["menu_controller"] == $this->uri->segment(1) ? "active" : "";
                        $main_link = 'javascript:;';
                        if (empty($nav_menu_array["subMenu"]) && !empty($nav_menu_array["menu_link"])) {
                            $main_link = base_url() . $nav_menu_array["menu_link"];
                        }
                        echo '<li class="menu-list ' . $class_nm . ' parent_menu_' . $menu_open_seq . '">';
                        echo '<a class="' . $class_nm . '" href="' . $main_link . '">';

                        $icn_class = $nav_menu_array["menu_icon"] != '' ? $nav_menu_array["menu_icon"] : "fa-bar-chart-o";

                        echo '<i class="fa ' . $icn_class . '"></i>';
                        echo '<span>' . $nav_menu_array["title_name"] . '</span>';
                        if (!empty($nav_menu_array["subMenu"])) {
                            echo '<span class="arrow "></span>';
                        }
                        echo '</a>';
                        if ($nav_menu_array["subMenu"]) {

                            echo '<ul class="sub-menu">';
                            foreach ($nav_menu_array["subMenu"] as $sub_arr) {
                                $icn_class = $sub_arr["menu_icon"] != '' ? $sub_arr["menu_icon"] : "fa-bar-chart-o";
                                $subMenuRole = (isset($sub_arr['menu_role']) && $sub_arr['menu_role'] != "") ? explode(",", $sub_arr['menu_role']) : "";
                                if (in_array("$user_role", $subMenuRole)) {
                                    if ($method_nm != "index") :
                                        $sub_selected = $this->uri->segment(1) . '/' . $method_nm;
                                    else :
                                        $sub_selected = $this->uri->segment(1);
                                    endif;
                                    $class_nm = ($sub_arr["menu_link"] == $sub_selected) ? "active" : "";
                                    if ($class_nm == 'active') {
            ?>
                                        <script>
                                            $('.parent_menu_' + <?php echo $menu_open_seq; ?>).addClass('open');
                                            $('.parent_menu_' + <?php echo $menu_open_seq; ?>).addClass('active');
                                        </script>
                                    <?php
                                    }
                                    echo '<li class="' . $class_nm . '"><a href="' . base_url($sub_arr['menu_link']) . '"><i class="fa ' . $icn_class . '"></i>' . $sub_arr['menu_title'] . '</a></li>';
                                }
                            }
                            echo "</ul>";
                        }
                        echo "</li>";
                    }
                    $menu_open_seq++;
                }
            } else {
                $nav_menu = $this->Navmenu->displayEmpMenu($this->tank_auth->get_user_id());
                foreach ($nav_menu as $nav_menu_array) {
                    // echo '<pre>';
                    // print_r($nav_menu_array);
                    // die;
                    // echo $user_id;die;
                    $UsermainMenuRole = (isset($nav_menu_array["user_ids"]) && $nav_menu_array["user_ids"] != "") ? explode(",", $nav_menu_array["user_ids"]) : array();
                    // echo '<pre>';
                    // print_r($UsermainMenuRole);
                    // if (in_array($user_id, $UsermainMenuRole)) {
                        $class_nm = $nav_menu_array["menu_controller"] == $this->uri->segment(1) ? "active" : "";
                        $main_link = 'javascript:;';
                        if (empty($nav_menu_array["subMenu"]) && !empty($nav_menu_array["menu_link"])) {
                            $main_link = base_url() . $nav_menu_array["menu_link"];
                        }
                        echo '<li class="menu-list ' . $class_nm . ' parent_menu_' . $menu_open_seq . '">';
                        echo '<a class="' . $class_nm . '" href="' . $main_link . '">';

                        $icn_class = $nav_menu_array["menu_icon"] != '' ? $nav_menu_array["menu_icon"] : "fa-bar-chart-o";

                        echo '<i class="fa ' . $icn_class . '"></i>';
                        echo '<span>' . $nav_menu_array["title_name"] . '</span>';
                        if (!empty($nav_menu_array["subMenu"])) {
                            echo '<span class="arrow "></span>';
                        }
                        echo '</a>';
                        if ($nav_menu_array["subMenu"]) {

                            echo '<ul class="sub-menu">';
                            foreach ($nav_menu_array["subMenu"] as $sub_arr) {
                                $icn_class = $sub_arr["menu_icon"] != '' ? $sub_arr["menu_icon"] : "fa-bar-chart-o";
                                $subMenuRole = (isset($sub_arr['user_ids']) && $sub_arr['user_ids'] != "") ? explode(",", $sub_arr['user_ids']) : array();
                                // if (in_array($user_id, $subMenuRole)) {
                                    if ($method_nm != "index") :
                                        $sub_selected = $this->uri->segment(1) . '/' . $method_nm;
                                    else :
                                        $sub_selected = $this->uri->segment(1);
                                    endif;
                                    $class_nm = ($sub_arr["menu_link"] == $sub_selected) ? "active" : "";
                                    if ($class_nm == 'active') {
                                    ?>
                                        <script>
                                            $('.parent_menu_' + <?php echo $menu_open_seq; ?>).addClass('open');
                                            $('.parent_menu_' + <?php echo $menu_open_seq; ?>).addClass('active');
                                        </script>
            <?php
                                    }
                                     $target_blank = '';
                                    if($sub_arr['menu_link'] == 'car_images'){
                                        $target_blank = 'target="_blank"';
                                    }
                                   echo '<li class="' . $class_nm . '"><a href="' . base_url($sub_arr['menu_link']) . '" '.$target_blank.'"><i class="fa ' . $icn_class . '"></i>' . $sub_arr['menu_title'] . '</a></li>';
                                // }
                            }
                            echo "</ul>";
                        }
                        echo "</li>";
                    // }
                    $menu_open_seq++;
                }
            }
            ?>
        </ul>

        <div class="clearfix"></div>
        <!-- END SIDEBAR MENU -->
    </div>
</div>