<?php

session_start();

require "./db_inc.php";
require "./account_class.php";

$account = new Account();

try {
  $newId = $account->addAccount("NameName", "aaaaa", "myPassword");
}
catch (Exception $e)
{
  echo $e->getMessage();
  die();
}

echo "The new account ID is" . $newId;