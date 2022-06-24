<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__).'/../vendor/autoload.php';


/* mailer test */
    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465,"ssl"))
    ->setUsername('webproject.webshop@gmail.com')
    ->setPassword('Pass123?')
  ;

    $mailer = new Swift_SmtpTransport($transport);


    $message = (new Swift_Message('Wonderful Subject'))
  ->setFrom(['webproject.webshop@gmail.com'])
  ->setTo(['webproject.webshop@gmail.com'])
  ->setBody('Here is the message itself')
  ;

    $result = $mailer->send($message);
    print_r($result);

?>
 