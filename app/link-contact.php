<?php
session_start();
if (isset($_SESSION['role'], $_SESSION['id'])) {
    if (isset($_POST['client_id'], $_POST['contact_id'])) {
        include "../DB_connection.php";
        include "Model/Contact.php";

        $client_id  = $_POST['client_id'];
        $contact_id = $_POST['contact_id'];

        // Check if already linked
        if (is_contact_linked($conn, $contact_id, $client_id)) {
            header("Location: ../link-contact.php?error=" . urlencode("This contact is already linked to this client."));
            exit();
        }

        // Insert into linking table
        if (link_contact_to_client($conn, $client_id, $contact_id)) {
            header("Location: ../link-contact.php?success=" . urlencode("Contact successfully linked to client."));
            exit();
        } else {
            header("Location: ../link-contact.php?error=" . urlencode("Failed to link contact."));
            exit();
        }

    } else {
        header("Location: ../link-contact.php?error=" . urlencode("Missing input."));
        exit();
    }
} else {
    header("Location: ../login.php?error=" . urlencode("First login"));
    exit();
}
