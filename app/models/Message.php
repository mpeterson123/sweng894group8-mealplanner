<?php
/////////////////////////////////////////////////////////////////////
// Message                                   SWENG894 [Group 8] 2018
/////////////////////////////////////////////////////////////////////
// Primary Object Class
/////////////////////////////////////////////////////////////////////
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Message
{
    private $id;

    private $starred;
    private $trash;
    private $viewed;

    private $senttime;
    private $message;
    private $senderid;
    private $recipientid;

    public function isTrash()
    {
        return ($this->trash);
    }

    public function isStarred()
    {
        return ($this->starred);
    }

    public function isNew()
    {
        return (!$this->viewed);
    }

    public function getID()
    {
        return $this->id;
    }

    public function star()
    {
    }

    public function unStar()
    {
    }

    public function send()
    {
    }

}
