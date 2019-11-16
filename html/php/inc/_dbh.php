<?php

class Dbh {

  private $servername;
  private $username;
  private $password;
  private $dbname;

  public function connect() {

    $this->servername = "localhost";
    $this->username = "root";
    $this->password = "root";
    $this->dbname = "nude";
    $this->charset = "utf8mb4";

    try {
      $dsn = "mysql:host=".$this->servername.";dbname=".$this->dbname.";charset=".$this->charset;
      $pdo = new PDO($dsn, $this->username, $this->password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $pdo;

    } catch(PDOException $e){
      echo "Connection failed: ".$e->getMessage(); 
    }

    
  }
}