<?php
if (isset($_POST['user_name']) && isset($_POST['password'])){
  
	function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
}else{
    $em="Unknown error occured";
   header("Location: ../login.php?error=$em");
   exit();
}

