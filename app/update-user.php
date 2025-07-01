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
        $clientName = validate_input($_POST['clientName']);
        $id = validate_input($_POST['id']);

        if (empty($clientName)) {
            $em = "Client name is required";
            header("Location: ../edit-client.php?error=$em&id=$id");
            exit();
        } else {

             include "Model/User.php";
            $data = [$clientName, $id];
            update_client($conn, $data, $id);
 $em = "Client Updated Successfully";
        header("Location: ../edit-client.php?success=$em&id=$id");
        exit();
        }
    
} 
else{
    $em = "Unknown error occured";
        header("Location: ../edit-client.php?error=$em&id=$id");
        exit();
        } 
}
else {
    $em = "First Login";
    header("Location: ../edit-client.php?error=$em");
    exit();
}
