<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/User.php";

    if (! isset($_GET['id'])) {
        header("Location: user.php");
        exit();
    }

    $id     = $_GET['id'];
    $client = get_client_by_id($conn, $id);

    if ($client == 0) {
        header("Location: user.php");
        exit();
    }
    $id   = $_GET['id'];
    $user = get_client_by_id($conn, $id);

    if ($client == 0) {
        header("Location: user.php");
        exit();
    }
    $data = array($id);
    delete_client($conn, $data);

    $sm = "Deleted Successfully";
    header("Location: user.php?success=$sm");
} else {
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
