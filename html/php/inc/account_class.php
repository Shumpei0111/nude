<?php

class Account
{
  // Class properties (variables)

  // The ID of the logged in account (or NULL it there is no logged in account)
  private $id;
  private $name;
  private $mail;
  private $auth;

  // public class methods
  public function __construct()
  {
    // Initialize
    $this->id = NULL;
    $this->name = NULL;
    $this->mail = NULL;
    $this->auth = FALSE;
  }

  // Destructor
  public function __destruct()
  {

  }

  // Add a new account to the system and return its ID (the member_id column if the accounts table)
  public function addAccount(string $name, string $mail, string $passwd): ?int
  {
    global $pdo;

    $name = trim($name);
    $mail = trim($mail);
    $passwd = trim($passwd);

    if(!$this->isNameValid($name))
    {
      echo $name;
      throw new Exception("無効なユーザー名です");
    }

    if(!$this->isMailValid($mail))
    {
      echo $mail;
      throw new Exception("無効なメールアドレスです");
    }

    if(!$this->isPasswdValid($passwd))
    {
      throw new Exception("無効なパスワードです");
    }

    // 同一のユーザー名は弾く
    if(!is_null($this->getIdFromName($name)))
    {
      throw new Exception("ユーザー名が重複しているため登録できません");
    }

    $query = "INSERT INTO nude.mst_member (member_name, mail, pass) VALUES (:name, :mail, :passwd)";

    $hash = password_hash($passwd, PASSWORD_DEFAULT);

    $values = array(":name" => $name, ":mail" => $mail, ":passwd" => $hash);

    try {
      $res = $pdo->prepare($query);
      $res->execute($values);
    }
    catch (PDOException $e)
    {
      echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
      throw new Exception("---74---, Database query error");
    }

    return $pdo->lastInsertId();
  }

  public function isIdValid(int $id): bool
  {
    $valid = TRUE;

    if(($id < 1) || ($id > 10000))
    {
      $valid = FALSE;
    }

    return $valid;
  }


  public function isNameValid(string $name): bool
  {
    $valid = TRUE;

    $len = mb_strlen($name);

    if (($len < 8) || ($len > 16))
    {
      $valid = FALSE;
    }

    return $valid;
  }


  public function isMailValid(string $mail): bool
  {
    $valid = TRUE;

    $len = mb_strlen($mail);

    if (($len < 4) || ($len > 255))
    {
      $valid = FALSE;
    }

    return $valid;
  }


  public function isPasswdValid(string $passwd): bool
  {
    $valid = TRUE;

    $len = mb_strlen($passwd);

    if(($len < 8) || ($len > 16)) 
    {
      $valid = FALSE;
    }

    return $valid;
  }


  public function getIdFromName(string $name): ?int
  {
    global $pdo;

    if(!$this->isNameValid($name))
    {
      throw new Exception("無効なユーザー名です");
    }

    $id = NULL;

    $query = "SELECT member_id FROM nude.mst_member WHERE (member_name = :name)";
    $values = array(":name" => $name);

    try {
      $res = $pdo->prepare($query);
      $res->execute($values);
    }
    catch(PDOException $e) {
      echo $e->getMessage();
      throw new Exception("---142---, Database query error");
    }

    $row = $res->fetch(PDO::FETCH_ASSOC);

    if(is_array($row))
    {
      $id = intval($row["member_id"], 10);
    }

    return $id;
  }


  public function editAccount(int $id, string $name, string $mail, string $passwd, bool $enabled )
  {

    global $pdo;

    $name = trim($name);
    $mail = trim($mail);
    $passwd = trim($passwd);

    if(!$this->isIdValid($id))
    {
      echo $id;
      throw new Exception("無効なIDです");
    }

    if(!$this->isNameValid($name))
    {
      echo $name;
      throw new Exception("無効なユーザー名です");
    }

    if(!$this->isMailValid($mail))
    {
      echo $mail;
      throw new Exception("無効なメールアドレスです");
    }

    if(!$this->isPasswdValid($passwd))
    {
      throw new Exception("無効なパスワードです");
    }

    $idFromName = $this->getIdFromName($name);

    // 同一のユーザー名は弾く
    if(!is_null($idFromName) && ($idFromName != $id))
    {
      throw new Exception("ユーザー名が重複しているため登録できません");
    }

    $query = "UPDATE nude.mst_member SET member_name = :name, mail = :mail, pass = :passwd, enabled = :enabled WHERE member_id = :id";

    $hash - password_hash($pass, PASSWORD_DEFAULT);

    $intEnabled = $enabled ? 1 : 0;

    $values = array(":name" => $name, ":mail" => $mail, ":passwd" => $hash, ":enabled" => $intEnabled, ":id" => $id);

    try {
      $res = $pdo->prepare($query);
      $res->execute($values);
    }
    catch (PDOException $e)
    {
      echo $e->getMessage().' in '. $e->getFile().' on line '. $e->getLine();
      throw new Exception("Database query error");
    }

    // Delete an account (selected by its ID)
    public function deleteAccount(int $id)
    {
      global $pdo;

      if(!$this->isIdValid($id))
      {
        throw new Exception("無効なIDです");
      }

      $query = "UPDATE nude.mst_member SET deleted_at = CURRENT_TIMESTAMP WHERE member_id = :id";

      $values = array(":id" => $id);

      try {
        $res = $pdo->prepare($query);
        $res->execute($values);
      }
      catch (PDOException $e)
      {
        echo $e->getMessage().' in ' $e->getFile().' on line ' $e->getLine();
        throw new Exception("Database querry error");
      }

      $query = "DELETE FROM nude.member_sessions WHERE (member_id = :id)";

      $values = array(":id" => $id);

      try {
        $res = pdo->prepare($query);
        $res->execute($values);
      }
      catch (PDOException $e)
      {
        echo $e->getMessage().' in ' $e->getFile().' on line ' $e->getLine();
        throw new Exception("Database query error");
      }
    
    }

  }

} // ----- class