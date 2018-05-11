<?php
/**
 * Created by PhpStorm.
 * User: marek
 * Date: 08.05.2018
 * Time: 07:20
 */
class Message
{

    private $id;
    private $senderId;
    private $receiverId;
    private $text;
    private $wasRead;
    private $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->senderId = "";
        $this->receiverId = "";
        $this->text = "";
        $this->wasRead = 0;
    }

    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    }

    public function setReceiverId($receiverId)
    {
        $this->receiverId = $receiverId;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function setWasRead($wasRead)
    {
        $this->wasRead = $wasRead;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSenderId()
    {
        return $this->senderId;
    }

    public function getReceiverId()
    {
        return $this->receiverId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getWasRead()
    {
        return $this->wasRead;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id == -1) { // sprawdzanie czy obiektu nie ma już w bazie
            $stmt = $conn->prepare(
                'INSERT INTO Messages  (sender_id, receiver_id, text, creation_date, was_read)
              VALUES (:sender_id, :receiver_id, :text, :creation_date, :was_read)'
            );

            $result = $stmt->execute([
//                    ':id' => $this->id, // obecność tej linii generuje błąd
                    ':sender_id' => $this->senderId,
                    ':receiver_id' => $this->receiverId,
                    ':text' => $this->text,
                    ':creation_date' => $this->creationDate,
                    ':was_read' => $this->wasRead
            ]);
            if ($result !== false) {
                    return $conn->lastInsertId();
//                return true;
            }
        } else { // zapisanie obiektu już w bazie, ale zmodyfikowanego
            $stmt = $conn->prepare(
                'UPDATE Messages SET sender_id=:sender_id, receiver_id=:receiver_id, text=:text, creation_date=:creation_date, 
was_read=:was_read'
                . ' WHERE id=:id');
            $result = $stmt->execute ([
                ':id' => $this->id,
                ':sender_id' => $this->senderId,
                ':receiver_id' => $this->receiverId,
                ':text' => $this->text,
                ':creation_date' => $this->creationDate,
                ':was_read' => $this->wasRead
            ]);
            return $result;
        }
        return false;
    }

    public function delete (PDO $conn) {
        $id = $this->getId();
        if ($id != -1) {
            $stmt = $conn->prepare("DELETE FROM Messages"
                . " WHERE id=:id");
            $result = $stmt->execute([':id' => $id]);
            if ($result === true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return false;
    }

    static public function loadMessages(PDO $conn)
    {
        $stmt = $conn->prepare('SELECT * FROM Messages ORDER BY  creation_date DESC');
        $result = $stmt->execute();
        $arr = [];
        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arr[] = self::copyData($row);
            }
        }
        return $arr;
    }

    static public function loadMessageById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE id=:id');
        $result = $stmt->execute([':id' => $id]);
        $arr = [];
        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arr[] = self::copyData($row);
            }
        }
        if (count([$arr]) >1 or count([$arr]) ==0) {
            return $arr;
        } else {
            return $arr[0];
        }
    }
    static public function loadMessagesIfId(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Messages'
            .' WHERE sender_id=:id OR receiver_id=:id'
            .' ORDER BY creation_date DESC');
        $result = $stmt->execute([':id' => $id]);
        $arr = [];
        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arr[] = self::copyData($row);
            }
        }
        return $arr;
    }

    static private function copyData($row)
    {
        $loadedMessage = new Message();
        $loadedMessage->id = $row['id'];
        $loadedMessage->senderId = $row['sender_id'];
        $loadedMessage->receiverId = $row['receiver_id'];
        $loadedMessage->text = $row['text'];
        $loadedMessage->creationDate = $row['creation_date'];
        $loadedMessage->wasRead = $row['was_read'];
        return $loadedMessage;
    }

    static public function loadAllMessagesBySender(PDO $conn, $sender)
    {
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE sender=:sender order by creation_date DESC' );
        $result = $stmt->execute([':sender' => $sender]);
        $arr = [];
        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arr[] = copyData($row);
            }
        }
        return $arr;
    }
    static public function loadAllMessagesByReceiver(PDO $conn, $receiver)
    {
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE receiver=:receiver ORDER BY creation_date DESC');
        $result = $stmt->execute([':receiver' => $receiver]);
        $arr = [];
        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arr[] = copyData($row);
            }
        }
        return $arr;
    }
}
