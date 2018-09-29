<?php
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Email{
  private $mailer;

  /*
   * Create Mailer
   */
  public function __construct() {
    require_once $_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php';

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
    require_once $_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php';

    // Create a message
    $message = (new \Swift_Message($subject))
      ->setFrom(['mealplanner18@gmail.com' => 'MealPlanner'])
      ->setTo([$to])
      ->setBody($body,'text/html');

    // Send the message
    $result = $this->mailer->send($message);
  }

  public function sendEmailAddrConfirm($to){
    // Hash makes it harder to circumvent email (need code to confirm)
    $salt = 'QM8z7AnkXUKQzwtK7UcA';
    $code = urlencode(hash('sha256',$to.$salt));
    $subject = 'Please confirm your email';
    $body = 'Please click this link to confirm your email address:<p>
              localhost/Account/ConfirmEmail/'.$to.'/'.$code.'<p>';
    $this->send($to,$subject,$body);
  }
  public function sendPasswordReset($to,$code){
    $subject = 'Password Reset';
    $body = 'Please click this link to reset your password:<p>
              localhost/Account/ResetPassword/'.$to.'/'.$code.'<p>';
    $this->send($to,$subject,$body);
  }
  public function sendEmailUpdateConfirm($to,$old_email){
    // Hash makes it harder to circumvent email (need code to confirm)
    $salt = 'QM8z7AnkXUKQzwtK7UcA';
    $code = urlencode(hash('sha256',$to.$salt.$old_email));
    $subject = 'Please confirm your email';
    $body = 'Please click this link to confirm your email address:<p>
              localhost/Account/ConfirmNewEmail/'.$to.'/'.$old_email.'/'.$code.'<p>';
    $this->send($to,$subject,$body);
  }
}
?>
