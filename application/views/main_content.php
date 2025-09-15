<?php
$data['user_id'] = $this->tank_auth->get_user_id();
$data['username'] = $this->tank_auth->get_username();
$this->load->view('includes/header', $data);
$this->load->view($main_content, $data);
$this->load->view('includes/footer', $data);
?>

