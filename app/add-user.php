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
            $words = preg_split('/\s+/', strtoupper($name));
            $initials = '';

            // If the name has multiple words, get first letters
            if (count($words) > 1) {
                foreach ($words as $word) {
                    if (ctype_alpha($word[0])) {
                        $initials .= $word[0];
                    }
                }

                // Pad if less than 3
                if (strlen($initials) < 3) {
                    $pad = range('A', 'Z');
                    $i = 0;
                    while (strlen($initials) < 3) {
                        $initials .= $pad[$i++];
                    }
                }

                return substr($initials, 0, 3);
            } else {
                // If it's a single word like Bankofnamibia
                $singleWord = preg_replace('/[^A-Z]/', '', strtoupper($name));
                $initials = substr($singleWord, 0, 3);

                // Pad if less than 3
                if (strlen($initials) < 3) {
                    $pad = range('A', 'Z');
                    $i = 0;
                    while (strlen($initials) < 3) {
                        $initials .= $pad[$i++];
                    }
                }

                return strtoupper($initials);
            }
        }


    function get_next_suffix($conn)
        {
            $stmt = $conn->prepare("SELECT client_code FROM clients");
            $stmt->execute();
            $codes = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $max = 0;
            foreach ($codes as $code) {
                $num = (int) substr($code, -3); // Get last 3 digits
                if ($num > $max) {
                    $max = $num;
                }
            }

            return str_pad($max + 1, 3, '0', STR_PAD_LEFT);
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

        $prefix = generate_alpha_part($clientName);
        $suffix = get_next_suffix($conn);
        $clientCode = $prefix . $suffix;
        $data = [$clientName, $clientCode];
        if (insert_client($conn, $data)) {
            $sm = "Client Created Successfully. Client Code: $clientCode";
            header("Location: ../add-client.php?success=" . urlencode($sm));
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
