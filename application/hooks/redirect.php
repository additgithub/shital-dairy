<?php
function redirects(){
    if ((!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off')) {
            $CI = &get_instance();
            $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
            redirect($CI->uri->uri_string());
        }
}
?>