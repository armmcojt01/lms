<?php
require_once __DIR__ . '/../inc/config.php';
session_destroy();
header('Location: ' . BASE_URL . '/public/login.php');
exit;
?>
