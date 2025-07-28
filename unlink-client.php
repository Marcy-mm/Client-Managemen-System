<?php
session_start();
if (! isset($_SESSION['role'], $_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=" . urlencode("First login"));
    exit();
}

// 1) Grab and cast the IDs
$contact_id = isset($_GET['contact_id']) ? (int) $_GET['contact_id'] : 0;
$client_id  = isset($_GET['client_id']) ? (int) $_GET['client_id'] : 0;

if ($contact_id <= 0 || $client_id <= 0) {
    header("Location: contacts.php?error=" . urlencode("Invalid link parameters"));
    exit();
}

include "DB_connection.php";
include "app/Model/Contact.php";

// 2) Verify they exist
$contact = get_contact_by_id($conn, $contact_id);
$client  = get_clientid($conn, $client_id);

if (empty($contact) || $client === 0) {
    header("Location: contacts.php?error=" . urlencode("Contact or client not found"));
    exit();
}

// 3) Perform the unlink
$deleted = unlink_client($conn, [$client_id, $contact_id]);

if ($deleted > 0) {
    $msg = "Client unlinked successfully.";
    header("Location: linked-clients.php?contact_id=". $contact_id ."&success=" . urlencode($msg));
} else {
    $err = "No link found to unlink.";
    header("Location: linked-clients.php?contact_id&error=" . urlencode($err));
}
exit();
