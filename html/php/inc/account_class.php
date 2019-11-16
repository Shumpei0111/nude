<?php

class Account
{
  // Class properties (variables)

  // The ID of the logged in account (or NULL it there is no logged in account)
  private $id;
  private $name;
  private $auth;

  // public class methods
  public function __construct()
  {
    // Initialize
    $this->id = NULL;
    $this->name = NULL;
    $this->auth = FALSE;
  }

  // Destructor
  public function __destruct()
  {

  }

  // Add a new account to the system and return its ID (the member_id column if the accounts table)
  public function addAccount(string $name, string $passwd): int
  {
    global $pdo;

    $name = trim($name);
    $passwd = trim($passwd);

    if(!$this->isNameValid($name))
    {
      throw new Exception("無効なユーザー名です"):
    }

    if(!$this->isPasswdValid($passwd))
    {
      throw new Exception("無効なパスワードです");
    }

    if(!is_null($this->getIdFromName($name)))
    {
      throw new Exception("ユーザー名が登録できません");
    }

    $query = "INSERT INTO nude.mst_member (member_name, pass) VALUES (:name, :passwd)";

    $hash = password_hash($passwd, PASSWORD_DEFAULT);

    $values = array(":name" => $name, ":pass" => $hash);

    try {
      $res = $pdo->prepare($query);
      $res->execute($values);
    }
    catch (PDOException $e)
    {
      throw new Exception("Database query error");
    }

    return $pdo->lastInsertId();
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

    if(!this->isNameValid($name))
    {
      throw new Exception("無効なユーザー名です");
    }

    $id = NULL;

    $query = "SELECT member_id FROM nude.mst_member WHERE (account_name = :name)";
    $values = array(":name" => $name);

    try {
      $res = $pdo->prepare($query);
      $res->execute($values);
    }
    catch(PDOException $e) {
      throw new Exception("Database query error");
    }

    $row = $res->fetch(PDO::FETCH_ASSOC);

    if(is_array($row))
    {
      $id = intval($row["account_id"], 10);
    }

    return $id;
  }

} // ----- class