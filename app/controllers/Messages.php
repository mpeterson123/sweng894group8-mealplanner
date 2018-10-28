<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Message Controller
///////////////////////////////////////////////////////////////////////////////
namespace Base\Controllers;

///////////////////////////////////////////////////////////////////////////////
// Autoload dependencies
///////////////////////////////////////////////////////////////////////////////
require_once __DIR__.'/../../vendor/autoload.php';

///////////////////////////////////////////////////////////////////////////////
// Standard classes
///////////////////////////////////////////////////////////////////////////////
use Base\Core\Controller;
use Base\Core\DatabaseHandler;
use Base\Helpers\Session;
use Base\Helpers\Redirect;
use Base\Helpers\Format;
use \Valitron\Validator;

///////////////////////////////////////////////////////////////////////////////
// Messages-specific classes
///////////////////////////////////////////////////////////////////////////////
use Base\Models\Message;

///////////////////////////////////////////////////////////////////////////////
// Inbox related definitions
///////////////////////////////////////////////////////////////////////////////
define('_DISPLAY_NORMAL_',  1);
define('_DISPLAY_STARS_',   2);
define('_DISPLAY_READ_',    3);
define('_DISPLAY_NEW_',     4);
define('_DISPLAY_TRASH_',   5);
define('_DISPLAY_SENT_',    6);

///////////////////////////////////////////////////////////////////////////////
// Messaging
///////////////////////////////////////////////////////////////////////////////
class Messages extends Controller
{
    private $messageRepository;
    private $dbh;

    public function __construct()
    {
        parent::__construct(...func_get_args());

        $this->dbh = DatabaseHandler::getInstance();
    }

    ///////////////////////////////////////////////////////////////////////////
    // Inbound messages
    ///////////////////////////////////////////////////////////////////////////
    public function inbox()
    {
        $this->view('message/inbox');
    }

    public function starred()
    {
        $displayType = _DISPLAY_STARS_;
        $this->view('message/inbox', compact('displayType'));
    }

    public function read()
    {
        $displayType = _DISPLAY_READ_;
        $this->view('message/inbox', compact('displayType'));
    }

    public function unread()
    {
        $displayType = _DISPLAY_NEW_;
        $this->view('message/inbox', compact('displayType'));
    }

    public function trash()
    {
        $displayType = _DISPLAY_TRASH_;
        $this->view('message/inbox', compact('displayType'));
    }

    public function open($messageID)
    {
        $this->view('message/open', compact('messageID'));
    }

    public function outbox()
    {
        $displayType = _DISPLAY_SENT_;
        $this->view('message/inbox', compact('displayType'));
    }

    public function compose($targetID = NULL)
    {
        $this->view('message/compose', compact('targetID'));
    }
}
