<?php

date_default_timezone_set('Asia/Kolkata');
$BASE_URL = '';
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define("HOSTNAME", 'localhost');
    define("USERNAME", "root");
    define("PASSWORD", "");
    define("DATABASE", "sheetal_dairy");
    $BASE_URL = 'http://' . $_SERVER['HTTP_HOST'] . '/shital-dairy/';
    define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT'] . '/shital-dairy/');
} 
// else if ($_SERVER['HTTP_HOST'] == 'live.com') {
//     //Live Server
//     define("HOSTNAME", 'localhost');
//     define("USERNAME", "autobyvf_auction");
//     define("PASSWORD", "CI.4];O^AkWY");
//     define("DATABASE", "autobyvf_auction");
//     $BASE_URL = 'https://' . $_SERVER['HTTP_HOST'] . '/';  
//     define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
// }


// $BASE_URL = 'http://' . $_SERVER['HTTP_HOST'] . '/autobazaar-Auction/';


define("WEBSITE_NAME", "SHEETAL DAIRY");
define("WEBSITE_EMAIL", "support@matunga.com");
define("ADMIN_EMAIL", "ramizg.aipl@gmail.com");
define('BANNER_DIR', 'banner/');
define('UPLOAD_DIR', '../uploads/');
define('UPLOAD_ZIP_DIR', '../uploads_zip/');
define('UPLOAD_FRONT_DIR', 'uploads/');
define('UPLOAD_DIR_NAME', '/uploads/');
define('BASE_URL', $BASE_URL);
define('ROOT_URL', $BASE_URL);
define('DEFAULT_AVATAR', BASE_URL . 'assets/img/profiles/default_avatar_wogjep.jpg');

define('DEFAULT_NO_EMAGE', BASE_URL . 'assets/img/No_image_found.jpg');
define('DEFAULT_NO_IMAGE', BASE_URL . 'assets/images/NO-image.jpg');
define('DEFAULT_BANK_AVATAR', BASE_URL . 'assets/images/NO-image.jpg');

?>