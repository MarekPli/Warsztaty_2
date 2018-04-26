<?php

class User
{
    private $id;
    private $username;
    private $hashPass;
    private $email;

    public function __construct() {
        $this->id = -1;
        $this->username = "";
        $this->email = "";
        $this->hashPass = "";
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($newPass) {
        $newHashedPass = password_hash($newPass, PASSWORD_BCRYPT);
        $this->hashPass = $newHashedPass;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getHashPass()
    {
        return $this->hashPass;
    }

    public function delete (PDO $conn) {
        $id = $this->getId();
        echo "Indeks: $id";
        if ($id != -1) {
            $stmt = $conn->prepare("DELETE FROM Users"
                . " WHERE id=:id");
            $result = $stmt->execute([':id' => $id]);
            if ($result === true) {
                echo "Indeks: $id";
                $this->id = -1;
                echo "udało się<br>";
            }
            return $result;
        }
        return false;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id == -1) { // sprawdzanie czy obiektu nie ma już w bazie
            $stmt = $conn->prepare(
              'INSERT INTO Users (username, email, hash_pass)
              VALUES (:username, :email, :pass)'
            );
            $result = $stmt->execute([':username' => $this->username,
                ':email' => $this->email,
                ':pass' => $this->hashPass]);
            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else { // zapisanie obiektu już w bazie, ale zmodyfikowanego
            $stmt = $conn->prepare(
                'UPDATE Users SET email=:email, username=:username, 
              hash_pass=:hash_pass WHERE id=:id');
            $result = $stmt->execute ([
                ':id' => $this->is,
                ':email' => $this->email,
                ':username' => $this->username,
                ':hash_pass' => $this->hashPass
            ]);
            return $result;
        }
        return false;
    }

    static public function loadUserById (PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute([':id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return false;
    }

    static public function loadAll (PDO $conn)
    {
        $stmt = $conn->prepare('SELECT * FROM Users');
        $result = $stmt->execute();
        $arr = [];
        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];

                //długo szukałem błędu, napisałem i źle działało:
//                $loadedUser->id = $row[$id];


                $loadedUser->username = $row['username'];
                $loadedUser->hashPass = $row['hash_pass'];
                $loadedUser->email = $row['email'];
                $arr[] = $loadedUser;
            }
        }
        return $arr;
    }

    static public function is_email (PDO $conn, $email) {
            $stmt = $conn->prepare("SELECT email FROM Users"
             . " WHERE email=:email");
            $stmt->execute([':email' => $email]);
            $result = $stmt->rowCount();
            if ($result > 0) {
                return true;
            }
            return false;
    }
    static public function is_username (PDO $conn, $username) {
            $stmt = $conn->prepare("SELECT username FROM Users"
             . " WHERE username=:username");
            $stmt->execute([':username' => $username]);
            $result = $stmt->rowCount();
            if ($result > 0) {
                return true;
            }
            return false;
    }
}