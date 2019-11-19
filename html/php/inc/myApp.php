<?php

session_start();

require "./db_inc.php";
require "./account_class.php";

// New sample
$account = new Account();

// try {
//   $newId = $account->addAccount("kimuratarou", "aaaaa", "myPassword");
// }
// catch (Exception $e)
// {
//   echo $e->getMessage().' in ' . $e->getFile().' on line ' . $e->getLine();
//   die();
// }

// echo "The new account ID is" . $newId;




$accountId = 10;

// Edit sample
// try {
//   $account->editAccount($accountId, "kimurataro", "erererere", "asasasasas", TRUE);
// }
// catch (PDOException $e)
// {
//   echo $e->getMessage().' in ' . $e->getFile().' on line ' . $e->getLine();
//   die();
// }
// echo "Account edit successful";


// Delete Sample
// try {
//   $account->deleteAccount($accountId);
// }
// catch (PDOException $e)
// {
//   echo $e->getMessage().' in ' . $e->getFile().' on line ' . $e->getLine();
//   die();
// }
// echo "Delete success";


//Login sample
// $login = FALSE;
// try {
//   $login = $account->login("kimurataro", "asasasasas");
// }
// catch (PDOException $e)
// {
//   echo $e->getMessage().' in ' . $e->getFile().' on line ' . $e->getLine();
//   die();
// }

// if($login)
// {
//   echo "Login Success, ID is :".$account->getId()."<br>";
//   echo "Account Name is :".$account->getName();
// } else {
//   echo "Login failed";
// }


// Logout sample
// try {
//   $login = $account->login("kimurataro", "asasasasas");
//   if($login)
//   {
//     echo "___Logout sample test___...login success";
//     echo "Login Id is:".$account->getId()."<br>";
//     echo "Name is:".$account->getName();
//   } else {
//     echo "__Logout sample test__... failed";
//   }

//   $account->logout();
//   $login = $account->sessionLogin();
//   if($login)
//   {
//     echo "session login success";
//     echo "Login Id is:".$account->getId()."<br>";
//     echo "Name is:".$account->getName();
//   } else {
//     echo "__Logout sample test__... failed";
//   }
// }
// catch (PDOException $e)
// {
//   echo $e->getMessage().' in ' . $e->getFile().' on line ' . $e->getLine();
//   die();
// }
// echo "Logout Success";


// Session Login sample
// $login = FALSE;
// try {
//   $login = $account->sessionLogin();
// }
// catch (PDOException $e)
// {
//   echo $e->getMessage().' in ' . $e->getFile().' on line ' . $e->getLine();
//   die();
// }

// if($login)
// {
//   echo "SessionLogin Success, ID is :".$account->getId()."<br>";
//   echo "Account Name is :".$account->getName();
// } else {
//   echo "Login failed";
// }


// Close other open Sessions
try {
  $login = $account->login("kimurataro", "asasasasas");
  if($login)
  {
    echo "SessionLogin Success, ID is :".$account->getId()."<br>";
    echo "Account Name is :".$account->getName();
  } else {
    echo "Login failed";
  }
  $account->closeOtherSessions();
  
}
catch (PDOException $e)
{
  echo $e->getMessage().' in ' . $e->getFile().' on line ' . $e->getLine();
  die();
}
echo "Session Close Success";

var_dump($_SESSION); // 0