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
        function generate_alpha_part($name)
        {
            $name = strtoupper($name);
            preg_match_all('/\b[A-Z]/', $name, $matches);

            $initials = implode('', $matches[0]);

            if (strlen($initials) >= 3) {
                return substr($initials, 0, 3);
            } elseif (strlen($initials) > 0) {
                // Pad with A-Z if initials are fewer than 3
                $pad = range('A', 'Z');
                $i   = 0;
                while (strlen($initials) < 3) {
                    $initials .= $pad[$i++];
                }
                return substr($initials, 0, 3);
            } else {
                // Fallback: take first 3 letters of the name (only alpha)
                $name = preg_replace('/[^A-Z]/', '', $name);
                if (strlen($name) >= 3) {
                    return substr($name, 0, 3);
                } else {
                    $pad = range('A', 'Z');
                    $i   = 0;
                    while (strlen($name) < 3) {
                        $name .= $pad[$i++];
                    }
                    return substr($name, 0, 3);
                }
            }
        }

        function generate_client_code($conn, $clientName)
        {
            $prefix = generate_alpha_part($clientName);

            $stmt = $conn->prepare("SELECT client_code FROM clients WHERE client_code LIKE ?");
            $stmt->execute([$prefix . '%']);
            $existingCodes = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $maxSuffix = 0;
            foreach ($existingCodes as $code) {
                $suffix = (int) substr($code, 3);
                if ($suffix > $maxSuffix) {
                    $maxSuffix = $suffix;
                }
            }

            $nextSuffix = str_pad($maxSuffix + 1, 3, '0', STR_PAD_LEFT);
            return $prefix . $nextSuffix;
        }

        $clientName = validate_input($_POST['clientName']);

        if (empty($clientName)) {
            $em = "Client name is required";
            header("Location: ../add-client.php?error=$em");
            exit();
        }

        include "Model/User.php";

        if (client_exists($conn, $clientName)) {
            $em = "A client with this name already exists.";
            header("Location: ../add-client.php?error=" . urlencode($em));
            exit();
        }

        $clientCode = generate_client_code($conn, $clientName);
        $data       = [$clientName, $clientCode];
        if (insert_client($conn, $data)) {
            $em = "User Created Successfully. Client Code: $clientCode";
            //urlencode implemented to prevent incorrect query being passed in the URL
            header("Location: ../add-client.php?success=" . urlencode($em));
            exit();
        } else {
            $em = "Failed to insert client.";
            header("Location: ../add-client.php?error=" . urlencode($em));
            exit();
        }

    } else {
        $em = "Unknown error occurred";
        header("Location: ../add-client.php?error=" . urlencode($em));
        exit();
    }
} else {
    $em = "First Login";
    header("Location: ../add-client.php?error=" . urlencode($em));
    exit();
}
