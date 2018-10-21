<?php
/////////////////////////////////////////////////////////////////////
// Message                                   SWENG894 [Group 8] 2018
/////////////////////////////////////////////////////////////////////
// Primary Object Class
/////////////////////////////////////////////////////////////////////
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

define('MAX_MESSAGE_SIZE',   2048);
define('MAX_INT_SIZE', 2147483647);

class Message
{
    private $id;

    private $starred;
    private $trashed;
    private $viewed;

    private $timeSent;
    private $message;
    private $senderID;
    private $recipientID;

    /////////////////////////////////////////////////////////////////
    // Trash
    /////////////////////////////////////////////////////////////////
    public function trash()
    {
        if ($this->trashed)
        {
            throw new Exception("Message is already set as trash.");
        }

        $this->trashed = TRUE;
    }

    public function recover()
    {
        if (!$this->trashed)
        {
            throw new Exception("Message is not trash, and cannot be recovered.");
        }

        $this->trashed = FALSE;
    }

    public function isTrash()
    {
        return ($this->trashed);
    }

    /////////////////////////////////////////////////////////////////
    // Viewed
    /////////////////////////////////////////////////////////////////
    public function isNew()
    {
        return (!$this->viewed);
    }

    public function setViewed()
    {
        if ($this->viewed)
        {
            throw new Exception("Message has already been viewed.");
        }

        $this->viewed = TRUE;
    }

    public function unsetViewed()
    {
        if (!$this->viewed)
        {
            throw new Exception("Message has not been viewed yet.");
        }

        $this->viewed = FALSE;
    }

    /////////////////////////////////////////////////////////////////
    // ID #
    /////////////////////////////////////////////////////////////////
    public function setID($id)
    {
        if ($id === NULL)
        {
            throw new Exception("ID # cannot be NULL.");
        }

        if (!$id)
        {
            throw new Exception("ID # cannot be empty.");
        }

        if (gettype($id) !== 'integer')
        {
            throw new Exception("ID # must be type Int.");
        }

        if ($id < 0)
        {
            throw new Exception("ID # cannot be negative.");
        }

        if ($id >= MAX_INT_SIZE)
        {
            throw new Exception("ID # cannot be maximum signed integer size.");
        }
 
        $this->id = $id;
    }

    public function getID()
    {
        return $this->id;
    }

    /////////////////////////////////////////////////////////////////
    // Messages
    /////////////////////////////////////////////////////////////////
    public function setMessage($message)
    {
        if ($message === NULL)
        {
            throw new Exception("Message cannot be NULL.");
        }

        if ($message == '')
        {
            throw new Exception("Message cannot be empty.");
        }

        if (strlen($message) > MAX_MESSAGE_SIZE)
        {
            throw new Exception("Food Item message cannot be longer than 20 characters");
        }

        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /////////////////////////////////////////////////////////////////
    // Sender and Recipient IDs
    /////////////////////////////////////////////////////////////////
    public function getTimeSent()
    {
        return $this->timeSent;
    }

    public function getSenderID()
    {
        return $this->senderID;
    }

    public function getRecipientID()
    {
        return $this->recipientID;
    }

    public function setRecipientID($id)
    {
        if ($id === NULL)
        {
            throw new Exception("ID # cannot be NULL.");
        }

        if (!$id)
        {
            throw new Exception("ID # cannot be empty.");
        }

        if (gettype($id) !== 'integer')
        {
            throw new Exception("ID # must be type Int.");
        }

        if ($id < 0)
        {
            throw new Exception("ID # cannot be negative.");
        }

        if ($id >= MAX_INT_SIZE)
        {
            throw new Exception("ID # cannot be maximum signed integer size.");
        }

        if ($id == $this->getID())
        {
            throw new Exception("Recipient ID # cannot match sender ID #.");
        }
 
        $this->id = $id;
    }

    /////////////////////////////////////////////////////////////////
    // Stars
    /////////////////////////////////////////////////////////////////
    public function star()
    {
        if ($this->starred)
        {
            throw new Exception("Message is already starred.");
        }

        $this->starred = TRUE;
    }

    public function unStar()
    {
        if (!$this->starred)
        {
            throw new Exception("Message is not starred.");
        }

        $this->starred = FALSE;
    }

    public function isStarred()
    {
        return ($this->starred);
    }

    /////////////////////////////////////////////////////////////////
    // Messages in Database
    /////////////////////////////////////////////////////////////////

    // For initially sending a message
    public function send()
    {
    }

    // Updating a pre-existing message 
    public function update()
    {
    }
}
