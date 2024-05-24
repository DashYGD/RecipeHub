<?php
session_start();

$_SESSION = array();

session_unset();
session_destroy();

setcookie('auth_token', '', time() - 3600, '/');

header("Location: ../../etusivu");
exit();
