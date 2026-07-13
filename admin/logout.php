<?php
require_once __DIR__ . '/../includes/functions.php';
app_session_start();
session_destroy();
redirect_to('/admin/login.php');
?>
