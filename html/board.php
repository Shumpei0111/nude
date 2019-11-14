<?php
// var_dump($_POST);

require "./php/inc/functions.php";

if(isset($_POST["signin_submit"])) {
  $name = $_POST["name"];
  $mail = $_POST["mail"];
  $pass = $_POST["pass"];
  $passRe = $_POST["pass_repeat"];

  addAcc($mail);  

  // char escape
  $name = h($name);
  $mail = h($mail);
  $pass = h($pass);
  $passRe = h($passRe);
  
  if(empty($name) || empty($mail) || empty($pass) || empty($passRe)) {
    header("Location: ./index.php?error=empty_fields");
    exit();
  } else if($pass !== $passRe) {
    header("Location: ./index.php?error=wrong_password");
    exit();
  } else {
    try {
      require "./php/inc/dbh.php";

      $pass = password_hash($pass, PASSWORD_DEFAULT);    

      $obj = new Dbh;
      $sql = "INSERT INTO mst_member(username, mail, pass) VALUES(?, ?, ?)";
      $stmt = $obj->connect()->prepare($sql);
      $data[] = $name;
      $data[] = $mail;
      $data[] = $pass;
      $stmt->execute($data);
      $obj = null;

      require "./php/inc/session.php";

      $_SESSION["name"] = $name;

    } catch(Exception $e) {
      echo "データベースの接続に失敗しました";
      echo $e->getMessage();
      exit();
    }  
    
  }
}

/*********** LogIn ***********/

if(isset($_POST["login_submit"])) {
  $login_mail = $_POST["login_mail"];
  $login_pass = $_POST["login_pass"];

  // char escape
  $login_mail = h($login_mail);
  $login_pass = h($login_pass);
  
  if(empty($login_mail) || empty($login_pass)) {
    header("Location: ./index.php?error=empty_fields");
    exit();
  } else {
    try {
      require "./php/inc/dbh.php";


      $obj = new Dbh;
      $sql = "SELECT * FROM mst_member WHERE mail = :mail";
      $stmt = $obj->connect()->prepare($sql);
      $stmt->bindParam(":mail", $login_mail);
      $stmt->execute();
      // $res = $stmt->fetch();
      $res = $stmt->fetch(PDO::FETCH_ASSOC);
      $obj = null;

      // var_dump($res);

      // $res = $stmt->fetch(PDO::FETCH_ASSOC);
      try{
        if(password_verify($login_pass, $res["pass"])) {
          echo "pass is correct";
          echo "ログインしました";

          require "./php/inc/session.php";
          $_SESSION["name"] = $res["username"];

          $name = $_SESSION["name"];
        }
      } catch(Exception $e) { 
        echo $e->getMessage();
        header("Location: ./index.php?error=mail_or_password_are_wrong");        
      }

    } catch(Exception $e) {
      echo "データベースの接続に失敗しました";
      echo $e->getMessage();
      exit();
    }  
    
  }
}
// var_dump($_SESSION);

$title = "掲示板";
require "./php/inc/header.php";
?>


<h1>Nude</h1>

<p>User Name: <?php echo $name; ?></p>

<a href="/index.php" onclick="<?php logout()?>">logout</a>

<?php
require "./php/inc/footer.php"
?>