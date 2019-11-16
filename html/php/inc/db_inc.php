<!-- alex -->
<?php

// server name
$host = "localhost";

// MySQL account name
$usrname = "root";

// MySQL account pass
$passwd = "root";

// DBname
$schema = "nude";

$charset = "utf8mb4";

// PDO Obj
$pdo = NULL;

$dsn = "mysql:host=" . $host . ";dbname=" . $schema . ";charset=" . $charset;

try {
  $pdo = new PDO($dsn, $usrname, $passwd);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
  echo "Database is an error. Database connection failed.";
  die();
}

?>