<?php

session_start();
session_unset();
session_destroy();
$em = "First Login";
header("Location: login.php?error=$em");

exit();
