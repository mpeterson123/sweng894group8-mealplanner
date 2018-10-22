<?php
namespace Base\Helpers;
require_once __DIR__.'/../../vendor/autoload.php';

/**
 * Sends emails to users
 */
class Email{

    private $mailer;

    /**
    * Instantiate mailer
    */
    public function __construct() {
        // Create the Transport
        $transport = (new \Swift_SmtpTransport('ssl://smtp.gmail.com', 465))
            ->setUsername('mealplanner18')
            ->setPassword('crSFtFFn4');

        // Create the Mailer using your created Transport
        $this->mailer = new \Swift_Mailer($transport);
    }

    /**
    * Send email using SwiftMailer
    * @param string $to      Destination email address
    * @param string $subject Email subject
    * @param string $body    Email body
    */
    public function send($to, $subject, $body){
        // Create a message
        $message = (new \Swift_Message($subject))
            ->setFrom(['mealplanner18@gmail.com' => 'MealPlanner'])
            ->setTo([$to])
            ->setBody($body,'text/html');

        // Send the message
        $result = $this->mailer->send($message);
    }

    /**
    * Send confirmation email to new user
    * @param  string $to New user's email address
    */
    public function sendEmailAddrConfirm($to){
        // Hash makes it harder to circumvent email (need code to confirm)
        $salt = 'QM8z7AnkXUKQzwtK7UcA';
        $code = urlencode(hash('sha256',$to.$salt));
        $subject = 'Please confirm your email';
        $body = 'Please click this link to confirm your email address:
        <a href="http://localhost/Account/ConfirmEmail/'.$to.'/'.$code.'">Confirm your email<a>';

        // Send email
        $this->send($to,$subject,$body);
    }

    /**
    * Send password reset email
    * @param string $to   User's email
    * @param string $code Unique password reset code
    */
    public function sendPasswordReset($to, $code){
        $subject = 'Password Reset';
        $body = 'Please click this link to reset your password:
        <a href="http://localhost/Account/ResetPassword/'.$to.'/'.$code.'">Reset your password<a>';

        $this->send($to,$subject,$body);
    }

    /**
    * Send email to check updated email address is valid. User must reconfirm.
    * @param string $to        User's new email
    * @param string $old_email User's old email
    */
    public function sendEmailUpdateConfirm($to, $old_email){
        // Hash makes it harder to circumvent email (need code to confirm)
        $salt = 'QM8z7AnkXUKQzwtK7UcA';
        $code = urlencode(hash('sha256',$to.$salt.$old_email));
        $subject = 'Please confirm your email';
        $body = 'Please click this link to confirm your email address:<p>
            localhost/Account/ConfirmNewEmail/'.$to.'/'.$old_email.'/'.$code.'<p>';

        $body = 'Please click this link to confirm your email address:
        <a href="http://localhost/Account/ConfirmNewEmail/'.$to.'/'.$old_email.'/'.$code.'">Confirm your email<a>';

            // Send email
        $this->send($to,$subject,$body);
    }
}
?>
