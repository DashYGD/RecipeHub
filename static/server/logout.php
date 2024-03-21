<?php
session_start();

$_SESSION = array();

session_destroy();

setcookie('auth_token', '', time() - 3600, '/');

header("Location: ../login");
exit();
