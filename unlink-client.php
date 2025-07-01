<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Contact.php";

    // Check if both contact_id and client_id are passed in the URL
    if (! isset($_GET['contact_id']) || ! isset($_GET['client_id'])) {
        header("Location: contacts.php");
        exit();
    }

    $contact_id = $_GET['contact_id'];
    $client_id  = $_GET['client_id'];

    // Optional: validate they exist
    $contact = get_contact_by_id($conn, $contact_id);
    $client  = get_client_by_id($conn, $client_id);

    if ($contact && $client) {
        var_dump($contact_id, $client_id);
        exit();
        // Perform unlink operation
        unlink_client($conn, [$client_id, $contact_id]);

        $sm = "Client Unlinked Successfully";
    } else {
        $sm = "No link found to unlink (already removed or incorrect ID)";

    }
    header("Location: contacts.php?success=" . urlencode($sm));
    exit();

} else {
    $em = "First login";
    header("Location: login.php?error=" . urlencode($em));
    exit();
}
