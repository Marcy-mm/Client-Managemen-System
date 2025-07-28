<?php
session_start();

if (! isset($_SESSION['role'], $_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=" . urlencode("First login"));
    exit();
}

$contact_id = isset($_GET['contact_id']) ? (int) $_GET['contact_id'] : 0;
$client_id  = isset($_GET['client_id']) ? (int) $_GET['client_id'] : 0;

if ($contact_id <= 0 || $client_id <= 0) {
    header("Location: user.php?error=" . urlencode("Invalid unlink parameters"));
    exit();
}

include "DB_connection.php";
include "app/Model/User.php";
include "app/Model/Contact.php";

$contact = get_contact_by_id($conn, $contact_id);
$client  = get_client_by_id($conn, $client_id);

if (empty($contact) || empty($client)) {
    header("Location: user.php?error=" . urlencode("Contact or client not found"));
    exit();
}

$deleted = unlink_client($conn, [$client_id, $contact_id]);

if ($deleted > 0) {
    header("Location: linked-contacts.php?client_id=" . $client_id . "&success=" . urlencode("Contact unlinked from client successfully."));
} else {
    header("Location: linked-contacts.php?client_id=" . $client_id . "error=" . urlencode("No link found to unlink."));
}
exit();
