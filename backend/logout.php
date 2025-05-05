<?php
session_start();
session_destroy();
header('Location: ../common/login.php');
exit();
?>