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
	<title>Create Clients</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">

</head>
<body>
	<input type="checkbox" id="checkbox">
<?php include "inc/header.php"?>
	<div class="body">
		<?php include "inc/nav.php"?>
		<section class="section-1">
            <h4 class="title" >Create Client <a href="user.php">Clients</a></h4>
   <form class="form-1"
   method="POST"
   action="app/add-user.php">
   <?php if (isset($_GET['error'])) {?>
    <div class="danger" role="alert">
        <?php echo stripcslashes($_GET['error']); ?>
   </div>
<?php
}?>
<?php if (isset($_GET['success'])) {?>

    <div class="success" role="alert">
        <?php echo stripcslashes($_GET['success']); ?>
   </div>
<?php
}?>

    <div class="input-holder">
        <lable> Client Name </lable>
        <input type="text" name="clientName" class="input-1" placeholder="Client Name"><br><br>
    </div>

    <div class="input-holder">
        <lable> Client Code </lable>
         <input type="text" name="clientCode" class="input-1" value="Auto-generated" disabled><br><br>
    </div>

    <button class="edit-btn">Create</button>
   </form>
	</section>

	</div>
<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(3)");
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