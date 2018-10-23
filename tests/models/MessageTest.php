<?php
/////////////////////////////////////////////////////////////////////
// MessageTest                               SWENG894 [Group 8] 2018
/////////////////////////////////////////////////////////////////////
// xUnit :: PHPUnit
/////////////////////////////////////////////////////////////////////
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Models\Message;

class MessageTest extends TestCase
{
    private $message;

    //
    // Initialization
    //
    public function init()
    {
        $this->message = new Message();
    }

    //
    // Reversion
    //
    public function recycle()
    {
      unset($this->message);
    }

    //
    // [#81] Star/Unstar Message
    // 
    public function testStarMessageInvalidID()
    {
        $this->expectException(Exception::class);
        $this->message->star();
    }
    
    public function testUnStarMessageInvalidID()
    {
        $this->expectException(Exception::class);
        $this->message->unStar();
    }
    
    //
    // [#84] View Message
    // 
    
    //
    // [#85] View (Sent) Message
    //

    //
    // [#87] Compose Message
    // 
    public function testComposeMessageMissingRecipient()
    {
        $this->expectException(Exception::class);
        $this->message(NULL, "This is a test message.");
    }

    public function testComposeMessageMissingMessage()
    {
        $this->expectException(Exception::class);
        $this->message(1, NULL);
    }

    public function testComposeMessageIsTooShort()
    {
        $this->expectException(Exception::class);
        $this->message(1, "!");
    }

    public function testComposeMessageIsTooLong()
    {
        $this->expectException(Exception::class);
        $maxChars = 2049;
        $contents = '';

        for ($i = 0; $i < $maxChars; $i++) { $contents .= '!'; }

        $this->message(1, $contents);
    }

    public function testComposeMessageInvalidRecipient()
    {
        $this->expectException(Exception::class);
        $this->message(0, "This is a test message.");
    }

    //
    // [#87] Compose (Send) Message
    // 
    public function testSendMessageMissingSender()
    {
        $this->expectException(Exception::class);
        $this->message->send(NULL);
    }

    public function testSendMessageInvalidSender()
    {
        $this->expectException(Exception::class);
        $this->message->send(0);
    }

    //
    // [#92] Trash/Recover Message
    // 
    public function testTrashMessageAlreadyTrashed()
    {
        $this->expectException(Exception::class);
    }

    public function testRecoverMessageNotTrashed()
    {
        $this->expectException(Exception::class);
    }

    //
    // Un/Set Message Viewed
    // 
    public function testViewMessageAlreadyViewed()
    {
        $this->expectException(Exception::class);
        $this->message->setViewed();
    }

    public function testUnViewMessageNotViewed()
    {
        $this->expectException(Exception::class);
        $this->message->unsetViewed();
    }

    //
    // Other
    //
    public function testSetIdNULL()
    {
        $this->expectException(Exception::class);
        $this->message->setID(NULL);
    }

    public function testSetIdEmpty()
    {
        $this->expectException(Exception::class);
        $this->message->setID(0);
    }

    public function testSetIdNotInteger()
    {
        $this->expectException(Exception::class);
        $this->message->setID('string');
    }

    public function testSetIdIsNegative()
    {
        $this->expectException(Exception::class);
        $this->message->setID(-1);
    }

    public function testSetIdTooHigh()
    {
        $this->expectException(Exception::class);
        $this->message->setID(2147483648);
    }

    public function testSetMessageNULL()
    {
        $this->expectException(Exception::class);
        $this->message->setMessage(NULL);
    }

    public function testSetMessageEmpty()
    {
        $this->expectException(Exception::class);
        $this->message->setMessage('');
    }

    public function testSetMessageIsTooLong()
    {
        $this->expectException(Exception::class);
        $maxChars = 2049;
        $contents = '';

        for ($i = 0; $i < $maxChars; $i++) { $contents .= '!'; }

        $this->message->setMessage($contents);
    }

}
