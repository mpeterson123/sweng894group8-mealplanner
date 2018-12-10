<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                            Penn State - Cohorts 19 & 20 (@) 2018
///////////////////////////////////////////////////////////////////////////////
// Message Primary Object Class
///////////////////////////////////////////////////////////////////////////////
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';
define('MAX_MESSAGE_SIZE',   2048);
define('MAX_INT_SIZE', 2147483647);

/**
 * Represents a message between users
 */
class Message
{
    private $id;
    private $trashed;
    private $viewed;
    private $starred;
    private $timeSent;
    private $humanReadableTime;
    private $humanReadableTime2;
    private $message;
    private $senderID;
    private $recipientID;

    /////////////////////////////////////////////////////////////////
    // ID #
    /////////////////////////////////////////////////////////////////
    public function setID($id)
    {
        if ($id === NULL)
        {
            throw new \Exception("ID # cannot be NULL.");
        }
        if (!$id)
        {
            throw new \Exception("ID # cannot be empty.");
        }
        if (gettype($id) !== 'integer')
        {
            throw new \Exception("ID # must be type Int.");
        }
        if ($id < 0)
        {
            throw new \Exception("ID # cannot be negative.");
        }
        if ($id >= MAX_INT_SIZE)
        {
            throw new \Exception("ID # cannot be maximum signed integer size.");
        }

        $this->id = $id;
    }
    public function getID()
    {
        return $this->id;
    }

    /////////////////////////////////////////////////////////////////
    // Trash
    /////////////////////////////////////////////////////////////////
    public function trash()
    {
        if ($this->trashed)
        {
            throw new \Exception("Message is already set as trash.");
        }
        $this->trashed = TRUE;
    }
    public function recover()
    {
        if (!$this->trashed)
        {
            throw new \Exception("Message is not trash, and cannot be recovered.");
        }
        $this->trashed = FALSE;
    }
    public function isTrash()
    {
        return ($this->trashed);
    }
    public function setTrashed($boolean)
    {
        $this->trashed = $boolean;
    }

    /////////////////////////////////////////////////////////////////
    // Viewed
    /////////////////////////////////////////////////////////////////
    public function isNew()
    {
        return (!$this->viewed);
    }
    public function view()
    {
        if ($this->viewed)
        {
            throw new \Exception("Message has already been viewed.");
        }
        $this->viewed = TRUE;
    }
    public function unView()
    {
        if (!$this->viewed)
        {
            throw new \Exception("Message has not been viewed yet.");
        }
        $this->viewed = FALSE;
    }
    public function setViewed($boolean)
    {
        $this->viewed = $boolean;
    }
    /////////////////////////////////////////////////////////////////
    // Stars
    /////////////////////////////////////////////////////////////////
    public function star()
    {
        if ($this->starred)
        {
            throw new \Exception("Message is already starred.");
        }
        $this->starred = TRUE;
    }
    public function unStar()
    {
        if (!$this->starred)
        {
            throw new \Exception("Message is not starred.");
        }
        $this->starred = FALSE;
    }
    public function isStarred()
    {
        return ($this->starred);
    }
    public function setStarred($boolean)
    {
        $this->starred = $boolean;
    }

    /////////////////////////////////////////////////////////////////
    // timeSent
    /////////////////////////////////////////////////////////////////
    public function getTimeSent()
    {
        return $this->timeSent;
    }
    public function setTimeSent($timeSent)
    {
        $this->timeSent = $timeSent;
    }

    /////////////////////////////////////////////////////////////////
    // humanReadableTime
    /////////////////////////////////////////////////////////////////
    public function getHumanReadableTime($timeSent)
    {
        return $this->humanReadableTime;
    }
    public function setHumanReadableTime($timeSent)
    {
        $this->humanReadableTime = $timeSent;
    }

    /////////////////////////////////////////////////////////////////
    // humanReadableTime2
    /////////////////////////////////////////////////////////////////
    public function getHumanReadableTime2($timeSent)
    {
        return $this->humanReadableTime2;
    }
    public function setHumanReadableTime2($timeSent)
    {
        $this->humanReadableTime2 = $timeSent;
    }

    /////////////////////////////////////////////////////////////////
    // Messages
    /////////////////////////////////////////////////////////////////
    public function setMessage($message)
    {
        if ($message === NULL)
        {
            throw new \Exception("Message cannot be NULL.");
        }
        if ($message == '')
        {
            throw new \Exception("Message cannot be empty.");
        }
        if (strlen($message) > MAX_MESSAGE_SIZE)
        {
            throw new \Exception("Message cannot exceed maximum size.");
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
    // public function getTimeSent()
    // {
    //     return $this->timeSent;
    // }
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
            throw new \Exception("ID # cannot be NULL.");
        }
        if (!$id)
        {
            throw new \Exception("ID # cannot be empty.");
        }
        if (gettype($id) !== 'integer')
        {
            throw new \Exception("ID # must be type Int.");
        }
        if ($id < 0)
        {
            throw new \Exception("ID # cannot be negative.");
        }
        if ($id >= MAX_INT_SIZE)
        {
            throw new \Exception("ID # cannot be maximum signed integer size.");
        }
        /*
        if ($id == $this->getID())
        {
            throw new Exception("Recipient ID # cannot match sender ID #.");
        }
        */

        $this->id = $id;
    }
    public function setSenderID($id)
    {
        if ($id === NULL)
        {
            throw new \Exception("ID # cannot be NULL.");
        }
        if (!$id)
        {
            throw new \Exception("ID # cannot be empty.");
        }
        if (gettype($id) !== 'integer')
        {
            throw new \Exception("ID # must be type Int.");
        }
        if ($id < 0)
        {
            throw new \Exception("ID # cannot be negative.");
        }
        if ($id >= MAX_INT_SIZE)
        {
            throw new \Exception("ID # cannot be maximum signed integer size.");
        }

        $this->id = $id;
    }
}
