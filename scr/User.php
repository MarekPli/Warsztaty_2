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

    public function saveToDB(PDO $conn) {
        if ($this->id == -1) {
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
        }
        return false;
    }
}