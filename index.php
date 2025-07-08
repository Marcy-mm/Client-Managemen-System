<?php
    session_start();

	// Prevent back navigation after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

    if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    ?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<input type="checkbox" id="checkbox">
<?php include "inc/header.php"?>
	<div class="body">
		<?php include "inc/nav.php"?>
<section class="dashboard">
		<h1>WELCOME TO THE MARS</h1>
		<p>Client Management System</p>
	</section>

	</div>



 <script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(1)");
	active.classList.add("active");
</script>
</body>
</html>
<?php } else {
        $em = "First Login";
        header("Location: login.php?error=$em");
        exit();
}
?>