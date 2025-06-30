<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    if (isset($_POST['clientName'])) {
        include "../DB_connection.php";
        function validate_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        function generate_client_code($conn)
        {
            do {
                $letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
                $numbers = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
                $code    = $letters . $numbers;

                $stmt = $conn->prepare("SELECT COUNT(*) FROM clients WHERE client_code = ?");
                $stmt->execute([$code]);
                $exists = $stmt->fetchColumn();
            } while ($exists > 0);

            return $code;
        }

        $clientName = validate_input($_POST['clientName']);

        if (empty($clientName)) {
            $em = "Client name is required";
            header("Location: ../add-user.php?error=$em");
            exit();
        } else {
             include "Model/User.php";
            $clientCode = generate_client_code($conn);
            $data = [$clientName, $clientCode];
            insert_client($conn, $data);

        }
    } else {
        $em = "Unknown error occured";
        header("Location: ../add-client.php?error=$em");
        exit();
    }
} else {
    $em = "First Login";
    header("Location: ../add-client.php?error=$em");
    exit();
}
