<?php
require_once dirname(__FILE__).'/../config.php';
require_once dirname(__FILE__).'/../../vendor/autoload.php';

class SMTPClient {

  private $mailer;
  private $base_url;

  public function __construct(){
    $transport = (new Swift_SmtpTransport(Config::SMTP_HOST(), Config::SMTP_PORT(), 'tls'))
      ->setUsername(Config::SMTP_USER())
      ->setPassword(Config::SMTP_PASSWORD());

    $this->mailer = new Swift_Mailer($transport);
    $this->base_url = $_SERVER['SERVER_NAME'];
  }   

  public function send_registration_token($userAccount){
    $base_url = $this->base_url;
    if($base_url = "localhost"){
      $base_url = "localhost/hotelsea";
    }

    $message = (new Swift_Message('Confirm your account'))
      ->setFrom(['haris.hukara@stu.ibu.edu.ba' => 'Hotel Sea'])
      ->setTo([$userAccount['email']])
      ->setBody('Here is the confirmation link: http://'.$base_url.'/login.html?confirm='.$userAccount['token']);
      
    $this->mailer->send($message);
  }

  public function send_recovery_token($userAccount){
    $message = (new Swift_Message('Reset Your Password'))
      ->setFrom(['haris.hukara@stu.ibu.edu.ba' => 'Hotel Sea'])
      ->setTo([$userAccount['email']])
      ->setBody('Recovery link: '.$this->base_url.'/login.html?token='.$userAccount['token']);

    $this->mailer->send($message);
  }

}
?>