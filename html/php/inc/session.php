<?php
session_start();
session_regenerate_id(true);

$_SESSION["login"] = true;
$_SESSION["token"] = base64_encode(openssl_random_pseudo_bytes(48));

?>