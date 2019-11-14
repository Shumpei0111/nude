<?php

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

function logout() {
  $_SESSION = array();
  if(isset($_COOKIE[session_name()]) == true) {
    setcookie(session_name(), "", time()-42000, "/index.php");
  }
  session_destroy();
}

function notLogin() {
  if(isset($_SESSION["login"]) == false) {
    header("Location ./index.php?error=not_login_or_sing_in");
  }
}

?>