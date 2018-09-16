<?php
namespace Base\Models;

class Email{
  private $mailer;

  /*
   * Create Mailer
   */
  public function __construct() {
    require_once $_SERVER['DOCUMENT_ROOT'].'../vendor/autoload.php';

    // Create the Transport
    $transport = (new \Swift_SmtpTransport('ssl://smtp.gmail.com', 465))
      ->setUsername('mealplanner18')
      ->setPassword('crSFtFFn4');

    // Create the Mailer using your created Transport
    $this->mailer = new \Swift_Mailer($transport);
  }

  /*
   * Send Email using mailer
   */
  public function send($to,$subject,$body){
    require_once $_SERVER['DOCUMENT_ROOT'].'../vendor/autoload.php';

    // Create a message
    $message = (new \Swift_Message($subject))
      ->setFrom(['mealplanner18@gmail.com' => 'MealPlanner'])
      ->setTo([$to])
      ->setBody($body);

    // Send the message
    $result = $this->mailer->send($message);
  }
}
?>
