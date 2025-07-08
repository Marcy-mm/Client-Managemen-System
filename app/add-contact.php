<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email'])) {

        function validate_input(string $data): string
        {
            $data = trim($data);
            $data = stripslashes($data);
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }

        $name    = validate_input($_POST['name']);
        $surname = validate_input($_POST['surname']);
        $email   = validate_input($_POST['email']);

        if (empty($name)) {
            $em = "Client name is required";
            header("Location: ../add-contact.php?error=$em&name=$name&surname=$surname&email=$email");
            exit();
        } else if (empty($surname)) {
            $em = "Surname is required";
            header("Location: ../add-contact.php?error=$em&name=$name&surname=$surname&email=$email");
            exit();
        } else if (empty($email)) {
            $em = "Email is required";
            header("Location: ../add-contact.php?error=$em&name=$name&surname=$surname&email=$email");
            exit();
        }
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address.";
            header("Location: ../add-contact.php?error=" . urlencode($error));
            exit();
        }

        include "../DB_connection.php";
        include "Model/Contact.php";

        $stmt = $conn->prepare("SELECT COUNT(*) FROM contacts WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $em = "Contact already exists with this email";
            header("Location: ../add-contact.php?error=" . urlencode($em) . "&name=$name&surname=$surname");
            exit();

        }
        insert_contact($conn, [$name, $surname, $email]);
        $msg = "Contact created successfully.";
        header("Location: ../add-contact.php?success=" . urlencode($msg));
        exit();

    } else {
        $em = "Unknown error occured";
        header("Location: ../add-contact.php?error=$em&name=$name&surname=$surname&email=$email");
        exit();
    }
} else {
    $em = "First Login";
    header("Location: ../add-contact.php?error=$em");
    exit();
}
