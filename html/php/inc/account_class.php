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

  // ------------------------------------------------------------------------ Add Account ------------------------------------------------------------------------ 

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

    if (($len < 4) || ($len > 16))
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

    $hash = password_hash($passwd, PASSWORD_DEFAULT);

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
      echo $e->getMessage().' in ' .$e->getFile().' on line ' .$e->getLine();
      throw new Exception("Database querry error");
    }

    $query = "DELETE FROM nude.member_sessions WHERE (member_id = :id)";

    $values = array(":id" => $id);

    try {
      $res = $pdo->prepare($query);
      $res->execute($values);
    }
    catch (PDOException $e)
    {
      echo $e->getMessage().' in ' .$e->getFile().' on line ' .$e->getLine();
      throw new Exception("Database query error");
    }
  
  }

  // ------------------------------------------------------------------------ Login and Logout ------------------------------------------------------------------------
  public function login(string $name, string $passwd): bool
  {
    global $pdo;

    $name = trim($name);
    $passwd = trim($passwd);

    if(!$this->isNameValid($name))
    {
      return FALSE;
    }

    if(!$this->isPasswdValid($passwd))
    {
      return FALSE;
    }
    $query = "SELECT * FROM nude.mst_member WHERE (member_name = :name) AND (enabled = 1)";

    $values = array(":name" => $name);

    try {
      $res = $pdo->prepare($query);
      $res->execute($values);
    }
    catch (PDOException $e)
    {
      echo $e->getMessage().' in ' .$e->getFile().' on line ' .$e->getLine();
      throw new Exception("Database query error");
    }

    $row = $res->fetch(PDO::FETCH_ASSOC);

    if(is_array($row))
    {
      if(password_verify($passwd, $row["pass"]))
      {
        $this->id = intval($row["member_id"], 10);
        $this->name = $name;
        $this->auth = TRUE;

        $this->registerLoginSessions();

        return TRUE;
      }
    }

    return FALSE;
  }

  public function getId()
  {
    return  $this->id;
  }

  public function setId(string $id)
  {
    if($this->isIdValid($id))
    {
      $this->id = $id;
    }
  }

  public function getName()
  {
    return  $this->name;
  }

  public function setName(string $name)
  {
    if($this->isNameValid($name))
    {
      $this->name = $name;
    }
  }

  // --------------------- Login time --------------------- 
  public function registerLoginSessions()
  {
    global $pdo;

    // Check that a Session has been started
    if(session_status() == PHP_SESSION_ACTIVE)
    {
      // Use a REPLACE statement to:
      //   - insert s new row with the session id, if it doesn't exist or... 
      //   - update the row having the session id, if it dosen't exist.
      $query = "REPLACE INTO nude.member_sessions (session_id, member_id, login_time) VALUE (:sid, :memberId, NOW())";
      $values = array(":sid" => session_id(), ":memberId" => $this->id);

      try {
        $res = $pdo->prepare($query);
        $res->execute($values);
      }
      catch (PDOException $e)
      {
        echo $e->getMessage().' in ' .$e->getFile().' on line ' .$e->getLine();
        throw new Exception("Database query error");
      }
    }
  }

  
  // --------------------- session --------------------- 
  public function sessionLogin()
  {
    global $pdo;

    if(session_status() == PHP_SESSION_ACTIVE)
    {
      // Query template to look for the current session ID on the member_sessions table.
      // The query also make sure the Session is not older than 7days
      $query = 

      "SELECT * FROM nude.member_sessions, nude.mst_member WHERE (member_sessions.session_id = :sid)" .
      "AND (member_sessions.login_time >= (NOW() - INTERVAL 7 DAY)) AND (member_sessions.member_id = mst_member.member_id)" .
      "AND (mst_member.enabled = 1)";

      $values = array(":sid" => session_id());

      try {
        $res = $pdo->prepare($query);
        $res->execute($values);
      }
      catch (PDOException $e)
      {
        echo $e->getMessage().' in ' .$e->getFile().' on line ' .$e->getLine();
        throw new Exception("Database query error");
      }

      $row = $res->fetch(PDO::FETCH_ASSOC);

      if(is_array($row))
      {
        // Authentication successed. Set the class properties (id and name) and return TRUE
        $this->id = intval($row["member_id"], 10);
        $this->name = $row["member_name"];
        $this->auth = TRUE;

        return TRUE;
      }
    }

    return FALSE;
  }


  // --------------------- Logout --------------------- 
  public function logout()
  {
    global $pdo;

    if(is_null($this->id))
    {
      return;
    }

    $this->id = NULL;
    $this->name = NULL;
    $this->mail = NULL;
    $this->auth = FALSE;

    if(session_status() == PHP_SESSION_ACTIVE)
    {
      $query = "DELETE FROM nude.member_sessions WHERE (session_id = :sid)";
      $values = array(":sid" => session_id());
    }

    try {
      $res = $pdo->prepare($query);
      $res->execute($values);
    }
    catch (PDOException $e)
    {
      echo $e->getMessage().' in ' .$e->getFile().' on line ' .$e->getLine();
      throw new Exception("Database query error");
    }
  }


  // Getter function for the $auth variable
  // Returns TRUE if the user is authentivated
  public function isAuth()
  {
    return $this->auth;
  }


  // Close all account Sessions except for the current one (aka: "logout form other deveices")
  public function closeOtherSessions()
  {
    global $pdo;

    if(is_null($this->id))
    {
      return;
    }

    if(session_status() == PHP_SESSION_ACTIVE)
    {
      $query = "DELETE FROM nude.member_sessions WHERE (session_id != :sid) AND (member_id = :member_id)";

      $values = array(":sid" => session_id(), "member_id" => $this->id);

      try {
        $res = $pdo->prepare($query);
        $res->execute($values);
      }
      catch (PDOException $e)
      {
        echo $e->getMessage().' in ' .$e->getFile().' on line ' .$e->getLine();
        throw new Exception("Database query error");
      }
    }
  } 



} // ----- class