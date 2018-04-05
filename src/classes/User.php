<?php

require_once("../db.php");
require_once("Post.php");

class User
{
    private $ID;
    private $username;
    private $email;
    private $isModerator;

    public function getID()
    {
        return $this->ID;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getModerator()
    {
        return $this->isModerator;
    }

    function __construct($ID, $username, $email)
    {
        $this->ID = $ID;
        $this->username = $username;
        $this->email = $email;
    }

    static function fromRow($row)
    {
        $u = new User($row["ID"], $row["Username"], $row["Email"]);
        $u->setModerator($row["IsModerator"]);
    }

    static function fromID($ID)
    {
        $db = connect();
        $SQL = "SELECT * FROM $TABLE_User WHERE ID = ':id'";
        $statement = $db->prepare($SQL);
        $statement->bindParam(":id", $ID);
        $statement->execute();
        $row = $statement->fetch();

        if (!$row)
            return null;

        return fromRow($row);
    }

    static function fromUsername($username)
    {
        $db = connect();
        $SQL = "SELECT * FROM $TABLE_User WHERE Username = ':username'";
        $statement = $db->prepare($SQL);
        $statement->bindParam(":username", $username);
        $statement->execute();
        $row = $statement->fetch();

        if (!$row)
            return null;

        return fromRow($row);
    }
        
    static function emailExists($email)
    {
        $SQL = "SELECT ID FROM User WHERE Email = :email";
        $statement = $db->prepare($SQL);
        $statement->bindParam(":email", $email);
        $statement->execute();
        $row = $statement->fetch();

        if (!$row)
            return false;
        else
            return true;
    }

    static function usernameExists($username)
    {
        $SQL = "SELECT ID FROM User WHERE Username = :username";
        $statement = $db->prepare($SQL);
        $statement->bindParam(":username", $username);
        $statement->execute();
        $row = $statement->fetch();

        if (!$row)
            return false;
        else
            return true;
    }

    static function idExists($id)
    {
        $SQL = "SELECT ID FROM USER WHERE ID = :id";
        $statement = $db->prepare($SQL);
        $statement->bindParam(":id", $id);
        $statement->execute();
        $row = $statement->fetch();

        if (!$row)
            return false;
        else
            return true;

    }
    
    const Error_EmailExists = "Email already in database.";
    const Error_UsernameExists = "Username already in database.";

    /* Insertion d'un utilisateur dans la base de données */
    static function create($username, $email, $password)
    {
        if (User::emailExists($email))
            return Error_EmailExists;
        else if (User::usernameExists($username))
            return Error_UsernameExists;

        $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
        $id = uniqid();

        $db = connect();
        $SQL = "INSERT INTO User (ID, Username, Email, Password, Moderator) VALUES (:id, :username, :email, :password, 0)";
        $statement = $db->prepare($SQL);
        $statement->bindParam(":id", $id);
        $statement->bindParam(":username", $username);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":password", $hash);

        return new User($id, $username, $email);
    }

    static function testPassword($ID, $attempt)
    {
        $db = connect();
        $SQL = "SELECT Password FROM User WHERE ID = :id";
        $statement = $db->prepare($SQL);
        $statement->bindParam(":id", $ID);
        $statement->execute();

        $row = $statement->fetch();
        $hash = $row["Password"];

        return password_verify($attempt, $hash);
    }

    function findPosts($limit = 50)
    {
        $db = connect();
        $SQL = "SELECT * FROM Posts WHERE Author = :id LIMIT $limit ORDER BY Timestamp DESC";
        $statement = $db->prepare($SQL);
        $statement->bindParam(":id", $this->getID());
        $statement->execute();
        $rows = $statement->fetchall();

        $posts = array();
        foreach($rows as $row)
            array_push($posts, Post::fromRow($row));
 
        return $posts;
    }
    
    function update($newId, $newEmail, $newUsername)
    {
        $db = connect();
        
        $this->ID = $newId;
        $this->email = $newEmail;
        $this->username = $newUsername;

        $SQL = "UPDATE User SET Email = :email, Username = :username";
        $statement = $db->prepare($SQL);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":username", $username);
        $statement->execute();

        if ($statement->rowCount() == 1)
            return true;
        else
            return false;
    }

    function delete()
    {
        $db = connect();
        
        $SQL = "DELETE FROM User WHERE ID = :id";
        $statement = $db->prepare($SQL);
        $statement->bindParam(":id", $this->id);
        $statement->execute();

        if ($statement->rowCount() == 1)
            return true;
        else
            return false;
    }
}
?>