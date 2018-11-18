<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                            Penn State - Cohorts 19 & 20 (@) 2018
///////////////////////////////////////////////////////////////////////////////
// Message Repository
///////////////////////////////////////////////////////////////////////////////
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;
// File-specific classes
use Base\Factories\MessageFactory;

class MessageRepository extends Repository implements EditableModelRepository
{
    private $db;
    private $messageFactory;

    public function __construct($db, $messageFactory)
    {
        $this->db = $db;
        $this->messageFactory = $messageFactory;
    }

    /**
     * Find a message by its ID #
     * @param  integer     $id items's id
     * @return array       associative array of message
     */
    public function find($id)
    {
        // Prep query
        $query = $this->db->prepare('SELECT * FROM messages WHERE id = ?');
        $query->bind_param("s", $id);

        if(!$query->execute()){
            return NULL;
        }
        $result = $query->get_result();

        if(!$result || !$result->num_rows){
            return NULL;
        }
        $messageRow = $result->fetch_assoc();
        $message = $this->messageFactory->make($messageRow);

        return $message;
    }

    /**
     * Inserts or updates a message in the database
     * @param  Base\Models\Message $message item to be saved
     * @return void
     */
    public function save($message)
    {
        if ($message->getID() && $this->find($message->getID()))
        {
            $this->update($message);
        }
        else
        {
            $this->insert($message);
        }
    }

    /**
     * Get all messages received by a user
     * @param  User  $recipient
     * @return array Associative array of messages
     */
    public function allForRecipient($recipient)
    {
        $query = $this->db->prepare("SELECT *, TIME_FORMAT(timesent, '%I:%m %p') AS timesent2, DATE_FORMAT(timesent, '%b %D') AS timesent3 FROM messages WHERE recipientid = ? ORDER by timesent");
        @$query->bind_param("s", $recipient->getId());

        // Execute query
        $query->execute();

        // Retrieve results
        $result = $query->get_result();

        // Transform results into array of associative arrays
        $messages = $result->fetch_all(MYSQLI_ASSOC);

        // Init the inbox (list of Message objects)
        $inbox = array();

        // Loop through the messages
        foreach($messages as $message)
        {
            // Add a Message object to the inbox
            $inbox[] = $this->messageFactory->make($message);
        }

        // Return our recipient's inbox
        return $inbox;
    }

    /**
     * Get last few inbound messages for a user
     * @param  User  $recipient
     * @return array Associative array of messages
     */
    public function lastFew($recipient)
    {
        $query = $this->db->prepare("SELECT *, DATE_FORMAT(timesent, '%I %p') AS timesent2 FROM messages WHERE recipientid = ? ORDER by timesent LIMIT 5");
        @$query->bind_param("s", $recipient->getId());

        // Execute query
        $query->execute();

        // Retrieve results
        $result = $query->get_result();

        // Transform results into array of associative arrays
        $messages = $result->fetch_all(MYSQLI_ASSOC);

        // Init the inbox (list of Message objects)
        $inbox = array();

        // Loop through the messages
        foreach($messages as $message)
        {
            // Add a Message object to the inbox
            $inbox[] = $this->messageFactory->make($message);
        }

        // Return our recipient's inbox
        return $inbox;
    }

    /**
     * Get all messages sent by a user
     * @param  User  $sender
     * @return array Associative array of messages
     */
    public function allForSender($sender)
    {
        $query = $this->db->prepare('SELECT * FROM messages WHERE senderid = ? ORDER by timesent');
        @$query->bind_param("s", $sender->getId());

        // Execute query
        $query->execute();

        // Retrieve results
        $result = $query->get_result();

        // Transform results into array of associative arrays
        $messages = $result->fetch_all(MYSQLI_ASSOC);

        // Init the outbox (list of Message objects)
        $outbox = array();

        // Loop through the messages
        foreach($messages as $message)
        {
            // Add a Message object to the outbox
            $outbox[] = $this->messageFactory->make($message);
        }

        // Return our sender's outbox
        return $outbox;
    }

    /**
     * Send message
     * @param  Base\Models\Message $message   Message to be sent
     * @return bool                           Whether query was successful
     */
    public function send($message)
    {
        // Prepare query
        $query = $this->db->prepare('INSERT INTO messages (senderid, recipientid, message) VALUES (?, ?, ?)');
        @$query->bind_param("iis",
            $message->getSenderID(),
            $message->getRecipientID(),
            $message->getMessage()
        );

        // Simultaneously execute query and return result
        return $query->execute();
    }

    /**
     * Trash message
     * @param  Base\Models\Message $message to be trashed
     * @return bool                Whether query was successful
     */
    public function trash($message)
    {
        // Prepare update query
        $query = $this->db->prepare('UPDATE messages SET trash = TRUE WHERE id = ?');
        @$query->bind_param("i", $message->getID());

        // Execute query
        $query->execute();
    }

    /**
     * Un-Trash message
     * @param  Base\Models\Message $message to be un-trashed
     * @return bool                Whether query was successful
     */
    public function unTrash($message)
    {
        // Prepare update query
        $query = $this->db->prepare('UPDATE messages SET trash = FALSE WHERE id = ?');
        @$query->bind_param("i", $message->getID());

        // Execute query
        $query->execute();
    }

    /**
     * View message
     * @param  Base\Models\Message $message to be viewed
     * @return bool                Whether query was successful
     */
    public function view($message)
    {
        // Prepare update query
        $query = $this->db->prepare('UPDATE messages SET viewed = TRUE WHERE id = ?');
        @$query->bind_param("i", $message->getID());

        // Execute query
        $query->execute();
    }

    /**
     * Un-View message
     * @param  Base\Models\Message $message to be un-viewed
     * @return bool                Whether query was successful
     */
    public function unView($message)
    {
        // Prepare update query
        $query = $this->db->prepare('UPDATE messages SET viewed = FALSE WHERE id = ?');
        @$query->bind_param("i", $message->getID());

        // Execute query
        $query->execute();
    }

    /**
     * Star message
     * @param  Base\Models\Message $message to be starred
     * @return bool                Whether query was successful
     */
    public function star($message)
    {
        // Prepare update query
        $query = $this->db->prepare('UPDATE messages SET starred = TRUE WHERE id = ?');
        @$query->bind_param("i", $message->getID());

        // Execute query
        $query->execute();
    }

    /**
     * Un-Star message
     * @param  Base\Models\Message $message to be un-starred
     * @return bool                Whether query was successful
     */
    public function unStar($message)
    {
        // Prepare update query
        $query = $this->db->prepare('UPDATE messages SET starred = FALSE WHERE id = ?');
        @$query->bind_param("i", $message->getID());

        // Execute query
        $query->execute();
    }

}
