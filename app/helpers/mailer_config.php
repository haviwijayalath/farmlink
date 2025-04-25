<?php

require APPROOT . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

function mailerConfig()
{
  // Load environment variables from .env file
  $dotenv = Dotenv::createImmutable(APPROOT . '/config');
  $dotenv->load();

  // Create a new PHPMailer instance
  $mail = new PHPMailer(true);

  // SMTP configuration
  $mail->isSMTP();
  $mail->Host = $_ENV['MAIL_HOST'];
  $mail->SMTPAuth = true;
  $mail->Username = $_ENV['MAIL_USERNAME'];
  $mail->Password = $_ENV['MAIL_PASSWORD'];
  $mail->Port = $_ENV['MAIL_PORT'];

  $mail->isHTML(true);

  return $mail;
}