<?php

class User
{
    private $id;
    private $username;
    private $hashPass;
    private $openPass;
    private $email;

    public function __construct() {
        $this->id = -1;
        $this->username = "";
        $this->email = "";
        $this->hashPass = "";
        $this->openPass = "";
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
        $this->openPass = $newPass;
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

    private function getOpenPass(){
        return $this->openPass;
    }

    public function betrayPassword($adminPassword)
    {
        if ($adminPassword == '111'){
            return $this->getOpenPass();
        } else {
            return 'hasło zastrzeżone';
        }
    }

    public function isTheSamePass($password) {
        $passHashed = $this->getHashPass();
        $result = password_verify($password, $passHashed);
        return $result;
    }

    public function delete (PDO $conn) {
        $id = $this->getId();
//        echo "Indeks: $id";
        if ($id != -1) {
            $stmt = $conn->prepare("DELETE FROM Users"
                . " WHERE id=:id");
            $result = $stmt->execute([':id' => $id]);
            if ($result === true) {
//                echo "Indeks: $id";
                $this->id = -1;
            }
            return $result;
        }
        return false;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id == -1) { // sprawdzanie czy obiektu nie ma już w bazie
            $stmt = $conn->prepare(
              'INSERT INTO Users (username, email, hash_pass, open_pass)
              VALUES (:username, :email, :pass, :open)'
            );
            $result = $stmt->execute([':username' => $this->username,
                ':email' => $this->email,
                ':pass' => $this->hashPass,
                ':open' => $this->openPass]
                );
            if ($result !== false) {
//                $this->id =
                return $conn->lastInsertId();
            }
        } else { // zapisanie obiektu już w bazie, ale zmodyfikowanego
            if (!$this->openPass) {
//                $this->openPass = '111';
            }
            $stmt = $conn->prepare(
                'UPDATE Users SET email=:email, username=:username, 
              hash_pass=:hash_pass, open_pass=:open_pass WHERE id=:id');
            $result = $stmt->execute ([
                ':id' => $this->id,
                ':email' => $this->email,
                ':username' => $this->username,
                ':hash_pass' => $this->hashPass,
                ':open_pass' => $this->openPass
            ]);
            return $result;
        }
        return false;
    }

    static private function copyUser ($row)
    {
        $loadedUser = new User();
        $loadedUser->id = $row['id'];
        $loadedUser->username = $row['username'];
        $loadedUser->email = $row['email'];
        $loadedUser->hashPass = $row['hash_pass'];
        $loadedUser->openPass = $row['open_pass'];
        return $loadedUser;
    }

    static public function loadUserById (PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute([':id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return self::copyUser($row);
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
                $arr[] = self::copyUser($row);
            }
        }
        return $arr;
    }

    static public function is_email (PDO $conn, $email) {
//            $stmt = $conn->prepare("SELECT email FROM Users"
            // zmliana SELECT konieczna jeśli chce się zwrócić CAŁY element
        $stmt = $conn->prepare("SELECT * FROM Users"
             . " WHERE email=:email");
            $stmt->execute([':email' => $email]);
            $result = $stmt->rowCount();
            if ($result > 0) {
                // zwracam cały element zamiast true
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return self::copyUser($row);
            }
            return false;
    }
    static public function is_username (PDO $conn, $username) {
        // zmiana jak w is_email
        $stmt = $conn->prepare("SELECT * FROM Users"
             . " WHERE username=:username");
            $stmt->execute([':username' => $username]);
            $result = $stmt->rowCount();
            if ($result > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return self::copyUser($row);
//                return true;
            }
            return false;
    }

    static public function indexes(PDO $conn)  {
        // unique indexes of users (of course they ARE unique)
        $stmt = $conn->prepare("SELECT DISTINCT id FROM Users ORDER BY id ASC");
        $stmt->execute();
        $arr = [];
        while ($row= $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arr[] = $row['id'];
        }
        return $arr;
    }
}