<?php
class Tweet
{

    private $id;
    private $userId;
    private $tweet;
    private $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = "";
        $this->tweet = "";
        $this->creationDate = "";
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setTweet($tweet)
    {
        $this->tweet = $tweet;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getTweet()
    {
        return $this->tweet;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id == -1) { // sprawdzanie czy obiektu nie ma już w bazie
            $stmt = $conn->prepare(
                'INSERT INTO Tweets  (user_id, tweet, creation_date)
              VALUES (:user_id, :tweet, :creation_date)'
            );

            $result = $stmt->execute([
                    ':user_id' => $this->userId,
                    ':tweet' => $this->tweet,
                    ':creation_date' => $this->creationDate]
            );
            if ($result !== false) {
                return $conn->lastInsertId();
//                return true;
            }
        } else { // zapisanie obiektu już w bazie, ale zmodyfikowanego
            $stmt = $conn->prepare(
                'UPDATE Tweets SET user_id=:user_id, tweet=:tweet, creation_date=:creation_date  WHERE id=:id');
            $result = $stmt->execute ([
                ':id' => $this->id,
                ':user_id' => $this->userId,
                ':tweet' => $this->tweet,
                ':creation_date' => $this->creationDate
            ]);
            return $result;
        }
        return false;
    }

    public function delete (PDO $conn) {
        $id = $this->getId();
        if ($id != -1) {
            $stmt = $conn->prepare("DELETE FROM Tweets"
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

    static public function loadTweets(PDO $conn)
    {
        $stmt = $conn->prepare('SELECT * FROM Tweets ORDER BY  creation_date DESC, id  DESC'
//  trzeba było szukać sortowania złożonego - 2 kolumny
// no bo co z tweetami powstałymi tego samego dnia?
        );
        $result = $stmt->execute();
        $arr = [];
        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arr[] = self::copyData($row);
            }
        }
        return $arr;
    }

    static public function loadTweetById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Tweets WHERE id=:id');
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

    static private function copyData($row)
    {
        $loadedTweet = new Tweet();
        $loadedTweet->id = $row['id'];
        $loadedTweet->userId = $row['user_id'];
        $loadedTweet->tweet = $row['tweet'];
        $loadedTweet->creationDate = $row['creation_date'];
        return $loadedTweet;
    }

    static public function loadAllTweetsByUserId(PDO $conn, $user_id)
    {
        $stmt = $conn->prepare('SELECT * FROM Tweets WHERE user_id=:user_id'
        . ' ORDER BY creation_date DESC, id DESC');
        $result = $stmt->execute([':user_id' => $user_id]);
        $arr = [];
        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arr[] = self::copyData($row);
            }
        }
        return $arr;
    }
}
