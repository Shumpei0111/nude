<?php

if(isset($_POST["signin_submit"])) {
  $name = $_POST["name"];
  $pass = $_POST["pass"];
  $passRe = $_POST["pass_repeat"];

  $name = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
  $pass = htmlspecialchars($pass, ENT_QUOTES, "UTF-8");
  $passRe = htmlspecialchars($passRe, ENT_QUOTES, "UTF-8");

  
  if(empty($name) || empty($pass) || empty($passRe)) {
    header("Location: ./index.php?error=empty_fields");
    exit();
  } else if($pass !== $passRe) {
    header("Location: ./index.php?error=wrong_password");
    exit();
  } else {
    require "./php/inc/dbh.php";

    $code = 1;
    $pass = password_hash($pass, PASSWORD_BCRYPT);    

    $obj = new Dbh;
    $sql = "INSERT INTO mst_member(username, usercode, pass) VALUES(?, ?, ?)";
    $stmt = $obj->connect()->prepare($sql);
    $data[] = $name;
    $data[] = $code;
    $data[] = $pass;
    $stmt->execute($data);
    $obj = null;

    require "./php/inc/session.php";
    session_regenerate_id(true);

    $_SESSION["login"] = true;
    $_SESSION["name"] = $name;
    $_SESSION["token"] = base64_encode(openssl_random_pseudo_bytes(48));
    
    
  }
}
var_dump($_POST);
var_dump($_SESSION);

$title = "掲示板";
require "./php/inc/header.php";
?>


<h1>Nude</h1>

<p>User Name: <?php echo $_SESSION["name"]; ?></p>

<?php
require "./php/inc/footer.php"
?>