<?php
class Comment {
    private $id;
    private $userId;
    private $postId;
    private $creation_date;
    private $text;

    public function __construct() {
        $this->id = -1;
        $this->userId = "";
        $this->postId = "";
        $this->creation_date = "";
        $this->text = "";
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setPostId($postId)
    {
        $this->postId = $postId;
    }

    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getCreationDate()
    {
        return $this->creation_date;
    }

    public function getText()
    {
        return $this->text;
    }

    public function loadCommentById(PDO $conn, $id) {
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE id=:id');
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
public function loadAllComments(PDO $conn) {
        $stmt = $conn->prepare('SELECT * FROM Comments'
            .' ORDER BY creation_date DESC');
        $result = $stmt->execute();
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
        $loadedComment = new Comment();
        $loadedComment->id = $row['id'];
        $loadedComment->userId = $row['user_id'];
        $loadedComment->postId = $row['post_id'];
        $loadedComment->text = $row['text'];
        $loadedComment->creation_date = $row['creation_date'];
        return $loadedComment;
    }

    static public function loadAllCommentsByPostId($conn, $postId) {
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE post_id=:post_id');
        $result = $stmt->execute([':post_id' => $postId]);
        $arr = [];
        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arr[] = self::copyData($row);
            }
        }
        return $arr;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id == -1) { // sprawdzanie czy obiektu nie ma już w bazie
            $stmt = $conn->prepare(
                'INSERT INTO Comments  (user_id, post_id, text, creation_date)
          VALUES (:user_id, :post_id, :text, :creation_date)'
            );

            $result = $stmt->execute([
                    ':user_id' => $this->userId,
                    ':post_id' => $this->postId,
                    ':text' => $this->text,
                    ':creation_date' => $this->creation_date
            ]);
            if ($result !== false) {
                  return  $conn->lastInsertId();
//                return true;
            }
        } else { // zapisanie obiektu już w bazie, ale zmodyfikowanego
            $stmt = $conn->prepare( // tu nawiasy po SET są zabronione
                'UPDATE Comments SET user_id=:user_id, post_id=:post_id, text=:text, creation_date=:creation_date  WHERE id=:id');
            $result = $stmt->execute ([
                ':id' => $this->id, // można podawać tylko obecne w wyrażeniu
                ':user_id' => $this->userId,
                ':post_id' => $this->postId,
                ':text' => $this->text,
                ':creation_date' => $this->creation_date
            ]);
            return $result;
        }
        return false;
    }

    public function delete (PDO $conn) {
        $id = $this->getId();
        if ($id != -1) {
            $stmt = $conn->prepare("DELETE FROM Comments"
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

}