<?php
require_once("Mail.php");

$from = "Run Hoops <info@runhoops.com>";
$to = "Ramiz Girach <ramizg.aipl@gmail.com>";
$subject = "Subject";
$body = "Lorem ipsum dolor sit amet, consectetur adipiscing elit...";

$host = "smtp.office365.com";
$username = "info@runhoops.com";
$password = "Gardener0903";

$headers = array('From' => $from, 'To' => $to, 'Subject' => $subject);

$smtp = Mail::factory('smtp', array ('host' => $host,
                                     'auth' => true,
                                     'username' => $username,
                                     'password' => $password));

$mail = $smtp->send($to, $headers, $body);

if ( PEAR::isError($mail) ) {
    echo("<p>Error sending mail:<br/>" . $mail->getMessage() . "</p>");
} else {
    echo("<p>Message sent.</p>");
}
?>