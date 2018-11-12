<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                            Penn State - Cohorts 19 & 20 (@) 2018
///////////////////////////////////////////////////////////////////////////////
// Message Factory
///////////////////////////////////////////////////////////////////////////////
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Factories\Factory;
use Base\Models\Message;

class MessageFactory extends Factory
{
    public function __construct()
    {
    }

    /**
     * Creates a new instance of Message model
     * @param  properties    Message Properties - A message's properties
     * @return Message       A message object
     */
    public function make(array $messageProperties) : Message
    {
        $message  = new Message();

        if ($messageProperties['id'] ?? NULL)
        {
            $message->setID($messageProperties['id']);
        }

        $message->setTrashed( $messageProperties['trash']    ?? FALSE);
        $message->setViewed(  $messageProperties['viewed']   ?? FALSE);
        $message->setStarred( $messageProperties['starred']  ?? FALSE);
        $message->setTimeSent($messageProperties['timesent'] ?? NULL);

        if ($messageProperties['message'] ?? NULL)
        {
            $message->setMessage($messageProperties['message']);
        }

        if ($messageProperties['senderid'] ?? NULL)
        {
            $message->setSenderID($messageProperties['senderid']);
        }

        if ($messageProperties['reciepientid'] ?? NULL)
        {
            $message->setReciepientID($messageProperties['reciepientid']);
        }

        return $message;
    }
}
